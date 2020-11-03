'use strict';


/*cargar los datos de la tabla en forma
dinámica con el plugin jquery datatable a fin de 
que no se escriba el tbody en html directamente de la bd
esto merma el tiempo de respuesta al ser demasiados registros,
por ende, voy a relizar esta tarea, (cargar los registros de la bd
    en un archivo ajax, para luego mostarlos en el html*/
/*
$.ajax({
    url: "Ajax/tablaSeccion.ajax.php",
    success:(ans)=>{
        console.log("respuesta", ans);
    }
});*/


$(".VtSeccions").DataTable({
  "ajax":"Ajax/tablaSeccion.ajax.php",
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
$(".VtSeccions tbody").on("click","button.botonEditarSeccion",function()
{
    
    var idEditarSeccion = $(this).attr("idEditarSeccion");
    //console.log(idEditarSeccion);
    /*creo una variable para utilizar un formulario de js */
    var info= new FormData();
    /*creamos una variable post con el 
    nip del usurio seleccionado de la tabla */
    info.append("idEditarSeccion",idEditarSeccion)
    /*conexión a la base de datos por medio 
    de ajax para recuperar los datos  del perito 
    que vamos a editar y mostrarlos en el modal,
    se creará un archivo en la en la carpeta ajax con este fin */
    alert(idEditarSeccion);
    $.ajax({
        url:"Ajax/seccion.ajax.php",
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
           document.querySelector('#identificadorEditar').value=contenido["IDENTIFICADOR"];
           
        },
    });
}
);


/*dar de baja un perito del sistema*/

$(".VtSeccions tbody").on("click","button.botonEliminarSeccion",function(){

/*tomando el valor del atributo para capturar el NIP del perito responsable en dar de baja del sistema */
var nip_seccion_Responsable=$(this).attr("RESPON");
/*id del perito a dar de baja del sistema*/
var id_seccion_baja=$(this).attr("idBorrarSeccion");

    /*alerta suave para confirmar la baja del perito en el sistema*/
    swal.fire({
        title:"¿Está seguro de eliminar la sección?",
        icon:'warning',
        showCancelButton:true,
        confirmButtonColor:"red",
        cancelButtonColor:"blue",
        cancelButtonText:"Cancelar",
        confirmButtonText:"Eliminar sección",
        /*al terminar el proceso de confirmación */
        }).then((resultado)=>{
            if(resultado.value)
            {
                /*redirecciono a la pagina perito, envio por get el id del perito a eliminar y
                el responsable en realizar el proceso */
                window.location="index.php?ruta=seccion&id_seccion_baja="+id_seccion_baja+"&nip_seccion_Responsable="+nip_seccion_Responsable;
            }
        })
    });
