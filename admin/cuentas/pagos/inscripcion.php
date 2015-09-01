<?php
/**
 * Created by PhpStorm.
 * User: Gustavo
 * Date: 11/03/14
 * Time: 12:09 PM
 */

$id_modulo = 17; // Cuentas - Pagos
include_once("../../../includes/validar_admin.php");
include_once("../../../includes/clases/class_lib.php");
include_once("../../../includes/validar_acceso.php");
?>
<html>
<head>
    <meta charset="utf-8" />
    <title>Sistema Integral Meze - Inscripción</title>
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
        var estadoCuenta;
        var abono;

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
                cargarCiclos();
                getStatus();
                $("#alumnoVal").val(alumno.nombres + " " + alumno.apellido_paterno + " " + alumno.apellido_materno);
            });
            $("#buscador_alumnos").fadeOut();
        }

        function llenarInfo(cuenta)
        {
            estadoCuenta = cuenta;
            console.dir(estadoCuenta);

            $("#boton_pagar").prop('disabled', false);

            // # subtotal | descuento | total | pagado | adeudo
            $("#cuentaVal").val(estadoCuenta.id_cuenta);
            $("#subtotalVal").html("$" + estadoCuenta.subtotal);
            $("#descuentoVal").html("$" + estadoCuenta.descuento);
            $("#totalVal").html("$" + estadoCuenta.total);
            $("#pagadoVal").html("$" + estadoCuenta.pagado);
            $("#adeudoVal").html("$" + estadoCuenta.adeudo);
            $("#fechaVal").html(estadoCuenta.fecha);
            if(estadoCuenta.adeudo > 0)
            {
                $("#abonoVal").val(estadoCuenta.adeudo);
                $("#inscripcionVal").css("color", "red");
            }
            else
            {
                $("#inscripcionVal").css("color", "green");
            }
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

        function getStatus()
        {
            if(!alumno)
            {
                return false;
            }
            var id_ciclo_escolar = $("#cicloVal").val();
            $.ajax({
                type: "POST",
                url: "/includes/acciones/alumnos/getInscripcionStatus.php",
                data: "id_alumno=" + alumno.id_persona + "&id_ciclo_escolar=" + id_ciclo_escolar,
                dataType: "json",
                success: function (cuenta)
                {
                    llenarInfo(cuenta);
                }
            });
        }

        function pagar()
        {
            if(estadoCuenta.adeudo > 0)
            {
                var id_cuenta = $("#cuentaVal").val();

                if(confirm("¿Seguro que los datos están correctos?"))
                {
                    $("#boton_pagar").prop('disabled', true);
                    $.ajax({
                        type: "POST",
                        url: "/includes/acciones/cuentas/pagos/pagar_inscripcion.php",
                        data: "id_cuenta=" + id_cuenta + "&monto=" + $("#abonoVal").val() + "&id_forma_pago=" + 1
                            + "&comentario=" + " ",
                        success: function (data)
                        {
                            if(data)
                            {
                                if(confirm("¿Imprimir el recibo de pago?"))
                                {
                                    window.location.href = "/admin/cuentas/pagos/imprimir_recibo.php?id_pago=" + data;
                                }
                                else
                                {
                                    window.location.reload(true);
                                }
                            }
                            else{ alert("Error."); $("#boton_pagar").prop('disabled', false); }
                        }
                    });
                }
                else
                {
                    $("#boton_pagar").prop('disabled', false);
                }
            }
            else alert("La inscripción ya está pagada");
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
                <select class="form_input_half" id="cicloVal" name="cicloVal" onchange="getStatus()" >
                    <!-- AJAX -->
                    <?php
                    /*$ciclos = CicloEscolar::getLista();
                    if(is_array($ciclos))
                    {
                        foreach($ciclos as $ciclo)
                        {
                            echo "<option value='".$ciclo['id_ciclo_escolar']."' >".$ciclo['ciclo_escolar']."</option>";
                        }
                    }*/
                    ?>
                </select>
            </div>

            <div id="tabla_pagos_wrapper" class="table-responsive">
                <table id="tabla_pagos" class="table" >
                    <thead>
                        <tr>
                            <th style="width: 0px"></th>
                            <th style="width: 20%" ></th>
                            <th style="width: 10%" >Subtotal</th>
                            <th style="width: 10%" >Descuento</th>
                            <th style="width: 10%" >Total</th>
                            <th style="width: 10%" >Pagado</th>
                            <th style="width: 10%" >Adeudo</th>
                            <th style="width: 20%" >Abono</th>
                            <th style="width: 10%" >Fecha limite</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><input type="hidden" id="cuentaVal" /></td>
                            <td id="inscripcionVal">Inscripción</td>
                            <td id="subtotalVal" ></td>
                            <td id="descuentoVal" ></td>
                            <td id="totalVal" ></td>
                            <td id="pagadoVal" ></td>
                            <td id="adeudoVal" ></td>
                            <td>
                                $<input type="text" id="abonoVal" disabled="disabled" />
                            </td>
                            <td id="fechaVal" ></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div id="div_monto_a_pagar">
                <div style="float: left" >Pago por el concepto de inscripción por la cantidad de</div>
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

</body>
</html>