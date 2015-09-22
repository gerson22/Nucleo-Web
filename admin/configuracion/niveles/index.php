<?php
/**
 * Created by PhpStorm.
 * User: Yozki
 * Date: 11/19/14
 * Time: 4:32 PM
 */

include_once("../../../includes/validar_admin.php");
include_once("../../../includes/clases/class_lib.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Sistema Integral Meze - Niveles</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<script src="../../../js/jquery.js" type="text/javascript"></script>
	<script src="../../../js/bootstrap.js" type="text/javascript"></script>
	<script src="../../../plugins/assets/js/appear.min.js" type="text/javascript"></script>
	<script src="../../plugins/assets/js/animations.js" type="text/javascript"></script>
    <link rel="stylesheet" href="../../../estilo/general.css" />
    <link rel="stylesheet" href="../../../estilo/jquery.dataTables.css" />
    <link rel="stylesheet" href="../../../estilo/jQueryUI.css" />
    <style>
        .form-group
        {
            margin: 10px 0;
        }
    </style>
</head>
<body>
<div id="wrapper">
    <?php include("../../../includes/headerConfig.php"); ?>
     <div id="principal" class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2" >
        	<div id="area_trabajo">

            <h2>Niveles</h2>

            <button onclick="nuevo()" class="btn btn-primary" >Nuevo</button>

            <table id="tabla_niveles" class="table" >
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Nivel</th>
                    <th>Prefijo</th>
                    <th>No. de parciales</th>
                    <th>Modificar</th>
                    <th>Eliminar</th>
                </tr>
                </thead>
                <tbody>
                    <!-- AJAX -->
                </tbody>
            </table>
        </div>

    </div>

<!-- MODAL -->
<div id="modal_nuevo_nivel" title="Nuevo nivel" style="box-shadow: 2px 2px 5px #5f5f5f;">
    <form action="" onsubmit="return false;">
        <div class="form-group">
            <label for="nombreVal">Nivel</label>
            <input type="text" name="nombreVal" id="nombreVal" class="text ui-widget-content ui-corner-all">
        </div>
        <div class="form-group">
            <label for="prefijoVal">Prefijo</label>
            <input type="text" name="prefijoVal" id="prefijoVal" class="text ui-widget-content ui-corner-all">
        </div>
        <div class="form-group">
            <label for="no_parcialesVal">No. de parciales</label>
            <input type="text" name="no_parcialesVal" id="no_parcialesVal" class="text ui-widget-content ui-corner-all">
        </div>

        <!-- Allow form submission with keyboard without duplicating the dialog button -->
        <input type="submit" tabindex="-1" style="position:absolute; top:-1000px">
    </form>
</div>
<!-- /MODAL -->

<!-- MODAL MODIFICAR -->
<div id="modal_modificar_nivel" title="Modificar nivel" style="box-shadow: 2px 2px 5px #5f5f5f;">
    <form action="" onsubmit="return false;">
        <div class="form-group">
            <label for="nombreModVal">Nivel</label>
            <input type="text" name="nombreModVal" id="nombreModVal" class="text ui-widget-content ui-corner-all">
        </div>
        <div class="form-group">
            <label for="prefijoModVal">Prefijo</label>
            <input type="text" name="prefijoModVal" id="prefijoModVal" class="text ui-widget-content ui-corner-all">
        </div>
        <div class="form-group">
            <label for="no_parcialesModVal">No. de parciales</label>
            <input type="text" name="no_parcialesModVal" id="no_parcialesModVal" class="text ui-widget-content ui-corner-all">
        </div>

        <!-- Allow form submission with keyboard without duplicating the dialog button -->
        <input type="submit" tabindex="-1" style="position:absolute; top:-1000px">
    </form>
</div>
<!-- /MODAL -->

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
<script src="/librerias/jquery.dataTables.min.js" ></script>
<script src="/librerias/jQueryUI.js" ></script>
<script src="/librerias/fnAjaxReload.js" ></script>
<script>
    var tabla_niveles;
    var modal;
    var modal_modificar;
    var id_nivel_mod;

    $(document).ready(function ()
    {
        declararDataTable();
        declararModals();
    });

    function nuevo()
    {
        modal.dialog("open");
    }

    function modificar(id_nivel, area, prefijo, no_parciales)
    {
        id_nivel_mod = id_nivel;

        $("#nombreModVal").val(area);
        $("#prefijoModVal").val(prefijo);
        $("#no_parcialesModVal").val(no_parciales);

        modal_modificar.dialog("open");
    }

    function eliminar(id_nivel)
    {
        if(confirm("Eliminar un nivel implica eliminar todos sus grados, grupos, clases, materias etc asignados a dicho nivel. " +
            "Esta acción es irreversible." +
            "¿Desea continuar?"))
        {
            $.post("/includes/acciones/configuracion/niveles/eliminar.php", {id_nivel:id_nivel}, function (data)
            {
                tabla_niveles.fnReloadAjax();
            });
        }
    }

    function agregar()
    {
        var nombre          = $("#nombreVal").val();
        var prefijo         = $("#prefijoVal").val();
        var no_parciales    = $("#no_parcialesVal").val();

        if(nombre)
        {
            if(nombre.length > 0 && nombre != " ")
            {
                if(confirm("¿Seguro que desea agregar un nuevo nivel educativo a la base de datos?"))
                {
                    $.ajax({
                        type: "POST",
                        url: "/includes/acciones/configuracion/niveles/nuevo.php",
                        data: "nombre=" + nombre + "&prefijo=" + prefijo + "&no_parciales=" + no_parciales,
                        success: function (data)
                        {
                            if(data == 1) tabla_niveles.fnReloadAjax();
                        }
                    });
                }
            }
        }

        modal.dialog( "close" );
    }

    function update()
    {
        console.log("updato");

        var nombre          = $("#nombreModVal").val();
        var prefijo         = $("#prefijoModVal").val();
        var no_parciales    = $("#no_parcialesModVal").val();

        if(nombre)
        {
            if(nombre.length > 0 && nombre != " ")
            {
                if(confirm("¿Seguro que desea modificar los datos del nivel? Los cambios realizados se reflejaran en todo el sistema."))
                {
                    $.ajax({
                        type: "POST",
                        url: "/includes/acciones/configuracion/niveles/update.php",
                        data: "id_nivel=" + id_nivel_mod + "&nombre=" + nombre + "&prefijo=" + prefijo + "&no_parciales=" + no_parciales,
                        success: function (data)
                        {
                            if(data == 1) tabla_niveles.fnReloadAjax();
                        }
                    });
                }
            }
        }

        modal_modificar.dialog( "close" );
    }

    function declararModals()
    {
        modal = $( "#modal_nuevo_nivel" ).dialog({
            autoOpen: false,
            height: 350,
            width: 450,
            modal: true,
            buttons: {
                "Agregar": agregar,
                "Cancelar": function()
                {
                    modal.dialog( "close" );
                }
            },
            close: function()
            {

            }
        });

        modal_modificar = $( "#modal_modificar_nivel" ).dialog({
            autoOpen: false,
            height: 350,
            width: 450,
            modal: true,
            buttons: {
                "Modificar": update,
                "Cancelar": function()
                {
                    modal_modificar.dialog( "close" );
                }
            },
            close: function()
            {

            }
        });

    }

    function declararDataTable()
    {
        tabla_niveles = $('#tabla_niveles').dataTable({
            "oLanguage": {
                "sLengthMenu": "Mostrar _MENU_ niveles por página",
                "sZeroRecords": "No existen niveles",
                "sInfo": "Mostrando _START_ a _END_ de _TOTAL_ niveles",
                "sInfoEmpty": "Mostrando 0 a 0 de 0 niveles",
                "sInfoFiltered": "(Encontrados de _MAX_ niveles)"
            },
            "iDisplayLength": 25,
            "bProcessing": true,
            "sAjaxSource": '/includes/acciones/configuracion/niveles/print_tabla.php',
            "fnServerParams": function (aoData)
            {

            }
        });
    }
</script>

</body>
</html>
