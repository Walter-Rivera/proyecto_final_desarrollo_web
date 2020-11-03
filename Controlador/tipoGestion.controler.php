<?php

class ContrlTipoGestion
{
    static public function controlerTipoGestion()
    {
        /*incluimos el archivo tipoGestion.php para poder hacer uso del mismo */
        include "Vista/Modulos/tipoGestion.php";
    }


    /*método para poder crear un tipoGestion en el sistema*/
    static public function controlerCrearTipoGestion()
    {
        /*si se trea una variable tipo post
        y se está enviando por medio del formulario de creación de tipoGestion
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

            /*en el caso de los nombres y apellidos vamos a validar 
            que solo se permitan caracteres, no números*/
            if(preg_match('/^[a-zA-Z-ñÑáéíóúÁÉÍÓÚ ]+$/',$_POST["nombreNuevo"])) 
            {
                /* coloco en un array los datos obtenidos del envío del 
                formulario a través de las variables tipo $_POST*/
                $nuevaTupla=array("NOMBRES"=> $_POST["nombreNuevo"],
                            "CLASE_GESTION"=> $_POST["TipoGestionC"],
                            "NIP_ULT_USR_MODIFICADOR"=> $_SESSION["NIP"],
                            "ROL"=>$_SESSION["DESCRIPCION"]
                            );

                /*enviamos a nuestro modelo de tipoGestion, el nombre de la tabla
                y array de datos que se obtuvo de la vista*/
                $answer=ModelTipoGestion::mdlCrearTipoGestion($nuevaTupla);

                /*si se almacenan correctamente los datos del tipoGestion en la tabla
                tipoGestion */
                if($answer==true)
                {
                    /*lanzo una alerta suave (notificación)
                    que el tipoGestion fue almacenado en la bd correctamente*/
                    echo
                    '<script>
                             
                                Swal.fire({
                                    position: "center",
                                    icon: "success",
                                    title: "El tipo de Gestion Fue creado con éxito",
                                    showConfirmButton: false,
                                    timer: 2000
                                }).then((result)=>{
                                    window.location="tipoGestion";
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
                            window.location="tipoGestion";
                         }); 
                    </script>';
                }

            }
            /*si los datos que ingresa el tipoGestion no coinciden con 
            las parametrizaciones que realicé a través de las expresiones
            regulares*/
            else
            {
                /*lanzo un alert para indicarle al tipoGestion que la información
                que brinda es errónea y no cumple con la evaluación descrita en el
                if con la función preg_match sobre los campos señalados anteriormente */
                echo
                    '<script>
                            swal.fire({
                                type:"error",
                                icon: "error",
                                title: " En el campo nombre solo se permiten letras y vocales",
                            }).then((result)=>{
                                window.location="tipoGestion";
                            }); 
                    </script>';
            }
        }


    }

    /*método para mostrar los tipoGestions*/
    static public function controlerMostrarTipoGestion($campo,$valor)
    {
        /*tabla de tipoGestions*/
        $tbl="VISTA_CRUD_TIPO_GESTION";

        $ans=ModelTipoGestion::mdlMostrarTipoGestion($tbl,$campo,$valor);

        /*retornamos la respuesta a la vista */    
        return $ans;

    }



    static public function controlerEditarTipoGestion()
    {
        /*verifico si la variable post (nipEditar) de edicion
        contiene valores */
        if(isset($_POST["idEditar"]))
        {
            /*vuelvo a hacer la validación de la entrada de caracteres
            que permito */
            if(preg_match('/^[a-zA-Z-ñÑáéíóúÁÉÍÓÚ ]+$/',$_POST["nombreEditar"]) &&
               preg_match('/^[0-9]+$/',$_POST["idEditar"]))
            {
                /*con las validaciones anteriores, ya podré enviar
                los campos actualizados al modelo */
                $nuevaTupla=array("ID"=> $_POST["idEditar"],
                            "NOMBRES"=> $_POST["nombreEditar"],
                            "CLASE_GESTION"=> $_POST["TipoGestionE"],
                            "NIP_ULT_USR_MODIFICADOR"=> $_SESSION["NIP"],
                            "ROL"=>$_SESSION["DESCRIPCION"]
                            );
                /*solicitando  respuesta al modelo */
                $ans=ModelTipoGestion::mdlEditarTipoGestion($nuevaTupla);
                /*si la actualización se realizó con éxito, lanzo una 
                notificación para informarle al tipoGestion */
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
                                    window.location="tipoGestion";
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
                            window.location="tipoGestion";
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
                                window.location="tipoGestion";
                            }); 
                    </script>';
            }
        }
    }


    /*controler para borrar tipoGestion (dar de baja)*/
    static public function controlerBorrarTipoGestion()
    {
        /*verifico el id del tipoGestion a eliminar  y
        que el id del tipoGestion responsable de la ejecución de  la acción
        sean registrados*/
        if(isset($_GET["id_TipoGestion_baja"]))
        {
            /*solicitamos al modelo que ejecute la acción y envie respuesta */
            $res=ModelTipoGestion::mdlBorrarTipoGestion($_GET["id_TipoGestion_baja"],$_GET["nip_TipoGestion_Responsable"]);
            /*luego de obtener respuesta del modelo,
            verifico si la acción fue realizada con éxito */
            if($res==true)
            {
                echo
                    '<script>
                                
                        Swal.fire({
                            position: "center",
                            icon: "success",
                            title: "TipoGestion Eliminado Exitosamente",
                            showConfirmButton: false,
                            timer: 2000
                            }).then((result)=>{
                                window.location="tipoGestion";
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
                                window.location="tipoGestion";
                            }); 
                    </script>';
            }
        }

    }

    static public function controlerMostarClase()
    {
        $ans=ModelTipoGestion::mdlmostrarClase();   
        return $ans;
    }

}