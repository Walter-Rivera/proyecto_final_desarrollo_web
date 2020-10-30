<?php

class ContrlProveedor
{
    static public function controlerProveedor()
    {
        /*incluimos el archivo Proveedor.php para poder hacer uso del mismo */
        include "Vista/Modulos/proveedor.php";
    }


    /*método para poder crear un proveedor en el sistema*/
    static public function controlerCrearProveedor()
    {
        /*si se trea una variable tipo post
        y se está enviando por medio del formulario de creación de usuario
        en el front, puede ser cualquier variable del formulario*/
        if(isset($_POST["nombreNuevo"]))
        {
            /*validamos la información contenida en las variables posts,
            con preg match lo que se hará es permitir que la contraseña
            soporte el uso de caracteres especiales, tal es el caso de tildes únicamente
            en  vocales, letras
            y vocales de la a-z (mayúsculas, minúsculas), dígitos del 0 al 9,
            las ñ's (mayúsculas y minúsculas) en el caso de las tildes, se permitirán
            únicamente sobre las vocales y espacio en blaco de necesitarlo etc */

            /*en el caso del nombre vamos a validar 
            que se permitan números, letras y vocales del abecedario*/
            if(preg_match('/^[0-9a-zA-Z-ñÑáéíóúÁÉÍÓÚ ]+$/',$_POST["nombreNuevo"]) &&
               preg_match('/^[0-9a-zA-Z-ñÑáéíóúÁÉÍÓÚ, ]+$/',$_POST["direccionNuevo"]) &&
               preg_match('/^[0-9]+$/',$_POST["telefonoNuevo"])) 
            {
                /* coloco en un array los datos obtenidos del envío del 
                formulario a través de las variables tipo $_POST*/
                $nuevaTupla=array("NOMBRE"=> $_POST["nombreNuevo"],
                            "DIRECCION"=> $_POST["direccionNuevo"],
                            "TELEFONO"=> $_POST["telefonoNuevo"],
                            "NIP_ULT_USR_MODIFICADOR"=> $_SESSION["NIP"]
                );

                /*enviamos a nuestro modelo de proveedor, el nombre de la tabla
                y array de datos que se obtuvo de la vista*/
                $respue=modelProveedor::mdlCrearProveedor($nuevaTupla);

                /*si se almacenan correctamente los datos del proveedor en la tabla
               Proveeor */
                if($respue==true)
                {
                    /*lanzo una alerta suave (notificación)
                    que el usuario fue almacenado en la bd correctamente*/
                    echo
                    '<script>
                             
                                Swal.fire({
                                    position: "center",
                                    icon: "success",
                                    title: "El proveedor Fue creado con éxito",
                                    showConfirmButton: false,
                                    timer: 2000
                                }).then((result)=>{
                                    window.location="proveedor";
                            });
                    </script>';

                }

            }
            /*si los datos que ingresa el usuario no coinciden con 
            las parametrizaciones que realicé a través de las expresiones
            regulares*/
            else
            {
                /*lanzo un alert para indicarle al usuario que la información
                que brinda es errónea y no cumple con la evaluación descrita en el
                if con la función preg_match sobre los campos señalados anteriormente */
                echo
                    '<script>
                            swal.fire({
                                type:"error",
                                icon: "error",
                                title: "verifique los caracteres que está ingresando, no se permiten (/,<,>,.,^)",
                            }).then((result)=>{
                                window.location="proveedor";
                        });
                    
                    
                    
                    </script>';
            }
        }

    }

    /*método para mostrar los Proveedores*/
    static public function controlerMostrarProveedor($campo,$valor)
    {
        /*tabla de Proveedores*/
        $tbl="PROVEEDOR";

        $ans=ModelProveedor::mdlMostrarProveedor($tbl,$campo,$valor);

        /*retornamos la respuesta a la vista */    
        return $ans;

    }


    
    static public function controlerEditarProveedor()
    {
        /*verifico si la variable post (idEditar) de edicion
        contiene valores */
        if(isset($_POST["idEditar"]))
        {
            /*vuelvo a hacer la validación de la entrada de caracteres
            que permito */
            if(preg_match('/^[0-9a-zA-Z-ñÑáéíóúÁÉÍÓÚ ]+$/',$_POST["nombreEditar"]) &&
            preg_match('/^[0-9a-zA-Z-ñÑáéíóúÁÉÍÓÚ, ]+$/',$_POST["direccionEditar"]) &&
            preg_match('/^[0-9]+$/',$_POST["telefonoEditar"])) 
         {
               /* coloco en un array los datos obtenidos del envío del 
                formulario a través de las variables tipo $_POST*/
                $nuevaTupla=array("ID_PROVEEDOR"=> $_POST["idEditar"],
                            "NOMBRE"=> $_POST["nombreEditar"],
                            "DIRECCION"=> $_POST["direccionEditar"],
                            "TELEFONO"=> $_POST["telefonoEditar"],
                            "NIP_ULT_USR_MODIFICADOR"=> $_SESSION["NIP"]
                );
                
               
                /*solicitando  respuesta al modelo */
                $ans=ModelProveedor::mdlEditarProveedor($nuevaTupla);
                /*si la actualización se realizó con éxito, lanzo una 
                notificación para informarle al usuario */
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
                                        window.location="proveedor";
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
                                title: "verifique los caracteres que está ingresando, no se permiten (/,<,>,.,^)",
                            }).then((result)=>{
                                window.location="proveedor";
                        });
                    </script>';
            }
        }
    }


     /*controler para borrar usuario (dar de baja)*/
     static public function controlerBorrarProveedor()
     {
         /*verifico el id del proveedor a eliminar venga en variable get (solo es para en margen de
         02 segundos pasarlo al método de eliminar (dar de baja)*/
         if(isset($_GET["id_Proveedor_baja"]))
         {
            
             /*solicitamos al modelo que ejecute la acción y envie respuesta */
             $res=ModelProveedor::mdlBorrarProveedor($_GET["id_Proveedor_baja"],$_GET["nip_Usuario_Responsabl"]);
             /*luego de obtener respuesta del modelo,
             verifico si la acción fue realizada con éxito */
             if($res==true)
             {
                 echo
                     '<script>
                                 
                         Swal.fire({
                             position: "center",
                             icon: "success",
                             title: "El proveedor fue eliminado exitosamente",
                             showConfirmButton: false,
                             timer: 2000
                             }).then((result)=>{
                                 window.location="proveedor";
                             }); 
                         </script>';
 
             } else
             {
                 echo
                     '<script>
                             swal.fire({
                                 type:"error",
                                 icon: "error",
                                 title: "Error al eliminar proveedor, contacte al desarollador",
                             }).then((result)=>{
                                 window.location="proveedor";
                             }); 
                     </script>';
             }
        }
 
    }


}