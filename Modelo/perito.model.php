<?php
/*solicitando la conexión a la base de datos*/
require_once "permiso.php"; 

    class ModelPerito
    {
        /*método para mostar el usuario
        recibirá como parámetros el nombre de latabla,
        el campo a verificar, y el valor que nos están enviando*/
        static public function MdlMostrarUsuario($tbl,$cmp,$val)
        {
          /*como este método servirá para recuperar uno o más usuarios
          vamos a condicionar esa situación, si $cmp es diferente de nulo, porque 
          solo vamos a comparar una fila con un valor */
          if($cmp!=null)
          {
            /*hacemos un manejo de excepciones si en dado caso no se logra el 
            resultado esperado (la inserción de información) por diferentes circunstancias
            (nombre mal escrito de los campos de la tabla, el nombre de la tabla misma, etc)*/   
            try {
                /*solicitar respuesta de conexión y preparar consulta sql*/
                $resp=Permiso::acceder()->prepare("SELECT * FROM $tbl WHERE $cmp = :$cmp");
                /*se enlaza el parámetro a fin de proteger la bd para
                evitar que nos realicen un sql inyection, el segundo parámetro es 
                el valor a comparar, para evitar también que nos quieran hacer un
                ataque por medio de scripts, vamos a indicar que el parámetro
                solo recibirá strings/caracteres*/
                $resp->bindParam(":".$cmp,$val,PDO::PARAM_STR);
                /*ejecutamos el objeto*/
                $resp->execute();
                /*retornamos el objeto al controler*/
                return $resp->fetch();
                /*cerrando la conexión a la bd */
                $resp->close();
                /*apuntamos a null el objeto para que no alcene los datos de conexión anteriormente
                ejecutados */
                $resp=null;
            } catch (PDOException $Ex) {
                /*si se llegase a dar un fallo, muestro de donde surgió
                a fin de depurar todo ello antes de pasarlo a produccción */
                return $Ex->getMessage();
            }
          }
          else
          {
            /*hacemos un manejo de excepciones si en dado caso no se logra el 
            resultado esperado (la inserción de información) por diferentes circunstancias
            (nombre mal escrito de los campos de la tabla, el nombre de la tabla misma, etc)*/   
            try {
                /*solicitar respuesta de conexión y preparar consulta sql*/
                $resp=Permiso::acceder()->prepare("SELECT * FROM VISTA_CRUD_USUARIO");
                /*ejecutamos el objeto*/
                $resp->execute();
                /*retornamos todas las tuplas al controler*/
                return $resp->fetchAll();
                /*cerrando la conexión a la bd */
                $resp->close();
                /*apuntamos a null el objeto para que no alcene los datos de conexión anteriormente
                ejecutados */
                $resp=null;
            } catch (PDOException $Ex) {
                /*si se llegase a dar un fallo, muestro de donde surgió
                a fin de depurar todo ello antes de pasarlo a produccción */
                /*echo "Excepción:  ".$Ex->getMessage()."<br>";
                echo "Número de Excepción: ".$Ex->getCode()."<br>";
                echo "Documento causable del fallo: ".$Ex->getCode()."<br>";
                echo "Error en la línea... : ".$Ex->getLine()."<br>";
                echo "informe general: ".$Ex->__toString()."<br>";*/
                return $Ex->getMessage();
            }

          }
        }

        /*función para almacenar en la bd la creación de un nuevo usuario*/
        static public function MdlCrearUsuario($nuevaTupla)
        {
            try {
                
                /*Hago uso de sentencias preparadas para evitar
                ataques de SQLInyection*/
                $query="CALL PA_CREAR_USUARIO_SISTEMA(?,?,?,?,?,?)";
                $INSERCIONUSUARIO=Permiso::acceder()->prepare($query);
                $INSERCIONUSUARIO->bindParam(1,$nuevaTupla["NIP"],PDO::PARAM_INT);
                $INSERCIONUSUARIO->bindParam(2,$nuevaTupla["NOMBRES"],PDO::PARAM_STR);
                $INSERCIONUSUARIO->bindParam(3,$nuevaTupla["APELLIDOS"],PDO::PARAM_STR);
                $INSERCIONUSUARIO->bindParam(4,$nuevaTupla["ID_ROL_USUARIO"],PDO::PARAM_STR);
                $INSERCIONUSUARIO->bindParam(5,$nuevaTupla["ACCESO"],PDO::PARAM_STR);
                $INSERCIONUSUARIO->bindParam(6,$nuevaTupla["NIP_ULT_USR_MODIFICADOR"],PDO::PARAM_INT);
               /* print "Values of bound parameters _before_ CALL:\n";
                print "  1: {$nuevaTupla["NIP"]} 2: {$nuevaTupla["NOMBRES"]}\n"; 
                print "  3: {$nuevaTupla["APELLIDOS"]} 4: {$nuevaTupla["CORREO_INSTITUCIONAL"]}\n";
                print "  5: {$nuevaTupla["ID_ROL_USUARIO"]} 4: {$nuevaTupla["ACCESO"]}\n";
                
                /*si la consulta se ejecuta correctamente vamos 
                a devolver un true para notificar al modelo y este a la vista*/
                if($INSERCIONUSUARIO->execute())
                {
                   return true;
                }
               
                /*cierro la conexión*/
                $INSERCIONUSUARIO->close();
                /*vacio el objeto recién creado*/
                $INSERCIONUSUARIO=null;

            } catch (PDOException $Ex) {
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

        /*método para llamar al stored procedure de editar usuarios */
        static public function MdlEditarUsuario($nuevaTupla)
        {
            try {
                
                /*Hago uso de sentencias preparadas para evitar
                ataques de SQLInyection*/
                $query="CALL PA_ACTUALIZAR_USUARIO_SISTEMA(?,?,?,?,?,?)";
                $ACTUALIZACIONUSUARIO=Permiso::acceder()->prepare($query);
                $ACTUALIZACIONUSUARIO->bindParam(1,$nuevaTupla["NIP"],PDO::PARAM_INT);
                $ACTUALIZACIONUSUARIO->bindParam(2,$nuevaTupla["NOMBRES"],PDO::PARAM_STR);
                $ACTUALIZACIONUSUARIO->bindParam(3,$nuevaTupla["APELLIDOS"],PDO::PARAM_STR);
                $ACTUALIZACIONUSUARIO->bindParam(4,$nuevaTupla["ID_ROL_USUARIO"],PDO::PARAM_STR);
                $ACTUALIZACIONUSUARIO->bindParam(5,$nuevaTupla["ACCESO"],PDO::PARAM_STR);
                $ACTUALIZACIONUSUARIO->bindParam(6,$nuevaTupla["NIP_ULT_USR_MODIFICADOR"],PDO::PARAM_INT);

                
                /*si la consulta se ejecuta correctamente vamos 
                a devolver un true para notificar al modelo y este a la vista*/
                if($ACTUALIZACIONUSUARIO->execute())
                {
                   return true;
                }
                /*cierro la conexión*/
                $ACTUALIZACIONUSUARIO->close();
                /*vacio el objeto recién creado*/
                $ACTUALIZACIONUSUARIO=null;

            } catch (PDOException $Ex) {
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
                //echo "Excepción:  ".$Ex->getMessage();
                /*echo "Ocurrió un error en la SP. Código de error: {$INSERCIONUSUARIO->errorInfo()[1]}";
                echo "Número de Excepción: ".$Ex->getCode()."<br>";
                echo "Documento causable del fallo: ".$Ex->getCode()."<br>";
                echo "Error en la línea... : ".$Ex->getLine()."<br>";
                echo "informe general: ".$Ex->__toString()."<br>";*/
            }

        }

        static public function MdlActualizarEstadoUsuario($numeroUsuario,$estado,$usr_Responsable)
        {
            /*vamos a actualizar el estado del usuario */
            try
            {
                /*Hago uso de sentencias preparadas para evitar
                ataques de SQLInyection*/
                $query="CALL PA_ACTUALIZACION_ESTADO_USR(?,?,?)";
                $ACTUALIZAESTADO=Permiso::acceder()->prepare($query);
                $ACTUALIZAESTADO->bindParam(1,$numeroUsuario,PDO::PARAM_INT);
                $ACTUALIZAESTADO->bindParam(2,$estado,PDO::PARAM_INT);
                $ACTUALIZAESTADO->bindParam(3,$usr_Responsable,PDO::PARAM_INT);

             /*si la consulta se ejecuta correctamente vamos 
                a devolver un true para notificar al modelo y este a la vista*/
                if($ACTUALIZAESTADO->execute())
                {
                   return true;
                }
               
                /*cierro la conexión*/
                $ACTUALIZAESTADO->close();
                /*vacio el objeto recién creado*/
                $ACTUALIZAESTADO=null;

            } catch (PDOException $Ex) {
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



        /*función para actualizar la fecha y hora de último login en el sistema */

        static public function mdlActualizarLogin($nip)
        {
            /*vamos a actualizar el estado del usuario */
            try
            {
                /*Hago uso de sentencias preparadas para evitar
                ataques de SQLInyection*/
                $query="CALL PA_ACTUALIZACION_ULTIMO_LOGIN(?,?)";
                $ACTUALIZAESTADO=Permiso::acceder()->prepare($query);
                $ACTUALIZAESTADO->bindParam(1,$nip,PDO::PARAM_INT);
                $ACTUALIZAESTADO->bindParam(2,$nip,PDO::PARAM_INT);

             /*si la consulta se ejecuta correctamente vamos 
                a devolver un true para notificar al modelo y este a la vista*/
                if($ACTUALIZAESTADO->execute())
                {
                   return true;
                }
               
                /*cierro la conexión*/
                $ACTUALIZAESTADO->close();
                /*vacio el objeto recién creado*/
                $ACTUALIZAESTADO=null;

            } catch (PDOException $Ex) {
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
                echo "Excepción:  ".$Ex->getMessage();
                /*echo "Ocurrió un error en la SP. Código de error: {$INSERCIONUSUARIO->errorInfo()[1]}";
                echo "Número de Excepción: ".$Ex->getCode()."<br>";
                echo "Documento causable del fallo: ".$Ex->getCode()."<br>";
                echo "Error en la línea... : ".$Ex->getLine()."<br>";
                echo "informe general: ".$Ex->__toString()."<br>";*/
            }
        }

        static public function mdlBorrarUsuario($usuario_borrar,$NIP_USR_RESPONSABLE)
        {
            /*manejo de excepciones */
            try
            {
                /*Hago uso de sentencias preparadas para evitar
                ataques de SQLInyection*/
                $query="CALL PA_BAJA_USR_SISTEMA(?,?)";
                /*petición a la base de datos*/
                $ELIMINAR_USR_SISTEMA=Permiso::acceder()->prepare($query);
                $ELIMINAR_USR_SISTEMA->bindParam(1,$usuario_borrar,PDO::PARAM_INT);
                $ELIMINAR_USR_SISTEMA->bindParam(2,$NIP_USR_RESPONSABLE,PDO::PARAM_INT);
                /*si la consulta se ejecuta correctamente vamos 
                a devolver un true para notificar al modelo y este a la vista*/
                if($ELIMINAR_USR_SISTEMA->execute())
                {
                   return true;
                }
                else{return false;}
                /*cierro la conexión*/
                $ELIMINAR_USR_SISTEMA->close();
                /*vacio el objeto recién creado*/
                $ELIMINAR_USR_SISTEMA=null; 
            }
            /*sino se logra establece conexión*/
            catch(PDOException $Ex)
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

        static public function mdlObtenerDatos()
        {
            try
            {
                /*QUERY*/
                $query = "SELECT DESCRIPCION FROM VISTA_DATOS";
                $ans=Permiso::acceder()->prepare($query);
                $ans->execute();
                return $ans->fetchAll();
                $ans->close();
                $ans=null;

            }
            catch(PDOException $ex)
            {
                return $Ex->getMessage();
            }
        }
    }

