<!DOCTYPE html>
		<html lang="en">
		<head>
			<?php
			   require_once(appRoot . '/views/includes/enca.php');
			?>
			<title><?php echo siteName; ?></title>
		</head>
		<body class="container">
			<header class="col-12">
				<?php
					require_once(appRoot . '/views/includes/menu.php');
				?>        
			</header>
			<!-- Contenido principal -->
<div class="container mt-5">
    <h1 class="display-4 text-center">Sobre Huellitas de Amor Caldera</h1>
    <p class="lead text-center">Un refugio con historia y pasión por el cuidado animal.</p>
    <hr class="my-4">

    <!-- Historia del Refugio -->
    <div class="mt-4">
        <h2>Historia</h2>
        <p>Desde 1985, Huellitas de Amor Caldera ha sido el santuario de miles de animales rescatados. Nuestro fundador, Don José Caldera, comenzó con la simple misión de proporcionar un hogar seguro a los animales callejeros de la ciudad. Con el tiempo, el refugio creció, y ahora cuenta con más de 50 hectáreas de terreno dedicadas exclusivamente al bienestar de los animales.</p>
    </div>

    <!-- Programas Educativos -->
    <div class="mt-4">
        <h2>Programas Educativos</h2>
        <p>Creemos en la educación como herramienta de cambio. Es por eso que ofrecemos programas educativos para escuelas, donde enseñamos a los jóvenes la importancia del cuidado animal y el respeto por todas las formas de vida.</p>
    </div>

    <!-- Actividades y Eventos -->
    <div class="mt-4">
        <h2>Actividades y Eventos</h2>
        <p>Todo el año, realizamos diversas actividades, desde jornadas de adopción hasta ferias educativas y eventos de recaudación de fondos. Estas actividades nos permiten conectar con la comunidad y continuar con nuestra misión.</p>
    </div>

</div>
	<!-- Galería de imágenes -->
    <h2 class="mt-5">Nuestros perros son...</h2>
    <div class="row">
        <!-- Primera fila de imágenes -->
        <div class="col-md-6">
            <div class="card mb-6">
                <img src="public/img/perritoamoroso.jpg" class="card-img-top" alt="Imagen 2">
                <div class="card-body">
                    <p class="card-text">JUGUETONES</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-6">
                <img src="public/img/perritodormilon.jpg" class="card-img-top" alt="Imagen 3">
                <div class="card-body">
                    <p class="card-text">DORMILONES</p>
                </div>
            </div>
        </div>
        
        <!-- Segunda fila de imágenes -->
        <div class="col-md-6">
            <div class="card mb-6">
                <img src="public/img/perritounico.jpg" class="card-img-top" alt="Imagen 5">
                <div class="card-body">
                    <p class="card-text">ÚNICOS</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-6">
                <img src="public/img/perrofeliz.png" class="card-img-top" alt="Imagen 6">
                <div class="card-body">
                    <p class="card-text">FELICES</p>
                </div>
            </div>
        </div>
    </div>
</div>

			<main class="col-12 linea_sep">
				<?php
					if(isLoggedIn()){
						echo "Usted es: " . $_SESSION['usuario'];
					}else{
						echo "Si aún no formas parte de nuestra organización, 
						Por favor autentiquese ante nosotros...";
					}
				?>
			</main>
			<footer class="col-12 linea_sep">
				<?php
					require_once(appRoot . '/views/includes/pie.php');
				?>
			</footer>
		</body>
</html>
