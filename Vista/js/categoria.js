'use strict';
/*edición del categoria */
/*este evento se activará cuando le presione click
al boton de edición en nuestra vista*/
$(".botonEditarCategoria").click(function()
{
    
    var IdCategoria = $(this).attr("ID_TIPO_PRODUCTO_MODIFICAR");
    console.log(IdCategoria);
    /*creo una variable para utilizar un formulario de js */
    var info= new FormData();
    /*creamos una variable post con el 
    nip del usurio seleccionado de la tabla */
    info.append("ID_TIPO_PRODUCTO_MODIFICAR",IdCategoria)
    /*conexión a la base de datos por medio 
    de ajax para recuperar los datos  del usuario 
    que vamos a editar y mostrarlos en el modal,
    se creará un archivo en la en la carpeta ajax con este fin */
    $.ajax({
        url:"Ajax/categoria.ajax.php",
        method: "POST",
        data: info,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function (contenido){
            /*mostar el contenido recuperado de la bd
            en los inputs del modal*/
           document.querySelector('#idEditar').value=contenido["ID_TIPO_PRODUCTO"];
           document.querySelector('#nombreEditar').value=contenido["DESCRIPCION_TIPO_PRODUCTO"];

           
        },
    });
}
);




/*evento para detectar cuando se presione el cambio de estado de un usuario*/
$(".botonActivoCategoria").click(function(){

    /*tomando el valor del atributo para capturar el NIP del usuario responsable en modificar */
    var nip_Usuario_Responsable=$(this).attr("RESPON");
    /*tomando el valor del atributo para capturar el id de la categoria a modificar*/
    var idCategoriaModicar=$(this).attr("ID_TPC");
    /*tomando el valor del atributo para capturar el estado del usuario en el sistema
    1-activo
    2-inactivo */
    var estadoTP=$(this).attr("estadoTP");
    /*creamos un formulario de datos para luego trabajarlo en ajax */
    var informacion=new FormData(); 
    /*agregamos los valores de las variables de stado y id usuario */
    informacion.append("ID_TPC",idCategoriaModicar);
    informacion.append("estadoTP",estadoTP);
    informacion.append("RESPON",nip_Usuario_Responsable)
    //console.log(idCategoriaModicar);
    //console.log(estadoUsuario);
    /*petición ajax para la actualización de estado */
    $.ajax({
        url:"Ajax/categoria.ajax.php",
        method: "POST",
        data: informacion,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        /*beforeSend:function()
        {
            console.log(informacion.get("RESPON")+" RESPONSABLE peticion antes de envio");
            console.log(informacion.get("ID_TPC")+" id de la categoria antes de envio");
            console.log(informacion.get("estadoTP")+" ESTADO categoria peticion antes de envio");

        },*/
        success: function(ans){
            
        }});
    /*luego de la actualización del estado, cambiamos las propiedades 
                del botón, según sea su condición 2=inactivo */
    if(estadoTP==1)
    {
        $(this).removeClass('btn-success');
        $(this).addClass('btn-danger');
        $(this).html('Inactivo');
        $(this).attr('estadoTP',2);
        console.log(estadoTP+"rojo");
    }
    else
    {
        /*si es todo lo contrario*/
        $(this).addClass('btn-success');
        $(this).removeClass('btn-danger');
        $(this).html('Activo');
        $(this).attr('estadoTP',1);   
    }
});


/*dar de baja un usuario del sistema*/

$(".botonEliminarCategoria").click(function(){

/*tomando el valor del atributo para capturar el NIP del usuario responsable en dar de baja del sistema */
var nip_Usuario_Responsable=$(this).attr("RESPON");
/*id del usuario a dar de baja del sistema*/
var id_Categoria_baja=$(this).attr("IdBorrarCategoria");

    /*alerta suave para confirmar la baja de la categoria en el sistema*/
    swal.fire({
        title:"¿Está seguro de eliminar la categoria?",
        icon:'warning',
        showCancelButton:true,
        confirmButtonColor:"red",
        cancelButtonColor:"blue",
        cancelButtonText:"Cancelar",
        confirmButtonText:"Eliminar Categoria",
        /*al terminar el proceso de confirmación verifico con una promesa
        si se cumple con lo esperado (dar de baja a al categoria) */
        }).then((resultado)=>{
            if(resultado.value)
            {
                /*redirecciono a la pagina categoria, envio por get el id de la categoria a eliminar y
                el responsable en realizar el proceso */
                window.location="index.php?ruta=categoria&id_Categoria_baja="+id_Categoria_baja+"&nip_Usuario_Responsablecat="+nip_Usuario_Responsable;
            }
        })
    });