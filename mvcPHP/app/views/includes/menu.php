<nav class="navbar navbar-expand letra_menu navbar-light bg-light">
    <!-- Logo de la página -->
    <a class="navbar-brand" href="#">
        <img src="<?php echo urlRoot; ?>/public/img/huellitasamor.jpeg" alt="Logo" class="navbar-logo">
    </a>

    <!-- Botón del navbar -->
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown">
        <span class="navbar-toggler-icon"></span>
    </button>
    
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="<?php echo urlRoot; ?>/index">Inicio</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo urlRoot; ?>/pages/about">Acerca de..</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo urlRoot; ?>/pages/eventos">Eventos</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo urlRoot; ?>/pages/cuidadosCaninos">Cuidado Responsable</a>
            </li>
			<li class="nav-item">
                <a class="nav-link" href="#">???</a>
            </li>
            	<li class="nav-item">
                <?php
                if(isLoggedIn()){
                    echo '<a class="nav-link" href="'. urlRoot .'/users/logout">Salir</a>';
                }else{
                    echo '<a class="nav-link" href="'. urlRoot .'/users/login">Ingresar</a>';
                }
                ?>
            </li>
        </ul>
    </div>
</nav>
 
