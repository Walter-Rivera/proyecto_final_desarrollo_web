<?php
/*validando acceso a las opciones si no tiene
permisos el TipoGestion, lo redirecciona a la página de inicio*/
/*a la administración de usuarios, solo el admin tendrá acceso*/
  if($_SESSION["DESCRIPCION"]!="ADMINISTRADOR")
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
        <b>Administración Tipo de Gestiones</b> 
        <small>TFM</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="ini"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li class="active">Administración tipo de Gestiones</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <!--Colocando opción para agregar TipoGestion al Sistema, básicamente
              al darle click, nos creará una ventana modal para un form y allí colocar los elementos
            para crear una nueva TipoGestion-->
          <button color="red" class="btn btn-primary" data-toggle="modal" data-target="#opcAgregarTipoGestion">
            Crear TipoGestion
          </button>

        </div>
        <!--mostrar los usuarios registrados, voy a mostrarlos usando una tabla la cual
          se incrustará en un div-->
        <div class="box-body">
            <!--las clases para esta tabla se están tomando de bootstrap; a excepción 
            de la clase Tabla, la cual usaremos en js para activar el plugin datatable en esta
            TipoGestion-->
          <table class="table table-bordered table-striped table-dark table-responsive-xl VtTipoGestion" style="width:100%">

           
            <!--cabecera de la tabla-->
            <thead class="thead-dark" align="center">
             <tr>
                <th>ID</th>
                <th>NOMBRE</th>
                <th>CLASE_GESTION</th>
                <th>ESTADO_TIPO_GESTION</th>
                <th>Acciones</th>
              </tr>
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
el formulario para creación de un nuevo TipoGestion-->

<!-- Modal tomado de boostrap 4.5 -->
<div class="modal fade" id="opcAgregarTipoGestion" tabindex="-1" 
  aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <!--creando el formulario para capturar los datos de la creación en el modal-->
      <form role="form" method="POST">
        <div class="modal-header" style="background:#001F3F; color:white">
          <h5 class="modal-title" id="exampleModalLabel">Registrar Tipo Gestion</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <!--creando caja para el contenidod del body-->
          <div class="box-body">
            
          
          
            <!--creando formulario (clases de bootstrap específicas para realizar formularios)
            para recolectar el o los nombres del nuevo TipoGestion-->
            <div class="form-group">
              <!--este input group lo que permite es agrupar 
              un ícono con los datos que ingrese el TipoGestion-->
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-plus"></i></span>
                <input class="form-control input-lg" type="text" name="nombreNuevo" placeholder="ingrese Nombre de la gestión" required>
              </div>
            </div>


            <!--para recolectar el o los identificadors del nuevo TipoGestion-->
            <div class="form-group">
              <!--este input group lo que permite es agrupar 
              un ícono con los datos que ingrese el TipoGestion-->
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-plus"></i></span>
                <select class="form-control input-lg" name="TipoGestionC">
                <option value="">Seleccione clase de gestion...</option>
                <?php
                    $info = ContrlTipoGestion::controlerMostarClase();

                    foreach($info as $key => $value)
                    {
                      echo'<option value='.$value["NOMBRE"].'>'.$value["NOMBRE"].'</option>';
                    }

                  ?>
                </select>
              </div>
            </div>

          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Crear</button>
        </div>
        <?php
          /*código php para guardar la creación del TipoGestion*/
            $almacenarTipoGestion=new ContrlTipoGestion();
            /*método para guardar los usuarios en el sistema*/
            $almacenarTipoGestion->controlerCrearTipoGestion();
        ?>
      </form>
    </div>
  </div>
</div>

 <!--creando una ventana modal para actualizar un TipoGestion-->

<!-- Modal tomado de boostrap 4.5 -->
<div class="modal fade" id="opcEditarTipoGestion" tabindex="-1" 
  aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <!--creando el formulario para capturar los datos de la creación en el modal-->
      <form role="form" method="POST">
        <div class="modal-header"  text-align="center" style="background:#001F3F; color:white">
          <h5 class="modal-title" id="exampleModalLabel" text-align="center">Actualizar datos de TipoGestion</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <!--creando caja para el contenidod del body-->
          <div class="box-body">
            <!--div para ver el NIP del TipoGestion-->
            <div class="form-group">


              <!--este input group lo que permite es agrupar 
              un ícono con los datos del TipoGestion-->
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-list"></i></span>
                <input class="form-control input-lg" type="text" id="idEditar" name="idEditar" value="" required readonly>
              </div>
            </div>
          
          
          
            <!--creando formulario (clases de bootstrap específicas para realizar formularios)
            para recolectar el o los nombres del nuevo TipoGestion-->
            <div class="form-group">
              <!--este input group lo que permite es agrupar 
              un ícono del TipoGestion-->
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-list"></i></span>
                <input class="form-control input-lg" type="text" id="nombreEditar" name="nombreEditar" value="" required>
              </div>
            </div>


            <!--para recolectar el o los identificador del nuevo TipoGestion-->
            <div class="form-group">
              <!--este input group lo que permite es agrupar 
              un ícono con los datos del TipoGestion-->
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-list"></i></span>
                <select class="form-control input-lg" name="TipoGestionE">
                <option value="" id="optEditarTipoGestion" name="optEditarTipoGestion">seleccione clase gestion...</option>
                <?php
                    $info = ContrlTipoGestion::controlerMostarClase();

                    foreach($info as $key => $value)
                    {
                      echo'<option value='.$value["NOMBRE"].'>'.$value["NOMBRE"].'</option>';
                    }

                  ?>
                </select>
              </div>
            </div>

          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Actualizar</button>
        </div>
        <?php
          /*creamos las instancias de la clase controler de TipoGestion 
          para salvar las modificaciones del individuo */
            $edicionTipoGestion=new ContrlTipoGestion();  
            $edicionTipoGestion->controlerEditarTipoGestion(); 
        ?>
      </form>
    </div>
  </div>
</div>

 

 <?php
 /*llamando al controlador para eliminar = (dar de baja en el  sistema al TipoGestion)*/
 $borrarUsuario= new ContrlTipoGestion();
/*método que realiza la acción*/
 $borrarUsuario->controlerBorrarTipoGestion();
 ?>