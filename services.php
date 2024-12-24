<?php

require("db_conn.php");

if(isset($_POST["option"])) {
    $option = $_POST["option"];
    switch ($option) {
        case 'insert_order':
            insert_order();
            break;
        case 'get_orders':
            get_orders();
            break;
        case 'get_holdings':
            get_holdings();
            break;
        case 'updated_dca':
            calcular_nuevo_dca();
            break;
        case 'update_timer':
            update_timer();
            break;
        }
    }


//*** Obtener el precio actual de BINANCE para cada moneda Operada ****/
function get_binance_price($moneda) {
    // Construir la URL de la API de Binance para obtener el precio de la moneda
    $apiUrl = "https://api.binance.com/api/v3/ticker/price?symbol={$moneda}USDT";
    // Realizar una solicitud GET a la API de Binance
    $data = file_get_contents($apiUrl);
    if ($data) {
        $response = json_decode($data, true);
        if (isset($response['price'])) {
            return $response['price'];
        }
    }
    return "No disponible"; // En caso de que no se pueda obtener el precio
}


//*** Obtener el precio actual de BITGET para cada moneda Operada ****/
function get_bitget_price($moneda) {
    // Construir la URL de la API de Bitget para obtener el precio de la moneda
    $apiUrl = "https://api.bitget.com/api/v2/spot/market/tickers?symbol={$moneda}USDT";
    
    // Realizar una solicitud GET a la API de Bitget
    $data = file_get_contents($apiUrl);
    
    if ($data) {
        $response = json_decode($data, true);
        // Verificar si la respuesta tiene el precio
        if (isset($response['data'][0]['lastPr'])) {
            return $response['data'][0]['lastPr']; // Retorna el precio más reciente
        }
    }
    
    return "No disponible"; // En caso de que no se pueda obtener el precio
}


// Calcular Nuevo DCA para ordenes preexistentes
function calcular_nuevo_dca() { // Oct 16 2024
    global $conn;
    $data = $_POST["formulario"];
    $moneda = $data["moneda"];
    $exchange = $data["exchange"];
    $sql = "SELECT moneda , exchange , direccion_ , X , SUM(volumen_entrada) AS suma_volumen , SUM(margen_) AS suma_margen , ROUND(SUM(volumen_entrada) / SUM(contratos_), 6) AS entrada_promedio FROM ordenes WHERE status_ = 0 AND moneda = '".$moneda."' AND exchange = '".$exchange."' GROUP BY exchange, moneda, direccion_, status_"; 

    $result = $conn->query($sql);
    $row_count = $result->num_rows;

    if($row_count > 0){

        while($row = $result->fetch_array()){
            $moneda = $row['moneda'];
            $exchange = $row['exchange'];
            $suma_volumen = $row['suma_volumen'];
            $entrada_promedio = $row['entrada_promedio'];
        }

    } else {
        $suma_volumen = 0;
        $entrada_promedio = 0;
    }

    $jsondata['suma_vol'] = $suma_volumen;
    $jsondata['entrada_prom'] = $entrada_promedio;

    echo json_encode($jsondata);
}

// Actualiza el timer en la base de datos para los tiempos de ejecucion del script de python
function update_timer() {
    global $conn;
    $jsondata = array();
    $data = $_POST["formulario"];
    $time = $data["timerVal"];
    $time = $time * 60;
    $sql = "UPDATE ticker_timer SET timer_secs =". $time ." WHERE timer_ID = 1";
    // Run update
    $conn->query($sql);

    $jsondata['updated_time'] = ($time/60) . " mins.";
    echo json_encode($jsondata);
}


// Extraer ordenes Guardadas desde el tablero
// ********  Lista de Ordenes - View  ***********************//
function get_orders() {
    // Consulta SQL
    global $conn;
    $where = $_POST['formulario'];
    $jsondata = array();
    $table = "";
    $pnl_color = "";
    $direct_color = "";
    $index = 1;
    $precio_actual = 0;
    $total_volumen = 0;
    $total_margen = 0;
    $total_pnl = 0;
    $exchange_total_1 = 0;
    $exchange_total_2 = 0;
    $exchange_total_3 = 0;
    $margen_binance = 0;
    $margen_bitget = 0;
    $margen_quantfury = 0;
    $porcentage_del_margen = 0;
    $sql = "SELECT moneda, exchange, direccion_, X, SUM(volumen_entrada) AS suma_volumen, SUM(margen_) AS suma_margen, SUM(volumen_entrada) / SUM(contratos_) AS entrada_promedio FROM ordenes WHERE status_ = 0 GROUP BY exchange, moneda, direccion_, status_";

    $result = $conn->query($sql);
    $row_count = $result->num_rows;

    if($row_count > 0){
        while($row = $result->fetch_array()){
            $moneda = $row['moneda'];
            $exchange = $row['exchange'];
            $direccion = $row['direccion_'];
            $x = $row['X'];
            $suma_volumen = $row["suma_volumen"];
            $suma_margen = $row["suma_margen"];
            $entrada_promedio = $row["entrada_promedio"];
            $proyeccion_1 = ($suma_volumen * 10) / 100;   // Proyeccion de 50% de ganancia sobre el volumen predefinida
            $proyeccion_2 = ($suma_volumen * (-10)) / 100;   // Proyeccion de 50% de perdida sobre el volumen predefinida

            // Obteniendo el precio basado en el Exchange
            if($exchange == "BINANCE"){
                $precio_actual = get_binance_price($moneda);
            } else if ($exchange == "BITGET") {
                $precio_actual = get_bitget_price($moneda);
            } else if ($exchange == "QUANTFURY") {
                $precio_actual = get_binance_price($moneda);
            }

            if($moneda == 'PEPE') {
                $precio_actual = $precio_actual * 1000;
            }
            $porcentaje_movimiento_actual = (($precio_actual / $entrada_promedio) *100) -100;
            $pnl_actual = ($suma_volumen * $porcentaje_movimiento_actual) / 100;
            $direct_color = ($direccion == 'Long' ? "style='color:#b5e7a0;'" : "style='color:#feb236;'");
            
            // Coloreado de la data
            if($direccion == 'Short') {
                $direccion_bg_color = "style='box-shadow: 5px 5px 12px 0px #000000 inset; background-color:#feb236; color: #3b3a30; font-weight: 900;'";
                $pnl_actual = $pnl_actual * (-1);
                $pnl_color = $pnl_actual < 0 ? "style='color:#feb236;'" : "style='color:#b5e7a0;'";
            } else if ($direccion == 'Long') {
                $direccion_bg_color = "style='box-shadow: 5px -5px 12px 0px #000000 inset; background-color:#82b74b; color: #3b3a30; font-weight: 900;'";
                $pnl_color = $pnl_actual < 0 ? "style='color:#feb236;'" : "style='color:#b5e7a0;'";
            } else {
                $pnl_color = "style='color:blue;'"; 
            }

            // A: Exchange based row color
            // B: Suma de totales por Exchange
            if($exchange == "BINANCE"){
                $exchange_total_1 += $pnl_actual;
                $margen_binance += $suma_margen;
                $exchange_color = "style='box-shadow: 5px 5px 12px 0px #000000 inset; background-color:#ffcc00; color: #3b3a30; font-weight: 900;'";
            } else if ($exchange == "BITGET") {
                $exchange_total_2 += $pnl_actual;
                $margen_bitget += $suma_margen;
                $exchange_color = "style='box-shadow: 5px 5px 12px 0px #000000 inset; background-color:#00ccff; color: #3b3a30; font-weight: 900;'";
            } else if ($exchange == "QUANTFURY") {
                $exchange_total_3 += $pnl_actual;
                $margen_quantfury += $suma_margen;
                $exchange_color = "style='box-shadow: 5px 5px 12px 0px #000000 inset; background-color:#4fa190; color: #3b3a30; font-weight: 900;'";
            }

            // Suma de totales generales
            $total_volumen += $suma_volumen;
            $total_margen += $suma_margen;
            $total_pnl += $pnl_actual;

            $table .= "<tr>";
            $table .= "<td align='center' ". $direccion_bg_color .">".$index."</td>";
            $table .= "<td align='center'". $exchange_color .">".$moneda."</td>";
            $table .= "<td align='center' ".$pnl_color.">".round($pnl_actual, 4)."</td>";
            $table .= "<td align='center'><strong>".round($precio_actual, 5)."<strong></td>";
            $table .= "<td align='center' class='p-enrada'>".round($entrada_promedio, 5)."</td>";
            $table .= "<td align='center'>" .round($porcentaje_movimiento_actual, 3)."%</td>";
            $table .= "<td align='center'>".$x."</td>";
            $table .= "<td align='center' class='volumen'><strong>".round($suma_volumen, 4)."</strong></td>";
            $table .= "<td align='center'>".round($suma_margen, 4)."</td>";
            if($where != 'panel'){
                $table .= "<td align='center' class='calc-profit'>".round($proyeccion_1, 4)."</td>";
                $table .= "<td align='center' class='calc-loss'>".round($proyeccion_2, 4)."</td>";
            } else {
                $table .= "<td><button type='button' class='btn btn-sm btn-secondary close-order-btn' data-direccion='".$direccion."' data-exchange='".$exchange."' data-moneda='".$moneda."'>Close</button></td>";
            }
            $table .= "</tr>";
            $index += 1;

        }

        $btc_price = get_binance_price("BTC");
        $porcentaje_del_margen = ($total_pnl/$total_margen);
        $jsondata['orders_table'] = $table;
        $jsondata['pnl_acumulado'] = round($total_pnl, 4);
        $jsondata['volumen_acumulado'] = round($total_volumen, 4);
        $jsondata['margen_acumulado'] = round($total_margen, 4);
        $jsondata['perc_margen'] = round(($porcentaje_del_margen*100),2) . "%";
        $jsondata['binance_total'] = round($exchange_total_1, 3);
        $jsondata['bitget_total'] = round($exchange_total_2, 3);
        $jsondata['quantfury_total'] = round($exchange_total_3, 3);
        $jsondata['btc_price'] = round($btc_price, 3);
        $jsondata['margen_binance'] = round($margen_binance, 3);
        $jsondata['margen_bitget'] = round($margen_bitget, 3);
        $jsondata['margen_quantfury'] = round($margen_quantfury, 3);

    } else {

        $porcentaje_del_margen = 0;
        $jsondata['orders_table'] = "Sin datos para mostrar.";
        $jsondata['pnl_acumulado'] = 0;
        $jsondata['volumen_acumulado'] = 0;
        $jsondata['margen_acumulado'] = 0;
        $jsondata['perc_margen'] = "0%";
    }

    echo json_encode($jsondata);
}


// Transformar a formato currency
function transform($usd, $currency) {
    $gtq = 7.8; // Conversion rate from USD to GTQ
    $amountInGTQ = $usd * $gtq;

    if ($currency === 'gtq') {
        // Format and return as GTQ currency
        return  'Q' . number_format($amountInGTQ, 5, '.', ',');
    } elseif ($currency === 'usd') {
        // Format and return as USD currency
        return '$' . number_format($usd, 5, '.', ',');
    }
    return null; // If currency is neither 'gtq' nor 'usd'
}

// ********  Lista de Holdings de Criptos  ***********************//
function get_holdings() {
    // Consulta SQL
    global $conn;
    $where = $_POST['formulario'];
    $jsondata = array();
    $table = "";
    $sum_inversion = 0;
    $sum_pnl = 0;

    $sql = "SELECT * FROM holdings";

    $result = $conn->query($sql);
    $row_count = $result->num_rows;

    if($row_count > 0){
        while($row = $result->fetch_array()){
            // Valores iniciales
            $moneda = $row['token'];
            $amount = $row['amount'];
            $dca = $row['dca'];
            // Calculos
            $inversion = floatval($amount * $dca);
            $current_price = get_binance_price($moneda);
            $pnl = $current_price * $amount;
            $xs = ($pnl / $inversion);
            // Fila de Totales
            $sum_inversion += $inversion;
            $sum_pnl += $pnl;
            // Transformación de Divisas
            $dca = transform($dca, 'usd');
            $inversionD = transform($inversion, 'usd');
            $inversionQ = transform($inversion, 'gtq');
            $pnlQ = transform($current_price * $amount, 'gtq');
            $pnlD = transform($current_price * $amount, 'usd');
            $current_price = transform($current_price, 'usd');

            $table .= "<tr>";
            $table .= "<td align='center'>".$moneda."</td>";
            $table .= "<td align='center'>".$amount."</td>";
            $table .= "<td align='center'>".$dca."</td>";
            $table .= "<td align='center'>".$current_price."</td>";
            $table .= "<td align='center'>".$inversionD."</td>";
            $table .= "<td align='center'>".$inversionQ."</td>";
            $table .= "<td align='center'>".$pnlD."</td>";
            $table .= "<td align='center'>".$pnlQ."</td>";
            $table .= "<td align='center'>".round($xs, 2)."</td>";
            $table .= "</tr>";
        }

        $table .= "<tr>";
        $table .= "<td align='center'>Totales</td>";
        $table .= "<td align='center'>"."</td>";
        $table .= "<td align='center'>"."</td>";
        $table .= "<td align='center'>"."</td>";
        $table .= "<td align='center'>".transform($sum_inversion, 'usd')."</td>";
        $table .= "<td align='center'>".transform($sum_inversion, 'gtq')."</td>";
        $table .= "<td align='center'>".transform($sum_pnl, 'usd')."</td>";
        $table .= "<td align='center'>".transform($sum_pnl, 'gtq')."</td>";
        $table .= "<td align='center'>"."</td>";
        $table .= "</tr>";


        $jsondata['holdings_table'] = $table;

    } else {
        $jsondata['holdings_table'] = "Sin datos para mostrar.";
    }

    echo json_encode($jsondata);
}


// *********************** INSERTAR ORDENES ***********************//
function insert_order() {
    // Obtiene los datos del formulario
    global $conn;
    $formData = $_POST["formulario"];
    $moneda = $formData['moneda'];
    $exchange = $formData['exchange'];
    $direccion = $formData['direccion'];
    $apalancamiento = $formData['apalancamiento'];
    $precioEntrada = $formData['precioEntrada'];
    $volumen = $formData['volumen'];
    $contratos = $volumen / $precioEntrada;
    $margen = $volumen / $apalancamiento;
    
    try {
        $sql = "INSERT INTO ordenes (moneda, exchange, direccion_, X, precio_entrada, volumen_entrada, contratos_, margen_, creada_) VALUES ('". $moneda ."','". $exchange ."','".$direccion."',". $apalancamiento .",". $precioEntrada .",". $volumen .",". $contratos .",". $margen .", NOW())";
        $conn->query($sql);
        $conn->close();
        // header("Location: ./index.php");
    } catch (Exception $e) {
        echo "An error occurred: " . $e->getMessage();
    }

}



?>
