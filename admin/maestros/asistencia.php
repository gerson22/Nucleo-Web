<?php
/**
 * Created by PhpStorm.
 * User: Gustavo
 * Date: 19/09/14
 * Time: 05:57 PM
 */

include_once("../../includes/validar_admin.php");
include_once("../../includes/clases/class_lib.php");
?>
<html>
<head>
    <meta charset="utf-8" />
    <title>Sistema Integral Meze - Maestros</title>
    <link rel="stylesheet" href="../../estilo/general.css" />
    <link rel="stylesheet" href="../../estilo/jquery.dataTables.css" />
    <link rel="stylesheet" href="../../estilo/jquery-ui.min.css" />
    <link rel="stylesheet" href="../../estilo/formas_extensas.css" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
		<script src="../../js/jquery.js" type="text/javascript"></script>
		<script src="../../js/bootstrap.js" type="text/javascript"></script>
		<script src="../../plugins/assets/js/appear.min.js" type="text/javascript"></script>
		<script src="../../plugins/assets/js/animations.js" type="text/javascript"></script>
</head>
<body>
    <?php include("../../includes/header.php"); ?>
    <div id="principal" class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2" style="margin-top:20px;">
            <div id="area_trabajo">

            <h1>Asistencias</h1>

            <div class="form_row_3">
                <label class="form_label">Fecha</label>
                <input type="hidden" id="fechaVal" value="" />
                <input type="date" id="fechaDisplay" onchange="reloadTable()" class="form_input" />
            </div>


            <table id="tabla_asistencias" class="table">
                <thead>
                <tr>
                    <th>Docente</th>
                    <th>Hora de entrada</th>
                    <th>Hora de salida</th>
                </tr>
                </thead>
                <tbody>
                    <!-- AJAX -->
                </tbody>
            </table>
        </div>

    </div>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
    <script src="../../librerias/jquery.dataTables.min.js" ></script>
    <script src="../../librerias/fnAjaxReload.js" ></script>
    <script src="/librerias/jquery-ui.min.js"></script>
    <script src="/librerias/jquery.ui.datepicker-es.js"></script>
    <script>
        var tabla_asistencias;

        $(document).ready(function ()
        {
            declararDatePicker();
            declararDataTable();
        });

        function declararDatePicker()
        {
            $("#fechaDisplay").datepicker({
                "dateFormat": "DD, d MM, yy",
                altField: "#fechaVal",
                altFormat: "yy-mm-dd",
                "defaultDate": new Date()
            });

            $("#fechaDisplay").datepicker( "setDate", new Date() );
        }

        function GetTodayDate() {
            var tdate = new Date();
            var dd = tdate.getDate(); //yields day
            var MM = tdate.getMonth(); //yields month
            var yyyy = tdate.getFullYear(); //yields year
            var xxx = yyyy + "-" + (MM+1) + "-" + dd;

            return xxx;
        }

        function declararDataTable()
        {
            tabla_asistencias = $('#tabla_asistencias').dataTable({
                "oLanguage": {
                    "sLengthMenu": "Mostrar _MENU_ asistencias por página",
                    "sZeroRecords": "No existen asistencias",
                    "sInfo": "Mostrando _START_ a _END_ de _TOTAL_ asistencias",
                    "sInfoEmpty": "Mostrando 0 a 0 de 0 asistencias",
                    "sInfoFiltered": "(Encontradas de _MAX_ asistencias)"
                },
                "aoColumns": [
                    {"sWidth":"40%"},{"sWidth":"30%"},{"sWidth":"30%"}
                ],
                "bProcessing": true,
                "sAjaxSource": '../../includes/acciones/asistencias/print_tabla.php',
                "fnServerParams": function (aoData)
                {
                    var fecha = $('#fechaVal').val();
                    aoData.push({ "name": "fecha", "value": fecha });
                },
                "iDisplayLength": 25
            });
        }

        function reloadTable()
        {
            tabla_asistencias.fnReloadAjax();
        }
    </script>
</div>
</body>
</html>