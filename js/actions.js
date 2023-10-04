$(document).ready(function() {
    // Obtener órdenes al cargar la página
    getOrders();
    getFullInventory();
    
    // Guardar una nueva orden
    $("#guardar").click(async function(e) {
        e.preventDefault();
        const formData = {
            moneda: $('#moneda').val(),
            exchange: $('#exchange').val(),
            direccion: $('#direccion').val(),
            apalancamiento: $('#apalancamiento').val(),
            precioEntrada: $('#precioEntrada').val(),
            volumen: $('#volumen').val()
        };

        try {
            await saveOrder(formData);
            console.log("Orden guardada");
            // Limpiar el formulario después de guardar
            $('#order_form')[0].reset();
            // Obtener y mostrar las órdenes actualizadas
            await getOrders();
        } catch (error) {
            console.log("Error al guardar la orden:", error);
        }
    });
    
    async function saveOrder(formData) {
        return makeAjaxRequest('insert_order', formData);
    }
    
    async function getOrders() {
        try {
            const response = await makeAjaxRequest('orders_list');
            displayOrders(response.orders_table);
        } catch (error) {
            console.log("Error al obtener las órdenes:", error);
            // Muestra un mensaje de error en un div con ID 'error_message'
            $('#error_message').html("Error al cargar los datos.");
        }
    }
    
    async function getFullInventory() {
        try {
            const response = await makeAjaxRequest('inventario');
            displayInventory(response.inventory_table);
        } catch (error) {
            console.log("Error al obtener las órdenes:", error);
            // Muestra un mensaje de error en un div con ID 'error_message'
            $('#error_message').html("Error al cargar los datos.");
        }
    }
    
    function displayOrders(ordersTableHtml) {
        $('#ordenes_table').html(ordersTableHtml);
    }
    
    function displayInventory(inventoryTableHtml) {
        $('#inventario_ordenes').html(inventoryTableHtml);
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
    
    new DataTable('.dataTable_');
    
});
