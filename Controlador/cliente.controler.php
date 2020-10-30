<?php

class ContrlCliente
{
    static public function controlerCliente()
    {
        /*incluimos el archivo cliente.php para poder hacer uso del mismo */
        include "Vista/Modulos/cliente.php";
    }

    /*método para poder crear un cliente en el sistema*/
    static public function controlerCrearCliente()
    {
        /*si se trea una variable tipo post
        y se está enviando por medio del formulario de creación de cliente
        en el front, puede ser cualquier variable del formulario*/
        if(isset($_POST["nipNuevo"]))
        {
            /*validamos la información contenida en las variables posts,
            con preg match lo que se hará es permitir que los campos solo acepten
            un conjunto de caracteres en específico, esto para poder evitar ataques de XSS
            soporte el uso de caracteres especiales, tal es el caso de tildes únicamente
            en  vocales, letras
            y vocales de la a-z (mayúsculas, minúsculas), dígitos del 0 al 9,
            las ñ's (mayúsculas y minúsculas) en el caso de las tildes, se permitirán
            únicamente sobre las vocales y espacio en blaco de necesitarlo etc */

            /*en el caso de los nombres y apellidos vamos a validar 
            que solo se permitan caracteres, no números
            En el caso del nip, permitiremos únicamente números*/
            if(preg_match('/^[a-zA-Z-ñÑáéíóúÁÉÍÓÚ ]+$/',$_POST["nombreNuevo"]) &&
               preg_match('/^[a-zA-Z-ñÑáéíóúÁÉÍÓÚ ]+$/',$_POST["apellidoNuevo"]) &&
               preg_match('/^[0-9]+$/',$_POST["nipNuevo"])) 
            {
        
                /* coloco en un array los datos obtenidos del envío del 
                formulario a través de las variables tipo $_POST*/
                $nuevaTupla=array("NIP"=> $_POST["nipNuevo"],
                            "NOMBRES"=> $_POST["nombreNuevo"],
                            "APELLIDOS"=> $_POST["apellidoNuevo"],
                            "CORREO_INSTITUCIONAL"=> $_POST["correoNuevo"],
                            "SECCION"=> $_POST["seccionN"],
                            "NIP_ULT_USR_MODIFICADOR"=> $_SESSION["NIP"]
                            );

                /*enviamos a nuestro modelo de cliente, el nombre de la tabla
                y array de datos que se obtuvo de la vista*/
                $answer=ModelCliente::MdlCrearCliente($nuevaTupla);

                /*si se almacenan correctamente los datos del cliente en la tabla */
                if($answer==true)
                {
                    /*lanzo una alerta suave (notificación)
                    que el cliente fue almacenado en la bd correctamente*/
                    echo
                    '<script>
                             
                                Swal.fire({
                                    position: "center",
                                    icon: "success",
                                    title: "El cliente Fue creado con éxito",
                                    showConfirmButton: false,
                                    timer: 2000
                                }).then((result)=>{
                                    window.location="cliente";
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
                                title: "Solo se permiten números en el campo NIP; en los campos Nombres y Apellidos, letras y vocales",
                            }).then((result)=>{
                                window.location="cliente";
                            }); 
                    </script>';
            }
        }


    }

    /*método para mostrar los clientes*/
    static public function controlerMostrarClientes($campo,$valor)
    {
        /*datos de clientes*/
        $tbl="VISTA_CRUD_CLIENTE";

        $ans=ModelCliente::MdlMostrarCliente($tbl,$campo,$valor);

        /*retornamos la respuesta a la vista */    
        return $ans;

    }



    static public function controlerEditarClientes()
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
                            "CORREO_INSTITUCIONAL"=> $_POST["correoEditar"],
                            "SECCION"=> $_POST["seccionE"],
                            "NIP_ULT_USR_MODIFICADOR"=> $_SESSION["NIP"]
                            );
                /*solicitando  respuesta al modelo */
                $ans=ModelCliente::mdlEditarCliente($nuevaTupla);
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
                                    window.location="cliente";
                                }); 
                    </script>';

                }
                else
                {
                    echo
                    '<script>
                        Swal.fire({
                            position:"center",
                            icon: "error",
                            title: "error:"'.$ans.'"contacte al dba",
                            showConfirmButton: false,
                            timer:3000
                        }).then((result)=>{
                            window.location="cliente";
                        });
                        <script>';
                }

            } else
            {
                echo
                     '<script>
                            swal.fire({
                                type:"error",
                                icon: "error",
                                title: "Solo se permiten números en el campo NIP; en los campos Nombres y Apellidos, letras y vocales",
                            }).then((result)=>{
                                window.location="cliente";
                            }); 
                    </script>';
            }
        }
    }


    /*controler para borrar cliente (dar de baja)*/
    static public function controlerBorrarCliente()
    {
        /*verifico el id del cliente a eliminar (NIP) y
        que el id del cliente responsable de la ejecución de  la acción
        sean registrados*/
        if(isset($_GET["id_Cliente_baja"]))
        {
            /*solicitamos al modelo que ejecute la acción y envie respuesta */
            $res=ModelCliente::mdlBorrarCliente($_GET["id_Cliente_baja"],$_GET["nip_Usuario_Responsable"]);
            /*luego de obtener respuesta del modelo,
            verifico si la acción fue realizada con éxito */
            if($res==true)
            {
                echo
                    '<script>
                                
                        Swal.fire({
                            position: "center",
                            icon: "success",
                            title: "Cliente Eliminado Exitosamente",
                            showConfirmButton: false,
                            timer: 2000
                            }).then((result)=>{
                                window.location="cliente";
                            }); 
                        </script>';

            } else
            {
                echo
                    '<script>
                            swal.fire({
                                type:"error",
                                icon: "error",
                                title: "Error:'.$res.' contacte al desarollador",
                            }).then((result)=>{
                                window.location="cliente";
                            }); 
                    </script>';
            }
        }

    }

    static public function controlerMostarSeccion()
    {
        $respuesta=ModelCliente::mdlMostrarSeccion();
        return $respuesta;
    }

}