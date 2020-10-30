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
        <b>Administración de Productos</b> 
        <small>Almacén TFM</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="ini"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li class="active">Administración de Productos</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <!--Colocando opción para agregar Usuario al Sistema, básicamente
              al darle click, nos creará una ventana modal para un form y allí colocar los elementos
            para crear un nuevo producto-->
          <button color="red" class="btn btn-primary" data-toggle="modal" data-target="#opcAgregarProducto">
            Crear Producto
          </button>

        </div>
        <!--mostrar los productos registrados, voy a mostrarlos usando una tabla la cual
          se incrustará en un div-->
        <div class="box-body">
            <!--las clases para esta tabla se están tomando de bootstrap; a excepción 
            de la clase Tabla, la cual usaremos en js para activar el plugin datatable en esta
            seccion-->
          <table class="table table-bordered table-striped table-dark table-responsive-xl VtProductos" style="width:100%">
            <!--cabecera de la tabla-->
            <thead class="thead-dark" align="center">
              <!----><tr>
                <th>SKU</th>
                <th>DESCRIPCION</th>
                <th>PRECIO_COSTO</th>
                <th>PRIORIDAD</th>
                <th>TIPO_PRODUCTO</th>
                <th>ESTADO_PRODUCTO</th>
                <th>STOCK_MINIMO</th>
                <th>STOCK_MAXIMO</th>
                <th>EXISTENCIA</th>
                <th>Acciones</th>
              </tr>
              <!---->
            </thead>
            
          </table>

        </div>
        <!-- /.box-body -->
      </div>
      <!-- /.box -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->


  <!--creando una ventana modal para llenar
el formulario para creación de un nuevo producto-->

<!-- Modal tomado de boostrap 4.5 -->
<div class="modal fade" id="opcAgregarProducto" tabindex="-1" 
  aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <!--creando el formulario para capturar los datos de la creación en el modal-->
      <form role="form" method="POST">
        <div class="modal-header" style="background:#001F3F; color:white">
          <h5 class="modal-title" id="exampleModalLabel">Registrar nuevo Producto</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <!--creando caja para el contenidod del body-->
          <div class="box-body">
            <!--div para insertar el NIP del producto-->
            <div class="form-group">


              <!--este input group lo que permite es agrupar 
              un ícono con los datos que ingrese el usuario-->
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-key"></i></span>
                <!--solicitar el sku (stock key Unity) del producto (id del producto)-->
                <input class="form-control input-lg" type="number" name="skuNuevo" placeholder="ingrese Clave de producto (SKU)" required>
              </div>
            </div>
          
          
          
            <!--creando formulario (clases de bootstrap específicas para realizar formularios)
            para recolectar el  nombre del nuevo producto-->
            <div class="form-group">
              <!--este input group lo que permite es agrupar 
              un ícono con los datos que ingrese el usuario-->
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-align-left"></i></span>
                <input class="form-control input-lg" type="text" name="descripcionNuevo" placeholder="Descripción del producto (nombre)" required>
              </div>
            </div>


            <!--para recolectar el precio costo del nuevo producto-->
            <div class="form-group">
              <!--este input group lo que permite es agrupar 
              un ícono con los datos que ingrese el usuario-->
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-shopping-basket"></i></span>
                <input class="form-control input-lg" type="text" name="precioCostoNuevo" min=1 step="any"  placeholder="precio costo" required>
              </div>
            </div>


              <!--para recolectar el tipoProducto nuevo producto-->
              <div class="form-group">
                <!--este input group lo que permite es agrupar 
                un ícono con los datos que ingrese el usuario-->
                <div class="input-group">
                  <span class="input-group-addon"><i class="fa fa-list-ol"></i></span>
                  <select class="form-control input-lg" name="tipoProductoNuevo">
                    <option value="">Seleccione categoría del producto...</option>
                    <?php 
                      /*código php para mostrar categorias*/
                      $categoria=ContrlProducto::controlerMostarCategoria();
                      foreach($categoria as $key => $value)
                      {
                       echo'<option value='.$value["DESCRIPCION_TIPO_PRODUCTO"].'>'.$value["DESCRIPCION_TIPO_PRODUCTO"].'</option>';
                      }
                    ?>
                  </select>
                </div>
              </div>


              <!--para recolectar el stockMinimoNuevo del  producto-->
              <div class="form-group">
                <!--este input group lo que permite es agrupar 
                un ícono con los datos que ingrese el usuario-->
                <div class="input-group">
                  <span class="input-group-addon"><i class="fa fa-arrow-down"></i></span>
                  <input class="form-control input-lg" type="number" name="stockMinimoNuevo" min=1 placeholder="Ingrese stock mínimo" required>
                </div>
              </div>




            <!--para recolectar  el stockMaximoNuevo  producto-->
            <div class="form-group">  
              <!--este input group lo que permite es agrupar 
              un ícono con los datos que ingrese el usuario-->
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-arrow-up"></i></span>
                <input class="form-control input-lg" type="number" name="stockMaximoNuevo" min=2 placeholder="Stock Máximo" required>
            </div>
          </div>

          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Crear</button>
        </div>
        <?php
          /*código php para guardar la creación del producto*/
            $almacenarProducto=new ContrlProducto();
            /*método para guardar los productos en el sistema*/
            $almacenarProducto->controlerCrearProducto();
        ?>
      </form>
    </div>
  </div>
</div>

 <!--creando una ventana modal para actualizar un producto-->

<!-- Modal tomado de boostrap 4.5 -->
<div class="modal fade" id="opcEditarProducto" tabindex="-1" 
  aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <!--creando el formulario para capturar los datos de la creación en el modal-->
      <form role="form" method="POST">
        <div class="modal-header"  text-align="center" style="background:#001F3F; color:white">
          <h5 class="modal-title" id="exampleModalLabel" text-align="center">Actualizar datos de Producto</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <!--creando caja para el contenidod del body-->
          <div class="box-body">
            <!--div para insertar el NIP del producto-->
            <div class="form-group">


              <!--este input group lo que permite es agrupar 
              un ícono con los datos que ingrese el usuario-->
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-key"></i></span>
                <!--solo mostrar el sku (stock key Unity) del producto (id del producto)-->
                <input class="form-control input-lg" type="text" id= "skuEditar" name="skuEditar" value="" readonly>
              </div>
            </div>
          
          
          
            <!--creando formulario (clases de bootstrap específicas para realizar formularios)
            para recolectar el  nombre del nuevo producto-->
            <div class="form-group">
              <!--este input group lo que permite es agrupar 
              un ícono con los datos que ingrese el usuario-->
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-align-left"></i></span>
                <input class="form-control input-lg" type="text" id="descripcionEditar" name="descripcionEditar" value="" required>
              </div>
            </div>


            <!--para recolectar el precio costo del nuevo producto-->
            <div class="form-group">
              <!--este input group lo que permite es agrupar 
              un ícono con los datos que ingrese el usuario-->
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-shopping-basket"></i></span>
                <input class="form-control input-lg" type="number" id= "precioCostoEditar" name="precioCostoEditar" min=1 step="any" value="" required>
              </div>
            </div>


              <!--para recolectar el tipoProducto nuevo producto-->
              <div class="form-group">
                <!--este input group lo que permite es agrupar 
                un ícono con los datos que ingrese el usuario-->
                <div class="input-group">
                  <span class="input-group-addon"><i class="fa fa-list-ol"></i></span>
                  <select class="form-control input-lg" name="tipoProductoEditar">
                    <option value="" id="categoriaEditar">Seleccione categoría del producto...</option>
                    <?php 
                      /*código php para mostrar categorias*/
                      $categoria=ContrlProducto::controlerMostarCategoria();
                      foreach($categoria as $key => $value)
                      {
                       echo'<option value='.$value["DESCRIPCION_TIPO_PRODUCTO"].'>'.$value["DESCRIPCION_TIPO_PRODUCTO"].'</option>';
                      }
                    ?>
                  </select>
                </div>
              </div>


              <!--para recolectar el stockMinimoNuevo del  producto-->
              <div class="form-group">
                <!--este input group lo que permite es agrupar 
                un ícono con los datos que ingrese el usuario-->
                <div class="input-group">
                  <span class="input-group-addon"><i class="fa fa-arrow-down"></i></span>
                  <input class="form-control input-lg" type="number" id= "stockMinimoEditar" name="stockMinimoEditar" min="1" value="" required>
                </div>
              </div>




            <!--para recolectar  el stockMaximoNuevo  producto-->
            <div class="form-group">
              <!--este input group lo que permite es agrupar 
              un ícono con los datos que ingrese el usuario-->
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-arrow-up"></i></span>
                <input class="form-control input-lg" type="number" id= "stockMaximoEditar" name="stockMaximoEditar" min="2" value="" required>
            </div>
          </div>

          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Actualizar</button>
        </div>
        <?php
          /*creamos las instancias de la clase controler de producto 
          para salvar las modificaciones del individuo */
            $edicionProducto=new ContrlProducto();  
            $edicionProducto->controlerEditarProducto(); 
        ?>
      </form>
    </div>
  </div>
</div>

 

 <?php
 /*llamando al controlador para eliminar = (dar de baja en el  sistema al producto)*/
 $borrarProducto= new ContrlProducto();
/*método que realiza la acción*/
 $borrarProducto->controlerBorrarProducto();
 
 ?>