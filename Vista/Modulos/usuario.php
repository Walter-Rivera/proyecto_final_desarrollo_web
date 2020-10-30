<?php
/*validando acceso a las opciones si no tiene
permisos el usuario, lo redirecciona a la página de inicio*/
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
        <b>Administración de Usuarios</b> 
        <small>Almacén TFM</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="ini"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li class="active">Administración de Usuraios</li>
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
          <button color="red" class="btn btn-primary" data-toggle="modal" data-target="#opcAgregarUsuario">
            Crear Usuario
          </button>

        </div>
        <!--mostrar los usuarios registrados, voy a mostrarlos usando una tabla la cual
          se incrustará en un div-->
        <div class="box-body">
            <!--las clases para esta tabla se están tomando de bootstrap; a excepción 
            de la clase Tabla, la cual usaremos en js para activar el plugin datatable en esta
            seccion-->
          <table class="table table-bordered table-striped table-dark table-responsive-xl VtUsuarios" style="width:100%">

           
            <!--cabecera de la tabla-->
            <thead class="thead-dark" align="center">
                <!--<tr>
                    <th>Name</th>
                    <th>Position</th>
                    <th>Office</th>
                    <th>Extn.</th>
                    <th>Start date</th>
                    <th>Salary</th>
                </tr>-->
              <!----><tr>
                <th>Nip</th>
                <th>Nombres</th>
                <th>Apellidos</th>
                <th>Correo Institucional</th>
                <th>Estado</th>
                <th>Rol</th>
                <th>Contraseña</th>
                <th>Fecha Último Acceso</th>
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
el formulario para creación de un nuevo usuario-->

<!-- Modal tomado de boostrap 4.5 -->
<div class="modal fade" id="opcAgregarUsuario" tabindex="-1" 
  aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <!--creando el formulario para capturar los datos de la creación en el modal-->
      <form role="form" method="POST">
        <div class="modal-header" style="background:#001F3F; color:white">
          <h5 class="modal-title" id="exampleModalLabel">Registrar nuevo Usuario</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <!--creando caja para el contenidod del body-->
          <div class="box-body">
            <!--div para insertar el NIP del usuario-->
            <div class="form-group">


              <!--este input group lo que permite es agrupar 
              un ícono con los datos que ingrese el usuario-->
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-id-card"></i></span>
                <!--atributo de solo lectura para el NIP-->
                <input class="form-control input-lg" type="text" name="nipNuevo" placeholder="ingrese el NIP" required>
              </div>
            </div>
          
          
          
            <!--creando formulario (clases de bootstrap específicas para realizar formularios)
            para recolectar el o los nombres del nuevo usuario-->
            <div class="form-group">
              <!--este input group lo que permite es agrupar 
              un ícono con los datos que ingrese el usuario-->
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-user-plus"></i></span>
                <input class="form-control input-lg" type="text" name="nombreNuevo" placeholder="ingrese Nombre(s)" required>
              </div>
            </div>


            <!--para recolectar el o los apellidos del nuevo usuario-->
            <div class="form-group">
              <!--este input group lo que permite es agrupar 
              un ícono con los datos que ingrese el usuario-->
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-user-plus"></i></span>
                <input class="form-control input-lg" type="text" name="apellidoNuevo" placeholder="ingrese Apellido(s)" required>
              </div>
            </div>

            <!--para recolectar el correo del nuevo usuario-->
            <div class="form-group">
                <!--este input group lo que permite es agrupar 
                un ícono con los datos que ingrese el usuario-->
                <div class="input-group">
                  <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                  <input class="form-control input-lg" type="email" name="correoNuevo" placeholder="correo electrónico institucional" required>
                </div>
              </div>


              <!--para recolectar el rol del nuevo usuario-->
              <div class="form-group">
              <!--este input group lo que permite es agrupar 
              un ícono con los datos que ingrese el usuario-->
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-users"></i></span>
                <select class="form-control input-lg" name="rolNuevo">
                  <option value="">Seleccione un rol...</option>
                  <option value="ADMINISTRADOR">ADMINISTRADOR</option>
                  <option value="ENCARGADO_ALMACEN">ENCARGADO_ALMACEN</option>
                  <option value="JEFATURA">JEFATURA</option>
                </select>
              </div>
            </div>




            <!--para recolectar la contraseña del nuevo usuario-->
            <div class="form-group">
              <!--este input group lo que permite es agrupar 
              un ícono con los datos que ingrese el usuario-->
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-key"></i></span>
                <input class="form-control input-lg" type="password" name="contraNuevo" placeholder="Establecer contraseña" required>
            </div>
          </div>

          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Crear</button>
        </div>
        <?php
          /*código php para guardar la creación del usuario*/
            $almacenarUsuario=new ContrlUsuario();
            /*método para guardar los usuarios en el sistema*/
            $almacenarUsuario->controlerCrearUsuario();
        ?>
      </form>
    </div>
  </div>
</div>

 <!--creando una ventana modal para actualizar un usuario-->

<!-- Modal tomado de boostrap 4.5 -->
<div class="modal fade" id="opcEditarUsuario" tabindex="-1" 
  aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <!--creando el formulario para capturar los datos de la creación en el modal-->
      <form role="form" method="POST">
        <div class="modal-header"  text-align="center" style="background:#001F3F; color:white">
          <h5 class="modal-title" id="exampleModalLabel" text-align="center">Actualizar datos de Usuario</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <!--creando caja para el contenidod del body-->
          <div class="box-body">
            <!--div para ver el NIP del usuario-->
            <div class="form-group">


              <!--este input group lo que permite es agrupar 
              un ícono con los datos del usuario-->
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-id-card"></i></span>
                <input class="form-control input-lg" type="text" id="nipEditar" name="nipEditar" value="" required readonly>
              </div>
            </div>
          
          
          
            <!--creando formulario (clases de bootstrap específicas para realizar formularios)
            para recolectar el o los nombres del nuevo usuario-->
            <div class="form-group">
              <!--este input group lo que permite es agrupar 
              un ícono del usuario-->
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-user-plus"></i></span>
                <input class="form-control input-lg" type="text" id="nombreEditar" name="nombreEditar" value="" required>
              </div>
            </div>


            <!--para recolectar el o los apellidos del nuevo usuario-->
            <div class="form-group">
              <!--este input group lo que permite es agrupar 
              un ícono con los datos del usuario-->
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-user-plus"></i></span>
                <input class="form-control input-lg" type="text" id="apellidoEditar" name="apellidoEditar" value="" required>
              </div>
            </div>

            <!--para recolectar el correo del nuevo usuario-->
            <div class="form-group">
                <!--este input group lo que permite es agrupar 
                un ícono con los datos del usuario-->
                <div class="input-group">
                  <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                  <input class="form-control input-lg" type="email" id="correoEditar" name="correoEditar" value="" required>
                </div>
              </div>


              <!--para recolectar el rol del  usuario-->
              <div class="form-group">
              <!--este input group lo que permite es agrupar 
              un ícono con los datos del usuario-->
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-users"></i></span>
                <select class="form-control input-lg" name="rolEditar">
                  <option value="" id="optEditarRol">Editar Rol</option>
                  <option value="ADMINISTRADOR">ADMINISTRADOR</option>
                  <option value="ENCARGADO_ALMACEN">ENCARGADO_ALMACEN</option>
                  <option value="JEFATURA">JEFATURA</option>
                </select>
              </div>
            </div>




                  <!--para recolectar la contraseña del  usuario-->
                  <div class="form-group">
              <!--este input group lo que permite es agrupar 
              un ícono con los datos del  usuario-->
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-key"></i></span>
                <input class="form-control input-lg" type="password" name="contraEditar" placeholder="editar contraseña">
                <!--input oculto para guardar la contraseña actual del usuario antes de actualizarla -->
                <input type="hidden" id="contraActual" name="contraActual" value="">
              </div>
            </div>

          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Actualizar</button>
        </div>
        <?php
          /*creamos las instancias de la clase controler de usuario 
          para salvar las modificaciones del individuo */
            $edicionUsuario=new ContrlUsuario();  
            $edicionUsuario->controlerEditarUsuario(); 

        ?>
      </form>
    </div>
  </div>
</div>

 

 <?php
 /*llamando al controlador para eliminar = (dar de baja en el  sistema al usuario)*/
 $borrarUsuario= new ContrlUsuario();
/*método que realiza la acción*/
 $borrarUsuario->controlerBorrarUsuario();
 
 ?>