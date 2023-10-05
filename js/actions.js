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
            getOrders();
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
        getFullInventory();
    });
    

    async function saveOrder(formData) {
        return makeAjaxRequest('insert_order', formData);
    }

    async function getOrders() {
        try {
            const response = await makeAjaxRequest('orders_list');
            $('#ordenes_table').html(response.orders_table);
            $('#vol').html(response.volumen_acumulado);
            $('#margen').html(response.margen_acumulado);
            $('#pnl').html(response.pnl_acumulado);
        } catch (error) {
            console.log("Error al obtener las órdenes:", error);
            // Muestra un mensaje de error en un div con ID 'error_message'
            $('#ordenes_table').html("Error al cargar los datos.");
        }
    }

    async function getFullInventory() {
        try {
            const response = await makeAjaxRequest('inventario');
            $('#inventario_ordenes').html(response.inventory_table);
        } catch (error) {
            console.log("Error al obtener las órdenes:", error);
            // Muestra un mensaje de error en un div con ID 'error_message'
            $('#inventario_ordenes').html("Error al cargar los datos.");
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


    $(document).ready(function() {
        // Obtener elementos del DOM
        const direccionSelect = $("#direccion");
        const precioEntradaInput = $("#precioEntrada");
        const volumenInput = $("#volumen");
        const proyeccionArribaPercent = $("#projecctionArribaPercent");
        const proyeccionArribaPnl = $("#proyeccionArribaPnl");
        const proyeccionAbajoPercent = $("#proyeccionAbajoPercent");
        const proyeccionAbajoPnl = $("#proyeccionAbajoPnl");
    
        // Función para calcular y mostrar la proyección en tiempo real
        function calcularProyeccion() {
            const direccion = direccionSelect.val();
            const precioEntrada = parseFloat(precioEntradaInput.val());
            const volumen = parseFloat(volumenInput.val());
    
            // Verificar que el precio de entrada y el volumen sean números válidos
            if (isNaN(precioEntrada) || isNaN(volumen)) {
                proyeccion1.text("0");
                return;
            }
    
            // Calcular la proyección de crecimiento en función de la dirección
            const proyeccionPercent_1 = direccion === "Long" ? (precioEntrada + (precioEntrada * 0.01)) : (precioEntrada - (precioEntrada * 0.01));
            const proyeccionPnl_1     = volumen * 0.01;
            const proyeccionPercent_2 = direccion === "Long" ? (precioEntrada + (precioEntrada * 0.02)) : (precioEntrada - (precioEntrada * 0.02));
            const proyeccionPnl_2     = volumen * 0.02;
    
            // Mostrar la proyección en el elemento HTML
            proyeccionArribaPercent.text(proyeccionPercent_1.toFixed(4));
            proyeccionArribaPnl.text(proyeccionPnl_1.toFixed(4));
            proyeccionAbajoPercent.text(proyeccionPercent_2.toFixed(4));
            proyeccionAbajoPnl.text(proyeccionPnl_2.toFixed(4));
        }
    
        // Escuchar cambios en la dirección y el precio de entrada
        direccionSelect.on("change", calcularProyeccion);
        precioEntradaInput.on("input", calcularProyeccion);
    
        // Calcular la proyección al quitar el foco del campo Volumen
        volumenInput.on("blur", calcularProyeccion);
    
        // Calcular la proyección inicial al cargar la página
        calcularProyeccion();
    });
    
    


});