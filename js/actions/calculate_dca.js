// Calcular DCA acorde a la nueva entrada - Optimizado
async function calcularNuevoDca() {
    let nuevoVol = parseFloat($('#volumen_').val()) || 0;
    let nuevaEntrada = parseFloat($('#precioEntrada').val()) || 0;
    let contratos = nuevoVol && nuevaEntrada ? (nuevoVol / nuevaEntrada) : 0;

    let data = {
        moneda: $('#moneda').val(),
        exchange: $('#exchange').val()
    };

    try {
        const response = await makeAjaxRequest('updated_dca', data);
        let volumenActual = parseFloat(response.suma_vol) || 0;
        let dcaActual = parseFloat(response.entrada_prom) || 1; 
        let totalVolumen = volumenActual + nuevoVol;
        let contratosActuales = volumenActual / dcaActual;
        let sumaContratos = contratosActuales + contratos;
        let nuevoDCA = sumaContratos ? (totalVolumen / sumaContratos) : 0;

        $('#contratosActuales').text(contratosActuales.toFixed(4));
        $('#nuevoVolumen').text(`$${totalVolumen.toFixed(2)}`);
        $('#nuevoMargen').text(`$${(totalVolumen / 20).toFixed(2)}`);
        $('#sumaContratos').text(sumaContratos.toFixed(4));
        $('#nuevoDCA').text(nuevoDCA.toFixed(5));
        $('#new_dca').html("!!!").show().fadeOut(3000);
    } catch (error) {
        console.error("Error al calcular DCA:", error);
    }
}

// Evento para calcular nuevo DCA
$(document).on('click', '#calcularNuevoDCA', calcularNuevoDca);
