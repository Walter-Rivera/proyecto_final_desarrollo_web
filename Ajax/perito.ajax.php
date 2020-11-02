<?php
/*necesitamos requerir de nuevo el controlador y modelo 
de perito porque al trabajar con js, esto se ejecuta
independiente de html, es decir, en segundo plano */
require_once "../Controlador/perito.controler.php";
require_once "../Modelo/perito.model.php";

class AjaxPerito
{
    /*edición de perito*/
    /*variable que tomará el valor
    enviado a través de javascript
    (nip de perito) */
    public $nipUsuari;
     /*para identificar que perito hizo la alteración de estado de perito*/
     public $NIP_USR_RESPONSABLE;
    /*activar / desactivar perito */
    /*declaro las variables para recuperar los datos a modificar (estado perito, nip) */
    public $NIPperitoActDes;
    public $idEstadoUsrActDes;

  
    /*función para tomar el NIP del perito */
    public function ajaxEdicionUsurio()
    {
        /*columna a evaluar en la bd */
        $columna="NIP";
        /*valor del NIP a buscar en la bd */
        $dato=$this->nipUsuari;
        /*Solicitando al modelo/controlador que muestren los peritos */
        $ans=ContrlPerito::controlerMostrarPeritos($columna,$dato);
        /*el resultado de la bd,
        lo devolvemos en un json codificado (el resultado
        de la consulta) */
        echo json_encode($ans);
    }

    /*método para solicitar la respuesta directamente al modelo,
    del cambio de estado de un perito */
    public function ajaxCambiarEstadoPerito()
    {
        /*a este método del modelo le vamos a mandar 2 parámetros, el nip del perito y el 
        id del estado al cual queremos cambiarlo*/
        $numeroPerito=$this->NIPperitoActDes;
        $estado=$this->idEstadoUsrActDes;
        $usr_Responsable=$this->NIP_USR_RESPONSABLE;
        $ans=ModelPerito::MdlActualizarEstadoPerito($numeroPerito,$estado,$usr_Responsable);
        /*el resultado de la bd,
        lo devolvemos en un json codificado (el resultado
        de la consulta) */
        echo json_encode($ans);
    }
}


/*previo a crear el objeto AjaxPerito,validamos
que nuestra variable post NIP tenga información */

if(isset($_POST["nipEditarPerito"]))
{
    /*creando un objeto para la edición de perito */
    $edicion = new AjaxPerito();
    /*enlazando nuestra variable de clase 
    con el NIP del perito que está en nuestra variable POST */
    $edicion->nipUsuari=$_POST["nipEditarPerito"];
    /*Ejecutando el método para enviar el NIP de perito */
    $edicion->ajaxEdicionUsurio();
}

/*creando objetos que recibirán las variables post para editar
el estado del perito */
if(isset($_POST["estadoUsr"]) && $_POST["estadoUsr"]==4)
{
    /*creando un objeto para la edición de perito */
    $edicion = new AjaxPerito();
    /*USUARIO RESPONSABLE DE GESTION*/
    $edicion->NIP_USR_RESPONSABLE=$_POST["RESPON"];
    /*enlazando la variable de clase 
    con el NIP del perito que está en la variable POST de edicion de estado */
    $edicion->NIPperitoActDes=$_POST["NIPusr"];
    /*almacenando el estado del perito */
    $edicion->idEstadoUsrActDes=5;
    /*método para cambiar el estado del perito */
    $edicion->ajaxCambiarEstadoPerito();
}

if(isset($_POST["estadoUsr"]) && $_POST["estadoUsr"]==5)
{
    /*creando un objeto para la edición de perito */
    $edicion = new AjaxPerito();
    /*USUARIO RESPONSABLE DE GESTION*/
    $edicion->NIP_USR_RESPONSABLE=$_POST["RESPON"];
    /*enlazando la variable de clase 
    con el NIP del perito que está en la variable POST de edicion de estado */
    $edicion->NIPperitoActDes=$_POST["NIPusr"];
    /*almacenando el estado del perito */
    $edicion->idEstadoUsrActDes=4;
    /*método para cambiar el estado del perito */
    $edicion->ajaxCambiarEstadoPerito();
}

