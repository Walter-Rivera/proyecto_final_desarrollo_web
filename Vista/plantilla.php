
<?php
    /*indicamos que vamos a usar session start para evitar tener
      conflicto con diversas versiones de php que puedan ser utilizadas*/
    session_start();
?>


<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Gestiones TFM</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!--agregando el logo de la Institución-->
  <link rel="icon" href="Vista/img/Plantilla/logoIcono.png">
  <!--
      CÓDIGO CSS
  -->
  <link rel="stylesheet" href="Vista/dist/css/personalizacion.css">
  <!-- Bootstrap 4.5 -->
  <link rel="stylesheet" href="Vista/bower_components/bootstrap/dist/css/bootstrap.css">
  <!-- Font Awesome (para íconos) -->
  <link rel="stylesheet" href="Vista/bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="Vista/bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="Vista/dist/css/AdminLTE.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="Vista/dist/css/skins/_all-skins.min.css">

  <!-- DataTables -->
  <link rel="stylesheet" href="Vista/bower_components/datatables.net-bs/css/dataTables.bootstrap4.css">
  <link rel="stylesheet" href="Vista/bower_components/datatables.net-bs/css/responsive.bootstrap4.min.css">

  <!-- Google Font (letra)-->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  
    <!--
      CÓDIGO JAVASCRIPT Y PLUGINS
  -->
  <!-- jQuery 3.5 -->
  <script src="Vista/bower_components/jquery/dist/jquery.min.js"></script>
  <!-- Bootstrap 4.5-->
  <script src="Vista/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
  <!-- SlimScroll -->
  <script src="Vista/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
  <!-- FastClick -->
  <script src="Vista/bower_components/fastclick/lib/fastclick.js"></script>
  <!-- AdminLTE App -->
  <script src="Vista/dist/js/adminlte.min.js"></script>
  <!-- DataTables -->
  <script src="Vista/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
  <script src="Vista/bower_components/datatables.net/js/dataTables.bootstrap4.min.js"></script>
  <script src="Vista/bower_components/datatables.net/js/dataTables.responsive.min.js"></script>
  <script src="Vista/bower_components/datatables.net/js/responsive.bootstrap4.min.js"></script>

  <!--SweetAlert2-->
  <script src="Vista/plugins/SweetAlert2/SweetAlert2.js"></script>

</head>


<!--BODY-->
<body class="hold-transition skin-blue sidebar-collapse sidebar-mini login-page">

   <!-- =============================================== -->
   <?php
      /*vamos a validar que el contenido del los módulos
      en la página principal estén disponibles cuando ya esté logueado
      el usuario*/
      if(isset($_SESSION["login"]) && $_SESSION["login"]=="adelante" )
      {
        /*Site wrapper*/
        echo '<div class="wrapper">';
        /*incluyendo el archivo cabecera.php este tendrá la definición del 
        encabezado de la pantalla de inicio */
        include "Modulos/cabecera.php"; 

        /*incluyendo el modulo que permitirá utilizar el menú lateral */
        include "Modulos/menu_lateral.php";   

        /*validando si en la variable get que nos brinde el 
        .htaccess viene el parámetro ruta */
        if(isset($_GET["ruta"]))
        {
          /*comparamos con el nombre de los módulos que definimos si el parametro
          que trae el método GET es correcto */
          if($_GET["ruta"]=="ini"|| $_GET["ruta"]=="usuario"
            || $_GET["ruta"]=="ampliacionesEvacuadas" || $_GET["ruta"]=="ampliacionesPendientes"
            || $_GET["ruta"]=="dictamenesEvacuados" || $_GET["ruta"]=="dictamenesPendientes"
            || $_GET["ruta"]=="dictamenesTranscritos" || $_GET["ruta"]=="gestion"
            || $_GET["ruta"]=="perito" || $_GET["ruta"]=="seccion"
            || $_GET["ruta"]=="tipoGestion" || $_GET["ruta"]=="salir")
            {
              /*vamos a incluir el modulo para ajustar la altura de nuestro 
              menu de inicio lateral y verlo de forma correcta*/
              include "Modulos/".$_GET["ruta"].".php";    
            }
            else
            {
              /*En dado caso nos incluyan en la url alguna información que no coincida
              con los parámetors que estamos aceptando el la ruta*/
              include "Modulos/404.php";
            }
          
        }
        else
        {
          /*sino se están enviando variables GET en la ruta, ponemos por default la pagina
          de inicio (dashboards) */
            include "Modulos/ini.php";
        }
      
        
        /*vamos a agregar el pie de página */
        include "Modulos/pie_pagina.php";
    
        /*wrapper */
        echo '</div>';

        /*fin del if para validar si el usuario está ya logueado */
      }
      else
      {
        /*la persona se debe loguear antes de accesar al sistema */
        /*inclimos el módulo login */
        include "Modulos/login.php";
        /*vamos a agregar el pie de página */
        include "Modulos/pie_paginalogin.php";
      }

     
   ?>
  



<script type="text/javascript" src="Vista/js/plantilla.js"></script>
<script src="Vista/js/usuario.js"></script>
<script src="Vista/js/perito.js"></script>






</body>
</html>
