<?php
include("../../includes/validar_admin.php");
include_once("../../includes/clases/class_lib.php");
$alumnos = Alumno::getLista();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Sistema Integral Meze - Alumnos</title>
        <link rel="stylesheet" href="../../estilo/general.css" />
        <link rel="stylesheet" href="../../estilo/jquery.dataTables.css" />
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
                    },
                    "iDisplayLength": 25
                }); 
            }
        </script>
    </head>
    <body>
        <div id="wrapper">
            <?php include("../../includes/header.php"); ?>
            <div id="content">

                <div id="inner_content">

                    <table id="tabla_alumnos" >
                        <thead>
                            <tr>
                                <th>Matrícula</th>
                                <th>Apellido paterno</th>
                                <th>Apellido materno</th>
                                <th>Nombres</th>
                                <th>Area</th>
                                <th>Grado</th>
                                <th>Grupo</th>
                                <th></th>
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
                                            <td>".$alumno['matricula']."</td>
                                            <td>".$alumno['apellido_paterno']."</td>
                                            <td>".$alumno['apellido_materno']."</td>
                                            <td>".$alumno['nombres']."</td>
                                            <td>".$alumno['area']."</td>
                                            <td>".$alumno['grado']."</td>
                                            <td>".$alumno['grupo']."</td>
                                            <td>
                                                <a href='perfil.php?id_alumno=".$alumno['id_persona']."' >
                                                    <img src='/media/iconos/icon_profile.png' alt='P' />
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

                <a href="inscribir.php" class="link_estilizado" >Inscribir alumno</a>

            </div>
        </div>
    </body>
</html>
