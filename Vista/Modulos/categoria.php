<?php
/*validando acceso a las opciones si no tiene
permisos el usuario, lo redirecciona a la página de inicio*/
  if(($_SESSION["DESCRIPCION"]=="JEFATURA"))
  {
    echo '<script> window.location="ini"</script>';
    return;
  }

?>
<!-- 
    Código tomado de adminLTE
    Content Wrapper. Contains page content -->
    <div class="content-wrapper">
    <!-- Content Header (Page header) Será la base para 
    crear los módulos que se formarán-->
    <section class="content-header">
      <h1>
        <b>Administración Categorías de Productos</b> 
        <small>Almacén TFM</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="ini"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li class="active">Administración de Categorías de Productos</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <!--Colocando opción para agregar Usuario al Sistema, básicamente
              al darle click, nos creará una ventana modal para un form y allí colocar los elementos
            para crear un nuevo usuario-->
          <button color="red" class="btn btn-primary" data-toggle="modal" data-target="#opcAgregarCategoria">
            Crear Categoria
          </button>

        </div>
        <!--mostrar los usuarios registrados, voy a mostrarlos usando una tabla la cual
          se incrustará en un div-->
        <div class="box-body">
            <!--las clases para esta tabla se están tomando de bootstrap; a excepción 
            de la clase Tabla, la cual usaremos en js para activar el plugin datatable en esta
            seccion-->
          <table class="table table-bordered table-striped table-dark table-responsive-xl Tabla">
            <!--cabecera de la tabla-->
            <thead class="thead-dark" align="center">
              <tr>
              <th>No.</th>
                <th>NOMBRE_TIPO_PRODUCTO</th>
                <th>ESTADO_TIPO_PRODUCTO</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <!--creando  el cuerpo de la tabla-->
            <tbody align="center">
           
              <?php
                /*enviamos estos parámetros nulos a fin de reutilizar un método en el modelo,
                el cual nos muestra los usurios, por ello luego se condicionará que acciones debe 
                tomar cuando lleguen estos dos parámetros en null */
                $campo=null;
                $valor=null;
             

                /*recuperando el listado de usuarios, vamos a hacer la petición
                al controlador para que este la haga al modelo*/
                $res=ContrlCategoria::controlerMostrarCategoria($campo,$valor);
                //var_dump($res);
                /*con un for each recorremos el contenido del array que nos devuelve el controler */
                foreach ($res as $key => $value) {
                  
                  /*metemos los resultados al body de la tabla */
                  echo'
                        <tr>
                        <td>'.$value["ID_TIPO_PRODUCTO"].'</td>
                        <td>'.$value["DESCRIPCION_TIPO_PRODUCTO"].'</td>';

                  if($value["ESTADO_TIPO_PRODUCTO"]=="ACTIVO")
                  {
                    echo'<td> <button  class="btn btn-success btn-xs botonActivoCategoria" ID_TPC="'.$value["ID_TIPO_PRODUCTO"].'" RESPON="'.$_SESSION["NIP"].'" estadoTP="1">Activo</button> </td>';
                  }
                  else
                  {
                    echo'<td> <button  class="btn btn-danger btn-xs botonActivoCategoria" ID_TPC="'.$value["ID_TIPO_PRODUCTO"].'" RESPON="'.$_SESSION["NIP"].'" estadoTP="2">Inactivo</button> </td>';
                  }
                  echo'
                          <td>
                          <div class="btn-group">
                            <button class="btn btn-warning botonEditarCategoria" ID_TIPO_PRODUCTO_MODIFICAR="'.$value["ID_TIPO_PRODUCTO"].'" data-toggle="modal" data-target="#opcActualizarCategoria"><i class="fa fa-pencil"></i></button>
                            <button class="btn btn-danger botonEliminarCategoria" IdBorrarCategoria="'.$value["ID_TIPO_PRODUCTO"].'" RESPON="'.$_SESSION["NIP"].'"><i class="fa fa-exclamation-circle"></i></button>
                            </div>
                        </td>
                      </tr>
                  ';
                }
              ?>
            </tbody>
          </table>

        </div>
        <!-- /.box-body -->
      </div>
      <!-- /.box -->

    </section>
    <!-- /.content -->
  </div>




  <!--creando una ventana modal para llenar
el formulario para creación de un nuevo Proveedor-->

  <!-- Modal tomado de boostrap 4.5 -->
  <div class="modal fade" id="opcAgregarCategoria" tabindex="-1" 
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <!--creando el formulario para capturar los datos de la creación en el modal-->
        <form role="form" method="POST">
          <div class="modal-header" style="background:#001F3F; color:white">
            <h5 class="modal-title" id="exampleModalLabel">Registrar nueva Categoria</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <!--creando caja para el contenidod del body-->
            <div class="box-body">
              <!--creando formulario (clases de bootstrap específicas para realizar formularios)
              para recolectar el o los nombres del nuevo usuario-->
              <div class="form-group">
                <!--este input group lo que permite es agrupar 
                un ícono con los datos que ingrese el usuario-->
                  <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-list"></i></span>
                    <input class="form-control input-lg" type="text" name="nombreNuevo" placeholder="Nombre de la Categoria" required>
                  </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary">Crear</button>
          </div>
          <?php
            /*código php para guardar la creación del Proveedor*/
              $almacenarProveedor=new ContrlCategoria();
              /*método para guardar los usuarios en el sistema*/
              $almacenarProveedor->controlerCrearCategoria();
          ?>
        </form>
      </div>
    </div>
  </div>
</div>





   <!--creando una ventana modal para actualizar un Proveedor-->
  <!-- Modal tomado de boostrap 4.5 -->
  <div class="modal fade" id="opcActualizarCategoria" tabindex="-1" 
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <!--creando el formulario para capturar los datos de la creación en el modal-->
        <form role="form" method="POST">
          <div class="modal-header" style="background:#001F3F; color:white">
            <h5 class="modal-title" id="exampleModalLabel">Editar Categoria de Producto</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <!--creando caja para el contenidod del body-->
            <div class="box-body">
              <!--div para insertar el NIP del usuario-->
              <div class="form-group">

              
              
               <!--para recolectar el id del proveedor-->
               <div class="form-group">
                <!--este input group lo que permite es agrupar 
                un ícono con los datos que ingrese el usuario-->
                <div class="input-group">
                  <span class="input-group-addon"><i class="fa fa-hourglass"></i></span>
                  <input class="form-control input-lg" type="text" id="idEditar" name="idEditar" placeholder="" required readonly>
                </div>
              </div>
              
              
              
              
              
              <!--creando formulario (clases de bootstrap específicas para realizar formularios)
              para recolectar el o los nombres del nuevo usuario-->
              <div class="form-group">
                <!--este input group lo que permite es agrupar 
                un ícono con los datos que ingrese el usuario-->
                <div class="input-group">
                  <span class="input-group-addon"><i class="fa fa-list"></i></span>
                  <input class="form-control input-lg" type="text" id="nombreEditar" name="nombreEditar" placeholder="ingrese Nombre(s)" required>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary">Guardar</button>
          </div>
          <?php
            /*código php para guardar la creación del Proveedor*/
              $almacenarProveedor=new ContrlCategoria();
              /*método para guardar los categorías de productos en el sistema*/
              $almacenarProveedor->controlerEditarCategoria();
          ?>
        </form>
      </div>
    </div>
   </div>

   </div>


   <?php
 /*llamando al controlador para eliminar = (dar de baja en el  sistema a la categoria)*/
 $borrarUsuario= new ContrlCategoria();
/*método que realiza la acción*/
 $borrarUsuario->controlerBorrarCategoria();
 
 ?>


































 