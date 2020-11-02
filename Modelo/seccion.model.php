<?php
/*solicitando la conexión a la base de datos*/
require_once "permiso.php"; 

    class ModelSeccion
    {
        /*método para mostar el seccion
        recibirá como parámetros el nombre de latabla,
        el campo a verificar, y el valor que nos están enviando*/
        static public function mdlMostrarSeccion($tbl,$cmp,$val)
        {
          /*como este método servirá para recuperar uno o más seccions
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
                $resp=Permiso::acceder()->prepare("SELECT * FROM VISTA_CRUD_SECCION");
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

        /*función para almacenar en la bd la creación de un nuevo seccion*/
        static public function mdlCrearSeccion($nuevaTupla)
        {
            try {
                
                /*Hago uso de sentencias preparadas para evitar
                ataques de SQLInyection*/
                $query="CALL PA_CREAR_SECCION_SISTEMA(?,?,?,?)";
                $INSERCIONSECCION=Permiso::acceder()->prepare($query);
                $INSERCIONSECCION->bindParam(1,$nuevaTupla["NOMBRES"],PDO::PARAM_STR);
                $INSERCIONSECCION->bindParam(2,$nuevaTupla["IDENTIFICADOR"],PDO::PARAM_STR);
                $INSERCIONSECCION->bindParam(3,$nuevaTupla["NIP_ULT_USR_MODIFICADOR"],PDO::PARAM_INT);
                $INSERCIONSECCION->bindParam(4,$nuevaTupla["ROL"],PDO::PARAM_STR);
                
                /*si la consulta se ejecuta correctamente vamos 
                a devolver un true para notificar al modelo y este a la vista*/
                if($INSERCIONSECCION->execute())
                {
                   return true;
                }
               
                /*cierro la conexión*/
                $INSERCIONSECCION->close();
                /*vacio el objeto recién creado*/
                $INSERCIONSECCION=null;

            } catch (PDOException $Ex) {
                echo
                    '<script>
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

        /*método para llamar al stored procedure de editar seccions */
        static public function mdlEditarSeccion($nuevaTupla)
        {
            try {
                
                /*Hago uso de sentencias preparadas para evitar
                ataques de SQLInyection*/
                $query="CALL PA_ACTUALIZAR_SECCION_SISTEMA(?,?,?,?,?)";
                $ACTUALIZACIONSECCION=Permiso::acceder()->prepare($query);
                $ACTUALIZACIONSECCION->bindParam(1,$nuevaTupla["ID"],PDO::PARAM_INT);
                $ACTUALIZACIONSECCION->bindParam(2,$nuevaTupla["NOMBRES"],PDO::PARAM_STR);
                $ACTUALIZACIONSECCION->bindParam(3,$nuevaTupla["IDENTIFICADOR"],PDO::PARAM_STR);
                $ACTUALIZACIONSECCION->bindParam(4,$nuevaTupla["NIP_ULT_USR_MODIFICADOR"],PDO::PARAM_INT);
                $ACTUALIZACIONSECCION->bindParam(5,$nuevaTupla["ROL"],PDO::PARAM_STR);

                /*si la consulta se ejecuta correctamente vamos 
                a devolver un true para notificar al modelo y este a la vista*/
                if($ACTUALIZACIONSECCION->execute())
                {
                   return true;
                }
                /*cierro la conexión*/
                $ACTUALIZACIONSECCION->close();
                /*vacio el objeto recién creado*/
                $ACTUALIZACIONSECCION=null;

            } catch (PDOException $Ex) {
                echo
                    '<script>
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

        static public function mdlBorrarSeccion($seccion_borrar,$NIP_USR_RESPONSABLE)
        {
            /*manejo de excepciones */
            try
            {
                /*Hago uso de sentencias preparadas para evitar
                ataques de SQLInyection*/
                $query="CALL PA_BAJA_SEC_SISTEMA(?,?)";
                /*petición a la base de datos*/
                $ELIMINAR_SEC_SISTEMA=Permiso::acceder()->prepare($query);
                $ELIMINAR_SEC_SISTEMA->bindParam(1,$seccion_borrar,PDO::PARAM_INT);
                $ELIMINAR_SEC_SISTEMA->bindParam(2,$NIP_USR_RESPONSABLE,PDO::PARAM_INT);
                /*si la consulta se ejecuta correctamente vamos 
                a devolver un true para notificar al modelo y este a la vista*/
                if($ELIMINAR_SEC_SISTEMA->execute())
                {
                   return true;
                }
                else{return false;}
                /*cierro la conexión*/
                $ELIMINAR_SEC_SISTEMA->close();
                /*vacio el objeto recién creado*/
                $ELIMINAR_SEC_SISTEMA=null; 
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
                            window.location="seccion";
                         }); 
                </script>';
            }
        }
    }

