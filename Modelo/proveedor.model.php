<?php
/*solicitando la conexión a la base de datos*/
require_once "permiso.php"; 

    class ModelProveedor
    {
        /*método para mostar el proveedor
        recibirá como parámetros el nombre de latabla,
        el campo a verificar, y el valor que nos están enviando*/
        static public function mdlMostrarProveedor($tbl,$cmp,$val)
        {
          /*como este método servirá para recuperar uno o más proveedores
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
                echo "Excepción:  ".$Ex->getMessage()."<br>";
                echo "Número de Excepción: ".$Ex->getCode()."<br>";
                echo "Documento causable del fallo: ".$Ex->getCode()."<br>";
                echo "Error en la línea... : ".$Ex->getLine()."<br>";
                echo "informe general: ".$Ex->__toString()."<br>";
            }
          }
          else
          {
            /*hacemos un manejo de excepciones si en dado caso no se logra el 
            resultado esperado (la inserción de información) por diferentes circunstancias
            (nombre mal escrito de los campos de la tabla, el nombre de la tabla misma, etc)*/   
            try {
                /*solicitar respuesta de conexión y preparar consulta sql*/
                $resp=Permiso::acceder()->prepare("SELECT * FROM VISTA_CRUD_PROVEEDOR");
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
                echo "Excepción:  ".$Ex->getMessage()."<br>";
                echo "Número de Excepción: ".$Ex->getCode()."<br>";
                echo "Documento causable del fallo: ".$Ex->getCode()."<br>";
                echo "Error en la línea... : ".$Ex->getLine()."<br>";
                echo "informe general: ".$Ex->__toString()."<br>";
            }

          }
        }

        /*función para almacenar en la bd la creación de un nuevo usuario*/
        static public function mdlCrearProveedor($nuevaTupla)
        {
            try {
                
                /*Hago uso de sentencias preparadas para evitar
                ataques de SQLInyection*/
                //echo '     print "Values of bound parameters _ANTES_ CALL:\n";
                //print "  1: {$nuevaTupla["NOMBRE"]} 2: {$nuevaTupla["DIRECCION"]}\n"; 
                //print "  3: {$nuevaTupla["TELEFONO"]} 4: {$nuevaTupla["NIP_ULT_USR_MODIFICADOR"]}\n";';
           
                $query="CALL PA_CREAR_PROVEEDOR(?,?,?,?)";
                $INSERCIONPROVEEDOR=Permiso::acceder()->prepare($query);
                $INSERCIONPROVEEDOR->bindParam(1,$nuevaTupla["NOMBRE"],PDO::PARAM_STR);
                $INSERCIONPROVEEDOR->bindParam(2,$nuevaTupla["DIRECCION"],PDO::PARAM_STR);
                $INSERCIONPROVEEDOR->bindParam(3,$nuevaTupla["TELEFONO"],PDO::PARAM_STR);
                $INSERCIONPROVEEDOR->bindParam(4,$nuevaTupla["NIP_ULT_USR_MODIFICADOR"],PDO::PARAM_INT);
                
                //print "  5: {$nuevaTupla["ID_ROL_USUARIO"]} 4: {$nuevaTupla["ACCESO"]}\n";
                
                /*si la consulta se ejecuta correctamente vamos 
                a devolver un true para notificar al modelo y este a la vista*/
                if($INSERCIONPROVEEDOR->execute())
                {
                   return true;
                }
               
                /*cierro la conexión*/
                $INSERCIONPROVEEDOR->close();
                /*vacio el objeto recién creado*/
                $INSERCIONPROVEEDOR=null;

            } catch (PDOException $Ex) {

                echo
                    '<script>
                            swal.fire({
                                type:"error",
                                icon: "error",
                                title: "error: "+"'.$Ex->getMessage().'",
                            }).then((result)=>{
                                window.location="proveedor";
                            });
                    </script>';

               
                    echo "Ocurrió un error en la SP. Código de error: {$INSERCIONPROVEEDOR->errorInfo()[1]}";
                    echo "Número de Excepción: ".$Ex->getCode()."\n";
                    echo "Documento causable del fallo: ".$Ex->getCode()."\n";
                    echo "Error en la línea... : ".$Ex->getLine()."\n";
                    echo "informe general: ".$Ex->__toString()."\n";
            }

        }

        /*método para llamar al stored procedure de editar Proveedores */
        static public function mdlEditarProveedor($nuevaTupla)
        {
            try {
                
                /*Hago uso de sentencias preparadas para evitar
                ataques de SQLInyection*/
                $query="CALL PA_ACTUALIZAR_PROVEEDOR(?,?,?,?,?)";
                $ACTUALIZACIONUSUARIO=Permiso::acceder()->prepare($query);
                $ACTUALIZACIONUSUARIO->bindParam(1,$nuevaTupla["ID_PROVEEDOR"],PDO::PARAM_INT);
                $ACTUALIZACIONUSUARIO->bindParam(2,$nuevaTupla["NOMBRE"],PDO::PARAM_STR);
                $ACTUALIZACIONUSUARIO->bindParam(3,$nuevaTupla["DIRECCION"],PDO::PARAM_STR);
                $ACTUALIZACIONUSUARIO->bindParam(4,$nuevaTupla["TELEFONO"],PDO::PARAM_STR);
                $ACTUALIZACIONUSUARIO->bindParam(5,$nuevaTupla["NIP_ULT_USR_MODIFICADOR"],PDO::PARAM_INT);
               /* print "Values of bound parameters _before_ CALL:\n";
                print "  1: {$nuevaTupla["NIP"]} 2: {$nuevaTupla["NOMBRES"]}\n"; 
                print "  3: {$nuevaTupla["APELLIDOS"]} 4: {$nuevaTupla["CORREO_INSTITUCIONAL"]}\n";
                print "  5: {$nuevaTupla["ID_ROL_USUARIO"]} 4: {$nuevaTupla["ACCESO"]}\n";
                
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
                                title: "error al actualizar el usuario, verifique los datos",
                            }).then((result)=>{
                                window.location="proveedor";
                        });
                    </script>';
                echo "Excepción:  ".$Ex->getMessage();
                /*echo "Ocurrió un error en la SP. Código de error: {$INSERCIONPROVEEDOR->errorInfo()[1]}";
                echo "Número de Excepción: ".$Ex->getCode()."<br>";
                echo "Documento causable del fallo: ".$Ex->getCode()."<br>";
                echo "Error en la línea... : ".$Ex->getLine()."<br>";
                echo "informe general: ".$Ex->__toString()."<br>";*/
            }

        }

        static public function mdlActualizarEstadoProveedor($numeroProveedor,$estado,$usr_Responsable)
        {
            /*vamos a actualizar el estado del proveedor */
            try
            {
                /*Hago uso de sentencias preparadas para evitar
                ataques de SQLInyection*/
                $query="CALL PA_ACTUALIZACION_ESTADO_PROVEEDOR(?,?,?)";
                $ACTUALIZAESTADO=Permiso::acceder()->prepare($query);
                $ACTUALIZAESTADO->bindParam(1,$numeroProveedor,PDO::PARAM_INT);
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
                                title: "error al actualizar el Estado del PROVEEDOR",
                            }).then((result)=>{
                                window.location="proveedor";
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


        static public function mdlBorrarProveedor($usuario_borrar,$NIP_USR_RESPONSABLE)
        {
            /*manejo de excepciones */
            try
            {
                /*Hago uso de sentencias preparadas para evitar
                ataques de SQLInyection*/
                $query="CALL PA_BAJA_PROVEEDOR_SISTEMA(?,?)";
                /*petición a la base de datos*/
                $ELIMINAR_PROVEEDOR_SISTEMA=Permiso::acceder()->prepare($query);
                $ELIMINAR_PROVEEDOR_SISTEMA->bindParam(1,$usuario_borrar,PDO::PARAM_INT);
                $ELIMINAR_PROVEEDOR_SISTEMA->bindParam(2,$NIP_USR_RESPONSABLE,PDO::PARAM_INT);
                /*si la consulta se ejecuta correctamente vamos 
                a devolver un true para notificar al modelo y este a la vista*/
                if($ELIMINAR_PROVEEDOR_SISTEMA->execute())
                {
                   return true;
                }
                else{return false;}
                /*cierro la conexión*/
                $ELIMINAR_PROVEEDOR_SISTEMA->close();
                /*vacio el objeto recién creado*/
                $ELIMINAR_PROVEEDOR_SISTEMA=null; 
            }
            /*sino se logra establece conexión*/
            catch(PDOException $Ex)
            {
                echo
                '<script>
                        swal.fire({
                            type:"error",
                            icon: "error",
                            title: "error al trataar de eliminar al proveedor",
                        }).then((result)=>{
                            window.location="proveedor";
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

    }