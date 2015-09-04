<?php
    include("../../includes/validar_admin.php");
    include_once("../../includes/clases/class_lib.php");
    $clases = Clase::getLista();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Sistema Integral Meze - Clases</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<script src="../../js/jquery.js" type="text/javascript"></script>
		<script src="../../js/bootstrap.js" type="text/javascript"></script>
		<script src="../../plugins/assets/js/appear.min.js" type="text/javascript"></script>
		<script src="../../plugins/assets/js/animations.js" type="text/javascript"></script>
        <link rel="stylesheet" href="../../estilo/general.css" />
        <link rel="stylesheet" href="../../estilo/jquery.dataTables.css" />
        <style>
            #div_select_filtro
            {
                overflow: auto;
                float: right;
            }
            
            #select_filtro
            {
                border: 1px solid #152975;
                height: 40px;
                margin-left: 20px;
                padding: 10px;
                width: 200px;
            }
        </style>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
        <script src="../../librerias/jquery.dataTables.min.js" ></script>
        <script>
            $(document).ready(function ()
            {
                reloadTable();
                
                //$('#select_filtro').change(function () { reloadTable(); });
            });

            function declararDataTable()
            {
                $('#tabla_clases').dataTable({
                    "oLanguage": {
                        "sLengthMenu": "Mostrar _MENU_ clases por página",
                        "sZeroRecords": "No existen clases",
                        "sInfo": "Mostrando _START_ a _END_ de _TOTAL_ clases",
                        "sInfoEmpty": "Mostrando 0 a 0 de 0 clases",
                        "sInfoFiltered": "(Encontrados de _MAX_ clases)"
                    }
                });
            }

            function reloadTable()
            {
                /*var id_ciclo_escolar = $('#select_clases').val();
                $.post("/includes/acciones/grupos/print_tabla.php", { id_ciclo_escolar: id_ciclo_escolar }, function (data)
                {
                    $('#tabla_grupos tbody').html(data);
                });*/

                $.post("/includes/acciones/clases/print_tabla.php", {}, function (data)
                {
                    $('#tabla_clases tbody').html(data);
                    declararDataTable();
                })
            }
        </script>
    </head>
    <body>
            <?php include("../../includes/header.php"); ?>
				<div id="principal" class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2" style="margin-top:30px;">
        			<div id="area_trabajo">

                    <h2>Clases</h2>

                    <button onclick="location.href='nueva.php'" class="btn btn-primary" disabled="disabled">Nueva</button>
                    
                    <div id="div_select_ciclo">
                        <label for="select_filtro">Selecciona un filtro</label>
                        <select id="select_filtro" name="select_filtro" class="form-control">
                            <option value="1">Materia</option>
                        </select>
                    </div>

                    <table id="tabla_clases" class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Grado</th>
                                <th>Grupo</th>
                                <th>Materia</th>
                                <th>Maestro</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- AJAX -->
                        </tbody>
                    </table>
                </div>

            </div>
    </body>
</html>
