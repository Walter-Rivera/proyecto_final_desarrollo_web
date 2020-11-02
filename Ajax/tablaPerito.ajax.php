<?php
session_start();
require_once '../Controlador/usuario.controler.php';
require_once '../Modelo/usuario.model.php';

class VistaUsuario{

    static public function mostrarVistaUsuario()
    {
        $campo=null;
        $valor=null;
        $datosUsuario='';
        $res=ContrlUsuario::controlerMostrarUsuarios($campo,$valor);
                /*Capturando atributos del boton de estado */
        $datosUsuario='{
            "data": [';
            for($n=0;$n<count($res);$n++)
            {
                /*estado_boton*/
                $btnActivo="<td><button class='btn btn-success btn-xs botonActivar' NIPusr='".$res[$n]["NIP"]."' RESPON='".$_SESSION["NIP"]."' estadoUsr='4'>Activo</button></td>";
                $btnInactivo="<td><button class='btn btn-danger btn-xs botonActivar' NIPusr='".$res[$n]["NIP"]."' RESPON='".$_SESSION["NIP"]."' estadoUsr='5'>Inactivo</button></td>";
                $botonesAcciones="<div class='btn-group'><button class='btn btn-warning botonEditarUsuario' nipEditarUsuario='".$res[$n]["NIP"]."' data-toggle='modal' data-target='#opcEditarUsuario'><i class='fa fa-pencil'></i></button><button class='btn btn-danger botonEliminarUsuario' nipBorrarUsuario='".$res[$n]["NIP"]."' RESPON='".$_SESSION["NIP"]."'><i class='fa fa-exclamation-circle'></i></button></div>";

                $datosUsuario.= '[
                    "'.$res[$n]["NIP"].'",
                    "'.$res[$n]["NOMBRES"].'",
                    "'.$res[$n]["APELLIDOS"].'",';
                    if($res[$n]["ESTADO_USUARIO"]=="ACTIVO"){
                        $datosUsuario.= '"'.$btnActivo.'",';
                    }
                    else if($res[$n]["ESTADO_USUARIO"]=="INACTIVO")
                    {
                        $datosUsuario.= '"'.$btnInactivo.'",';

                    }
                $datosUsuario.= '"'.$res[$n]["ROL"].'",
                    "'.$res[$n]["ACCESO"].'",
                    "'.$res[$n]["ULTIMO_LOGIN"].'",
                    "'.$botonesAcciones.'"
                ],'; 
            }
            $datosUsuario=substr($datosUsuario,0,-1);
            $datosUsuario.= '] }';
        echo $datosUsuario;
    }
}

$mostrar=new VistaUsuario();
$mostrar->mostrarVistaUsuario();
