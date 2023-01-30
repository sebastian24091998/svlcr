<div class="row sales layout-top-spacing">
    <div class="col-sm-12">
        <div class="widget widget-chart-one">
            <div class="widget-heading">
                <h4 class="card-title">
                    <b>{{$componentName}} | {{$pageTitle}}</b>
                </h4>
                <ul class="tabs tab-pills">
                    
                    <li>
                        <a href="javascript:void(0)" class="tabmenu bg-dark" data-toggle="modal" data-target="#theModal">
                            Agregar
                        </a>
                    </li>
                    
                </ul>

            </div>
            @can('Category_Search')
            @include('common.searchbox')
            @endcan
            <div class="widget-content">
                <div class="table-responsive">
                    <table class="table table-bordered table striped mt-1">
                        <thead class="text-white" style="background:#3b3f5c;">
                            <tr>
                                <th class="table-th text-while" style="color: #d5dfe9">
                                    DESCRIPCION
                                </th>
                                <th class="table-th text-while" style="color: #d5dfe9">
                                    IMAGEN
                                </th>
                                <th class="table-th text-while" style="color: #d5dfe9">
                                    ACCTIONS
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($categories as $category)
                                
                            
                            <tr>
                                <td>
                                    <h6>{{$category->name}}</h6>
                                </td>
                                <td class="text-center">
                                    <span>
                                    <img src="{{ asset('storage/categories/' .$category->imagen) }}" alt="imagen de ejemplos" height="78" width="80" class="rounder">
                                        
                                    </span>
                                </td>
                                <td class="text-center">
                                    <!--   --> 
                                   
                                    <a href="javascript:void(0)" wire:click="Edit({{$category->id}})" class="btn btn-dark mtmoble" title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                        </svg>
                                    </a>
                                    

                                    
                                    <a href="javascript:void(0)" onclick="Confirm('{{$category->id}}' , '{{ $category->products->count() }}')" class="btn btn-dark" title="Delete">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2">
                                            <polyline points="3 6 5 6 21 6"></polyline>
                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                            <line x1="10" y1="11" x2="10" y2="17"></line>
                                            <line x1="14" y1="11" x2="14" y2="17"></line>
                                        </svg>
                                    </a>
                                    
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{$categories->links()}}
                </div>
            </div>
        </div>
    </div>
    @include('livewire.category.form')
</div>

<script>
    document.addEventListener('DOMContentLoaded', function(){
        //para escuchar el evento mostrar ventana emergente
        window.livewire.on('show-modal', Msg =>{
            $('#theModal').modal('show')
        });
         //para escuchar el evento mostrar ventana emergente
         window.livewire.on('category-close', Msg =>{
            $('#theModal').modal('hide')
        });
        //evento de category ocultar
        window.livewire.on('category-added', Msg =>{
            $('#theModal').modal('hide')
            noty(Msg)
        });
        //evento de categoria actualizar
        window.livewire.on('category-updated', Msg =>{
            $('#theModal').modal('hide')
            noty(Msg)
        });
    });

    //funcion de ventana emergente de confirmacion para eliminar
    function Confirm(id, products)
    {   
        if(products>0)
        {
            //swal es para mensaje
            swal('no se puede eliminar porque tiene productos')
            return;
        }
        swal({
            title: 'CONFIRMAR',
            text: '¿CONFIRMAS ELIMINAR EL REGISTRO?',
            type: 'warning',
            showCancelButton: true,
            cancelButtonText: 'Cerrar',
            cancelButtonColor: '#fff',
            confirmButtonColor: '#3B3F5C',
            confirmButtonText: 'Aceptar'
        }).then(function(result){
            if(result.value){
                window.livewire.emit('deleteRow', id)
                swal.close()
            }
        })
    }
</script>