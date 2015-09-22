<?php
include_once("../../includes/validar_admin.php");
include_once("../../includes/clases/class_lib.php");
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Tipos de becas</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<script src="../../js/jquery.js" type="text/javascript"></script>
		<script src="../../js/bootstrap.js" type="text/javascript"></script>
		<script src="../../plugins/assets/js/appear.min.js" type="text/javascript"></script>
		<script src="../../plugins/assets/js/animations.js" type="text/javascript"></script>
        <link rel="stylesheet" href="../../estilo/general.css" />
        <link rel="stylesheet" href="../../estilo/fixed_form.css" />
        <style>
            .tabla
            {
                width: 100%;
                border: 1px solid black;
                padding: 10px;
            }
            
            .titulo_tabla
            {
                width: 100%;
                font-size: 12px;
            }
            
            .table_wrapper
            {
                width: 400px;
            }
            
            .seleccionado
            {
                background-color: #BBCBFF;
            }

            tr{ height: 10px; }
        </style>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
        <script src="../../librerias/jquery.dataTables.min.js" ></script>
        <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
        <script>
            var tipo_seleccionado;

            $(document).ready(function ()
            {
                cargarTablaTipos();
                $("#div_nuevo_tipo").draggable({ handle: "#div_nuevo_tipo_bar" });
                $("#div_nuevo_subtipo").draggable({ handle: "#div_nuevo_subtipo_bar" });
            });

            function cargarTablaTipos()
            {
                $.ajax({
                    type: "POST",
                    url: "/includes/acciones/becas/print_tipos.php",
                    data: "",
                    success: function (data)
                    {
                        $("#tabla_tipos").html(data);
                    }
                });
            }

            function agregarTipo()
            {
                $("#boton_nuevo_tipo").attr('disabled', 'disabled');
                var tipoVal = $("#tipoVal").val();
                var subtipoVal = $("#subtipoVal").val();
                if (tipoVal.length < 1 || subtipoVal.length < 1)
                {
                    alert("Debe introducir un valor");
                    $("#boton_nuevo_tipo").removeAttr('disabled');
                }
                else
                {
                    if (confirm("¿Seguro que desea agregar el tipo de beca: '" + tipoVal + "'?"))
                    {
                        $.ajax({
                            type: "POST",
                            url: "/includes/acciones/becas/insert_tipo.php",
                            data: "tipoVal=" + tipoVal + "&subtipoVal=" + subtipoVal,
                            success: function (data)
                            {
                                cargarTablaTipos();
                                $('#div_nuevo_tipo').fadeOut();
                                $("#boton_nuevo_tipo").removeAttr('disabled');
                            }
                        });
                    }
                }
            }

            function agregarSubTipo()
            {
                $("#boton_nuevo_subtipo").attr('disabled', 'disabled');
                var id_tipo_beca = $("#id_tipo_becaVal").val();
                var subtipo = $("#subtipo2Val").val();

                if (subtipo.length < 1)
                {
                    alert("Debe introducir un valor");
                    $("#boton_nuevo_subtipo").removeAttr('disabled');
                }
                else
                {
                    if (confirm("¿Seguro que desea agregar el subtipo de beca: '" + subtipo + "'?"))
                    {
                        $.ajax({
                            type: "POST",
                            url: "/includes/acciones/becas/insert_subtipo.php",
                            data: "id_tipo=" + id_tipo_beca + "&subtipoVal=" + subtipo,
                            success: function (data)
                            {
                                cargarTablaTipos();
                                $('#div_nuevo_subtipo').fadeOut();
                                $("#boton_nuevo_subtipo").removeAttr('disabled');
                            }
                        });
                    }
                }
            }

            function seleccionarTipo(id_tipo, caller)
            {
                tipo_seleccionado = id_tipo;
                $("#tabla_tipos tr").each(function ()
                {
                    $(this).removeAttr('class');
                });
                $(caller).attr('class', 'seleccionado');
                $.ajax({
                    type: "POST",
                    url: "/includes/acciones/becas/print_subtipos.php",
                    data: "id_tipo_beca=" + id_tipo,
                    success: function (data)
                    {
                        $("#tabla_subtipos").html(data);
                    }
                });
            }

            function mostrarNuevoSubtipo()
            {
                $.ajax({
                    type: "POST",
                    url: "/includes/acciones/becas/print_select_tipos.php",
                    data: "",
                    success: function (data)
                    {
                        $("#id_tipo_becaVal").html(data);
                        $('#div_nuevo_subtipo').fadeIn();
                        $("#id_tipo_becaVal").val(tipo_seleccionado);
                    }
                });
            }
        </script>
    </head>
    <body>
            <?php include("../../includes/header.php"); ?>
            <div id="principal" class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2" style="margin-top:30px;">
        		<div id="area_trabajo">
                <h1>Tipos de becas</h1>

                <div class="table_wrapper" style="float: left;">
                    <div class="titulo_tabla table">Tipo de beca</div>
                    <table class="tabla" id="tabla_tipos">
                        <!-- AJAX -->
                    </table>
                    <img src="/media/iconos/icon_add.png" ALT="nuevo" style="float: right;" onclick="$('#div_nuevo_tipo').fadeIn();" />
                </div>

                <div class="table_wrapper" style="float: right;" >
                    <div class="titulo_tabla table" >Subtipos</div>
                    <table class="tabla" id="tabla_subtipos">
                        <tr><td>Seleccione un tipo de beca</td></tr>
                        <!-- AJAX -->
                    </table>
                    <img src="/media/iconos/icon_add.png" ALT="nuevo" style="float: right;" onclick="mostrarNuevoSubtipo();" />
                </div>

                <div id="div_nuevo_tipo" class="fixed_form" style="box-shadow: 1px 1px 5px #5f5f5f;">
                    <div id="div_nuevo_tipo_bar" class="fixed_form_handle table" >
                        <img src="/media/iconos/icon_close.gif" alt="X" onclick="$(this).parent().parent().fadeOut();" />
                    </div>
                    <div class="fixed_form_content" >
                        <div class="fixed_form_row">
                            <label>Tipo</label>
                            <input type="text" class="fixed_form_value" id="tipoVal" />
                        </div>
                        <div class="fixed_form_row">
                            <label>Debe agregar al menos 1 sub-tipo</label>
                            <input type="text" class="fixed_form_value" id="subtipoVal" placeholder="General" />
                        </div>
                        <div class="fixed_for_row">
                            <input id="boton_nuevo_tipo" type="button" value="Aceptar" class="fixed_form_button" onclick="agregarTipo();" />
                        </div>
                    </div>
                </div>

                <div id="div_nuevo_subtipo" class="fixed_form" style="box-shadow: 1px 1px 5px #5f5f5f;" >
                    <div id="div_nuevo_subtipo_bar" class="fixed_form_handle" >
                        <img src="/media/iconos/icon_close.gif" alt="X" onclick="$(this).parent().parent().fadeOut();" />
                    </div>
                    <div class="fixed_form_content" >
                        <div class="fixed_form_row">
                            <label>Tipo de beca</label>
                            <select class="fixed_form_value" id="id_tipo_becaVal" >
                                <!-- AJAX -->
                            </select>
                        </div>
                        <div class="fixed_form_row">
                            <label>Subtipo</label>
                            <input type="text" class="fixed_form_value" id="subtipo2Val" />
                        </div>
                        <div class="fixed_for_row">
                            <input id="boton_nuevo_subtipo" type="button" value="Aceptar" class="fixed_form_button" onclick="agregarSubTipo();" />
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </body>
</html>
