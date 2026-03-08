<?php

namespace App\Livewire\Estudiante;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Perfil extends Component
{
    public ?int $user_id = null;
    public ?int $alumno_id = null;
    public ?int $inscripcion_id = null;

    // Datos del alumno
    public string $nombre = '';
    public string $apellido_paterno = '';
    public string $apellido_materno = '';
    public string $curp = '';
    public string $fecha_nacimiento = '';
    public string $sexo = '';

    // Datos escolares
    public string $matricula = '';
    public string $folio = '';
    public string $licenciatura = '';

    // Datos de inscripción
    public string $generacion = '';
    public string $cuatrimestre = '';
    public string $fecha_inscripcion = '';
    public string $estado_inscripcion = '';

    // Datos de contacto
    public string $email = '';
    public string $celular = '';
    public string $telefono = '';
    public string $calle = '';
    public string $numero_exterior = '';
    public string $numero_interior = '';
    public string $colonia = '';
    public string $municipio = '';
    public string $codigo_postal = '';
    public string $bachillerato_procedente = '';
    public string $pais = '';
    public string $estado = '';
    public string $ciudad = '';

    // Foto
    public ?string $foto_actual = null;

    public function mount(): void
    {
        $this->cargarPerfil();
    }

    public function cargarPerfil(): void
    {
        $usuario = Auth::user();

        if (!$usuario) {
            return;
        }

        $this->user_id = $usuario->id;
        $this->email = $usuario->email ?? '';

        $perfil = DB::table('alumnos')
            ->leftJoin('users', 'users.id', '=', 'alumnos.user_id')
            ->leftJoin('datos_escolares', 'datos_escolares.alumno_id', '=', 'alumnos.id')
            ->leftJoin('datos_contactos', 'datos_contactos.alumno_id', '=', 'alumnos.id')
            ->leftJoin('countries', 'countries.id', '=', 'datos_contactos.pais_id')
            ->leftJoin('states', 'states.id', '=', 'datos_contactos.estado_id')
            ->leftJoin('cities', 'cities.id', '=', 'datos_contactos.ciudad_id')
            ->leftJoin('inscripciones', 'inscripciones.alumno_id', '=', 'alumnos.id')
            ->leftJoin('licenciaturas', 'licenciaturas.id', '=', 'inscripciones.licenciatura_id')
            ->leftJoin('generaciones', 'generaciones.id', '=', 'inscripciones.generacion_id')
            ->leftJoin('cuatrimestres', 'cuatrimestres.id', '=', 'inscripciones.cuatrimestre_id')
            ->where('alumnos.user_id', $usuario->id)
            ->select(
                'alumnos.id as alumno_id',
                'alumnos.nombre',
                'alumnos.apellido_paterno',
                'alumnos.apellido_materno',
                'alumnos.curp',
                'alumnos.fecha_nacimiento',
                'alumnos.sexo',

                'users.email',

                'datos_escolares.matricula',
                'datos_escolares.folio',
                'datos_escolares.foto',

                'datos_contactos.calle',
                'datos_contactos.numero_exterior',
                'datos_contactos.numero_interior',
                'datos_contactos.colonia',
                'datos_contactos.municipio',
                'datos_contactos.codigo_postal',
                'datos_contactos.celular',
                'datos_contactos.telefono',
                'datos_contactos.bachillerato_procedente',

                'countries.name as pais',
                'states.name as estado',
                'cities.name as ciudad',

                'inscripciones.id as inscripcion_id',
                'inscripciones.status as estado_inscripcion',
                'inscripciones.fecha_inscripcion',

                'licenciaturas.nombre as licenciatura',
                'generaciones.generacion as generacion',
                'cuatrimestres.nombre_cuatrimestre as cuatrimestre'
            )
            ->first();

        if (!$perfil) {
            return;
        }

        $this->alumno_id = $perfil->alumno_id;
        $this->inscripcion_id = $perfil->inscripcion_id;

        // Datos del alumno
        $this->nombre = $perfil->nombre ?? '';
        $this->apellido_paterno = $perfil->apellido_paterno ?? '';
        $this->apellido_materno = $perfil->apellido_materno ?? '';
        $this->curp = $perfil->curp ?? '';
        $this->fecha_nacimiento = $perfil->fecha_nacimiento ?? '';
        $this->sexo = $perfil->sexo ?? '';

        // Datos escolares
        $this->matricula = $perfil->matricula ?? '';
        $this->folio = $perfil->folio ?? '';
        $this->licenciatura = $perfil->licenciatura ?? '';
        $this->foto_actual = $perfil->foto ?? null;

        // Datos de inscripción
        $this->generacion = $perfil->generacion ?? '';
        $this->cuatrimestre = $perfil->cuatrimestre ?? '';
        $this->fecha_inscripcion = $perfil->fecha_inscripcion ?? '';
        $this->estado_inscripcion = (string) ($perfil->estado_inscripcion ?? '');

        // Datos de contacto
        $this->email = $perfil->email ?? '';
        $this->celular = $perfil->celular ?? '';
        $this->telefono = $perfil->telefono ?? '';
        $this->calle = $perfil->calle ?? '';
        $this->numero_exterior = $perfil->numero_exterior ?? '';
        $this->numero_interior = $perfil->numero_interior ?? '';
        $this->colonia = $perfil->colonia ?? '';
        $this->municipio = $perfil->municipio ?? '';
        $this->codigo_postal = $perfil->codigo_postal ?? '';
        $this->bachillerato_procedente = $perfil->bachillerato_procedente ?? '';
        $this->pais = $perfil->pais ?? '';
        $this->estado = $perfil->estado ?? '';
        $this->ciudad = $perfil->ciudad ?? '';
    }

    public function getNombreCompletoProperty(): string
    {
        return trim($this->nombre . ' ' . $this->apellido_paterno . ' ' . $this->apellido_materno);
    }

    public function getFotoPreviewProperty(): string
    {
        if ($this->foto_actual) {
            return asset('storage/' . $this->foto_actual);
        }

        return 'https://ui-avatars.com/api/?name=' . urlencode($this->nombreCompleto ?: 'Alumno') . '&background=E2E8F0&color=475569&size=180';
    }

    public function render()
    {
        return view('livewire.estudiante.perfil');
    }
}
