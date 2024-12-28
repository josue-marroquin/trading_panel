/*** Actualizar HSL y HTP de una operacion abierta
 * La misma debe mostrar un status_ 0 en la base de datos
 * para ser elegible al momento de actualizar el hsl,htp
 * se actualizaran todas las rows que coincidan con los datos:
 * moneda, exchange, direccion
 * */
$(document).on('click', '#updateHValues', async function (e) {

    let hsl_ = parseFloat($('#hsl').val()) / 100 || 0.1;
    let htp_ = parseFloat($('#htp').val()) / 100 || 0.2;

    const formData = {
        moneda: $('#moneda').val(),
        exchange: $('#exchange').val(),
        direccion: $('#direccion').val() || 'Long',
        hsl: hsl_,
        htp: htp_
    };

    try {
        const response = await makeAjaxRequest('update_h_values', formData);
        $('#guardado_').html(response.message).show().fadeOut(5000);
        getOrdersTable();
    } catch (error) {
        console.error("Error al guardar H Values:", error);
        $('#guardado_').html(response.message).show().fadeOut(5000);
    }
});