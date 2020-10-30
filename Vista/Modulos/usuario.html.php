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
          <table class="table table-bordered table-striped table-dark table-responsive-xl Tabla">
            <!--cabecera de la tabla-->
            <thead class="thead-dark">
              <tr>
                <th>Nip</th>
                <th>Nombres</th>
                <th>Apellidos</th>
                <th>Correo Institucional</th>
                <th>Rol</th>
                <th>Estado</th>
                <th>Ultimo Ingreso</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <!--creando  el cuerpo de la tabla-->
            <tbody>
              <tr>
                <td>1</td>
                <td>2</td>
                <td>3</td>
                <td>4</td>
                <td >5</td>
                <td> <button  class="btn btn-success btn-xs">Activo</button> </td>
                <td><?php echo date('l jS \of F Y h:i:s A');?></td>
                <td>
                  <div class="btn-group">
                    <button class="btn btn-warning"><i class="fa fa-pencil"></i></button>
                    <button class="btn btn-danger"><i class="fa fa-exclamation-circle"></i></button>
                  </div>
                </td>
              </tr>

              <tr>
                <td>1</td>
                <td>2</td>
                <td>3</td>
                <td>4</td>
                <td >5</td>
                <td> <button  class="btn btn-danger btn-xs">Inactivo</button> </td>
                <td><?php echo date('l jS \of F Y h:i:s A');?></td>
                <td>
                  <div class="btn-group">
                    <button class="btn btn-warning"><i class="fa fa-pencil"></i></button>
                    <button class="btn btn-danger"><i class="fa fa-exclamation-circle"></i></button>
                  </div>
                </td>
              </tr>

              <tr>
                <td>1</td>
                <td>2</td>
                <td>3</td>
                <td>4</td>
                <td >5</td>
                <td> <button  class="btn btn-success btn-xs">Activo</button> </td>
                <td><?php echo date('l jS \of F Y h:i:s A');?></td>
                <td>
                  <div class="btn-group">
                    <button class="btn btn-warning"><i class="fa fa-pencil"></i></button>
                    <button class="btn btn-danger"><i class="fa fa-exclamation-circle"></i></button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>

        </div>
        <!-- /.box-body -->
        <div class="box-footer">
          Footer
        </div>
        <!-- /.box-footer-->
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
                  <option value="admin">Administrador</option>
                  <option value="encargado">Encargado Almacén</option>
                  <option value="jefe">Jefatura</option>
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
      </form>
    </div>
  </div>
</div>