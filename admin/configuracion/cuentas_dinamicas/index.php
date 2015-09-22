<?php
/**
 * Created by PhpStorm.
 * User: Yozki
 * Date: 12/22/14
 * Time: 6:26 PM
 */

$id_modulo = 49; // 49 - Cuentas - Dinámicas
include_once("../../../includes/validar_admin.php");
include_once("../../../includes/clases/class_lib.php");
include_once("../../../includes/validar_acceso.php");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Sistema Integral Meze - Configuración: Clubs</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<script src="../../../js/jquery.js" type="text/javascript"></script>
	<script src="../../../js/bootstrap.js" type="text/javascript"></script>
	<script src="../../../plugins/assets/js/appear.min.js" type="text/javascript"></script>
	<script src="../../plugins/assets/js/animations.js" type="text/javascript"></script>
    <link rel="stylesheet" href="../../../estilo/general.css" />
    <link rel="stylesheet" href="../../../estilo/jquery.dataTables.css" />
</head>
<body>
    <?php include("../../../includes/headerConfig.php"); ?>
    	<div id="principal" class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2" style="margin-top:30px;">
        	<div id="area_trabajo">
            <h2>Cuentas dinámicas</h2>

            <a href="nuevo_concepto.php">
                <button class="btn btn-primary">Nuevo</button>
            </a>

            <table id="tabla_cuentas" class="table" >
                <thead>
                    <tr>
                        <th>Concepto</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <!-- AJAX -->
                </tbody>
            </table>
        </div>

    </div>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
<script src="../../../librerias/jquery.dataTables.min.js" ></script>
<script src="../../../librerias/fnAjaxReload.js" ></script>
<script>
    var tabla_cuentas;

    declararDataTable();

    function declararDataTable()
    {
        tabla_cuentas = $('#tabla_cuentas').dataTable({
            "oLanguage": {
                "sLengthMenu": "Mostrar _MENU_ cuentas por página",
                "sZeroRecords": "No existen cuentas",
                "sInfo": "Mostrando _START_ a _END_ de _TOTAL_ cuentas",
                "sInfoEmpty": "Mostrando 0 a 0 de 0 cuentas",
                "sInfoFiltered": "(Encontrados de _MAX_ cuentas)"
            },
            "bProcessing": true,
            "sAjaxSource": '../../../includes/acciones/cuentas/conceptos/get_lista.php',
            "fnServerParams": function (aoData)
            {
                //aoData.push({ "name": "id_ciclo_escolar", "value": id_ciclo_escolar });
            },
            "iDisplayLength": 25
        });
    }
</script>
</body>
</html>