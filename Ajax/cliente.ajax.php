<?php
/*necesitamos requerir de nuevo el controlador y modelo 
de cliente porque al trabajar con js, esto se ejecuta
independiente de html, es decir, en segundo plano */
require_once "../Controlador/cliente.controler.php";
require_once "../Modelo/cliente.model.php";

class AjaxCliente
{
    /*edición de cliente*/
    /*variable que tomará el valor
    enviado a través de javascript
    (nip de cliente) */
    public $nipUsuari;
     /*para identificar que usuario hizo la alteración de estado de usuario*/
     public $NIP_USR_RESPONSABLE;
    /*activar / desactivar usuario */
    /*declaro las variables para recuperar los datos a modificar (estado cliente, nip) */
    public $NIPClienteActDes;
    public $idEstadoCliActDes;

  
    /*función para tomar el NIP del usuario */
    public function ajaxEdicionCliente()
    {
        /*columna a evaluar en la bd */
        $columna="NIP";
        /*valor del NIP a buscar en la bd */
        $dato=$this->nipUsuari;
        /*Solicitando al modelo/controlador que muestren los usuarios */
        $ans=ContrlCliente::controlerMostrarClientes($columna,$dato);
        /*el resultado de la bd,
        lo devolvemos en un json codificado (el resultado
        de la consulta) */
        echo json_encode($ans);
    }

    /*método para solicitar la respuesta directamente al modelo,
    del cambio de estado de un usuario */
    public function ajaxCambiarEstadoCliente()
    {
        /*a este método del modelo le vamos a mandar 2 parámetros, el nip del usuario y el 
        id del estado al cual queremos cambiarlo*/
        $numeroUsuario=$this->NIPClienteActDes;
        $estado=$this->idEstadoCliActDes;
        $usr_Responsable=$this->NIP_USR_RESPONSABLE;
        $ans=ModelCliente::MdlActualizarEstadoCliente($numeroUsuario,$estado,$usr_Responsable);
    }
}


/*previo a crear el objeto AjaxCliente,validamos
que nuestra variable post NIP tenga información */

if(isset($_POST["nipEditarCliente"]))
{
    /*creando un objeto para la edición de cliente */
    $edicion = new AjaxCliente();
    /*enlazando nuestra variable de clase 
    con el NIP del usuario que está en nuestra variable POST */
    $edicion->nipUsuari=$_POST["nipEditarCliente"];
    /*Ejecutando el método para enviar el NIP del cliente */
    $edicion->ajaxEdicionCliente();
}

/*creando objetos que recibirán las variables post para editar
el estado del usuario */
if(isset($_POST["estadoCli"]) && $_POST["estadoCli"]==1)
{
    /*creando un objeto para la edición de usuario */
    $edicion = new AjaxCliente();
    /*USUARIO RESPONSABLE DE GESTION*/
    $edicion->NIP_USR_RESPONSABLE=$_POST["RESPON"];
    /*enlazando la variable de clase 
    con el NIP del usuario que está en la variable POST de edicion de estado */
    $edicion->NIPClienteActDes=$_POST["NIPcli"];
    /*almacenando el estado del usuario */
    $edicion->idEstadoCliActDes=2;
    /*método para cambiar el estado del usuario */
    $edicion->ajaxCambiarEstadoCliente();
}

if(isset($_POST["estadoCli"]) && $_POST["estadoCli"]==2)
{
    /*creando un objeto para la edición de usuario */
    $edicion = new AjaxCliente();
    /*USUARIO RESPONSABLE DE GESTION*/
    $edicion->NIP_USR_RESPONSABLE=$_POST["RESPON"];
    /*enlazando la variable de clase 
    con el NIP del usuario que está en la variable POST de edicion de estado */
    $edicion->NIPClienteActDes=$_POST["NIPcli"];
    /*almacenando el estado del usuario */
    $edicion->idEstadoCliActDes=1;
    /*método para cambiar el estado del usuario */
    $edicion->ajaxCambiarEstadoCliente();
}

