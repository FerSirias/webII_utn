<?php 
function CargarTabla($cantidad_dados){  
    if($cantidad_dados == 2){
        echo "<table border='1'>";
        echo "<tr>";
        echo "<td></td>";
        echo "<td>2</td>";
        echo "<td>3</td>";
        echo "<td>4</td>";
        echo "</tr>";
        echo "<tr>";
        echo "<td>5</td>";
        echo "<td>6</td>";
        echo "<td>7</td>";
        echo "<td>8</td>";
        echo "</tr>";
        echo "<tr>";
        echo "<td>9</td>";
        echo "<td>10</td>";
        echo "<td>11</td>";
        echo "<td>12</td>";
        echo "</tr>";
        echo "</table>";
    }elseif ($cantidad_dados == 3){
        echo "<table border='1'>";
        echo "<tr>";
        echo "<td></td>";
        echo "<td></td>";
        echo "<td>3</td>";
        echo "<td>4</td>";
        echo "</tr>";
        echo "<tr>";
        echo "<td>5</td>";
        echo "<td>6</td>";
        echo "<td>7</td>";
        echo "<td>8</td>";
        echo "</tr>";
        echo "<tr>";
        echo "<td>9</td>";
        echo "<td>10</td>";
        echo "<td>11</td>";
        echo "<td>12</td>";
        echo "</tr>";
        echo "<tr>";
        echo "<td>13</td>";
        echo "<td>14</td>";
        echo "<td>15</td>";
        echo "<td>16</td>";
        echo "</tr>";
        echo "</table>";
    }
}

function ValidarEdad($fechaNacimiento){
    $edad = calcularEdad($fechaNacimiento);
  
    if ($edad >= 21) {
        echo '<form action="Juego.php" method="post">';
        echo '<div class="form-group">';
        echo '<label for="nombre">Nombre del Jugador:</label>';
        echo '<input type="text" name="nombre" id="nombre" required>';
        echo '</div>';
        echo '<div class="form-group">';
        echo '<label for="monto">Monto en Dinero:</label>';
        echo '<input type="number" name="monto" id="monto" min="0" step="0.01" required>';
        echo '</div>';
        echo '<div class="form-group">';
        echo '<label for "cantidadDados">Cantidad de Dados (2-3):</label>';
        echo '<input type="number" name="cantidadDados" id="cantidadDados" min="2" max="3" required>';
        echo '</div>';
        echo '<div class="form-group">';
        echo '<input type="submit" value="Ingresar">';
        echo '</div>';
        echo '</form>';
        
    } else {
        echo '<p>Lo siento, debes ser mayor de 21 años para ingresar.</p>';
    }
    
}

function calcularEdad($fechaNacimiento) {
    $hoy = new DateTime();
    $nacimiento = new DateTime($fechaNacimiento);
    $edad = $hoy->diff($nacimiento);
    return $edad->y;
}


function obtenerCaraDado($numero) {
    $carasDado = [
        1 => "dado1.png",
        2 => "dado2.png",
        3 => "dado3.png",
        4 => "dado4.png",
        5 => "dado5.png",
        6 => "dado6.png"
    ];

    $rutaimg = "/Img/dados/" . $carasDado[$numero];
    if (file_exists($_SERVER['DOCUMENT_ROOT'] . $rutaimg)) {
        echo "<img src='$rutaimg' alt='Cara del dado $numero'>";
    } else {
        echo "La imagen no existe en la ruta especificada.";
    }

}
?>