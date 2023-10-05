<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trading Panel</title>
    <link rel="stylesheet" src="css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <meta http-equiv="refresh" content="600">
</head>
<body style="background-color:#acacac;">
    <div class="container mt-5">
        <h4>Nueva entrada:</h4>
        <form id="order_form">
            <div class="row">
                <div class="col-md-1">
                    <div class="form-group">
                        <select class="form-control form-control-sm" id="moneda" name="moneda">
                            <option disabled selected>Coin</option>
                            <option value="BTC">BTC</option>
                            <option value="ETH">ETH</option>
                            <option value="ADA">ADA</option>
                            <option value="TRX">ROSE</option>
                            <option value="XRP">XRP</option>
                            <option value="SOL">SOL</option>
                            <option value="BNB">BNB</option>
                            <option value="COTI">COTI</option>
                            <option value="TRX">TRX</option>
                            <option value="ALGO">ALGO</option>
                            <option value="AVAX">AVAX</option>
                            <option value="XMR">XMR</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <select class="form-control form-control-sm" id="exchange" name="exchange">
                            <option disabled selected>Exchange</option>
                            <option value="BINANCE">BINANCE</option>
                            <option value="QUANTFURY">QUANTFURY</option>
                            <option value="BITGET">BITGET</option>
                            <option value="BYBIT">BYBIT</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <select class="form-control form-control-sm" id="direccion">
                            <option disabled selected>Long/Short</option>
                            <option value="Long">Long</option>
                            <option value="Short">Short</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <select class="form-control form-control-sm" id="apalancamiento" name="apalancamiento">
                            <option disabled>X</option>
                            <option value="10">10</option>
                            <option value="20" selected>20X</option>
                            <option value="50">50</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <input type="number" class="form-control form-control-sm" id="precioEntrada" name="precioEntrada" step="0.0001" placeholder="P/Entrada">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <input type="number" class="form-control form-control-sm" id="volumen" name="volumen" placeholder="Volumen">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <input type="submit" id="guardar" class="btn btn-outline-dark btn-sm" value="Guardar">
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="container">
        <table class="table table-sm table-hover border">
            <thead>
                <tr align="center">
                    <th colspan="2">Proyeccion 1%</th>
                    <th colspan="2">Proyeccion 2%</th>
                </tr>
            </thead>
            <tbody>
                <tr align="center">
                    <td id="projecctionArribaPercent"></td>
                    <td id="proyeccionArribaPnl"></td>
                    <td id="proyeccionAbajoPercent"></td>
                    <td id="proyeccionAbajoPnl"></td>
                </tr>
            </tbody>
        </table>
    </div>
    
    <hr>

    <div class="container">
        <h4>Tablero:</h4>
        <table class="table table-sm table-hover">
            <thead>
                <tr align="center">
                    <th>Total Volumen</th>
                    <th>Total Margen</th>
                    <th>Total PnL</th>
                </tr>
            </thead>
            <tbody>
                <tr align="center">
                    <td id="vol"></td>
                    <td id="margen"></td>
                    <td id="pnl"></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="container">
        <table class="table table-sm table-hover">
            <thead>
                <tr align="center">
                    <th>#</th>
                    <th>Moneda</th>
                    <th>Exchange</th>
                    <th>L/S</th>
                    <th>Volumen</th>
                    <th>Margen</th>
                    <th>Entrada Promedio</th>
                    <th>1%</th>
                    <th>2%</th>
                    <th>Precio Actual</th>
                    <th>% Cambio</th>
                    <th>PnL</th>
                </tr>
            </thead>
            <tbody id="ordenes_table"></tbody>
        </table>
    </div>

    <hr>

    <div class="container">
        <h4>Historial de entradas:</h4>
        <table class="table table-sm table-hover">
            <thead>
                <tr align="center">
                    <th>#</th>
                    <th>Moneda</th>
                    <th>Exchange</th>
                    <th>L/S</th>
                    <th>X</th>
                    <th>Entrada</th>
                    <th>Volumen</th>
                    <th>Contratos</th>
                    <th>Margen</th>
                    <th>Apertura</th>
                    <th>Status</th>
                    <th>&nbsp;</th>
                </tr>
            </thead>
            <tbody id="inventario_ordenes"></tbody>
        </table>
    </div>

</body>
<script src="js/actions.js"></script>
</html>
