<?php
/**
 * Created by PhpStorm.
 * User: Yozki
 * Date: 9/30/14
 * Time: 2:15 PM
 */

$id_modulo = 44; // 44 - Configuración - Clubs
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
            <h2>Clubs</h2>

            <button onclick="nuevoClubClicked()" class="btn btn-primary" ><span class="glyphicon glyphicon-plus"></span>Nuevo</button>

            <table id="tabla_clubs" class="table" >
                <thead>
                    <tr>
                        <th>Club</th>
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
    var tabla_clubs;

    declararDataTable();

    function declararDataTable()
    {
        tabla_clubs = $('#tabla_clubs').dataTable({
            "oLanguage": {
                "sLengthMenu": "Mostrar _MENU_ clubs por página",
                "sZeroRecords": "No existen clubs",
                "sInfo": "Mostrando _START_ a _END_ de _TOTAL_ clubs",
                "sInfoEmpty": "Mostrando 0 a 0 de 0 clubs",
                "sInfoFiltered": "(Encontrados de _MAX_ clubs)"
            },
            "bProcessing": true,
            "sAjaxSource": '../../../includes/acciones/configuracion/clubs/print_tabla.php',
            "fnServerParams": function (aoData)
            {
                //aoData.push({ "name": "id_ciclo_escolar", "value": id_ciclo_escolar });
            },
            "iDisplayLength": 25
        });
    }

    function nuevoClubClicked()
    {
        var nuevoClubNombre = prompt("Nombre");
        if(nuevoClubNombre)
        {
            if(confirm("¿Seguro que desea agregar el nuevo club: " + nuevoClubNombre + "?"))
            {
                $.ajax({
                    type: "POST",
                    url: "/includes/acciones/configuracion/clubs/insert.php",
                    data: "nombre=" + nuevoClubNombre,
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
        tabla_clubs.fnReloadAjax();
    }
</script>
</body>
</html>