<?php
require('codigos/fpdf.php');

class PDF extends FPDF {
    // Header del documento
    function Header() {
        // Logo
        $this->Image('imagenes/logos/logo_empresa.png', 10, 10, 20);
        // Arial bold 15
        $this->SetFont('Arial', 'B', 15);
        // Mover a la derecha para centrar el título
        $this->Cell(80);
        // Título
        $this->Cell(30, 10, 'Listado de Peliculas', 0, 1, 'C');
        // Salto de línea
        $this->Ln(20);
    }

    // Footer del documento
    function Footer() {
        // Posición: a 1.5 cm del final
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial', 'I', 8);
        // Número de página
        $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }

    // Cargar datos de películas
    function LoadData($categoryName, $movies) {
        $this->SetFont('Arial','B',12);
        $this->Cell(0, 10, 'Categoria ' . $categoryName . ':', 0, 1, 'L');
        $this->SetFont('Arial','',12);
        foreach($movies as $row) {
            $this->Cell(30, 10, $row['ID'], 1);
            $this->Cell(70, 10, $row['Nombre'], 1);
            $this->Cell(40, 10, $row['Existencias'], 1);
            $this->Cell(30, 10, $row['Anio'], 1);
            $this->Ln();
        }
    }
}

// Crear una nueva instancia de PDF
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','',12);

// Verifica si se ha enviado el id de la categoría
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['film_id'])) {
    $idCategoria = $_POST['film_id'];

    include_once("codigos/conexion4.inc");

    // Consulta para obtener el nombre de la categoría
    $queryCat = "SELECT title FROM film WHERE film_id = ?";
    $stmtCat = $conex->prepare($queryCat);
    $stmtCat->bind_param('i', $idCategoria);
    $stmtCat->execute();
    $stmtCat->bind_result($categoryName);
    $stmtCat->fetch();
    $stmtCat->close();

    // Consulta para obtener las películas de la categoría
    $queryMovies = "SELECT film.film_id AS ID, title AS Nombre, inventory_id AS Existencias, release_year AS Anio FROM film_category JOIN film ON film_category.film_id = film.film_id JOIN inventory ON inventory.film_id = film.film_id WHERE category_id = ? ORDER BY title";
    $stmtMovies = $conex->prepare($queryMovies);
    $stmtMovies->bind_param('i', $idCategoria);
    $stmtMovies->execute();
    $resultMovies = $stmtMovies->get_result();
    $movies = $resultMovies->fetch_all(MYSQLI_ASSOC);
    $stmtMovies->close();

    // Cargar los datos en el PDF
    $pdf->LoadData($categoryName, $movies);

    // Generar y enviar el PDF al navegador
    $pdf->Output('I', 'listado_peliculas.pdf');

    $conex->close();
} else {
    // Inicia la parte de HTML para el formulario
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Film Report</title>
        <!-- Bootstrap CSS -->
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
        <div class="container mt-5">
            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <div class="card">
                        <div class="card-header">
                            Ingrese el código del empleado que deseas buscar
                        </div>
                        <div class="card-body">
                            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                                <div class="form-group">
                                    <label for="emp_id">Código pelicula:</label>
                                    <input type="text" class="form-control" id="film_id" name="film_id" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Generar PDF</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Optional JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    </body>
    </html>
    <?php
}
?>