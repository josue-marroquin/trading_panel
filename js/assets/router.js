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

