<?php
include_once("../includes/validar_maestro.php");
include_once("../includes/clases/class_lib.php");
$maestro = new Maestro($_SESSION['id_persona']);
$grupos = $maestro->getGrupos();
$clases = $maestro->getClases();
?>
<!DOCTYPE html>

<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Sistema Integral Meze - Grupos</title>
        <link rel="stylesheet" href="../estilo/general.css" />
        <link rel="stylesheet" href="../../estilo/jquery.dataTables.css" />
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
        <script src="../../librerias/jquery.dataTables.min.js" ></script>
    </head>
    <body>
        <div id="wrapper">
            <?php include("../includes/header.php"); ?>
            <div id="content">
                <div id="inner_content">

                    <div id="div_lista_grupos">
                        <?php
                        if(is_array($grupos))
                        {
                            echo "<h3>Lista de grupos con materias a su cargo</h3>";
                            echo "
                                <table id='tabla_grupos' >
                                    <thead>
                                        <tr>
                                            <th>Grupo</th>
                                            <th>Área</th>
                                            <th>Calificar</th>
                                            <th>Asistencia</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                            ";
                            foreach($grupos as $grupo)
                            {
                                echo "
                                    <tr>
                                        <td>".$grupo['grupo']."</td>
                                        <td>".$grupo['area']."</td>
                                        <td>
                                            <a href='calificar.php?id_grupo=".$grupo['id_grupo']."'>
                                                <img alt='Calificar' src='/media/iconos/icon_calificar.png' />
                                            </a>
                                        </td>
                                        <td>
                                            <a href='calificar.php?id_grupo=".$grupo['id_grupo']."'>
                                                <img alt='Calificar' src='/media/iconos/icon_stats.png' />
                                            </a>
                                        </td>
                                    </tr>
                                ";
                            }

                            echo "</tbody></table>";
                        }
                        ?>
                    </div>

                </div>
            </div>
        </div>
    </body>
    <script>
        function declararDataTable()
        {
            var tabla_grupos = document.getElementById('tabla_grupos');
            if (tabla_grupos !== null)
            {
                $('#tabla_grupos').dataTable({
                    "oLanguage": {
                        "sLengthMenu": "Mostrar _MENU_ grupos por página",
                        "sZeroRecords": "No tiene grupos a su cargo",
                        "sInfo": "Mostrando _START_ a _END_ de _TOTAL_ grupos",
                        "sInfoEmpty": "Mostrando 0 a 0 de 0 grupos",
                        "sInfoFiltered": "(Encontrados de _MAX_ grupos)"
                    }
                });
            }
        }

        /** Document ready */
        declararDataTable();
    </script>
</html>