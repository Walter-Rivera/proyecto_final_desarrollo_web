'use strict';


/*cargar los datos de la tabla en forma
dinámica con el plugin jquery datatable a fin de 
que no se escriba el tbody en html directamente de la bd
esto merma el tiempo de respuesta al ser demasiados registros,
por ende, voy a relizar esta tarea, (cargar los registros de la bd
    en un archivo ajax, para luego mostarlos en el html*/
/*
$.ajax({
    url: "Ajax/tablaPerito.ajax.php",
    success:(ans)=>{
        console.log("respuesta", ans);
    }
});
*/

$(".VtPeritos").DataTable({
  "ajax":"Ajax/tablaPerito.ajax.php",
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





/*edición del perito */
/*este evento se activará cuando le presione click
al boton de edición en nuestra vista*/
$(".VtPeritos tbody").on("click","button.botonEditarPerito",function()
{
    
    var nipperito = $(this).attr("nipEditarPerito");
    //console.log(nipperito);
    /*creo una variable para utilizar un formulario de js */
    var info= new FormData();
    /*creamos una variable post con el 
    nip del usurio seleccionado de la tabla */
    info.append("nipEditarPerito",nipperito)
    /*conexión a la base de datos por medio 
    de ajax para recuperar los datos  del perito 
    que vamos a editar y mostrarlos en el modal,
    se creará un archivo en la en la carpeta ajax con este fin */
    $.ajax({
        url:"Ajax/perito.ajax.php",
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
           
        },
    });
}
);


/*evento para detectar cuando se presione el cambio de estado de un perito*/
$(".VtPeritos tbody").on("click","button.botonActivarPer",function(){

    /*tomando el valor del atributo para capturar el NIP del perito responsable en modificar */
    var nip_perito_Responsable=$(this).attr("RESPON");
    /*tomando el valor del atributo para capturar el NIP del perito a modificar*/
    var NIPper=$(this).attr("NIPper");
    /*tomando el valor del atributo para capturar el estado del perito en el sistema
    1-activo
    2-inactivo */
    var estadoPer=$(this).attr("estadoPer");
    /*creamos un formulario de datos para luego trabajarlo en ajax */
    var informacion=new FormData(); 
    /*agregamos los valores de las variables de stado y id perito */
    informacion.append("NIPper",NIPper);
    informacion.append("estadoPer",estadoPer);
    informacion.append("RESPON",nip_perito_Responsable)
    //console.log(NIPper);
    //console.log(estadoperito);
    /*petición ajax para la actualización de estado */
    $.ajax({
        url:"Ajax/perito.ajax.php",
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
                window.location="perito";
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
                    window.location="perito";
            });
        }
    
    });
    /*luego de la actualización del estado, cambiamos las propiedades 
                del botón, según sea su condición 2=inactivo */
    if(estadoPer==4)
    {
        $(this).removeClass('btn-success');
        $(this).addClass('btn-danger');
        $(this).html('Inactivo');
        $(this).attr('estadoPer',5);
        console.log(estadoPer+"rojo");
    }
    else
    {
        /*si es todo lo contrario*/
        $(this).addClass('btn-success');
        $(this).removeClass('btn-danger');
        $(this).html('Activo');
        $(this).attr('estadoPer',4);   
    }
});


/*dar de baja un perito del sistema*/

$(".VtPeritos tbody").on("click","button.botonEliminarPerito",function(){

/*tomando el valor del atributo para capturar el NIP del perito responsable en dar de baja del sistema */
var nip_perito_Responsable=$(this).attr("RESPON");
/*id del perito a dar de baja del sistema*/
var id_perito_baja=$(this).attr("nipBorrarPerito");

    /*alerta suave para confirmar la baja del perito en el sistema*/
    swal.fire({
        title:"¿Está seguro de eliminar el perito?",
        icon:'warning',
        showCancelButton:true,
        confirmButtonColor:"red",
        cancelButtonColor:"blue",
        cancelButtonText:"Cancelar",
        confirmButtonText:"Eliminar perito",
        /*al terminar el proceso de confirmación */
        }).then((resultado)=>{
            if(resultado.value)
            {
                /*redirecciono a la pagina perito, envio por get el id del perito a eliminar y
                el responsable en realizar el proceso */
                window.location="index.php?ruta=perito&id_perito_baja="+id_perito_baja+"&nip_perito_Responsable="+nip_perito_Responsable;
            }
        })
    });
