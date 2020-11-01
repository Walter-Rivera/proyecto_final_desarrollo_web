<?php
session_start();
require_once '../Controlador/usuario.controler.php';
require_once '../Modelo/usuario.model.php';

class VerRespuesta
{
    static public function mostrarDatos()
    {
        /*recupero los datos */
        $ans=ContrlUsuario::controlerVerDatos();
        
        $datos='{"data": [';
            /*recorro los datos obtenidos*/
            for($d=0;$d<count($ans);$d++)
            {
                /*atributos html a agregar al select*/
                $atributoSelect=$ans[$d]["DESCRIPCION"];
                $datos.='["'.$atributoSelect.'"],';

            }
            $datos=substr($datos,0,-1);
        $datos.='] }';
        echo $datos;

    }
}
/*instancio la clase y método*/
$mostrar=new VerRespuesta;
$mostrar->mostrarDatos();