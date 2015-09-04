<?php
include("../../includes/validar_maestro.php");
include_once("../../includes/clases/class_lib.php");
$maestro = new Maestro($_SESSION['id_persona']);
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Sistema Integral Meze - Planeación</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<script src="../../js/jquery.js" type="text/javascript"></script>
		<script src="../../js/bootstrap.js" type="text/javascript"></script>
		<script src="../../plugins/assets/js/appear.min.js" type="text/javascript"></script>
		<script src="../../plugins/assets/js/animations.js" type="text/javascript"></script>
        <link rel="stylesheet" href="../../estilo/general.css" />
        <link rel="stylesheet" href="../../estilo/jquery.dataTables.css" />
        <style>
            #tabla_planeaciones_wrapper
            {
                font-size: 12px;
            }
        </style>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
        <script src="../../librerias/jquery.dataTables.min.js" ></script>
        <script src="../../librerias/fnAjaxReload.js" ></script>
        <script>
            var tabla_planeaciones;

            $(document).ready(function ()
            {
                inicializarDataTable();
            });

            function inicializarDataTable()
            {
                tabla_planeaciones = $('#tabla_planeaciones').dataTable({
                    "oLanguage": {
                        "sLengthMenu": "Mostrar _MENU_ planeaciones por página",
                        "sZeroRecords": "No se encontraron planeaciones",
                        "sInfo": "Mostrando _START_ a _END_ de _TOTAL_ planeaciones",
                        "sInfoEmpty": "Mostrando 0 a 0 de 0 planeaciones",
                        "sInfoFiltered": "(Encontrados de _MAX_ planeaciones)"
                    },
                    "aoColumns": [
                        {"sWidth":"30%"},{"sWidth":"30%"},{"sWidth":"35%"},{"sWidth":"5%"}
                    ],
                    "bProcessing": true,
                    "sAjaxSource": '../../includes/acciones/planeacion/get_planeaciones.php'
                });
            }
        </script>
    </head>
    <body>
            <?php include("../../includes/header.php"); ?>
            <div id="principal" class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2" style="margin-top:20px;">
                 <div id="area_trabajo">
                    <table id="tabla_planeaciones" class="table">
                        <thead>
                            <tr>
                                <th>Grado</th>
                                <th>Materia</th>
                                <th>Maestro</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>

                </div>

            </div>
    </body>
</html>
