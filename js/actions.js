// Rutas para generar carga dinamica y ahorro de recursos.
const index = '/panel/index.php';
const dashboard = '/panel/dashboard.php';
const holdings = '/panel/dashboard_holdings.php';

if (window.location.pathname === index) {
    $(document).ready(function(){
        getOrdersTable();
    });
} else if (window.location.pathname === dashboard) {
    $(document).ready(function(){
        getOrdersTable();
    });
} else if (window.location.pathname === holdings) {
    $(document).ready(function(){
        getHoldings();
    });
}


$(document).ready(function() {

    getOrdersTable();
    // Establece una actualización periódica cada minuto
    setInterval(function() {
        getOrdersTable();
    }, 120000); // 60000 milisegundos = 1 minuto


    // Calcular DCA acorde a la nueva entrada - Oct 16 2024
    async function calcularNuevoDca() {

        // Data para el calculo del nuevo DCA
        let nuevoVol = parseFloat($('#volumen_').val());
        let nuevaEntrada = parseFloat($('#precioEntrada').val());
        let contratos = parseFloat(nuevoVol / nuevaEntrada);

        // Data para la consulta
        let data = {
            moneda: $('#moneda').val(),
            exchange: $('#exchange').val()
        }

        try {
            const response = await makeAjaxRequest('updated_dca', data);
            // Data proviniente de la DB
            let volumenActual = parseFloat(response.suma_vol);
            let dcaActual = parseFloat(response.entrada_prom);
            let totalVolumen = (volumenActual + nuevoVol);
            let contratosActuales = ((volumenActual / dcaActual));
            let sumaContratos = contratosActuales + contratos;
            let nuevoDCA = totalVolumen / sumaContratos;

            $('#contratosActuales').text(contratosActuales.toFixed(4));
            $('#nuevoVolumen').text("$"+totalVolumen);
            $('#nuevoMargen').text("$"+(totalVolumen/20));
            $('#sumaContratos').text(sumaContratos.toFixed(4));
            $('#nuevoDCA').text(nuevoDCA.toFixed(5));

        } catch (error) {
            console.log("Error getting DCA" + error);
        }
    }

    // Generar proyeccion / Calcular Nuevo DCA
    $(document).on('click', '#calcularNuevoDCA', async function(e) {
        calcularNuevoDca();
    });

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

    //  Obtener informacion de las ordenes desde la BD
    async function getOrdersTable() {
        let d = new Date();
        $("#updatedAt").html(d.toLocaleTimeString());
        try {
            const response = await makeAjaxRequest('get_orders', 'panel');
            $('#orders_table').html(response.orders_table);
            $('#hey').html(response.js_script);
        } catch (error) {
            console.log("Error al obtener las órdenes 2:", error.statusText);
            // Muestra un mensaje de error en un div con ID 'error_message'
            $('#orders_table').html("Error al cargar los datos.");
        }
    }

    // Guardar una nueva orden
    $(document).on('click', '#guardarOrden', async function(e) {
        e.preventDefault();
        const formData = {
            moneda: $('#moneda').val(),
            exchange: $('#exchange').val(),
            direccion: $('#direccion').val(),
            apalancamiento: $('#apalancamiento').val(),
            precioEntrada: $('#precioEntrada').val(),
            volumen: $('#volumen_').val()
        };
    
        try {
            await saveOrder(formData);
            console.log("Orden guardada");
        } catch (error) {
            console.log("Error al guardar la orden:", error);
        }
        getOrdersTable();
    });


    // Actualizar Timer - Python Script
    $(document).on('click', '#timer_sync', async function(e) {
        e.preventDefault();
        const formData = {
            timerVal: $('#timer_val').val()
        };
    
        try {
            const updateResponse = await makeAjaxRequest('update_timer', formData);
            $('#ticker_time').html(updateResponse.updated_time);
        } catch (error) {
            console.log("Error: ", error.statusText);
        }
    });

});