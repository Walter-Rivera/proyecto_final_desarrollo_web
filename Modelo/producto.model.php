<?php
/*solicitando la conexión a la base de datos*/
require_once "permiso.php"; 

    class ModelProducto
    {
        /*método para mostar el producto
        recibirá como parámetros el nombre de latabla,
        el campo a verificar, y el valor que nos están enviando*/
        static public function mdlMostrarProducto($tbl,$cmp,$val)
        {
          /*como este método servirá para recuperar uno o más productos
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
                $resp=Permiso::acceder()->prepare("SELECT * FROM VISTA_CRUD_PRODUCTO");
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
                /*retorno el error al controler*/
                return $Ex->getMessage();
            }

          }
        }

        /*función para almacenar en la bd la creación de un nuevo producto*/
        static public function mdlCrearProducto($nuevaTupla)
        {
            try {
                
                /*Hago uso de sentencias preparadas para evitar
                ataques de SQLInyection*/
                $query="CALL PA_CREAR_PRODUCTO_SISTEMA(?,?,?,?,?,?,?)";
                $INSERCIONPRODUCTO=Permiso::acceder()->prepare($query);
                $INSERCIONPRODUCTO->bindParam(1,$nuevaTupla["SKU"],PDO::PARAM_INT);
                $INSERCIONPRODUCTO->bindParam(2,$nuevaTupla["DESCRIPCION"],PDO::PARAM_STR);
                $INSERCIONPRODUCTO->bindParam(3,$nuevaTupla["PRECIO_COSTO"],PDO::PARAM_STR);
                $INSERCIONPRODUCTO->bindParam(4,$nuevaTupla["TIPO_PRODUCTO"],PDO::PARAM_STR);
                $INSERCIONPRODUCTO->bindParam(5,$nuevaTupla["STOCK_MINIMO"],PDO::PARAM_INT);
                $INSERCIONPRODUCTO->bindParam(6,$nuevaTupla["STOCK_MAXIMO"],PDO::PARAM_INT);
                $INSERCIONPRODUCTO->bindParam(7,$nuevaTupla["NIP_ULT_USR_MODIFICADOR"],PDO::PARAM_INT);
   
                /*si la consulta se ejecuta correctamente vamos 
                a devolver un true para notificar al modelo y este a la vista*/
                if($INSERCIONPRODUCTO->execute())
                {
                   return true;
                }
               
                /*cierro la conexión*/
                $INSERCIONPRODUCTO->close();
                /*vacio el objeto recién creado*/
                $INSERCIONPRODUCTO=null;

            } catch (PDOException $Ex) {
                echo
                    '<script>
                            swal.fire({
                                type:"error",
                                icon: "error",
                                title: "'.$Ex->getMessage().'",
                            }).then((result)=>{
                                window.location="producto";
                            }); 
                    </script>';
            }

        }

        /*método para llamar al stored procedure de editar productos */
        static public function mdlEditarProducto($nuevaTupla)
        {
            try {
                
                /*Hago uso de sentencias preparadas para evitar
                ataques de SQLInyection*/
                $query="CALL PA_ACTUALIZAR_PRODUCTO(?,?,?,?,?,?,?)";
                $ACTUALIZACIONPRODUCTO=Permiso::acceder()->prepare($query);
                $ACTUALIZACIONPRODUCTO->bindParam(1,$nuevaTupla["SKU"],PDO::PARAM_INT);
                $ACTUALIZACIONPRODUCTO->bindParam(2,$nuevaTupla["DESCRIPCION"],PDO::PARAM_STR);
                $ACTUALIZACIONPRODUCTO->bindParam(3,$nuevaTupla["PRECIO_COSTO"],PDO::PARAM_STR);
                $ACTUALIZACIONPRODUCTO->bindParam(4,$nuevaTupla["TIPO_PRODUCTO"],PDO::PARAM_STR);
                $ACTUALIZACIONPRODUCTO->bindParam(5,$nuevaTupla["STOCK_MINIMO"],PDO::PARAM_INT);
                $ACTUALIZACIONPRODUCTO->bindParam(6,$nuevaTupla["STOCK_MAXIMO"],PDO::PARAM_INT);
                $ACTUALIZACIONPRODUCTO->bindParam(7,$nuevaTupla["NIP_ULT_USR_MODIFICADOR"],PDO::PARAM_INT);         
                /*si la consulta se ejecuta correctamente vamos 
                a devolver un true para notificar al modelo y este a la vista*/
                if($ACTUALIZACIONPRODUCTO->execute())
                {
                   return true;
                }
                /*cierro la conexión*/
                $ACTUALIZACIONPRODUCTO->close();
                /*vacio el objeto recién creado*/
                $ACTUALIZACIONPRODUCTO=null;

            } catch (PDOException $Ex) {
                echo
                    '<script>
                            swal.fire({
                                type:"error",
                                icon: "error",
                                title: "'.$Ex->getMessage().'",
                            }).then((result)=>{
                                window.location="producto";
                        }); 
                    </script>';
            }

        }

        static public function mdlActualizarEstadoProducto($skuProducto,$estado,$usr_Responsable)
        {
            /*vamos a actualizar el estado del producto */
            try
            {
                /*Hago uso de sentencias preparadas para evitar
                ataques de SQLInyection*/
                $query="CALL PA_ACTUALIZACION_ESTADO_PRODUCTO(?,?,?)";
                $ACTUALIZAESTADO=Permiso::acceder()->prepare($query);
                $ACTUALIZAESTADO->bindParam(1,$skuProducto,PDO::PARAM_INT);
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
                return $Ex->getMessage();
            }
        }


        static public function mdlBorrarProducto($producto_borrar,$NIP_USR_RESPONSABLE)
        {
            /*manejo de excepciones */
            try
            {
                /*Hago uso de sentencias preparadas para evitar
                ataques de SQLInyection*/
                $query="CALL PA_BAJA_PRODUCTO_SISTEMA(?,?)";
                /*petición a la base de datos*/
                $ELIMINAR_PRODUCTO_SISTEMA=Permiso::acceder()->prepare($query);
                $ELIMINAR_PRODUCTO_SISTEMA->bindParam(1,$producto_borrar,PDO::PARAM_INT);
                $ELIMINAR_PRODUCTO_SISTEMA->bindParam(2,$NIP_USR_RESPONSABLE,PDO::PARAM_INT);
                /*si la consulta se ejecuta correctamente vamos 
                a devolver un true para notificar al modelo y este a la vista*/
                if($ELIMINAR_PRODUCTO_SISTEMA->execute())
                {
                   return true;
                }
                /*cierro la conexión*/
                $ELIMINAR_PRODUCTO_SISTEMA->close();
                /*vacio el objeto recién creado*/
                $ELIMINAR_PRODUCTO_SISTEMA=null; 
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
                                window.location="producto";
                            }); 
                    </script>';
            }
        }

        static public function mdlverCategoria()
        {
            try
            {
                /*PETICIÓN*/
                $MOSTRAR_CATEGORIA=Permiso::acceder()->prepare("SELECT DESCRIPCION_TIPO_PRODUCTO FROM VISTA_CRUD_CATEGORIA WHERE ESTADO_TIPO_PRODUCTO='ACTIVO'");
                /*si la consulta se ejecuta correctamente vamos 
                a devolver un true para notificar al modelo y este a la vista*/
                $MOSTRAR_CATEGORIA->execute();
                return $MOSTRAR_CATEGORIA->fetchAll();
                /*cierro la conexión*/
                $MOSTRAR_CATEGORIA->close();
                /*vacio el objeto recién creado*/
                $MOSTRAR_CATEGORIA=null; 

            }
            catch(PDOException $Ex)
            {
            echo
            '<script>
                    swal.fire({
                        type:"error",
                        icon:"error",
                        title: "'.$Ex->getMessage().'"
                    }).then((result)=>{
                        window.location="producto";
                    });
            
            </script>';

            }
        }

    }
    
    