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

    <style>
        body {
            color: white;
            background-image: url('https://external-content.duckduckgo.com/iu/?u=https%3A%2F%2Fwallpaperaccess.com%2Ffull%2F395434.jpg&f=1&nofb=1&ipt=49cc32f3ef33304b376a9ff74b68abbbff07234deb824d70f5f7692db2b24af2&ipo=images');
            background-size: cover; /* Esta propiedad ajustará la imagen para cubrir todo el fondo */
            background-repeat: no-repeat; /* Evita que la imagen se repita */
            background-attachment: fixed; /* Fija la imagen en su lugar mientras se desplaza la página */
            margin: 0; /* Elimina el margen predeterminado del body */
            padding: 0; /* Elimina el padding predeterminado del body */
        }
    </style>
</head>
<body>

<div class="container-fluid mt-5">
    <h4>Nueva entrada:</h4>
    <form id="order_form">
        <div class="row">
            <div class="form-group col-md-2">
                <label for="moneda">Moneda</label>
                <select class="form-control form-control-md" id="moneda" name="moneda">
                    <!-- Opciones de moneda -->
                    <option selected>ADA</option>
                    <option value="BTC">BTC</option>
                    <option value="ETH">ETH</option>
                    <option value="ROSE">ROSE</option>
                    <option value="XRP">XRP</option>
                    <option value="HBAR">HBAR</option>
                    <option value="COTI">COTI</option>
                    <option value="MATIC">MATIC</option>
                    <option value="GALA">GALA</option>
                    <option value="SOL">SOL</option>
                    <option value="BNB">BNB</option>
                    <option value="DOT">DOT</option>
                    <option value="TRX">TRX</option>
                    <option value="DOGE">DOGE</option>
                    <option value="AVAX">AVAX</option>
                    <option value="MKR">MKR</option>
                </select>
            </div>
            <div class="form-group col-md-2">
                <label for="exchange">Exchange</label>
                <select class="form-control form-control-md" id="exchange" name="exchange">
                    <!-- Opciones de exchange -->
                    <option value="BITGET">BITGET</option>
                    <option value="BINANCE">BINANCE</option>
                    <option value="QUANTFURY">QUANTFURY</option>
                    <option value="BYBIT">BYBIT</option>
                </select>
            </div>
            <div class="form-group col-md-2">
                <label for="direccion">Dirección (L / S)</label>
                <select class="form-control form-control-md" id="direccion">
                    <!-- Opciones de dirección (L / S) -->
                    <option disabled selected>L / S</option>
                    <option value="Long">Long</option>
                    <option value="Short">Short</option>
                </select>
            </div>
            <div class="form-group col-md-2">
                <label for="apalancamiento">Apalancamiento (X)</label>
                <select class="form-control form-control-md" id="apalancamiento" name="apalancamiento">
                    <!-- Opciones de apalancamiento (X) -->
                    <option disabled>X</option>
                    <option value="10">10X</option>
                    <option value="20" selected>20X</option>
                    <option value="25">25X</option>
                    <option value="30">30X</option>
                    <option value="50">50X</option>
                </select>
            </div>
            <div class="form-group col-md-2">
                <label for="precioEntrada">Precio de Entrada</label>
                <input type="number" class="form-control form-control-md" id="precioEntrada" name="precioEntrada" step="0.0001" placeholder="P/Entrada">
            </div>
            <!-- div class="form-group col-md-2">
                <input type="submit" id="guardar" class="form-control form-control-md btn btn-info btn-md" value="Guardar">
            </div -->
        </div>
    </form>
</div>

<hr style="height:2px;border-width:0;width:75%; color:gray;background-color:black">

    <div class="container-fluid" id="proyeccion">
        <div class="row">
            <div class="col-md-4">
                <h4>Estrategia de Proporciones (X:1)</h4>
                <div class="row">
                    <div class="form-group col-md-3">
                        <label for="stopLoss">SL %</label>
                        <input type="number" step="0.01" class="form-control form-control-md" id="stopLoss" name="stopLoss" placeholder="%">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="maxLoss">Cap.</label>
                        <input type="number" step="0.01" class="form-control form-control-md" id="maxLoss" name="maxLoss" placeholder="$">
                        <input type="hidden" step="0.01" class="form-control form-control-md" id="volumen" name="volumen">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="stopLoss">&nbsp;</label>
                        <input type="button" id="proyeccionStrat_1" class="form-control form-control-md btn btn-secondary btn-sm" value="Proyectar">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="stopLoss">&nbsp;</label>
                        <input type="button" id="guardarStrat_1" class="form-control form-control-md btn btn-primary btn-sm" value="Guardar">
                    </div>
                </div>            

                <table class="table table-dark table-lg table-hover vertical-header-table">
                    <tbody>
                        <tr>
                            <th scope="row">Volumen a operar</th>
                            <td id="volumen_"></td>
                        </tr>
                        <tr>
                            <th scope="row">Margen</th>
                            <td id="margen_"></td>
                        </tr>
                        <tr>
                            <th scope="row">Stop Loss</th>
                            <td id="stopLossProyectado" class="text-warning"></td>
                        </tr>
                        <tr>
                            <th scope="row">Take Profit 1:1</th>
                            <td id="unoAuno" class="text-success"></td>
                        </tr>
                        <tr>
                            <th scope="row">Take Profit 2:1</th>
                            <td id="dosAuno" class="text-success"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-md-8">
            <div class="container">
                <h4>Tabla de Operaciones de Futuros:</h4>
                <table class="table table-sm table-dark table-hover">
                    <thead>
                        <tr align="center">
                            <th>#</th>
                            <th>Moneda</th>
                            <th>Exchange</th>
                            <th>L/S</th>
                            <th>X</th>
                            <th>Volumne</th>
                            <th>Margen</th>
                            <th>Entrada</th>
                            <th>10%</th>
                            <th>20%</th>
                            <th>P. Actual</th>
                            <th>Cambio</th>
                            <th>PnL</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody id="orders_table"></tbody>
                </table>
            </div>
    
    <hr style="height:2px;border-width:0;width:75%; color:gray;background-color:black">


    <br><br><br><br><br><br><br><br><br>
    <br><br><br><br><br><br><br><br><br>

</body>
<script src="js/actions.js"></script>
</html>
