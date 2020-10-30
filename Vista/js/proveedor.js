'use strict';
/*edición del Proveedor */
/*este evento se activará cuando le presione click
al boton de edición en nuestra vista*/
$(".botonEditarProveedor").click(function()
{
    var id_Proveedor = $(this).attr("ID_PROVEEDOR_MODIFICAR");
    
    /*creo una variable para utilizar un formulario de js */
    var info= new FormData();
    /*creamos una variable post con el 
    ID_PROVEEDOR seleccionado de la tabla */
    info.append("ID_PROVEEDOR_MODIFICAR",id_Proveedor)
    /*conexión a la base de datos por medio 
    de ajax para recuperar los datos  del usuario 
    que vamos a editar y mostrarlos en el modal,
    se creará un archivo en la en la carpeta ajax con este fin */
    $.ajax({
        url:"Ajax/proveedor.ajax.php",
        method: "POST",
        data: info,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function (contenido){
            /*mostar el contenido recuperado de la bd
            en los inputs del modal*/
           document.querySelector('#idEditar').value=contenido["ID_PROVEEDOR"];
           document.querySelector('#nombreEditar').value=contenido["NOMBRE"];
           document.querySelector('#direccionEditar').value=contenido["DIRECCION"];
           document.querySelector('#telefonoEditar').value=contenido["TELEFONO"];
           
        },
    });
}
);

/*activar o desactivar proveedor */

$(".botonActivoProveedor").click(function(){


    /*tomando el valor del atributo para capturar el NIP del usuario responsable en modificar */
    var nip_Usuario_Responsable=$(this).attr("RESPON");
    /*id proveedor a modificar*/
    var Id_Proveedor_Modificar=$(this).attr("ID_PROV");
    /*tomando el valor del atributo para capturar el estado del usuario en el sistema
    1-activo
    2-inactivo */
    var estadoProv=$(this).attr("estadoProv");
    /*creamos un formulario de datos para luego trabajarlo en ajax */
    var informacion=new FormData(); 
    /*agregamos los valores de las variables de stado y id usuario */
    informacion.append("ID_PROV",Id_Proveedor_Modificar);
    informacion.append("estadoProv",estadoProv);
    informacion.append("RESPON",nip_Usuario_Responsable);
    //console.log(ID_PROV);
    //console.log(estadoUsuario);
    /*petición ajax para la actualización de estado */
    $.ajax({
        url:"Ajax/proveedor.ajax.php",
        method: "POST",
        data: informacion,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
       /* beforeSend:function()
        {
            console.log(informacion.get("RESPON")+" RESPONSABLE peticion antes de envio");
            console.log(informacion.get("ID_PROV")+" NIP peticion antes de envio");
            console.log(informacion.get("estadoProv")+" ESTADO proveedor peticion antes de envio");

        },*/
        success: function(ans){
        }});
    /*luego de la actualización del estado, cambiamos las propiedades 
                del botón, según sea su condición */
    if(estadoProv==1)
    {
        $(this).removeClass('btn-success');
        $(this).addClass('btn-danger');
        $(this).html('Inactivo');
        $(this).attr('estadoProv',2);
        console.log(estadoProv+"rojo");
    }
    else
    {
        /*si es todo lo contrario*/
        $(this).removeClass('btn-danger');
        $(this).addClass('btn-success');
        $(this).html('Activo');
        $(this).attr('estadoProv',1);
        console.log(estadoProv+"verde");   
    }
    



});





/*dar de baja un usuario del sistema*/

$(".botonEliminarProveedor").click(function(){

    /*tomando el valor del atributo para capturar el NIP del usuario responsable en dar de baja del sistema */
    var nip_Usuario_Responsable=$(this).attr("RESPON");
    /*id del usuario a dar de baja del sistema*/
    var id_Proveedor_baja=$(this).attr("idBorrarProveedor");
    console.log("id del proveedor: "+id_Proveedor_baja);
    console.log("id del usuario responsable:  "+nip_Usuario_Responsable);
    
        /*alerta suave para confirmar la baja del usuario en el sistema*/
        swal.fire({
            title:"¿Está seguro de eliminar el proveedor?",
            icon:'warning',
            showCancelButton:true,
            confirmButtonColor:"red",
            cancelButtonColor:"blue",
            cancelButtonText:"Cancelar",
            confirmButtonText:"Eliminar Proveedor"
            /*al terminar el proceso de confirmación */
            }).then((resultado)=>{
                if(resultado.value)
                {
                    /*redirecciono a la pagina usuario, envio por get el id del usuario a eliminar y
                    el responsable en realizar el proceso */
                    window.location="index.php?ruta=proveedor&id_Proveedor_baja="+id_Proveedor_baja+"&nip_Usuario_Responsabl="+nip_Usuario_Responsable;
                }
            })
        });
    
