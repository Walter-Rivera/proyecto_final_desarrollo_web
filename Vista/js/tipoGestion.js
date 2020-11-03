'use strict';


/*cargar los datos de la tabla en forma
dinámica con el plugin jquery datatable a fin de 
que no se escriba el tbody en html directamente de la bd
esto merma el tiempo de respuesta al ser demasiados registros,
por ende, voy a relizar esta tarea, (cargar los registros de la bd
    en un archivo ajax, para luego mostarlos en el html*/
/*
$.ajax({
    url: "Ajax/tablaTipoGestion.ajax.php",
    success:(ans)=>{
        console.log("respuesta", ans);
    }
});*/


$(".VtTipoGestion").DataTable({
  "ajax":"Ajax/tablaTipoGestion.ajax.php",
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
$(".VtTipoGestion tbody").on("click","button.botonEditarTipoGestion",function()
{
    
    var idEditarTipoGestion = $(this).attr("idEditarTipoGestion");
    //console.log(idEditarTipoGestion);
    /*creo una variable para utilizar un formulario de js */
    var info= new FormData();
    /*creamos una variable post con el 
    nip del usurio seleccionado de la tabla */
    info.append("idEditarTipoGestion",idEditarTipoGestion)
    /*conexión a la base de datos por medio 
    de ajax para recuperar los datos  del perito 
    que vamos a editar y mostrarlos en el modal,
    se creará un archivo en la en la carpeta ajax con este fin */
    $.ajax({
        url:"Ajax/tipoGestion.ajax.php",
        method: "POST",
        data: info,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function (contenido){
            /*mostar el contenido recuperado de la bd
            en los inputs del modal*/
           document.querySelector('#idEditar').value=contenido["ID"];
           document.querySelector('#nombreEditar').value=contenido["NOMBRES"];
           document.querySelector('#optEditarTipoGestion').value=contenido["CLASE_GESTION"];
           document.querySelector('#optEditarTipoGestion').innerHTML=contenido["CLASE_GESTION"];
           
        },
    });
}
);


/*dar de baja un perito del sistema*/

$(".VtTipoGestion tbody").on("click","button.botonEliminarTipoGestion",function(){

/*tomando el valor del atributo para capturar el NIP del perito responsable en dar de baja del sistema */
var nip_TipoGestion_Responsable=$(this).attr("RESPON");
/*id del perito a dar de baja del sistema*/
var id_TipoGestion_baja=$(this).attr("idBorrarTipoGestion");

    /*alerta suave para confirmar la baja del perito en el sistema*/
    swal.fire({
        title:"¿Está seguro de eliminar el tipo de Gestion?",
        icon:'warning',
        showCancelButton:true,
        confirmButtonColor:"red",
        cancelButtonColor:"blue",
        cancelButtonText:"Cancelar",
        confirmButtonText:"Eliminar TipoGestion",
        /*al terminar el proceso de confirmación */
        }).then((resultado)=>{
            if(resultado.value)
            {
                /*redirecciono a la pagina perito, envio por get el id del perito a eliminar y
                el responsable en realizar el proceso */
                window.location="index.php?ruta=tipoGestion&id_TipoGestion_baja="+id_TipoGestion_baja+"&nip_TipoGestion_Responsable="+nip_TipoGestion_Responsable;
            }
        })
    });
