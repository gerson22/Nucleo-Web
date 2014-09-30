<?php
include_once("../../includes/validar_admin.php");
include_once("../../includes/clases/class_lib.php");
$grados = Grado::getLista();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Sistema Integral Meze - Grados</title>
        <link rel="stylesheet" href="../../estilo/general.css" />
        <link rel="stylesheet" href="../../estilo/jquery.dataTables.css" />
        <style>
            #div_select_reticula
            {
                overflow: auto;
                float: right;
            }
            
            #select_reticula
            {
                border: 1px solid #152975;
                height: 40px;
                margin-left: 20px;
                padding: 10px;
                width: 120px;
            }
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
                $('#tabla_grados').dataTable({
                    "oLanguage": {
                        "sLengthMenu": "Mostrar _MENU_ grados por página",
                        "sZeroRecords": "No existen grados",
                        "sInfo": "Mostrando _START_ a _END_ de _TOTAL_ grados",
                        "sInfoEmpty": "Mostrando 0 a 0 de 0 grados",
                        "sInfoFiltered": "(Encontrados de _MAX_ grados)"
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
                
                    <h2>Grados</h2>

                    <button onclick="location.href='nuevo.php'" >Nuevo</button>
                    
                    <table id="tabla_grados" >
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Area</th>
                                <th>Grado</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            if(is_array($grados))
                            {
                                foreach($grados as $grado)
                                {
                                    echo "
                                        <tr>
                                            <td>".$grado['id_grado']."</td>
                                            <td>".$grado['area']."</td>
                                            <td>".$grado['grado']."</td>
                                            <td>
                                                <a href='modificar.php?id_grado=".$grado['id_grado']."' >
                                                    <img style='width:16px; height:16px' src='../../media/iconos/icon_modify.png' alt='X' >
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
