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
        // let adas = transform(response.ADA.token, 'gtq');
        // let hbars = transform(response.hbar, 'gtq');
        // let xrps =  transform(response.xrp, 'gtq');

    } catch (error) {
        console.log("Error al obtener los datos de Holdings:", error.statusText);
        // Muestra un mensaje de error en un div con ID 'error_message'
        $('#holdings_table').html("Error al cargar los datos.");
    }
}

// Obtener informacion de la DB
async function getDashboardOrders() {
    try {
        const response = await makeAjaxRequest('get_orders', 'dashboard'); // Get Stored Orders
        let binanceD = transform(response.binance_total, 'usd');
        let bitgetD = transform(response.bitget_total, 'usd');
        let quantD = transform(response.quantfury_total, 'usd');
        let btcD = transform(response.btc_price, 'usd');
        let volTot = transform(response.volumen_acumulado, 'usd');
        let margenTot = transform(response.margen_acumulado, 'usd');
        let pnlTot = transform(response.pnl_acumulado, 'usd');
        // Precios en GTQ
        let binanceQ = transform(response.binance_total, 'gtq');
        let bitgetQ = transform(response.bitget_total, 'gtq');
        let quantQ = transform(response.quantfury_total, 'gtq');
        let btcQ =  transform(response.btc_price, 'gtq');
        $('#vol_total').text(volTot);
        $('#margen_total').text(margenTot);
        $('#pnl_total').text(pnlTot);
        $('#percMargen_total').text(response.perc_margen);
        $('#orders_table').html(response.orders_table);
        $('#binance').text(binanceD);
        $('#bitget').text(bitgetD);
        $('#quantfury').text(quantD);
        $('#btc_price').text(btcD);
        // Precios en GTQ
        $('#binance_q').text(binanceQ);
        $('#bitget_q').text(bitgetQ);
        $('#quantfury_q').text(quantQ);
        $('#btc_price_q').text(btcQ);
    } catch (error) {
        console.log("Error al obtener las órdenes 2:", error.statusText);
        // Muestra un mensaje de error en un div con ID 'error_message'
        $('#orders_table').html("Error al cargar los datos.");
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
