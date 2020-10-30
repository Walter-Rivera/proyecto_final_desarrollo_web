<?php
/*solicitando la conexión a la base de datos*/
require_once "permiso.php"; 

    class ModelCliente
    {
        /*método para mostar el cliente
        recibirá como parámetros el nombre de la vista,
        el campo a verificar, y el valor que nos están enviando*/
        static public function MdlMostrarCliente($tbl,$cmp,$val)
        {
          /*como este método servirá para recuperar uno o más clientes
          vamos a condicionar esa situación, si $cmp es diferente de nulo, porque 
          solo vamos a comparar una fila con un valor */
          if($cmp!=null)
          {
            /*hacemos un manejo de excepciones si en dado caso no se logra el 
            resultado esperado (la vista de información) por diferentes circunstancias
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
            resultado esperado (la SELECCION de información) por diferentes circunstancias
            (nombre mal escrito de los campos de la tabla, el nombre de la tabla misma, etc)*/   
            try {
                /*solicitar respuesta de conexión y preparar consulta sql*/
                $resp=Permiso::acceder()->prepare("SELECT * FROM VISTA_CRUD_CLIENTE");
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

                return $Ex->getMessage();
            }

          }
        }

        /*función para almacenar en la bd la creación de un nuevo cliente*/
        static public function MdlCrearCliente($nuevaTupla)
        {
            try {
                
                /*Hago uso de sentencias preparadas para evitar
                ataques de SQLInyection*/
                $query="CALL PA_CREAR_CLIENTE_SISTEMA(?,?,?,?,?,?)";
                $INSERCIONCLIENTE=Permiso::acceder()->prepare($query);
                $INSERCIONCLIENTE->bindParam(1,$nuevaTupla["NIP"],PDO::PARAM_INT);
                $INSERCIONCLIENTE->bindParam(2,$nuevaTupla["NOMBRES"],PDO::PARAM_STR);
                $INSERCIONCLIENTE->bindParam(3,$nuevaTupla["APELLIDOS"],PDO::PARAM_STR);
                $INSERCIONCLIENTE->bindParam(4,$nuevaTupla["CORREO_INSTITUCIONAL"],PDO::PARAM_STR);
                $INSERCIONCLIENTE->bindParam(5,$nuevaTupla["SECCION"],PDO::PARAM_STR);
                $INSERCIONCLIENTE->bindParam(6,$nuevaTupla["NIP_ULT_USR_MODIFICADOR"],PDO::PARAM_INT);
                
                /*si la consulta se ejecuta correctamente vamos 
                a devolver un true para notificar al modelo y este a la vista*/
                if($INSERCIONCLIENTE->execute())
                {
                   return true;
                }
               
                /*cierro la conexión*/
                $INSERCIONCLIENTE->close();
                /*vacio el objeto recién creado*/
                $INSERCIONCLIENTE=null;

            } catch (PDOException $Ex) {
                echo
                    '<script>
                            swal.fire({
                                type:"error",
                                icon: "error",
                                title: "'.$Ex->getMessage(). '",
                            }).then((result)=>{
                                window.location="cliente";
                            }); 
                    </script>';
            }

        }

        /*método para llamar al stored procedure de editar clientes */
        static public function mdlEditarCliente($nuevaTupla)
        {
            try {
                
                /*Hago uso de sentencias preparadas para evitar
                ataques de SQLInyection*/
                $query="CALL PA_ACTUALIZAR_CLIENTE_SISTEMA(?,?,?,?,?,?)";
                $ACTUALIZACIONCLIENTE=Permiso::acceder()->prepare($query);
                $ACTUALIZACIONCLIENTE->bindParam(1,$nuevaTupla["NIP"],PDO::PARAM_INT);
                $ACTUALIZACIONCLIENTE->bindParam(2,$nuevaTupla["NOMBRES"],PDO::PARAM_STR);
                $ACTUALIZACIONCLIENTE->bindParam(3,$nuevaTupla["APELLIDOS"],PDO::PARAM_STR);
                $ACTUALIZACIONCLIENTE->bindParam(4,$nuevaTupla["CORREO_INSTITUCIONAL"],PDO::PARAM_STR);
                $ACTUALIZACIONCLIENTE->bindParam(5,$nuevaTupla["SECCION"],PDO::PARAM_STR);
                $ACTUALIZACIONCLIENTE->bindParam(6,$nuevaTupla["NIP_ULT_USR_MODIFICADOR"],PDO::PARAM_INT);
                /*si la consulta se ejecuta correctamente vamos 
                a devolver un true para notificar al modelo y este a la vista*/
                if($ACTUALIZACIONCLIENTE->execute())
                {
                   return true;
                }
                /*cierro la conexión*/
                $ACTUALIZACIONCLIENTE->close();
                /*vacio el objeto recién creado*/
                $ACTUALIZACIONCLIENTE=null;

            } catch (PDOException $Ex) {
                echo
                    '<script>
                            swal.fire({
                                type:"error",
                                icon: "error",
                                title: "'.$Ex->getMessage(). '",
                            }).then((result)=>{
                                window.location="cliente";
                            }); 
                    </script>';
            }

        }

        static public function MdlActualizarEstadoCliente($numeroUsuario,$estado,$usr_Responsable)
        {
            /*vamos a actualizar el estado del cliente */
            try
            {
                /*Hago uso de sentencias preparadas para evitar
                ataques de SQLInyection*/
                $query="CALL PA_ACTUALIZACION_ESTADO_CLI(?,?,?)";
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
                                window.location="cliente";
                            }); 
                    </script>';
            }
        }

        static public function mdlBorrarCliente($usuario_borrar,$NIP_USR_RESPONSABLE)
        {
            /*manejo de excepciones */
            try
            {
                /*Hago uso de sentencias preparadas para evitar
                ataques de SQLInyection*/
                $query="CALL PA_BAJA_CLI_SISTEMA(?,?)";
                /*petición a la base de datos*/
                $ELIMINAR_CLI_SISTEMA=Permiso::acceder()->prepare($query);
                $ELIMINAR_CLI_SISTEMA->bindParam(1,$usuario_borrar,PDO::PARAM_INT);
                $ELIMINAR_CLI_SISTEMA->bindParam(2,$NIP_USR_RESPONSABLE,PDO::PARAM_INT);
                /*si la consulta se ejecuta correctamente vamos 
                a devolver un true para notificar al modelo y este a la vista*/
                if($ELIMINAR_CLI_SISTEMA->execute())
                {
                   return true;
                }
                else{return false;}
                /*cierro la conexión*/
                $ELIMINAR_CLI_SISTEMA->close();
                /*vacio el objeto recién creado*/
                $ELIMINAR_CLI_SISTEMA=null; 
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
                                window.location="cliente";
                            }); 
                    </script>';
            }
        }


        static public function mdlMostrarSeccion()
        {
            try
            {
                $query="SELECT * FROM VISTA_SECCIONES";
                /*petición*/
                $Respuesta=Permiso::acceder()->prepare($query);
                /*ejecuto el query*/
                $Respuesta->execute();
                /*retorno el resultado*/
                return $Respuesta->fetchAll();
                /*cierro conexión*/
                $Respuesta->close();
                /*vacío el objeto recién utilizado */
                $Respuesta=null;
            }
            catch(PDOException $Ex)
            {
                return $Ex->getMessage();
            }
        }
    }