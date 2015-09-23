<?php
include_once("../../../includes/validar_admin.php");
include_once("../../../includes/clases/class_lib.php");
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Sistema Integral Meze - Nuevo Pago</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<script src="../../../js/jquery.js" type="text/javascript"></script>
		<script src="../../../js/bootstrap.js" type="text/javascript"></script>
		<script src="../../../plugins/assets/js/appear.min.js" type="text/javascript"></script>
		<script src="../../../plugins/assets/js/animations.js" type="text/javascript"></script>
        <link rel="stylesheet" href="../../../estilo/general.css" />
        <link rel="stylesheet" href="../../../estilo/formas_mini.css" />
        <link rel="stylesheet" href="../../../estilo/buscadorAjax.css" />
        <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
        <link rel="stylesheet" href="../../../estilo/jquery.dataTables.css" />
        <link rel="stylesheet" href="../../../estilo/fixed_form.css" />
        <style>
            #div_pagos
            {
                font-size: 12px;
            }

            #div_summary
            {
                float: right;
                padding: 5px;
                width: 200px;
            }

            .summary_row
            {
                overflow: auto;
            }

            .summary_row label
            {
                width: 40%;
                float: left;
                text-align: right;
            }

            .summary_row_right
            {
                float: right;
            }

            #div_descuentos
            {
                float: left;
                font-size: 12px;
                overflow: auto;
                width: 150px;
                border: 1px solid #CCC;
                padding: 10px;
            }

            #div_descuentos div
            {
                overflow: auto;
                display: block;
                margin: 10px 0;
            }

            #div_descuentos label
            {
                float: left;
                width: 80px;
                text-align: right;
                display: block;
                padding-top: 7px;
            }

            #div_descuentos input
            {
                height: 25px;
                float: right;
                width: 50px;
            }

            #becaVal
            {
               width: 50px;
            }

            #datosMonto
            {
                float: right;
                font-size: 12px;
                font-weight: bold;
                margin: 0 80px 0 0;
                overflow: auto;
                text-align: right;
                width: 260px;
            }

            .datosMonto_row
            {
                overflow: auto;
            }

            .datosMonto_row label
            {
                float: left;
                width: 100px;
                text-align: right;
            }

            #div_mes
            {
                visibility: hidden;
            }
        </style>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
        <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
        <script src="../../../librerias/jquery.validate.min.js" ></script>
        <script src="../../../librerias/messages_es.js" ></script>
        <script src="../../../librerias/jquery.dataTables.min.js" ></script>
        <script>
            var descuento_beca = 0;
            var descuento_descuento = 0.00;
            var alumno;
            var beca = 0;
            var pagos = [];
            var subtotal = 0;
            var total = 0;
            var aPagar = 0;
            var adeudo;

            $(document).ready(function ()
            {
                $(".buscadorAjax").draggable({ handle: ".buscadorAjax_barra", containment: "window" });
                $("#div_nuevo_pago").draggable({ handle: ".fixed_form_handle", containment: "window" });
                asignarValidacion();
                cargarMontoSugerido();
                inicializarDataTable();
            });

            function inicializarDataTable()
            {
                tabla_pagos = $('#tabla_pagos').dataTable({
                    "oLanguage": {
                        "sLengthMenu": "Mostrar _MENU_ pagos por página",
                        "sZeroRecords": "Debe agregar al menos un pago para continuar.",
                        "sInfo": "Mostrando _START_ a _END_ de _TOTAL_ pagos",
                        "sInfoEmpty": "Mostrando 0 a 0 de 0 pagos",
                        "sInfoFiltered": "(Encontrados de _MAX_ pagos)"
                    },
                    "aoColumns": [{"sWidth":"20%"},{"sWidth":"20%"},{"sWidth":"60%"}],
                    "bProcessing": true
                });
            }

            function conceptoChanged()
            {
                if($("#id_conceptoVal").val() == 2) $("#div_mes").css('visibility', 'visible');
                else $("#div_mes").css('visibility', 'hidden');
                cargarMontoSugerido();
            }

            function cargarMontoSugerido()
            {
                var id_concepto = $("#id_conceptoVal").val();
                $.post("/includes/acciones/cuentas/conceptos/get_monto_sugerido.php", {id_concepto: id_concepto}, function (data)
                {
                    $("#montoVal").val(data);
                    subtotal = Number(data).toFixed(2);
                    descuento_beca = (data * beca / 100).toFixed(2);
                    total = (subtotal - descuento_beca).toFixed(2);

                    actualizarMonto();
                });
            }

            function asignarValidacion()
            {
                $('#forma_nuevo_pago').validate({
                    rules:
                    {
                        "id_conceptoVal": { required: true },
                        "input_alumno": { required: true },
                        "montoVal": { required: true, number: true }
                    },
                    ignore: "",
                    messages: { "input_alumno": "Debe seleccionar un alumno" }
                });
            }

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
                            beca = alumno.beca;
                            descuento_beca = (subtotal * (beca / 100)).toFixed(2);
                            $("#id_alumnoVal").val(alumno.id_persona);
                            $("#input_alumno").val(alumno.apellido_paterno + " " + alumno.apellido_materno + " " + alumno.nombres);
                            $("#becaVal").val(beca + "%");
                            cargarDescuentos();
                        }
                    }
                });
                $("#buscador_alumnos").fadeOut();
            }

            function cargarDescuentos()
            {
                var id_concepto = $("#id_conceptoVal").val();
                $.post("/includes/acciones/cuentas/descuentos/get_descuento_alumno.php", {id_alumno:alumno.id_persona, id_concepto:id_concepto}, function (data)
                {
                    descuento_descuento = Number(data).toFixed(2);
                    $("#descuentoVal").val("$" + descuento_descuento);

                    actualizarMonto();
                });
            }

            function actualizarMonto()
            {
                total = (subtotal - descuento_beca - descuento_descuento).toFixed(2);
                if(total < 0) total = 0;
                console.log("actualizarMonto()");
                $("#montos_subtotal").html("$" + subtotal);
                if(descuento_beca > 0) $("#montos_beca").html("-$" + descuento_beca); else $("#montos_beca").html("$0.00");
                if(descuento_descuento > 0) $("#montos_descuento").html("-$" + descuento_descuento); else $("#montos_descuento").html("$0.00");
                $("#montos_total").html("$" + total);
                $("#nuevo_pago_monto").val(Number(total).toFixed(2));
                actualizarSummary();
            }

            function mostrarNuevoPago()
            {
                if(adeudo > 0)
                {
                    if($("#id_alumnoVal").val() > 0)
                    {
                        $("#nuevo_pago_monto").val(adeudo);
                        $("#nuevo_pago_descripcion").val("");
                        $("#div_nuevo_pago").fadeIn();
                    }
                    else alert("Debe elegir un alumno");
                }
                else alert("La cuenta ya está saldada. No puede agregar mas pagos.");
            }

            function agregar_detalle()
            {
                var montoPagar = $("#nuevo_pago_monto").val();

                if(montoPagar > adeudo)
                {
                    alert("El monto supera el adeudo.");
                    $("#nuevo_pago_monto").val(adeudo);
                }
                else if(montoPagar <= 0) alert("El monto debe de ser mayor a 0.");
                else if(!$.isNumeric(montoPagar)) alert("Debe de ingresar un número.");
                else
                {
                    $("#boton_nuevo_pago").prop('disabled', true);
                    var detalle =
                    {
                        monto: montoPagar,
                        descripcion: $("#nuevo_pago_descripcion").val(),
                        forma_pago: $("#nuevo_pago_forma_pago").val()
                    }
                    pagos.push(detalle);
                    $("#div_nuevo_pago").fadeOut("fast", function(){ $("#boton_nuevo_pago").prop('disabled', false); });
                    $('#tabla_pagos').dataTable().fnAddData([
                        "$" + detalle.monto,
                        $("#nuevo_pago_forma_pago option:selected").text(),
                        detalle.descripcion
                    ]);
                    actualizarSummary();
                }
            }

            function montoCambiado(caller)
            {
                var montoPrevio = $(caller).val();
                var nuevoMonto = montoPrevio;
                $("#datosMonto").html("$" + (montoPrevio * 1.0 + (montoPrevio * beca / 100) * 1.0) + " - $" + beca + "% ($" + (montoPrevio * beca / 100) + ") = $" + nuevoMonto );
                $("#nuevo_pago_monto").val(nuevoMonto);
                actualizarSummary();
            }

            function actualizarSummary()
            {
                $("#summary_total").html("$" + Number(total).toFixed(2));
                aPagar = 0.00;
                for(var i = 0; i < pagos.length; i++)
                {
                    aPagar += Number(pagos[i].monto);
                }
                $("#summary_pagar").html("$" + Number(aPagar).toFixed(2));
                adeudo = total - aPagar;
                if(adeudo > 0) $("#summary_adeudo").css('color', 'red');
                else $("#summary_adeudo").css('color','green');
                $("#summary_adeudo").html("$" + Number(adeudo).toFixed(2));
                if(adeudo == 0) $("#boton_final_aceptar").prop('disabled', false);
            }

            function agregarPago()
            {
                if($('#forma_nuevo_pago').valid())
                {
                    if(pagos.length > 0)
                    {
                        if(adeudo > 0)
                        {
                            if(confirm("La cuenta tendrá un adeudo de " + adeudo + ", ¿Desea continua?"))
                            {
                                realizarPago();
                            }
                        }
                        else
                        {
                            realizarPago();
                        }
                    }
                    else
                    {
                        alert("Debe realizar al menos 1 pago para registrar la transacción.");
                    }
                }
            }

            function realizarPago()
            {
                $("#boton_final_aceptar").prop('disabled', true);
                var id_concepto = $("#id_conceptoVal").val();
                var descripcion = $("#descripcionVal").val();
                var id_ciclo    = $("#cicloVal").val();
                $.ajax({
                    type: "POST",
                    url: "/includes/acciones/cuentas/pagos/nuevo.php",
                    data: "id_persona=" + alumno.id_persona + "&id_concepto=" + id_concepto
                        + "&monto=" + aPagar + "&descripcion="
                        + descripcion + "&pagos=" + JSON.stringify(pagos) + "&id_ciclo=" + id_ciclo,
                    success: function (data)
                    {
                        if(data !== "error")
                        {
                            alert("Pago realizado.");
                            if(confirm("¿Desea imprimir un recibo?"))
                            {
                                // Imprimir recibo
                            }
                            document.location = "/admin/cuentas/pagos/lista_pagos.php";
                        }
                        else alert("Error.");
                    }
                });
            }

            function clearPagos()
            {
                $("#tabla_pagos").dataTable().fnClearTable();
                pagos = [];
                actualizarSummary();
            }
        </script>
    </head>
    <body>
            <?php include("../../../includes/headerConfig.php"); ?>
            <div id="principal" class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2" >
        		<div id="area_trabajo">
                    <form id="forma_nuevo_pago" action="../../../includes/acciones/cuentas/pagos/nuevo.php" method="post" >
                        <input type="hidden" id="id_alumnoVal" name="id_alumnoVal" />
                        <div class="form_row_2">
                            <label class="form_label">Alumno</label>
                            <input id="input_alumno" type="text" class="form_input" ondblclick="$('#buscador_alumnos').fadeIn();" readonly name="input_alumno" />
                        </div>
                        <div class="form_row_4">
                            <label class="form_label">Concepto</label>
                            <select id="id_conceptoVal" name="id_conceptoVal" class="form_input" onchange="conceptoChanged()" >
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
                        <div class="form_row_4" id="div_mes" >
                            <label class="form_label">Mes</label>
                            <select id="mesVal" class="form_input" >
                                <option value="1" >Enero</option>
                                <option value="2" >Febrero</option>
                                <option value="3" >Marzo</option>
                                <option value="4" >Abril</option>
                                <option value="5" >Mayo</option>
                                <option value="6" >Junio</option>
                                <option value="7" >Julio</option>
                                <option value="8" >Agosto</option>
                                <option value="9" >Septiembre</option>
                                <option value="10" >Octubre</option>
                                <option value="11" >Noviembre</option>
                                <option value="12" >Diciembre</option>
                            </select>
                        </div>
                        <div class="form_row_4">
                            <label class="form_label">Ciclo escolar</label>
                            <select id="cicloVal" class="form_input" name="cicloVal" >
                            <?php
                            $ciclos = CicloEscolar::getListaProximos();
                            if(is_array($ciclos))
                            {
                                foreach($ciclos as $ciclo)
                                {
                                    echo "<option value='".$ciclo['id_ciclo_escolar']."' >".$ciclo['ciclo']."</option>";
                                }
                            }
                            ?>
                            </select>
                        </div>

                        <div class="form_row_4">
                            <label class="form_label">Monto</label>
                            <input type="text" class="form_input" id="montoVal" name="montoVal" onchange="montoCambiado(this)" readonly />
                        </div>

                        <div id="datosMonto">
                            <div class="datosMonto_row">
                                <label>Sub-total</label>
                                <div id="montos_subtotal">$0.00</div>
                            </div>
                            <div class="datosMonto_row">
                                <label>Beca</label>
                                <div id="montos_beca">$0.00</div>
                            </div>
                            <div class="datosMonto_row">
                                <label>Descuento</label>
                                <div id="montos_descuento">$0.00</div>
                            </div>
                            <hr />
                            <div class="datosMonto_row">
                                <label>Total</label>
                                <div id="montos_total">$0.00</div>
                            </div>
                        </div>

                        <div id="div_descuentos">
                            <div id="info_beca">
                                <label>Beca</label>
                                <input type="text" readonly="readonly" id="becaVal" />
                            </div>
                            <div id="info_descuento">
                                <label>Descuento</label>
                                <input type="text" id="descuentoVal" readonly="readonly" >
                            </div>
                        </div>
                        <div class="form_row">
                            <label class="form_label">Descripción</label>
                            <textarea id="descripcionVal" name="descripcionVal" ></textarea>
                        </div>
                    </form>

                    <div id="div_pagos" class="table-responsive">

                        <table id="tabla_pagos" class="table">
                            <thead>
                            <tr>
                               <th colspan="3">Pagos a realizar</th>
                            </tr>
                            <tr>
                                <th>Monto</th>
                                <th>Forma de pago</th>
                                <th>Descripción</th>
                            </tr>
                            </thead>
                            <tbody>
                                <!-- Dinámico -->
                            </tbody>
                        </table>
                        <input type="button" class="form_submit btn btn-success" value="Nuevo pago" onclick="mostrarNuevoPago();"/>
                        <input type="button" class="form_submit btn btn-danger" value="Eliminar" onclick="clearPagos();"/>
                    </div>

                    <div id="div_summary">
                        <div class="summary_row">
                            <label>Total</label><div class="summary_row_right" id="summary_total"></div>
                        </div>
                        <div class="summary_row" style="border-bottom: 1px solid #CCC;">
                            <label>A pagar</label><div class="summary_row_right" id="summary_pagar"></div>
                        </div>
                        <div class="summary_row">
                            <label>Adeudo</label><div class="summary_row_right" id="summary_adeudo"></div>
                        </div>
                    </div>

                    <div class="form_row">
                        <input id="boton_final_aceptar" type="button" class="form_submit btn btn-primary" value="Aceptar" style="margin: auto 45%;" onclick="agregarPago()" />
                    </div>

                </div>

                <div class="buscadorAjax" id="buscador_alumnos" >
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

                <div class="fixed_form" id="div_nuevo_pago" >
                    <div class="fixed_form_handle">
                        <img onclick="$(this).parent(0).parent(0).fadeOut()" alt="X" src="../../../media/iconos/icon_close.gif" />
                    </div>
                    <div class="fixed_form_content">
                        <div class="fixed_form_row">
                            <label>Monto</label>
                            <input type="text" class="fixed_form_value" id="nuevo_pago_monto" />
                        </div>
                        <div class="fixed_form_row">
                            <label>Descripción</label>
                            <input type="text" class="fixed_form_value" id="nuevo_pago_descripcion" />
                        </div>
                        <div class="fixed_form_row">
                            <label>Forma de pago</label>
                            <select class="fixed_form_value" id="nuevo_pago_forma_pago" >
                                <?php
                                $formas_pago = FormaPago::getFormasPago();
                                if(is_array($formas_pago))
                                {
                                    foreach($formas_pago as $forma)
                                    {
                                        echo "<option value='".$forma['id_forma_pago']."' >".$forma['forma_pago']."</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="fixed_form_row">
                            <input type="button" id="boton_nuevo_pago" class="fixed_form_button" onclick="agregar_detalle()" value="Aceptar" />
                        </div>
                    </div>
                </div>

            </div>
    </body>
</html>
