<?php
/** Created by Gustavo Carrillo
 * gus@yozki.net
 * @yozki
 */

include_once("../includes/validar_maestro.php");
include_once("../includes/clases/class_lib.php");
extract($_GET);

$maestro = new Maestro($_SESSION['id_persona']);
if(!$maestro->teachesClass($id_clase)) header('Location: /maestros/grupos.php?error=1');
$clase = new Clase($id_clase);
$grupo = new Grupo($clase->id_grupo);
$ciclo_escolar = CicloEscolar::getActual();
$alumnos = $grupo->getAlumnos();
?>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Sistema Integral Meze - Asistencia</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<script src="../js/jquery.js" type="text/javascript"></script>
		<script src="../js/bootstrap.js" type="text/javascript"></script>
		<script src="../plugins/assets/js/appear.min.js" type="text/javascript"></script>
		<script src="../plugins/assets/js/animations.js" type="text/javascript"></script>
        <link rel="stylesheet" href="../estilo/general.css" />
        <style>
            #tabla_asistencia
            {
                margin: 10px auto;
            }
        </style>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
        <script>
            var id_clase = <?php echo $id_clase; ?>;
            function clickAceptarAsistencias()
            {
                if(confirm('¿Seguro que desea enviar las faltas?'))
                {
                    $("#boton_aceptar").attr('disabled','disabled');
                    var faltas = [];
                    $(".chk_asistencia").each(function()
                    {
                        if(!$(this).prop('checked')) faltas.push($(this).val());
                    });

                    $.ajax({
                        type: "POST",
                        url: "/includes/acciones/asistencias/asignar_faltas.php",
                        data: "id_clase=" + id_clase + "&faltas=" + JSON.stringify(faltas),
                        success: function (data)
                        {
                            if(data == 1)
                            {
                                alert("Faltas registradas.");
                                window.location.href = '/index.php';
                            }
                        }
                    });
                }
            }
        </script>
    </head>
    <body>
        <?php include("../includes/header.php"); ?>
        <div id="principal" class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2" style="margin-top:20px;">
                <div id="area_trabajo">

                <table id="tabla_asistencia" class="table" >
                    <thead>
                        <tr>
                            <th>Alumno</th>
                            <th>Asistencia</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        if(is_array($alumnos))
                        {
                            foreach($alumnos as $alumno)
                            {
                                echo "
                                    <tr>
                                        <td>".$alumno['nombres']." ".$alumno['apellido_paterno']." ".$alumno['apellido_materno']."</td>
                                        <td><input type='checkbox' class='chk_asistencia' checked value='".$alumno['id_alumno']."' /></td>
                                    </tr>
                                ";
                            }
                        }
                    ?>
                    </tbody>
                </table>

                <button id="boton_aceptar" style="margin: 0px 40%;" class="btn btn-primary" onclick="clickAceptarAsistencias();"><span class="glyphicon glyphicon-ok"></span>Aceptar</button>

            </div>
        </div>
    </body>
</html>