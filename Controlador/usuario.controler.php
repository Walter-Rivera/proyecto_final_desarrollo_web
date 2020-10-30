<?php

class ContrlUsuario
{
    static public function controlerUsuario()
    {
        /*incluimos el archivo usuario.php para poder hacer uso del mismo */
        include "Vista/Modulos/usuario.php";
    }

    /*función para validar el ingreso del usuario*/
    static public function controlerIngresoUsuario()
    {
        try
        {
            
            /*verificamos si traemos variables enviadas a través del método post del formulario*/
            if(isset($_POST["nipUsuario"]))
            {
                /*haciendo validación del lado del servidor para no permitir valores especiales,
                a fin de evitar que nos hagan un sql Inyection, solo vamos a aceptar
                letras y vocales de la a-z y números del 0 al 9*/
                if(preg_match('/^[0-9]+$/',$_POST["nipUsuario"]) && 
                    preg_match('/^[a-zA-Z0-9]+$/',$_POST["Ingreso"]))
                {
                    /*vamos a enviar a la tabla usuarios los siguientes datos para comprobar
                    si el usuario existe*/
                    /*Seleccionamos la tabla a la que queremos ir a hacer la consulta*/
                    $tbl="VISTA_CRUD_USUARIO";
                    /*campo a verificar*/
                    $campo="NIP";
                    /*Vamos a comparar la variable post (nipUsuario) con $campo*/
                    $entrada_Usuario=$_POST["nipUsuario"];
                    /*solicitando respusta del modelo*/
                    $respuesta=ModelUsuario::MdlMostrarUsuario($tbl,$campo,$entrada_Usuario);
                    /*mostrando la respuesta, solo extraemos del array que nos trae,
                    el NIP del usuario*/
                    /*empezamos a comparar el resultado de la comparación con el modelo, es
                    decir, validamos si el usuario y contraseña existen en nuestra bd*/
                    /*Encripto la contraseña con el método salt CRYPT_BLOWFISH */
                    $encrypt=crypt($_POST["Ingreso"],'$2a$07$lsijooasdljiwIQKTBQRwgrl$');

                    if($respuesta["NIP"] == $_POST["nipUsuario"] && $respuesta["ACCESO"] == $encrypt)
                    { 
                       
                        /*validamos que el usuario esté activo para poder iniciar sesión */
                        if($respuesta["ESTADO_USUARIOS"]=="ACTIVO")
                        {
                            /*con el filtro anterior, podemos crear la variable de sesión a fin de conocer
                            cual usuario está logueado en el sistema*/
                            $_SESSION["login"]="adelante";
                            /*también, capturamos los datos del usuario para colocar en el encabezado sus datos
                            (nombres, apellidos) */
                            $_SESSION["NIP"]=$respuesta["NIP"];
                            $_SESSION["NOMBRES"]=$respuesta["NOMBRES"];
                            $_SESSION["APELLIDOS"]=$respuesta["APELLIDOS"];
                            $_SESSION["DESCRIPCION"]=$respuesta["ROL"];
                            /*almacenar el último login */

                            $act=ModelUsuario::mdlActualizarLogin($_SESSION["NIP"]);
                            if($act==true)
                            {
                                /*por lo anterior, hacemos un redireccionamiento de ubicación de la página,
                                a la de inicio,se hará a través de javascript*/
                                echo '<script>
                                window.location="ini";
                                </script>';
                            }
                            $nipSesion=$_SESSION["NIP"];
                        }
                        else
                        {
                            echo '<br/>';
                            echo '<div class="alert alert-danger">Usuario Inactivo</div>';

                        }
                        
                    }
                    else
                    {
                        /*si alguno de los datos no coincide o ambos,
                        le mostramos un alert al usuario para notificarle que alguno de los datos
                        es incorrecto (en esto usamos clases de bootstrap*/
                        echo '<br/>';
                        echo '<div class="alert alert-danger">Usuario o Contraseña incorrecta, verifique sus datos por favor</div>';
                    }
                }
            }   
        }catch(PDOException $ex)
        {
             echo
                '<script>
                        swal.fire({
                            type:"error",
                            icon: "error",
                            title: "'.$Ex->getMessage().'",
                        }).then((result)=>{
                            window.location="usuario";
                         }); 
                </script>';
            
        }
        
    }

    /*método para poder crear un usuario en el sistema*/
    static public function controlerCrearUsuario()
    {
        /*si se trea una variable tipo post
        y se está enviando por medio del formulario de creación de usuario
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
               preg_match('/^[0-9]+$/',$_POST["nipNuevo"])&&
               preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/',$_POST["contraNuevo"])) 
            {
        
             
                /*procedo a encriptar la contraseña,
                la cifraré usando el método salt que consiste en la generación
                de números aleatorios que se le agregan al código hash al principio y al final */
                /*para ello, nos apoyamos de la función crypt de php, la cual lleva 2 parámetros
                el primero es el valor a encriptar,el segundo parámetro es el tipo de salt que voy a usar,
                en este caso usaré CRYPT_BLOWFISH el cual,
                según la página oficial de php:
                CRYPT_BLOWFISH - Hash con Blowfish con un salt como sigue: "$2a$", "$2x$" o "$2y$", 
                un parámetro de coste de dos dígitos, "$", y 22 caracteres del alfabeto "./0-9A-Za-z". 
                Utilizar caracteres fuera de este rango en el salt causará que crypt() 
                devuelva una cadena de longitud cero. El parámetro de coste de dos dígitos es el logaritmo
                en base 2 de la cuenta de la iteración del algoritmo hach basado en Blowfish subyacente,
                y debe estar en el rango 04-31; los valores fuera de este rango causarán que crypt() falle. 
                Las versiones de PHP anteriores a 5.3.7 únicamente admitían "$2a$" como el prefijo para salt:
                PHP 5.3.7 introdujo los nuevos prefijos para corregir un problema de seguridad 
                en la implementación de Blowfish. Por favor, consulte » este documento para detalles completos
                de la corrección de seguridad, pero para resumir, los desarrolladores 
                que se oriente por PHP 5.3.7 y posteriores deberían usar "$2y$" en vez de "$2a$". */
                $encrypt=crypt($_POST["contraNuevo"],'$2a$07$lsijooasdljiwIQKTBQRwgrl$');

                
                
                /* coloco en un array los datos obtenidos del envío del 
                formulario a través de las variables tipo $_POST*/
                $nuevaTupla=array("NIP"=> $_POST["nipNuevo"],
                            "NOMBRES"=> $_POST["nombreNuevo"],
                            "APELLIDOS"=> $_POST["apellidoNuevo"],
                            "CORREO_INSTITUCIONAL"=> $_POST["correoNuevo"],
                            "ID_ROL_USUARIO"=> $_POST["rolNuevo"],
                            /*ENnvío ya la clave encriptada */
                            "ACCESO"=> $encrypt,
                            "NIP_ULT_USR_MODIFICADOR"=> $_SESSION["NIP"]
                            );

                /*enviamos a nuestro modelo de usuario, el nombre de la tabla
                y array de datos que se obtuvo de la vista*/
                $answer=ModelUsuario::MdlCrearUsuario($nuevaTupla);

                /*si se almacenan correctamente los datos del usuario en la tabla
                USUARIO_SISTEMA */
                if($answer==true)
                {
                    /*lanzo una alerta suave (notificación)
                    que el usuario fue almacenado en la bd correctamente*/
                    echo
                    '<script>
                             
                                Swal.fire({
                                    position: "center",
                                    icon: "success",
                                    title: "El usuario Fue creado con éxito",
                                    showConfirmButton: false,
                                    timer: 2000
                                }).then((result)=>{
                                    window.location="usuario";
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
                            window.location="usuario";
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
                                window.location="usuario";
                            }); 
                    </script>';
            }
        }


    }

    /*método para mostrar los usuarios*/
    static public function controlerMostrarUsuarios($campo,$valor)
    {
        /*tabla de usuarios*/
        $tbl="VISTA_CRUD_USUARIO";

        $ans=ModelUsuario::MdlMostrarUsuario($tbl,$campo,$valor);

        /*retornamos la respuesta a la vista */    
        return $ans;

    }



    static public function controlerEditarUsuario()
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
                /*vamos a validar si se va a actualizar la contraseña, se debe
                encriptar de nuevo */
                if($_POST["contraEditar"]!="")
                {
                    /*validamos que la contraseña actualiza solo traiga
                    caracteres permitidos */
                    if(preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/',$_POST["contraEditar"]))
                    {
                        $encrypt=crypt($_POST["contraEditar"],'$2a$07$lsijooasdljiwIQKTBQRwgrl$');
                    }
                    else
                    {
                        /*si el usuario ingresa caracteres no permitidos, le notificamos */
                        echo
                        '<script>
                                swal.fire({
                                    type:"error",
                                    icon: "error",
                                    title: "No se permite el uso de caracteres especiales o la contraseña está vacía",
                                }).then((result)=>{
                                    window.location="usuario";
                                 }); 
                        </script>';
                    } 
                }
                else
                {
                    /*sino se actualiza la contraseña, guardamos la que 
                    siempre ha tenido (la última que definió) */
                    $encrypt=$_POST["contraActual"];
                }
                
                /*con las validaciones anteriores, ya podré enviar
                los campos actualizados al modelo */
                $nuevaTupla=array("NIP"=> $_POST["nipEditar"],
                            "NOMBRES"=> $_POST["nombreEditar"],
                            "APELLIDOS"=> $_POST["apellidoEditar"],
                            "CORREO_INSTITUCIONAL"=> $_POST["correoEditar"],
                            "ID_ROL_USUARIO"=> $_POST["rolEditar"],
                            /*ENnvío ya la clave encriptada */
                            "ACCESO"=> $encrypt,
                            "NIP_ULT_USR_MODIFICADOR"=> $_SESSION["NIP"]
                            );
                /*solicitando  respuesta al modelo */
                $ans=ModelUsuario::MdlEditarUsuario($nuevaTupla);
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
                                    window.location="usuario";
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
                            window.location="usuario";
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
                                title: "Revise sus datos por favor",
                            }).then((result)=>{
                                window.location="usuario";
                            }); 
                    </script>';
            }
        }
    }


    /*controler para borrar usuario (dar de baja)*/
    static public function controlerBorrarUsuario()
    {
        /*verifico el id del usuario a eliminar (NIP) y
        que el id del usuario responsable de la ejecución de  la acción
        sean registrados*/
        if(isset($_GET["id_Usuario_baja"]))
        {
            /*solicitamos al modelo que ejecute la acción y envie respuesta */
            $res=ModelUsuario::mdlBorrarUsuario($_GET["id_Usuario_baja"],$_GET["nip_Usuario_Responsable"]);
            /*luego de obtener respuesta del modelo,
            verifico si la acción fue realizada con éxito */
            if($res==true)
            {
                echo
                    '<script>
                                
                        Swal.fire({
                            position: "center",
                            icon: "success",
                            title: "Usuario Eliminado Exitosamente",
                            showConfirmButton: false,
                            timer: 2000
                            }).then((result)=>{
                                window.location="usuario";
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
                                window.location="usuario";
                            }); 
                    </script>';
            }
        }

    }

}