<?php
include_once("../../includes/validar_admin.php");
include_once("../../includes/clases/class_lib.php");
$materias = Materia::getLista();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Sistema Integral Meze - Materias</title>
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
                $('#tabla_materias').dataTable({
                    "oLanguage": {
                        "sLengthMenu": "Mostrar _MENU_ materias por página",
                        "sZeroRecords": "No existen materias",
                        "sInfo": "Mostrando _START_ a _END_ de _TOTAL_ materias",
                        "sInfoEmpty": "Mostrando 0 a 0 de 0 materias",
                        "sInfoFiltered": "(Encontrados de _MAX_ materias)"
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
                
                    <h2>Materias</h2>

                    <button onclick="location.href='nueva.php'" >Nueva</button>

                    <table id="tabla_materias" >
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Materia</th>
                                <th>Area</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            if(is_array($materias))
                            {
                                foreach($materias as $materia)
                                {
                                    echo "
                                        <tr>
                                            <td>".$materia['id_materia']."</td>
                                            <td>".$materia['materia']."</td>
                                            <td>".$materia['area']."</td>
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
