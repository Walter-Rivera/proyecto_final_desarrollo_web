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
        <b>Administración de Aprovisionamiento</b> 
        <small>Almacén TFM</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="ini"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li class="active">Aprovisionamiento</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <!--Colocando opción para agregar aprovisionamiento al Sistema, básicamente
              al darle click, nos rederigirá a otra página para el proceso-->
          
          <a href="crear_aprovisionamiento">
            <button color="red" class="btn btn-primary">
              Registrar Ingreso de productos
            </button>
          </a>
          
        </div>
        <!--mostrar los usuarios registrados, voy a mostrarlos usando una tabla la cual
          se incrustará en un div-->
        <div class="box-body">
            <!--las clases para esta tabla se están tomando de bootstrap; a excepción 
            de la clase Tabla, la cual usaremos en js para activar el plugin datatable en esta
            seccion-->
          <table class="table table-bordered table-striped table-dark table-responsive-xl Tabla" style="width:100%">

           
            <!--cabecera de la tabla-->
            <thead class="thead-dark" align="center">
              <tr>
                <th>Consumidor_Final</th>
                <th>total</th>
                <th>Fecha</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
                <tr>
                    <td>pep</td>
                    <td>1300</td>
                    <td>27/10/2020</td>
                    <td>
                      <div class="btn-group">
                          <button class="btn btn-info"><i class="fa fa-print"></i></button>
                          <button class="btn btn-danger"><i class="fa fa-exclamation-circle"></i></button>
                      </div>
                    </td>
                  </tr>
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

