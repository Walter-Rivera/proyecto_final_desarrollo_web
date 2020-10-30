<?php
/*validando acceso a las opciones si no tiene
permisos el usuario, lo redirecciona a la página de inicio*/
/*a la administración de usuarios, solo el admin tendrá acceso*/
  if($_SESSION["DESCRIPCION"]=="JEFATURA")
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
        <b>Crear Registro de Aprovisionamiento</b> 
        <small>Almacén TFM</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="ini"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li class="active">Crear Registro de Aprovisionamiento</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <!--formulario-->
        <div class="col-lg-5 col-12">
          <div class="box box-warning">
            <div class="box-header with-border">
            </div>
            <div class="box-body">
              <form role="form" method="post">
                <div class="box">
                  <!--nombre del proveedor-->
                  <div class="form-group">
                    <div class="input-group">
                      <span class="input-group-addon"><i class="fa fa-user-secret"></i></span>
                      <select type="text"  class="form-control text-center" id="idProveedor" name="idProveedor" required>
                        <option value="">Seleccione proveedor...</option>
                      </select>
                      <span class="input-group-addon"><button type="button" class="btn btn-warning btn-xs"
                      data-toggle="modal" data-target="#opcAgregarProducto" data-dismiss="modal">Producto Nuevo</button></span>
                    </div>
                  </div>

                   <!--id del envío de entrada (aprovisionamiento)-->
                  <div class="form-group">
                    <div class="input-group">
                      <span class="input-group-addon"><i class="fa fa-key"></i></span>
                      <input type="text"  class="form-control text-left" id="idNuevaEntrada" name="idNuevaEntrada" value="1234568" readonly>
                    </div>
                  </div>
                  <!--agregar productos-->
                  <div class="form-group row agregarProducto">
                    <!--descripcion-->
                    <div class="col-lg-6" style="padding-left:15px">
                      <div class="input-group">
                        <input type="text" class="form-control" id="descripcionProducto" name="descripcionProducto" placeholder="Descripción producto" readonly required>
                      </div>
                    </div>
                    <!--cantidad-->
                    <div class="col-lg-3" style="padding-left:15px">
                      <input type="number" class="form-control text-center" id="cantidadProducto" name="cantidadProducto" min=1 placeholder="0" required>
                    </div>
                    <!--precio costo-->
                    <div class="col-lg-3" style="padding-left:15px">
                      <div class="input-group">
                        <input type="number" class="form-control text-center" id="precioProducto" name="precioProducto" min=0.05 step="any" placeholder="precio" readonly required>
                        <span class="input-group-addon"><button type="button" class="btn btn-danger btn-xs"><i class="fa fa-times"></i></button></span>

                      </div>
                    </div>
                  </div>
                  <!--agregar producto desde dispositivos pequeños-->
                  <div class="row justify-content-center">
                    <button type="button" class="btn btn-default d-lg-none">Agregar Producto</button>
                  </div>
                  <br>
                  <br>
                  <div class="row justify-content-center" >
                    <div class="col-8">
                      <table class="table row justify-content-center">
                        <thead>
                          <tr>
                            <th>Total (Q)</th>
                          </tr> 
                        </thead>
                        <tbody>
                          <tr>
                            <td style="width:50%">
                            <div class="input-group">
                              <input type="number" class="form-control text-center" id="total" name="total" min=0.05 step="any" placeholder="total" readonly required>
                            </div></td>
                          </tr>
                        </tbody>
                      </table> 
                    </div>
                  </div>
                </div>
                <br>
                <div class="row justify-content-center" sytle="padding-left:15px">
                  <button type="submit" class="btn btn-primary">Guardar Aprovisionamiento</button>              
                </div>
              </form>
            </div>
          </div>


        </div>

        <!--ocultar el formulario de stock de productos para dispositivos de menor resolución a una pc-->
        <div class="col-lg-7 d-none d-sm-none d-md-block d-xs-none">
          <div class="box box-info">
            <div class="box-header with-border"></div>
            <div class="box-body">
              <table class="table table-bordered table-striped table-dark table-responsive-xl Tabla">
                <thead>
                  <tr>
                    <th>SKU</th>
                    <th>DESCRIPCION</th>
                    <th>EXISTENCIA_ACTUAL</th>
                    <th>ACCIONES</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>1500</td>
                    <td>AJAJALJAJALJLAJDLJFLAJFLAJFLAJDFLAJSFAFKSKFÑK DFA </td>
                    <td>120</td>
                    <td><div class="btn-group"><button type="button" class="btn btn-primary">Agregar</button></div></td>
                  </tr>
                </tbody>
              </table>
            
            </div>

          
          </div>

          
        </div>
        
        

      </div>

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

   <!--creando una ventana modal para llenar
el formulario para creación de un nuevo Proveedor-->
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
</div>

