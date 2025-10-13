<?php

namespace App\Livewire\Admin\Usuarios;

use App\Exports\UsersExport;
use App\Models\User;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Role;

// Componente para mostrar y administrar usuarios en el panel de administración
class MostrarUsuarios extends Component
{
    use WithPagination; // Permite dividir la lista de usuarios en páginas

    // Variables públicas que se usan para buscar, filtrar y seleccionar usuarios
    public $search = ''; // Para buscar usuarios por texto
    public $rol;
    public $roles; // Lista de roles disponibles
    public $filtrar_status; // Para filtrar por estado (activo/inactivo)
    public $filtrar_roles; // Para filtrar por rol

    public $contar_admin; // Número de usuarios con rol Admin
    public $obtener_admin; // Lista de usuarios con rol Admin

    public $selected = []; // IDs de usuarios seleccionados
    public $selectAll = false; // Si se seleccionan todos los usuarios

    // Cuando el usuario marca/desmarca "seleccionar todos"
    public function updatedSelectAll($value)
    {
        if ($value) {
            // Se arma la consulta para obtener los usuarios según los filtros
            $query = User::query();

            // Filtra por estado si está seleccionado
            if ($this->filtrar_status) {
                $query->where('status', $this->filtrar_status == 'Activo' ? 'true' : 'false');
            }
            // Filtra por rol si está seleccionado
            if ($this->filtrar_roles) {
                $query->whereHas('roles', function ($query) {
                    $query->where('name', $this->filtrar_roles);
                });
            }
            // Filtra por texto de búsqueda
            if ($this->search) {
                $query->where(function ($query) {
                    $query->where('email', 'like', '%' . $this->search . '%')
                        ->orWhere('username', 'like', '%' . $this->search . '%')
                        ->orWhereHas('roles', function ($query) {
                            $query->where('name', 'like', '%' . $this->search . '%');
                        });
                });
            }

            // Excluye usuarios con el rol "Admin"
            $query->whereDoesntHave('roles', function ($query) {
                $query->where('name', 'Admin');
            });

            // Guarda los IDs de los usuarios seleccionados
            $this->selected = $query->pluck('id')->toArray();
        } else {
            // Si se desmarca, se limpia la selección
            $this->selected = [];
        }
    }

    // Cambia el rol de los usuarios seleccionados
   public function cambioRolUsuariosSeleccionados($id)
{
    $usuarios = User::whereIn('id', $this->selected)->get();

    foreach ($usuarios as $usuario) {
        $usuario->roles()->sync($id);
    }


    $this->refreshAdminStats();

    $this->dispatch('swal', [
        'title' => '¡Rol cambiado correctamente!',
        'icon' => 'success',
        'position' => 'top-end',
    ]);

    $this->dispatch('refreshUsuarios');
    $this->selected = [];
    $this->selectAll = false;
    $this->limpiarFiltros();
}


    // Se ejecuta al iniciar el componente


    #[On('refreshUsuarios')]
    public function onRefreshUsuarios(): void
    {
        $this->refreshAdminStats(); // <-- vuelve a contar y obtener admins
    }

    public function refreshAdminStats(): void
    {
        $this->contar_admin  = User::role('Admin')->count();
        $this->obtener_admin = User::role('Admin')->get();
    }

    #[On('refreshUsuarios')]
    public function mount(){

        $this->roles = Role::all(); // Obtiene todos los roles

        $this->refreshAdminStats();
    }

    // Obtiene la lista de usuarios según los filtros y búsqueda
    public function getUsuariosProperty()
    {
        $query = User::orderBy('id', 'desc'); // Ordena por ID descendente

        // Aplica filtro por estado
        if ($this->filtrar_status) {
            $query->where('status', $this->filtrar_status == 'Activo' ? 'true' : 'false');
        }

        // Aplica filtro por rol
        if ($this->filtrar_roles) {
            $query->whereHas('roles', function ($query) {
                $query->where('name', $this->filtrar_roles);
            });
        }

        // Aplica filtro por búsqueda
        if ($this->search) {
            $query->where(function ($query) {
                $query->where('email', 'like', '%' . $this->search . '%')
                    ->orWhere('username', 'like', '%' . $this->search . '%')
                    ->orWhereHas('roles', function ($query) {
                        $query->where('name', 'like', '%' . $this->search . '%');
                    });
            });
        }

        // Excluye usuarios con el rol "Admin"
        $query->whereDoesntHave('roles', function ($query) {
            $query->where('name', 'Admin');
        });

        return $query->paginate(15); // Devuelve los usuarios paginados
    }

    // Elimina un usuario por su ID
    public function eliminarUsuario($id)
    {
        $user = User::find($id);

        if ($user) {
            // Elimina la imagen de perfil si existe
            $imagePath = public_path('storage/profile-photos/' . $user->imagen);
            if ($user->imagen && file_exists($imagePath)) {
                unlink($imagePath);
            }

            $user->delete(); // Elimina el usuario

            // Muestra mensaje de éxito
            $this->dispatch('swal', [
                'title' => 'Usuario eliminado correctamente!',
                'icon' => 'success',
                'position' => 'top-end',
            ]);
        }
          $this->refreshAdminStats();
    }

    // Inactiva usuarios con ciertos roles
    public function inactivarUsuarios()
    {
        $user = User::all();
        foreach ($user as $u) {
            if ($u->roles()->whereIn('name', ['Profesor', 'Estudiante'])->exists()) {
                $u->status = 'false'; // Cambia el estado a inactivo
                $u->save();
            }
        }
        // Muestra mensaje de éxito
        $this->dispatch('swal', [
            'title' => 'Usuarios inactivados correctamente!',
            'icon' => 'success',
            'position' => 'top-end',
        ]);

          $this->refreshAdminStats();
    }

    // Activa usuarios con ciertos roles
    public function activarUsuarios()
    {
        $user = User::all();

        foreach ($user as $u) {
            if ($u->roles()->whereIn('name', ['Profesor', 'Estudiante'])->exists()) {
                $u->status = 'true'; // Cambia el estado a activo
                $u->save();
            }
        }
        // Muestra mensaje de éxito
        $this->dispatch('swal', [
            'title' => 'Usuarios activados correctamente!',
            'icon' => 'success',
            'position' => 'top-end',
        ]);
          $this->refreshAdminStats();
    }

    // Limpia los filtros de búsqueda y selección
    public function limpiarFiltros()
    {
        $this->search = '';
        $this->filtrar_status = null;
        $this->filtrar_roles = null;
    }

    // Vista de marcador de posición
    public static function placeholder(){
        return view('placeholder');
    }

    // Reinicia la página cuando se cambia el texto de búsqueda
    public function updatingSearch()
    {
        $this->resetPage();
    }


    public function render()
    {
        return view('livewire.admin.usuarios.mostrar-usuarios', [
            'usuarios' => $this->usuarios,
        ]);
    }
}
