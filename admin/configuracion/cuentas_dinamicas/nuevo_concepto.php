<?php
/**
 * Created by PhpStorm.
 * User: Yozki
 * Date: 12/30/14
 * Time: 8:32 PM
 */

$id_modulo = 49; // 49 - Cuentas - Dinámicas
include_once("../../../includes/validar_admin.php");
include_once("../../../includes/clases/class_lib.php");
include_once("../../../includes/validar_acceso.php");

$areas = Area::getLista();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Sistema Integral Meze - Configuración: Clubs</title>
    <link rel="stylesheet" href="../../../estilo/general.css" />
    <link rel="stylesheet" href="../../../estilo/jquery.dataTables.css" />
    <link rel="stylesheet" href="../../../estilo/cuentas_dinamicas.css" />
</head>
<body>
<div id="wrapper">
    <?php include("../../../includes/header.php"); ?>
    <div id="content">

        <div id="inner_content">

            <h2>Nuevo concepto de cuentas dinámicas</h2>

            <div class="form_row_3">
                <label class="form_label" for="nuevo_conceptoVal">Concepto</label>
                <input id="nuevo_conceptoVal" class="form_input" type="text" />
            </div>

            <!-- 5 Areas -->
            <div id="div_areas">
                <span>Areas en las que será aplicable el concepto, y su monto correspondiente.</span>
                <?php
                if(is_array($areas))
                {
                    foreach($areas as $area)
                    {
                        echo "
                            <div class='area_row'>
                                <input type='checkbox' class='area_check' data-idarea='".$area['id_area']."' />
                                <label>".$area['area']."</label>
                                <input type='text' class='area_monto' />
                            </div>
                        ";
                    }
                }
                ?>
            </div>
            <!------------->

            <button onclick="aceptarClicked()">Aceptar</button>

        </div>

    </div>
</div>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
<script src="../../../librerias/jquery.dataTables.min.js" ></script>
<script src="../../../librerias/fnAjaxReload.js" ></script>
<script>
    function aceptarClicked()
    {
        if(confirm('¿Desea agregar el concepto con los montos determinados?'))
        {
            var concepto = $("#nuevo_conceptoVal").val();
            var montos = {};

            $(".area_check").each(function()
            {
                if(this.checked)
                {
                    var id_area = $(this).data('idarea');
                    var monto = $(this).parent().children('.area_monto').val();
                    montos[id_area] = monto;
                }
            });

            $.ajax({
                type: "POST",
                url: "/includes/acciones/cuentas/conceptos/insert_dinamico.php",
                data: "concepto=" + concepto + "&montos=" + JSON.stringify(montos),
                success: function (data)
                {
                    document.location.href = "/admin/configuracion/cuentas_dinamicas/index.php";
                }
            });
        }
    }
</script>
</body>
</html>