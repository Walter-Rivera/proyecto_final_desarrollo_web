<?php
    
    /*CONTROLADORES */
    /*requiriendo el archivo el archivo plantilla (controlador) */
    require_once "Controlador/plantilla.controler.php";

    /*requiriendo el archivo el archivo categoria (controlador) */
    require_once "Controlador/categoria.controler.php";

    /*requiriendo el archivo el archivo cliente (controlador) */
    require_once "Controlador/cliente.controler.php";

    /*requiriendo el archivo el archivo producto (controlador) */
    require_once "Controlador/producto.controler.php";

    /*requiriendo el archivo el archivo usuario (controlador) */
    require_once "Controlador/usuario.controler.php";

     /*requiriendo el archivo el archivo proveedor (controlador) */
     require_once "Controlador/proveedor.controler.php";

    
    /*MODELOS */

     /*requiriendo el archivo el archivo categoria (Modelo) */
     require_once "Modelo/categoria.model.php";

     /*requiriendo el archivo el archivo cliente (Modelo) */
     require_once "Modelo/cliente.model.php";
 
     /*requiriendo el archivo el archivo producto (Modelo) */
     require_once "Modelo/producto.model.php";
 
     /*requiriendo el archivo el archivo usuario (Modelo) */
     require_once "Modelo/usuario.model.php";

     
     /*requiriendo el archivo el archivo usuario (Modelo) */
     require_once "Modelo/proveedor.model.php";
    

    /*instanciando la clase y creando un nuevo objeto */
    $planilla = new ContrlPlantilla();
    /*invocando un método de la clase */
    $planilla->controlerPlantilla();
