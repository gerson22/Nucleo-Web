<?php
/**
 * Created by PhpStorm.
 * User: Gustavo
 * Date: 20/03/14
 * Time: 03:05 PM
 */

$id_modulo = 17; // Cuentas - Pagos
include_once("../../../includes/validar_admin.php");
include_once("../../../includes/clases/class_lib.php");
include_once("../../../includes/validar_acceso.php");
?>
<html>
<head>
    <meta charset="utf-8" />
    <title>Sistema Integral Meze - Colegiaturas</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<script src="../../../js/jquery.js" type="text/javascript"></script>
	<script src="../../../js/bootstrap.js" type="text/javascript"></script>
	<script src="../../../plugins/assets/js/appear.min.js" type="text/javascript"></script>
	<script src="../../../plugins/assets/js/animations.js" type="text/javascript"></script>
    <link rel="stylesheet" href="../../../estilo/general.css" />
    <link rel="stylesheet" href="../../../estilo/formas_mini.css" />
    <link rel="stylesheet" href="../../../estilo/buscadorAjax.css" />
    <link rel="stylesheet" href="../../../estilo/cuentas.css" />
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
    <script>
        var alumno;
        var abonos;

        $(document).ready(function ()
        {
            $('#buscador_alumnos').draggable({ containment: "document", handle: ".buscadorAjax_barra" });
        });

        function buscarAlumno()
        {
            $.ajax({
                type: "POST",
                url: "/includes/acciones/alumnos/buscar_alumnos.php",
                data: "parametro=" + $("#parametroVal").val(),
                success: function (data)
                {
                    $("#buscador_alumnos_tabla").html(data);
                }
            });
        }

        function seleccionarAlumno(id_alumno)
        {
            $.post("/includes/acciones/alumnos/getAlumnoJSON.php", {id_alumno:id_alumno}, function (data)
            {
                alumno = $.parseJSON(data);
                getStatus();
                $("#alumnoVal").val(alumno.nombres + " " + alumno.apellido_paterno + " " + alumno.apellido_materno);
            });
            $("#buscador_alumnos").fadeOut();
        }

        function getStatus()
        {
            var id_ciclo_escolar = $("#cicloVal").val();
            if(alumno)
            {
                $.ajax({
                    type: "POST",
                    url: "/includes/acciones/alumnos/getColegiaturasStatus.php",
                    data: "id_alumno=" + alumno.id_persona + "&id_ciclo_escolar=" + id_ciclo_escolar,
                    success: function (data)
                    {
                        $("#boton_pagar").prop('disabled', false);

                        $("#tabla_pagos tbody").html(data);
                        abonos = [];
                        asignarListener();
                    }
                });
            }
        }

        function asignarListener()
        {
            $(".td_monto").bind('keyup mouseup oninput', function()
            {
                abonos = [];

                $(".colegiatura").each(function(){
                    var id_cuenta   = $(this).children('.td_cuenta').children('.id_cuentaVal').val() * 1.0;
                    var adeudo      = $(this).children('.td_adeudo').children('.adeudoVal').val() * 1.0;
                    var abono       = $(this).children('.td_monto').children('.montoVal').val() * 1.0;

                    if(abono > adeudo)
                    {
                        abono = adeudo;
                        $(this).children('.td_monto').children('.montoVal').val(abono);
                    }
                    if(abono < 0)
                    {
                        $(this).children('.td_monto').children('.montoVal').val(0);
                    }

                    var colegiatura = {"id_cuenta":id_cuenta,"abono":abono}
                    if(abono > 0){ abonos.push(colegiatura); }
                });
            });
        }

        function pagar()
        {
            if(abonos)
            {
                if(abonos.length > 0)
                {
                    if(confirm("¿Seguro que los datos están correctos?"))
                    {
                        $("#boton_pagar").prop('disabled', true);

                        var id_ciclo_escolar = $("#cicloVal").val();

                        $.ajax({
                            type: "POST",
                            url: "/includes/acciones/cuentas/pagos/pagar_colegiaturas.php",
                            data: "id_persona=" + alumno.id_persona  + "&id_ciclo_escolar=" + id_ciclo_escolar
                                + "&abonos=" + JSON.stringify(abonos) + "&id_forma_pago=" + 1,
                            success: function (data)
                            {
                                if(data)
                                {
                                    if(confirm("¿Imprimir el recibo de pago?"))
                                    {
                                        window.location.href = "/admin/cuentas/pagos/imprimir_recibo.php?recibo=" + data;
                                    }
                                    else
                                    {
                                        window.location.reload(true);
                                    }
                                }
                                else{ alert("Error."); $("#boton_pagar").prop('disabled', false); }

                                if(data){ alert("Pago realizado."); window.location.reload(true); }
                                else{ alert("Error."); $("#boton_pagar").prop('disabled', false); }
                            }
                        });
                    }
                }
            }
        }
    </script>
</head>
<body>
    <?php include("../../../includes/headerConfig.php"); ?>
    <div id="principal" class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2" style="margin-top:30px;">
        	<div id="area_trabajo">

            <div class="form_row_2">
                <label for="alumnoVal" class="form_label">Alumno</label>
                <input type="text" class="form_input" id="alumnoVal" ondblclick="$('#buscador_alumnos').fadeIn();" >
            </div>
            <div class="form_row_2">
                <label for="cicloVal" class="form_label">Ciclo escolar</label>
                <select class="form_input_half" id="cicloVal" name="cicloVal" onchange="getStatus();" >
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

            <div id="tabla_pagos_wrapper" class="table-responsive" >
                <table id="tabla_pagos" class="table" >
                    <thead>
                        <tr>
                            <th style="width: 15%" ></th>
                            <th style="width: 10%" >Subtotal</th>
                            <th style="width: 10%" >Recargos</th>
                            <th style="width: 10%" >Descuento</th>
                            <th style="width: 10%" >Total</th>
                            <th style="width: 10%" >Pagado</th>
                            <th style="width: 10%" >Adeudo</th>
                            <th style="width: 10%" >Fecha límite</th>
                            <th style="width: 15%" >Abono</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- AJAX -->
                    </tbody>
                </table>
            </div>

            <div id="div_monto_a_pagar">
                <div style="float: left" >Pago por el concepto de colegiatura
                    es por la cantidad de</div>
                <div id="monto_a_pagar" style="float: left; margin-left: 4px;"></div>
            </div>

            <input type="button" class="form_submit btn btn-primary" value="Aceptar" id="boton_pagar" onclick="pagar();" disabled />

        </div>

    </div>

    <div id="buscador_alumnos" class="buscadorAjax" style="box-shadow: 2px 2px 5px #5f5f5f; ">
        <div class="buscadorAjax_barra">
            <img src='/media/iconos/icon_close.gif' alt="Cerrar" onclick="$(this).parent().parent().fadeOut()" />
        </div>
        <div class="buscadorAjax_top">
            <label class="buscadorAjax_top_label">Parametro: </label>
            <input class="buscadorAjax_top_input" type="text" id="parametroVal" />
            <input class="buscadorAjax_top_boton" type="button" onclick="buscarAlumno()" value="Buscar" />
        </div>
        <div class="buscadorAjax_contenedor_tabla">
            <table id="buscador_alumnos_tabla" class="buscadorAjax_tabla">
                <!-- Buscador AJAX -->
            </table>
        </div>
    </div>

</div>
</body>
</html>