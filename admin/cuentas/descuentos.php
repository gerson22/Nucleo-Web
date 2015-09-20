<?php
/**
 * Created by PhpStorm.
 * User: Gustavo
 * Date: 9/01/14
 * Time: 10:00 AM
 */

$id_modulo = 39; // Cuentas - Descuento
include_once("../../includes/validar_admin.php");
include_once("../../includes/clases/class_lib.php");
include_once("../../includes/validar_acceso.php");

?>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Sistema Integral Meze - Materias</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<script src="../../js/jquery.js" type="text/javascript"></script>
		<script src="../../js/bootstrap.js" type="text/javascript"></script>
		<script src="../../plugins/assets/js/appear.min.js" type="text/javascript"></script>
		<script src="../../plugins/assets/js/animations.js" type="text/javascript"></script>
        <link rel="stylesheet" href="../../estilo/general.css" />
        <link rel="stylesheet" href="../../estilo/jquery.dataTables.css" />
        <link rel="stylesheet" href="../../estilo/formas_mini.css" />
        <link rel="stylesheet" href="../../estilo/buscadorAjax.css" />
        <style>
            #boton_pagar
            {
                float: right;
                margin-top: 50px;
            }

            #tabla_cuentas
            {
                border-collapse: collapse;
                font-size: 12px;
                text-align: left;
                border: 1px solid #A4C7E1;
                width: 100%;
            }

            #tabla_cuentas_wrapper
            {
                float: left;
                margin-top: 25px;
                overflow: auto;
                width: 100%;
            }

            #tabla_cuentas thead
            {
                background-color: #152975;
                color: #FFFFFF;
                height: 30px;
            }

            #tabla_cuentas tbody tr
            {
                height: 30px;
            }
        </style>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
        <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
        <script src="../../librerias/jquery.dataTables.min.js" ></script>
        <script src="../../librerias/jquery.validate.min.js" ></script>
        <script src="../../librerias/messages_es.js" ></script>
        <script src="../../librerias/fnAjaxReload.js" ></script>
        <script>
            var alumno;
            var descuentos;

            function buscarAlumno()
            {
                $.ajax({
                    type: "POST",
                    url: "../../../includes/acciones/alumnos/buscar_alumnos.php",
                    data: "parametro=" + $("#parametroVal").val(),
                    success: function (data)
                    {
                        $("#buscador_alumnos_tabla").html(data);
                    }
                });
            }

            function seleccionarAlumno(id_alumno)
            {
                $.ajax({
                    type: "POST",
                    url: "/includes/acciones/alumnos/getAlumnoJSON.php",
                    data: "id_alumno=" + id_alumno,
                    async: false,
                    success: function (data)
                    {
                        if (data != "error")
                        {
                            alumno = jQuery.parseJSON(data);
                            $("#id_alumnoVal").val(alumno.id_persona);
                            $("#input_alumno").val(alumno.apellido_paterno + " " + alumno.apellido_materno + " " + alumno.nombres);
                            cargarCiclos();
                            printCuentas();
                        }
                    }
                });
                $("#buscador_alumnos").fadeOut();
            }

            function cargarCiclos()
            {
                $.ajax({
                    type: "POST",
                    url: "/includes/acciones/alumnos/getCiclosInscrito.php",
                    data: "id_alumno=" + alumno.id_persona,
                    async: false,
                    success: function (data)
                    {
                        $("#cicloVal").html(data);
                    }
                });
            }

            function printCuentas()
            {
                $.ajax({
                    type: "POST",
                    url: "/includes/acciones/alumnos/print_status_cuentas.php",
                    data: "id_alumno=" + alumno.id_persona + "&id_ciclo_escolar=" + $("#cicloVal").val(),
                    success: function (data)
                    {
                        $("#div_cuentas").html(data);
                        $("#tabla_cuentas tbody").html(data);
                        descuentos = [];

                        asignarListener();
                    }
                });
            }

            function asignarListener()
            {
                $(".montoVal").bind('keyup mouseup oninput', function()
                {
                    console.log("Recalcular descuentos...");
                    descuentos = [];

                    $(".cuenta").each(function()
                    {
                        var id_cuenta   = $(this).children('.td_cuenta').children('.id_cuentaVal').val() * 1.0;
                        var monto       = $(this).children('.td_monto').children('.montoVal').val() * 1.0;
                        var adeudo      = $(this).children('.td_adeudo').children('.adeudoVal').val() * 1.0;

                        if(monto < 0)
                        {
                            monto = 0;
                            $(this).children('.td_monto').children('.montoVal').val(monto);
                        }
                        if(monto > adeudo)
                        {
                            monto = adeudo;
                            $(this).children('.td_monto').children('.montoVal').val(monto);
                        }

                        var descuento = {"id_cuenta":id_cuenta,"descuento":monto}
                        if(monto > 0){ descuentos.push(descuento); }
                    });
                });
            }

            function guardarDescuentos()
            {
                console.dir(descuentos);
                if(descuentos.length > 0)
                {
                    if(confirm("¿Seguro que los descuentos están correctos?"))
                    {
                        $("#boton_pagar").prop('disabled', true);

                        $.ajax({
                            type: "POST",
                            url: "/includes/acciones/cuentas/descuentos/asignar_descuentos.php",
                            data: "descuentos=" + JSON.stringify(descuentos),
                            success: function (data)
                            {
                                if(data == 1){ alert("Descuentos asignados."); window.location.reload(true); }
                                else{ alert("Error."); $("#boton_pagar").prop('disabled', false); }
                            }
                        });
                    }
                }
            }
        </script>
    </head>
    <body>
        <?php include("../../includes/header.php"); ?>
        <div id="principal" class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2" >
        	<div id="area_trabajo">

                <div class="form_row_2">
                    <input type="hidden" id="id_alumnoVal" />
                    <label class="form_label">Alumno</label>
                    <input id="input_alumno" name="input_alumno" class="form_input" type="text" readonly="" ondblclick="$('#buscador_alumnos').fadeIn();" />
                </div>
                <div class="form_row_2">
                    <label for="cicloVal" class="form_label">Ciclo escolar</label>
                    <select class="form_input_half" id="cicloVal" name="cicloVal" onchange="printCuentas()" >
                        <!-- AJAX -->
                    </select>
                </div>

                <div id="tabla_cuentas_wrapper" class="table-responsive">
                    <table id="tabla_cuentas" class="table">
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
                                <th style="width: 15%" >Descuento</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- AJAX -->
                        </tbody>
                    </table>
                </div>

                <input type="button" class="form_submit btn btn-primary" value="Aceptar" id="boton_pagar" onclick="guardarDescuentos();" />

            </div>

        </div>

        <div class="buscadorAjax" id="buscador_alumnos" style="box-shadow: 2px 2px 5px #5f5f5f; " >
            <div class="buscadorAjax_barra">
                <img onclick="$(this).parent(0).parent(0).fadeOut()" alt="Cerrar" src="../../../media/iconos/icon_close.gif">
            </div>
            <div class="buscadorAjax_top">
                <label class="buscadorAjax_top_label">Parametro: </label>
                <input type="text" id="parametroVal" class="buscadorAjax_top_input">
                <input type="button" value="Buscar" onclick="buscarAlumno()" class="buscadorAjax_top_boton">
            </div>
            <div class="buscadorAjax_contenedor_tabla">
                <table class="buscadorAjax_tabla" id="buscador_alumnos_tabla">
                    <!-- Buscador AJAX -->
                </table>
            </div>
        </div>

    </div>
    </body>
    <script>
        $(".buscadorAjax").draggable({ handle: ".buscadorAjax_barra", containment: "document" });
    </script>
</html>