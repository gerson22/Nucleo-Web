<?php
/**
 * Created by PhpStorm.
 * User: Yozki
 * Date: 10/4/14
 * Time: 3:38 PM
 */

$id_modulo = 47; // 47 - Configuración - Ocupaciones
include_once("../../../includes/validar_admin.php");
include_once("../../../includes/clases/class_lib.php");
include_once("../../../includes/validar_acceso.php");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Sistema Integral Meze - Configuración: Ocupaciones</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<script src="../../../js/jquery.js" type="text/javascript"></script>
	<script src="../../../js/bootstrap.js" type="text/javascript"></script>
	<script src="../../../plugins/assets/js/appear.min.js" type="text/javascript"></script>
	<script src="../../../plugins/assets/js/animations.js" type="text/javascript"></script>
    <link rel="stylesheet" href="../../../estilo/general.css" />
    <link rel="stylesheet" href="../../../estilo/jquery.dataTables.css" />
</head>
<body>
    <?php include("../../../includes/headerConfig.php"); ?>
    	<div id="principal" class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2" style="margin-top:30px;">
        	<div id="area_trabajo">
            <h2>Ocupaciones</h2>

            <button onclick="nuevaOcupacionClicked()" class="btn btn-primary" ><span class="glyphicon glyphicon-plus"></span> Nueva</button>

            <table id="tabla_ocupaciones" class="table" >
                <thead>
                <tr>
                    <th>Ocupación</th>
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
    var tabla_ocupaciones;

    declararDataTable();

    function declararDataTable()
    {
        tabla_ocupaciones = $('#tabla_ocupaciones').dataTable({
            "oLanguage": {
                "sLengthMenu": "Mostrar _MENU_ ocupaciones por página",
                "sZeroRecords": "No existen ocupaciones",
                "sInfo": "Mostrando _START_ a _END_ de _TOTAL_ ocupaciones",
                "sInfoEmpty": "Mostrando 0 a 0 de 0 ocupaciones",
                "sInfoFiltered": "(Encontrados de _MAX_ ocupaciones)"
            },
            "bProcessing": true,
            "sAjaxSource": '../../../includes/acciones/configuracion/ocupaciones/print_tabla.php',
            "fnServerParams": function (aoData)
            {

            },
            "iDisplayLength": 25
        });
    }

    function nuevaOcupacionClicked()
    {
        var nuevaOcupacionNombre = prompt("Ocupación");
        if(nuevaOcupacionNombre)
        {
            if(confirm("¿Seguro que desea agregar la nueva ocupación: " + nuevaOcupacionNombre + "?"))
            {
                $.ajax({
                    type: "POST",
                    url: "/includes/acciones/configuracion/ocupaciones/insert.php",
                    data: "nombre=" + nuevaOcupacionNombre,
                    success: function (data)
                    {
                        if(data == 1)
                        {
                            reloadTable();
                        }
                        else
                        {
                            alert("Error del sistema.");
                        }
                    }
                });
            }
        }

    }

    function reloadTable()
    {
        tabla_ocupaciones.fnReloadAjax();
    }
</script>
</body>
</html>