<div class="connect-sorting">

    <div class="connect sorting-content">
        <div class="card simple-title-task ui-sortable-handle">
            <div class="card-body">
                
                @if($total > 0)
                        <div class="table-responsive tblscroll" style="max-height: 650px; overflow: hidden">
                            <table  class="table table-bordered table-striped mt-1">
                                <thead  class="text-white" style="background: #3b3f5c">

                                
                                <tr >

                                    <th width="10%"></th>
                                    <th class="table-th text-left text-white">DESCRIPCION</th>
                                    <th class="table-th text-center text-white">PRECIO</th>
                                    <th width="13%" class="table-th text-center text-white">CANT</th>
                                    <th class="table-th text-center text-white">IMPORTE</th>
                                    <th class="table-th text-center text-white">ACTIONS</th>

                                </tr>
                                </thead>
                                <tbody>
                                    @foreach ($cart as $item)
                                        <tr>
                                            <td class="text-center table-th">
                                                @if (count($item->attributes) > 0)
                                                    <span>
                                                        <img src="{{ asset('storage/products/' . $item->attributes[0]) }}"
                                                            alt="imagen" height="90" width="90" class="rounded">
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <h6>{{ $item->name }}</h6>
                                            </td>
                                            <td class="text-center">${{ number_format($item->price, 2) }}</td>
                                            <td>
                                                <input type="number" id="r{{ $item->id }}"
                                                    wire:change="updateQty({{ $item->id }}, $('#r' + {{ $item->id }}).val() )"
                                                    style="font-size: 1rem|important" class="form-control text-center"
                                                    value="{{ $item->quantity }}">

                                            </td>
                                            <td class="text-center">
                                                <h6>
                                                    ${{number_format($item->price * $item->quantity,2)}}
                                                </h6>
                                            </td>
                                            <td class="text-center">
                                                <button
                                                    onclick="Confirm('{{ $item->id }}', 'removeItem', '¿CONFIRMAS ELIMINAR E REGISTRO?')"
                                                    class="btn btn-dark mbmobile">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round"
                                                        class="feather feather-trash-2">
                                                        <polyline points="3 6 5 6 21 6"></polyline>
                                                        <path
                                                            d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                                        </path>
                                                        <line x1="10" y1="11" x2="10" y2="17"></line>
                                                        <line x1="14" y1="11" x2="14" y2="17"></line>
                                                    </svg>
                                                </button> 
                                                <button wire:click.prevent="decreaseQty({{ $item->id }})"
                                                    class="btn dark mbmobile">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round"
                                                        class="feather feather-minus">
                                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                                    </svg>
                                                </button>
                                                <button wire:click.prevent="increaseQty({{ $item->id }})"
                                                    class="btn dark mbmobile">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus">
                                                        <line x1="12" y1="5" x2="12" y2="19"></line>
                                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                                    </svg>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                @else
                <h5 class="text-center text-muted">
                    Agrega Productos a la venta
                </h5>
                @endif
                
                <div wire:loading.inline wire:target="saveSale">
                    <h4 class="text-danger text-center">
                        Guardando Venta...
                    </h4>
                </div>

            </div>
        </div>
    </div>

</div>
