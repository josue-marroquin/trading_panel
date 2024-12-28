// Rutas para generar carga dinamica y ahorro de recursos.
const index = '/panel/index.php';
const dashboard = '/panel/dashboard';
const holdings = '/panel/dashboard_holdings';

if (window.location.pathname === index) {
    $(document).ready(function(){
        getDashboardOrders();
    });
} else if (window.location.pathname === dashboard) {
    $(document).ready(function(){
        getDashboardOrders();
        setInterval(function() {
            getDashboardOrders();
        }, 120000); // 60000 milisegundos = 1 minuto
    });
} else if (window.location.pathname === holdings) {
    $(document).ready(function(){
        getHoldings();
        setInterval(function() {
            getHoldings();
        }, 120000); // 60000 milisegundos = 1 minuto
    });
}



// Funcion para Convertir a formato currency
// Basicamente esta funcion agrega el formato $/GTQ.0.00
let transform = (usd, currency) => {
    const gtq = 7.8; // Conversion rate from USD to GTQ
    const amountInGTQ = usd * gtq;
    if(currency === 'gtq'){
        return amountInGTQ.toLocaleString('en-US', {
            style: 'currency',
            currency: 'GTQ',
        });
    } else if(currency === 'usd') {
        const amountInUSD = usd * 1;
        return amountInUSD.toLocaleString('en-US', {
            style: 'currency',
            currency: 'USD',
        });
    }
}


// Obtener informacion de la DB para la tabla de Holdings - Spot
async function getHoldings() {
    try {
        const response = await makeAjaxRequest('get_holdings');
        $('#holdings_table').html(response.holdings_table);
    } catch (error) {
        console.log("Error al obtener los datos de Holdings:", error.statusText);
        $('#holdings_table').html("Error al cargar los datos.");
    }
}

// Obtener y Transformar informacion de las Ordenes de futuros
async function getDashboardOrders() {
    try {
        const response = await makeAjaxRequest('get_orders', 'dashboard'); // Get Stored Orders
        // Variables de totales representadas en dolares
        let binanceD = transform(response.binance_total, 'usd');
        let hsl_binanceD = transform(response.binance_hsl, 'usd');
        let htp_binanceD = transform(response.binance_htp, 'usd');
        let bitgetD = transform(response.bitget_total, 'usd');
        let hsl_bitgetD = transform(response.bitget_hsl, 'usd');
        let htp_bitgetD = transform(response.bitget_htp, 'usd');
        let quantD = transform(response.quantfury_total, 'usd');
        let hsl_quantD = transform(response.quantfury_hsl, 'usd');
        let htp_quantD = transform(response.quantfury_htp, 'usd');
        let btcD = transform(response.btc_price, 'usd');
        let volTot = transform(response.volumen_acumulado, 'usd');
        let margenTot = transform(response.margen_acumulado, 'usd');
        let pnlTot = transform(response.pnl_acumulado, 'usd');
        // Precios en GTQ
        let binanceQ = transform(response.binance_total, 'gtq');
        let bitgetQ = transform(response.bitget_total, 'gtq');
        let quantQ = transform(response.quantfury_total, 'gtq');
        let btcQ =  transform(response.btc_price, 'gtq');
        // Margenes
        let margen_bin = transform(response.margen_binance, 'usd');
        let margen_bitget = transform(response.margen_bitget, 'usd');
        let marget_quantfury = transform(response.margen_quantfury, 'usd');
        // Volcado de datos
        $('#vol_total').text(volTot);
        $('#margen_total').text(margenTot);
        $('#pnl_total').text(pnlTot);
        $('#percMargen_total').text(response.perc_margen);
        $('#orders_table').html(response.orders_table);
        $('#binance').text(binanceD);
        $('#hsl_binance').text(hsl_binanceD);
        $('#htp_binance').text(htp_binanceD);
        $('#bitget').text(bitgetD);
        $('#hsl_bitget').text(hsl_bitgetD);
        $('#htp_bitget').text(htp_bitgetD);
        $('#quantfury').text(quantD);
        $('#hsl_quantfury').text(hsl_quantD);
        $('#htp_quantfury').text(htp_quantD);
        $('#btc_price').text(btcD);
        // Precios en GTQ
        $('#binance_q').text(binanceQ);
        $('#bitget_q').text(bitgetQ);
        $('#quantfury_q').text(quantQ);
        $('#btc_price_q').text(btcQ);
        // Margenes
        $('#margen_binance').text(margen_bin);
        $('#margen_bitget').text(margen_bitget);
        $('#margen_quantfury').text(marget_quantfury);
    } catch (error) {
        console.log("Error al obtener las órdenes 2:", error);
        $('#orders_table').html("Error al cargar los datos.");
    }
}