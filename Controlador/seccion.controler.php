<?php

class ContrlSeccion
{
    static public function controlerSeccion()
    {
        /*incluimos el archivo seccion.php para poder hacer uso del mismo */
        include "Vista/Modulos/seccion.php";
    }


    /*método para poder crear un seccion en el sistema*/
    static public function controlerCrearSeccion()
    {
        /*si se trea una variable tipo post
        y se está enviando por medio del formulario de creación de seccion
        en el front, puede ser cualquier variable del formulario*/
        if(isset($_POST["idNuevo"]))
        {
            /*validamos la información contenida en las variables posts,
            con preg match lo que se hará es permitir que la contraseña
            soporte el uso de caracteres especiales, tal es el caso de tildes únicamente
            en  vocales, letras
            y vocales de la a-z (mayúsculas, minúsculas), dígitos del 0 al 9,
            las ñ's (mayúsculas y minúsculas) en el caso de las tildes, se permitirán
            únicamente sobre las vocales y espacio en blaco de necesitarlo etc */

            /*en el caso de los nombres y apellidos vamos a validar 
            que solo se permitan caracteres, no números*/
            if(preg_match('/^[a-zA-Z-ñÑáéíóúÁÉÍÓÚ ]+$/',$_POST["nombreNuevo"]) &&
               preg_match('/^[a-zA-Z-ñÑáéíóúÁÉÍÓÚ ]+$/',$_POST["identificadorNuevo"]) &&
               preg_match('/^[0-9]+$/',$_POST["idNuevo"])) 
            {
                /* coloco en un array los datos obtenidos del envío del 
                formulario a través de las variables tipo $_POST*/
                $nuevaTupla=array("NOMBRES"=> $_POST["nombreNuevo"],
                            "IDENTIFICADOR"=> $_POST["identificadorNuevo"],
                            "NIP_ULT_USR_MODIFICADOR"=> $_SESSION["NIP"],
                            "ROL"=>$_SESSION["DESCRIPCION"]
                            );

                /*enviamos a nuestro modelo de seccion, el nombre de la tabla
                y array de datos que se obtuvo de la vista*/
                $answer=ModelSeccion::mdlCrearSeccion($nuevaTupla);

                /*si se almacenan correctamente los datos del seccion en la tabla
                seccion */
                if($answer==true)
                {
                    /*lanzo una alerta suave (notificación)
                    que el seccion fue almacenado en la bd correctamente*/
                    echo
                    '<script>
                             
                                Swal.fire({
                                    position: "center",
                                    icon: "success",
                                    title: "La seccion Fue creada con éxito",
                                    showConfirmButton: false,
                                    timer: 2000
                                }).then((result)=>{
                                    window.location="seccion";
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
                            window.location="seccion";
                         }); 
                    </script>';
                }

            }
            /*si los datos que ingresa el seccion no coinciden con 
            las parametrizaciones que realicé a través de las expresiones
            regulares*/
            else
            {
                /*lanzo un alert para indicarle al seccion que la información
                que brinda es errónea y no cumple con la evaluación descrita en el
                if con la función preg_match sobre los campos señalados anteriormente */
                echo
                    '<script>
                            swal.fire({
                                type:"error",
                                icon: "error",
                                title: "Solo se permiten números en el campo NIP; en los campos Nombres y Apellidos, letras y vocales",
                            }).then((result)=>{
                                window.location="seccion";
                            }); 
                    </script>';
            }
        }


    }

    /*método para mostrar los seccions*/
    static public function controlerMostrarSeccion($campo,$valor)
    {
        /*tabla de seccions*/
        $tbl="VISTA_CRUD_PERITO";

        $ans=ModelSeccion::mdlMostrarSeccion($tbl,$campo,$valor);

        /*retornamos la respuesta a la vista */    
        return $ans;

    }



    static public function controlerEditarSeccion()
    {
        /*verifico si la variable post (nipEditar) de edicion
        contiene valores */
        if(isset($_POST["nipEditar"]))
        {
            /*vuelvo a hacer la validación de la entrada de caracteres
            que permito */
            if(preg_match('/^[a-zA-Z-ñÑáéíóúÁÉÍÓÚ ]+$/',$_POST["nombreEditar"]) &&
               preg_match('/^[a-zA-Z-ñÑáéíóúÁÉÍÓÚ ]+$/',$_POST["identificadorEditar"]) &&
               preg_match('/^[0-9]+$/',$_POST["nipEditar"]))
            {
                /*con las validaciones anteriores, ya podré enviar
                los campos actualizados al modelo */
                $nuevaTupla=array("ID"=> $_POST["idEditar"],
                            "NOMBRES"=> $_POST["nombreEditar"],
                            "IDENTIFICADOR"=> $_POST["identificadorEditar"],
                            "NIP_ULT_USR_MODIFICADOR"=> $_SESSION["NIP"],
                            "ROL"=>$_SESSION["DESCRIPCION"]
                            );
                /*solicitando  respuesta al modelo */
                $ans=ModelSeccion::mdlEditarSeccion($nuevaTupla);
                /*si la actualización se realizó con éxito, lanzo una 
                notificación para informarle al seccion */
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
                                    window.location="seccion";
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
                            window.location="seccion";
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
                                title: "solo se aceptan letras y vocales en los campos, intente  de nuevo"
                            }).then((result)=>{
                                window.location="seccion";
                            }); 
                    </script>';
            }
        }
    }


    /*controler para borrar seccion (dar de baja)*/
    static public function controlerBorrarSeccion()
    {
        /*verifico el id del seccion a eliminar (NIP) y
        que el id del seccion responsable de la ejecución de  la acción
        sean registrados*/
        if(isset($_GET["id_seccion_baja"]))
        {
            /*solicitamos al modelo que ejecute la acción y envie respuesta */
            $res=ModelSeccion::mdlBorrarSeccion($_GET["id_seccion_baja"],$_GET["nip_seccion_Responsable"]);
            /*luego de obtener respuesta del modelo,
            verifico si la acción fue realizada con éxito */
            if($res==true)
            {
                echo
                    '<script>
                                
                        Swal.fire({
                            position: "center",
                            icon: "success",
                            title: "Seccion Eliminada Exitosamente",
                            showConfirmButton: false,
                            timer: 2000
                            }).then((result)=>{
                                window.location="seccion";
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
                                window.location="seccion";
                            }); 
                    </script>';
            }
        }

    }

}