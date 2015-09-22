<?php
/** Created by Gustavo Carrillo
 * gus@yozki.net
 * @yozki
 */

$id_modulo = 10; // Becas - Nueva
include_once("../../includes/validar_admin.php");
include_once("../../includes/clases/class_lib.php");
include_once("../../includes/validar_acceso.php");
$tipos = Beca::getTipos();
extract($_GET);
# id_alumno

$alumno = new Alumno($id_alumno);
$beca = $alumno->getBecaActual();
$id_ciclo_escolar = $beca['id_ciclo_escolar'];
$id_subtipo = $beca['id_subtipo'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Sistema Integral Meze - Nueva Beca</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<script src="../../js/jquery.js" type="text/javascript"></script>
	<script src="../../js/bootstrap.js" type="text/javascript"></script>
	<script src="../../plugins/assets/js/appear.min.js" type="text/javascript"></script>
	<script src="../../plugins/assets/js/animations.js" type="text/javascript"></script>
    <link rel="stylesheet" href="../../estilo/general.css" />
    <link rel="stylesheet" href="../../estilo/formas.css" />
    <link rel="stylesheet" href="../../estilo/buscadorAjax.css" />
    <link rel="stylesheet" href="../../estilo/jquery.dataTables.css" />
    <style>
        #buscador_alumnos
        {
            width: 450px;
            border: 1px solid #CCC;
            background-color: #FFF;
            display: none;
            position: fixed;
            top: 150px;
            left: 200px;
        }
    </style>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
    <script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
    <script src="../../librerias/jquery.dataTables.min.js" ></script>
    <script src="/librerias/messages_es.js"></script>
    <script>
        var tablaHistorialBecas;
        var id_alumno = <?php echo $id_alumno; ?>;

        $(document).ready(function ()
        {
            $(".buscadorAjax").draggable({ handle: ".buscadorAjax_barra" });
            asignarReglasValidacion();
            declararDataTable();
            loadSubtipos();
            cargarTablaBecas(id_alumno);
        });

        function declararDataTable()
        {
            tablaHistorialBecas = $('#historia_becas_alumno').dataTable({
                "oLanguage": {
                    "sLengthMenu": "Mostrar _MENU_ ciclos escolares por página",
                    "sZeroRecords": "El alumno nunca a estado becado",
                    "sInfo": "Mostrando _START_ a _END_ de _TOTAL_ ciclos escolares con beca",
                    "sInfoEmpty": "Mostrando 0 a 0 de 0 ciclos escolares con beca",
                    "sInfoFiltered": "(Encontrados de _MAX_ ciclos escolares con beca)"
                }
            });
        }

        function asignarReglasValidacion()
        {
            $('#forma_nueva_beca').validate({
                rules:
                {
                    "alumnoVal": { required: true },
                    "becaVal": { required: true, number: true, range: [1, 100] }
                }
            })
        }

        function cargarInfoAlumno(id_alumno)
        {
            $.ajax({
                type: "POST",
                url: "../../includes/acciones/alumnos/getAlumnoJSON.php",
                data: "id_alumno=" + id_alumno,
                async: true,
                success: function (data)
                {
                    if (data != "error")
                    {
                        var alumno = jQuery.parseJSON(data);
                        $("#id_alumnoVal").val(alumno.id_persona);
                        $("#alumnoVal").val(alumno.apellido_paterno + " " + alumno.apellido_materno + " " + alumno.nombres);
                    }
                }
            });
        }

        function cargarTablaBecas(id_alumno)
        {
            $.ajax({
                type: "POST",
                url: "/includes/acciones/alumnos/print_tabla_becas.php",
                data: "id_alumno=" + id_alumno,
                success: function (data)
                {
                    if (data != "error")
                    {
                        tablaHistorialBecas.fnDestroy(0);
                        $("#historia_becas_alumno tbody").html(data);
                        declararDataTable();
                    }
                }
            });
        }

        function seleccionarAlumno(id_alumno)
        {
            cargarInfoAlumno(id_alumno);
            cargarTablaBecas(id_alumno);
            $("#buscador_alumnos").fadeOut();
        }

        function loadSubtipos()
        {
            var id_tipo = $("#tipoVal").val();
            $.ajax({
                type: "POST",
                url: "/includes/acciones/becas/load_subtipos.php",
                data: "id_tipo=" + id_tipo,
                success: function (data)
                {
                    $("#subtipoVal").html(data);
                }
            });
        }
    </script>
</head>
<body>
    <?php include("../../includes/header.php"); ?>
   	<div id="principal" class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2" style="margin-top:30px;">
        	<div id="area_trabajo">
            <h2>Modificar beca</h2>
            <form id="forma_nueva_beca" action="../../includes/acciones/alumnos/modificar_beca.php" method="post" onsubmit='return confirm("¿Los datos están correctos?");' >
                <div class="form_row_2">
                    <input type="hidden" name="id_alumnoVal" id="id_alumnoVal" value="<?php echo $alumno->id_persona; ?>" />
                    <label class="form_label" for="alumnoVal">Alumno</label>
                    <input type="text" name="alumnoVal" class="form_input" readonly="readonly" value="<?php echo $alumno->nombres; ?>" />
                </div>
                <div class="form_row_2">
                    <label class="form_label" for="becaVal">% Beca</label>
                    <?php
                        $becaRS = $alumno->getBecaActual();
                        $beca = $becaRS['beca'];
                        $id_subtipo = $becaRS['id_subtipo'];
                    ?>
                    <input class="form_input" type="text" name="becaVal" required value="<?php echo $beca; ?>" />
                </div>
                <div class="form_row_2">
                    <label class="form_label" for="cicloVal">Ciclo escolar</label>
                    <select class="form_input" name="cicloVal" id="cicloVal">
                        <?php
                        $ciclos = CicloEscolar::getListaProximos();
                        if(is_array($ciclos))
                        {
                            foreach($ciclos as $ciclo)
                            {
                                echo "<option value='".$ciclo['id_ciclo_escolar']."'>".$ciclo['ciclo']."</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="form_row_2">
                    <label class="form_label" for="tipoVal">Tipo</label>
                    <select class="form_input" name="tipoVal" id="tipoVal" required onchange="loadSubtipos();" >
                        <?php
                        if(is_array($tipos))
                        {
                            foreach($tipos as $tipo)
                            {
                                echo "<option value='".$tipo['id_tipo_beca']."' >".$tipo['tipo_beca']."</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="form_row_2">
                    <input type="hidden" name="id_ciclo_escolar" value="<?php echo $id_ciclo_escolar; ?>" />
                    <input type="hidden" name="id_subtipoVal" value="<?php echo $id_subtipo; ?>" />
                    <label class="form_label" for="subtipoVal">Sub tipo</label>
                    <select class="form_input" name="subtipoVal" id="subtipoVal" required >
                    </select>
                </div>

                <table id="historia_becas_alumno">
                    <thead>
                    <tr>
                        <th>Ciclo escolar</th>
                        <th>Usuario</th>
                        <th>% Beca</th>
                    </tr>
                    </thead>
                    <tbody>
                    <!-- AJAX -->
                    </tbody>
                </table>

                <?
                switch($error)
                {
                    case 1: echo ""; break;
                    case 2: echo ""; break;
                    default: break;
                }
                ?>
                <div class="form_row">
                    <input id="boton_aceptar" class="form_submit btn btn-primary " type="submit" value="Aceptar" />
                </div>
            </form>

        </div>
    </div>
</body>
</html>