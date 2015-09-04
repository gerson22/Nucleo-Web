<?php
/**
 * Created by PhpStorm.
 * User: Yozki
 * Date: 2/27/14
 * Time: 2:48 PM
 */

$id_modulo = 41; // Uniformes - Lista
include_once("../../includes/clases/class_lib.php");
include_once("../../includes/validar_acceso.php");
include_once("../../includes/validar_admin.php");

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Sistema Integral Meze - Lista de uniformes</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<script src="../../js/jquery.js" type="text/javascript"></script>
		<script src="../../js/bootstrap.js" type="text/javascript"></script>
		<script src="../../plugins/assets/js/appear.min.js" type="text/javascript"></script>
		<script src="../../plugins/assets/js/animations.js" type="text/javascript"></script>
        <link rel="stylesheet" href="../../estilo/general.css" />
        <link rel="stylesheet" href="../../estilo/jquery.dataTables.css" />
        <style>
            #tabla_uniformes_wrapper
            {
                display: inline-block;
                width: 40%;
                float:left;
            }

            #div_informacion
            {
                border: 1px solid #CCCCCC;
                float: right;
                margin: 20px 0;
                padding: 10px;
                width: 50%;
                font-size: 12px;
            }

            #div_informacion.row
            {
                width: 100%;
                overflow: auto;
            }

            .row
            {
                overflow: auto;
                margin: 10px 0;
            }

            .row_half
            {
                float: left;
                margin: 10px 0;
                overflow: auto;
                width: 44.5%;
            }

            .info_label
            {
                display: block;
            }

            .info_input
            {
                border: 1px solid #A4C7E1;
                height: 25px;
                padding-left: 5px;
                width: 80%;
            }

            .info_img
            {
                left: 10px;
                position: relative;
                top: 5px;
                width: 20px;
            }
        </style>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
        <script src="../../librerias/jquery.dataTables.min.js" ></script>
        <script src="../../librerias/fnAjaxReload.js" ></script>
        <script>
            var tabla_uniformes;
            var uniforme;

            $(document).ready(function ()
            {
                declararDataTable();
            });

            function declararDataTable()
            {
                tabla_uniformes = $("#tabla_uniformes").dataTable({
                    "oLanguage": {
                        "sLengthMenu": "Mostrar _MENU_ uniformes por página",
                        "sZeroRecords": "No se encontraron uniformes",
                        "sInfo": "Mostrando _START_ a _END_ de _TOTAL_ uniformes",
                        "sInfoEmpty": "Mostrando 0 a 0 de 0 uniformes",
                        "sInfoFiltered": "(Encontrados de _MAX_ uniformes)"
                    },
                    "aoColumns": [{"sWidth":"95%"},{"sWidth":"5%"}],
                    "bProcessing": true,
                    "sAjaxSource": '../../includes/acciones/uniformes/get_uniformes_data.php'
                    /*"fnServerParams": function ( aoData ) {
                        aoData.push( { "name": "solo_pendientes", "value": solo_pendientes } );
                    }*/
                });
            }

            function reloadTable()
            {
                tabla_uniformes.fnReloadAjax();
            }

            function verDatos(id_uniforme)
            {
                $.post("/includes/acciones/uniformes/get_uniforme_JSON.php", {id_uniforme:id_uniforme}, function (data)
                {
                    uniforme = jQuery.parseJSON(data);
                    $("#informacion_descripcion").val(uniforme.descripcion);
                    $("#informacion_codigo").val(uniforme.codigo);
                    $("#informacion_costo").val(uniforme.costo);
                    $("#informacion_precio").val(uniforme.precio);
                    $("#informacion_inventario").val(uniforme.inventario);

                });
            }

            function modificarDescripcion()
            {
                if(uniforme)
                {
                    var descripcion = prompt("Nueva descripción:");
                    if(descripcion)
                    {
                        $.post("/includes/acciones/uniformes/update_descripcion.php", {id_uniforme:uniforme.id_uniforme, descripcion:descripcion}, function (data)
                        {
                            reloadTable();
                            verDatos(uniforme.id_uniforme);
                        });
                    }
                    else alert("Debe usar una descripción válida.");
                }
            }

            function modificarCodigo()
            {
                if(uniforme)
                {
                    var codigo = prompt("Nuevo código:");
                    if(codigo)
                    {
                        $.post("/includes/acciones/uniformes/update_codigo.php", {id_uniforme:uniforme.id_uniforme, codigo:codigo}, function (data)
                        {
                            reloadTable();
                            verDatos(uniforme.id_uniforme);
                        });
                    }
                    else alert("Debe usar una descripción válida.");
                }
            }

            function modificarCosto()
            {
                if(uniforme)
                {
                    var costo = prompt("Nuevo costo:");
                    if(costo)
                    {
                        $.post("/includes/acciones/uniformes/update_costo.php", {id_uniforme:uniforme.id_uniforme, costo:costo}, function (data)
                        {
                            reloadTable();
                            verDatos(uniforme.id_uniforme);
                        });
                    }
                    else alert("Debe usar una descripción válida.");
                }
            }

            function modificarPrecio()
            {
                if(uniforme)
                {
                    var precio = prompt("Nuevo precio:");
                    if(precio)
                    {
                        $.post("/includes/acciones/uniformes/update_precio.php", {id_uniforme:uniforme.id_uniforme, precio:precio}, function (data)
                        {
                            reloadTable();
                            verDatos(uniforme.id_uniforme);
                        });
                    }
                    else alert("Debe usar una descripción válida.");
                }
            }
        </script>
    </head>
    <body>
            <?php include("../../includes/header.php"); ?>
            <div id="principal" class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2" style="margin-top:20px;">
                 <div id="area_trabajo" class="table-responsive">

                    <table id="tabla_uniformes" class="table">
                        <thead>
                            <tr>
                                <th>Descripción</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- AJAX -->
                        </tbody>
                    </table>

                    <div id="div_informacion">
                        <div class="row">
                            <label class="info_label" >Información</label>
                            <input type="text" id="informacion_descripcion" class="info_input" readonly="readonly" />
                            <img src="/media/iconos/icon_modify.png" alt="mdy" onclick="modificarDescripcion()" class="info_img" />
                        </div>
                        <div class="row">
                            <label class="info_label" >Código</label>
                            <input type="text" id="informacion_codigo" class="info_input" readonly="readonly" />
                            <img src="/media/iconos/icon_modify.png" alt="mdy" onclick="modificarCodigo()" class="info_img" />
                        </div>
                        <div class="row_half">
                            <label class="info_label" >Costo</label>
                            <input type="text" id="informacion_costo" class="info_input" readonly="readonly" />
                            <img src="/media/iconos/icon_modify.png" alt="mdy" onclick="modificarCosto()" class="info_img" />
                        </div>
                        <div class="row_half">
                            <label class="info_label" >Precio</label>
                            <input type="text" id="informacion_precio" class="info_input" readonly="readonly" />
                            <img src="/media/iconos/icon_modify.png" alt="mdy" onclick="modificarPrecio()" class="info_img" />
                        </div>
                        <div class="row_half">
                            <label class="info_label" >Inventario</label>
                            <input type="text" id="informacion_inventario" class="info_input" readonly="readonly" />
                        </div>
                    </div>

                </div>
            </div>
    </body>
</html>