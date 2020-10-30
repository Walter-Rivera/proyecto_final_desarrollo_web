<?php
session_start();
require_once '../Controlador/producto.controler.php';
require_once '../Modelo/producto.model.php';


class VistaProducto{

    public function mostrarVistaProducto()
    {
        $campo=null;
        $valor=null;
        $datosProducto='';
        
        $res=ContrlProducto::controlerMostrarProducto($campo,$valor);
                /*Capturando atributos del boton de estado */
        $datosProducto='{
            "data": [';
            for($n=0;$n<count($res);$n++)
            {
                /*indicar el estado del stock
                1.- verde, está dentro del margen permitido
                2.- anaranjado, está a un 10% de llegar al stock mínimo
                3.- rojo, el stock llegó al mínimo, se necesita reabastecer*/
                if($res[$n]["EXISTENCIA"]=="REGISTRE APROVISIONAMIENTO")
                {
                    $indicador=$res[$n]["EXISTENCIA"];
                }
                else if($res[$n]["EXISTENCIA"]>$res[$n]["STOCK_MAXIMO"] && ($res[$n]["EXISTENCIA"]> ($res[$n]["STOCK_MINIMO"]+($res[$n]["STOCK_MINIMO"]*0.10))))
                {
                    $indicador="<button class='btn btn-success btn-xs'>".$res[$n]["EXISTENCIA"]."</button>";
                }
                else if(($res[$n]["EXISTENCIA"]<=($res[$n]["STOCK_MINIMO"]+($res[$n]["STOCK_MINIMO"]*0.10))) && ($res[$n]["EXISTENCIA"]> $res[$n]["STOCK_MINIMO"]))
                {
                    $indicador="<button class='btn btn-warning btn-xs'>".$res[$n]["EXISTENCIA"]."</button>";

                }
                else if($res[$n]["EXISTENCIA"]<$res[$n]["STOCK_MINIMO"])
                {
                    $indicador="<button class='btn btn-danger btn-xs'>".$res[$n]["EXISTENCIA"]."</button>";

                }
                /*estado_boton*/
                $btnActivo="<td><button class='btn btn-success btn-xs botonActivar' skuPro='".$res[$n]["SKU"]."' RESPON='".$_SESSION["NIP"]."' estadoPro='1'>Activo</button></td>";
                $btnInactivo="<td><button class='btn btn-danger btn-xs botonActivar' skuPro='".$res[$n]["SKU"]."' RESPON='".$_SESSION["NIP"]."' estadoPro='2'>Inactivo</button></td>";
                $botonesAcciones="<div class='btn-group'><button class='btn btn-warning botonEditarProducto' skuEditarPro='".$res[$n]["SKU"]."' data-toggle='modal' data-target='#opcEditarProducto'><i class='fa fa-pencil'></i></button><button class='btn btn-danger botonEliminarProducto' skuBorrarProducto='".$res[$n]["SKU"]."' RESPON='".$_SESSION["NIP"]."'><i class='fa fa-exclamation-circle'></i></button></div>";

                $datosProducto.= '[
                    "'.$res[$n]["SKU"].'",
                    "'.$res[$n]["DESCRIPCION_PRODUCTO"].'",
                    "'.$res[$n]["PRECIO_COSTO"].'",
                    "'.$res[$n]["PRIORIDAD"].'",
                    "'.$res[$n]["TIPO_PRODUCTO"].'",';
                    if($res[$n]["ESTADO_PRODUCTO"]=="ACTIVO"){
                        $datosProducto.= '"'.$btnActivo.'",';
                    }
                    else if($res[$n]["ESTADO_PRODUCTO"]=="INACTIVO")
                    {
                        $datosProducto.= '"'.$btnInactivo.'",';

                    }
                $datosProducto.= '"'.$res[$n]["STOCK_MINIMO"].'",
                    "'.$res[$n]["STOCK_MAXIMO"].'",
                    "'.$indicador.'",
                    "'.$botonesAcciones.'"
                ],'; 
            }
            $datosProducto=substr($datosProducto,0,-1);
            $datosProducto.= '] }';
        echo $datosProducto;
    }
}

$mostrar=new VistaProducto();
$mostrar->mostrarVistaProducto();
