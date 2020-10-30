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
        <b>Administración de Clientes</b> 
        <small>Almacén TFM</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="ini"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li class="active">Administración de Clientes</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <!--Colocando opción para agregar cliente al Sistema, básicamente
              al darle click, nos creará una ventana modal para un form y allí colocar los elementos
            para crear un nuevo cliente-->
          <button color="red" class="btn btn-primary" data-toggle="modal" data-target="#opcAgregarCliente">
            Crear Cliente
          </button>

        </div>
        <!--mostrar los clientes registrados, voy a mostrarlos usando una tabla la cual
          se incrustará en un div-->
        <div class="box-body">
            <!--las clases para esta tabla se están tomando de bootstrap; a excepción 
            de la clase Tabla, la cual usaremos en js para activar el plugin datatable en esta
            seccion-->
          <table class="table table-bordered table-striped table-dark table-responsive-xl Tabla">
            <!--cabecera de la tabla-->
            <thead class="thead-dark" align="center">
              <tr>
                <th>Nip</th>
                <th>Nombres</th>
                <th>Apellidos</th>
                <th>Correo Institucional</th>
                <th>Estado</th>
                <th>Seccion</th>
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


                /*recuperando el listado de clientes, vamos a hacer la petición
                al controlador para que este la haga al modelo*/
                $res=ContrlCliente::controlerMostrarClientes($campo,$valor);
                //var_dump($res);
                /*con un for each recorremos el contenido del array que nos devuelve el controler */
                foreach ($res as $key => $value) {

                  /*metemos los resultados al body de la tabla */
                  echo'
                        <tr>
                        <td>'.$value["NIP"].'</td>
                        <td>'.$value["NOMBRES"].'</td>
                        <td>'.$value["APELLIDOS"].'</td>
                        <td>'.$value["CORREO_INSTITUCIONAL"].'</td>';

                  if($value["ESTADO_CLIENTE"]=="ACTIVO")
                  {
                    echo'<td> <button  class="btn btn-success btn-xs botonActivarCli" NIPcli="'.$value["NIP"].'" RESPON="'.$_SESSION["NIP"].'" estadoCli="1">Activo</button> </td>';
                  }
                  else
                  {
                    echo'<td> <button  class="btn btn-danger btn-xs botonActivarCli" NIPcli="'.$value["NIP"].'" RESPON="'.$_SESSION["NIP"].'" estadoCli="2">Inactivo</button> </td>';
                  }
                  echo'
                        <td>'.$value["SECCION"].'</td>
                        <td>
                          <div class="btn-group">
                            <button class="btn btn-warning botonEditarCliente" nipEditarCliente="'.$value["NIP"].'" data-toggle="modal" data-target="#opcEditarCliente"><i class="fa fa-pencil"></i></button>
                            <button class="btn btn-danger botonEliminarCliente" nipBorrarCliente="'.$value["NIP"].'" RESPON="'.$_SESSION["NIP"].'"><i class="fa fa-exclamation-circle"></i></button>
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
  <!-- /.content-wrapper -->


  <!--creando una ventana modal para llenar
el formulario para creación de un nuevo cliente-->

<!-- Modal tomado de boostrap 4.5 -->
<div class="modal fade" id="opcAgregarCliente" tabindex="-1" 
  aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <!--creando el formulario para capturar los datos de la creación en el modal-->
      <form role="form" method="POST">
        <div class="modal-header" style="background:#001F3F; color:white">
          <h5 class="modal-title" id="exampleModalLabel">Registrar nuevo Cliente</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <!--creando caja para el contenidod del body-->
          <div class="box-body">
            <!--div para insertar el NIP del cliente-->
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
            para recolectar el o los nombres del nuevo cliente-->
            <div class="form-group">
              <!--este input group lo que permite es agrupar 
              un ícono con los datos que ingrese el cliente-->
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-user-plus"></i></span>
                <input class="form-control input-lg" type="text" name="nombreNuevo" placeholder="ingrese Nombre(s)" required>
              </div>
            </div>


            <!--para recolectar el o los apellidos del nuevo cliente-->
            <div class="form-group">
              <!--este input group lo que permite es agrupar 
              un ícono con los datos que ingrese el cliente-->
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-user-plus"></i></span>
                <input class="form-control input-lg" type="text" name="apellidoNuevo" placeholder="ingrese Apellido(s)" required>
              </div>
            </div>

            <!--para recolectar el correo del nuevo cliente-->
            <div class="form-group">
                <!--este input group lo que permite es agrupar 
                un ícono con los datos que ingrese el cliente-->
                <div class="input-group">
                  <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                  <input class="form-control input-lg" type="email" name="correoNuevo" placeholder="correo electrónico institucional" required>
                </div>
              </div>


              <!--para recolectar la sección del cliente-->
              <div class="form-group">
              <!--este input group lo que permite es agrupar 
              un ícono con los datos que ingrese el usuario-->
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-users"></i></span>
                <select class="form-control input-lg" name="seccionN">
                  <option value="">Elija la sección a la que pertenece el cliente...</option>
                  <?php
                    $info = ContrlCliente::controlerMostarSeccion();

                    foreach($info as $key => $value)
                    {
                      echo'<option value='.$value["DESCRIPCION"].'>'.$value["DESCRIPCION"].'</option>';
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
          /*código php para guardar la creación del cliente*/
            $almacenarCliente=new ContrlCliente();
            /*método para guardar los clientes en el sistema*/
            $almacenarCliente->controlerCrearCliente();
        ?>
      </form>
    </div>
  </div>
</div>

 <!--creando una ventana modal para actualizar un cliente-->

<!-- Modal tomado de boostrap 4.5 -->
<div class="modal fade" id="opcEditarCliente" tabindex="-1" 
  aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <!--creando el formulario para capturar los datos de la creación en el modal-->
      <form role="form" method="POST">
        <div class="modal-header"  text-align="center" style="background:#001F3F; color:white">
          <h5 class="modal-title" id="exampleModalLabel" text-align="center">Actualizar datos de Cliente</h5>
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
                <select class="form-control input-lg" name="seccionE">
                <option value="" id="optEditarCliente">Seleccione un rol...</option>
                <?php
                    $info = ContrlCliente::controlerMostarSeccion();

                    foreach($info as $key => $value)
                    {
                      echo'<option value='.$value["DESCRIPCION"].'>'.$value["DESCRIPCION"].'</option>';
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
          /*creamos las instancias de la clase controler de cliente 
          para salvar las modificaciones del individuo */
            $edicionUsuario=new ContrlCliente();  
            $edicionUsuario->controlerEditarClientes(); 
        ?>
      </form>
    </div>
  </div>
</div>

 

 <?php
 /*llamando al controlador para eliminar = (dar de baja en el  sistema al cliente)*/
 $borrarUsuario= new ContrlCliente();
/*método que realiza la acción*/
 $borrarUsuario->controlerBorrarCliente();
 
 ?>