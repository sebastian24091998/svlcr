<div class="row sales layout-top-spacing">
    <div class="col-sm-12">
        <div class="widget widget-chart-one">
            <div class="widget-heading">
                <h4 class="card-title">
                    <b>{{$componentName}} </b>
                </h4>
                
            </div>

            <div class="widget-content">

                <div class="form-inline">
                    <div class="form-group mr-5">
                        <select wire:model="role" class="form-control">
                            <option value="Elegir" selected>== Selecciona el Role ==</option>
                            @foreach ($roles as $role)

                                <option value="{{$role->id}}" > {{$role->name}} </option>
                                
                            @endforeach
                        </select>
                    </div>
                    <button wire:click.prevent="SyncAll()" type="button" class="btn btn-dark mbmobile inblock mr-5">
                        Sincronizar Todos
                    </button>

                    
                </div>

                <div class="row mt-3">
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table class="table table-bordered table striped mt-1">
                                <thead class="text-white" style="background:#3b3f5c;">
                                    <tr>
                                        <th class="table-th text-white text-center">
                                            ID
                                        </th>
                                        <th class="table-th text-white text-center">
                                            PERMISO
                                        </th>
                                        <th class="table-th text-white text-center">
                                            ROLES CON EL PERMISO
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($permisos as $permiso)
                                    <tr>
                                        <td>
                                            <h6 class="text-center">{{$permiso->id}}</h6>
                                        </td>

                                        <td class="text-center">
                                            
                                            <div class="n-check">
                                                <label class="new-control new-checkbox checkbox-primary">
                                                    <input type="checkbox" wire:change="SyncPermiso($('#p' + {{ $permiso->id }} ).is(':checked'), '{{ $permiso->name }}' )"
                                                    id="p{{ $permiso->id }}"
                                                    value="{{ $permiso->id }}"
                                                    class="new-control-input"
                                                    {{ $permiso->checked == 1 ? 'checked': '' }}
                                                    >
                                                    <span class="new-control-indicator"></span>
                                                    <h6> {{ $permiso->name }} </h6>
                                                </label>
                                            </div>
                                        </td>

                                        <td class="text-center">
                                            <h6> {{ \App\Models\User::permission($permiso->name)->count() }} </h6>
                                        </td>
                                    </tr>
                                    @endforeach
                                    
                                </tbody>
                            </table>
                            {{ $permisos->links() }}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<button onclick="Revocar()" type="button" class="btn btn-dark mbmobile mr-5">
    Revocar Todos
</button>

<a href="javascript:void(0)" onclick="Revocar()" class="btn btn-dark" title="Delete">
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2">
        <polyline points="3 6 5 6 21 6"></polyline>
        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
        <line x1="10" y1="11" x2="10" y2="17"></line>
        <line x1="14" y1="11" x2="14" y2="17"></line>
    </svg>
</a>

<script>
    document.addEventListener('DOMContentLoaded', function(){
        window.livewire.on('sync-error', Msg =>)
        {
            noty(Msg)
        }
        //evento de permiso
        window.livewire.on('permi', Msg =>)
        {
            noty(Msg)
        }
        //
        window.livewire.on('syncall', Msg =>)
        {
            noty(Msg)
        }
        //evento de remover
        window.livewire.on('removeall', Msg =>)
        {
            noty(Msg)
        }
    });

    function Revocar()
    {   
        
        swal({
            title: 'CONFIRMAR',
            text: '¿CONFIRMAS Revocar Todos los Permisos',
            type: 'warning',
            showCancelButton: true,
            cancelButtonText: 'Cerrar',
            cancelButtonColor: '#fff',
            confirmButtonColor: '#3B3F5C',
            confirmButtonText: 'Aceptar'
        }).then(function(result){
            if(result.value){
                window.livewire.emit('revokeall')
                swal.close()
            }
        })
    }
</script>