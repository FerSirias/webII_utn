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
        $this->Cell(30, 10, 'Reporte de Ingresos', 0, 1, 'C');
        // Salto de línea
        $this->Ln(10);
    }

    // Footer del documento
    function Footer() {
        // Posición: a 1.5 cm del final
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial', 'I', 8);
        // Número de página
        $this->Cell(0, 10, 'Página ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }

    // Una función para agregar los ingresos por alquiler
    function AddRentalIncome($rentals) {
        // Aquí iría el código para añadir los datos al PDF
        // ...
    }
}

// Crear una nueva instancia de PDF
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','',12);

// Verifica si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['startDate'], $_POST['endDate'])) {
    $startDate = $_POST['startDate'];
    $endDate = $_POST['endDate'];

    // Aquí deberías incluir la conexión a tu base de datos
    include_once("codigos/conexion4.inc");

    // Consulta SQL para obtener los ingresos por alquiler de películas
    // Deberás reemplazar 'rental' y 'income' con los nombres reales de tu tabla y columna
    $query = "SELECT od.ProductID AS ID, p.ProductName AS Nombre, c.CategoryName AS Genero, SUM(r.amount) AS Monto
          FROM order_details od
          JOIN products p ON od.ProductID = p.ProductID
          JOIN categories c ON p.CategoryID = c.CategoryID
          JOIN rental r ON p.ProductID = r.ProductID
          WHERE r.rental_date BETWEEN ? AND ?
          GROUP BY od.ProductID, p.ProductName, c.CategoryName
          ORDER BY SUM(r.amount) DESC";
    $stmt = $conex->prepare($query);
    $stmt->bind_param('ss', $startDate, $endDate);
    $stmt->execute();
    $result = $stmt->get_result();
    $rentals = $result->fetch_all(MYSQLI_ASSOC);

    // Cargar los datos en el PDF
    $pdf->AddRentalIncome($rentals);

    // Generar y enviar el PDF al navegador
    $pdf->Output('I', 'reporte_ingresos.pdf');

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
                         Ingrese las fechas de renta:
                     </div>
                     <div class="card-body">
                         <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                             <div class="form-group">
                                 <label for="emp_id">Fecha inicio ingreso:</label>
                                 <input type="text" class="form-control" id="startDate" name="startDate" required>
                                 <label for="emp_id">Fecha final ingreso:</label>
                                 <input type="text" class="form-control" id="endDate" name="endDate" required>
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