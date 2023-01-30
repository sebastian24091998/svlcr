<script>
    try {
        onScan.attachTo(document,{
        suffixKeyCodes: [13],
        //lectura completa
        onScan: function(barcode) {
            console.log(barcode)
            //evento para capturar el producto
            window.livewire.emit('scan-code', barcode)
        },

        //controlar errores de la lectura 
        onScanError: function(e){
            console.log(e)
        }
    })

        console.log('Scanner ready!')
    } catch (e) {
        console.log('Error de lectura: ' e)
    }
</script>