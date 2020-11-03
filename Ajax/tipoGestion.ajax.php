<?php
/*necesitamos requerir de nuevo el controlador y modelo 
de tipoGestion porque al trabajar con js, esto se ejecuta
independiente de html, es decir, en segundo plano */
require_once "../Controlador/tipoGestion.controler.php";
require_once "../Modelo/tipoGestion.model.php";

class AjaxTipoGestion
{
    /*edición de tipoGestion*/
    /*variable que tomará el valor
    enviado a través de javascript
    (id de tipoGestion) */
    public $idSecc;

    /*función para tomar el ID del tipoGestion */
    public function ajaxEdicionTipoGestion()
    {
        /*columna a evaluar en la bd */
        $columna="ID";
        /*valor del ID a buscar en la bd */
        $dato=$this->idSecc;
        /*Solicitando al modelo/controlador que muestren los tipoGestions */
        $ans=ContrlTipoGestion::controlerMostrarTipoGestion($columna,$dato);
        /*el resultado de la bd,
        lo devolvemos en un json codificado (el resultado
        de la consulta) */
        echo json_encode($ans);
    }
}


/*previo a crear el objeto AjaxTipoGestion,validamos
que nuestra variable post ID tenga información */

if(isset($_POST["idEditarTipoGestion"]))
{
    /*creando un objeto para la edición de tipoGestion */
    $edicion = new AjaxTipoGestion();
    /*enlazando nuestra variable de clase 
    con el ID del tipoGestion que está en nuestra variable POST */
    $edicion->idSecc=$_POST["idEditarTipoGestion"];
    /*Ejecutando el método para enviar el ID de tipoGestion */
    $edicion->ajaxEdicionTipoGestion();
}
