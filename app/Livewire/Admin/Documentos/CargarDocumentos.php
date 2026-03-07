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

    // Guarda el id del alumno seleccionado.
    public ?int $alumno_id = null;

    // Guarda el nombre completo del alumno para mostrarlo en la vista.
    public string $nombreAlumno = '';

    // Propiedades para recibir los archivos temporales que se cargan desde el formulario.
    public $curp_archivo = null;
    public $acta_nacimiento_archivo = null;
    public $certificado_estudios_archivo = null;

    // Propiedades para guardar la ruta de los archivos ya almacenados.
    public ?string $curp_guardado = null;
    public ?string $acta_nacimiento_guardado = null;
    public ?string $certificado_estudios_guardado = null;

    // Reglas de validación para cada documento.
    protected function rules(): array
    {
        return [
            'curp_archivo' => 'nullable|file|mimes:pdf|max:1024',
            'acta_nacimiento_archivo' => 'nullable|file|mimes:pdf|max:1024',
            'certificado_estudios_archivo' => 'nullable|file|mimes:pdf|max:1024',
        ];
    }

    // Mensajes personalizados para mostrar errores de validación más claros.
    protected array $messages = [
        'curp_archivo.mimes' => 'La CURP debe estar en formato PDF.',
        'acta_nacimiento_archivo.mimes' => 'El acta de nacimiento debe estar en formato PDF.',
        'certificado_estudios_archivo.mimes' => 'El certificado de estudios debe estar en formato PDF.',
        'curp_archivo.max' => 'La CURP no debe exceder 1 MB.',
        'acta_nacimiento_archivo.max' => 'El acta de nacimiento no debe exceder 1 MB.',
        'certificado_estudios_archivo.max' => 'El certificado de estudios no debe exceder 1 MB.',
    ];

    // Este método se ejecuta cuando se recibe el evento para abrir el modal.
    // Aquí se cargan los datos del alumno y sus documentos guardados.
    #[On('abrir-modal-documentos-livewire')]
    public function abrirModalDocumentos($id = null): void
    {
        // Si no llega un id, no continúa el proceso.
        if (!$id) {
            return;
        }

        // Busca al alumno junto con su relación de documentación.
        $alumno = Alumno::with('documentacion')->findOrFail($id);

        // Limpia errores de validación anteriores.
        $this->resetValidation();

        // Limpia archivos temporales cargados anteriormente.
        $this->reset([
            'curp_archivo',
            'acta_nacimiento_archivo',
            'certificado_estudios_archivo',
        ]);

        // Asigna el id y el nombre completo del alumno seleccionado.
        $this->alumno_id = $alumno->id;
        $this->nombreAlumno = trim(
            ($alumno->nombre ?? '') . ' ' .
                ($alumno->apellido_paterno ?? '') . ' ' .
                ($alumno->apellido_materno ?? '')
        );

        // Carga las rutas de los documentos ya guardados, si existen.
        $this->curp_guardado = $alumno->documentacion->url_curp ?? null;
        $this->acta_nacimiento_guardado = $alumno->documentacion->url_acta_nacimiento ?? null;
        $this->certificado_estudios_guardado = $alumno->documentacion->url_certificado_estudios ?? null;

        // Notifica a la vista que ya terminó de cargar la información.
        $this->dispatch('documentos-cargados');
    }

    // Construye el nombre completo del alumno.
    protected function construirNombreAlumno(Alumno $alumno): string
    {
        return trim(
            ($alumno->nombre ?? '') . ' ' .
                ($alumno->apellido_paterno ?? '') . ' ' .
                ($alumno->apellido_materno ?? '')
        );
    }

    // Obtiene la fecha de nacimiento en formato útil para el nombre del archivo.
    // Si no hay fecha o falla el formato, devuelve SIN_FECHA.
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

    // Genera un nombre de archivo limpio, ordenado y en mayúsculas.
    protected function generarNombreArchivo(string $prefijo, Alumno $alumno, $archivo): string
    {
        $nombreAlumno = $this->construirNombreAlumno($alumno);
        $fechaNacimiento = $this->obtenerFechaNacimientoParaArchivo($alumno);

        $base = $prefijo . '_' . $nombreAlumno . '_' . $fechaNacimiento;
        $base = Str::upper(Str::slug($base, '_'));
        $extension = $archivo->getClientOriginalExtension();

        return $base . '.' . $extension;
    }

    // Busca la documentación del alumno.
    // Si no existe, crea un registro nuevo con valores vacíos.
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

    // Guarda el documento según el tipo recibido.
    public function guardarDocumento(string $tipo): void
    {
        // Busca al alumno actual.
        $alumno = Alumno::find($this->alumno_id);

        // Si no existe el alumno, detiene el proceso.
        if (!$alumno) {
            return;
        }

        // Obtiene o crea el registro de documentación del alumno.
        $documentacion = $this->obtenerDocumentacion($alumno->id);

        // Guarda el archivo de CURP.
        if ($tipo === 'curp') {
            $this->validateOnly('curp_archivo');

            if ($this->curp_archivo) {
                // Si ya existía un archivo anterior, lo elimina antes de guardar el nuevo.
                if (!empty($documentacion->url_curp) && Storage::disk('public')->exists($documentacion->url_curp)) {
                    Storage::disk('public')->delete($documentacion->url_curp);
                }

                // Genera el nombre del archivo y lo guarda en la carpeta correspondiente.
                $nombreArchivo = $this->generarNombreArchivo('CURP', $alumno, $this->curp_archivo);

                $ruta = $this->curp_archivo->storeAs(
                    'documentos/alumnos/curp',
                    $nombreArchivo,
                    'public'
                );

                // Guarda la ruta en la base de datos.
                $documentacion->url_curp = $ruta;
                $documentacion->save();

                // Actualiza las propiedades del componente.
                $this->curp_guardado = $ruta;
                $this->curp_archivo = null;
            }
        }

        // Guarda el archivo del acta de nacimiento.
        if ($tipo === 'acta_nacimiento') {
            $this->validateOnly('acta_nacimiento_archivo');

            if ($this->acta_nacimiento_archivo) {
                // Elimina el archivo anterior si ya existía.
                if (!empty($documentacion->url_acta_nacimiento) && Storage::disk('public')->exists($documentacion->url_acta_nacimiento)) {
                    Storage::disk('public')->delete($documentacion->url_acta_nacimiento);
                }

                // Genera el nombre y guarda el archivo.
                $nombreArchivo = $this->generarNombreArchivo('ACTA_NACIMIENTO', $alumno, $this->acta_nacimiento_archivo);

                $ruta = $this->acta_nacimiento_archivo->storeAs(
                    'documentos/alumnos/actas',
                    $nombreArchivo,
                    'public'
                );

                // Guarda la ruta en la base de datos.
                $documentacion->url_acta_nacimiento = $ruta;
                $documentacion->save();

                // Actualiza las propiedades del componente.
                $this->acta_nacimiento_guardado = $ruta;
                $this->acta_nacimiento_archivo = null;
            }
        }

        // Guarda el archivo del certificado de estudios.
        if ($tipo === 'certificado_estudios') {
            $this->validateOnly('certificado_estudios_archivo');

            if ($this->certificado_estudios_archivo) {
                // Elimina el archivo anterior si ya existía.
                if (!empty($documentacion->url_certificado_estudios) && Storage::disk('public')->exists($documentacion->url_certificado_estudios)) {
                    Storage::disk('public')->delete($documentacion->url_certificado_estudios);
                }

                // Genera el nombre y guarda el archivo.
                $nombreArchivo = $this->generarNombreArchivo('CERTIFICADO_ESTUDIOS', $alumno, $this->certificado_estudios_archivo);

                $ruta = $this->certificado_estudios_archivo->storeAs(
                    'documentos/alumnos/certificados',
                    $nombreArchivo,
                    'public'
                );

                // Guarda la ruta en la base de datos.
                $documentacion->url_certificado_estudios = $ruta;
                $documentacion->save();

                // Actualiza las propiedades del componente.
                $this->certificado_estudios_guardado = $ruta;
                $this->certificado_estudios_archivo = null;
            }
        }

        // Muestra un mensaje de éxito al terminar el guardado.
        $this->dispatch('swal', [
            'title' => 'Documento guardado correctamente',
            'icon' => 'success',
            'position' => 'top-end',
        ]);

        // Notifica a la vista para refrescar el estado de los documentos.
        $this->dispatch('documentos-cargados');
    }

    // Elimina el documento según el tipo recibido.
    public function eliminarDocumento(string $tipo): void
    {
        // Busca el registro de documentación del alumno actual.
        $documentacion = Documentacion::where('alumno_id', $this->alumno_id)->first();

        // Si no existe documentación, termina el proceso.
        if (!$documentacion) {
            return;
        }

        // Elimina la CURP.
        if ($tipo === 'curp') {
            if (!empty($documentacion->url_curp) && Storage::disk('public')->exists($documentacion->url_curp)) {
                Storage::disk('public')->delete($documentacion->url_curp);
            }

            $documentacion->url_curp = null;
            $documentacion->save();

            $this->curp_guardado = null;
            $this->curp_archivo = null;
        }

        // Elimina el acta de nacimiento.
        if ($tipo === 'acta_nacimiento') {
            if (!empty($documentacion->url_acta_nacimiento) && Storage::disk('public')->exists($documentacion->url_acta_nacimiento)) {
                Storage::disk('public')->delete($documentacion->url_acta_nacimiento);
            }

            $documentacion->url_acta_nacimiento = null;
            $documentacion->save();

            $this->acta_nacimiento_guardado = null;
            $this->acta_nacimiento_archivo = null;
        }

        // Elimina el certificado de estudios.
        if ($tipo === 'certificado_estudios') {
            if (!empty($documentacion->url_certificado_estudios) && Storage::disk('public')->exists($documentacion->url_certificado_estudios)) {
                Storage::disk('public')->delete($documentacion->url_certificado_estudios);
            }

            $documentacion->url_certificado_estudios = null;
            $documentacion->save();

            $this->certificado_estudios_guardado = null;
            $this->certificado_estudios_archivo = null;
        }

        // Muestra un mensaje de éxito al eliminar.
        $this->dispatch('swal', [
            'title' => 'Documento eliminado correctamente',
            'icon' => 'success',
            'position' => 'top-end',
        ]);

        // Notifica a la vista para actualizar la información.
        $this->dispatch('documentos-cargados');
    }

    // Cierra el modal y limpia todas las propiedades del componente.
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

    // Devuelve la URL completa del archivo para previsualizarlo o descargarlo.
    public function obtenerUrlDocumento(?string $ruta): string
    {
        return $ruta ? asset('storage/' . $ruta) : '#';
    }

    // Devuelve solamente el nombre del archivo a partir de la ruta guardada.
    public function obtenerNombreArchivo(?string $ruta): string
    {
        return $ruta ? basename($ruta) : '';
    }

    public function render()
    {
        return view('livewire.admin.documentos.cargar-documentos');
    }
}
