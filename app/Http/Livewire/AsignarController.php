<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Livewire\WithPagination;
use DB;

class AsignarController extends Component
{
    
    use WithPagination;

    public $role, $componentName, $permisosSelected = [], $old_permissions = [];
    private $pagination = 10;

    public function paginationView()
    {
        return 'vendor.livewire.bootstrap';
    }

    public function mount()
    {
        $this->role = 'Elegir';
        $this->componentName = 'Asignar Permisos';
    }

    public function render()
    {
        //mostrar el listado de pemisos
        $permisos = Permission::select('name', 'id', DB::raw("0 as checked") )
        ->orderBy('name', 'asc')
        ->paginate($this->pagination);

        if($this->role != 'Elegir')
        {
            $list = Permission::join('role_has_permissions as rp', 'rp.permission_id', 'permissions.id')
            ->where('role_id', $this->role)->pluck('permissions.id')->toArray();
            //lista de rol de permisos anterior
            $this->old_permissions = $list;
        }

        if($this->role != 'Elegir')
        {
            foreach ($permisos as $permiso) {
                $role = Role::find($this->role);
                //buscar si tiene asginado ese permiso
                $tienePermiso = $role->hasPermissionTo($permiso->name);
                if($tienePermiso){
                    $permiso->checked = 1;
                }
            }
        }

        return view('livewire.asignar.component2',[
            'roles' => Role::orderBy('name', 'asc')->get(),
            'permisos' => $permisos
        ])
        ->extends('layouts.theme.app')
        ->section('content');
    }

    public $listeners = ['revokeall' => 'RemoveAll'];

    //metodo para remover todos los permisos
    public function RemoveAll()
    {
        //validar si estamos seleccionando un rol
        if($this->role == 'Elegir'){
            $this->emit('sync-error', 'Selecciona un rol valido');
            return;
        }

        //sincronizar los permisos
        $role = Role::find($this->role);
        $role->syncPermissions([0]);
        $this->emit('removeall', "Se revocaron todos los permisos al role $role->name ");
    }

    //funcion para sincronizar todo los permisos
    public function SyncAll()
    {
        //validar si estamos seleccionando un rol
        if($this->role == 'Elegir'){
            $this->emit('sync-error', 'Selecciona un rol valido');
            return;
        }

        //buscamos el rol
        $role = Role::find($this->role);
        //pluck sirve para obtener las columnas que queremos
        $permisos = Permission::pluck('id')->toArray();
        //sincronizar los permisos
        $role->syncPermissions($permisos);
        $this->emit('syncall', "Se sincronizaron todos los permisos al role $role->name ");
    }

    //
    public function SyncPermiso($state, $permisoName)
    {
        //validar si estamos seleccionando un rol
        if($this->role !='Elegir')
        {
            $roleName = Role::find($this->role);
            //
            if($state)
            {
                //dar permiso
                $roleName->givePermissionTo($permisoName);
                $this->emit('permi', 'Permiso asginado correcamente');
            } else {
                //eliminar permiso
                $roleName->revokePermissionTo($permisoName);
                $this->emit('permi', 'Permiso eliminado correctamente');
            }
        } else {
            $this-> emit('permi', 'Elige un rol valido');
        }
    }
}
