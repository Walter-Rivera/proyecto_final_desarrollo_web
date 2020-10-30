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
                            <span>Usuario</span>
                        </a>
                    </li>';
            }
            if($_SESSION["DESCRIPCION"]=="ADMINISTRADOR" || $_SESSION["DESCRIPCION"]=="ENCARGADO_ALMACEN")
            {
            echo'<li>
                    <a href="proveedor">
                        <i class="fa fa-user-secret"></i>
                        <span>Proveedor</span>
                    </a>
                </li>
                <li>
                    <a href="categoria">
                        <i class="fa fa-list-ol"></i>
                        <span>Categorías</span>
                    </a>
                </li>
                <li>
                    <a href="producto">
                        <i class="fa fa-product-hunt"></i>
                        <span>Productos</span>
                    </a>
                </li>
                <li>
                    <a href="cliente"> 
                        
                        <i class="fa fa-users"></i>
                        <span>clientes</span>
                    </a>
                </li>
                <li class="treeview">
                    <a href="#"> 
                        <i class="fa fa-cart-arrow-down"></i>
                        <span>Distribución</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right" ></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li>
                            <a href="distribucion_envio">
                                <i class="fa fa-circle-o-notch"></i>
                                <span>Egreso de Productos</span>    
                            </a>
                        </li>
                        <!--Administración de distribución-->
                        <li>
                            <a href="gestion_distribucion">
                                <i class="fa fa-circle-o-notch"></i>
                                <span>Administación</span>    
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="treeview">
                    <a href="#"> 
                        <i class="fa fa-buysellads"></i>
                        <span>Aprovisionamiento</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right" ></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li>
                            <a href="aprovisionamiento_envio">
                                <i class="fa fa-circle-o-notch"></i>
                                <span>Ingreso De Productos</span>    
                            </a>
                        </li>
                        <li>
                            <a href="gestion_aprovisionamiento">
                                <i class="fa fa-circle-o-notch"></i>
                                <span>Administación</span>    
                            </a>
                        </li>
                    </ul>
                </li>';
            }


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
                        <a href="sugerencia_aprovisionamiento">
                            <i class="fa fa-circle-o-notch"></i>
                            <span>Solicitar Productos</span>    
                        </a>
                    </li>
                    <li>
                        <a href="historial_aprovisionamiento">
                            <i class="fa fa-circle-o-notch"></i>
                            <span>Hist. Aprovisionamiento</span>    
                        </a>
                    </li>
                    <li>
                        <a href="historial_distribucion">
                            <i class="fa fa-circle-o-notch"></i>
                            <span>Hist. Distribución</span>    
                        </a>
                    </li>
                    <li>
                        <a href="consumo_seccion">
                            <i class="fa fa-circle-o-notch"></i>
                            <span>Consumo Prod.(Secc)</span>    
                        </a>
                    </li>
                        <!--TOP 20 Productos más consumidos -->
                    <li>
                        <a href="top20_masconsumo">
                            <i class="fa fa-circle-o-notch"></i>
                            <span>Top20 Prod.+ demanda</span>    
                        </a>
                    </li>
                        <!--TOP 10 Productos menos consumidos -->
                    <li>
                        <a href="top10_menosconsumo">
                            <i class="fa fa-circle-o-notch"></i>
                            <span>Top10 Prod.- demanda</span>    
                        </a>
                    </li>
                    <li>
                        <a href="consumo_cliente">
                            <i class="fa fa-circle-o-notch"></i>
                            <span>Consumo Prod.(Cliente)</span>    
                        </a>
                    </li>
                </ul>
            </li>';
        ?>
        </ul>
        

    </section>

</aside>