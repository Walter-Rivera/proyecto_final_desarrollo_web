'use strict';


/*cargar los datos de la tabla en forma
dinámica con el plugin jquery datatable a fin de 
que no se escriba el tbody en html directamente de la bd
esto merma el tiempo de respuesta al ser demasiados registros,
por ende, voy a relizar esta tarea, (cargar los registros de la bd
    en un archivo ajax, para luego mostarlos en el html*/

/*$.ajax({
    url: "Ajax/tablaProductos.ajax.php",
    success:(ans)=>{
        console.log("respuesta 2", ans);
    }
});*/
$(".VtProductos").DataTable({
  "ajax":"Ajax/tablaProductos.ajax.php",
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



/*edición del producto */
/*este evento se activará cuando le presione click
al boton de edición en nuestra vista*/
$(".VtProductos tbody").on("click","button.botonEditarProducto",function()
{
    
    var skuProducto = $(this).attr("skuEditarPro");
    console.log("clave del producto  " +skuProducto);
    /*creo una variable para utilizar un formulario de js */
    var info= new FormData();
    /*creamos una variable post con el 
    nip del usurio seleccionado de la tabla */
    info.append("skuEditarPro",skuProducto)
    /*conexión a la base de datos por medio 
    de ajax para recuperar los datos  del producto 
    que vamos a editar y mostrarlos en el modal,
    se creará un archivo en la en la carpeta ajax con este fin */
    $.ajax({
        url:"Ajax/producto.ajax.php",
        method: "POST",
        data: info,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function (contenido){
            /*mostar el contenido recuperado de la bd
            en los inputs del modal*/
            console.log("luego de respuesta ajax stock minimo"+contenido["STOCK_MINIMO"]);
           document.querySelector('#skuEditar').value=contenido["SKU"];
           document.querySelector('#descripcionEditar').value=contenido["DESCRIPCION_PRODUCTO"];
           document.querySelector('#precioCostoEditar').value=contenido["PRECIO_COSTO"];
           document.querySelector('#categoriaEditar').value=contenido["TIPO_PRODUCTO"];
           document.querySelector('#categoriaEditar').innerHTML=contenido["TIPO_PRODUCTO"];
           document.querySelector('#stockMinimoEditar').value=contenido["STOCK_MINIMO"];
           document.querySelector('#stockMaximoEditar').value=contenido["STOCK_MAXIMO"];
           
        },
    });
}
);


/*evento para detectar cuando se presione el cambio de estado de un producto*/
$(".VtProductos tbody").on("click","button.botonActivar",function(){

    /*tomando el valor del atributo para capturar el NIP del producto responsable en modificar */
    var nip_Usuario_Responsable=$(this).attr("RESPON");
    /*tomando el valor del atributo para capturar el SKU del producto a modificar*/
    var skuPro=$(this).attr("skuPro");
    /*tomando el valor del atributo para capturar el estado del producto en el sistema
    1-activo
    2-inactivo */
    var estadoPro=$(this).attr("estadoPro");
    /*creamos un formulario de datos para luego trabajarlo en ajax */
    var informacion=new FormData(); 
    /*agregamos los valores de las variables de stado y id producto */
    informacion.append("skuPro",skuPro);
    informacion.append("estadoPro",estadoPro);
    informacion.append("RESPON",nip_Usuario_Responsable)
    //console.log(skuPro);
    //console.log(estadoUsuario);
    /*petición ajax para la actualización de estado */
    $.ajax({
        url:"Ajax/producto.ajax.php",
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
                icon: "warning",
                title: ans,
                showConfirmButton: false,
                timer: 3000
            }).then((result)=>{
                window.location="producto";
             });
            
        }
    });
    /*luego de la actualización del estado, cambiamos las propiedades 
                del botón, según sea su condición 2=inactivo */
    if(estadoPro==1)
    {
        $(this).removeClass('btn-success');
        $(this).addClass('btn-danger');
        $(this).html('Inactivo');
        $(this).attr('estadoPro',2);
        console.log(estadoPro+"rojo");
    }
    else
    {
        /*si es todo lo contrario*/
        $(this).addClass('btn-success');
        $(this).removeClass('btn-danger');
        $(this).html('Activo');
        $(this).attr('estadoPro',1);   
    }
});


/*dar de baja un producto del sistema*/

$(".VtProductos tbody").on("click","button.botonEliminarProducto",function(){

/*tomando el valor del atributo para capturar el NIP del producto responsable en dar de baja del sistema */
var nip_Usuario_Responsable=$(this).attr("RESPON");
/*id del producto a dar de baja del sistema*/
var id_Producto_baja=$(this).attr("skuBorrarProducto");

    /*alerta suave para confirmar la baja del producto en el sistema*/
    swal.fire({
        title:"¿Está seguro de eliminar el producto?",
        icon:'warning',
        showCancelButton:true,
        confirmButtonColor:"red",
        cancelButtonColor:"blue",
        cancelButtonText:"Cancelar",
        confirmButtonText:"Eliminar Producto",
        /*al terminar el proceso de confirmación */
        }).then((resultado)=>{
            if(resultado.value)
            {
                /*redirecciono a la pagina producto, envio por get el id del producto a eliminar y
                el responsable en realizar el proceso */
                window.location="index.php?ruta=producto&id_Producto_baja="+id_Producto_baja+"&nip_Usuario_Responsable="+nip_Usuario_Responsable;
            }
        })
    });
