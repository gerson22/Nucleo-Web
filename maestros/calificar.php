<?php
include_once("../includes/validar_maestro.php");
include_once("../includes/clases/class_lib.php");

$maestro = new Maestro($_SESSION['id_persona']);

if(isset($_GET['id_grupo'])) $grupo = new Grupo($_GET['id_grupo']);
$materias = $grupo->getClasesMaestro($maestro->id_persona);
$grado = new Grado($grupo->id_grado);
$area = new Area($grado->id_area);
$no_parciales = $area->no_parciales;
$no_materias = count($materias);
$alumnos = $grupo->getAlumnos();
?>

<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Sistema Integral Meze - Calificar</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<script src="../js/jquery.js" type="text/javascript"></script>
		<script src="../js/bootstrap.js" type="text/javascript"></script>
		<script src="../plugins/assets/js/appear.min.js" type="text/javascript"></script>
		<script src="../plugins/assets/js/animations.js" type="text/javascript"></script>
        <link rel="stylesheet" href="../estilo/general.css" />
        <link rel="stylesheet" href="../estilo/calificar.css" />
        <link rel="stylesheet" href="../estilo/formas_extensas.css" />
        <style>
            #boton_aceptar
            {
                margin: 20px 0;
            }
        </style>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
    </head>
    <body>
            <?php include("../includes/header.php"); ?>
            <div id="principal" class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2" style="margin-top:20px;">
                <div id="area_trabajo">
                <h4><?php echo "Calificaciones de ".$grupo->getGrado()." ".$grupo->grupo." de ".$area->area; ?></h4>

                <div class="form_row_3">
                    <label for="parcialVal" class="form_label">Parcial</label>
                    <select id="parcialVal" name="parcialVal" class="form_input" onChange='parcialCambiado()'>
                        <?php
                            for($i = 1; $i <= $no_parciales; $i++)
                            {
                                echo "<option value='".$i."' >".$i."</option>";
                            }
                        ?>
                    </select>
                </div>

                <div id="div_calificaciones" class="table-responsive" >

                    <table id="tabla_calificaciones" class="table">
                        <thead>
                            <tr>
                                <th>Alumno</th>
                                <?php
                                if(is_array($materias))
                                {
                                    foreach($materias as $materia)
                                    {
                                        echo "<th>".$materia['materia']."</th>";
                                    }
                                }
                                ?>
                                <th>Promedio</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if(is_array($alumnos))
                            {
                                foreach($alumnos as $alumno)
                                {
                                    $alu = $alumno['id_alumno'];
                                    echo "
                                        <tr class='cali_row' >
                                            <td>".$alumno['nombres']." ".$alumno['apellido_paterno']."</td>";
                                    foreach($materias as $materia)
                                    {
                                        $cla = $materia['id_clase'];
                                        echo "<td>
                                            <input
                                                class='calificacion'
                                                data-alu='".$alu."'
                                                data-cla='".$cla."'
                                                onkeyup='validarCal(this)' />
                                        </td>";
                                    }
                                    echo "
                                            <td class='promedio'></td>
                                        </tr>
                                    ";
                                }
                            }
                            ?>
                        </tbody>
                    </table>

                </div>

                <button id="boton_aceptar" onclick="aceptarClicked()" class="btn btn-primary" ><span class="glyphicon glyphicon-ok"></span> Aceptar</button>

            </div>
        </div>
    </body>
    <script>
        var id_grupo = <?php echo $grupo->id_grupo; ?>;

        function calificacionCambiada(caller)
        {
            var original = caller.defaultValue;
            var nuevo = $(caller).val();

            if (isNaN(nuevo) || nuevo < 0 || nuevo > 10) $(caller).val(0);

            if (original.length == 0) caller.className = caller.className + " insert";
            else caller.className = caller.className + " update";
        }

        function parcialCambiado()
        {
            $.ajax({
                type: "POST",
                url: "/includes/acciones/calificaciones/JSONgetCalificaciones.php",
                data: "id_grupo=" + id_grupo + "&parcial=" + $("#parcialVal").val(),
                dataType: 'json',
                success: function (data)
                {
                    var calificaciones = data;
                    llenarTabla(calificaciones);
                }
            });
        }

        function aceptarClicked()
        {
            if(confirm("¿Desea actualizar los cambios?"))
            {
                var parcial = $("#parcialVal").val();
                var calificaciones = [];

                $(".calificacion").each(function(){
                    var calTMP = {};

                    calTMP.calificacion = $(this).val();
                    calTMP.alumno       = $(this).attr('data-alu');
                    calTMP.clase        = $(this).attr('data-cla');

                    if(calTMP.calificacion > 0 && !calTMP.calificacion.isEmptyObject && !isNaN(calTMP.calificacion))
                        calificaciones.push(calTMP);
                });

                $.ajax({
                    type: "POST",
                    url: "/includes/acciones/calificaciones/update.php",
                    data: "parcial=" + parcial + "&calificaciones=" + JSON.stringify(calificaciones),
                    success: function (data)
                    {
                        if(data == 1)
                        {
                            alert("Datos actualizados.");
                            parcialCambiado();
                        }
                        else alert("Error.");
                    }
                });
            }
        }

        function llenarTabla(calificaciones)
        {
            limpiarTabla();
            // Por cada clase
            $.each(calificaciones, function(i, obj){

                var id_clase = obj.id_clase;
                var calis = obj.calificaciones;

                // Cada calificación
                $.each(calis, function(j, obj2){
                    var id_alumno = obj2.alumno;
                    var calificacion = obj2.calificacion;

                    $('input[data-alu='+id_alumno+'][data-cla='+id_clase+']').val(calificacion);
                });
            });

            calcularPromedios();
        }

        function limpiarTabla()
        {
            $(".calificacion").each(function(){ $(this).val(""); });
            $(".promedio").each(function(){ $(this).html(""); });
        }

        function validarCal(caller)
        {
            var calTMP = $(caller).val();
            if(calTMP < 0) $(caller).val(0);
            if(calTMP > 100) $(caller).val(100);
            if(isNaN(calTMP)) $(caller).val("");

            calcularPromedios();
        }

        function calcularPromedios()
        {
            $(".cali_row").each(function(){
                var promedio = 0;
                var periodos = 0;
                $(this).find('.calificacion').each(function(){
                    if($(this).val() > 0) periodos = periodos + 1;
                    promedio += $(this).val() * 1.0;
                });
                if(periodos > 0) $(this).children('.promedio').html((promedio / periodos).toFixed(2));
            });
        }

        /** Document ready */
        parcialCambiado();

    </script>
</html>