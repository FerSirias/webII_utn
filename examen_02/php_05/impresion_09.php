<?php
require('codigos/fpdf.php');

class PDF extends FPDF {
    // Header del documento
    function Header() {
        // Logo
        $this->Image('logo.png',10,6,30); // Asegúrate de que el logo exista en la ruta especificada
        $this->SetFont('Arial', 'B', 12);
        // Añadir la cabecera con los detalles del cliente
        if (!empty($this->customerHeader)) {
            foreach ($this->customerHeader as $line) {
                $this->Cell(0, 10, $line, 0, 1);
            }
        }
        $this->Ln(5);
    }

    // Footer del documento
    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }

    // Función para establecer la cabecera del cliente
    function SetCustomerHeader($header) {
        $this->customerHeader = $header;
    }

    function LoadInvoiceData($file) {
        // Leer las líneas del archivo
        $lines = file($file);
        $data = [];
        foreach($lines as $line) {
            $data[] = explode(';', trim($line));
        }
        return $data;
    }
}

// Verifica si se ha enviado el código del empleado
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['customerID'], $_POST['startDate'], $_POST['endDate'])){
    $customerID = $_POST['customerID'];
    $startDate = $_POST['startDate'];
    $endDate = $_POST['endDate'];
    }

    // Obtener la información del cliente de la base de datos
    $customerInfoQuery = $db->prepare("SELECT CompanyName, ContactName, Country, City, PostalCode FROM customers WHERE CustomerID = ?");
    $customerInfoQuery->bind_param('s', $customerID);
    $customerInfoQuery->execute();
    $result = $customerInfoQuery->get_result();
    $customerInfo = $result->fetch_assoc();
    $customerInfoQuery->close();        

    // Obtener los detalles de las facturas de la base de datos
    // Debes reemplazar 'your_invoice_table' con el nombre real de tu tabla de facturas y ajustar la consulta SQL.
    $invoicesQuery = $db->prepare("SELECT * FROM orders WHERE CustomerID = ? AND OrderDate BETWEEN ? AND ?");
    $invoicesQuery->bind_param('sss', $customerID, $startDate, $endDate);
    $invoicesQuery->execute();
    $invoicesResult = $invoicesQuery->get_result();
    $invoices = $invoicesResult->fetch_all(MYSQLI_ASSOC);
    $invoicesQuery->close();  

    $pdf = new PDF();
    $pdf->AliasNbPages();
    $pdf->AddPage();
    $pdf->SetFont('Arial', '', 12);

    include_once("codigos/conexion3.inc");
    $pdf->AddCustomerHeader();
    $pdf->AddInvoiceDetails();

    // Generar y mostrar el PDF
    $pdf->Output();
    exit;

    $conex->close();
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
                                    <label for="emp_id">Código Cliente:</label>
                                    <input type="text" class="form-control" id="emp_id" name="emp_id" required>
                                    <label for="emp_id">Fecha Inicio:</label>
                                    <input type="text" class="form-control" id="startDate" name="startDate" required>
                                    <label for="emp_id">Código Final:</label>
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
?>
