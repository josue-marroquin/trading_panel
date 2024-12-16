<?php

require("./db_conn.php");

if(isset($_POST["option"])) {
    $option = $_POST["option"];
    switch ($option) {
        case 'cerrar_orden':
            cerrar_orden();
            break;
        }
    }

// Actualizar estado de las ordenes del systema. (Cerrar ordenes)
function cerrar_orden() {
    global $conn;
    $jsondata = array();
    $data = $_POST["formulario"];
    $moneda = $data["moneda"];
    $exchange = $data["exchange"];
    $direccion = $data["direccion"];
    $sql = "UPDATE ordenes SET status_ = '1' WHERE moneda = '".$moneda."' AND exchange = '".$exchange"' AND direccion_ = '".$direccion."' ";
    // Run update
    $conn->query($sql);

    $jsondata['updated_time'] = ($time/60) . " mins.";
    echo json_encode($jsondata);
}

?>