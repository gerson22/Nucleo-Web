<?php
/**
 * Created by PhpStorm.
 * User: Yozki
 * Date: 12/20/14
 * Time: 1:07 PM
 */

include_once("../../../includes/validar_admin.php");
include_once("../../../includes/clases/class_lib.php");
?>
<html>
<head>
    <meta charset="utf-8" />
    <title>Sistema Integral Meze - Cuotas</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<script src="../../../js/jquery.js" type="text/javascript"></script>
	<script src="../../../js/bootstrap.js" type="text/javascript"></script>
	<script src="../../../plugins/assets/js/appear.min.js" type="text/javascript"></script>
	<script src="../../plugins/assets/js/animations.js" type="text/javascript"></script>
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
                $("#alumnoVal").val(alumno.nombres + " " + alumno.apellido_paterno + " " + alumno.apellido_materno);

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
                    llenarCuentas();
                }
            });
        }

        function pagar()
        {
            if(confirm("¿Desea realizar los pagos determinados?"))
            {
                var pagos = [];

                // Cada concepto
                $(".abonoVal").each(function(){

                    if($(this).val() !== "" )
                    {
                        var pago = {};
                        pago.id_concepto = $(this).data("idconcepto");
                        pago.abono = $(this).val();
                        pagos.push(pago);
                    }
                });

                $.ajax({
                    type: "POST",
                    url: "/includes/acciones/cuentas/nuevos_pago.php",
                    data: "id_alumno=" + alumno.id_persona + "&pagos=" + JSON.stringify(pagos) + "&id_ciclo_escolar=" + $("#cicloVal").val(),
                    success: function (data)
                    {
                        if(data == 1) document.location.reload(true);
                    }
                });
            }
        }

        function llenarCuentas()
        {
            $.ajax({
                type: "POST",
                url: "/includes/acciones/alumnos/get_cuentas_otras.php",
                dataType: "json",
                data: "id_alumno=" + alumno.id_persona + "&id_ciclo_escolar=" + $("#cicloVal").val(),
                success: function (data)
                {
                    $.each(data, function(i, obj)
                    {
                        var html_string = "<tr>";
                        html_string += "<td><input type='hidden' class='id_concepto' value='"+data[i].id_concepto+"' /></td>";
                        html_string += "<td>"+data[i].concepto+"</td>";
                        html_string += "<td>"+data[i].monto+"</td>";
                        html_string += "<td>"+data[i].pagado+"</td>";
                        html_string += "<td>"+(data[i].monto - data[i].pagado)+"</td>";
                        html_string += "<td><input type='text' class='abonoVal' data-idConcepto='"+data[i].id_concepto+"' /></td>";

                        $("#tabla_pagos").children('tbody').append(html_string);
                        //use obj.id and obj.name here, for example:
                    });
                }
            });
        }
    </script>
</head>
<body>
    <?php include("../../../includes/headerConfig.php"); ?>
	<div id="principal" class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2" >
        	<div id="area_trabajo">
            <div class="form_row_2">
                <label for="alumnoVal" class="form_label">Alumno</label>
                <input type="text" class="form_input" id="alumnoVal" ondblclick="$('#buscador_alumnos').fadeIn();" >
            </div>
            <div class="form_row_2">
                <label for="cicloVal" class="form_label">Ciclo escolar</label>
                <select class="form_input_half" id="cicloVal" name="cicloVal" onchange="llenarCuentas()" >
                    <!-- AJAX -->
                </select>
            </div>

            <div id="tabla_pagos_wrapper" >
                <table id="tabla_pagos" >
                    <thead>
                        <tr>
                            <th style="width: 0px"></th>
                            <th style="width: 40%" >Concepto</th>
                            <th style="width: 15%" >Monto</th>
                            <th style="width: 15%" >Pagado</th>
                            <th style="width: 15%" >Adeudo</th>
                            <th style="width: 15%" >Abono</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- AJAX -->
                    </tbody>
                </table>
            </div>

            <div id="div_monto_a_pagar">
                <div style="float: left" >Pago por el concepto de inscripción por la cantidad de</div>
                <div id="monto_a_pagar" style="float: left; margin-left: 4px;"></div>
            </div>

            <input type="button" class="form_submit btn btn-primary" value="Aceptar" id="boton_pagar" onclick="pagar();"  />

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