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
    <link rel="stylesheet" href="../../../estilo/general.css" />
    <link rel="stylesheet" href="../../../estilo/jquery.dataTables.css" />
</head>
<body>
<div id="wrapper">
    <?php include("../../../includes/header.php"); ?>
    <div id="content">

        <div id="inner_content">

            <h2>Cuentas dinámicas</h2>

            <a href="nuevo_concepto.php">
                <button>Nuevo</button>
            </a>

            <table id="tabla_cuentas" >
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