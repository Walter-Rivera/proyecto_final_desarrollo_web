<header class="main-header">
   <!--Colocando el logotipo-->
   <a href="ini" class="logo">
       <!--logotipo que estará disponible al ocultar el sidebar-->
       <span class="logo-mini">
           <img src="Vista/img/Plantilla/logo-pe.png" alt="logo-pequeño" class="img-responsive" style="padding:10px">
       </span>
        <!--logotipo que estará disponible al expandir el sidebar-->
        <span class="logo-lg">
            <img src="Vista/img/Plantilla/grande1.png" alt="logo-grande" class="img-responsive" style="padding: 10px 20px">
        </span>
   </a> 
<!--realizando barra de navegación-->
<nav class="navbar navbar-static-top" role="navigation">
    <!--botón que contrae el menú-->
    <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Navegación</span> 
    </a>

    <!--usuario (menú)-->
    <div class="navbar-custom-menu">
        <!--Esta lista va a albergar las opciones que dispondrá  el usuario
        para -->
        <ul class="nav navbar-nav">
            <!--esta clase user-menu la provee admin-LTE-->
            <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <!--vamos a colocar una imagen por default para el perfil del usuario-->
                    <img src="Vista/img/Usuario/perfil.jpg" alt="foto-perfil-usuario" class="user-image"/>
                    <!--colocar el nombre del usuario a la par de la fotografía-->
                    <span class="hidden-xs"><?php echo $_SESSION["NOMBRES"].' '.$_SESSION["APELLIDOS"];?></span>
                </a>
                <!--Para alternar el menú desplegable-->
                <ul class="dropdown-menu">
                    <li class="user-footer">
                        <!--Mostrar el contenido de lado derecho-->
                        <div class="pull-right">
                            <!--Botón para salir, llamamos al módulo salir para ejecutarlo
                            al momento que presionen click sobre el botón-->
                            <a href="salir" class="btn btn-default btn-flat">Salir</a>
                        </div>
                    </li>
                </ul>
            </li>       
        </ul>
    </div> 
       
</nav>

</header>