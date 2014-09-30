<?php
/**
 * Created by PhpStorm.
 * User: Gustavo
 * Date: 30/06/14
 * Time: 12:31 PM
 */

$id_modulo = 43; // Cuentas - Re-imprimir recibo
include_once("../../includes/validar_admin.php");
include_once("../../includes/clases/class_lib.php");
include_once("../../includes/validar_acceso.php");

$ciclos = CicloEscolar::getLista();
?>
<html>
<head>
    <meta charset="utf-8" />
    <title>Sistema Integral Meze - Recibos</title>
    <link rel="stylesheet" href="../../estilo/general.css" />
    <link rel="stylesheet" href="../../estilo/jquery.dataTables.css" />
    <link rel="stylesheet" href="../../estilo/formas.css" />
    <style>
        #cicloVal
        {
            padding-top: 10px;
        }
    </style>
</head>
<body>
<div id="wrapper">
    <?php include("../../includes/header.php"); ?>
    <div id="content">
        <div id="inner_content">

            <!------------------------ FILTROS --------------------------------->
            <div id="div_filtros">
                <div class="form_row_3">
                    <label class="form_label"># Recibo</label>
                    <input type="text" class="form_input" id="no_reciboVal">
                </div>
                <div class="form_row_3">
                    <label class="form_label">Alumno</label>
                    <input type="text" class="form_input" id="alumnoVal">
                </div>
                <div class="form_row_3">
                    <label class="form_label" >Ciclo escolar</label>
                    <select id="cicloVal" class="form_input" >
                        <?php
                        foreach($ciclos as $ciclo)
                        {
                            echo "<option value=".$ciclo['id_ciclo_escolar']." >".$ciclo['ciclo_escolar']."</option>";
                        }
                        ?>
                    </select>
                </div>
                <button onclick="buscarRecibos()">Buscar</button>
            </div>
            <!------------------------ FILTROS --------------------------------->

            <!----------------------- DATATABLE -------------------------------->
            <table id="tabla_recibos">
                <thead>
                <tr>
                    <th style="width: 5%" >Folio</th>
                    <th style="width: 30%" >Fecha</th>
                    <th style="width: 40%" >Alumno</th>
                    <th style="width: 20%" >Total</th>
                    <th style="width: 5%" >Imprimir</th>
                </tr>
                </thead>
                <tbody>
                    <!-- AJAX -->
                </tbody>
            </table>
            <!----------------------- DATATABLE -------------------------------->

        </div>
    </div>
</div>
</body>

<!------------------------ JAVASCRIPT --------------------------->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
<script src="../../librerias/jquery.dataTables.min.js" ></script>
<script src="../../librerias/fnAjaxReload.js" ></script>
<script>
    var tabla_recibos;

    declararDataTable();

    function buscarRecibos()
    {
        console.log("Buscando recibos...");
        tabla_recibos.fnReloadAjax();
    }

    function declararDataTable()
    {
        tabla_recibos = $('#tabla_recibos').dataTable({
            "oLanguage": {
                "sLengthMenu": "Mostrar _MENU_ recibos por página",
                "sZeroRecords": "No existen recibos",
                "sInfo": "Mostrando _START_ a _END_ de _TOTAL_ recibos",
                "sInfoEmpty": "Mostrando 0 a 0 de 0 recibos",
                "sInfoFiltered": "(Encontrados de _MAX_ recibos)"
            },
            "aoColumns": [{"sWidth":"5%"},{"sWidth":"30%"},{"sWidth":"40%"},{"sWidth":"20%"},{"sWidth":"5%"}],
            "bProcessing": true,
            "sAjaxSource": '../../includes/acciones/cuentas/get_recibos_json.php',
            "fnServerParams": function ( aoData ) {
                aoData.push(
                    { "name": "no_recibo", "value": $("#no_reciboVal").val() },
                    { "name": "alumno", "value": $("#alumnoVal").val() },
                    { "name": "ciclo", "value": $("#cicloVal").val() }
                );
            },
            "iDisplayLength": 25
        });
    }
</script>
<!------------------------ JAVASCRIPT --------------------------->

</html>