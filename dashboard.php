<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="css/styles.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!--script src="js/jquery.js"></script-->
</head>
<body>

    <nav class="navbar navbar-expand-lg bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" style="color:white;" href="#">Dashboard</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav">
                    <li class="nav-item">
                    <a class="nav-link active" style="color:white;" aria-current="page" href="../panel">Trading Panel</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link" style="color:white;" href="dashboard_holdings.php">Holdings</a>
                    </li>
                </ul>
            </div>
            <div class="topnav-centered">
                <marquee behavior="slide" direction="right" scrollamount="5">Have <strong>control</strong> over a high risk and you'll get a high reward! </marquee>
            </div>
        </div>
    </nav>

    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-dark table-md table-hover">
                    <thead>
                        <tr align="center">
                            <th colspan="2">BTC Current Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr align="center">
                            <td id="btc_price" class="btc_price"></td>
                            <td id="btc_price_q" class="btc_price"></td>
                        </tr>
                    </tbody>
                </table>

                <table class="table table-dark table-md table-hover">
                    <thead>
                        <tr align="center">
                            <th>PnL</th>
                            <th>Volumen</th>
                            <th>Margen</th>
                            <th>% Margen</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr align="center">
                            <td id="pnl_total" style="font-size: 30px"></td>
                            <td id="vol_total" style="font-size: 30px"></td>
                            <td id="margen_total" style="font-size: 30px"></td>
                            <td id="percMargen_total" style="font-size: 30px">
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-dark table-sm table-hover">
                    <thead>
                        <tr align="center">
                            <th></th>
                            <th>Binance</th>
                            <th>Bitget</th>
                            <th>Quantfury</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr align="center">
                            <td>Balance</td>
                            <td class="binance">$850.00</td>
                            <td class="bitget">$1033.00</td>
                            <td class="quantfury">$10,000.00</td>
                        </tr>
                        <tr align="center">
                            <td>Margen</td>
                            <td class="binance" id="margen_binance"></td>
                            <td class="bitget" id="margen_bitget"></td>
                            <td class="quantfury" id="margen_quantfury"></td>
                        </tr>
                        <tr align="center">
                            <td>HSL</td>
                            <td id="hsl_binance" class="binance"></td>
                            <td id="hsl_bitget" class="bitget"></td>
                            <td id="hsl_quantfury" class="quantfury"></td>
                        </tr>
                        <tr align="center">
                            <td>HTP</td>
                            <td id="htp_binance" class="binance"></td>
                            <td id="htp_bitget" class="bitget"></td>
                            <td id="htp_quantfury" class="quantfury"></td>
                        </tr>
                        <tr align="center">
                            <td>PnL $</td>
                            <td id="binance" class="binance"></td>
                            <td id="bitget" class="bitget"></td>
                            <td id="quantfury" class="quantfury"></td>
                        </tr>
                        <tr align="center">
                            <td>PnL Q</td>
                            <td id="binance_q" class="binance"></td>
                            <td id="bitget_q" class="bitget"></td>
                            <td id="quantfury_q" class="quantfury"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="container-fluid mt-4">
        <table class="table table-sm table-dark table-hover">
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
                    <th>HSL</th>
                    <th>HTP</th>
                </tr>
            </thead>
            <tbody id="orders_table"></tbody>
        </table>
    </div>

</body>
<script src="js/assets/router.js"></script>
<script src="js/assets/transactions.js"></script>
<script src="js/actions_dashboard.js"></script>
</html>
