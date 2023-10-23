<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/style.css">
    <title>Ingreso de Usuarios</title>
</head>
<body>
    <?php
    require_once('Subprocesos.php');
    ?>
    <div class="container">
        <div class="row">
            <div class="col-xl-12">
                <h1>Ingreso de Usuarios</h1>
                <form action="" method="post">
                    <div class="form-group">
                        <label for="fechaNacimiento">Fecha de Nacimiento:</label>
                        <input type="date" name="fechaNacimiento" id="fechaNacimiento" required>
                    </div>
                    <div class="form-group">
                        <input type="submit" value="Validar Edad">
                    </div>
                </form>
                <?php
                if ($_SERVER["REQUEST_METHOD"] == "POST") {

                    $fechaNacimiento = $_POST["fechaNacimiento"];
                    echo ValidarEdad($fechaNacimiento);
                }
                ?>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
