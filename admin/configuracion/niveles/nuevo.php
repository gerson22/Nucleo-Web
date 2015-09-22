<?php
/**
 * Created by PhpStorm.
 * User: Yozki
 * Date: 11/19/14
 * Time: 4:46 PM
 */

include_once("../../includes/validar_admin.php");
include_once("../../includes/clases/class_lib.php");
$materias = Materia::getLista();
$areas = Area::getLista();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Sistema Integral Meze - Nuevo grado</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<script src="../../../js/jquery.js" type="text/javascript"></script>
	<script src="../../../js/bootstrap.js" type="text/javascript"></script>
	<script src="../../../plugins/assets/js/appear.min.js" type="text/javascript"></script>
	<script src="../../plugins/assets/js/animations.js" type="text/javascript"></script>
    <link rel="stylesheet" href="../../estilo/general.css" />
    <link rel="stylesheet" href="../../estilo/formas.css" />
    <style>
        #areaVal{ padding-top: 5px; }
        #div_materias
        {
            overflow: auto;
        }
        #lista_disponibles
        {
            background-color: #FFFFFF;
            border: 1px solid #CCCCCC;
            float: left;
            height: 250px;
            margin: 0 60px 0 0;
            overflow-x: hidden;
            overflow-y: auto;
            padding: 5px;
            width: 40%;
        }
        #lista_asignadas
        {
            border: 1px solid #CCCCCC;
            float: left;
            margin: 0;
            background-color: white;
            height: 250px;
            padding: 5px;
            width: 40%;
            overflow-x: hidden;
            overflow-y: auto;
        }

        ul, li, ol{ list-style: none; }

        .lista_materias li
        {
            background-color: #EEEEEE;
            border: 1px solid #CECDCD;
            border-radius: 2px;
            margin: 0 0 5px;
            padding: 5px;
            width: 97%;
        }
    </style>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
    <script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
    <script src="/librerias/messages_es.js"></script>
    <script>
        $(document).ready(function ()
        {
            asignarReglasValidacion();
            areaSeleccionada();
            declareSortable();
        });

        function declareSortable()
        {
            $("#lista_disponibles, #lista_asignadas").sortable({
                connectWith: ".lista_materias",
                cancel: ".stuck",
                distance: 30,
                placeholder: "placeholder",
                helper: 'clone',
                receive: function( event, ui ) { materiaAsignada(event, ui); }
            }).disableSelection();
        }

        function materiaAsignada(evento, objeto)
        {

        }

        function asignarReglasValidacion()
        {
            $("#forma_nuevo_grado").validate({
                rules: {
                    "gradoVal": { required: true }
                }
            })
        }

        function insertGrado()
        {
            var grado = $("#gradoVal").val();
            var area = $("#areaVal").val();
            var materias = [];

            $("#lista_asignadas li").each(function(){
                materias.push($(this).attr('data-id_materia') * 1.0);
            });

            if($("#forma_nuevo_grado").valid())
            {
                if(materias.length > 0)
                {
                    if(confirm("¿Seguro que desea agregar el grado?"))
                    {
                        $("#boton_aceptar").attr('disabled','disabled');
                        $.ajax({
                            type: "POST",
                            url: "../../includes/acciones/grados/insert.php",
                            data: "grado=" + grado + "&area=" + area + "&materias=" + JSON.stringify(materias),
                            success: function (data)
                            {
                                alert("Grado agregado");
                                window.location.href = "/admin/grados/index.php";
                            }
                        });
                    }
                }
                else
                {
                    alert("No es posible agergar un grado sin materias asignadas.");
                }
            }
        }

        function areaSeleccionada()
        {
            var id_area = $("#areaVal").val();
            $("#lista_disponibles").html("");
            $("#lista_asignadas").html("");
            $.post("../../includes/acciones/materias/print_lista_materias_area.php", {id_area:id_area}, function (data)
            {
                $("#lista_disponibles").html(data);
            });
        }
    </script>
</head>
<body>
    <?php include("../../../includes/headerConfig.php"); ?>
    <div id="principal" class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2">
        	<div id="area_trabajo">
            <h3>Nuevo grado</h3>
            <form id="forma_nuevo_grado" >
                <div class="form_row_2">
                    <label class="form_label" for="areaVal">Area</label>
                    <select name="areaVal" id="areaVal" class="form_input form-control" onclick="areaSeleccionada();" >
                        <?php
							foreach($areas as $area)
							{
								echo "<option value='".$area['id_area']."' >".$area['area']."</option>";
							}
                        ?>
                    </select>
                </div>
                <div class="form_row_2">
                    <label class="form_label" for="gradoVal">Grado</label>
                    <input type="text" name="gradoVal" id="gradoVal" class="form_input" />
                </div>
            </form>

            <div id="div_materias">
                <label style="float: left; width: 40%; margin: 0 70px 0 0;" >Materias disponibles</label>
                <label style="float: left; width: 40%;" >Materias asignadas</label>
                <ul id="lista_disponibles" class="lista_materias" >
                    <!-- AJAX -->
                </ul>
                <ul id="lista_asignadas" class="lista_materias" >

                </ul>
            </div>

            <div class="form_row">
                <input id="boton_aceptar" class="form_submit form-control" type="button" value="Aceptar" onclick="insertGrado();" />
            </div>

        </div>
    </div>
</body>
</html>
