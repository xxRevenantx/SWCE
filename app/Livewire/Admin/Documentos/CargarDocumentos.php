<?php

namespace App\Livewire\Admin\Documentos;

use App\Models\Alumno;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

class CargarDocumentos extends Component
{
    use WithFileUploads;

    public ?int $alumno_id = null;
    public string $nombreAlumno = '';

    public $curp_archivo = null;
    public $acta_nacimiento_archivo = null;
    public $certificado_estudios_archivo = null;

    public ?string $curp_guardado = null;
    public ?string $acta_nacimiento_guardado = null;
    public ?string $certificado_estudios_guardado = null;

    public bool $cargandoDatos = false;

    protected function rules(): array
    {
        return [
            'curp_archivo' => 'nullable|file|mimes:pdf|max:5120',
            'acta_nacimiento_archivo' => 'nullable|file|mimes:pdf|max:5120',
            'certificado_estudios_archivo' => 'nullable|file|mimes:pdf|max:5120',
        ];
    }

    protected array $messages = [
        'curp_archivo.mimes' => 'La CURP debe estar en formato PDF.',
        'acta_nacimiento_archivo.mimes' => 'El acta de nacimiento debe estar en formato PDF.',
        'certificado_estudios_archivo.mimes' => 'El certificado de estudios debe estar en formato PDF.',
        'curp_archivo.max' => 'La CURP no debe exceder 5 MB.',
        'acta_nacimiento_archivo.max' => 'El acta de nacimiento no debe exceder 5 MB.',
        'certificado_estudios_archivo.max' => 'El certificado de estudios no debe exceder 5 MB.',
    ];

    #[On('abrir-modal-documentos-livewire')]
    public function abrirModalDocumentos($id): void
    {
        if (!$id) {
            return;
        }

        $alumno = Alumno::findOrFail($id);

        $this->resetValidation();

        $this->reset([
            'curp_archivo',
            'acta_nacimiento_archivo',
            'certificado_estudios_archivo',
        ]);

        $this->alumno_id = $alumno->id;
        $this->nombreAlumno = trim(
            ($alumno->nombre ?? '') . ' ' .
                ($alumno->apellido_paterno ?? '') . ' ' .
                ($alumno->apellido_materno ?? '')
        );

        $this->curp_guardado = $alumno->curp_documento;
        $this->acta_nacimiento_guardado = $alumno->acta_nacimiento_documento;
        $this->certificado_estudios_guardado = $alumno->certificado_estudios_documento;

        $this->dispatch('documentos-cargados');
    }

    public function guardarDocumento(string $tipo): void
    {
        $alumno = Alumno::find($this->alumno_id);

        if (!$alumno) {
            return;
        }

        if ($tipo === 'curp') {
            $this->validateOnly('curp_archivo');

            if ($this->curp_archivo) {
                $ruta = $this->curp_archivo->store('documentos/alumnos/curp', 'public');
                $alumno->curp_documento = $ruta;
                $alumno->save();

                $this->curp_guardado = $ruta;
                $this->curp_archivo = null;
            }
        }

        if ($tipo === 'acta_nacimiento') {
            $this->validateOnly('acta_nacimiento_archivo');

            if ($this->acta_nacimiento_archivo) {
                $ruta = $this->acta_nacimiento_archivo->store('documentos/alumnos/actas', 'public');
                $alumno->acta_nacimiento_documento = $ruta;
                $alumno->save();

                $this->acta_nacimiento_guardado = $ruta;
                $this->acta_nacimiento_archivo = null;
            }
        }

        if ($tipo === 'certificado_estudios') {
            $this->validateOnly('certificado_estudios_archivo');

            if ($this->certificado_estudios_archivo) {
                $ruta = $this->certificado_estudios_archivo->store('documentos/alumnos/certificados', 'public');
                $alumno->certificado_estudios_documento = $ruta;
                $alumno->save();

                $this->certificado_estudios_guardado = $ruta;
                $this->certificado_estudios_archivo = null;
            }
        }

        $this->dispatch('swal', [
            'title' => 'Documento guardado correctamente',
            'icon' => 'success',
            'position' => 'top-end',
        ]);
    }

    public function eliminarDocumento(string $tipo): void
    {
        $alumno = Alumno::find($this->alumno_id);

        if (!$alumno) {
            return;
        }

        if ($tipo === 'curp') {
            $alumno->curp_documento = null;
            $alumno->save();
            $this->curp_guardado = null;
            $this->curp_archivo = null;
        }

        if ($tipo === 'acta_nacimiento') {
            $alumno->acta_nacimiento_documento = null;
            $alumno->save();
            $this->acta_nacimiento_guardado = null;
            $this->acta_nacimiento_archivo = null;
        }

        if ($tipo === 'certificado_estudios') {
            $alumno->certificado_estudios_documento = null;
            $alumno->save();
            $this->certificado_estudios_guardado = null;
            $this->certificado_estudios_archivo = null;
        }

        $this->dispatch('swal', [
            'title' => 'Documento eliminado correctamente',
            'icon' => 'success',
            'position' => 'top-end',
        ]);
    }

    public function cerrarModal(): void
    {
        $this->reset([
            'alumno_id',
            'nombreAlumno',
            'curp_archivo',
            'acta_nacimiento_archivo',
            'certificado_estudios_archivo',
            'curp_guardado',
            'acta_nacimiento_guardado',
            'certificado_estudios_guardado',
            'cargandoDatos',
        ]);

        $this->resetValidation();
    }

    public function obtenerUrlDocumento(?string $ruta): string
    {
        return $ruta ? asset('storage/' . $ruta) : '#';
    }

    public function obtenerNombreArchivo(?string $ruta): string
    {
        return $ruta ? basename($ruta) : '';
    }

    public function render()
    {
        return view('livewire.admin.documentos.cargar-documentos');
    }
}
