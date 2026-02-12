<?php

namespace App\Livewire\Admin\Profesor;

use Livewire\Component;
use Livewire\WithFileUploads;

use App\Models\Profesor;
use App\Models\User;
use App\Services\CurpService;

class CrearProfesor extends Component
{
    use WithFileUploads;

    public $usuarios;

    public $user_id;
    public $CURP;
    public $foto;
    public $nombre;
    public $apellido_paterno;
    public $apellido_materno;
    public $telefono;
    public $perfil;
    public $color;
    public $status;

    public $usuario_email;

    public $datosCurp;

    /** modo pruebas CURP */
    public bool $curpModoPruebas = false;

    public function mount()
    {
        // Detecto si el service está en modo pruebas
        $service = app(CurpService::class);
        $this->curpModoPruebas = $service->esModoPruebas();

        $this->usuarios = User::role('Profesor')
            ->whereNotIn('id', Profesor::pluck('user_id'))
            ->where('status', "true")
            ->orderBy('id', 'desc')
            ->get();
    }

    public function updated($propertyName)
    {
        if ($propertyName === 'CURP') {

            // Normalizo y quito espacios y paso a MAYÚSCULAS
            $this->CURP = strtoupper(preg_replace('/\s+/', '', (string) $this->CURP));

            // Si aún no tiene 18 caracteres, limpio y salgo
            if (strlen((string) $this->CURP) < 18) {
                $this->reset(['datosCurp', 'nombre', 'apellido_paterno', 'apellido_materno']);
                return;
            }

            // Valido formato de CURP antes de llamar al servicio
            $patronCurp = '/^[A-Z]{4}\d{6}[HM][A-Z]{5}[A-Z0-9]\d$/';
            if (!preg_match($patronCurp, (string) $this->CURP)) {
                $this->dispatch('swal', [
                    'title' => 'Formato de CURP inválido.',
                    'icon' => 'warning',
                    'position' => 'top-end',
                ]);
                return;
            }

            // Refresco usuarios
            $this->usuarios = User::role('Profesor')
                ->whereNotIn('id', Profesor::pluck('user_id'))
                ->where('status', "true")
                ->orderBy('id', 'desc')
                ->get();

            // Email del usuario si ya eligieron uno
            $this->usuario_email = $this->user_id
                ? User::where('id', $this->user_id)->value('email')
                : null;

            // Consulto al servicio
            /** @var CurpService $servicio */
            $servicio = app(CurpService::class);

            // Actualizo bandera por si cambiaste token
            $this->curpModoPruebas = $servicio->esModoPruebas();

            $this->datosCurp = $servicio->obtenerDatosPorCurp($this->CURP);

            // Manejo de respuesta adaptado a tu estructura real: response.Solicitante
            if (!($this->datosCurp['error'] ?? true)) {

                $info = data_get($this->datosCurp, 'response.Solicitante')
                    ?? data_get($this->datosCurp, 'response.Soliciante')
                    ?? null;

                if (is_array($info)) {
                    $this->nombre = $info['Nombres'] ?? '';
                    $this->apellido_paterno = $info['ApellidoPaterno'] ?? '';
                    $this->apellido_materno = $info['ApellidoMaterno'] ?? '';
                    return;
                }
            }

            // Si no trajo datos, limpio y muestro alerta
            $this->reset(['datosCurp', 'nombre', 'apellido_paterno', 'apellido_materno']);

            $this->dispatch('swal', [
                'title' => 'Este CURP no se encuentra en RENAPO.',
                'icon' => 'error',
                'position' => 'top-end',
            ]);
        }
    }

    public function crearProfesor()
    {
        $this->validate([
            'user_id' => 'required|unique:profesores,user_id',
            'CURP' => 'required|string|size:18|unique:profesores,CURP',
            'foto' => 'nullable|image|max:2048|mimes:jpeg,jpg,png',
            'nombre' => 'required|string|max:255',
            'apellido_paterno' => 'required|string|max:255',
            'apellido_materno' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:10',
            'perfil' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:255',
        ], [
            'user_id.required' => 'El campo usuario es obligatorio.',
            'user_id.unique' => 'El usuario ya está registrado como profesor.',
            'CURP.required' => 'El campo CURP es obligatorio.',
            'CURP.string' => 'El CURP debe ser una cadena de texto.',
            'CURP.size' => 'El CURP debe tener exactamente 18 caracteres.',
            'CURP.unique' => 'El CURP ya está registrado.',
            'foto.image' => 'El archivo debe ser una imagen.',
            'foto.max' => 'La imagen no debe exceder 2 MB.',
            'foto.mimes' => 'La imagen debe ser de tipo jpeg, jpg o png.',
            'nombre.required' => 'El campo nombre es obligatorio.',
            'nombre.string' => 'El nombre debe ser una cadena de texto.',
            'nombre.max' => 'El nombre no debe exceder 255 caracteres.',
            'apellido_paterno.required' => 'El campo apellido paterno es obligatorio.',
            'apellido_paterno.string' => 'El apellido paterno debe ser una cadena de texto.',
            'apellido_paterno.max' => 'El apellido paterno no debe exceder 255 caracteres.',
            'apellido_materno.string' => 'El apellido materno debe ser una cadena de texto.',
            'apellido_materno.max' => 'El apellido materno no debe exceder 255 caracteres.',
            'telefono.string' => 'El teléfono debe ser una cadena de texto.',
            'telefono.max' => 'El teléfono no debe exceder 10 caracteres.',
            'perfil.string' => 'El perfil debe ser una cadena de texto.',
            'perfil.max' => 'El perfil no debe exceder 255 caracteres.',
        ]);

        $datos = [];

        // Validación extra de la imagen
        if ($this->foto) {

            if (method_exists($this->foto, 'getError') && $this->foto->getError()) {
                $this->dispatch('swal', [
                    'title' => 'Error al subir la imagen.',
                    'text' => 'Vuelve a intentarlo con otro archivo.',
                    'icon' => 'error',
                    'position' => 'top-end',
                ]);
                return;
            }

            $mime = $this->foto->getMimeType();
            $permitidos = ['image/jpeg', 'image/png'];

            if (!in_array($mime, $permitidos, true)) {
                $this->dispatch('swal', [
                    'title' => 'Formato no permitido.',
                    'text' => 'Solo se aceptan imágenes JPEG o PNG.',
                    'icon' => 'warning',
                    'position' => 'top-end',
                ]);
                return;
            }

            $realPath = $this->foto->getRealPath();
            $info = @getimagesize($realPath);

            if ($info === false) {
                $this->dispatch('swal', [
                    'title' => 'Imagen inválida o corrupta.',
                    'text' => 'El archivo no contiene datos de imagen válidos.',
                    'icon' => 'error',
                    'position' => 'top-end',
                ]);
                return;
            }

            [$width, $height] = $info;

            if ($width < 120 || $height < 120) {
                $this->dispatch('swal', [
                    'title' => 'Imagen demasiado pequeña.',
                    'text' => 'Mínimo 120×120 px.',
                    'icon' => 'warning',
                    'position' => 'top-end',
                ]);
                return;
            }

            if ($width > 8000 || $height > 8000) {
                $this->dispatch('swal', [
                    'title' => 'Imagen demasiado grande.',
                    'text' => 'Máximo 8000×8000 px.',
                    'icon' => 'warning',
                    'position' => 'top-end',
                ]);
                return;
            }

            try {
                $ext = strtolower($this->foto->extension() ?: ($mime === 'image/png' ? 'png' : 'jpg'));
                $filename = now()->format('YmdHis') . '_' . \Illuminate\Support\Str::uuid() . '.' . $ext;

                $path = $this->foto->storeAs('profesores', $filename, 'public');

                $datos['foto'] = basename($path);
            } catch (\Throwable $ex) {
                $this->dispatch('swal', [
                    'title' => 'No se pudo guardar la imagen.',
                    'text' => 'Detalle: ' . $ex->getMessage(),
                    'icon' => 'error',
                    'position' => 'top-end',
                ]);
                return;
            }
        } else {
            $datos['foto'] = null;
        }

        try {
            Profesor::create([
                'user_id' => $this->user_id,
                'CURP' => strtoupper(trim((string) $this->CURP)),
                'nombre' => strtoupper(trim((string) $this->nombre)),
                'apellido_paterno' => strtoupper(trim((string) $this->apellido_paterno)),
                'apellido_materno' => strtoupper(trim((string) $this->apellido_materno)),
                'telefono' => trim((string) $this->telefono),
                'perfil' => strtoupper(trim((string) $this->perfil)),
                'color' => $this->color,
                'status' => 'true',
                'foto' => $datos['foto'],
            ]);

            $this->reset([
                'user_id',
                'CURP',
                'foto',
                'nombre',
                'apellido_paterno',
                'apellido_materno',
                'telefono',
                'perfil',
                'color',
                'status',
                'datosCurp',
                'usuario_email',
            ]);

            $this->dispatch('refreshProfesores');

            $this->dispatch('swal', [
                'title' => 'Profesor creado correctamente.',
                'icon' => 'success',
                'position' => 'top-end',
            ]);

            $this->usuarios = User::role('Profesor')
                ->whereNotIn('id', Profesor::pluck('user_id'))
                ->where('status', "true")
                ->orderBy('id', 'desc')
                ->get();
        } catch (\Exception $e) {
            $this->dispatch('swal', [
                'title' => 'Error al crear el profesor',
                'text' => $e->getMessage(),
                'icon' => 'error',
                'position' => 'top-end',
            ]);
        }
    }

    public function render()
    {
        return view('livewire.admin.profesor.crear-profesor');
    }
}
