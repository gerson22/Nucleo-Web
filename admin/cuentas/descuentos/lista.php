<?php
/**
 * Created by PhpStorm.
 * User: gus_c_000
 * Date: 22/07/14
 * Time: 12:54 PM
 */

$id_modulo = 18; // Cuentas - Descuentos
include_once("../../../includes/validar_admin.php");
include_once("../../../includes/clases/class_lib.php");
include_once("../../../includes/validar_acceso.php");

$ciclos = CicloEscolar::getLista();
?>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Sistema Integral Meze - Materias</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<script src="../../../js/jquery.js" type="text/javascript"></script>
		<script src="../../../js/bootstrap.js" type="text/javascript"></script>
		<script src="../../../plugins/assets/js/appear.min.js" type="text/javascript"></script>
		<script src="../../../plugins/assets/js/animations.js" type="text/javascript"></script>
        <link rel="stylesheet" href="../../../estilo/general.css" />
        <link rel="stylesheet" href="../../../estilo/jquery.dataTables.css" />
        <link rel="stylesheet" href="../../../estilo/formas_mini.css" />
    </head>
    <body>
        <?php include("../../../includes/headerConfig.php"); ?>
        <div id="principal" class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2" >
        	<div id="area_trabajo">
                <h1>Descuentos</h1>

                <div class="form_row_2">
                    <label for="cicloVal" class="form_label">Ciclo escolar</label>
                    <select class="form_input_half" id="cicloVal" name="cicloVal" onchange="reloadDescuentos()" >
                        <?php
                        foreach($ciclos as $ciclo)
                        {
                            echo "<option value=".$ciclo['id_ciclo_escolar']." >".$ciclo['ciclo_escolar']."</option>";
                        }
                        ?>
                    </select>
                </div>

                <table id="tabla_descuentos" class="table" >
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Alumno</th>
                        <th>Concepto</th>
                        <th>Monto</th>
                        <th>Fecha</th>
                        <th>Usuario</th>
                    </tr>
                    </thead>
                    <tbody>
                        <!-- AJAX. DataTables -->
                    </tbody>
                </table>

            </div>

        </div>
    </body>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
    <script src="../../../librerias/jquery.dataTables.min.js" ></script>
    <script src="../../../librerias/jquery.validate.min.js" ></script>
    <script src="../../../librerias/messages_es.js" ></script>
    <script src="../../../librerias/fnAjaxReload.js" ></script>
    <script>
        // Variables globales
        var tabla_descuentos;

        function reloadDescuentos()
        {
            tabla_descuentos.fnReloadAjax();
        }

        function crearDataTable()
        {
            tabla_descuentos = $('#tabla_descuentos').dataTable({
                "oLanguage": {
                    "sLengthMenu": "Mostrar _MENU_ descuentos por página",
                    "sZeroRecords": "No existen descuentos en el ciclo escolar seleccionado",
                    "sInfo": "Mostrando _START_ a _END_ de _TOTAL_ descuentos",
                    "sInfoEmpty": "Mostrando 0 a 0 de 0 descuentos",
                    "sInfoFiltered": "(Encontrados de _MAX_ descuentos)"
                }, //ID, alumno, concepto, monto, fecha, usuario
                "aoColumns": [{"sWidth":"5%"},{"sWidth":"30%"},{"sWidth":"20%"},{"sWidth":"20%"},{"sWidth":"25%"}],
                "bProcessing": true,
                "sAjaxSource": '../../../includes/acciones/cuentas/descuentos/getDescuentosDT.php',
                "fnServerParams": function ( aoData ) {
                    aoData.push(
                        { "name": "ciclo", "value": $("#cicloVal").val() }
                    );
                },
                "iDisplayLength": 25
            });
        }

        // document.ready
        crearDataTable();
    </script>
</html>