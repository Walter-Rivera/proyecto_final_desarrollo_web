<?php
/*necesitamos requerir de nuevo el controlador y modelo 
de usuario porque al trabajar con js, esto se ejecuta
independiente de html, es decir, en segundo plano */
require_once "../Controlador/proveedor.controler.php";
require_once "../Modelo/proveedor.model.php";

class AjaxProveedor
{
    /*edición de usuario*/
    /*variable que tomará el valor
    enviado a través de javascript
    (ID_PROVEEDOR) */
    public $ID_PROVEEDOR_ENVIAR;
    /*para identificar que usuario hizo la alteración de estado de usuario*/
    public $NIP_USR_RESPONSABLE;

    /*activar / desactivar usuario */
    /*declaro las variables para recuperar los datos a modificar (estado usuario, ID_PROVEEDOR) */
    public $ID_PROVEEDOR_ENVIARActDes;
    public $idEstadoProvActDes;

  
    /*función para tomar el ID del usuario */
    public function ajaxEdicionProveedor()
    {
        /*columna a evaluar en la bd */
        $columna="ID_PROVEEDOR";
        /*valor del ID_PROVEEDOR a buscar en la bd */
        $dato=$this->ID_PROVEEDOR_ENVIAR;
        /*Solicitando al modelo/controlador que muestren los usuarios */
        $ans=ContrlProveedor::controlerMostrarProveedor($columna,$dato);
        /*el resultado de la bd,
        lo devolvemos en un json codificado (el resultado
        de la consulta) */
        echo json_encode($ans);
    }


    /*método para solicitar la respuesta directamente al modelo,
    del cambio de estado de un usuario */
    public function ajaxCambiarEstadoProveedor()
    {
        /*a este método del modelo le vamos a mandar 2 parámetros, el nip del usuario y el 
        id del estado al cual queremos cambiarlo*/
        $numeroProveedor=$this->ID_PROVEEDOR_ENVIARActDes;
        $estado=$this->idEstadoProvActDes;
        $usr_Responsable=$this->NIP_USR_RESPONSABLE;
        $ans=ModelProveedor::mdlActualizarEstadoProveedor($numeroProveedor,$estado,$usr_Responsable);
    }

}


/*previo a crear el objeto AjaxProveedor,validamos
que nuestra variable post ID_PROVEEDOR tenga información */

if(isset($_POST["ID_PROVEEDOR_MODIFICAR"]))
{
    /*creando un objeto para la edición de usuario */
    $edicion = new AjaxProveedor();
    /*enlazando nuestra variable de clase 
    con el ID_PROVEEDOR del usuario que está en nuestra variable POST */
    $edicion->ID_PROVEEDOR_ENVIAR=$_POST["ID_PROVEEDOR_MODIFICAR"];
    /*Ejecutando el método para enviar el ID_PROVEEDOR de usuario */
    $edicion->ajaxEdicionProveedor();
}






/*estado Proveedor*/
/*creando objetos que recibirán las variables post para editar
el estado del proveedor */
if(isset($_POST["estadoProv"]) && $_POST["estadoProv"]==1)
{
    /*creando un objeto para la edición de Proveedor */
    $edicion = new AjaxProveedor();

    /*USUARIO RESPONSABLE DE GESTION*/
    $edicion->NIP_USR_RESPONSABLE=$_POST["RESPON"];
    /*enlazando la variable de clase 
    con el id del Proveedor que está en la variable POST de edicion de estado */
    $edicion->ID_PROVEEDOR_ENVIARActDes=$_POST["ID_PROV"];
    /*almacenando el estado del usuario */
    $edicion->idEstadoProvActDes=2;
    /*método para cambiar el estado del usuario */
    $edicion->ajaxCambiarEstadoProveedor();
}

if(isset($_POST["estadoProv"]) && $_POST["estadoProv"]==2)
{
    /*creando un objeto para la edición de usuario */
    $edicion = new AjaxProveedor();
    
    /*USUARIO RESPONSABLE DE GESTION*/
    $edicion->NIP_USR_RESPONSABLE=$_POST["RESPON"];
    /*enlazando la variable de clase 
    con el NIP del usuario que está en la variable POST de edicion de estado */
    $edicion->ID_PROVEEDOR_ENVIARActDes=$_POST["ID_PROV"];
    /*almacenando el estado del usuario */
    $edicion->idEstadoProvActDes=1;
    /*método para cambiar el estado del usuario */
    $edicion->ajaxCambiarEstadoProveedor();
}


