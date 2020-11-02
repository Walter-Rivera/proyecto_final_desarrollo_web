<?php
session_start();
require_once '../Controlador/perito.controler.php';
require_once '../Modelo/perito.model.php';

class VistaPerito{

    static public function mostrarVistaPerito()
    {
        $campo=null;
        $valor=null;
        $datosPerito='';
        $res=ContrlPerito::controlerMostrarPeritos($campo,$valor);
                /*Capturando atributos del boton de estado */
        $datosPerito='{
            "data": [';
            for($n=0;$n<count($res);$n++)
            {
                /*estado_boton*/
                $btnActivo="<td><button class='btn btn-success btn-xs botonActivarPer' NIPper='".$res[$n]["NIP"]."' RESPON='".$_SESSION["NIP"]."' estadoPer='4'>Activo</button></td>";
                $btnInactivo="<td><button class='btn btn-danger btn-xs botonActivarPer' NIPper='".$res[$n]["NIP"]."' RESPON='".$_SESSION["NIP"]."' estadoPer='5'>Inactivo</button></td>";
                $botonesAcciones="<div class='btn-group'><button class='btn btn-warning botonEditarPerito' nipEditarPerito='".$res[$n]["NIP"]."' data-toggle='modal' data-target='#opcEditarPerito'><i class='fa fa-pencil'></i></button><button class='btn btn-danger botonEliminarPerito' nipBorrarPerito='".$res[$n]["NIP"]."' RESPON='".$_SESSION["NIP"]."'><i class='fa fa-exclamation-circle'></i></button></div>";

                $datosPerito.= '[
                    "'.$res[$n]["NIP"].'",
                    "'.$res[$n]["NOMBRES"].'",
                    "'.$res[$n]["APELLIDOS"].'",';
                    if($res[$n]["ESTADO_PERITO"]=="ACTIVO"){
                        $datosPerito.= '"'.$btnActivo.'",';
                    }
                    else if($res[$n]["ESTADO_PERITO"]=="INACTIVO")
                    {
                        $datosPerito.= '"'.$btnInactivo.'",';

                    }
                $datosPerito.= '"'.$botonesAcciones.'"
                ],'; 
            }
            $datosPerito=substr($datosPerito,0,-1);
            $datosPerito.= '] }';
        echo $datosPerito;
    }
}

$mostrar=new VistaPerito();
$mostrar->mostrarVistaPerito();
