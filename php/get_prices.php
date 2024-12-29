<?php

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

?>