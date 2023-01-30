<div>
    
    <style></style>

    <div class="row layout-top-spacing">

        <div class="col-sm-12 col-md-3">
            <!-- total -->
            <!-- denominations -->
            @include('livewire.tiendas.partial.coins')
            <div class="col-sm-12 col-md-12 col-lg-6">
                <button wire:click.prevent="datitos('{{$efectivo}}','{{$itemsQuantity}}')" data-toggle="modal" data-target="#theModal" class="btn btn-dark mtmobile">
                    Generar QR
                </button>
            </div>
                
            

        </div>
        
    @include('livewire.tiendas.partial.QR')
</div>



<script>
    document.addEventListener('DOMContentLoaded', function(){
         //para escuchar el evento mostrar ventana emergente
       window.livewire.on('show-modal', Msg =>{
           $('#theModal').modal('show')
       });
      
       window.livewire.on('scan-ok', Msg => {
           //llamar a la funcion del backend
           noty(Msg)
       })
      

    })
</script