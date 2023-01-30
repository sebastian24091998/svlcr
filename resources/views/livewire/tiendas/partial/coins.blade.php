<div class="row mt-3">
    <div class="col-sm-12">
        <div class="connect-sorting">
            <h5 class="text-center mb-2">
                DENOMINACIONES
            </h5>
            <div class="containee">
                <div class="row">
                    @foreach ($denominations as $d)
                        <div class="col-sm mt-2">
                            <button wire:click.prevent="Acash({{ $d->value }})" wire:model="dinero"
                                class="btn btn-dark btn-block den">
                                {{   'Bs. ' . number_format($d ->value, 2, )  }}
                            </button>
                            
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="connect-sorting-content mt-4">
                <div class="card simple-title-task ui-sortable-handle">
                    <div class="card-body">
                        <div class="input-group input-group-md mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text input-gp hideonsm"
                                    style="background: #3B3F5C;
                                color:white">
                                   
                                </span>
                            </div>
                            <input type="number" id="cash" wire:model="efectivo" wire:keydown.enter="Acash({{$efectivo}})"
                                class="form-control text-center" value="{{ $efectivo }}">
                            <div class="input-group-append">
                                <span wire:click="$set('efectivo', 0)" class="input-group-text"
                                    style="background: #3B3F5C; color:white">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" 
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" class="feather feather-delete">
                                    <path d="M21 4H8l-7 8 7 8h13a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2z"></path>
                                    <line x1="18" y1="9" x2="12" y2="15"></line>
                                    <line x1="12" y1="9" x2="18" y2="15"></line>
                                    </svg>
                            </span>
                                
                            </div>
                        </div>

                       
                        <div class="row justify-content-between mt-5">
                            
                            <div class="col-sm-12 col-md-12 col-lg-6">
                                
                            </div>
                            <div class="col-sm-12 col-md-12 col-lg-6">
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

