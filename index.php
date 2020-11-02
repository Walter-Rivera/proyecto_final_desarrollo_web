<?php
    
    /*CONTROLADORES */
    /*requiriendo el archivo el archivo plantilla (controlador) */
    require_once "Controlador/plantilla.controler.php";

    /*requiriendo el archivo el archivo usuario (controlador) */
    require_once "Controlador/usuario.controler.php";

    
    
    /*MODELOS */

     /*requiriendo el archivo el archivo usuario (Modelo) */
     require_once "Modelo/usuario.model.php";

    

    /*instanciando la clase y creando un nuevo objeto */
    $planilla = new ContrlPlantilla();
    /*invocando un método de la clase */
    $planilla->controlerPlantilla();
