<?php
include_once("../includes/validar_maestro.php");
include_once("../includes/clases/class_lib.php");
extract($_GET);
# id_clase

$clase = new Clase($id_clase);
$grupo = new Grupo($clase->id_grupo);
$alumnos = $grupo->getAlumnos();
if(is_null($clase->id_clase)){ header('Location: /index.php'); exit; }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Sistema Integral Meze - Clase</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<script src="../js/jquery.js" type="text/javascript"></script>
		<script src="../js/bootstrap.js" type="text/javascript"></script>
		<script src="../plugins/assets/js/appear.min.js" type="text/javascript"></script>
		<script src="../plugins/assets/js/animations.js" type="text/javascript"></script>
        <link rel="stylesheet" href="../../estilo/general.css" />
        <link rel="stylesheet" href="../../estilo/index.css" />
        <link rel="stylesheet" href="../../estilo/jquery.dataTables.css" />
        <style>

        </style>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
        <script src="../../librerias/jquery.dataTables.min.js" ></script>
        <script>
            $(document).ready(function ()
            {
                declararDataTable();
            });

            function declararDataTable()
            {
                $('#tabla_alumnos').dataTable({
                    "oLanguage": {
                        "sLengthMenu": "Mostrar _MENU_ alumnos por página",
                        "sZeroRecords": "No existen alumnos",
                        "sInfo": "Mostrando _START_ a _END_ de _TOTAL_ alumnos",
                        "sInfoEmpty": "Mostrando 0 a 0 de 0 alumnos",
                        "sInfoFiltered": "(Encontrados de _MAX_ alumnos)"
                    }
                });
            }
        </script>
    </head>
    <body>
            <?php include("../includes/header.php"); ?>
            <div id="principal" class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2" style="margin-top:20px;">
                <div id="area_trabajo">
                <table id="tabla_alumnos">
                    <thead>
                        <tr>
                            <th colspan="3">Alumnos</th>
                        </tr>
                        <tr>
                            <th>Apellido paterno</th>
                            <th>Apellido materno</th>
                            <th>Nombres</th>
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
                                            <td>".$alumno['apellido_paterno']."</td>
                                            <td>".$alumno['apellido_materno']."</td>
                                            <td>".$alumno['nombres']."</td>
                                        </tr>
                                    ";
                                }
                            }
                        ?>
                    </tbody>
                </table>

                <div class="index_section">
                    <div class="index_section_title">Personal</div>
                    <a href="admin/maestros/index.php" class="section_option">
                        <img src="media/iconos/icon_teacher.png" alt="Maestros">
                        Maestros
                    </a>
                    <a href="admin/alumnos/index.php" class="section_option">
                        <img src="media/iconos/icon_student.png" alt="Alumnos">
                        Alumnos
                    </a>
                </div>

            </div>
        </div>
    </body>
</html>