<?php
    
    /*CONTROLADORES */
    /*requiriendo el archivo el archivo plantilla (controlador) */
    require_once "Controlador/plantilla.controler.php";

    /*requiriendo el archivo el archivo usuario (controlador) */
    require_once "Controlador/usuario.controler.php";
    require_once "Controlador/perito.controler.php";
    require_once "Controlador/seccion.controler.php";
    


    
    
    /*MODELOS */

     /*requiriendo el archivo el archivo usuario (Modelo) */
     require_once "Modelo/usuario.model.php";
     require_once "Modelo/perito.model.php";
     require_once "Modelo/seccion.model.php";


    

    /*instanciando la clase y creando un nuevo objeto */
    $planilla = new ContrlPlantilla();
    /*invocando un método de la clase */
    $planilla->controlerPlantilla();
