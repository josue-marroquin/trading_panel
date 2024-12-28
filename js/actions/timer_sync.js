// Actualizar el Timer desde un script de Python
$(document).on('click', '#timer_sync', async function (e) {
    e.preventDefault();
    let timerVal = $('#timer_val').val() || 5;

    try {
        const updateResponse = await makeAjaxRequest('update_timer', { timerVal });
        $('#ticker_time').html(updateResponse.updated_time || `${timerVal} min.`);
    } catch (error) {
        console.error("Error al actualizar el timer:", error.statusText);
    }
});
