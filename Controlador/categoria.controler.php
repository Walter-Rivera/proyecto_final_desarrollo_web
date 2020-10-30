<?php

class ContrlCategoria
{
    static public function controlerCategoria()
    {
        /*incluimos el archivo categoria.php para poder hacer uso del mismo */
        include "Vista/Modulos/categoria.php";
    }


    /*método para poder crear una categoria en el sistema*/
    static public function controlerCrearCategoria()
    {
        /*si se trea una variable tipo post
        y se está enviando por medio del formulario de creación de categoria
        en el front, puede ser cualquier variable del formulario*/
        if(isset($_POST["nombreNuevo"]))
        {
            /*validamos la información contenida en la variable post,
            con preg match lo que se hará es permitir que el nombre de la categoría
            soporte el uso de caracteres especiales, tal es el caso de tildes únicamente
            en  vocales, letras
            y vocales de la a-z (mayúsculas, minúsculas), dígitos del 0 al 9,
            las ñ's (mayúsculas y minúsculas) en el caso de las tildes, se permitirán
            únicamente sobre las vocales y espacio en blaco de necesitarlo */

            /*en el caso del nombre vamos a validar 
            que se permitan números, letras y vocales del abecedario*/
            if(preg_match('/^[0-9a-zA-Z-ñÑáéíóúÁÉÍÓÚ ]+$/',$_POST["nombreNuevo"]))
            {
                /* coloco en un array los datos obtenidos del envío del 
                formulario a través de las variables tipo $_POST*/
                $nuevaTupla=array("NOMBRE"=> $_POST["nombreNuevo"],
                            "NIP_ULT_USR_MODIFICADOR"=> $_SESSION["NIP"]
                );

                /*enviamos a nuestro modelo de categoria, el nombre de la tabla
                y array de datos que se obtuvo de la vista*/
                $respue=ModelCategoria::mdlCrearCategoria($nuevaTupla);

                /*si se almacenan correctamente los datos de la categoria en la tabla
               Categoria */
                if($respue==true)
                {
                    /*lanzo una alerta suave (notificación)
                    que la categoria fue almacenada en la bd correctamente*/
                    echo
                    '<script>
                             
                                Swal.fire({
                                    position: "center",
                                    icon: "success",
                                    title: "La categoría fue Creada con Éxito",
                                    showConfirmButton: false,
                                    timer: 2000
                                }).then((result)=>{
                                    window.location="categoria";
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
                                title: " No se permiten caracteres especiales (*,/,^,<,>) en el Nombre",
                            }).then((result)=>{
                                window.location="categoria";
                        });
                    </script>';
            }
        }

    }

    /*método para mostrar las Categorias*/
    static public function controlerMostrarCategoria($campo,$valor)
    {
        /*tabla de Proveedores*/
        $tbl="VISTA_CRUD_CATEGORIA";

        $ans=ModelCategoria::mdlMostrarCategoria($tbl,$campo,$valor);

        /*retornamos la respuesta a la vista */    
        return $ans;

    }


    
    static public function controlerEditarCategoria()
    {
        /*verifico si la variable post (idEditar) de edicion
        contiene valores */
        if(isset($_POST["idEditar"]))
        {
            /*vuelvo a hacer la validación de la entrada de caracteres
            que permito */
            if(preg_match('/^[0-9a-zA-Z-ñÑáéíóúÁÉÍÓÚ ]+$/',$_POST["nombreEditar"])) 
         {
               /* coloco en un array los datos obtenidos del envío del 
                formulario a través de las variables tipo $_POST*/
                $nuevaTupla=array("ID_ESTADO_TIPO_PRODUCTO"=> $_POST["idEditar"],
                            "NOMBRE"=> $_POST["nombreEditar"],
                            "NIP_ULT_USR_MODIFICADOR"=> $_SESSION["NIP"]
                );
                
               
                /*solicitando  respuesta al modelo */
                $ans=ModelCategoria::mdlEditarCategoria($nuevaTupla);
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
                                        window.location="categoria";
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
                                title: "En el nombre no se permiten caracteres especiales(/,<,>,*,-, y ,)",
                            }).then((result)=>{
                                window.location="categoria";
                        });
                    </script>';
            }
        }
    }

    /*controler para borrar categoria (dar de baja)*/
    static public function controlerBorrarCategoria()
    {
        /*verifico el id de la categoria a eliminar venga en variable get (solo es para en margen de
        02 segundos pasarlo al método de eliminar (dar de baja)*/
        if(isset($_GET["id_Categoria_baja"]))
        {
            /*solicitamos al modelo que ejecute la acción y envie respuesta */
            $res=ModelCategoria::mdlBorrarCategoria($_GET["id_Categoria_baja"],$_GET["nip_Usuario_Responsablecat"]);
            /*luego de obtener respuesta del modelo,
            verifico si la acción fue realizada con éxito */
            if($res==true)
            {
                echo
                    '<script>
                                
                        Swal.fire({
                            position: "center",
                            icon: "success",
                            title: "La categoria fue eliminado exitosamente",
                            showConfirmButton: false,
                            timer: 2000
                            }).then((result)=>{
                                window.location="categoria";
                            }); 
                        </script>';

            } else
            {
                echo
                    '<script>
                            swal.fire({
                                type:"error",
                                icon: "error",
                                title: "Error al eliminar categoria, contacte al desarollador",
                            }).then((result)=>{
                                window.location="categoria";
                            }); 
                    </script>';
            }
       }

   }


}