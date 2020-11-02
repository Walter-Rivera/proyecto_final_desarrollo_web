<?php

class ContrlPerito
{
    static public function controlerPerito()
    {
        /*incluimos el archivo perito.php para poder hacer uso del mismo */
        include "Vista/Modulos/perito.php";
    }


    /*método para poder crear un perito en el sistema*/
    static public function controlerCrearPerito()
    {
        /*si se trea una variable tipo post
        y se está enviando por medio del formulario de creación de perito
        en el front, puede ser cualquier variable del formulario*/
        if(isset($_POST["nipNuevo"]))
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
               preg_match('/^[a-zA-Z-ñÑáéíóúÁÉÍÓÚ ]+$/',$_POST["apellidoNuevo"]) &&
               preg_match('/^[0-9]+$/',$_POST["nipNuevo"])) 
            {
                /* coloco en un array los datos obtenidos del envío del 
                formulario a través de las variables tipo $_POST*/
                $nuevaTupla=array("NIP"=> $_POST["nipNuevo"],
                            "NOMBRES"=> $_POST["nombreNuevo"],
                            "APELLIDOS"=> $_POST["apellidoNuevo"],
                            "NIP_ULT_USR_MODIFICADOR"=> $_SESSION["NIP"],
                            "ROL"=>$_SESSION["DESCRIPCION"]
                            );

                /*enviamos a nuestro modelo de perito, el nombre de la tabla
                y array de datos que se obtuvo de la vista*/
                $answer=ModelPerito::mdlCrearPerito($nuevaTupla);

                /*si se almacenan correctamente los datos del perito en la tabla
                perito */
                if($answer==true)
                {
                    /*lanzo una alerta suave (notificación)
                    que el perito fue almacenado en la bd correctamente*/
                    echo
                    '<script>
                             
                                Swal.fire({
                                    position: "center",
                                    icon: "success",
                                    title: "El perito Fue creado con éxito",
                                    showConfirmButton: false,
                                    timer: 2000
                                }).then((result)=>{
                                    window.location="perito";
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
                            window.location="perito";
                         }); 
                    </script>';
                }

            }
            /*si los datos que ingresa el perito no coinciden con 
            las parametrizaciones que realicé a través de las expresiones
            regulares*/
            else
            {
                /*lanzo un alert para indicarle al perito que la información
                que brinda es errónea y no cumple con la evaluación descrita en el
                if con la función preg_match sobre los campos señalados anteriormente */
                echo
                    '<script>
                            swal.fire({
                                type:"error",
                                icon: "error",
                                title: "Solo se permiten números en el campo NIP; en los campos Nombres y Apellidos, letras y vocales",
                            }).then((result)=>{
                                window.location="perito";
                            }); 
                    </script>';
            }
        }


    }

    /*método para mostrar los peritos*/
    static public function controlerMostrarPeritos($campo,$valor)
    {
        /*tabla de peritos*/
        $tbl="VISTA_CRUD_PERITO";

        $ans=ModelPerito::mdlMostrarPerito($tbl,$campo,$valor);

        /*retornamos la respuesta a la vista */    
        return $ans;

    }



    static public function controlerEditarPerito()
    {
        /*verifico si la variable post (nipEditar) de edicion
        contiene valores */
        if(isset($_POST["nipEditar"]))
        {
            /*vuelvo a hacer la validación de la entrada de caracteres
            que permito */
            if(preg_match('/^[a-zA-Z-ñÑáéíóúÁÉÍÓÚ ]+$/',$_POST["nombreEditar"]) &&
               preg_match('/^[a-zA-Z-ñÑáéíóúÁÉÍÓÚ ]+$/',$_POST["apellidoEditar"]) &&
               preg_match('/^[0-9]+$/',$_POST["nipEditar"]))
            {
                /*con las validaciones anteriores, ya podré enviar
                los campos actualizados al modelo */
                $nuevaTupla=array("NIP"=> $_POST["nipEditar"],
                            "NOMBRES"=> $_POST["nombreEditar"],
                            "APELLIDOS"=> $_POST["apellidoEditar"],
                            "NIP_ULT_USR_MODIFICADOR"=> $_SESSION["NIP"],
                            "ROL"=>$_SESSION["DESCRIPCION"]
                            );
                /*solicitando  respuesta al modelo */
                $ans=ModelPerito::mdlEditarPerito($nuevaTupla);
                /*si la actualización se realizó con éxito, lanzo una 
                notificación para informarle al perito */
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
                                    window.location="perito";
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
                            window.location="perito";
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
                                title: "La contraseña debe incluir números y letras",
                            }).then((result)=>{
                                window.location="perito";
                            }); 
                    </script>';
            }
        }
    }


    /*controler para borrar perito (dar de baja)*/
    static public function controlerBorrarPerito()
    {
        /*verifico el id del perito a eliminar (NIP) y
        que el id del perito responsable de la ejecución de  la acción
        sean registrados*/
        if(isset($_GET["id_Perito_baja"]))
        {
            /*solicitamos al modelo que ejecute la acción y envie respuesta */
            $res=ModelPerito::mdlBorrarPerito($_GET["id_Perito_baja"],$_GET["nip_Perito_Responsable"]);
            /*luego de obtener respuesta del modelo,
            verifico si la acción fue realizada con éxito */
            if($res==true)
            {
                echo
                    '<script>
                                
                        Swal.fire({
                            position: "center",
                            icon: "success",
                            title: "Perito Eliminado Exitosamente",
                            showConfirmButton: false,
                            timer: 2000
                            }).then((result)=>{
                                window.location="perito";
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
                                window.location="perito";
                            }); 
                    </script>';
            }
        }

    }

}