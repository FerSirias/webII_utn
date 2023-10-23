<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Problema 2</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php
    session_start();
    require_once('Subprocesos.php');
    $nombre_jugador = $_SESSION['nombre']; 
    $Monto_Jugador =  $_SESSION['monto'];
    $cantidad_dados =  $_SESSION['cantidadDados'];
    $Cantidad_apostada = 0;
    $resultado = "Win";
    $dado1=1;
    $dado2=1;
    $dado3=1;
    ?>
    <div class="container">
        <div class="row mb-5">
            <div class="col-xl-6 p-0">
                <table>
                    <tr>
                        <td>Jugador:</td><th><?php echo $nombre_jugador; ?></th>
                    </tr>
                </table>
            </div>
            <div class="col-xl-6 p-0">
                <table>
                    <tr>
                        <td>Monto Disponible</td><th><?php echo $Monto_Jugador; ?></th>
                    </tr>
                    <tr>
                        <td>Monto Disponible</td><th><?php echo $Cantidad_apostada; ?></th>
                    </tr>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-4">
                <h1>Dados</h1>
                <form method="POST" action="">
                    <input type="submit" name="Jugar" value="Jugar">
                    <?php
                    if (isset($_POST['Jugar'])) {
                        if($cantidad_dados == 2){
                            $dado1=rand(1,6);
                            $dado2=rand(1,6);
                            echo obtenerCaraDado($dado1);
                            echo obtenerCaraDado($dado2);
                        }elseif ($cantidad_dados == 3){   
                            $dado1=rand(1,6);
                            $dado2=rand(1,6);
                            $dado3=rand(1,6);
                            echo obtenerCaraDado($dado1);
                            echo obtenerCaraDado($dado2);
                            echo obtenerCaraDado($dado3);
                        }
                    }
                    ?>
                </form>
            </div>
            <div class="col-xl-4">
                <h1>Tabla de apuestas</h1>
                <?php CargarTabla($cantidad_dados); ?>
            </div>
            <div class="col-xl-4">
            <h1>Estado</h1>
            <?php 
                if ($resultado == "Win") {
                    echo "<img src='/Img/Sticker/Happy.png' alt='Sticker_Win'>";
                } elseif ($resultado == "Lose") {
                    echo "<img src='/Img/Sticker/angry.png' alt='Sticker_lose'>";
                } else {
                    echo "<img src='/Img/Sticker/neutral.png' alt='Sticker_empty'>";
                }
            ?>
            </div>
        </div>
    </div>       
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
