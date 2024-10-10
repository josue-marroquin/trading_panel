<?php

require("db_conn.php");

if(isset($_POST["option"])) {
    $option = $_POST["option"];
    switch ($option) {
        case 'posiciones':
            get_positions_from_DB();
            break;
        case 'ordenes':
            get_orders_from_DB();
            break;
        case 'insert_order':
            insert_order();
            break;
        case 'projections':
            projections();
            break;
        }
    }


//*** Obtener el precio actual de Binace para cada moneda Operada ****/
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


// Extraer ordenes Guardadas desde el tablero
// ********  Lista de Ordenes - View  ***********************//
function projections() {
    // Consulta SQL
    global $conn;
    $jsondata = array();
    $table = "";
    $pnl_color = "";
    $direct_color = "";
    $index = 1;
    $total_volumen = 0;
    $total_margen = 0;
    $total_pnl = 0;
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
            $proyeccion_1 = $direccion == 'Long' ?  ($entrada_promedio + ($entrada_promedio * 0.1)) : ($entrada_promedio - ($entrada_promedio * 0.1));
            $proyeccion_2 = $direccion == 'Long' ?  ($entrada_promedio + ($entrada_promedio * 0.2)) : ($entrada_promedio - ($entrada_promedio * 0.2));
            $precio_actual = get_binance_price($moneda);
            $porcentaje_movimiento_actual = (($precio_actual / $entrada_promedio) *100) -100;
            $pnl_actual = ($suma_volumen * $porcentaje_movimiento_actual) / 100;
            $direct_color = ($direccion == 'Long' ? "style='color:#b5e7a0;'" : "style='color:#feb236;'");
            
            // Colores de la data
            if($direccion == 'Short') {
                $row_bg_color = "style='background-color:#feb236;'";
                $pnl_actual = $pnl_actual * (-1);
                $pnl_color = $pnl_actual < 0 ? "style='color:#feb236;'" : "style='color:#b5e7a0;'";
            } else if ($direccion == 'Long') {
                $row_bg_color = "style='background-color:#82b74b;'";
                $pnl_color = $pnl_actual < 0 ? "style='color:#feb236;'" : "style='color:#b5e7a0;'";
            } else {
                $pnl_color = "style='color:blue;'"; 
            }

            $total_volumen += $suma_volumen;
            $total_margen += $suma_margen;
            $total_pnl += $pnl_actual;

            $table .= "<tr>";
            $table .= "<td align='center' ". $row_bg_color .">".$index."</td>";
            $table .= "<td align='center'>".$moneda."</td>";
            $table .= "<td align='center' ".$pnl_color.">".round($pnl_actual, 4)."</td>";
            $table .= "<td align='center'><strong>".round($precio_actual, 5)."<strong></td>";
            $table .= "<td align='center'>".round($entrada_promedio, 5)."</td>";
            $table .= "<td align='center'>" .round($porcentaje_movimiento_actual, 3)."%</td>";
            $table .= "<td align='center'>".$exchange."</td>";
            $table .= "<td align='center'>".$x."</td>";
            $table .= "<td align='center'><strong>".round($suma_volumen, 4)."</strong></td>";
            $table .= "<td align='center'>".round($suma_margen, 4)."</td>";
            $table .= "<td align='center'>".round($proyeccion_1, 4)."</td>";
            $table .= "<td align='center'>".round($proyeccion_2, 4)."</td>";
            $table .= "</tr>";

            $index += 1;

        }

        $porcentaje_del_margen = ($total_pnl/$total_margen);
        $jsondata['orders_table'] = $table;
        $jsondata['pnl_acumulado'] = "$" . round($total_pnl, 4);
        $jsondata['volumen_acumulado'] = "$" . round($total_volumen, 4);
        $jsondata['margen_acumulado'] = "$" . round($total_margen, 4);
        $jsondata['perc_margen'] = round(($porcentaje_del_margen*100),2) . "%";

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





// Extraer informacion de ordenes de la base de datos
function get_orders_from_DB() {
    // Consulta SQL
    global $conn;
    $jsondata = array();
    $table = "";
    $pnl_color = "";
    $direct_color = "";
    $index = 1;
    $total_volumen = 0;
    $total_margen = 0;
    $total_pnl = 0;
    $porcentaje_del_margen = 0;

    $sql = "SELECT id, symbol, price, origQty, type, side, origType, time FROM trading_orders ORDER BY symbol ASC";

    $result = $conn->query($sql);
    $row_count = $result->num_rows;

    if ($row_count > 0) {
        while ($row = $result->fetch_array()) {
            $id = $row['id'];
            $symbol = $row['symbol'];
            $side = $row['side'] == 'SELL' ? 'SHORT' : ($row['side'] == 'BUY' ? 'LONG' : $row['side']);
            $price = $row['price'];
            $origQty = $row['origQty'];
            $volumen = $price * $origQty;
            $type = $row['type'];
            # $side = $row['side'];
            $time = $row['time'];

            // Calcular el tiempo transcurrido desde $hora_entrada
            $hora_actual = time(); // Tiempo actual en segundos
            $hora_entrada_unix = strtotime($time); // Convertir la hora de entrada a tiempo Unix
            $tiempo_transcurrido = $hora_actual - $hora_entrada_unix;

            // Calcular horas y minutos transcurridos
            $horas = floor($tiempo_transcurrido / 3600);
            $minutos = floor(($tiempo_transcurrido % 3600) / 60);

            // Procesa los datos como desees y agrega a la tabla
            // Por ejemplo, puedes agregarlos así:
            $table .= "<tr>";
            $table .= "<td align='center'>".$index."</td>";
            $table .= "<td align='center'>".$symbol."</td>";
            $table .= "<td align='center'>".$side."</td>";
            $table .= "<td align='center'>".$price."</td>";
            $table .= "<td align='center'>".$volumen."</td>";
            $table .= "<td align='center'>".$type."</td>";
            $table .= "<td align='center'>".$origType."</td>";
            $table .="<td>" . $horas . " horas, " . $minutos . " minutos</td>"; // Agregar el tiempo transcurrido
            $table .= "</tr>";

            $index += 1;
        }

        $jsondata['orders_table'] = $table;

    } else {
        $jsondata['orders_table'] = "Sin registros para mostrar.";
    }

    echo json_encode($jsondata);
}
    


// Extraer informacion de operaciones de la base de datos
function get_positions_from_DB() {

    global $conn;
    $table = "";
    $pnl_color = "";
    $direct_color = "";
    $jsondata = array();
    $total_volumen = 0;
    $total_margen = 0;
    $total_pnl = 0;
    $porcentage_del_margen = 0;
    // Consulta MySQL para obtener los datos
    $sql = "SELECT symbol, position_exchange, entry_price, position_direction, leverage, mark_price, unrealized_profit, last_trade_time FROM trading_positions";
    $result = $conn->query($sql);
    $row_count = $result->num_rows;

    if($row_count > 0){
        while ($row = $result->fetch_array()) {
            $symbol = $row['symbol'];
            $exchange = $row['position_exchange'];
            $precio_entrada = $row['entry_price'];
            $direccion = $row['position_direction'];
            $volumen = $row['leverage'];
            $precio_actual = $row['mark_price'];
            $pnl_actual = $row['unrealized_profit'];
            $hora_entrada = $row['last_trade_time'];
            $direct_color = ($direccion == 'LONG' ? "style='color:green;'" : "style='color:red;'");

            // Calcular el tiempo transcurrido desde $hora_entrada
            $hora_actual = time(); // Tiempo actual en segundos
            $hora_entrada_unix = strtotime($hora_entrada); // Convertir la hora de entrada a tiempo Unix
            $tiempo_transcurrido = $hora_actual - $hora_entrada_unix;

            // Calcular horas y minutos transcurridos
            $horas = floor($tiempo_transcurrido / 3600);
            $minutos = floor(($tiempo_transcurrido % 3600) / 60);

            if($direccion == 'SHORT') {
                $pnl_color = $pnl_actual < 0 ? "style='color:red;'" : "style='color:green;'";
            } else if ($direccion == 'LONG') {
                $pnl_color = $pnl_actual > 0 ? "style='color:red;'" : "style='color:green;'";
            } else {
                $pnl_color = "style='color:blue;'"; 
            }

            $total_volumen += $volumen;
            $total_margen += $volumen/20;
            $total_pnl += $pnl_actual;

            $table .="<tr>";
            $table .="<td>" . $symbol . "</td>";
            $table .="<td>" . $exchange . "</td>";
            $table .="<td ".$direct_color.">" . $direccion . "</td>"; 
            $table .="<td>" . $precio_entrada . "</td>";
            $table .="<td>" . $precio_actual . "</td>";
            $table .="<td>" . $volumen . "</td>";
            $table .="<td ".$pnl_color.">" . $pnl_actual . "</td>";
            $table .="<td>" . $horas . " horas, " . $minutos . " minutos</td>"; // Agregar el tiempo transcurrido
            $table .="</tr>";
        }

        $porcentaje_del_margen = ($total_pnl/$total_margen);
        $jsondata['pnl_acumulado'] = round($total_pnl, 4);
        $jsondata['volumen_acumulado'] = round($total_volumen, 4);
        $jsondata['margen_acumulado'] = round($total_margen, 4);
        $jsondata['perc_margen'] = round(($porcentaje_del_margen*100),2) . "%";
        $jsondata['posiciones_DbTable'] = $table;

    } else {

        $table .= "<tr><td colspan='7'>No se encontraron resultados.</td></tr>";
        $porcentaje_del_margen = 0;
        $jsondata['pnl_acumulado'] = 0;
        $jsondata['volumen_acumulado'] = 0;
        $jsondata['margen_acumulado'] = 0;
        $jsondata['perc_margen'] = "0%";
        $jsondata['posiciones_DbTable'] = $table;
    }

    // Cerrar la conexión a la base de datos
    $conn->close();
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
