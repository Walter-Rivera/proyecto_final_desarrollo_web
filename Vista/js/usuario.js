'use strict';


/*cargar los datos de la tabla en forma
dinámica con el plugin jquery datatable a fin de 
que no se escriba el tbody en html directamente de la bd
esto merma el tiempo de respuesta al ser demasiados registros,
por ende, voy a relizar esta tarea, (cargar los registros de la bd
    en un archivo ajax, para luego mostarlos en el html*/
/*
$.ajax({
    url: "Ajax/tablaUsuario.ajax.php",
    success:(ans)=>{
        console.log("respuesta", ans);
    }
});*/
$(".VtUsuarios").DataTable({
  "ajax":"Ajax/tablaUsuario.ajax.php",
  "language":{
        "sProcessing":  "Procesando",
        "sLengthMenu":  "Mostrar_MENU_registros",
        "sZeroRecords": "No existen resultados con los datos propocionados",
        "sEmptyTable": "Ningún elemento Registrado",
        "sInfo": "Visualizando información del _START_ al _END_ Registro de un total de _TOTAL_",
        "sSearch":      "Búsqueda",
        "sInfoEmpty": "Actualmente no hay ningún registro en el sistema",
        "sInfoFiltered": "Mostrando de un total de _MAX_ registros",
        "sUrl": " ",
        "sInfoThousands": ",",
        "sLoadingRecords": "Cargando...",
        "oPaginate": 
    {
        "sFirst": "Primer",
        "sLast": "Último",
        "sNext": "Siguiente",
        "sPrevious": "Anterior"
    },
    "oAria":{
        "sSortAscending":   "Orden ascendente",
        "sSortDescending": "Orden descendente"
    }
    }

});






/*edición del usuario */
/*este evento se activará cuando le presione click
al boton de edición en nuestra vista*/
$(".VtUsuarios tbody").on("click","button.botonEditarUsuario",function()
{
    
    var nipUsuario = $(this).attr("nipEditarUsuario");
    console.log(nipUsuario);
    /*creo una variable para utilizar un formulario de js */
    var info= new FormData();
    /*creamos una variable post con el 
    nip del usurio seleccionado de la tabla */
    info.append("nipEditarUsuario",nipUsuario)
    /*conexión a la base de datos por medio 
    de ajax para recuperar los datos  del usuario 
    que vamos a editar y mostrarlos en el modal,
    se creará un archivo en la en la carpeta ajax con este fin */
    $.ajax({
        url:"Ajax/usuario.ajax.php",
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
           document.querySelector('#optEditarRol').value=contenido["ROL"];
           document.querySelector('#optEditarRol').innerHTML=contenido["ROL"];
           document.querySelector('#contraActual').value=contenido["ACCESO"];
           
        },
    });
}
);


/*evento para detectar cuando se presione el cambio de estado de un usuario*/
$(".VtUsuarios tbody").on("click","button.botonActivar",function(){

    /*tomando el valor del atributo para capturar el NIP del usuario responsable en modificar */
    var nip_Usuario_Responsable=$(this).attr("RESPON");
    /*tomando el valor del atributo para capturar el NIP del usuario a modificar*/
    var NIPusr=$(this).attr("NIPusr");
    /*tomando el valor del atributo para capturar el estado del usuario en el sistema
    1-activo
    2-inactivo */
    var estadoUsr=$(this).attr("estadoUsr");
    /*creamos un formulario de datos para luego trabajarlo en ajax */
    var informacion=new FormData(); 
    /*agregamos los valores de las variables de stado y id usuario */
    informacion.append("NIPusr",NIPusr);
    informacion.append("estadoUsr",estadoUsr);
    informacion.append("RESPON",nip_Usuario_Responsable)
    //console.log(NIPusr);
    //console.log(estadoUsuario);
    /*petición ajax para la actualización de estado */
    $.ajax({
        url:"Ajax/usuario.ajax.php",
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
            swal.fire({
                type:"success",
                icon: "success",
                title: "Estado Actualizado exitosamente",
                showConfirmButton: false,
                timer: 2000
            }).then((result)=>{
                window.location="usuario";
             });
            
        },
        error: function(xhr,status){
            swal.fire({
                type:"error",
                icon: "error",
                title: "¡Usted no tiene privilegios!",
                showConfirmButton: false,
                timer: 2000
            }).then((result)=>{
                    window.location="usuario";
            });
        }
    
    });
    /*luego de la actualización del estado, cambiamos las propiedades 
                del botón, según sea su condición 2=inactivo */
    if(estadoUsr==1)
    {
        $(this).removeClass('btn-success');
        $(this).addClass('btn-danger');
        $(this).html('Inactivo');
        $(this).attr('estadoUsr',5);
        console.log(estadoUsr+"rojo");
    }
    else
    {
        /*si es todo lo contrario*/
        $(this).addClass('btn-success');
        $(this).removeClass('btn-danger');
        $(this).html('Activo');
        $(this).attr('estadoUsr',4);   
    }
});


/*dar de baja un usuario del sistema*/

$(".VtUsuarios tbody").on("click","button.botonEliminarUsuario",function(){

/*tomando el valor del atributo para capturar el NIP del usuario responsable en dar de baja del sistema */
var nip_Usuario_Responsable=$(this).attr("RESPON");
/*id del usuario a dar de baja del sistema*/
var id_Usuario_baja=$(this).attr("nipBorrarUsuario");

    /*alerta suave para confirmar la baja del usuario en el sistema*/
    swal.fire({
        title:"¿Está seguro de eliminar el usuario?",
        icon:'warning',
        showCancelButton:true,
        confirmButtonColor:"red",
        cancelButtonColor:"blue",
        cancelButtonText:"Cancelar",
        confirmButtonText:"Eliminar Usuario",
        /*al terminar el proceso de confirmación */
        }).then((resultado)=>{
            if(resultado.value)
            {
                /*redirecciono a la pagina usuario, envio por get el id del usuario a eliminar y
                el responsable en realizar el proceso */
                window.location="index.php?ruta=usuario&id_Usuario_baja="+id_Usuario_baja+"&nip_Usuario_Responsable="+nip_Usuario_Responsable;
            }
        })
    });

/*
$.ajax({
    url: "Ajax/listadoRoles.ajax.php",
    success:(ans)=>{
        console.log("respuesta", ans);
    }
});
*/

var opcionRol= document.querySelector('#rolNuevo');
opcionRol.addEventListener("click",function alternativa(ev){
    $.ajax({
        url:"Ajax/listadoRoles.ajax.php",
        method: "POST",
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function(ans){
          for (let i in ans) {
            for (let j in ans[i]) {
                var tagnuevo=document.createElement("option");
                tagnuevo.value=ans[i][j];
                tagnuevo.innerHTML=ans[i][j];
                opcionRol.append(tagnuevo);
                //console.log(ans[i][j]);
            }
          }
        },
        error: function(xhr,status){
            swal.fire({
                type:"error",
                icon: "error",
                title: "¡error!",
                showConfirmButton: false,
                timer: 2000
            }).then((result)=>{
                    window.location="usuario";
            });
        }});
    /*este evento solo se ejecutará cuando se de una vez click */    
    ev.target.removeEventListener(ev.type, alternativa);
});




var opcionRolEditar= document.querySelector('#rolEditar');
opcionRolEditar.addEventListener("click",function alternativa(ev){
    $.ajax({
        url:"Ajax/listadoRoles.ajax.php",
        method: "POST",
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function(ans){
          for (let i in ans) {
            for (let j in ans[i]) {
                var tagnuevo=document.createElement("option");
                tagnuevo.value=ans[i][j];
                tagnuevo.innerHTML=ans[i][j];
                opcionRolEditar.append(tagnuevo);
                //console.log(ans[i][j]);
            }
          }
        },
        error: function(xhr,status){
            swal.fire({
                type:"error",
                icon: "error",
                title: "¡error!",
                showConfirmButton: false,
                timer: 2000
            }).then((result)=>{
                    window.location="usuario";
            });
        }});
    /*este evento solo se ejecutará cuando se de una vez click */    
    ev.target.removeEventListener(ev.type, alternativa);
});