/*  Obtener información de las órdenes desde la BD
* Todos los calculos requeridos fueron implementados en php
*/
async function getOrdersTable() {
    let d = new Date();
    try {
        const response = await makeAjaxRequest('get_orders', 'panel');
        $("#updatedAt").html(d.toLocaleTimeString());
        $('#orders_table').html(response.orders_table || "No hay órdenes disponibles.");
    } catch (error) {
        console.error("Error al obtener las órdenes:", error.statusText);
        $('#orders_table').html("Error al cargar los datos.");
    }
}
