<?php
$id_modulo = 15; // Ciclo Escolar - Nuevo
include_once("../../includes/validar_admin.php");
include_once("../../includes/clases/class_lib.php");
include_once("../../includes/validar_acceso.php");
$grados = Grado::getLista();
$materias = Materia::getLista();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Sistema Integral Meze - Nuevo ciclo escolar</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<script src="../../js/jquery.js" type="text/javascript"></script>
		<script src="../../js/bootstrap.js" type="text/javascript"></script>
		<script src="../../plugins/assets/js/appear.min.js" type="text/javascript"></script>
		<script src="../../plugins/assets/js/animations.js" type="text/javascript"></script>
        <link rel="stylesheet" href="../../estilo/general.css" />
        <link rel="stylesheet" href="../../estilo/formas_mini.css" />
        <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
        <script src='//code.jquery.com/ui/1.10.4/jquery-ui.js' ></script>
        <script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
        <script src="/librerias/messages_es.js" ></script>
        <script src="/librerias/jquery.ui.datepicker-es.js"></script>
        <script>
            $(document).ready(function ()
            {
                setDatepickers();
                setValidacion();
            });

            function setDatepickers()
            {
                $("#fecha_inicio").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: "dd 'de' MM 'del' yy",
                    altField: "#fecha_inicioVal",
                    altFormat: "yy-mm-dd",
                    minDate: -20, maxDate: "+5Y"
                });

                $("#fecha_fin").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: "dd 'de' MM 'del' yy",
                    altField: "#fecha_finVal",
                    altFormat: "yy-mm-dd",
                    minDate: -20, maxDate: "+5Y"
                });
            }

            function setValidacion()
            {
                $('#form_nuevo_ciclo').validate({
                    ignore: [],
                    rules:
                    {
                        "fecha_inicio": { required: true },
                        "fecha_fin": { required: true }
                    }
                });
            }

            function insertarCiclo()
            {
                console.log("insertarCiclo()");
                $("#boton_aceptar").prop('disabled', true);
                // Validar inputs
                if(!$('#form_nuevo_ciclo').valid())
                {
                    $("#boton_aceptar").prop('disabled', false);
                    return false;
                }

                // Obtener los valores de los inputs escondidos (Formato yy-mm-dd)
                var fecha_inicio    = $("#fecha_inicioVal").val();
                var fecha_fin       = $("#fecha_finVal").val();

                // Validar lógica de fechas: mas de 60 dias y termina despúes de empezar
                if(!validarFechas(fecha_inicio, fecha_fin)) return false;

                // Checar empalme con otros ciclos escolares (PHP)
                $.ajax({
                    type: "POST",
                    url: "/includes/acciones/ciclos_escolares/checar_empalme.php",
                    data: "fecha_inicio=" + fecha_inicio + "&fecha_fin=" + fecha_fin,
                    success: function (data)
                    {
                        if(data == 0)
                        {
                            $.ajax({
                                type: "POST",
                                url: "/includes/acciones/ciclos_escolares/insert.php",
                                data: "fecha_inicio=" + fecha_inicio + "&fecha_fin=" + fecha_fin,
                                success: function (data)
                                {
                                    if (data == 1)
                                    {
                                        document.location.href = "/admin/ciclos_escolares/index.php";
                                    }
                                    else alert("Error.");
                                }
                            });
                        }
                        else
                        {
                            alert("Error. Existe un empalme con los ciclos escolares.");
                            $("#boton_aceptar").prop('disabled', false);
                        }
                    }
                });
            }

            function validarFechas(fecha1, fecha2)
            {
                var date1 = Date.parse(fecha1);
                var date2 = Date.parse(fecha2);

                // The number of milliseconds in one day
                var ONE_DAY = 1000 * 60 * 60 * 24;

                // Calculate the difference in milliseconds
                var difference_ms = date2 - date1;

                // Convert back to days and return
                var duracion = Math.round(difference_ms/ONE_DAY);

                if(duracion > 60)
                {
                    return true;
                }
                else if(duracion < 0)
                {
                    alert("Error. El ciclo escolar no puede terminar antes de iniciar");
                    return false;
                }
                else
                {
                    alert("Error. La duración del ciclo escolar seria muy corta (" + duracion + " dias)");
                    $("#boton_aceptar").removeAttr('disabled');
                    return false;
                }
            }
        </script>
    </head>
    <body>
            <?php include("../../includes/header.php"); ?>
            	<div id="principal" class="col-sm-9 col-sm-offset-3 col-md-8 col-md-offset-4" style="margin-top:30px;">
        			<div id="area_trabajo">

					<h1>Nuevo ciclo escolar</h1>

					<form id="form_nuevo_ciclo" >

						<div style="width: 100%; overflow: auto">
							<div class="form_row_3">
								<input type="hidden" id="fecha_inicioVal" name="fecha_inicioVal" />
								<label class="form_label">Fecha de inicio</label>
								<input type="text" name="fecha_inicio" readonly="" class="form_input form-control" id="fecha_inicio" >
							</div>

							<div class="form_row_3">
								<input type="hidden" id="fecha_finVal" name="fecha_finVal" />
								<label class="form_label">Fecha de cierre</label>
								<input type="text" name="fecha_fin" readonly="" class="form_input form-control" id="fecha_fin" >
							</div>
						</div>

					</form>

					<input type="button" value="Aceptar" class="btn btn-primary" id="boton_aceptar" onclick="insertarCiclo()" >

            </div>
        </div>
    </body>
</html>
