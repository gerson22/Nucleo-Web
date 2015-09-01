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
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<script src="../../js/jquery.js" type="text/javascript"></script>
		<script src="../../js/bootstrap.js" type="text/javascript"></script>
		<script src="../../plugins/assets/js/appear.min.js" type="text/javascript"></script>
		<script src="../../plugins/assets/js/animations.js" type="text/javascript"></script>
        <link rel="stylesheet" href="../../estilo/general.css" />
        <link rel="stylesheet" href="../../estilo/jquery.dataTables.css" />
        <style>
            .icono_mdy{ width: 15px; }
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

            function cambiarNombreClicked(id_materia, nombreViejo)
            {
                var nombreNuevo = prompt("Nuevo nombre", nombreViejo);

                if(confirm("El cambio del nombre afectará a todos los grupos pasados y presentes en los que se imparte esta materia. " +
                    "¿Desea continuar con el cambio de " + nombreViejo + " a " + nombreNuevo + "?"))
                {
                    $.post("/includes/acciones/materias/updateNombre.php", {id_materia:id_materia, nombre:nombreNuevo}, function (data)
                    {
                        if(data == 1){ document.location.reload(true); }
                    });
                }
            }
        </script>
    </head>
    <body>
            <?php include("../../includes/header.php"); ?>
            <div id="principal" class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2" style="margin-top:20px;">
                 <div id="area_trabajo">
                    <h2>Materias</h2>

                    <button onclick="location.href='nueva.php'" class="btn btn-primary" ><span class="glyphicon glyphicon-plus"></span> Nueva</button>

                    <table id="tabla_materias" class="table" >
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Materia</th>
                                <th>Area</th>
                                <th></th>
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
                                            <td onclick='cambiarNombreClicked(".$materia['id_materia'].", \"".$materia['materia']."\")'>
                                                <img src='/media/iconos/icon_modify.png' class='icono_mdy' />
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
