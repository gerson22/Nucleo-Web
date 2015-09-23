<?php
include("../../../includes/validar_admin.php");
include_once("../../../includes/clases/class_lib.php");
$conceptos = Concepto::getLista();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Sistema Integral Meze - Conceptos de pago</title>
        <link rel="stylesheet" href="../../../estilo/general.css" />
        <link rel="stylesheet" href="../../../estilo/jquery.dataTables.css" />
        <link rel="stylesheet" href="../../../estilo/fixed_form.css" />
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
        <script src="../../../librerias/jquery.dataTables.min.js" ></script>
        <script>
            $(document).ready(function ()
            {
                declararDataTable();
            });

            function declararDataTable()
            {
                $('#tabla_conceptos').dataTable({
                    "oLanguage": {
                        "sLengthMenu": "Mostrar _MENU_ conceptos por página",
                        "sZeroRecords": "No existen conceptos",
                        "sInfo": "Mostrando _START_ a _END_ de _TOTAL_ conceptos",
                        "sInfoEmpty": "Mostrando 0 a 0 de 0 conceptos",
                        "sInfoFiltered": "(Encontrados de _MAX_ conceptos)"
                    }
                }); 
            }
        </script>
    </head>
    <body>
            <?php include("../../../includes/header.php"); ?>
            <div id="principal" class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2" >
        		<div id="area_trabajo">
                
                    <h1>Conceptos</h1>

                    <table id="tabla_conceptos" class="table" >
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Concepto</th>
                                <th>Monto sugerido</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            if(is_array($conceptos))
                            {
                                foreach($conceptos as $concepto)
                                {
                                    echo "
                                        <tr>
                                            <td>".$concepto['id_concepto']."</td>
                                            <td>".$concepto['concepto']."</td>
                                            <td>$".$concepto['monto_sugerido']."</td>
                                            <td>
                                                <a href='modificar.php?id_concepto=".$concepto['id_concepto']."' >
                                                    <img src='../../../media/iconos/icon_modify.png' alt='M' style='width:16px; height:16px;' />
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
    </body>
</html>
