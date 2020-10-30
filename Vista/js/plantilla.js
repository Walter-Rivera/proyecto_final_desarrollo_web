'use strict';

/*
    esta función permite que los elementos se desplieguen del menú principal en forma de árbol (descendnete)
*/
$('.sidebar-menu').tree();

/*activar el plugin dataTable*/
$(".Tabla").DataTable(

    /*cambiar el idioma de los elementos del dom que administra el 
    plugin a español*/
    {
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
    }
);