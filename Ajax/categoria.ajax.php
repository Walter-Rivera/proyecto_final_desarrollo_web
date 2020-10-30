<?php
/*necesitamos requerir de nuevo el controlador y modelo 
de usuario porque al trabajar con js, esto se ejecuta
independiente de html, es decir, en segundo plano */
require_once "../Controlador/categoria.controler.php";
require_once "../Modelo/categoria.model.php";

class AjaxCategoria
{
    /*edición de usuario*/
    /*variable que tomará el valor
    enviado a través de javascript
    (ID_PROVEEDOR) */
    public $ID_CATEGORIA_ENVIAR;
    /*para identificar que usuario hizo la alteración de estado de usuario*/
    public $NIP_USR_RESPONSABLE;

    /*activar / desactivar usuario */
    /*declaro las variables para recuperar los datos a modificar (estado usuario, ID_PROVEEDOR) */
    public $ID_CATEGORIA_ENVIARActDes;
    public $idEstadoCatActDes;

  
    /*función para tomar el ID del usuario */
    public function ajaxEdicionCategoria()
    {
        /*columna a evaluar en la bd */
        $columna="ID_TIPO_PRODUCTO";
        /*valor del ID_PROVEEDOR a buscar en la bd */
        $dato=$this->ID_CATEGORIA_ENVIAR;
        /*Solicitando al modelo/controlador que muestren los usuarios */
        $ans=ContrlCategoria::controlerMostrarCategoria($columna,$dato);
        /*el resultado de la bd,
        lo devolvemos en un json codificado (el resultado
        de la consulta) */
        echo json_encode($ans);
    }


    /*método para solicitar la respuesta directamente al modelo,
    del cambio de estado de un usuario */
    public function ajaxCambiarEstadoCategoria()
    {
        /*a este método del modelo le vamos a mandar 2 parámetros, el nip del usuario y el 
        id del estado al cual queremos cambiarlo*/
        $numeroCategoria=$this->ID_CATEGORIA_ENVIARActDes;
        $estado=$this->idEstadoCatActDes;
        $usr_Responsable=$this->NIP_USR_RESPONSABLE;
        
        $ans=ModelCategoria::mdlActualizarEstadoCategoria($numeroCategoria,$estado,$usr_Responsable);
    }

}


/*previo a crear el objeto AjaxCategoria,validamos
que nuestra variable post ID_CATEGORIA tenga información */

if(isset($_POST["ID_TIPO_PRODUCTO_MODIFICAR"]))
{
    /*creando un objeto para la edición de usuario */
    $edicion = new AjaxCategoria();
    /*enlazando nuestra variable de clase 
    con el ID_PROVEEDOR del usuario que está en nuestra variable POST */
    $edicion->ID_CATEGORIA_ENVIAR=$_POST["ID_TIPO_PRODUCTO_MODIFICAR"];
    /*Ejecutando el método para enviar el ID_PROVEEDOR de usuario */
    $edicion->ajaxEdicionCategoria();
}


/*estado Categoria*/
/*creando objetos que recibirán las variables post para editar
el estado del proveedor */
if(isset($_POST["estadoTP"]) && $_POST["estadoTP"]==1)
{
    /*creando un objeto para la edición de Proveedor */
    $edicion = new AjaxCategoria();

    /*USUARIO RESPONSABLE DE GESTION*/
    $edicion->NIP_USR_RESPONSABLE=$_POST["RESPON"];
    /*enlazando la variable de clase 
    con el id del Proveedor que está en la variable POST de edicion de estado */
    $edicion->ID_CATEGORIA_ENVIARActDes=$_POST["ID_TPC"];
    /*almacenando el estado del usuario */
    $edicion->idEstadoCatActDes=2;
    /*método para cambiar el estado del usuario */
    $edicion->ajaxCambiarEstadoCategoria();
}

if(isset($_POST["estadoTP"]) && $_POST["estadoTP"]==2)
{
    /*creando un objeto para la edición de usuario */
    $edicion = new AjaxCategoria();
    
    /*USUARIO RESPONSABLE DE GESTION*/
    $edicion->NIP_USR_RESPONSABLE=$_POST["RESPON"];
    /*enlazando la variable de clase 
    con el NIP del usuario que está en la variable POST de edicion de estado */
    $edicion->ID_CATEGORIA_ENVIARActDes=$_POST["ID_TPC"];
    /*almacenando el estado del usuario */
    $edicion->idEstadoCatActDes=1;
    /*método para cambiar el estado del usuario */
    $edicion->ajaxCambiarEstadoCategoria();
}


