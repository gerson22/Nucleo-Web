<?php
/**
 * Created by PhpStorm.
 * User: Gustavo
 * Date: 24/02/14
 * Time: 02:11 PM
 */

$id_modulo = 40; // Uniformes - Venta
include_once("../../includes/clases/class_lib.php");
include_once("../../includes/validar_acceso.php");
include_once("../../includes/validar_admin.php");
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Sistema Integral Meze - Vender uniforme</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<script src="../../js/jquery.js" type="text/javascript"></script>
		<script src="../../js/bootstrap.js" type="text/javascript"></script>
		<script src="../../plugins/assets/js/appear.min.js" type="text/javascript"></script>
		<script src="../../plugins/assets/js/animations.js" type="text/javascript"></script>
        <link rel="stylesheet" href="../../estilo/general.css" />
        <link rel="stylesheet" href="../../estilo/formas_mini.css" />
        <link rel="stylesheet" href="../../estilo/buscadorAjax.css" />
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
        <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
        <script>
            var alumno;
            var uniforme;
            var cantidad;
            var precio;
            var id_area;

            $(document).ready(function ()
            {
                $(".buscadorAjax").draggable({ handle: ".buscadorAjax_barra", containment: "window" });
                listenerCodigo();
            });

            function listenerCodigo()
            {
                $("#uniformeVal").keypress(function(e)
                {
                    if(e.which == 13)
                    {
                        var codigo = $("#uniformeVal").val();
                        $.post("/includes/acciones/uniformes/get_uniforme.php", {codigo:codigo}, function (data)
                        {
                            if(data != "error")
                            {
                                if(data != "código inválido")
                                {
                                    uniforme = jQuery.parseJSON(data);
                                    $("#uniformeVal").val(uniforme.descripcion);
                                    $("#precioVal").val(uniforme.precio);
                                }
                                else alert("Código inválido");
                            }
                        });
                    }
                });
            }

            function seleccionarAlumno(id_alumno)
            {
                $.ajax({
                    type: "POST",
                    url: "/includes/acciones/alumnos/getAlumnoJSON.php",
                    data: "id_alumno=" + id_alumno,
                    async: false,
                    success: function (data)
                    {
                        if (data != "error")
                        {
                            alumno = jQuery.parseJSON(data);
                            $("#alumnoVal").val(alumno.nombres + " " + alumno.apellido_paterno + " " + alumno.apellido_materno);
                        }
                    }
                });
                $("#buscador_alumnos").fadeOut();
            }

            function buscarAlumno()
            {
                $.ajax({
                    type: "POST",
                    url: "../../includes/acciones/alumnos/buscar_alumnos.php",
                    data: "parametro=" + $("#parametroVal").val(),
                    success: function (data)
                    {
                        $("#buscador_alumnos_tabla").html(data);
                    }
                });
            }

            function pagoClick()
            {
                $("#boton_final_aceptar").prop("disabled", true);
                cantidad    = $("#cantidadVal").val();
                id_area     = $("#areaVal").val();
                precio  = $("#precioVal").val();
                if(validar())
                {
                    $.ajax({
                        type: "POST",
                        url: "/includes/acciones/uniformes/venta.php",
                        data: "id_uniforme=" + uniforme.id_uniforme + "&id_persona=" + alumno.id_persona
                            + "&cantidad=" + cantidad + "&precio=" + precio + "&id_area=" + id_area,
                        success: function (data)
                        {
                            if(data != '0')
                            {
                                alert("Venta realizada.");
                            }
                            else
                            {
                                alert("Error.");
                                $("#boton_final_aceptar").prop("disabled", false);
                            }
                        }
                    });
                }
                else $("#boton_final_aceptar").prop("disabled", false);
            }

            function validar()
            {
                if(!uniforme){ alert("Debe elegir un uniforme."); return false; }
                if(!alumno){ alert("Debe elegir un alumno."); return false; }
                if(cantidad.length < 1 || isNaN(cantidad)){ alert("Debe elegir una cantidad válida"); return false; }
                if(precio.length < 1 || isNaN(precio)){ alert("Debe elegir un precio correcto"); return false; }
                if(id_area.length < 1 || isNaN(id_area)){ alert("Debe seleccionar un área"); return false; }
                return true;
            }
        </script>
    </head>
    <body>
            <?php include("../../includes/header.php"); ?>
            <div id="principal" class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2" style="margin-top:20px;">
                 <div id="area_trabajo">
                    <section>

                        <div class="form_row_2" >
                            <label class="form_label">Uniforme</label>
                            <input type="text" class="form_input form-control" placeholder="Código" id="uniformeVal" />
                        </div>

                        <div class="form_row_2">
                            <label class="form_label">Cantidad</label>
                            <input type="number" class="form_input_half form-control" id="cantidadVal" />
                        </div>

                        <div class="form_row_2" >
                            <label class="form_label">Alumno</label>
                            <input type="text" id="alumnoVal" class="form_input form-control" placeholder="Doble click" ondblclick="$('#buscador_alumnos').fadeIn();" />
                        </div>

                        <div class="form_row_2">
                            <label class="form_label">Precio</label>
                            <input type="number" class="form_input_half form-control" id="precioVal" />
                        </div>

                        <div class="form_row_3" >
                            <input type="hidden" id="id_alumnoVal" class="form_label" />
                            <label class="form_label">Area</label>
                            <select type="text" id="areaVal" class="form_select form-control" >
                                <option value="1" >Kinder</option>
                                <option value="2" >Primaria</option>
                                <option value="3" >Secundaria</option>
                                <option value="4" >Bachillerato</option>
                                <option value="5" >Ingenieria</option>
                            </select>
                        </div>

                        <hr style="border-bottom: medium none; color: #CCCCCC; float: left; height: 1px; width: 100%;" />

                        <div class="form_row">
                            <input id="boton_final_aceptar" class="form_submit btn btn-primary" type="button" onclick="pagoClick()" style="margin: auto 45%;" value="Aceptar" >
                        </div>

                    </section>

                </div>

                <div class="buscadorAjax" id="buscador_alumnos" style="box-shadow: 2px 2px 5px #5f5f5f;" >
                    <div class="buscadorAjax_barra">
                        <img onclick="$(this).parent(0).parent(0).fadeOut()" alt="Cerrar" src="../../../media/iconos/icon_close.gif">
                    </div>
                    <div class="buscadorAjax_top">
                        <label class="buscadorAjax_top_label">Parametro: </label>
                        <input type="text" id="parametroVal" class="buscadorAjax_top_input">
                        <input type="button" value="Buscar" onclick="buscarAlumno()" class="buscadorAjax_top_boton">
                    </div>
                    <div class="buscadorAjax_contenedor_tabla">
                        <table class="buscadorAjax_tabla table" id="buscador_alumnos_tabla">
                            <!-- Buscador AJAX -->
                        </table>
                    </div>
                </div>

            </div>
    </body>
</html>