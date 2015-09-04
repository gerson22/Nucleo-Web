<?php
/**
 * Created by PhpStorm.
 * User: Gustavo
 * Date: 24/02/14
 * Time: 02:11 PM
 */

$id_modulo = 42; // Uniformes - Compra
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
            var uniforme;
            var cantidad;
            var costo;

            $(document).ready(function ()
            {
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
                                    $("#costoVal").val(uniforme.costo);
                                }
                                else alert("Código inválido");
                            }
                        });
                    }
                });
            }

            function compraClick()
            {
                $("#boton_final_aceptar").prop("disabled", true);
                cantidad    = $("#cantidadVal").val();
                costo       = $("#costoVal").val();
                if(validar())
                {
                    $.ajax({
                        type: "POST",
                        url: "/includes/acciones/uniformes/compra.php",
                        data: "id_uniforme=" + uniforme.id_uniforme + "&id_persona=" + "&cantidad=" + cantidad
                            + "&costo=" + costo,
                        success: function (data)
                        {
                            if(data != '0')
                            {
                                alert("Compra realizada.");
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
                if(cantidad.length < 1 || isNaN(cantidad)){ alert("Debe elegir una cantidad válida"); return false; }
                if(costo.length < 1 || isNaN(costo)){ alert("Debe elegir un precio correcto"); return false; }
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
                            <input type="text" class="form_input" placeholder="Código" id="uniformeVal" />
                        </div>

                        <div class="form_row_2">
                            <label class="form_label">Cantidad</label>
                            <input type="number" class="form_input_half" id="cantidadVal" />
                        </div>

                        <div class="form_row_2">
                            <label class="form_label">Costo</label>
                            <input type="number" class="form_input_half" id="costoVal" />
                        </div>

                        <hr style="border-bottom: medium none; color: #CCCCCC; float: left; height: 1px; width: 100%;" />

                        <div class="form_row">
                            <input id="boton_final_aceptar" class="form_submit btn btn-primary" type="button" onclick="compraClick()" style="margin: auto 45%;" value="Aceptar" >
                        </div>

                    </section>

                </div>

            </div>
    </body>
</html>