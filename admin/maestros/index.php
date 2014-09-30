<?php
    include("../../includes/validar_admin.php");
    include_once("../../includes/clases/class_lib.php");
    $maestros = Maestro::getLista();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Sistema Integral Meze - Maestros</title>
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
                $('#tabla_maestros').dataTable({
                    "oLanguage": {
                        "sLengthMenu": "Mostrar _MENU_ maestros por página",
                        "sZeroRecords": "No existen maestros",
                        "sInfo": "Mostrando _START_ a _END_ de _TOTAL_ maestros",
                        "sInfoEmpty": "Mostrando 0 a 0 de 0 maestros",
                        "sInfoFiltered": "(Encontrados de _MAX_ maestros)"
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
                
                    <h1>Maestros</h1>

                    <button onclick="location.href='nuevo.php'" >Nuevo</button>

                    <table id="tabla_maestros" >
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Matrícula</th>
                                <th>Apellido paterno</th>
                                <th>Apellido materno</th>
                                <th>Nombres</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            if(is_array($maestros))
                            {
                                foreach($maestros as $maestro)
                                {
                                    echo "
                                        <tr>
                                            <td>".$maestro['id_persona']."</td>
                                            <td>".$maestro['matricula']."</td>
                                            <td>".$maestro['apellido_paterno']."</td>
                                            <td>".$maestro['apellido_materno']."</td>
                                            <td>".$maestro['nombres']."</td>
                                            <td>
                                                <a href='../../admin/maestros/perfil.php?id_maestro=".$maestro['id_persona']."' >
                                                    <img src='../../media/iconos/icon_profile.png' alt='P' >
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
    </body>
</html>
