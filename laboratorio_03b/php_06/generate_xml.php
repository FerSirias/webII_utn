<?php
//Hablitia conexion con el motor de MySql.
include_once("codigos/conexion1.inc");

// Supongamos que la función 'mysqli_connect' de 'conexion1.inc' asigna la conexión a la variable $conex.

// Comprobar si se ha enviado el código del departamento desde un formulario.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $dept_code = $_POST['dept_code']; // Obtiene el código del departamento del formulario.

    // Preparar la consulta SQL.
    $sql = "SELECT e.emp_no, CONCAT(e.first_name, ' ', e.last_name) AS full_name, t.title, s.salary
    FROM employees e
    INNER JOIN titles t ON e.emp_no = t.emp_no
    INNER JOIN salaries s ON e.emp_no = s.emp_no
    INNER JOIN dept_emp de ON e.emp_no = de.emp_no
    WHERE s.to_date = '9999-01-01' AND de.to_date = '9999-01-01'"; // '9999-01-01' se usa habitualmente para representar el empleo actual.

    if ($dept_code !== "0") {
        $sql .= " AND de.dept_no = ?";
    }

    $sql .= " ORDER BY full_name";

    // Preparar y ejecutar la consulta SQL.
    if ($stmt = mysqli_prepare($conex, $sql)) {
        if ($dept_code !== "0") {
            mysqli_stmt_bind_param($stmt, "s", $dept_code);
        }
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        // Crear el documento XML.
        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><enterprise></enterprise>');

        // Crear la sección de resumen.
        $summary = $xml->addChild('summary');

        // Agregar la información de los departamentos al XML.
        while ($row = mysqli_fetch_assoc($result)) {
            // Solo para el primer resultado o cuando cambia el departamento.
            if (!isset($department) || $department['name'] !== $row['dept_name']) {
                $department = $summary->addChild('department');
                $department->addChild('name', $row['dept_name']); // Asume que hay una columna dept_name en la consulta SQL.
            }

            // Agregar la información del empleado al departamento correspondiente.
            $employee = $department->addChild('employee');
            $employee->addChild('emp_no', $row['emp_no']);
            $employee->addChild('name', $row['full_name']);
            $employee->addChild('title', $row['title']);

            // Agregar salario actual, propuesto y la diferencia.
            $current_salary = $row['salary'];
            $proposed_salary = $current_salary * 1.10; // Asumimos un aumento del 10%.
            $employee->addChild('c_salary', $current_salary);
            $employee->addChild('n_salary', $proposed_salary);
            $employee->addChild('difference', $proposed_salary - $current_salary);
        }

        // Guardar el archivo XML.
        $xml->asXML('salary_increase_summary.xml');
        echo 'El archivo XML se ha generado. <a href="salary_increase_summary.xml">Visualizar XML</a>';
    } else {
        echo "Error al preparar la consulta: " . mysqli_error($conex);
    }
}
?>
