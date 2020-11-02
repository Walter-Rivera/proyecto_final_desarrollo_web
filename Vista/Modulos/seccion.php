<?php
/*validando acceso a las opciones si no tiene
permisos el seccion, lo redirecciona a la página de inicio*/
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
        <b>Administración de Secciones</b> 
        <small>TFM</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="ini"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li class="active">Administración de Secciones</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <!--Colocando opción para agregar seccion al Sistema, básicamente
              al darle click, nos creará una ventana modal para un form y allí colocar los elementos
            para crear una nueva seccion-->
          <button color="red" class="btn btn-primary" data-toggle="modal" data-target="#opcAgregarSeccion">
            Crear Seccion
          </button>

        </div>
        <!--mostrar los usuarios registrados, voy a mostrarlos usando una tabla la cual
          se incrustará en un div-->
        <div class="box-body">
            <!--las clases para esta tabla se están tomando de bootstrap; a excepción 
            de la clase Tabla, la cual usaremos en js para activar el plugin datatable en esta
            seccion-->
          <table class="table table-bordered table-striped table-dark table-responsive-xl VtSeccions" style="width:100%">

           
            <!--cabecera de la tabla-->
            <thead class="thead-dark" align="center">
             <tr>
                <th>ID</th>
                <th>NOMBRE</th>
                <th>IDENTIFICADOR</th>
                <th>Estado</th>
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
el formulario para creación de un nuevo seccion-->

<!-- Modal tomado de boostrap 4.5 -->
<div class="modal fade" id="opcAgregarSeccion" tabindex="-1" 
  aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <!--creando el formulario para capturar los datos de la creación en el modal-->
      <form role="form" method="POST">
        <div class="modal-header" style="background:#001F3F; color:white">
          <h5 class="modal-title" id="exampleModalLabel">Registrar Seccion</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <!--creando caja para el contenidod del body-->
          <div class="box-body">
            <!--div para insertar el NIP del seccion-->
            <div class="form-group">


              <!--este input group lo que permite es agrupar 
              un ícono con los datos que ingrese el seccion-->
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-plus"></i></span>
                <!--atributo de solo lectura para el NIP-->
                <input class="form-control input-lg" type="text" name="idNuevo" placeholder="ingrese el NIP" required>
              </div>
            </div>
          
          
          
            <!--creando formulario (clases de bootstrap específicas para realizar formularios)
            para recolectar el o los nombres del nuevo seccion-->
            <div class="form-group">
              <!--este input group lo que permite es agrupar 
              un ícono con los datos que ingrese el seccion-->
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-plus"></i></span>
                <input class="form-control input-lg" type="text" name="nombreNuevo" placeholder="ingrese Nombre de la sección" required>
              </div>
            </div>


            <!--para recolectar el o los identificadors del nuevo seccion-->
            <div class="form-group">
              <!--este input group lo que permite es agrupar 
              un ícono con los datos que ingrese el seccion-->
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-plus"></i></span>
                <input class="form-control input-lg" type="text" name="identificadorNuevo" placeholder="ingrese Acrónimo" required>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Crear</button>
        </div>
        <?php
          /*código php para guardar la creación del seccion*/
            $almacenarSeccion=new ContrlSeccion();
            /*método para guardar los usuarios en el sistema*/
            $almacenarSeccion->controlerCrearSeccion();
        ?>
      </form>
    </div>
  </div>
</div>

 <!--creando una ventana modal para actualizar un seccion-->

<!-- Modal tomado de boostrap 4.5 -->
<div class="modal fade" id="opcEditarSeccion" tabindex="-1" 
  aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <!--creando el formulario para capturar los datos de la creación en el modal-->
      <form role="form" method="POST">
        <div class="modal-header"  text-align="center" style="background:#001F3F; color:white">
          <h5 class="modal-title" id="exampleModalLabel" text-align="center">Actualizar datos de Seccion</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <!--creando caja para el contenidod del body-->
          <div class="box-body">
            <!--div para ver el NIP del seccion-->
            <div class="form-group">


              <!--este input group lo que permite es agrupar 
              un ícono con los datos del seccion-->
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-list"></i></span>
                <input class="form-control input-lg" type="text" id="idEditar" name="idEditar" value="" required readonly>
              </div>
            </div>
          
          
          
            <!--creando formulario (clases de bootstrap específicas para realizar formularios)
            para recolectar el o los nombres del nuevo seccion-->
            <div class="form-group">
              <!--este input group lo que permite es agrupar 
              un ícono del seccion-->
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-list"></i></span>
                <input class="form-control input-lg" type="text" id="nombreEditar" name="nombreEditar" value="" required>
              </div>
            </div>


            <!--para recolectar el o los identificador del nuevo seccion-->
            <div class="form-group">
              <!--este input group lo que permite es agrupar 
              un ícono con los datos del seccion-->
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-fa-list"></i></span>
                <input class="form-control input-lg" type="text" id="identificadorEditar" name="identificadorEditar" value="" required>
              </div>
            </div>

          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Actualizar</button>
        </div>
        <?php
          /*creamos las instancias de la clase controler de seccion 
          para salvar las modificaciones del individuo */
            $edicionSeccion=new ContrlSeccion();  
            $edicionSeccion->controlerEditarSeccion(); 
        ?>
      </form>
    </div>
  </div>
</div>

 

 <?php
 /*llamando al controlador para eliminar = (dar de baja en el  sistema al seccion)*/
 $borrarUsuario= new ContrlSeccion();
/*método que realiza la acción*/
 $borrarUsuario->controlerBorrarSeccion();
 ?>