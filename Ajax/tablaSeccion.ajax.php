<?php
session_start();
require_once '../Controlador/seccion.controler.php';
require_once '../Modelo/seccion.model.php';

class VistaSeccion{

    static public function mostrarVistaSeccion()
    {
        $campo=null;
        $valor=null;
        $datosSeccion='';
        $res=ContrlSeccion::controlerMostrarSeccion($campo,$valor);
                /*Capturando atributos del boton de estado */
        $datosSeccion='{
            "data": [';
            for($n=0;$n<count($res);$n++)
            {
               $botonesAcciones="<div class='btn-group'><button class='btn btn-warning botonEditarSeccion' nipEditarSeccion='".$res[$n]["ID"]."' data-toggle='modal' data-target='#opcEditarSeccion'><i class='fa fa-pencil'></i></button><button class='btn btn-danger botonEliminarSeccion' idBorrarSeccion='".$res[$n]["ID"]."' RESPON='".$_SESSION["NIP"]."'><i class='fa fa-exclamation-circle'></i></button></div>";
                $datosSeccion.= '[
                    "'.$res[$n]["ID"].'",
                    "'.$res[$n]["NOMBRES"].'",
                    "'.$res[$n]["IDENTIFICADOR"].'",
                    "'.$res[$n]["ESTADO_SECCION"].'",';
                $datosSeccion.= '"'.$botonesAcciones.'"
                ],'; 
            }
            $datosSeccion=substr($datosSeccion,0,-1);
            $datosSeccion.= '] }';
        echo $datosSeccion;
    }
}

$mostrar=new VistaSeccion();
$mostrar->mostrarVistaSeccion();
