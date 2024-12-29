<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" src="css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!--script src="js/jquery.js"></script-->
    <style>
        body {
            color: white;
            background-color: #3e4444;
        }
        /* Estilos generales para el tbody */
        tbody, thead {
            font-size: 20px;  /* Cambia el tamaño de la letra */
            color: #ffffff;   /* Cambia el color de la letra */
            background-color: #333333;  /* Fondo gris oscuro para el tbody */
            /**line-height: 1.8;  /* Aumenta el espaciado entre líneas */
            text-align: center;  /* Centra el texto */
        }

        /* Estilo para las filas pares */
        tbody tr:nth-child(even) {
            background-color: #393939;  /* Fondo más claro para filas pares */
        }

        /* Estilo para las filas impares */
        tbody tr:nth-child(odd) {
            background-color: #333333;  /* Fondo más oscuro para filas impares */
        }

        table {
            box-shadow: 0px 0px 24px 0px #fbefcc;
        }

        th [contenteditable="true"] {
            background-color: #343430; /* Color de fondo para indicar que es editable */
            color: white;
        }

        #profit {
            border: 2px solid #22aa32;
        }

        #loss {
            border: 2px solid #aa4b22;
        }

        .binance {
            box-shadow: 5px 5px 12px 0px #000000 inset;
            background-color:#ffcc00;
            color: #3b3a30;
            font-size: 30px;
            font-weight: 900;
        }

        .bitget {
            box-shadow: 5px 5px 12px 0px #000000 inset;
            background-color:#00ccff;
            color: #3b3a30;
            font-size: 30px;
            font-weight: 900;
        }

        .quantfury {
            box-shadow: 5px 5px 12px 0px #000000 inset;
            background-color:#4fa190;
            color: #3b3a30;
            font-size: 30px;
            font-weight: 900;
        }

        .btc_price {
            /* box-shadow: 5px 5px 12px 0px #808080 inset; */
            background-color:#181a1c;
            color: #29caf6;
            font-size: 30px;
            font-weight: 900;
        }

    </style>

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
                <a class="nav-link" style="color:white;" href="dashboard.php">Dashboard</a>
                </li>
            </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-md-12">
                <table class="table table-dark table-lg table-hover">
                    <thead>
                        <tr align="center">
                            <th>Moneda</th>
                            <th>Balance</th>
                            <th>DCA</th>
                            <th>Precio Actual</th>
                            <th>Inversion $</th>
                            <th>Inversion Q</th>
                            <th>Retorno $</th>
                            <th>Retorno Q</th>
                            <th>X</th>
                        </tr>
                    </thead>
                    <tbody id="holdings_table">
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<script src="js/actions_dashboard.js"></script>
</html>
