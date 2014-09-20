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
</head>
<body>
<div id="wrapper">
    <?php include("../../includes/header.php"); ?>
    <div id="content">

        <div id="inner_content">

            <h1>Asistencias</h1>

            <input type="date" id="fechaVal" onchange="reloadTable()" />

            <table id="tabla_asistencias" >
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
            $("#fechaVal").datepicker({
                "dateFormat": "yy-mm-dd",
                "defaultDate": new Date()
            });
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
                }
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