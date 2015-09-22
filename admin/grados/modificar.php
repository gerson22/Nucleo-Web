<?php
include_once("../../includes/validar_admin.php");
include_once("../../includes/clases/class_lib.php");
$materias = Materia::getLista();
extract($_GET);
#id_grado

if(isset($id_grado))
{
    $grado = new Grado($id_grado);
}
$ciclo_actual = CicloEscolar::getActual();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Sistema Integral Meze - Nuevo grado</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<script src="../../js/jquery.js" type="text/javascript"></script>
		<script src="../../js/bootstrap.js" type="text/javascript"></script>
		<script src="../../plugins/assets/js/appear.min.js" type="text/javascript"></script>
		<script src="../../plugins/assets/js/animations.js" type="text/javascript"></script>
        <link rel="stylesheet" href="../../estilo/general.css" />
        <link rel="stylesheet" href="../../estilo/formas.css" />
        <link rel="stylesheet" href="../../estilo/grado.css" />
        <link rel="stylesheet" href="../../estilo/perfiles.css" />
        <link rel="stylesheet" href="../../estilo/jquery.dataTables.css" />
        <link rel="stylesheet" href="../../estilo/jquery-ui.min.css" />
    </head>
    <body>
            <?php include("../../includes/header.php"); ?>
            <div id="principal" class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2" style="margin-top:20px;">
                 <div id="area_trabajo">
					<div class="datos_perfil" >
						<div class="datos_perfil_seccion">
							<div class="datos_perfil_dato">
								<input type="hidden" id="id_gradoVal" value="<?php echo $grado->id_grado; ?>" />
								<div class="perfil_dato_label">Grado</div>
								<div class="perfil_dato_value" id="gradoVal"><?php echo $grado->grado; ?></div>
								<img src="/media/iconos/icon_modify.png"
									 style="width: 15px; margin-left: 10px"
									 onclick="modificarClicked()" />
							</div>
							<div class="datos_perfil_dato">
								<div class="perfil_dato_label">Area</div>
								<div class="perfil_dato_value"><?php echo $grado->getArea(); ?></div>
							</div>
						</div>

					</div>

					<div id="div_asignaturas">
						<h3>Materias</h3>
						<label>Ciclo escolar:</label>
						<select id="cicloVal" onchange="nuevoCicloSeleccionado()">
							<?php
								$ciclos = CicloEscolar::getLista();
								if(is_array($ciclos))
								{
									foreach($ciclos as $ciclo)
									{
										echo "<option value='".$ciclo['id_ciclo_escolar']."'>".$ciclo['ciclo_escolar']."</option>";
									}
								}
							?>
						</select>

						<!-- Botón para nueva materia -->
						<button type="button" style="display: block; margin-top: 30px;" class="btn btn-primary" onclick="nuevaMateria()"><span class="glyphicon glyphicon-plus"></span> Nueva</button>

						<table id="tabla_materias" class="table">
							<thead>
								<tr>
									<th>ID</th>
									<th>Materia</th>
									<th>Eliminar</th>
								</tr>
							</thead>
							<tbody>
								<!-- AJAX. Cargar según el grado y el ciclo escolar -->
							</tbody>
						</table>
					</div>
				</div>
        	</div>

        <!-- Modal form para asignar una nueva materia -->
        <div id="formNuevaMateria" title="Asignar nueva materia" style="box-shadow: 2px 2px 5px #5f5f5f;" >
            <label style="display: block;" >Materias disponibles para grados de <?php echo $grado->getArea(); ?>:</label>
            <select id="nuevaMateriaVal">
                <?php
                    $area = $grado->getAreaObj();
                    $materias = $area->getMaterias();
                    if(is_array($materias))
                    {
                        foreach($materias as $materia)
                        {
                            echo "<option value='".$materia['id_materia']."' >".$materia['materia']."</option>";
                        }
                    }
                ?>
            </select>
            <hr />
            <label style="display: block;" >
                Grupos de <?php echo $grado->grado; ?> de <?php echo $grado->getArea(); ?>
                en este ciclo escolar (<?php echo $ciclo_actual->ciclo; ?>):
            </label>
            <div id="div_asignar_docentes">
                <!-- AJAX -->
            </div>
            <button type="button" onclick="asignarNuevaMateria()" id="boton_agregar_materia">Aceptar</button>
        </div>
        <!-- -------------------------------------- -->

        <script src="/librerias/jquery.min.js"></script>
        <script src="/librerias/jquery.validate.min.js"></script>
        <script src="/librerias/messages_es.js"></script>
        <script src="../../librerias/jquery.dataTables.min.js" ></script>
        <script src="../../librerias/fnAjaxReload.js" ></script>
        <script src="../../librerias/jquery-ui.min.js" ></script>
        <script>
            /** Variables */
            var tabla_materias;
            var form_nueva_materia;

            function modificarClicked()
            {
                var nuevo_nombre = prompt("Nuevo nombre");
                if(nuevo_nombre.length > 0 && nuevo_nombre !== "")
                {
                    if(confirm("Seguro que desea cambiar el nombre del grado a " + nuevo_nombre))
                    {
                        $.ajax({
                            type: "POST",
                            url: "/includes/acciones/grados/update.php",
                            data: "id_gradoVal=" + $("#id_gradoVal").val() + "&gradoVal=" + nuevo_nombre,
                            success: function (data)
                            {
                                if(data == 1) document.location.reload(true);
                            }
                        });
                    }
                }
            }

            function declararDataTable()
            {
                tabla_materias = $("#tabla_materias").dataTable({
                    "bPaginate":   false,
                    "oLanguage": {
                        "sLengthMenu": "Mostrar _MENU_ materias por página",
                        "sZeroRecords": "No se encontraron materias",
                        "sInfo": "Mostrando _START_ a _END_ de _TOTAL_ materias",
                        "sInfoEmpty": "Mostrando 0 a 0 de 0 materias",
                        "sInfoFiltered": "(Encontrados de _MAX_ materias)"
                    },
                    "aoColumns": [
                        {"sWidth":"10%"},{"sWidth":"80%"},{"sWidth":"10%"}
                    ],
                    "bProcessing": true,
                    "sAjaxSource": '/includes/acciones/grados/JSON_get_materias.php',
                    "fnServerParams": function (aoData)
                    {
                        var grado = $("#id_gradoVal").val();
                        var ciclo = $("#cicloVal").val();

                        aoData.push({ "name": "grado", "value": grado });
                        aoData.push({ "name": "ciclo", "value": ciclo });
                    }
                });
            }

            function nuevoCicloSeleccionado()
            {
                reload();
            }

            function reload()
            {
                tabla_materias.fnReloadAjax();
            }

            function eliminarMateria(id_materia)
            {
                var id_grado    = $("#id_gradoVal").val();
                var grado       = $("#gradoVal").html();
                var ciclo       = $("#cicloVal").val();

                if(confirm("Se eliminarán las clases de esta materia de todos los grupos de " + grado + ". " +
                    "También se perderán las calificaciones y asistencias relacionadas a dichos grupos." +
                    "¿Desea continuar?"))
                {
                    $.ajax({
                        type: "POST",
                        url: "/includes/acciones/grados/eliminar_materia.php",
                        data: "id_grado=" + id_grado + "&id_materia=" + id_materia
                            + "&ciclo=" + ciclo,
                        success: function (data)
                        {
                            reload();
                        }
                    });
                }
            }

            function nuevaMateria()
            {
                form_nueva_materia.dialog("open");
                cargarGrupos();
            }

            function cargarGrupos()
            {
                $.ajax({
                    type: "POST",
                    url: "/includes/acciones/grados/getGruposJSON.php",
                    data: "grado=" + $("#id_gradoVal").val(),
                    dataType: "json",
                    success: function (grupos)
                    {
                        // Llenamos la variable optionsDocentes con el
                        // código HTML de los elementos <option> por cada docente.
                        var optionsDocentes = "";
                        $.ajax({
                            type: "POST",
                            url: "/includes/acciones/maestros/getJSONDocentesVigentes.php",
                            data: "",
                            dataType: "json",
                            success: function (docentes)
                            {
                                if(docentes.length > 0 && docentes != null)
                                {
                                    for(i in docentes)
                                    {
                                        optionsDocentes += "<option value='"
                                            + docentes[i].id_persona + "'>"
                                            + docentes[i].nombre + "</option>";
                                    }
                                }

                                $("#div_asignar_docentes").html("");
                                if(grupos.length > 0)
                                {
                                    for(i in grupos)
                                    {
                                        $("#div_asignar_docentes").append("<div class='grupo'" +
                                            " data-id_grupo='" + grupos[i].id_grupo + "' >" +
                                            "<div class='grupoNombre' >" + grupos[i].grupo + "</div>" +
                                            "<select class='docenteVal'>"+optionsDocentes+"</select></div>");
                                    }
                                }
                            }
                        });
                    }
                });
            }

            function declararFormaModal()
            {
                form_nueva_materia = $( "#formNuevaMateria" ).dialog({
                    autoOpen: false,
                    height: 300,
                    width: 600,
                    modal: true
                });
            }

            // Click a "Aceptar" ya se asignará la nueva materia al grado,
            // y se crearán las nuevas clases respectivas de cada grupo del grado
            function asignarNuevaMateria()
            {
                if(confirm("¿Están correctos los datos?"))
                {
                    $("#boton_agregar_materia").prop('disabled', true);

                    var nuevasClases = [];
                    $(".grupo").each(function()
                    {
                        var clase = {};
                        clase.id_grupo = $(this).attr('data-id_grupo');
                        clase.id_docente = $(this).children('.docenteVal').val();
                        nuevasClases.push(clase);
                    });

                    $.ajax({
                        type: "POST",
                        url: "/includes/acciones/grados/agregar_materia.php",
                        data: "id_grado=" + $("#id_gradoVal").val()
                            + "&id_ciclo=" + $("#cicloVal").val()
                            + "&id_materia=" + $("#nuevaMateriaVal").val()
                            + "&nuevasClases=" + JSON.stringify(nuevasClases),
                        success: function (data)
                        {
                            if(data == 1) document.location.reload(true);
                        }
                    });
                }
            }

            /** Document ready */
            declararDataTable();
            declararFormaModal();

        </script>
    </body>
</html>
