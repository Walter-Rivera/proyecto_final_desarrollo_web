<!--en este div vamos a cargar la imagen de fondo-->
<div id="fondo"></div>


<!--codigo base obtenido de AdminLTE-->
<div class="login-box">
  
    <div class="login-logo">
        <!--coloco la imagen del logo de la Institución-->
        <img src="Vista/img/Plantilla/logoIcono.png" class="img-responsive" 
        style="padding:50px 100px 0px 65px" align="center">
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
        <strong>
            <p class="login-box-msg">Sistema Gestión de Peritajes y oficios TFM</p>
        </strong>

        <form method="post">

            <!--div para solicitar el NIP del usuario-->
            <div class="form-group has-feedback">
                <!--el NIP del trabajador es el Númerode Identificación Personal dentro de la Institución
                (INACIF), debemos definir que tiene que ser un campo obligatorio, es decir que el usuario lo tiene
                que llenar-->
                <input type="text" class="form-control" placeholder="NIP" name="nipUsuario" required>
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
            </div>

            <!--div para solicitar la contraseña del usuario-->
            <div class="form-group has-feedback">
                <input type="password" class="form-control" placeholder="Ingrese su contraseña" name="Ingreso" required>
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>

            <div class="row text-center">
               
        
                <!-- Para el botón de iniciar sesión-->
                <div class="col-lg-4 text-center" >
                    <button type="submit" class="btn btn-primary btn-block btn-flat" >Entrar</button>
                </div>
             <!-- /.col -->
            </div>
            <!--realizando el inicio de sesión, vamos a invocar al controlador-->
            <?php
                /*instanciando el controlador de la clase usuarios*/
                $login = new ContrlUsuario();
                $login->controlerIngresoUsuario();

            ?>


        </form>

        
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->
