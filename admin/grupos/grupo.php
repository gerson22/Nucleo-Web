<?php
include("../../includes/validar_admin.php");
include_once("../../includes/clases/class_lib.php");
extract($_GET);
# id_grupo

$grupo = new Grupo($id_grupo);
if(is_null($grupo->id_grupo)){ header('Location: /admin/grupos/index.php'); exit; }
$grado = new Grado($grupo->id_grado);
$ciclo_actual = CicloEscolar::getActual();
$clases = $grupo->getClases();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Sistema Integral Meze - Perfil de grupo</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<script src="../../js/jquery.js" type="text/javascript"></script>
		<script src="../../js/bootstrap.js" type="text/javascript"></script>
		<script src="../../plugins/assets/js/appear.min.js" type="text/javascript"></script>
		<script src="../../plugins/assets/js/animations.js" type="text/javascript"></script>
        <link rel="stylesheet" href="../../estilo/general.css" />
        <link rel="stylesheet" href="../../estilo/jquery.dataTables.css" />
        <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
        <style>
            #datos_generales
            {
                width: 1000px;
                padding: 10px 0;
                overflow: auto;
            }
            
            .datos_generales_row
            {
                width: 500px;
                float: left;
                margin: 10px 0;
            }
            
            .datos_generales_label
            {
                width: 100px;
                float: left;
            }
            
            .datos_generales_value
            {
                float: left;
                font-weight: bold;
                margin-left: 10px;
                width: 380px;
            }

            #tabs{ font-size:12px; }

            #div_cambio_maestro
            {
                background-color: #fff;
                border: 1px solid #506aa0;
                display: none;
                height: 120px;
                left: 500px;
                overflow: auto;
                position: fixed;
                top: 200px;
                width: 250px;
                z-index: 10;
            }

            #div_cambio_maestro_inner
            {
                padding: 10px;
            }

            .barra
            {
                background-color: #506aa0;
                float: left;
                width: 100%;
            }

            .barra img
            {
                margin: 2px;
                float: right;
            }

            #div_cambio_maestro_inner label
            {
                width: 80%;
            }

            .imagen_icono
            {
                width: 20px;
            }
        </style>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
        <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
        <script src="../../librerias/jquery.dataTables.min.js" ></script>
        <script>
            $(document).ready(function ()
            {
                declararDataTables();
                $("#tabs").tabs();
            });

            function declararDataTables()
            {
                $('#tabla_clases').dataTable({
                    "oLanguage": {
                        "sLengthMenu": "Mostrar _MENU_ clases por página",
                        "sZeroRecords": "No existen clases",
                        "sInfo": "Mostrando _START_ a _END_ de _TOTAL_ clases",
                        "sInfoEmpty": "Mostrando 0 a 0 de 0 clases",
                        "sInfoFiltered": "(Encontrados de _MAX_ clases)"
                    }
                });

                $('#tabla_alumnos').dataTable({
                    "oLanguage": {
                        "sLengthMenu": "Mostrar _MENU_ alumnos por página",
                        "sZeroRecords": "No existen alumnos inscritos al grupo",
                        "sInfo": "Mostrando _START_ a _END_ de _TOTAL_ alumnos",
                        "sInfoEmpty": "Mostrando 0 a 0 de 0 alumnos",
                        "sInfoFiltered": "(Encontrados de _MAX_ clases)"
                    }
                });
            }
        </script>
    </head>
    <body>
            <?php include("../../includes/header.php"); ?>
            <div id="principal" class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2" style="margin-top:20px;">
                <div id="area_trabajo">
                <div id="datos_generales">
                    <div class="datos_generales_row">
                        <div class="datos_generales_label">Grupo:</div>
                        <div class="datos_generales_value"><?php echo $grupo->grupo; ?></div>
                    </div>
                    <div class="datos_generales_row">
                        <div class="datos_generales_label">Grado:</div>
                        <div class="datos_generales_value"><?php echo $grado->grado; ?></div>
                    </div>
                    <div class="datos_generales_row">
                        <div class="datos_generales_label">Area:</div>
                        <div class="datos_generales_value"><?php echo $grupo->getArea(); ?></div>
                    </div>
                    <div class="datos_generales_row">
                        <div class="datos_generales_label">Ciclo escolar:</div>
                        <div class="datos_generales_value"><?php echo $ciclo_actual->fecha_inicio; ?></div>
                    </div>
                </div>

                <div id="tabs">
                    <ul>
                        <li><a href="#tab-materias">Materias</a></li>
                        <li><a href="#tab-alumnos">Alumnos</a></li>
                    </ul>
                    <div id="tab-materias">
                        <table id="tabla_clases">
                            <thead>
                                <tr>
                                    <th>Materia</th>
                                    <th>Maestro</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            if(is_array($clases))
                            {
                                foreach($clases as $clases)
                                {
                                    echo "
                                        <tr>
                                            <td>".$clases['materia']."</td>
                                            <td>
                                                <img
                                                    class='imagen_icono'
                                                    src='/media/iconos/icon_modify.png'
                                                    onclick='mostrarCambioMaestro(".$clases['id_clase'].")'
                                                />
                                                ".$clases['nombre']."
                                            </td>
                                        </tr>
                                    ";
                                }
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                    <div id="tab-alumnos" class="table-responsive">
                        <table id="tabla_alumnos" class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Apellido paterno</th>
                                    <th>Apellido materno</th>
                                    <th>Nombres</th>
                                    <th>Perfil</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            $alumnos = $grupo->getAlumnos();
                            if(is_array($alumnos))
                            {
                                foreach($alumnos as $alumno)
                                {
                                    echo "
                                        <tr>
                                            <td>".$alumno['id_alumno']."</td>
                                            <td>".$alumno['apellido_paterno']."</td>
                                            <td>".$alumno['apellido_materno']."</td>
                                            <td>".$alumno['nombres']."</td>
                                            <td>
                                                <a href='/admin/alumnos/perfil.php?id_alumno=".$alumno['id_alumno']."'>
                                                    <img alt='P' src='/media/iconos/icon_profile.png'>
                                                </a>
                                            </td>
                                        </tr>
                                    ";
                                }
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div id="div_cambio_maestro">
            <div class="barra">
                <img src='/media/iconos/icon_close.gif' alt="Cerrar" onclick="$(this).parent().parent().fadeOut()" />
            </div>
            <div id="div_cambio_maestro_inner">
                <label>Maestro:</label>
                <select id="select_nuevo_maestro">
                    <?php
                    $maestros = Maestro::getLista();
                    if(is_array($maestros))
                    {
                        foreach($maestros as $maestro)
                        {
                            echo "
                            <option value=".$maestro['id_persona'].">
                                ".$maestro['nombres']." ".$maestro['apellido_paterno']."
                            </option>
                        ";
                        }
                    }
                    ?>
                </select>
                <input type="button" class="form_submit" value="Aceptar" onclick="cambiarMaestro(this)" />
            </div>
        </div>

    </body>
    <script>
        var id_clase_seleccionada = 0;

        function mostrarCambioMaestro(id_clase)
        {
            id_clase_seleccionada = id_clase;
            $("#div_cambio_maestro").fadeIn();
        }

        function cambiarMaestro(caller)
        {
            var id_maestro = $("#select_nuevo_maestro").val();
            if(id_maestro)
            {
                if(confirm("¿Desea cambiar el docente?"))
                {
                    $(caller).attr('disabled', 'disabled');
                    $("#div_cambio_maestro").hide();
                    $.ajax({
                        type: "POST",
                        url: "/includes/acciones/clases/cambiar_maestro.php",
                        data: "id_clase=" + id_clase_seleccionada + "&id_maestro=" + id_maestro,
                        success: function (data)
                        {
                            if(data == 1)
                            {
                                alert("Maestro cambiado");
                                location.reload();
                            }
                        }
                    });
                }
            }
            else
            {
                alert("Debe seleccionar un docente");
            }
        }

        /** Document Ready */
        $("#div_cambio_maestro").draggable({ handle: ".barra" });
    </script>
</html>