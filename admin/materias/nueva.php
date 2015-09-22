<?php
    include("../../includes/validar_admin.php");
    include_once("../../includes/clases/class_lib.php");
    extract($_GET);
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Sistema Integral Meze - Nueva materia</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<script src="../../js/jquery.js" type="text/javascript"></script>
		<script src="../../js/bootstrap.js" type="text/javascript"></script>
		<script src="../../plugins/assets/js/appear.min.js" type="text/javascript"></script>
		<script src="../../plugins/assets/js/animations.js" type="text/javascript"></script>
        <link rel="stylesheet" href="../../estilo/general.css" />
        <link rel="stylesheet" href="../../estilo/formas.css" />
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
        <script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
        <script src="/librerias/messages_es.js"></script>
        <script>
            $(document).ready(function()
            {
                asignarReglasValidacion();
            });

            function asignarReglasValidacion()
            {
                $('#forma_nueva_materia').validate({
                    rules: {
                        "materiaVal": { required: true },
                        "areaVal": { required: true }
                    }
                })
            }

            function confirmar()
            {
                return confirm("¿Seguro que desea agregar la materia?");
            }
        </script>
    </head>
    <body>
            <?php include("../../includes/header.php"); ?>
            <div id="principal" class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2" style="margin-top:20px;">
                 <div id="area_trabajo">
                    <h2>Nueva materia</h2>
                    <form id="forma_nueva_materia" action="../../includes/acciones/materias/insert.php" method="post" onsubmit="return confirm('¿Agregar materia?');" >
                        <div class="form_row_2">
                            <label class="form_label">Materia</label>
                            <input class="form_input" type="text" name="materiaVal" id="materiaVal" required />
                        </div>
                        <div class="form_row_2">
                            <label class="form_label">Area</label>
                            <select class="form_input" name="areaVal" id="areaVal" required >
                                <?php
                                $areas = Area::getLista();
                                if(is_array($areas))
                                {
                                    foreach($areas as $area)
                                    {
                                        echo "<option value='".$area['id_area']."'>".$area['area']."</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <?php
                            switch($error)
                            {
                                case 1: echo "<div id='error_msg'>Error. Contacte al administrador del sistema</div>"; break;
                                case 2: echo "<div id='error_msg'>Error. Contacte al administrador del sistema</div>"; break;
                                default: break;
                            }
                        ?>
                        <div class="form_row">
                            <input class="form_submit btn btn-primary" type="submit" value="Aceptar" />
                        </div>
                    </form>
                </div>
            </div>
    </body>
</html>
