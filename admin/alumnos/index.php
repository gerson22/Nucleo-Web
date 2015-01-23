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
        <script src="http://cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js" ></script>
        <script>
            $(document).ready(function ()
            {
                declararDataTable();
            });

            function declararDataTable()
            {
                var DT = $('#tabla_alumnos').dataTable({
                    "columnDefs": [
                        { "visible": false, "targets": [ 8 ] }
                    ],
                    "language": {
                        "lengthMenu": "Mostrar _MENU_ alumnos por página",
                        "zeroRecords": "No existen alumnos",
                        "info": "Mostrando _START_ a _END_ de _TOTAL_ alumnos",
                        "infoEmpty": "Mostrando 0 a 0 de 0 alumnos",
                        "infoFiltered": "(Encontrados de _MAX_ alumnos)"
                    },
                    "displayLength": 25
                });

                DT.fnSort( [ [1,'asc']  ]);
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
                                            <td>".$alumno['Colonia']."</td>
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
