<?php
/*este módulo nos ayudará a cerrar la sesión
 que se encuentre abierta (destruirla)*/
 session_destroy();
 /*al destruir la sesión, redireccionamos a 
 la persona nuevamente al login*/
 echo '<script>window.location="login";</script>';


