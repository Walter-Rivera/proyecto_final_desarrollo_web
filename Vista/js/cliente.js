'use strict';

/*edición del cliente */
/*este evento se activará cuando le presione click
al boton de edición en nuestra vista*/
$(".botonEditarCliente").click(function()
{
    
    var nipUsuario = $(this).attr("nipEditarCliente");
    /*creo una variable para utilizar un formulario de js */
    var info= new FormData();
    /*creamos una variable post con el 
    nip del cliente seleccionado de la tabla */
    info.append("nipEditarCliente",nipUsuario)
    /*conexión a la base de datos por medio 
    de ajax para recuperar los datos  del usuario 
    que vamos a editar y mostrarlos en el modal,
    se creará un archivo en la en la carpeta ajax con este fin */
    $.ajax({
        url:"Ajax/cliente.ajax.php",
        method: "POST",
        data: info,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function (contenido){
            /*mostar el contenido recuperado de la bd
            en los inputs del modal*/
           document.querySelector('#nipEditar').value=contenido["NIP"];
           document.querySelector('#nombreEditar').value=contenido["NOMBRES"];
           document.querySelector('#apellidoEditar').value=contenido["APELLIDOS"];
           document.querySelector('#correoEditar').value=contenido["CORREO_INSTITUCIONAL"];
           document.querySelector('#optEditarCliente').value=contenido["SECCION"];
           document.querySelector('#optEditarCliente').innerHTML=contenido["SECCION"];
        },
    });
}
);


/*evento para detectar cuando se presione el cambio de estado de un cliente*/
$(".botonActivarCli").click(function(){

    /*tomando el valor del atributo para capturar el NIP del usuario responsable en modificar */
    var nip_Usuario_Responsable=$(this).attr("RESPON");
    /*tomando el valor del atributo para capturar el NIP del cliente a modificar*/
    var NIPcli=$(this).attr("NIPcli");
    /*tomando el valor del atributo para capturar el estado del usuario en el sistema
    1-activo
    2-inactivo */
    var estadoCli=$(this).attr("estadoCli");
    /*creamos un formulario de datos para luego trabajarlo en ajax */
    var informacion=new FormData(); 
    /*agregamos los valores de las variables de stado y id usuario */
    informacion.append("NIPcli",NIPcli);
    informacion.append("estadoCli",estadoCli);
    informacion.append("RESPON",nip_Usuario_Responsable)
    //console.log(NIPcli);
    //console.log(estadoUsuario);
    /*petición ajax para la actualización de estado */
    $.ajax({
        url:"Ajax/cliente.ajax.php",
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
                del botón, según sea su condición 2=inactivo */
    if(estadoCli==1)
    {
        $(this).removeClass('btn-success');
        $(this).addClass('btn-danger');
        $(this).html('Inactivo');
        $(this).attr('estadoCli',2);
    }
    else
    {
        /*si es todo lo contrario*/
        $(this).addClass('btn-success');
        $(this).removeClass('btn-danger');
        $(this).html('Activo');
        $(this).attr('estadoCli',1);   
    }
});


/*dar de baja un usuario del sistema*/

$(".botonEliminarCliente").click(function(){

/*tomando el valor del atributo para capturar el NIP del usuario responsable en dar de baja del sistema */
var nip_Usuario_Responsable=$(this).attr("RESPON");
/*id del usuario a dar de baja del sistema*/
var id_Cliente_baja=$(this).attr("nipBorrarCliente");

    /*alerta suave para confirmar la baja del cliente en el sistema*/
    swal.fire({
        title:"¿Está seguro de eliminar el cliente?",
        icon:'warning',
        showCancelButton:true,
        confirmButtonColor:"red",
        cancelButtonColor:"blue",
        cancelButtonText:"Cancelar",
        confirmButtonText:"Eliminar Cliente",
        /*al terminar el proceso de confirmación */
        }).then((resultado)=>{
            if(resultado.value)
            {
                /*redirecciono a la pagina cliente, envio por get el id del cliente a eliminar y
                el responsable en realizar el proceso */
                window.location="index.php?ruta=cliente&id_Cliente_baja="+id_Cliente_baja+"&nip_Usuario_Responsable="+nip_Usuario_Responsable;
            }
        })
    });
