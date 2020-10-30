<?php
/*necesitamos requerir de nuevo el controlador y modelo 
de producto porque al trabajar con js, esto se ejecuta
independiente de html, es decir, en segundo plano */
require_once "../Controlador/producto.controler.php";
require_once "../Modelo/producto.model.php";

class AjaxProducto
{
    /*edición de producto*/
    /*variable que tomará el valor
    enviado a través de javascript
    (sku de producto) */
    public $skuProducto;
     /*para identificar que producto hizo la alteración de estado de producto*/
     public $NIP_USR_RESPONSABLE;
    /*activar / desactivar producto */
    /*declaro las variables para recuperar los datos a modificar (estado producto, nip) */
    public $skuProductoActDes;
    public $idestadoProActDes;

  
    /*función para tomar el NIP del producto */
    public function ajaxEdicionProducto()
    {
        /*columna a evaluar en la bd */
        $columna="SKU";
        /*valor del NIP a buscar en la bd */
        $dato=$this->skuProducto;
        /*Solicitando al modelo/controlador que muestren los productos */
        $ans=ContrlProducto::controlerMostrarProducto($columna,$dato);
        /*el resultado de la bd,
        lo devolvemos en un json codificado (el resultado
        de la consulta) */
        echo json_encode($ans);
    }

    /*método para solicitar la respuesta directamente al modelo,
    del cambio de estado de un producto */
    public function ajaxCambiarEstadoProducto()
    {
        /*a este método del modelo le vamos a mandar 2 parámetros, el sku del producto y el 
        id del estado al cual queremos cambiarlo*/
        $numeroUsuario=$this->skuProductoActDes;
        $estado=$this->idestadoProActDes;
        $usr_Responsable=$this->NIP_USR_RESPONSABLE;
        $ans=ModelProducto::mdlActualizarEstadoProducto($numeroUsuario,$estado,$usr_Responsable);
        /*el resultado de la bd,
        lo devolvemos en un json codificado (el resultado
        de la consulta) */
        echo json_encode($ans);
    }
}


/*previo a crear el objeto AjaxProducto,validamos
que nuestra variable post NIP tenga información */

if(isset($_POST["skuEditarPro"]))
{
    /*creando un objeto para la edición de producto */
    $edicion = new AjaxProducto();
    /*enlazando nuestra variable de clase 
    con el NIP del producto que está en nuestra variable POST */
    $edicion->skuProducto=$_POST["skuEditarPro"];
    /*Ejecutando el método para enviar el NIP de producto */
    $edicion->ajaxEdicionProducto();
}

/*creando objetos que recibirán las variables post para editar
el estado del producto */
if(isset($_POST["estadoPro"]) && $_POST["estadoPro"]==1)
{
    /*creando un objeto para la edición de producto */
    $edicion = new AjaxProducto();
    /*USUARIO RESPONSABLE DE GESTION*/
    $edicion->NIP_USR_RESPONSABLE=$_POST["RESPON"];
    /*enlazando la variable de clase 
    con el NIP del producto que está en la variable POST de edicion de estado */
    $edicion->skuProductoActDes=$_POST["skuPro"];
    /*almacenando el estado del producto */
    $edicion->idestadoProActDes=2;
    /*método para cambiar el estado del producto */
    $edicion->ajaxCambiarEstadoProducto();
}

if(isset($_POST["estadoPro"]) && $_POST["estadoPro"]==2)
{
    /*creando un objeto para la edición de producto */
    $edicion = new AjaxProducto();
    /*USUARIO RESPONSABLE DE GESTION*/
    $edicion->NIP_USR_RESPONSABLE=$_POST["RESPON"];
    /*enlazando la variable de clase 
    con el NIP del producto que está en la variable POST de edicion de estado */
    $edicion->skuProductoActDes=$_POST["skuPro"];
    /*almacenando el estado del producto */
    $edicion->idestadoProActDes=1;
    /*método para cambiar el estado del producto */
    $edicion->ajaxCambiarEstadoProducto();
}

