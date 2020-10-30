<?php
/*solicitando la conexión a la base de datos*/
require_once "permiso.php"; 

    class ModelCategoria
    {
        /*método para mostar la categoria
        recibirá como parámetros el nombre de la tabla (vista),
        el campo a verificar, y el valor que nos están enviando*/
        static public function mdlMostrarCategoria($tbl,$cmp,$val)
        {
          /*como este método servirá para recuperar una o más categorias
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
            resultado esperado (búsqueda de los registros) */  
            try {
                /*solicitar respuesta de conexión y preparar consulta sql*/
                $resp=Permiso::acceder()->prepare("SELECT * FROM VISTA_CRUD_CATEGORIA");
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
        static public function mdlCrearCategoria($nuevaTupla)
        {
            try {
                /*Hago uso de sentencias preparadas para evitar
                ataques de SQLInyection*/
                $query="CALL PA_CREACION_CATEGORIA(?,?)";
                $INSERCIONCATEGORIA=Permiso::acceder()->prepare($query);
                $INSERCIONCATEGORIA->bindParam(1,$nuevaTupla["NOMBRE"],PDO::PARAM_STR);
                $INSERCIONCATEGORIA->bindParam(2,$nuevaTupla["NIP_ULT_USR_MODIFICADOR"],PDO::PARAM_INT);
                /*si la consulta se ejecuta correctamente vamos 
                a devolver un true para notificar al modelo y este a la vista*/
                if($INSERCIONCATEGORIA->execute())
                {
                   return true;
                }
                /*cierro la conexión*/
                $INSERCIONCATEGORIA->close();
                /*vacio el objeto recién creado*/
                $INSERCIONCATEGORIA=null;

            } catch (PDOException $Ex) {

                echo
                    '<script>
                            swal.fire({
                                type:"error",
                                icon: "error",
                                title: "error al crear categoria, comuníquese con el DBA",
                            }).then((result)=>{
                                window.location="categoria";
                            });
                    </script>';
                     /*       
                    echo "Ocurrió un error en la SP. Código de error: {$INSERCIONCATEGORIA->errorInfo()[1]}";
                    echo "Número de Excepción: ".$Ex->getCode()."\n";
                    echo "Documento causable del fallo: ".$Ex->getCode()."\n";
                    echo "Error en la línea... : ".$Ex->getLine()."\n";
                    echo "informe general: ".$Ex->__toString()."\n";*/
            }

        }

        /*método para llamar al stored procedure de editar Proveedores */
        static public function mdlEditarCategoria($nuevaTupla)
        {
            try {
                
                /*Hago uso de sentencias preparadas para evitar
                ataques de SQLInyection*/
                $query="CALL PA_ACTUALIZAR_CATEGORIA(?,?,?)";
                $ACTUALIZACIONCATEGORIA=Permiso::acceder()->prepare($query);
                $ACTUALIZACIONCATEGORIA->bindParam(1,$nuevaTupla["ID_ESTADO_TIPO_PRODUCTO"],PDO::PARAM_INT);
                $ACTUALIZACIONCATEGORIA->bindParam(2,$nuevaTupla["NOMBRE"],PDO::PARAM_STR);
                $ACTUALIZACIONCATEGORIA->bindParam(3,$nuevaTupla["NIP_ULT_USR_MODIFICADOR"],PDO::PARAM_INT);
               /* print "Values of bound parameters _before_ CALL:\n";
                print "  1: {$nuevaTupla["NIP"]} 2: {$nuevaTupla["NOMBRES"]}\n"; 
                print "  3: {$nuevaTupla["APELLIDOS"]} 4: {$nuevaTupla["CORREO_INSTITUCIONAL"]}\n";
                print "  5: {$nuevaTupla["ID_ROL_USUARIO"]} 4: {$nuevaTupla["ACCESO"]}\n";
                /*si la consulta se ejecuta correctamente vamos 
                a devolver un true para notificar al modelo y este a la vista*/
                if($ACTUALIZACIONCATEGORIA->execute())
                {
                   return true;
                }
                /*cierro la conexión*/
                $ACTUALIZACIONCATEGORIA->close();
                /*vacio el objeto recién creado*/
                $ACTUALIZACIONCATEGORIA=null;

            } catch (PDOException $Ex) {
              
                echo
                    '<script>
                            swal.fire({
                                type:"error",
                                icon: "error",
                                title: "error al actualizar la categoria de prouductos, conuníquese con el desarrollador/dba",
                            }).then((result)=>{
                                window.location="categoria";
                        });
                    </script>';
                echo "Excepción:  ".$Ex->getMessage();
                /*echo "Ocurrió un error en la SP. Código de error: {$INSERCIONCATEGORIA->errorInfo()[1]}";
                echo "Número de Excepción: ".$Ex->getCode()."<br>";
                echo "Documento causable del fallo: ".$Ex->getCode()."<br>";
                echo "Error en la línea... : ".$Ex->getLine()."<br>";
                echo "informe general: ".$Ex->__toString()."<br>";*/
            }

        }

        static public function mdlActualizarEstadoCategoria($numeroCategoria,$estado,$usr_Responsable)
        {
            /*vamos a actualizar el estado del proveedor */
            try
            {
                /*Hago uso de sentencias preparadas para evitar
                ataques de SQLInyection*/
                $query="CALL PA_ACTUALIZACION_ESTADO_CATEGORIA(?,?,?)";
                $ACTUALIZAESTADO=Permiso::acceder()->prepare($query);
                $ACTUALIZAESTADO->bindParam(1,$numeroCategoria,PDO::PARAM_INT);
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
                                title: "error al actualizar el Estado del PROVEEDOR, contacte al desarrollador / dba",
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


        static public function mdlBorrarCategoria($id_categoria_borrar,$NIP_USR_RESPONSABLE)
        {
            /*manejo de excepciones */
            try
            {
                /*Hago uso de sentencias preparadas para evitar
                ataques de SQLInyection*/
                $query="CALL PA_BAJA_CATEGORIA_SISTEMA(?,?)";
                /*petición a la base de datos*/
                $ELIMINAR_CATEGORIA_SISTEMA=Permiso::acceder()->prepare($query);
                $ELIMINAR_CATEGORIA_SISTEMA->bindParam(1,$id_categoria_borrar,PDO::PARAM_INT);
                $ELIMINAR_CATEGORIA_SISTEMA->bindParam(2,$NIP_USR_RESPONSABLE,PDO::PARAM_INT);
                /*si la consulta se ejecuta correctamente vamos 
                a devolver un true para notificar al modelo y este a la vista*/
                if($ELIMINAR_CATEGORIA_SISTEMA->execute())
                {
                   return true;
                }
                else{return false;}
                /*cierro la conexión*/
                $ELIMINAR_CATEGORIA_SISTEMA->close();
                /*vacio el objeto recién creado*/
                $ELIMINAR_CATEGORIA_SISTEMA=null; 
            }
            /*sino se logra establece conexión*/
            catch(PDOException $Ex)
            {
                echo
                '<script>
                        swal.fire({
                            type:"error",
                            icon: "error",
                            title: "error al trataar de eliminar la categoria, contacte al dba o desarrollador",
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