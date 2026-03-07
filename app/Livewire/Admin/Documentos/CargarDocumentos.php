<?php

namespace App\Livewire\Admin\Documentos;

use App\Models\Alumno;
use App\Models\Documentacion;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
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
    public function abrirModalDocumentos($id = null): void
    {
        if (!$id) {
            return;
        }

        $alumno = Alumno::with('documentacion')->findOrFail($id);

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

        $this->curp_guardado = $alumno->documentacion->url_curp ?? null;
        $this->acta_nacimiento_guardado = $alumno->documentacion->url_acta_nacimiento ?? null;
        $this->certificado_estudios_guardado = $alumno->documentacion->url_certificado_estudios ?? null;

        $this->dispatch('documentos-cargados');
    }

    protected function construirNombreAlumno(Alumno $alumno): string
    {
        return trim(
            ($alumno->nombre ?? '') . ' ' .
            ($alumno->apellido_paterno ?? '') . ' ' .
            ($alumno->apellido_materno ?? '')
        );
    }

    protected function obtenerFechaNacimientoParaArchivo(Alumno $alumno): string
    {
        if (empty($alumno->fecha_nacimiento)) {
            return 'SIN_FECHA';
        }

        try {
            return Carbon::parse($alumno->fecha_nacimiento)->format('Y_m_d');
        } catch (\Throwable $e) {
            return 'SIN_FECHA';
        }
    }

    protected function generarNombreArchivo(string $prefijo, Alumno $alumno, $archivo): string
    {
        $nombreAlumno = $this->construirNombreAlumno($alumno);
        $fechaNacimiento = $this->obtenerFechaNacimientoParaArchivo($alumno);

        $base = $prefijo . '_' . $nombreAlumno . '_' . $fechaNacimiento;
        $base = Str::upper(Str::slug($base, '_'));
        $extension = $archivo->getClientOriginalExtension();

        return $base . '.' . $extension;
    }

    protected function obtenerDocumentacion(int $alumnoId): Documentacion
    {
        return Documentacion::firstOrCreate(
            ['alumno_id' => $alumnoId],
            [
                'url_curp' => null,
                'url_acta_nacimiento' => null,
                'url_certificado_estudios' => null,
            ]
        );
    }

    public function guardarDocumento(string $tipo): void
    {
        $alumno = Alumno::find($this->alumno_id);

        if (!$alumno) {
            return;
        }

        $documentacion = $this->obtenerDocumentacion($alumno->id);

        if ($tipo === 'curp') {
            $this->validateOnly('curp_archivo');

            if ($this->curp_archivo) {
                if (!empty($documentacion->url_curp) && Storage::disk('public')->exists($documentacion->url_curp)) {
                    Storage::disk('public')->delete($documentacion->url_curp);
                }

                $nombreArchivo = $this->generarNombreArchivo('CURP', $alumno, $this->curp_archivo);

                $ruta = $this->curp_archivo->storeAs(
                    'documentos/alumnos/curp',
                    $nombreArchivo,
                    'public'
                );

                $documentacion->url_curp = $ruta;
                $documentacion->save();

                $this->curp_guardado = $ruta;
                $this->curp_archivo = null;
            }
        }

        if ($tipo === 'acta_nacimiento') {
            $this->validateOnly('acta_nacimiento_archivo');

            if ($this->acta_nacimiento_archivo) {
                if (!empty($documentacion->url_acta_nacimiento) && Storage::disk('public')->exists($documentacion->url_acta_nacimiento)) {
                    Storage::disk('public')->delete($documentacion->url_acta_nacimiento);
                }

                $nombreArchivo = $this->generarNombreArchivo('ACTA_NACIMIENTO', $alumno, $this->acta_nacimiento_archivo);

                $ruta = $this->acta_nacimiento_archivo->storeAs(
                    'documentos/alumnos/actas',
                    $nombreArchivo,
                    'public'
                );

                $documentacion->url_acta_nacimiento = $ruta;
                $documentacion->save();

                $this->acta_nacimiento_guardado = $ruta;
                $this->acta_nacimiento_archivo = null;
            }
        }

        if ($tipo === 'certificado_estudios') {
            $this->validateOnly('certificado_estudios_archivo');

            if ($this->certificado_estudios_archivo) {
                if (!empty($documentacion->url_certificado_estudios) && Storage::disk('public')->exists($documentacion->url_certificado_estudios)) {
                    Storage::disk('public')->delete($documentacion->url_certificado_estudios);
                }

                $nombreArchivo = $this->generarNombreArchivo('CERTIFICADO_ESTUDIOS', $alumno, $this->certificado_estudios_archivo);

                $ruta = $this->certificado_estudios_archivo->storeAs(
                    'documentos/alumnos/certificados',
                    $nombreArchivo,
                    'public'
                );

                $documentacion->url_certificado_estudios = $ruta;
                $documentacion->save();

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
        $documentacion = Documentacion::where('alumno_id', $this->alumno_id)->first();

        if (!$documentacion) {
            return;
        }

        if ($tipo === 'curp') {
            if (!empty($documentacion->url_curp) && Storage::disk('public')->exists($documentacion->url_curp)) {
                Storage::disk('public')->delete($documentacion->url_curp);
            }

            $documentacion->url_curp = null;
            $documentacion->save();

            $this->curp_guardado = null;
            $this->curp_archivo = null;
        }

        if ($tipo === 'acta_nacimiento') {
            if (!empty($documentacion->url_acta_nacimiento) && Storage::disk('public')->exists($documentacion->url_acta_nacimiento)) {
                Storage::disk('public')->delete($documentacion->url_acta_nacimiento);
            }

            $documentacion->url_acta_nacimiento = null;
            $documentacion->save();

            $this->acta_nacimiento_guardado = null;
            $this->acta_nacimiento_archivo = null;
        }

        if ($tipo === 'certificado_estudios') {
            if (!empty($documentacion->url_certificado_estudios) && Storage::disk('public')->exists($documentacion->url_certificado_estudios)) {
                Storage::disk('public')->delete($documentacion->url_certificado_estudios);
            }

            $documentacion->url_certificado_estudios = null;
            $documentacion->save();

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
