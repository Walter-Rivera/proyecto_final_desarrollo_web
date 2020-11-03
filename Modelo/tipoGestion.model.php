<?php
/*solicitando la conexión a la base de datos*/
require_once "permiso.php"; 

    class ModelTipoGestion
    {
        /*método para mostar el tipoGestion
        recibirá como parámetros el nombre de latabla,
        el campo a verificar, y el valor que nos están enviando*/
        static public function mdlMostrarTipoGestion($tbl,$cmp,$val)
        {
          /*como este método servirá para recuperar uno o más tipoGestions
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
                $resp=Permiso::acceder()->prepare("SELECT * FROM VISTA_CRUD_TIPO_GESTION");
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

        /*función para almacenar en la bd la creación de un nuevo tipoGestion*/
        static public function mdlCrearTipoGestion($nuevaTupla)
        {
            try {
                
                /*Hago uso de sentencias preparadas para evitar
                ataques de SQLInyection*/
                $query="CALL PA_CREAR_TIPOGESTION_SISTEMA(?,?,?,?)";
                $INSERCIONTIPOGESTION=Permiso::acceder()->prepare($query);
                $INSERCIONTIPOGESTION->bindParam(1,$nuevaTupla["NOMBRES"],PDO::PARAM_STR);
                $INSERCIONTIPOGESTION->bindParam(2,$nuevaTupla["CLASE_GESTION"],PDO::PARAM_STR);
                $INSERCIONTIPOGESTION->bindParam(3,$nuevaTupla["NIP_ULT_USR_MODIFICADOR"],PDO::PARAM_INT);
                $INSERCIONTIPOGESTION->bindParam(4,$nuevaTupla["ROL"],PDO::PARAM_STR);
                
                /*si la consulta se ejecuta correctamente vamos 
                a devolver un true para notificar al modelo y este a la vista*/
                if($INSERCIONTIPOGESTION->execute())
                {
                   return true;
                }
               
                /*cierro la conexión*/
                $INSERCIONTIPOGESTION->close();
                /*vacio el objeto recién creado*/
                $INSERCIONTIPOGESTION=null;

            } catch (PDOException $Ex) {
                echo
                    '<script>
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

        /*método para llamar al stored procedure de editar tipoGestions */
        static public function mdlEditarTipoGestion($nuevaTupla)
        {
            try {
                
                /*Hago uso de sentencias preparadas para evitar
                ataques de SQLInyection*/
                $query="CALL PA_ACTUALIZAR_TIPOGESTION_SISTEMA(?,?,?,?,?)";
                $ACTUALIZACIONTIPOGESTION=Permiso::acceder()->prepare($query);
                $ACTUALIZACIONTIPOGESTION->bindParam(1,$nuevaTupla["ID"],PDO::PARAM_INT);
                $ACTUALIZACIONTIPOGESTION->bindParam(2,$nuevaTupla["NOMBRES"],PDO::PARAM_STR);
                $ACTUALIZACIONTIPOGESTION->bindParam(3,$nuevaTupla["CLASE_GESTION"],PDO::PARAM_STR);
                $ACTUALIZACIONTIPOGESTION->bindParam(4,$nuevaTupla["NIP_ULT_USR_MODIFICADOR"],PDO::PARAM_INT);
                $ACTUALIZACIONTIPOGESTION->bindParam(5,$nuevaTupla["ROL"],PDO::PARAM_STR);

                /*si la consulta se ejecuta correctamente vamos 
                a devolver un true para notificar al modelo y este a la vista*/
                if($ACTUALIZACIONTIPOGESTION->execute())
                {
                   return true;
                }
                /*cierro la conexión*/
                $ACTUALIZACIONTIPOGESTION->close();
                /*vacio el objeto recién creado*/
                $ACTUALIZACIONTIPOGESTION=null;

            } catch (PDOException $Ex) {
                echo
                    '<script>
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

        static public function mdlBorrarTipoGestion($tipoGestion_borrar,$NIP_USR_RESPONSABLE)
        {
            /*manejo de excepciones */
            try
            {
                /*Hago uso de sentencias preparadas para evitar
                ataques de SQLInyection*/
                $query="CALL PA_BAJA_TIPOGESTION_SISTEMA(?,?)";
                /*petición a la base de datos*/
                $ELIMINAR_TG_SISTEMA=Permiso::acceder()->prepare($query);
                $ELIMINAR_TG_SISTEMA->bindParam(1,$tipoGestion_borrar,PDO::PARAM_INT);
                $ELIMINAR_TG_SISTEMA->bindParam(2,$NIP_USR_RESPONSABLE,PDO::PARAM_INT);
                /*si la consulta se ejecuta correctamente vamos 
                a devolver un true para notificar al modelo y este a la vista*/
                if($ELIMINAR_TG_SISTEMA->execute())
                {
                   return true;
                }
                else{return false;}
                /*cierro la conexión*/
                $ELIMINAR_TG_SISTEMA->close();
                /*vacio el objeto recién creado*/
                $ELIMINAR_TG_SISTEMA=null; 
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
                            window.location="tipoGestion";
                         }); 
                </script>';
            }
        }

        static public function mdlmostrarClase()
        {
            try
            {
                /*solicitar respuesta de conexión y preparar consulta sql*/
                 $resp=Permiso::acceder()->prepare("SELECT NOMBRE FROM CLASE_GESTION");
                 /*ejecutamos el objeto*/
                 $resp->execute();
                 /*retornamos todas las tuplas al controler*/
                 return $resp->fetchAll();
                 /*cerrando la conexión a la bd */
                 $resp->close();
                 /*apuntamos a null el objeto para que no alcene los datos de conexión anteriormente
                 ejecutados */
                 $resp=null;

            }
            catch(PDOException $ex)
            {
                return $ex->getMessage();

            }
        }
    }

