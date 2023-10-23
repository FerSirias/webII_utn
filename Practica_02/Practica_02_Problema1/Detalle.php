<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle del Anime</title>
    <link href="style.css/style.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php
$animes = array(
    "Kage_no_Jitsuryokusha_ni_Naritakute!" => array(
        "nombre" => "Kage no Jitsuryokusha ni Naritakute!",
        "emisión" => "4 de octubre de 2023",
        "Creado por" => "Daisuke Aizawa",
    ),
    "The_Rising_of_the_Shield_Hero" => array(
        "nombre" => "The Rising of the Shield Hero",
        "emisión" => "6 de octubre de 2023",
        "Creado por" => "Aneko Yusagi",
    ),
    "Seiken_Gakuin_no_Makentsukai" => array(
        "nombre" => "Seiken Gakuin no Makentsukai",
        "emisión" => "2 de octubre de 2023",
        "Creado por" => "Yū Shimizu",
    ),
    "Konyaku_Haki_sareta_Reijou_wo_Hirotta_Ore_ga,_Ikenai_Koto_wo_Oshiekomu" => array(
        "nombre" => "Konyaku Haki sareta Reijou wo Hirotta Ore ga, Ikenai Koto wo Oshiekomu",
        "emisión" => "4 de octubre de 2023",
        "Creado por" => "Sametarō Fukada",
    )
);

// Obtener el nombre del anime de la URL
$nombreAnime = isset($_GET['anime']) ? $_GET['anime'] : 'desconocido';

$anime = $animes[$nombreAnime];
?> 
<table>
    <tr>
        <td rowspan="5" class="image-cell">
        <?php 
        $rutaimg = "images/concepto/normal/minis/Detallesphp/" . $nombreAnime . ".png";
        ?>
        <img src="<?php echo $rutaimg; ?>" alt="" id="ImageDetalle">
        </td>
        <th>Nombre</th>
        <td><?php echo $anime['nombre'] ?></td>
    </tr>
    <tr>
        <th>Emisión</th>
        <td><?php echo $anime['emisión']; ?></td>
    </tr>
    <tr>
        <th>Creado por</th>
        <td><?php echo $anime['Creado por']; ?></td>
    </tr>
    <tr>
        <th>Sinopsis</th>
        <td>
            <?php
            // Leer el contenido del archivo .txt
            $archi = file_get_contents(__DIR__ . "/docs/concepto/" . $nombreAnime . ".txt");
                    
            // Primera letra en mayúscula
            $archi = ucfirst($archi);

            // Convierte enter en <br>
            $archi = nl2br($archi);
                    
            // Imprimir el contenido
            echo $archi;
                    
            ?>
        </td>
    </tr>
</table>  
</body>
</html>
