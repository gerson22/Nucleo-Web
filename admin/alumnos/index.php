<?php
include("../../includes/validar_admin.php");
include_once("../../includes/clases/class_lib.php");
$alumnos = Alumno::getLista();
?>

<!DOCTYPE html>
<html lang="en" class="no-js">
    <head>
        <meta charset="utf-8" />
        <title>Sistema Integral Meze - Maestros</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<script src="../../js/jquery.js" type="text/javascript"></script>
		<script src="../../js/bootstrap.js" type="text/javascript"></script>
		<script src="../../plugins/assets/js/appear.min.js" type="text/javascript"></script>
		<script src="../../plugins/assets/js/animations.js" type="text/javascript"></script>
        <link rel="stylesheet" href="../../estilo/general.css" />
        <link rel="stylesheet" href="../../estilo/jquery.dataTables.css" />
		<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" type="text/css" />
        <link rel="stylesheet" href="https://cdn.datatables.net/1.10.8/css/dataTables.bootstrap.min.css" />
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
        <script src="../../librerias/jquery.dataTables.min.js" ></script>
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
                    "displayLength": 10
                });

                DT.fnSort( [ [1,'asc']  ]);
            }
        </script>
    </head>
    <body>
        <?php include("../../includes/header.php"); ?>
		<div id="principal" class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2" style="margin-top:20px;">
                 <div id="area_trabajo">
				 <h3>Alumnos</h3>
                 <div class="table-responsive">
					 <table id="tabla_alumnos" class="table  col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2">
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
                                <th>Colonia</th>
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
                <a href="inscribir.php" class="btn btn-primary btn-orig" ><i class="fa fa-user-plus"></i> Inscribir alumno</a> 
			</div>
		</div>
    </body>
</html>
