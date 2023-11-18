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
        $this->Cell(50, 10, 'Salaries Study Summary', 0, 1, 'C');
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
        $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }

    // Una función para agregar la información del empleado
    function AddEmployee($emp_no, $emp_name, $hire_date) {
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, "Employee $emp_no - $emp_name", 0, 1, 'L');
        $this->SetFont('Arial', '', 12);
        $this->Cell(0, 10, "Hire date: $hire_date", 0, 1, 'L');
        $this->Ln(5);
    }

    // Una función para agregar los salarios
    function AddSalaries($salaries) {
        // Colores, anchura de línea y fuente en negrita para la cabecera
        $this->SetFillColor(169, 169, 169); // Gris
        $this->SetTextColor(255);
        $this->SetDrawColor(128, 0, 0);
        $this->SetLineWidth(.3);
        $this->SetFont('', 'B');
        // Cabecera
        $w = array(40, 30, 30, 30, 30, 30); // Ajuste las anchuras de las columnas
        $header = array('Title', 'From Date', 'To Date', 'From Date', 'To Date', 'Year Salary');
        foreach($header as $i => $col)
            $this->Cell($w[$i], 7, $col, 1, 0, 'C', true);
        $this->Ln();
        // Restauración de colores y fuentes para los datos
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('');
        // Datos
        $fill = false; // Alternar el relleno
        foreach($salaries as $row) {
            foreach($row as $i => $col) {
                // Alineación a la derecha para la columna de salario
                $align = ($i == 5) ? 'R' : 'L';
                $this->Cell($w[$i], 6, $col, 'LR', 0, $align, $fill);
            }
            $this->Ln();
            $fill = !$fill;
        }
        // Línea de cierre
        $this->Cell(array_sum($w), 0, '', 'T');
    }
}

// Verifica si se ha enviado el código del empleado
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['emp_id'])) {
    $emp_id = $_POST['emp_id'];

    $pdf = new PDF();
    $pdf->AliasNbPages();
    $pdf->AddPage();
    $pdf->SetFont('Arial', '', 12);

    include_once("codigos/conexion3.inc");

    $query = "SELECT e.emp_no, CONCAT(e.first_name, ' ', e.last_name) AS emp_name, e.hire_date, t.title, s.from_date, s.to_date, s.salary FROM employees e JOIN titles t ON e.emp_no = t.emp_no JOIN salaries s ON e.emp_no = s.emp_no WHERE e.emp_no = ? ORDER BY s.from_date ASC";

    if ($stmt = $conex->prepare($query)) {
        $stmt->bind_param('i', $emp_id);
        $stmt->execute();
        $stmt->bind_result($emp_no, $emp_name, $hire_date, $title, $from_date, $to_date, $salary);

        $salaries = [];
        while ($stmt->fetch()) {
            $salaries[] = [
                $title,
                $from_date,
                $to_date == '9999-01-01' ? 'Current position' : $to_date,
                $from_date,
                $to_date,
                $salary
            ];
        }
        $pdf->AddEmployee($emp_no, $emp_name, $hire_date);
        $pdf->AddSalaries($salaries);
        $pdf->Output('I', 'salaries_report.pdf');
        $stmt->close();
    } else {
        echo "Error al preparar la consulta: " . $conex->error;
    }

    $conex->close();
}else {
    // Inicia la parte de HTML para el formulario
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Employee Report</title>
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
                                    <label for="emp_id">Código empleado:</label>
                                    <input type="text" class="form-control" id="emp_id" name="emp_id" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Generar Reporte</button>
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


