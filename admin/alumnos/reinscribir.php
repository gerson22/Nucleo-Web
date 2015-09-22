<?php
$id_modulo = 7; // Alumnos - Inscribir
include_once("../../includes/validar_admin.php");
include_once("../../includes/clases/class_lib.php");
include_once("../../includes/validar_acceso.php");
$tipos_tutor = Tutor::getTipos();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Sistema Integral Meze - Re-inscribir alumno</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<script src="../../js/jquery.js" type="text/javascript"></script>
		<script src="../../js/bootstrap.js" type="text/javascript"></script>
		<script src="../../plugins/assets/js/appear.min.js" type="text/javascript"></script>
		<script src="../../plugins/assets/js/animations.js" type="text/javascript"></script>
        <link rel="stylesheet" href="../../estilo/general.css" />
        <link rel="stylesheet" href="../../estilo/formas_extensas.css" />
        <link rel="stylesheet" href="../../estilo/buscadorAjax.css" />
        <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
        <style>
            #buscador_alumnos
            {
                width: 450px;
                border: 1px solid #CCC;
                background-color: #FFF;
                display: none;
                position: fixed;
                top: 150px;
                left: 200px;
            }

            #div_nuevo_grupo
            {
                display: none;
                overflow: auto;
                width: 100%;
            }
        </style>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
        <script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
        <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
        <script src="/librerias/messages_es.js"></script>
        <script>
            $(document).ready(function ()
            {
                $(".buscadorAjax").draggable({ handle: ".buscadorAjax_barra" });
            });

            function toggleBuscador()
            {
                $("#buscador_alumnos").fadeIn();
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

            function seleccionarAlumno(id_alumno)
            {
                cargarInfoAlumno(id_alumno);
                $("#buscador_alumnos").fadeOut();
            }

            function cargarInfoAlumno(id_alumno)
            {
                $.ajax({
                    type: "POST",
                    url: "../../includes/acciones/alumnos/getAlumnoJSON.php",
                    data: "id_alumno=" + id_alumno,
                    async: true,
                    success: function (data)
                    {
                        if (data != "error")
                        {
                            var alumno = jQuery.parseJSON(data);
                            $("#id_alumno_val").val(alumno.id_alumno);
                            $("#nombres_val").val(alumno.nombres);
                            $("#paterno_val").val(alumno.apellido_paterno);
                            $("#materno_val").val(alumno.apellido_materno);
                            $("#div_nuevo_grupo").fadeIn();
                        }
                    }
                });
            }

            function loadGrados()
            {
                var id_area = $("#areaVal").val();
                $.post("/includes/acciones/grados/print_select_grados.php", { id_area: id_area }, function (data)
                {
                    $("#gradoVal").html(data);
                    loadGrupos();
                });
            }

            function loadGrupos()
            {
                var id_grado = $("#gradoVal").val();
                $.post("/includes/acciones/grupos/print_select_grupos.php", { id_grado: id_grado }, function (data)
                {
                    $("#grupoVal").html(data);
                });
            }

            function reinscribir()
            {
                if(confirm("¿Desea reinscribir al alumno al grupo seleccionado?"))
                {
                    $("#boton_reinscribir").attr('disabled','disabled');
                    var id_alumno = $("#id_alumno_val").val();
                    var id_grupo = $("#grupoVal").val();
                    $.ajax({
                        type: "POST",
                        url: "/includes/acciones/alumnos/reinscribir.php",
                        data: "id_alumno=" + id_alumno + "&id_grupo=" + id_grupo,
                        success: function (data)
                        {
                            if(data == 1)
                            {
                                alert("Alumno reiscrito");
                                window.location.href = "/admin/alumnos/index.php";
                            }
                        }
                    });
                }
            }
        </script>
    </head>
    <body>
            <?php include("../../includes/header.php"); ?>
           	<div id="principal" class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2">
        		<div id="area_trabajo">
                    <h3>Reinscribir alumno</h3>

                    <button onclick="toggleBuscador();" class="btn btn-warning"><span class="glyphicon glyphicon-search"></span> Buscar</button>
                    <br />

                    <input type="hidden" id="id_alumno_val" />

                    <div class="form_row_3">
                        <label>Nombres</label>
                        <input class="form_input form-control" type="text" id="nombres_val" readonly />
                    </div>
                    <div class="form_row_3">
                        <label>Apellido paterno</label>
                        <input class="form_input form-control" type="text" id="paterno_val" readonly />
                    </div>
                    <div class="form_row_3">
                        <label>Apellido materno</label>
                        <input class="form_input form-control" type="text" id="materno_val" readonly />
                    </div>

                    <div id="div_nuevo_grupo">
                        <div class="form_row_4">
                            <label for="areaVal" class="form_label">Area</label>
                            <select onchange="loadGrados();" required id="areaVal" name="areaVal" class="form_input">
                                <option></option>
                                <option value="1">Kinder</option>
                                <option value="2">Primaria</option>
                                <option value="3">Secundaria</option>
                                <option value="4">Bachillerato</option>
                                <option value="5">Ingenieria</option>
                            </select>
                        </div>
                        <div class="form_row_4">
                            <label for="gradoVal" class="form_label">Grado</label>
                            <select onchange="loadGrupos();" required="" id="gradoVal" name="gradoVal" class="form_input">
                                <!-- AJAX -->
                            </select>
                        </div>
                        <div class="">
                            <label for="grupoVal" class="form_label">Grupo</label>
                            <select id="grupoVal" required="" name="grupoVal" class="form_input">
                                <!-- AJAX -->
                            </select>
                        </div>

                        <br />
                        <button id="boton_reinscribir" onclick="reinscribir();" class="btn btn-primary">Reinscribir</button>
                    </div>

                    <div id="buscador_alumnos" class="buscadorAjax" style="height:350px; box-shadow: 2px 2px 10px #5f5f5f;">
                        <div class="buscadorAjax_barra">
                            <img src='../../media/iconos/icon_close.gif' alt="Cerrar" onclick="$(this).parent().parent().fadeOut()" />
                        </div>
                        <div class="buscadorAjax_top">
                            <label class="buscadorAjax_top_label">Parametro: </label>
                            <input class="buscadorAjax_top_input form-control" type="text" id="parametroVal" />
                            <input class="buscadorAjax_top_boton btn btn-primary" style="margin-top:5px;" type="button" onclick="buscarAlumno()" value="Buscar" />
                        </div>
                        <div class="buscadorAjax_contenedor_tabla table-responsive" >
                            <table id="buscador_alumnos_tabla" class="buscadorAjax_tabla table">
                                <!-- Buscador AJAX -->
                            </table>
                        </div>
                    </div>

                </div>

            </div>
    </body>
</html>