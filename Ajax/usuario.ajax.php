<?php
/*necesitamos requerir de nuevo el controlador y modelo 
de usuario porque al trabajar con js, esto se ejecuta
independiente de html, es decir, en segundo plano */
require_once "../Controlador/usuario.controler.php";
require_once "../Modelo/usuario.model.php";

class AjaxUsuario
{
    /*edición de usuario*/
    /*variable que tomará el valor
    enviado a través de javascript
    (nip de usuario) */
    public $nipUsuari;
     /*para identificar que usuario hizo la alteración de estado de usuario*/
     public $NIP_USR_RESPONSABLE;
    /*activar / desactivar usuario */
    /*declaro las variables para recuperar los datos a modificar (estado usuario, nip) */
    public $NIPusuarioActDes;
    public $idEstadoUsrActDes;

  
    /*función para tomar el NIP del usuario */
    public function ajaxEdicionUsurio()
    {
        /*columna a evaluar en la bd */
        $columna="NIP";
        /*valor del NIP a buscar en la bd */
        $dato=$this->nipUsuari;
        /*Solicitando al modelo/controlador que muestren los usuarios */
        $ans=ContrlUsuario::controlerMostrarUsuarios($columna,$dato);
        /*el resultado de la bd,
        lo devolvemos en un json codificado (el resultado
        de la consulta) */
        echo json_encode($ans);
    }

    /*método para solicitar la respuesta directamente al modelo,
    del cambio de estado de un usuario */
    public function ajaxCambiarEstadoUsuario()
    {
        /*a este método del modelo le vamos a mandar 2 parámetros, el nip del usuario y el 
        id del estado al cual queremos cambiarlo*/
        $numeroUsuario=$this->NIPusuarioActDes;
        $estado=$this->idEstadoUsrActDes;
        $usr_Responsable=$this->NIP_USR_RESPONSABLE;
        $ans=ModelUsuario::MdlActualizarEstadoUsuario($numeroUsuario,$estado,$usr_Responsable);
        /*el resultado de la bd,
        lo devolvemos en un json codificado (el resultado
        de la consulta) */
        echo json_encode($ans);
    }
}


/*previo a crear el objeto AjaxUsuario,validamos
que nuestra variable post NIP tenga información */

if(isset($_POST["nipEditarUsuario"]))
{
    /*creando un objeto para la edición de usuario */
    $edicion = new AjaxUsuario();
    /*enlazando nuestra variable de clase 
    con el NIP del usuario que está en nuestra variable POST */
    $edicion->nipUsuari=$_POST["nipEditarUsuario"];
    /*Ejecutando el método para enviar el NIP de usuario */
    $edicion->ajaxEdicionUsurio();
}

/*creando objetos que recibirán las variables post para editar
el estado del usuario */
if(isset($_POST["estadoUsr"]) && $_POST["estadoUsr"]==1)
{
    /*creando un objeto para la edición de usuario */
    $edicion = new AjaxUsuario();
    /*USUARIO RESPONSABLE DE GESTION*/
    $edicion->NIP_USR_RESPONSABLE=$_POST["RESPON"];
    /*enlazando la variable de clase 
    con el NIP del usuario que está en la variable POST de edicion de estado */
    $edicion->NIPusuarioActDes=$_POST["NIPusr"];
    /*almacenando el estado del usuario */
    $edicion->idEstadoUsrActDes=2;
    /*método para cambiar el estado del usuario */
    $edicion->ajaxCambiarEstadoUsuario();
}

if(isset($_POST["estadoUsr"]) && $_POST["estadoUsr"]==2)
{
    /*creando un objeto para la edición de usuario */
    $edicion = new AjaxUsuario();
    /*USUARIO RESPONSABLE DE GESTION*/
    $edicion->NIP_USR_RESPONSABLE=$_POST["RESPON"];
    /*enlazando la variable de clase 
    con el NIP del usuario que está en la variable POST de edicion de estado */
    $edicion->NIPusuarioActDes=$_POST["NIPusr"];
    /*almacenando el estado del usuario */
    $edicion->idEstadoUsrActDes=1;
    /*método para cambiar el estado del usuario */
    $edicion->ajaxCambiarEstadoUsuario();
}

