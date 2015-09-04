<?php
/**
 * Created by PhpStorm.
 * User: Yozki
 * Date: 10/4/14
 * Time: 1:06 PM
 */

$id_modulo = 46; // 46 - Configuración - Papeleria
include_once("../../../includes/validar_admin.php");
include_once("../../../includes/clases/class_lib.php");
include_once("../../../includes/validar_acceso.php");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Sistema Integral Meze - Configuración: Papeleria</title>
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
            <h2>Papeleria</h2>

            <button onclick="nuevaPapeleriaClicked()" class="btn btn-primary" ><span class="glyphicon glyphicon-plus"></span> Nueva</button>

            <table id="tabla_papeleria" class="table" >
                <thead>
                <tr>
                    <th>Documento</th>
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
    var tabla_papeleria;

    declararDataTable();

    function declararDataTable()
    {
        tabla_papeleria = $('#tabla_papeleria').dataTable({
            "oLanguage": {
                "sLengthMenu": "Mostrar _MENU_ documentos por página",
                "sZeroRecords": "No existen documentos",
                "sInfo": "Mostrando _START_ a _END_ de _TOTAL_ documentos",
                "sInfoEmpty": "Mostrando 0 a 0 de 0 documentos",
                "sInfoFiltered": "(Encontrados de _MAX_ documentos)"
            },
            "bProcessing": true,
            "sAjaxSource": '../../../includes/acciones/configuracion/papeleria/print_tabla.php',
            "fnServerParams": function (aoData)
            {

            },
            "iDisplayLength": 25
        });
    }

    function nuevaPapeleriaClicked()
    {
        var nuevaPapeleriaNombre = prompt("Nombre");
        if(nuevaPapeleriaNombre)
        {
            if(confirm("¿Seguro que desea agregar el nuevo documento: " + nuevaPapeleriaNombre + "?"))
            {
                $.ajax({
                    type: "POST",
                    url: "/includes/acciones/configuracion/papeleria/insert.php",
                    data: "nombre=" + nuevaPapeleriaNombre,
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
        tabla_papeleria.fnReloadAjax();
    }
</script>
</body>
</html>