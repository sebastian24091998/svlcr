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
        window.livewire.on('scan-ok', Msg => {
            //llamar a la funcion del backend
            noty(Msg)
        })
        //evento de scan no encontrado
        window.livewire.on('scan-notfound', Msg => {
            noty(Msg, 2)
        })
        //evento no stock
        window.livewire.on('no-stock', Msg => {
            noty(Msg, 2)
        })
        //evento ventas
        window.livewire.on('sale-error', Msg => {
            noty(Msg)
        })
        //evento de numero de ventas
        window.livewire.on('print-ticket', saleId => {
            window.open("print://" + saleId , '_blank')
        })
     })
</script>