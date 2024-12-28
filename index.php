<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trading Panel</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="css/styles.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <!-- meta http-equiv="refresh" content="600" -->
</head>
<body>
<nav class="navbar navbar-expand-lg bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand text-white" href="#">Trading Panel</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link active text-white" aria-current="page" href="dashboard">Dashboard</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white" href="dashboard_holdings">Holdings</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<div class="container-fluid mt-5 mb-5">
    <div class="row">
        <div class="form-group col-md-2">
            <h4>Nueva entrada: <span class="badge bg-success" id="guardado_"></span></h4>
            <form id="order_form">
                <div class="row">
                    <div class="col-md-6">
                        <label for="moneda">Moneda</label>
                        <select class="form-control form-control-md" id="moneda" name="moneda">
                            <!-- Opciones de moneda -->
                            <option selected>ADA</option>
                            <option value="BTC">BTC</option>
                            <option value="HBAR">HBAR</option>
                            <option value="XRP">XRP</option>
                            <option value="ETH">ETH</option>
                            <option value="TON">TON</option>
                            <option value="SUI">SUI</option>
                            <option value="GALA">GALA</option>
                            <option value="SOL">SOL</option>
                            <option value="RSR">RSR</option>
                            <option value="BNB">BNB</option>
                            <option value="AVAX">AVAX</option>
                            <option value="DOT">DOT</option>
                            <option value="ROSE">ROSE</option>
                            <option value="TRX">TRX</option>
                            <option value="XLM">XLM</option>
                            <option value="COTI">COTI</option>
                            <option value="DOGE">DOGE</option>
                            <option value="FIL">FIL</option>
                            <option value="ICP">ICP</option>
                            <option value="PEPE">PEPE</option>
                            <option value="MKR">MKR</option>
                            <option value="ORDER">ORDER</option>
                            <option value="WIF">WIF</option>
                            <option value="VELO1">VELO</option>
                            <option value="CHILLGUY">CHILLGUY</option>
                            <option value="WLD">WLD</option>
                            <option value="COS">COS</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="exchange">Exchange</label>
                        <select class="form-control form-control-md" id="exchange" name="exchange">
                            <!-- Opciones de exchange -->
                            <option value="BITGET">BITGET</option>
                            <option value="BINANCE">BINANCE</option>
                            <option value="QUANTFURY">QUANTFURY</option>
                            <option value="BYBIT">BYBIT</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="direccion">Dirección</label>
                        <select class="form-control form-control-md" id="direccion">
                            <!-- Opciones de dirección (L / S) -->
                            <option disabled selected>L / S</option>
                            <option value="Long">Long</option>
                            <option value="Short">Short</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="apalancamiento">X</label>
                        <select class="form-control form-control-md" id="apalancamiento" name="apalancamiento">
                            <!-- Opciones de apalancamiento (X) -->
                            <option disabled>X</option>
                            <option value="10">10X</option>
                            <option value="20" selected>20X</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="precioEntrada">Precio de Entrada</label>
                        <input type="number" class="form-control form-control-md" id="precioEntrada" name="precioEntrada" step="0.0001" placeholder="P/Entrada">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="volumen">Volumen</label>
                        <input type="number" step="0.01" class="form-control form-control-md" id="volumen_" name="volumen" placeholder="$">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="hsl">HStop Loss %</label>
                        <input type="number" step="0.01" class="form-control form-control-md" id="hsl" name="hsl" value="10">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="htp">HTake Profit %</label>
                        <input type="number" step="0.01" class="form-control form-control-md" id="htp" name="htp" value="20">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-12">
                        <input type="button" id="calcularNuevoDCA" class="form-control form-control-md btn btn-info btn-sm" value="Calcular DCA">
                    </div>
                </div> 
                <div class="row">
                    <div class="form-group col-md-12">
                        <input type="button" id="guardarOrden" class="form-control form-control-md btn btn-success btn-sm" value="Guardar Orden">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-12">
                        <input type="button" id="updateHValues" class="form-control form-control-md btn btn-primary btn-sm" value="Actualizar SL & TP">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-12">
                        <input type="button" id="details" class="form-control form-control-md btn btn-dark btn-sm" value="Detalles">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-12">
                        <input type="button" id="close" class="form-control form-control-md btn btn-danger btn-sm" value="Cerrar Orden">
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-10">
            <div class="row">
                <div class="col-md-4">
                    <div class="row">
                        <h4 class="text-white">Proyección de Nueva entrada: <span class="badge bg-info" id="new_dca"></span></h4>
                        <div class="col-md-12">
                            <table class="table table-dark table-lg table-hover vertical-header-table">
                                <tbody>
                                    <tr>
                                        <th scope="row">Nuevo DCA</th>
                                        <td id="nuevoDCA" class="text-info"></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Nuevo Volumen:</th>
                                        <td id="nuevoVolumen"></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Nuevo Margen:</th>
                                        <td id="nuevoMargen"></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Contratos/Tokens Actuales:</th>
                                        <td id="contratosActuales"></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Suma Contratos/Tokens</th>
                                        <td id="sumaContratos" class="text-info"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <h4 class="text-white">AudioBot Timer:</h4>
                        <div class="col-md-12">
                            <form id="ticker_timer">
                                <table class="table table-lg table-dark">
                                    <thead>
                                        <th>Timer</th>
                                        <th>New</th>
                                        <th>&nbsp;</th>
                                    </thead>
                                    <tbody>
                                        <td id="ticker_time">15 min.</td>
                                        <td><input type="number" id="timer_val" class="form-control form-control-md" placeholder="5"></td>
                                        <td><input type="button" id="timer_sync" class="form-control form-control-md btn btn-outline-info btn-sm" value="Enviar"></td>
                                    </tbody>
                                </table>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <h4 class="text-white">Tabla de Operaciones de Futuros: <span class="badge bg-dark" id="updatedAt"></span></h4>
                    <table class="table table-lg table-dark table-hover">
                        <thead>
                            <tr align="center">
                                <th>#</th>
                                <th>Moneda</th>
                                <th>PnL</th>
                                <th>P. Actual</th>
                                <th>Entrada</th>
                                <th>Distancia</th>
                                <th>X</th>
                                <th>Volumen</th>
                                <th>Margen</th>
                            </tr>
                        </thead>
                        <tbody id="orders_table"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="container mt-5 mb-5">
            <h2 class="text-center">HBAR</h2>

            <!-- Datos de Operación -->
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-hover table-dark table-bordered">
                        <thead class="header">
                            <tr>
                                <th colspan="2" class="sub-header text-center"><h4>Datos de Operación</h4></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="header">Posición</td>
                                <td class="long text-center">LONG</td>
                            </tr>
                            <tr>
                                <td class="header">Total Tokens/Contratos</td>
                                <td>37089.586</td>
                            </tr>
                            <tr>
                                <td class="header">Entrada Promediada / DCA</td>
                                <td>0.05392</td>
                            </tr>
                            <tr>
                                <td class="header">Apalancamiento (Xs)</td>
                                <td>20</td>
                            </tr>
                            <tr>
                                <td class="header">Proyección Stop Loss</td>
                                <td class="sl-projection">$0.0420</td>
                            </tr>
                            <tr>
                                <td class="header">Proyección Take Profit</td>
                                <td class="tp-projection">$0.2500</td>
                            </tr>
                            <tr>
                                <td class="header">Volumen</td>
                                <td>2000</td>
                            </tr>
                            <tr>
                                <td class="header">Margen</td>
                                <td>100</td>
                            </tr>
                            <tr>
                                <td class="header">Fecha apertura</td>
                                <td>21/09/24</td>
                            </tr>
                            <tr>
                                <td class="header">% SL Proyectado</td>
                                <td class="sl-projection">-22.11%</td>
                            </tr>
                            <tr>
                                <td class="header">% TP Proyectado</td>
                                <td class="tp-projection">363.62%</td>
                            </tr>
                            <tr>
                                <td class="header">% Gan/Margen</td>
                                <td>7272.40%</td>
                            </tr>
                            <tr>
                                <td class="header">% Perd/Margen</td>
                                <td>442.24%</td>
                            </tr>
                            <tr>
                                <td class="header">Profit Estimado</td>
                                <td class="profit">$7,272.397</td>
                            </tr>
                            <tr>
                                <td class="header">Loss Estimado</td>
                                <td class="loss">-$442.237</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Entradas de Precio y Proyecciones -->
                <div class="col-md-6 mb-5">
                    <table class="table table-hover table-dark table-bordered">
                        <thead class="header">
                            <tr>
                                <th>#</th>
                                <th>Fecha</th>
                                <th>Precio Entrada</th>
                                <th>Volumen</th>
                                <th>Tokens/Contratos</th>
                                <th>DCA Historico</th>
                            </tr>
                        </thead>
                        <tbody id="data-body">
                            <!-- Example row, you would populate with your data dynamically -->
                            <tr>
                                <td>1</td>
                                <td>22/07/24</td>
                                <td contenteditable="true" class="editable">0.0718</td>
                                <td contenteditable="true" class="editable">50</td>
                                <td>696.3788</td>
                                <td>0.0714</td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>22/07/24</td>
                                <td contenteditable="true" class="editable">0.0714</td>
                                <td contenteditable="true" class="editable">50</td>
                                <td>700.280</td>
                                <td>0.0714</td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>22/07/24</td>
                                <td contenteditable="true" class="editable">0.0714</td>
                                <td contenteditable="true" class="editable">50</td>
                                <td>700.280</td>
                                <td>0.0710</td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td>22/07/24</td>
                                <td contenteditable="true" class="editable">0.0714</td>
                                <td contenteditable="true" class="editable">50</td>
                                <td>700.280</td>
                                <td>0.0690</td>
                            </tr>
                            <tr>
                                <td>5</td>
                                <td>22/07/24</td>
                                <td contenteditable="true" class="editable">0.0644</td>
                                <td contenteditable="true" class="editable">50</td>
                                <td>700.280</td>
                                <td>0.0673</td>
                            </tr>
                        </tbody>
                    </table>
            
                    <button id="add-row-btn" class="btn btn-primary">Agregar nueva orden</button>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
<script src="js/assets/router.js"></script>
<script src="js/assets/transactions.js"></script>
<script src="js/actions/get_orders_table.js"></script>
<script src="js/actions/guardar_orden.js"></script>
<script src="js/actions/calculate_dca.js"></script>
<script src="js/actions/update_h_values.js"></script>
</html>
