<?php
/*necesitamos requerir de nuevo el controlador y modelo 
de seccion porque al trabajar con js, esto se ejecuta
independiente de html, es decir, en segundo plano */
require_once "../Controlador/seccion.controler.php";
require_once "../Modelo/seccion.model.php";

class AjaxSeccion
{
    /*edición de seccion*/
    /*variable que tomará el valor
    enviado a través de javascript
    (id de seccion) */
    public $idSecc;
    
    /*función para tomar el ID del seccion */
    public function ajaxEdicionSeccion()
    {
        /*columna a evaluar en la bd */
        $columna="ID";
        /*valor del ID a buscar en la bd */
        $dato=$this->idSecc;
        /*Solicitando al modelo/controlador que muestren los seccions */
        $ans=ContrlSeccion::controlerMostrarSeccion($columna,$dato);
        /*el resultado de la bd,
        lo devolvemos en un json codificado (el resultado
        de la consulta) */
        echo json_encode($ans);
    }
}


/*previo a crear el objeto AjaxSeccion,validamos
que nuestra variable post ID tenga información */

if(isset($_POST["idEditar"]))
{
    /*creando un objeto para la edición de seccion */
    $edicion = new AjaxSeccion();
    /*enlazando nuestra variable de clase 
    con el ID del seccion que está en nuestra variable POST */
    $edicion->idSecc=$_POST["idEditar"];
    /*Ejecutando el método para enviar el ID de seccion */
    $edicion->ajaxEdicionSeccion();
}
