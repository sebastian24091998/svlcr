
<div wire:ignore.self class="modal fade" id="theModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark">
                <h5 class="modal-title text-white">
                    <b>GENERAR CODIGO QR</b> 
                </h5>
                
            </div>
            <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div>
                                <div class="connect-sorting">
                                    <h5 class="text-center mb-3"> RESUMEN DE VENTA</h5>
                                    <div class="connect-sorting-content text-center">
                                        <div class="card simple-title-task ui-sortable-handle">
                                            <div class="card-body">
                                                <div class="task-header">
                                                    <div>
                                                        <h2>TOTAL A PAGAR: ${{number_format($qrtotal,2)}}</h2>
                                                        <input type="hidden" id="hiddenTotal" value="{{$qrtotal}}">
                                                    </div>
                                                    <h4 class="mt-3">ARTICULOS: {{$qritem}}</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <br>
                        </div>
                        
                        <div class="col-sm-12 text-center">
                                <span class="text-center" style="margin: auto">
                                    
                                    <img src="assets/img/qr.jpeg" width="500px" height="500px" alt="">
                                </span>
                            
                        </div>
                        
                        
                       
                    </div>
  
                    <div>
                        <div class="modal-footer">
                        <button type="button" wire:click.prevent="resetUI()" class="btn btn-dark close-btn text-info" data-dismiss="modal">
                            CERRAR
                        </button>
                        
                        
                        
                    
                    
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>
  