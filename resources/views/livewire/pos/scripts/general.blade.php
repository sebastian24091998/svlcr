<script>
    //toda la parte de las tablas de los productos
    $('.tblscroll').nicescroll({
        cursorcolor: "#515365",
        cursorwidh: "30px",
        background: "rgba(20,20,20,0.3",
        cursorborder: "0px",
        cursorborderradius: 3
    })


    function Confirm(id, eventName, text)
    {   
        
        swal({
            title: 'CONFIRMAs',
            text: text,
            type: 'warning',
            showCancelButton: true,
            cancelButtonText: 'Cerrar',
            cancelButtonColor: '#fff',
            confirmButtonColor: '#3B3F5C',
            confirmButtonText: 'Aceptar'
        }).then(function(result){
            if(result.value){
                window.livewire.emit(eventName, id)
                swal.close()
            }
        })
    }
    function Eliminar(text)
    {   
        
         //limpia el texto
         document.getElementById('cash').value = ''
        
        //redicciona el cursor en la caja
        document.getElementById('cash').focus()

        //
        document.getElementById('hiddenTotal').value = ''
        document.getElementById('change').text.value = ''
    }
</script>