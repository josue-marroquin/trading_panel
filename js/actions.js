$(document).ready(function() {

    getProjections();
    // Establece una actualización periódica cada minuto
    setInterval(function() {
        getProjections();
    }, 120000); // 60000 milisegundos = 1 minuto


    // Obtener elementos del DOM y Valores - Input!!!
    const apalancamiento = $("#apalancamiento");
    const direccionSelect = $("#direccion");
    const precioEntradaInput = $("#precioEntrada");
    const stopLoss = $("#stopLoss");
    const capital = $("#maxLoss");
    const volumen  = $("#volumen");
    const volumen_ = $("#volumen_");
    const margen_ = $("#margen_");
    const volumenInput = $("#nivel");

    // Campos en Tabla de Proyeccion Proporcional !!!
    const slTable = $("#stopLossProyectado");
    const unoAuno  = $("#unoAuno");
    const dosAuno  = $("#dosAuno");


    // P R O Y E C C I O N  P R O P O R C I O N A L
    function calcularProyeccionProporcional() {
        console.log("Proporcional / 1:1 - 2:1");
        const direccion = direccionSelect.val();
        const precioEntrada = parseFloat(precioEntradaInput.val());
        const volumen_calc = parseFloat(capital.val() * apalancamiento.val()) / (parseFloat((stopLoss.val()/100)) * apalancamiento.val());
        const margen_calc = parseFloat(volumen_calc) / apalancamiento.val();
        
        if(direccion === "Long"){
            var uno = parseFloat(precioEntrada + (precioEntrada * parseFloat((stopLoss.val()/100))));
            var dos = parseFloat(precioEntrada + (precioEntrada * parseFloat((stopLoss.val()/100))) * 2);
            var precioSL = (precioEntradaInput.val() - (precioEntrada * stopLoss.val() / 100));
        } else {
            var uno = parseFloat(precioEntrada - (precioEntrada * parseFloat((stopLoss.val()/100))));
            var dos = parseFloat(precioEntrada - (precioEntrada * parseFloat((stopLoss.val()/100))) * 2);
            var precioSL = (parseFloat(precioEntradaInput.val()) + parseFloat((precioEntrada * stopLoss.val()) / 100));
        }

        slTable.html(precioSL.toFixed(5));
        volumen_.html(volumen_calc.toFixed(3));
        volumen.val(volumen_calc.toFixed(3));
        margen_.html(margen_calc.toFixed(3));
        unoAuno.html(uno.toFixed(5));
        dosAuno.html(dos.toFixed(5));

    }

    // Guardar Orden
    async function saveOrder(formData) {
        return makeAjaxRequest('insert_order', formData);
    }
    
    // Funcion Ajax que interactua con la base de datos
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

    ///------ GENERAR PROYECCIONES
    // Generar proyeccion Proporcional / Strategy 1
    $(document).on('click', '#proyeccionStrat_1', async function(e) {
        calcularProyeccionProporcional();
    });

    async function getProjections() {
        try {
            const response = await makeAjaxRequest('projections');
            $('#orders_table').html(response.orders_table);
        } catch (error) {
            console.log("Error al obtener las órdenes 2:", error.statusText);
            // Muestra un mensaje de error en un div con ID 'error_message'
            $('#orders_table').html("Error al cargar los datos.");
        }
    }

    // Guardar una nueva orden
    $(document).on('click', '#guardarStrat_1', async function(e) {
    // $("#guardar").click(async function(e) {
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
        } catch (error) {
            console.log("Error al guardar la orden:", error);
        }
        getProjections();
    });

});