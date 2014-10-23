<?php
$id_modulo = 7; // Alumnos - Inscribir
include_once("../../includes/validar_admin.php");
include_once("../../includes/clases/class_lib.php");
include_once("../../includes/validar_acceso.php");

$tipos_tutor = Tutor::getTipos();
$ocupaciones = Tutor::getOcupaciones();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Sistema Integral Meze - Inscribir alumno</title>
        <link rel="stylesheet" href="../../estilo/general.css" />
        <link rel="stylesheet" href="../../estilo/formas_extensas.css" />
        <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
        <link rel="stylesheet" href="../../estilo/inscribir_alumno.css" />
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
        <script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
        <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
        <script src="/librerias/messages_es.js"></script>
        <script>
            var tutor_string = "" +
                "<div class='tutor' >" +
                    "<img src='/media/imagenes/tutor.gif' class='imagen_tutor' />" +
                    "<div class='tutor_info_div_sm' >" +
                        "<label>Tipo de tutor</label>" +
                        "<?php
                        echo "<select class='tipo_tutor'>";
                        if(is_array($tipos_tutor))
                        {
                            foreach($tipos_tutor as $tipo)
                            {
                                echo "<option value='".$tipo['id_tipo_tutor']."' >".$tipo['tipo_tutor']."</option>";
                            }
                        }
                        echo "</select>";
                        ?>" +
                    "</div>" +
                    "<div class='tutor_info_div_lg' >" +
                        "<label>Nombre</label>" +
                        "<input type='text' class='nombreTutor' />" +
                    "</div>" +
                    "<div class='tutor_info_div_md' >" +
                        "<label>Teléfonos</label>" +
                        "<input type='text' class='telefonosTutor' />" +
                    "</div>" +
                    "<div class='tutor_info_div_md' >" +
                        "<label>Celular</label>" +
                        "<input type='text' class='celularTutor' />" +
                    "</div>" +
                    "<div class='tutor_info_div_md' >" +
                        "<label>Calle</label>" +
                        "<input type='text' class='calleTutor' />" +
                    "</div>" +
                    "<div class='tutor_info_div_sm' >" +
                        "<label>Número</label>" +
                        "<input type='text' class='numeroTutor' />" +
                    "</div>" +
                    "<div class='tutor_info_div_lg' >" +
                        "<label>Colonia</label>" +
                        "<input type='text' class='coloniaTutor' />" +
                    "</div>" +
                    "<div class='tutor_info_div_sm' >" +
                        "<label>CP</label>" +
                        "<input type='text' class='CPTutor' />" +
                    "</div>" +
                    "<img src='/media/iconos/copy.png' alt='X' class='img_eliminar_tutor' onclick='copiarDireccion($(this).parent());'/>" +
                    "<div class='tutor_info_div_lg' >" +
                        "<label>Ocupación</label>" +
                        "<?php
                            echo "<select class='ocupacionTutor'>";
                            if(is_array($ocupaciones))
                            {
                                foreach($ocupaciones as $ocupacion)
                                {
                                    echo "<option value='".$ocupacion['id_tutor_ocupacion']."' >".$ocupacion['ocupacion']."</option>";
                                }
                            }
                            echo "</select>";
                        ?>" +
                    "</div>" +
                    "<div class='tutor_info_div_lg' >" +
                        "<label>Lugar de trabajo</label>" +
                        "<input type='text' class='lugarTrabajoTutor' />" +
                    "</div>" +

                    "<img src='/media/iconos/close.png' alt='X' class='img_eliminar_tutor' onclick='$(this).parent().remove();'/>" +
                "</div>";

            $(document).ready(function ()
            {
                asignarReglasValidacion();
                $("#forma_nuevo_alumno").tabs();
                cargarSubtipos();
            });

            function copiarDireccion(tutor)
            {
                var calle   = $("#calleVal").val();
                var numero  = $("#numeroVal").val();
                var colonia = $("#coloniaVal option:selected").text();
                var CP      = $("#CPVal").val();

                $(tutor).find('.calleTutor').val(calle);
                $(tutor).find('.numeroTutor').val(numero);
                $(tutor).find('.coloniaTutor').val(colonia);
                $(tutor).find('.CPTutor').val(CP);
            }

            function asignarReglasValidacion()
            {
                $('#forma_nuevo_alumno').validate({
                    ignore: "",
                    rules:
                    {
                        "apellido_paternoVal": { required: true },
                        "nombresVal": { required: true },
                        "grupoVal": { required: true }
                    }
                })
            }

            function loadGrados()
            {
                var id_area = $("#areaVal").val();
                $.post("/includes/acciones/grados/print_select_grados.php", { id_area: id_area }, function (data)
                {
                    $("#gradoVal").html(data);
                    loadGrupos();
                });
            }

            function loadGrupos()
            {
                var id_ciclo = $("#cicloVal").val();
                var id_grado = $("#gradoVal").val();

                $.post("/includes/acciones/grupos/print_select_grupos.php", { id_ciclo:id_ciclo, id_grado: id_grado }, function (data)
                {
                    $("#grupoVal").html(data);
                });
            }

            function enviarFormulario()
            {
                var forma = $("#forma_nuevo_alumno");

                 /** Datos del usuario */
                var nombres     = $("#nombresVal").val();
                var paterno     = $("#apellido_paternoVal").val();
                var materno     = $("#apellido_maternoVal").val();
                var sexo        = $("#sexoVal").val();
                var ciclo       = $("#cicloVal").val();
                var area        = $("#areaVal").val();
                var grado       = $("#gradoVal").val();
                var grupo       = $("#grupoVal").val();

                /** Lista de familiares y tutores */
                var tutores = [];
                $(".tutor").each(function(){
                    var tutor = {};
                    tutor.id_tipo_tutor = $(this).find('.tipo_tutor').val();
                    tutor.nombres       = $(this).find('.nombreTutor').val();
                    tutor.calle         = $(this).find('.calleTutor').val();
                    tutor.numero        = $(this).find('.numeroTutor').val();
                    tutor.colonia       = $(this).find('.coloniaTutor').val();
                    tutor.CP            = $(this).find('.CPTutor').val();
                    tutor.telefonos     = $(this).find('.telefonosTutor').val();
                    tutor.celular       = $(this).find('.celularTutor').val();
                    tutor.ocupacion     = $(this).find('.ocupacionTutor').val();
                    tutor.lugarTrabajo  = $(this).find('.lugarTrabajoTutor').val();
                    tutores.push(tutor);
                });

                /** Otra información */
                var calle   = $("#calleVal").val();
                var numero  = $("#numeroVal").val();
                var colonia = $("#coloniaVal").val();
                var CP      = $("#CPVal").val();

                var club    = $("#clubVal").val();
                var CURP    = $("#curpVal").val();

                /** Beca */
                var beca_tipo = $("#becaTipoVal").val();
                var beca_subtipo = $("#becaSubtipoVal").val();
                var beca_porcentaje = $("#becaPorcentaje").val();

                /** Papeleria entregada */
                var papeleria_entregada = [];
                $("tr.documento").each(function(){
                    if($(this).find('.original').prop('checked') ||  $(this).find('.copia').prop('checked'))
                    {
                        var documento = {};
                        documento.id_documento = $(this).children('.id_documento').val();
                        if($(this).find('.original').prop('checked')) documento.original = 1;
                            else documento.original = 0;
                        if($(this).find('.copia').prop('checked')) documento.copia = 1;
                            else documento.copia = 0;
                        papeleria_entregada.push(documento);
                    }
                });

                if(forma.valid())
                {
                    $("#boton_aceptar").attr('disabled','disabled');

                    var parametros = "nombres=" + nombres + "&apellido_paterno=" + paterno + "&apellido_materno=" + materno
                        + "&area=" + area + "&sexo=" + sexo + "&grado=" + grado + "&grupo=" + grupo + "&calle=" + calle
                        + "&numero=" + numero + "&colonia=" + colonia + "&CP=" + CP
                        + "&tutores=" + JSON.stringify(tutores) + "&club=" + club + "&CURP=" + CURP + "&beca_tipo="
                        + beca_tipo + "&beca_subtipo=" + beca_subtipo + "&beca_porcentaje=" + beca_porcentaje
                        + "&id_ciclo_escolar=" + ciclo + "&papeleria_entregada=" + JSON.stringify(papeleria_entregada)

                    $("#boton_aceptar").attr('disabled', 'disabled');
                    $.ajax({
                        type: "POST",
                        url: "../../includes/acciones/alumnos/insert.php",
                        data: parametros,
                        success: function (data)
                        {
                            if(data)
                            {
                                alert("Alumno inscrito");
                                if(confirm("¿Desea imprimir la información"))
                                {
                                    window.location.href = "/includes/acciones/alumnos/print_inscripcion.php?id_alumno=" + data;
                                }
                                else
                                {
                                    window.location.href = "/admin/alumnos/index.php";
                                }
                            }
                            else
                            {
                                alert("Error: " + data);
                            }
                        }
                    });
                }
            }

            function nuevoTutor()
            {
                $("#div_tutores").append(tutor_string);
            }

            function cargarSubtipos()
            {
                var id_tipo = $("#becaTipoVal").val();
                $.post("/includes/acciones/becas/load_subtipos.php", {id_tipo:id_tipo}, function (data)
                {
                    $("#becaSubtipoVal").html(data);
                });
            }
        </script>
    </head>
    <body>
        <div id="wrapper">
            <?php include("../../includes/header.php"); ?>
            <div id="content">

                <div id="inner_content">
                    <h3>Inscribir alumno</h3>

                    <form id="forma_nuevo_alumno">
                        <ul>
                            <li><a href="#tab-datos_alumno">Datos del alumno</a></li>
                            <li><a href="#tab-datos_tutores">Familia / Tutores</a></li>
                            <li><a href="#tab-otra_informacion">Otra información</a></li>
                            <li><a href="#tab-beca">Beca</a></li>
                            <li><a href="#tab-papeleria">Papeleria</a></li>
                        </ul>
                        <div id="tab-datos_alumno" class="aTab" >
                            <div class="form_row_3">
                                <label class="form_label" for="nombresVal">Nombres</label>
                                <input class="form_input" type="text" name="nombresVal" id="nombresVal" required />
                            </div>
                            <div class="form_row_3">
                                <label class="form_label" for="apellido_paternoVal">Apellido paterno</label>
                                <input type="text" name="apellido_paternoVal" id="apellido_paternoVal" class="form_input" />
                            </div>
                            <div class="form_row_3">
                                <label class="form_label" for="apellido_maternoVal">Apellido materno</label>
                                <input class="form_input" type="text" name="apellido_maternoVal" id="apellido_maternoVal" />
                            </div>

                            <div class="form_row_4">
                                <label class="form_label" for="calleVal">Calle</label>
                                <input type="text" class="form_input" name="calleVal" id="calleVal" />
                            </div>
                            <div class="form_row_4">
                                <label class="form_label" for="numeroVal">Número</label>
                                <input type="text" class="form_input" name="numeroVal" id="numeroVal" />
                            </div>
                            <div class="form_row_4">
                                <label class="form_label" for="coloniaVal">Colonia</label>
                                <select class="form_input" name="coloniaVal" id="coloniaVal">
                                    <?php
                                    $colonias = Colonia::getColonias();
                                    if(is_array($colonias))
                                    {
                                        foreach($colonias as $colonia)
                                        {
                                            echo "<option value='".$colonia['id_colonia']."'>".$colonia['nombre']."</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form_row_4">
                                <label class="form_label" for="CPVal">C.P</label>
                                <input type="text" class="form_input" name="CPVal" id="CPVal" />
                            </div>

                            <div class="form_row_3">
                                <label class="form_label" for="curpVal">CURP</label>
                                <input class="form_input" type="text" name="curpVal" id="curpVal" />
                            </div>
                            <div class="form_row_3">
                                <label class="form_label" for="sexoVal">Sexo</label>
                                <select id="sexoVal" name="sexoVal" class="form_input" >
                                    <option value="M">M</option>
                                    <option value="F">F</option>
                                    <option value="N/A">N/A</option>
                                </select>
                            </div>

                            <hr style="width: 100%; float: left"/>
                            <!----------------------------------------------------------------------------------------->

                            <div class="form_row_4">
                                <label class="form_label" for="cicloVal">Ciclo escolar</label>
                                <select class="form_input" name="cicloVal" id="cicloVal" required onchange="loadGrupos();" >
                                    <?php
                                    $ciclos_proximos = CicloEscolar::getListaProximos();
                                    if(is_array($ciclos_proximos))
                                    {
                                        foreach($ciclos_proximos as $ciclo)
                                        {
                                            echo "<option value='".$ciclo['id_ciclo_escolar']."' >".$ciclo['ciclo']."</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form_row_4">
                                <label class="form_label" for="areaVal">Area</label>
                                <select class="form_input" name="areaVal" id="areaVal" required onchange="loadGrados();" >
                                    <option></option>
                                    <?php
                                    $areas = Area::getLista();
                                    foreach($areas as $area)
                                    {
                                        echo "<option value='".$area['id_area']."' >".$area['area']."</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form_row_4">
                                <label class="form_label" for="gradoVal">Grado</label>
                                <select class="form_input" name="gradoVal" id="gradoVal" required onchange="loadGrupos();" >
                                    <!-- AJAX -->
                                </select>
                            </div>
                            <div class="form_row_4">
                                <label class="form_label" for="grupoVal">Grupo</label>
                                <select class="form_input" name="grupoVal" required id="grupoVal" >
                                    <!-- AJAX -->
                                </select>
                            </div>
                        </div>

                        <!-- TUTORES -->
                        <div id="tab-datos_tutores" class="aTab" >
                            <div id="div_tutores">
                                <!-- Dinámico -->
                            </div>
                            <div id="boton_nuevo_tutor" onclick="nuevoTutor();">
                                <img src="/media/iconos/icon_add.png" alt="+" />
                                <div style="margin: 7px 0; overflow: auto;">Agregar</div>
                            </div>
                        </div>
                        <!------------>

                        <div id="tab-otra_informacion" class="aTab">
                            <div class="form_row_3">
                                <label class="form_label" for="clubVal">Club</label>
                                <select class="form_input" name="clubVal" id="clubVal">
                                    <option></option>
                                    <?php
                                    $clubs = Club::getClubs();
                                    if(is_array($clubs))
                                    {
                                        foreach($clubs as $club)
                                        {
                                            echo "<option value='".$club['id_club']."'>".$club['nombre']."</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div id="tab-beca" class="aTab">
                            <div class="form_row_3">
                                <label class="form_label" for="becaTipoVal">Tipo</label>
                                <select class="form_input" name="becaTipoVal" id="becaTipoVal" onchange="cargarSubtipos();">
                                    <?php
                                    $tipos_beca = Beca::getTipos();
                                    if(is_array($tipos_beca))
                                    {
                                        foreach($tipos_beca as $tipo)
                                        {
                                            echo "<option value='".$tipo['id_tipo_beca']."'>".$tipo['tipo_beca']."</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form_row_3">
                                <label class="form_label" for="becaSubtipoVal">Subtipo</label>
                                <select class="form_input" name="becaSubtipoVal" id="becaSubtipoVal" >
                                    <!-- AJAX -->
                                </select>
                            </div>
                            <div class="form_row_3">
                                <label class="form_label" for="becaPorcentaje">Porcentaje de beca</label>
                                <input type="text" class="form_input" name="becaPorcentaje" id="becaPorcentaje" />
                            </div>
                        </div>
                        <table id="tab-papeleria" class="aTab">
                            <thead>
                                <tr>
                                    <th>Documento</th>
                                    <th style="width: 120px" >Original</th>
                                    <th>Copia</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            $papeleria = Documento::getLista();
                            if(is_array($papeleria))
                            {
                                foreach($papeleria as $documento)
                                {
                                    $id_documento   = $documento['id_documento'];
                                    $documento      = $documento['documento'];
                                    echo "
                                        <tr class='documento' >
                                            <input type='hidden' class='id_documento' value='".$id_documento."' />
                                            <td>".$documento."</td>
                                            <td><input type='checkbox' class='original' value='".$id_documento."' /></td>
                                            <td><input type='checkbox' class='copia' value='".$id_documento."' /></td>
                                        </tr>
                                    ";
                                }
                            }
                            ?>
                            </tbody>
                        </table>
                    </form>

                    <button style="margin: 20px 0px;" onclick="enviarFormulario();" id="boton_aceptar" >Aceptar</button>
                    
                </div>
            </div>
        </div>
    </body>
</html>