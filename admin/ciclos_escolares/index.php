<?php
$id_modulo = 14; // Ciclos - Ver lista
include_once("../../includes/validar_admin.php");
include_once("../../includes/funciones_auxiliares.php");
include_once("../../includes/clases/class_lib.php");
include_once("../../includes/validar_acceso.php");
$ciclos_escolares = CicloEscolar::getLista();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Sistema Integral Meze - Ciclos escolares</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<script src="../../js/jquery.js" type="text/javascript"></script>
		<script src="../../js/bootstrap.js" type="text/javascript"></script>
		<script src="../../plugins/assets/js/appear.min.js" type="text/javascript"></script>
		<script src="../../plugins/assets/js/animations.js" type="text/javascript"></script>
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
                $('#tabla_ciclos_escolares').dataTable({
                    "oLanguage": {
                        "sLengthMenu": "Mostrar _MENU_ ciclos por página",
                        "sZeroRecords": "No existen ciclos",
                        "sInfo": "Mostrando _START_ a _END_ de _TOTAL_ ciclos",
                        "sInfoEmpty": "Mostrando 0 a 0 de 0 ciclos",
                        "sInfoFiltered": "(Encontrados de _MAX_ ciclos)"
                    },
                    aaSorting: [[2, 'desc']],
                    "iDisplayLength": 25
                }); 
            }
        </script>
    </head>
    <body>
        <?php include("../../includes/header.php"); ?>
			<div id="principal" class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2" style="margin-top:20px;">
        		<div id="area_trabajo">
                
                    <h2>Ciclos escolares</h2>

                    <button onclick="location.href='nuevo.php'" class="btn btn-primary"><span class="glyphicon glyphicon-plus"></span> Nuevo</button>

                    <div class="table-responsive">
						<table id="tabla_ciclos_escolares" class="table" >
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Ciclo</th>
                                <th>Fecha de inicio</th>
                                <th>Fecha de cierre</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            if(is_array($ciclos_escolares))
                            {
                                foreach($ciclos_escolares as $ciclo_escolar)
                                {
                                    echo "
                                        <tr>
                                            <td>".$ciclo_escolar['id_ciclo_escolar']."</td>
                                            <td>".$ciclo_escolar['ciclo_escolar']."</td>
                                            <td>".$ciclo_escolar['fecha_inicio']."</td>
                                            <td>".$ciclo_escolar['fecha_fin']."</td>
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
