<!--la clase main-sidebar es propia de adminLTE-->
<aside class="main-sidebar">
    <section class="sidebar">
        <!--vamos a realizar el conjunto de acciones dispuestas en el menu lateral-->
        <ul class="sidebar-menu">
        <?php
           echo'<li class="active">
                    <a href="ini">
                        <i class="fa fa-home"></i>
                        <span>Inicio</span>
                    </a>
                </li>';
           if($_SESSION["DESCRIPCION"]=="ADMINISTRADOR")
           {
                echo '<li>
                        <a href="usuario">
                            <i class="fa fa-user"></i>
                            <span>Usuarios</span>
                        </a>
                    </li>
                    <li>
                    <a href="perito">
                        <i class="fa fa-user-md"></i>
                        <span>Peritos</span>
                    </a>
                    </li>
                    <li>
                        <a href="seccion">
                            <i class="fa fa-list-ol"></i>
                            <span>Seccion</span>
                        </a>
                    </li>
                    <li>
                        <a href="tipoGestion">
                            <i class="fa fa-cogs"></i>
                            <span>Tipos de Gestiones</span>
                        </a>
                    </li>';
            }
            echo'<li>
                     <a href="gestion">
                        <i class="fa fa-plus-square"></i>
                        <span>Registrar Datos</span>
                     </a>
                </li>';

            echo'<li class="treeview">
                <a href="#"> 
                    <i class="fa fa-flag-checkered"></i>
                    <span>Reportes</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right" ></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li>
                        <a href="dictamenesEvacuados">
                            <i class="fa fa-circle-o-notch"></i>
                            <span>Dictámenes Evacuados</span>    
                        </a>
                    </li>
                    <li>
                        <a href="dictamenesPendientes">
                            <i class="fa fa-circle-o-notch"></i>
                            <span>Dictamenes Pendientes</span>    
                        </a>
                    </li>
                    <li>
                        <a href="dictamenesTranscritos">
                            <i class="fa fa-circle-o-notch"></i>
                            <span>Dictámenes Transcritos</span>    
                        </a>
                    </li>
                    <li>
                        <a href="ampliacionesPendientes">
                            <i class="fa fa-circle-o-notch"></i>
                            <span>Ampliaciones Pendientes</span>    
                        </a>
                    </li>
                    <li>
                        <a href="ampliacionesEvacuadas">
                            <i class="fa fa-circle-o-notch"></i>
                            <span>Ampliaciones Evacuadas</span>    
                        </a>
                    </li>
                </ul>
            </li>';
        ?>
        </ul>
        

    </section>

</aside>