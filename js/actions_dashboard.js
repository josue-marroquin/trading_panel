$(document).ready(function() {

    getDashboardOrders();
    // Establece una actualización periódica cada minuto
    setInterval(function() {
        getOrdersFromDb();
    }, 120000); // 60000 milisegundos = 1 minuto


    // Obtener informacion de la DB
    async function getDashboardOrders() {
        try {
            const response = await makeAjaxRequest('projections');
            $('#vol_total').html(response.volumen_acumulado);
            $('#margen_total').html(response.margen_acumulado);
            $('#pnl_total').html(response.pnl_acumulado);
            $('#percMargen_total').html(response.perc_margen);
            $('#orders_table').html(response.orders_table);
        } catch (error) {
            console.log("Error al obtener las órdenes 2:", error.statusText);
            // Muestra un mensaje de error en un div con ID 'error_message'
            $('#orders_table').html("Error al cargar los datos.");
        }
    }


    async function makeAjaxRequest(option, data = {}) {
        return new Promise((resolve, reject) => {
            $.ajax({
                type: 'POST',
                url: './services.php',
                data: { option, formulario: data },
                dataType: 'json',
                success: function(response) {
                    resolve(response);
                },
                error: function(error) {
                    reject(error);
                }
            });
        });
    }

});
