<?php
include_once("../../includes/validar_admin.php");
include_once("../../includes/clases/class_lib.php");
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Nuevo grupo</title>
        <link rel="stylesheet" href="../../estilo/general.css" />
        <link rel="stylesheet" href="../../estilo/formas_extensas.css" />
        <style>
            #div_clases
            {
                border: 1px solid #CCCCCC;
                margin-bottom: 10px;
                overflow: auto;
                padding: 10px;
                width: 978px;
            }
            
            .clase_row
            {
                float: left;
                margin: 10px 0 0;
                overflow: auto;
                width: 100%;
            }

            .materiaVal
            {
                float: left;
                width: 45%;
            }
           
            .maestroVal
            {
                float: right;
                width: 45%;
            }

            #div_grupos_existentes
            {
                padding: 5px 0;
            }
        </style>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
        <script>
            $(document).ready(function ()
            {
                areaSeleccionada();
            });

            function gradoCambiado()
            {
                llenarTablaClases();
                llenarGruposExistentes();
            }

            function llenarGruposExistentes()
            {
                $.ajax({
                    type: "POST",
                    url: "/includes/acciones/grupos/print_lista_existentes.php",
                    data: "id_grado=" + $("#gradoVal").val() + "&id_ciclo=" + $("#cicloVal").val(),
                    success: function (data)
                    {
                        var grado = $("#gradoVal :selected").text();
                        var area  = $("#areaVal :selected").text();

                        if(grado.length <= 0)
                        {
                            $("#label_grado").html("Grupos de " + area + ":");
                        }
                        else
                        {
                            $("#label_grado").html("Grupos de " + grado + " de " + area + ":");
                        }

                        $("#div_grupos_existentes").html(data);
                    }
                });
            }

            function llenarTablaClases()
            {
                var id_grado = $("#gradoVal").val();
                $.post("../../includes/acciones/grados/print_tabla_clases.php", { id_grado: id_grado }, function (data)
                {
                    $("#div_clases").html(data);
                });
            }

            function agregarGrupo()
            {
                // Validación 1: El grupo tiene nombre
                if ($("#grupoVal").val().length > 0)
                {
                    // Validación 2: Se seleccionó un grado
                    if($("#gradoVal").val())
                    {
                        $("#boton_nuevo").attr('disabled', 'disabled');
                        var clases = [];
                        var maestros_asignados = true;
                        $("#div_clases .clase_row").each(function ()
                        {
                            var clase = {};
                            clase.id_materia = $(this).children('.id_materiaVal').val();
                            clase.id_maestro = $(this).children('.maestroVal').val();
                            if(!$(this).children('.maestroVal').val()) maestros_asignados = false;
                            clases.push(clase);
                        });

                        // Validación 3: Todas las materias tienen un docente asignado
                        if(maestros_asignados)
                        {
                            if(confirm("¿Desea agregar el grupo?"))
                            {
                                var id_ciclo_escolar = $("#cicloVal").val();

                                var data = "id_gradoVal=" + $("#gradoVal").val() + "&grupoVal=" + $("#grupoVal").val();
                                data += "&clases=" + JSON.stringify(clases) + "&id_ciclo_escolar=" + id_ciclo_escolar;

                                $.ajax({
                                    type: "POST",
                                    url: "/includes/acciones/grupos/insert.php",
                                    data: data,
                                    success: function (data)
                                    {
                                        if(data == 1)
                                        {
                                            alert("Grupo agregado");
                                            window.location.href = "/admin/grupos/index.php";
                                        }
                                    }
                                });
                            }
                        }
                        else
                        {
                            alert("Debe asigarle un docente a todas las materias");
                        }
                    }
                    else
                    {
                        alert("Debe seleccionar un grado");
                    }
                }
                else
                {
                    alert("Debe asignarle un nombre al grupo (A, B, etc)");
                }
            }

            function toggleBuscadorMaestros(caller)
            {

            }

            function areaSeleccionada()
            {
                var area = $("#areaVal").val();
                $.post("/includes/acciones/grados/print_select_area.php", {area:area}, function (data)
                {
                    $("#gradoVal").html(data);
                    gradoCambiado();
                });
            }

            function copiarDocentes()
            {
                var docente = $(".maestroVal").first().val();

                $(".maestroVal").each(function(){
                    $(this).val(docente);
                })
            }
        </script>
    </head>
    <body>
        <div id="wrapper">
            <?php include("../../includes/header.php"); ?>
            <div id="content">

                <h3>Nuevo grupo</h3>

                <div class="form_row_3">
                    <label for="cicloVal" class="form_label">Ciclo escolar</label>
                    <select id="cicloVal" name="cicloVal" class="form_input" >
                        <?php
                        $ciclos = CicloEscolar::getLista();
                        if(is_array($ciclos))
                        {
                            foreach($ciclos as $ciclo)
                            {
                                echo "<option value='".$ciclo['id_ciclo_escolar']."' >".$ciclo['ciclo_escolar']."</option>";
                            }
                        }
                        ?>
                    </select>
                </div>

                <div class="form_row_3">
                    <label class="form_label">Area</label>
                    <select id="areaVal" class="form_input" onchange="areaSeleccionada()" >
                        <?php
                        $areas = Area::getLista();
                        if(is_array($areas))
                        {
                            foreach($areas as $area)
                            {
                                echo "<option value='".$area['id_area']."' >".$area['area']."</option>";
                            }
                        }
                        ?>
                    </select>
                </div>

                <div class="form_row_3">
                    <label class="form_label">Grado</label>
                    <select id="gradoVal" class="form_input" onchange="gradoCambiado()" >
                        <!-- AJAX -->
                    </select>
                </div>
                    
                <div class="form_row_3">
                    <label class="form_label">Nombre del grupo (A, B, etc)</label>
                    <input type="text" id="grupoVal" class="form_input" />
                </div>

                <div class="form_row_3">
                    <label class="form_label" id="label_grado" ></label>
                    <div id="div_grupos_existentes">
                        <!-- AJAX -->
                    </div>
                </div>

                <div style="font-size: 14px; font-weight: 600; overflow: auto; width: 100%;">Asignar clases</div>
                <div id="div_clases">
                    <!-- AJAX -->
                </div>

                <div class="form_row">
                    <input type="button" class="form_submit" value="Aceptar" onclick="agregarGrupo()" id="boton_nuevo" />
                    <input type="button" class="form_submit" value="Copiar docente" onclick="copiarDocentes()" />
                </div>

            </div>
        </div>
    </body>
</html>
