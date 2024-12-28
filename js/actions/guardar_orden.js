// Guardar nueva orden
$(document).on('click', '#guardarOrden', async function (e) {
    e.preventDefault();

    let hsl_ = parseFloat($('#hsl').val()) / 100 || 0.1;
    let htp_ = parseFloat($('#htp').val()) / 100 || 0.2;

    const formData = {
        moneda: $('#moneda').val(),
        exchange: $('#exchange').val(),
        direccion: $('#direccion').val() || 'Long',
        apalancamiento: $('#apalancamiento').val(),
        precioEntrada: $('#precioEntrada').val(),
        volumen: $('#volumen_').val(),
        hsl: hsl_,
        htp: htp_
    };

    try {
        const response = await makeAjaxRequest('insert_order', formData);
        $('#guardado_').html(response.message).show().fadeOut(3000);
        getOrdersTable();
    } catch (error) {
        console.error("Error al guardar la orden:", error);
        $('#guardado_').html(response.message).show();
    }
});