<?php
include_once("../../includes/validar_admin.php");
include_once("../../includes/clases/class_lib.php");
$materias = Materia::getLista();
extract($_GET);
#id_grado

$grado = new Grado($id_grado);
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Sistema Integral Meze - Nuevo grado</title>
        <link rel="stylesheet" href="../../estilo/general.css" />
        <link rel="stylesheet" href="../../estilo/formas.css" />
        <link rel="stylesheet" href="../../estilo/grado.css" />
        <link rel="stylesheet" href="../../estilo/perfiles.css" />
        <link rel="stylesheet" href="../../estilo/jquery.dataTables.css" />
    </head>
    <body>
        <div id="wrapper">
            <?php include("../../includes/header.php"); ?>
            <div id="content">

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
                    <button type="button" style="display: block; margin-top: 30px;" onclick="nuevaMateria()">Nueva</button>

                    <table id="tabla_materias">
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
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
        <script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
        <script src="/librerias/messages_es.js"></script>
        <script src="../../librerias/jquery.dataTables.min.js" ></script>
        <script src="../../librerias/fnAjaxReload.js" ></script>
        <script>
            /** Variables */
            var tabla_materias;

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
                console.log("nueva materia...");
            }

            /** Document ready */
            declararDataTable();

        </script>
    </body>
</html>
