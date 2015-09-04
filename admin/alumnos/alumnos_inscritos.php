<?php
include_once("../../includes/validar_admin.php");
include_once("../../includes/clases/class_lib.php");
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Alumnos inscritos</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<script src="../../js/jquery.js" type="text/javascript"></script>
		<script src="../../js/bootstrap.js" type="text/javascript"></script>
		<script src="../../plugins/assets/js/appear.min.js" type="text/javascript"></script>
		<script src="../../plugins/assets/js/animations.js" type="text/javascript"></script>
        <link rel="stylesheet" href="../../estilo/general.css" />
        <link rel="stylesheet" href="../../estilo/jquery.dataTables.css" />
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
        <script src="../../librerias/jquery.dataTables.min.js" ></script>
        <script src="../../librerias/fnAjaxReload.js" ></script>
    </head>
    <body>
            <?php include("../../includes/header.php"); ?>
				<div id="principal" class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2" style="margin-top:30px;">
        			<div id="area_trabajo">
						<div id="selector_ciclo">
							<label>Ciclo escolar: </label>
							<select id="ciclo_escolarVal" onchange="cargarAlumnos()" class="form-control">
								<?php
									$ciclos = CicloEscolar::getLista();
									$id_actual = CicloEscolar::getActual();
									foreach($ciclos as $ciclo)
									{
										echo "<option value='".$ciclo['id_ciclo_escolar']."' ".$selected." >".$ciclo['ciclo_escolar']."</option>";
									}
								?>
							</select>
						</div>

						<table id="tabla_alumnos" class="table" >
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
								/*if(is_array($alumnos_inscritos))
								{
									foreach($alumnos_inscritos as $alumno)
									{
										echo "
											<tr>
												<td>".$alumno['id_persona']."</td>
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
								*/
								?>
							</tbody>
						</table>

						<a href="inscribir.php" class="link_estilizado btn btn-primary"  ><i class="fa fa-user-plus"></i> Inscribir alumno</a>

					</div>
				</div>
    </body>
    <script>
        var tabla_alumnos;

        function declararDataTable()
        {
            tabla_alumnos = $('#tabla_alumnos').dataTable({
                "oLanguage": {
                    "sLengthMenu": "Mostrar _MENU_ alumnos por página",
                    "sZeroRecords": "No existen alumnos",
                    "sInfo": "Mostrando _START_ a _END_ de _TOTAL_ alumnos",
                    "sInfoEmpty": "Mostrando 0 a 0 de 0 alumnos",
                    "sInfoFiltered": "(Encontrados de _MAX_ alumnos)"
                },
                "aoColumns": [
                    {"sWidth":"5%"},{"sWidth":"15%"},{"sWidth":"15%"},{"sWidth":"20%"},
                    {"sWidth":"10%"},{"sWidth":"10%"},{"sWidth":"10%"},{"sWidth":"5%"}
                ],
                "bProcessing": true,
                "sAjaxSource": '../../includes/acciones/alumnos/loadInscritos.php',
                "fnServerParams": function ( aoData ) {
                    aoData.push( { "name": "id_ciclo_escolar", "value": $("#ciclo_escolarVal").val() } );
                },
                "iDisplayLength": 25
            });
        }

        function cargarAlumnos()
        {
            tabla_alumnos.fnReloadAjax();
        }

        declararDataTable();
        //cargarAlumnos();
    </script>
</html>
