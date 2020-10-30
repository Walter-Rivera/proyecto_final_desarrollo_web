<?php

class ContrlProducto
{
    static public function controlerProducto()
    {
        /*incluimos el archivo producto.php para poder hacer uso del mismo */
        include "Vista/Modulos/producto.php";
    }

    /*método para poder crear un producto en el sistema*/
    static public function controlerCrearProducto()
    {
        /*si se trea una variable tipo post
        y se está enviando por medio del formulario de creación de producto
        en el front, puede ser cualquier variable del formulario*/
        if(isset($_POST["skuNuevo"]))
        {
            /*verifico que los campos de sku,descripcion,stock minimo y máximo cumplan con las condicionale
            que les impondré*/
 
            if(preg_match('/^[0-9]+$/',$_POST["skuNuevo"]) &&
               preg_match('/^[0-9a-zA-Z-ñÑáéíóúÁÉÍÓÚ ]+$/',$_POST["descripcionNuevo"])) 
            {
                
                /* coloco en un array los datos obtenidos del envío del 
                formulario a través de las variables tipo $_POST*/
                $nuevaTupla=array("SKU"=> $_POST["skuNuevo"],
                            "DESCRIPCION"=>$_POST["descripcionNuevo"],
                            "PRECIO_COSTO"=>$_POST["precioCostoNuevo"],
                            "TIPO_PRODUCTO"=>$_POST["tipoProductoNuevo"],
                            "STOCK_MINIMO"=>$_POST["stockMinimoNuevo"],
                            "STOCK_MAXIMO"=>$_POST["stockMaximoNuevo"],
                            "NIP_ULT_USR_MODIFICADOR"=> $_SESSION["NIP"]
                            );

                /*enviamos a nuestro modelo de producto, el nombre de la tabla
                y array de datos que se obtuvo de la vista*/
                $answer=ModelProducto::mdlCrearProducto($nuevaTupla);

        
                if($answer==true)
                {
                    /*lanzo una alerta suave (notificación)
                    que el producto fue almacenado en la bd correctamente*/
                    echo
                    '<script>
                             
                                Swal.fire({
                                    position: "center",
                                    icon: "success",
                                    title: "El producto Fue creado con éxito",
                                    showConfirmButton: false,
                                    timer: 2000
                                }).then((result)=>{
                                    window.location="producto";
                                });
                    </script>';

                }
                else
                {
                    echo'<script>
                        swal.fire({
                            type:"error",
                            icon: "error",
                            title: "'.$Ex->getMessage().'",
                        }).then((result)=>{
                            window.location="producto";
                         }); 
                    </script>';
                }

            }
            /*si los datos que ingresa el producto no coinciden con 
            las parametrizaciones que realicé a través de las expresiones
            regulares*/
            else
            {
                /*lanzo un alert para indicarle al producto que la información
                que brinda es errónea y no cumple con la evaluación descrita en el
                if con la función preg_match sobre los campos señalados anteriormente */
                echo
                    '<script>
                            swal.fire({
                                type:"error",
                                icon: "error",
                                title: "datos incorrectos, verifique los valores proporcionados",
                            }).then((result)=>{
                                window.location="producto";
                            }); 
                    </script>';
            }
        }


    }

    /*método para mostrar los productos*/
    static public function controlerMostrarProducto($campo,$valor)
    {
        /*tabla de productos*/
        $tbl="VISTA_CRUD_PRODUCTO";

        $ans=ModelProducto::mdlMostrarProducto($tbl,$campo,$valor);

        /*retornamos la respuesta a la vista */    
        return $ans;

    }



    static public function controlerEditarProducto()
    {
        /*verifico si la variable post (skuEditar) de edicion
        contiene valores */
        if(isset($_POST["skuEditar"]))
        {
            /*vuelvo a hacer la validación de la entrada de caracteres
            que permito */
            if(preg_match('/^[0-9]+$/',$_POST["skuEditar"]) &&
            preg_match('/^[0-9a-zA-Z-ñÑáéíóúÁÉÍÓÚ ]+$/',$_POST["descripcionEditar"])&&
            preg_match('/^[0-9.]+$/',$_POST["precioCostoEditar"]))
            {
                 /* coloco en un array los datos obtenidos del envío del 
                formulario a través de las variables tipo $_POST*/
                $nuevaTupla=array("SKU"=> $_POST["skuEditar"],
                            "DESCRIPCION"=>$_POST["descripcionEditar"],
                            "PRECIO_COSTO"=>$_POST["precioCostoEditar"],
                            "TIPO_PRODUCTO"=>$_POST["tipoProductoEditar"],
                            "STOCK_MINIMO"=>$_POST["stockMinimoEditar"],
                            "STOCK_MAXIMO"=>$_POST["stockMaximoEditar"],
                            "NIP_ULT_USR_MODIFICADOR"=> $_SESSION["NIP"]
                            );
                /*solicitando  respuesta al modelo */
                $ans=ModelProducto::mdlEditarProducto($nuevaTupla);
                /*si la actualización se realizó con éxito, lanzo una 
                notificación para informarle al producto */
                if($ans==true)
                {
                    echo
                    '<script>
                             
                                Swal.fire({
                                    position: "center",
                                    icon: "success",
                                    title: "Datos actualizados exitosamente",
                                    showConfirmButton: false,
                                    timer: 2000
                                }).then((result)=>{
                                    window.location="producto";
                                }); 
                    </script>';

                }
                else
                {
                    echo'<script>
                        swal.fire({
                            type:"error",
                            icon: "error",
                            title: "'.$Ex->getMessage().'",
                        }).then((result)=>{
                            window.location="producto";
                         }); 
                    </script>';
                }


            } else
            {
                echo
                     '<script>
                            swal.fire({
                                type:"error",
                                icon: "error",
                                title: "No se permiten caracteres *,/,<,>,(,),"
                            }).then((result)=>{
                                window.location="producto";
                            }); 
                    </script>';
            }
        }
    }


    /*controler para borrar producto (dar de baja)*/
    static public function controlerBorrarProducto()
    {
        /*verifico el id del producto a eliminar (NIP) y
        que el id del producto responsable de la ejecución de  la acción
        sean registrados*/
        if(isset($_GET["id_Producto_baja"]))
        {
            /*solicitamos al modelo que ejecute la acción y envie respuesta */
            $res=ModelProducto::mdlBorrarProducto($_GET["id_Producto_baja"],$_GET["nip_Usuario_Responsable"]);
            /*luego de obtener respuesta del modelo,
            verifico si la acción fue realizada con éxito */
            if($res==true)
            {
                echo
                    '<script>
                                
                        Swal.fire({
                            position: "center",
                            icon: "success",
                            title: "Producto Eliminado Exitosamente",
                            showConfirmButton: false,
                            timer: 2000
                            }).then((result)=>{
                                window.location="producto";
                            }); 
                        </script>';

            } else
            {
                echo
                    '<script>
                            swal.fire({
                                type:"error",
                                icon: "error",
                                title: "'.$res->getMessage().'",
                            }).then((result)=>{
                                window.location="producto";
                            }); 
                    </script>';
            }
        }

    }


    static public function controlerMostarCategoria()
    {
        $resp=ModelProducto::mdlverCategoria();
        return $resp;
    }

}