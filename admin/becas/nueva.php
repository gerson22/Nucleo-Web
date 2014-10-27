<?php
include("../../includes/validar_admin.php");
include_once("../../includes/clases/class_lib.php");
$tipos = Beca::getTipos();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Sistema Integral Meze - Nueva Beca</title>
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

            $(document).ready(function ()
            {
                $(".buscadorAjax").draggable({ handle: ".buscadorAjax_barra" });
                asignarReglasValidacion();
                declararDataTable();
                loadSubtipos();
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

            function buscarAlumno()
            {
                $.ajax({
                    type: "POST",
                    url: "../../includes/acciones/alumnos/buscar_alumnos.php",
                    data: "parametro=" + $("#parametroVal").val(),
                    success: function (data)
                    {
                        $("#buscador_alumnos_tabla").html(data);
                    }
                });
            }

            function toggleBuscadorAlumno()
            {
                $("#buscador_alumnos").fadeIn();
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
                            alumno = jQuery.parseJSON(data);
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
                            tablaHistorialBecas.fnDestroy();
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
        <div id="wrapper">
            <?php include("../../includes/header.php"); ?>
            <div id="content">

                <div id="inner_content">
                    <h2>Asignar beca</h2>
                    <form id="forma_nueva_beca" action="../../includes/acciones/alumnos/asignar_beca.php" method="post" onsubmit='return confirm("¿Los datos están correctos?");' >
                        <div class="form_row_2">
                            <input type="hidden" name="id_alumnoVal" id="id_alumnoVal" />
                            <label class="form_label" for="alumnoVal">Alumno</label>
                            <input type="text" name="alumnoVal" id="alumnoVal" class="form_input" ondblclick="toggleBuscadorAlumno()" readonly="readonly" />
                        </div>
                        <div class="form_row_2">
                            <label class="form_label" for="becaVal">% Beca</label>
                            <input class="form_input" type="text" name="becaVal" id="becaVal" required />
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
                            <input id="boton_aceptar" class="form_submit" type="submit" value="Aceptar" />
                        </div>
                    </form>

                    <div id="buscador_alumnos" class="buscadorAjax">
                        <div class="buscadorAjax_barra">
                            <img src='../../media/iconos/icon_close.gif' alt="Cerrar" onclick="$(this).parent().parent().fadeOut()" />
                        </div>
                        <div class="buscadorAjax_top">
                            <label class="buscadorAjax_top_label">Parametro: </label>
                            <input class="buscadorAjax_top_input" type="text" id="parametroVal" />
                            <input class="buscadorAjax_top_boton" type="button" onclick="buscarAlumno()" value="Buscar" />
                        </div>
                        <div class="buscadorAjax_contenedor_tabla">
                            <table id="buscador_alumnos_tabla" class="buscadorAjax_tabla">
                                <!-- Buscador AJAX -->
                            </table>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </body>
</html>