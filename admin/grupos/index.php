<?php
include("../../includes/validar_admin.php");
include_once("../../includes/clases/class_lib.php");
$ciclos_escolares = CicloEscolar::getLista();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Sistema Integral Meze - Grupos</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<script src="../../js/jquery.js" type="text/javascript"></script>
		<script src="../../js/bootstrap.js" type="text/javascript"></script>
		<script src="../../plugins/assets/js/appear.min.js" type="text/javascript"></script>
		<script src="../../plugins/assets/js/animations.js" type="text/javascript"></script>
        <link rel="stylesheet" href="../../estilo/general.css" />
        <link rel="stylesheet" href="../../estilo/jquery.dataTables.css" />
        <style>
            #div_select_ciclo
            {
                overflow: auto;
                float: right;
            }
            
            #select_ciclo
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
        <script src="../../librerias/fnAjaxReload.js" ></script>
        <script>
            var tabla_grupos;

            $(document).ready(function ()
            {
                declararDataTable();
            });

            function declararDataTable()
            {
                tabla_grupos = $('#tabla_grupos').dataTable({
                    "oLanguage": {
                        "sLengthMenu": "Mostrar _MENU_ grupos por página",
                        "sZeroRecords": "No existen grupos",
                        "sInfo": "Mostrando _START_ a _END_ de _TOTAL_ grupos",
                        "sInfoEmpty": "Mostrando 0 a 0 de 0 grupos",
                        "sInfoFiltered": "(Encontrados de _MAX_ grupos)"
                    },
                    "aoColumns": [
                        {"sWidth":"25%"},{"sWidth":"25%"},{"sWidth":"20%"},{"sWidth":"20%"},{"sWidth":"10%"}
                    ],
                    "bProcessing": true,
                    "sAjaxSource": '../../includes/acciones/grupos/print_tabla.php',
                    "fnServerParams": function (aoData)
                    {
                        var id_ciclo_escolar = $('#select_ciclo').val();
                        aoData.push({ "name": "id_ciclo_escolar", "value": id_ciclo_escolar });
                    },
                    "iDisplayLength": 25
                });
            }

            function reloadTable()
            {
                tabla_grupos.fnReloadAjax();
            }
        </script>
    </head>
    <body>
            <?php include("../../includes/header.php"); ?>
            <div id="principal" class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2" style="margin-top:20px;">
                 <div id="area_trabajo">
                    <h2>Grupos</h2>

                    <button onclick="location.href='nuevo.php'" class="btn btn-primary" ><span class="glyphicon glyphicon-plus"></span> Nuevo</button>
                    
                    <div id="div_select_ciclo">
                        <label for="select_ciclo">Selecciona un ciclo escolar</label>
                        <select id="select_ciclo" name="select_ciclo" onchange="reloadTable()">
                            <?php
                                if(is_array($ciclos_escolares))
                                {
                                    foreach($ciclos_escolares as $ciclos_escolar)
                                    {
                                        echo "
                                            <option value='".$ciclos_escolar['id_ciclo_escolar']."' >
                                                ".$ciclos_escolar['ciclo_escolar']."
                                            </option>";
                                    }
                                }
                            ?>
                        </select>
                    </div>

                    <table id="tabla_grupos" class="table" >
                        <thead>
                            <tr>
                                <th>Grupo</th>
                                <th>Grado</th>
                                <th>Area</th>
                                <th>Alumnos</th>
                                <th></th>
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