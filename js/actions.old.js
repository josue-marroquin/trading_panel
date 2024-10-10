$(document).ready(function() {
    // Obtener órdenes al cargar la página
    $("#proyeccion").hide();

    getOrders();
    getPositions();
    getPositionsFromDb();

    $(document).ready(function() {
        // Obtener elementos del DOM
        const apalancamiento = $("#apalancamiento");
        const direccionSelect = $("#direccion");
        const precioEntradaInput = $("#precioEntrada");
        const stopLoss = $("#stopLoss");
        const slTable = $("#stopLossProyectado");
        const capital = $("#maxLoss");
        const volumen  = $("#volumen");
        const volumen_ = $("#volumen_");
        const margen_ = $("#margen_");
        const unoAuno  = $("#unoAuno");
        const dosAuno  = $("#dosAuno");

        // Función para calcular y mostrar la proyección en tiempo real
        function calcularProyeccion() {

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
 
            $("#proyeccion").show();

        }
    
        // Escuchar cambios en la dirección y el precio de entrada
        direccionSelect.on("change", calcularProyeccion);
        precioEntradaInput.on("input", calcularProyeccion);
    
        // Calcular la proyección al quitar el foco del campo Volumen
        stopLoss.on("blur", calcularProyeccion);
        capital.on("blur", calcularProyeccion);
    
        // Calcular la proyección inicial al cargar la página
        calcularProyeccion();
    });

    // Guardar una nueva orden
    $(document).on('click', '#guardar', async function(e) {
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
            // Limpiar el formulario después de guardar
            // $('#order_form')[0].reset();
            // Obtener y mostrar las órdenes actualizadas
            getOrders();
            getPositions();
            getFullInventory();
        } catch (error) {
            console.log("Error al guardar la orden:", error);
        }
    });

    $(document).on('click', '.borrar', function() {
        const formData = {
            id: $(this).data('id')
        };
        makeAjaxRequest('borrar_orden', formData);
        getOrders();
        getPositions();
        getFullInventory();
    });
    
    $(document).on('click', '.confirmar', function() {
        const formData = {
            id: $(this).data('id')
        };
        makeAjaxRequest('confirmar_orden', formData);
        getOrders();
        getPositions();
        getFullInventory();
    });

    async function saveOrder(formData) {
        return makeAjaxRequest('insert_order', formData);
    }

    async function getOrders() {
        try {
            const response = await makeAjaxRequest('orders_list');
            $('#ordenes_table').html(response.orders_table);
        } catch (error) {
            console.log("Error al obtener las órdenes:", error);
            // Muestra un mensaje de error en un div con ID 'error_message'
            $('#ordenes_table').html("Error al cargar los datos.");
        }
    }

    async function getPositions() {
        try {
            const response = await makeAjaxRequest('positions_list');
            $('#vol').html(response.volumen_acumulado);
            $('#margen').html(response.margen_acumulado);
            $('#pnl').html(response.pnl_acumulado);
            $('#percMargen').html(response.perc_margen);
            $('#positions_table').html(response.positions_table);
        } catch (error) {
            console.log("Error al obtener las órdenes:", error);
            // Muestra un mensaje de error en un div con ID 'error_message'
            $('#positions_table').html("Error al cargar los datos.");
        }
    }



    async function getPositionsFromDb() {
        try {
            const response = await makeAjaxRequest('posiciones');
            console.log(response.posiciones_DbTable);
            $('#posicionesDb').html(response.posiciones_DbTable);
        } catch (error) {
            console.log("Error al obtener las órdenes:", error);
            // Muestra un mensaje de error en un div con ID 'error_message'
            $('#posicionesDb').html("Error al cargar los datos.");
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