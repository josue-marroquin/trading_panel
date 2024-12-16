$(document).ready(function() {

    // Capturar el evento click en el botón "Close"
    $('.close-order-btn').click(function() {
        // Obtener el ID de la orden desde el atributo data-order-id
        const moneda = $(this).data('moneda');
        const exchange = $(this).data('exchange');
        const direccion = $(this).data('direccion');
        
        console.log(direccion);
        // Confirmar la acción con el usuario
        const confirmClose = confirm('¿Estás seguro de que quieres cerrar esta orden?');
        if (!confirmClose) return;

        // Realizar la solicitud AJAX
        $.ajax({
            url: './php/cerrar_orden.php', // Archivo PHP que actualizará el estado
            type: 'POST',
            data: { 
                option: 'cerrar_orden',
                moneda: moneda,
                exchange: exchange,
                direccion: direccion
             },
            success: function(response) {
                // Manejo de la respuesta del servidor
                if (response === 'success') {
                    alert('Orden cerrada correctamente.');
                    location.reload(); // Recargar la página para reflejar el cambio
                } else {
                    alert('Hubo un error al cerrar la orden. Inténtalo de nuevo.');
                }
            },
            error: function(err) {
                alert('No se pudo conectar al servidor. Inténtalo de nuevo.' + err.statusText);
            }
        });
    });
});