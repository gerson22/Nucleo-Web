<?php
include_once("../../includes/validar_admin.php");
include_once("../../includes/clases/class_lib.php");
$ciclo_actual = CicloEscolar::getActual();
$becas = $ciclo_actual->getBecas();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Becas</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<script src="../../js/jquery.js" type="text/javascript"></script>
		<script src="../../js/bootstrap.js" type="text/javascript"></script>
		<script src="../../plugins/assets/js/appear.min.js" type="text/javascript"></script>
		<script src="../../plugins/assets/js/animations.js" type="text/javascript"></script>
        <link rel="stylesheet" href="../../estilo/general.css" />
        <link rel="stylesheet" href="../../estilo/jquery.dataTables.css" />
        <style>
            #tabla_becas_wrapper{ font-size:  12px; }
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
                $('#tabla_becas').dataTable({
                    "oLanguage": {
                        "sLengthMenu": "Mostrar _MENU_ alumnos por página",
                        "sZeroRecords": "No existen alumnos becados",
                        "sInfo": "Mostrando _START_ a _END_ de _TOTAL_ alumnos becados",
                        "sInfoEmpty": "Mostrando 0 a 0 de 0 alumnos becados",
                        "sInfoFiltered": "(Encontrados de _MAX_ alumnos becados)"
                    },
                    "iDisplayLength": 25
                });
            }
        </script>
    </head>
    <body>
            <?php include("../../includes/header.php"); ?>
          	<div id="principal" class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2" style="margin-top:30px;">
        		<div id="area_trabajo">

                <h1>Becas</h1>
                
                <div style="margin: 20px 0;">
                    Total de alumnos becados en este ciclo escolar: <?php echo $ciclo_actual->getCountBecas(); ?>
                </div>

                <div class="table-responsive">
					<table id="tabla_becas" class="table" >
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Alumno</th>
                            <th>Usuario</th>
                            <th>% Beca</th>
                            <th>Tipo</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if(is_array($becas))
                        {
                            foreach($becas as $beca)
                            {
                                echo "
                                    <tr>
                                        <td>".$beca['id_persona']."</td>
                                        <td>
                                            <a href='/admin/alumnos/perfil.php?id_alumno=".$beca['id_persona']."' >".$beca['alumno']."</a>
                                        </td>
                                        <td>".$beca['usuario']."</td>
                                        <td>".$beca['beca']."</td>
                                        <td>".$beca['tipo']."</td>
                                        <td>
                                            <a href='modificar.php?id_alumno=".$beca['id_persona']."' >
                                                <img width='15' src='/media/iconos/icon_modify.png' alt='M' />
                                            </a>
                                            <a href='../../includes/acciones/alumnos/eliminar_beca.php?id_alumno=".$beca['id_persona']."' >
                                                <img width='15' src='/media/iconos/icon_close.gif' alt='M' />
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

                <a href="nueva.php" class="link_estilizado btn btn-primary"><span class="glyphicon glyphicon-plus"></span> Agregar beca</a>

            </div>
        </div>
    </body>
</html>