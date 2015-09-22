<?php
include("../../../includes/validar_admin.php");
include_once("../../../includes/clases/class_lib.php");
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Sistema Integral Meze - Lista de pagos</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<script src="../../../js/jquery.js" type="text/javascript"></script>
		<script src="../../../js/bootstrap.js" type="text/javascript"></script>
		<script src="../../../plugins/assets/js/appear.min.js" type="text/javascript"></script>
		<script src="../../../plugins/assets/js/animations.js" type="text/javascript"></script>
        <link rel="stylesheet" href="../../../estilo/general.css" />
        <link rel="stylesheet" href="../../../estilo/buscadorAjax.css" />
        <link rel="stylesheet" href="../../../estilo/jquery.dataTables.css" />
        <style>
            .select_wrapper
            {
                float: left;
                margin: 10px 10px 10px 0;
                overflow: auto;
                width: 200px;
            }
            
            .select_wrapper label
            {
                font-size: 12px;
            }
            
            .select_wrapper select
            {
                border: 1px solid #152975;
                height: 40px;
                padding: 10px;
                width: 100%;
            }
        </style>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
        <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
        <script src="../../../librerias/jquery.dataTables.min.js" ></script>
        <script src="../../../librerias/fnAjaxReload.js" ></script>
        <script>
            var tabla_pagos;

            $(document).ready(function ()
            {
                inicializarDataTable();
            });

            function inicializarDataTable()
            {
                tabla_pagos = $('#tabla_pagos').dataTable({
                    "oLanguage": {
                        "sLengthMenu": "Mostrar _MENU_ pagos por página",
                        "sZeroRecords": "No se encontraron pagos",
                        "sInfo": "Mostrando _START_ a _END_ de _TOTAL_ pagos",
                        "sInfoEmpty": "Mostrando 0 a 0 de 0 pagos",
                        "sInfoFiltered": "(Encontrados de _MAX_ pagos)"
                    },
                    "aoColumns": [
                        {"sWidth":"22%"},{"sWidth":"15%"},{"sWidth":"10%"},
                        {"sWidth":"12%"},{"sWidth":"12%"},{"sWidth":"12%"},
                        {"sWidth":"12%"},{"sWidth":"3%"}
                    ],
                    "bProcessing": true,
                    "sAjaxSource": '../../../includes/acciones/cuentas/pagos/get_lista.php',
                    "fnServerParams": function (aoData)
                    {
                        var ciclo_escolar = $("#ciclo_escolarVal").val();
                        var concepto = $("#conceptoVal").val();
                        aoData.push({ "name": "ciclo_escolar", "value": ciclo_escolar });
                        aoData.push({ "name": "concepto", "value": concepto });
                    }
                });
            }
        </script>
    </head>
    <body>
            <?php include("../../../includes/headerConfig.php"); ?>
            <div id="principal" class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2" >
        	<div id="area_trabajo">
                    <div class="select_wrapper">
                        <label>Ciclo Escolar</label>
                        <select id="ciclo_escolarVal" onchange="tabla_pagos.fnReloadAjax();">
                            <option value="" >Todos</option>
                            <?php
                            $ciclos = CicloEscolar::getLista();
                            if(is_array($ciclos))
                            {
                                foreach($ciclos as $ciclo)
                                {
                                    echo "<option value='".$ciclo['id_ciclo_escolar']."' >".$ciclo['ciclo_escolar']."</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>

                    <div class="select_wrapper">
                        <label>Concepto</label>
                        <select id="conceptoVal" onchange="tabla_pagos.fnReloadAjax();">
                            <option value="" >Todos</option>
                            <?php
                            $conceptos = Concepto::getLista();
                            if(is_array($conceptos))
                            {
                                foreach($conceptos as $concepto)
                                {
                                    echo "<option value='".$concepto['id_concepto']."' >".$concepto['concepto']."</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>

                    <table id="tabla_pagos" class="table">
                        <thead>
                            <tr>
                                <th>Alumno</th>
                                <th>Concepto</th>
                                <th>Ciclo</th>
                                <th>Fecha</th>
                                <th>Monto</th>
                                <th>Usuario</th>
                                <th>Descripción</th>
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
    </body>
</html>
