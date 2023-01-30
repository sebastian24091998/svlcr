<script>
    //todos los evnetos del teclado
    var listener = new window.keypress.Listener();

    //F9
    listener.simple_combo("f9", function() {
        console.log('f9')
        livewire.emit('saveSale')
    })

    //F8
    listener.simple_combo("k", function() {
        //limpia el texto
        document.getElementById('cash').value = ''
        
        //redicciona el cursor en la caja
        document.getElementById('cash').focus()

        //
        document.getElementById('hiddenTotal').value = ''
    })

    //F4
    listener.simple_combo("f4", function() {
        //validar que tenga productos
        var total = parserfloat(document.getElementById('hiddenTotal').value)
        if(total > 0) {
            Confirm(0, 'clearCart', '¿Seguro de Eliminar el carrito?')
        }else
        {
            noty('Agrega productos a la venta')
        }
    })
</script>