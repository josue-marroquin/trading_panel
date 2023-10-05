<?php

require("db_conn.php");

if(isset($_POST["option"])) {
    $option = $_POST["option"];
    switch ($option) {
        case 'orders_list':
            orders_list();
            break;
        case 'insert_order':
            insert_order();
            break;
        case 'inventario':
            inventario_de_ordenes();
            break;
        case 'borrar_orden':
            borrar_orden();
            break;
        }
    }

// ********  Lista de Ordenes - View  ***********************//
function orders_list() {
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
    $sql = "SELECT moneda, exchange, direccion_, SUM(volumen_entrada) AS suma_volumen, SUM(margen_) AS suma_margen, SUM(volumen_entrada) / SUM(contratos_) AS entrada_promedio FROM ordenes WHERE status_ = 1 GROUP BY moneda, exchange, direccion_";

    if($result = $conn->query($sql)){
        while($row = $result->fetch_array()){
            $moneda = $row['moneda'];
            $exchange = $row['exchange'];
            $direccion = $row['direccion_'];
            $suma_volumen = $row["suma_volumen"];
            $suma_margen = $row["suma_margen"];
            $entrada_promedio = $row["entrada_promedio"];
            $proyeccion_1 = $direccion == 'Long' ?  ($entrada_promedio + ($entrada_promedio * 0.01)) : ($entrada_promedio - ($entrada_promedio * 0.01));
            $proyeccion_2 = $direccion == 'Long' ?  ($entrada_promedio + ($entrada_promedio * 0.02)) : ($entrada_promedio - ($entrada_promedio * 0.02));
            $precio_actual = get_binance_price($moneda);
            $porcentaje_movimiento_actual = (($precio_actual / $entrada_promedio) *100) -100;
            $pnl_actual = ($suma_volumen * $porcentaje_movimiento_actual) / 100;
            $direct_color = ($direccion == 'Long' ? "style='color:green;'" : "style='color:red;'");

            if($direccion == 'Short') {
                $pnl_actual = $pnl_actual * (-1);
                $pnl_color = $pnl_actual < 0 ? "style='color:red;'" : "style='color:green;'";
            } else if ($direccion == 'Long') {
                $pnl_color = $pnl_actual < 0 ? "style='color:red;'" : "style='color:green;'";
            } else {
                $pnl_color = "style='color:blue;'"; 
            }

            $total_volumen += $suma_volumen;
            $total_margen += $suma_margen;
            $total_pnl += $pnl_actual;

            $table .= "<tr>";
            $table .= "<td align='center'>".$index."</td>";
            $table .= "<td align='center'>".$moneda."</td>";
            $table .= "<td align='center'>".$exchange."</td>";
            $table .= "<td align='center'".$direct_color.">".$direccion."</td>";
            $table .= "<td align='center'><strong>".round($suma_volumen, 4)."</strong></td>";
            $table .= "<td align='center'>".round($suma_margen, 4)."</td>";
            $table .= "<td align='center'>".round($entrada_promedio, 4)."</td>";
            $table .= "<td align='center'>".round($proyeccion_1, 4)."</td>";
            $table .= "<td align='center'>".round($proyeccion_2, 4)."</td>";
            $table .= "<td align='center'>".round($precio_actual, 5)."</td>";
            $table .= "<td align='center'>" .round($porcentaje_movimiento_actual, 3)."%</td>";
            $table .= "<td align='center' ".$pnl_color.">".round($pnl_actual, 4)."</td>";
            $table .= "</tr>";

            $index += 1;

        }
    }

    $jsondata['orders_table'] = $table;
    $jsondata['pnl_acumulado'] = round($total_pnl, 3);
    $jsondata['volumen_acumulado'] = round($total_volumen, 3);
    $jsondata['margen_acumulado'] = round($total_margen, 3);

    echo json_encode($jsondata);
}



// Inventario - Tabla con toda la informacion de las Ordenes
function inventario_de_ordenes() {
    // Consulta SQL
    global $conn;
    $jsondata = array();
    $table = "";
    $index = 1;
    $direct_color = "";
    $estado = "";
    $sql = "SELECT * FROM ordenes ORDER BY exchange";

    if($result = $conn->query($sql)){
        while($row = $result->fetch_array()){
            $id_ = $row['ID_orden'];
            $moneda = $row['moneda'];
            $exchange = $row['exchange'];
            $direccion = $row['direccion_'];
            $apalancamiento = $row["X"];
            $precio_entrada = $row["precio_entrada"];
            $volumen_entrada = $row["volumen_entrada"];
            $contratos = $row['contratos_'];
            $margen = $row['margen_'];
            $status = $row['status_'];
            $creacion = $row['creada_'];

                if($direccion == 'Short') {
                    $direct_color = "style='color:red;'";
                } else if ($direccion == 'Long') {
                    $direct_color = "style='color:green;'";
                } else {
                    $pnl_color = "style='color:blue;'";
                }

                if($status == 1) {
                    $estado = "Activa";
                } else if ($status == 0) {
                    $estado = "Cerrada";
                }

            $table .= "<tr>";
            $table .= "<td align='center'>".$index."</td>";
            $table .= "<td align='center'>".$moneda."</td>";
            $table .= "<td align='center'>".$exchange."</td>";
            $table .= "<td align='center'".$direct_color.">".$direccion."</td>";
            $table .= "<td align='center'>".$apalancamiento."</td>";
            $table .= "<td align='center'>".round($precio_entrada, 4)."</td>";
            $table .= "<td align='center'>".round($volumen_entrada, 4)."</td>";
            $table .= "<td align='center'>".round($contratos, 4)."</td>";
            $table .= "<td align='center'>" .round($margen, 3)."</td>";
            $table .= "<td align='center'>".$creacion."</td>";
            $table .= "<td align='center'>".$estado."</td>";
            $table .= "<td align='center'><button data-id=".$id_." class='btn btn-sm btn-warning borrar'>Borrar</td>";
            $table .= "</tr>";

            $index += 1;
        }
    }

    $jsondata['inventory_table'] = $table;
    echo json_encode($jsondata);
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


// *********************** ACTUALIZAR STATUS DE ORDENES ***********************//
function borrar_orden() {
    // Obtiene los datos del formulario
    global $conn;
    $data = '';
    $formData = $_POST["formulario"];
    $id = $formData['id'];
    
    try {
        $sql = "UPDATE ordenes SET status_ = 0 WHERE ordenes.ID_orden = ".$id;
        $conn->query($sql);
        $conn->close();
        // header("Location: ./index.php");
    } catch (Exception $e) {
        echo "An error occurred: " . $e->getMessage();
    }

}

?>
