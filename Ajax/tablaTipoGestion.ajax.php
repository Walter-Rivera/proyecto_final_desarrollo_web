<?php
session_start();
require_once '../Controlador/tipoGestion.controler.php';
require_once '../Modelo/tipoGestion.model.php';

class VistaTipoGestion{

    static public function mostrarVistaTipoGestion()
    {
        $campo=null;
        $valor=null;
        $datosTipoGestion='';
        $res=ContrlTipoGestion::controlerMostrarTipoGestion($campo,$valor);
        
        /*Capturando atributos del boton de estado */
        $datosTipoGestion='{
            "data": [';
            for($n=0;$n<count($res);$n++)
            {
               $botonesAcciones="<div class='btn-group'><button class='btn btn-warning botonEditarTipoGestion' idEditarTipoGestion='".$res[$n]["ID"]."' data-toggle='modal' data-target='#opcEditarTipoGestion'><i class='fa fa-pencil'></i></button><button class='btn btn-danger botonEliminarTipoGestion' idBorrarTipoGestion='".$res[$n]["ID"]."' RESPON='".$_SESSION["NIP"]."'><i class='fa fa-exclamation-circle'></i></button></div>";
                $datosTipoGestion.= '[
                    "'.$res[$n]["ID"].'",
                    "'.$res[$n]["NOMBRES"].'",
                    "'.$res[$n]["CLASE_GESTION"].'",
                    "'.$res[$n]["ESTADO_TIPO_GESTION"].'",';
                $datosTipoGestion.= '"'.$botonesAcciones.'"
                ],'; 
            }
            $datosTipoGestion=substr($datosTipoGestion,0,-1);
            $datosTipoGestion.= '] }';
        echo $datosTipoGestion;
    }
}

$mostrar=new VistaTipoGestion();
$mostrar->mostrarVistaTipoGestion();
