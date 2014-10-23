<?php
$id_modulo = 1; // Administradores - Nuevo
include_once("../../includes/clases/class_lib.php");
include_once("../../includes/validar_acceso.php");
include_once("../../includes/validar_admin.php");
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Sistema Integral Meze - Nuevo administrador</title>
        <link rel="stylesheet" href="../../estilo/general.css" />
        <link rel="stylesheet" href="../../estilo/formas.css" />
        <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
        <style>
            #forma_nuevo_administrador{ font-size:12px; }
            .grupo_permisos
            {
                border: 1px solid #CCCCCC;
                border-radius: 2px;
                float: left;
                margin: 10px;
                padding: 5px;
                width: 150px;
                height: 120px;
            }

            .grupo_permisos_titulo
            {
                font-weight: bold;
                text-align: center;
                width: 100%;
            }

            .permiso
            {
                width: 100%;
            }
        </style>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
        <script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
        <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
        <script src="/librerias/messages_es.js"></script>
        <script>
            $(document).ready(function ()
            {
                asignarReglasValidacion();
                $("#forma_nuevo_administrador").tabs();
            });

            function asignarReglasValidacion()
            {
                $('#forma_nuevo_administrador').validate({
                    rules:
                    {
                        "apellido_paternoVal": { required: true },
                        "nombresVal": { required: true }
                    },
                    ignore: ""
                });
            }

            function submitForma()
            {
                if($('#forma_nuevo_administrador').valid())
                {
                    if(confirm("¿Desea agregar un administrador?"))
                    {
                        $("#boton_aceptar").attr('disabled','disabled');

                        var permisos = [];
                        $(".permiso input").each(function()
                        {
                            if($(this).is(':checked')) permisos.push($(this).val());
                        });

                        var nombres             = $("#nombresVal").val();
                        var apellido_paterno    = $("#apellido_paternoVal").val();
                        var apellido_materno    = $("#apellido_maternoVal").val();
                        var sexo                = $("#sexoVal").val();

                        $.ajax({
                            type: "POST",
                            url: "../../includes/acciones/administradores/insert.php",
                            data: "nombres=" + nombres + "&apellido_paterno=" + apellido_paterno
                                + "&apellido_materno=" + apellido_materno + "&sexo=" + sexo,
                            async: false,
                            success: function (data)
                            {
                                if(data !== "error")
                                {
                                    id_administrador = data;
                                    $.ajax({
                                        type: "POST",
                                        url: "/includes/acciones/administradores/update_permisos.php",
                                        data: "id_persona=" + id_administrador + "&permisos=" + JSON.stringify(permisos),
                                        success: function (data)
                                        {
                                            alert("Administrador agregado");
                                            window.location.reload();
                                        }
                                    });
                                }
                            }
                        });
                    }
                }
            }

            function toggle_seleccion(caller)
            {
                var checked = $(caller).is(":checked");
                $(".permiso").each().prop("checked", checked);
            }
        </script>
    </head>
    <body>
        <div id="wrapper">
            <?php include("../../includes/header.php"); ?>
            <div id="content">

                <div id="inner_content">

                    <h3>Nuevo administrador</h3>

                    <form id="forma_nuevo_administrador" action="#" method="post" >
                        <ul>
                            <li><a href="#tab1-datos">Datos</a></li>
                            <li><a href="#tab2-permisos">Permisos</a></li>
                        </ul>
                        <div id="tab1-datos" >
                            <div class="form_row_2">
                                <label class="form_label" for="apellido_paternoVal">Apellido paterno</label>
                                <input type="text" name="apellido_paternoVal" id="apellido_paternoVal" class="form_input" />
                            </div>
                            <div class="form_row_2">
                                <label class="form_label" for="apellido_maternoVal">Apellido materno</label>
                                <input class="form_input" type="text" name="apellido_maternoVal" id="apellido_maternoVal" />
                            </div>
                            <div class="form_row_2">
                                <label class="form_label" for="nombresVal">Nombres</label>
                                <input class="form_input" type="text" name="nombresVal" id="nombresVal" required />
                            </div>
                            <div class="form_row_3">
                                <label class="form_label" for="nombresVal">Sexo</label>
                                <select id="sexoVal" name="sexoVal" class="form_input" >
                                    <option value="M">M</option>
                                    <option value="F">F</option>
                                    <option value="N/A">N/A</option>
                                </select>
                            </div>
                        </div>
                        <div id="tab2-permisos" >
                            <?php include_once("include_permisos.php"); ?>
                        </div>
                    </form>
                    <?php
                    if(isset($error))
                    {
                        switch($error)
                        {
                            case 1: echo "<div class='error'>Faltaron datos de llenar.</div>"; break;
                            case 2: echo "<div class='error'>Error de base de datos.</div>"; break;
                            default: break;
                        }
                    }
                    ?>
                    <div class="form_row">
                        <input id="boton_aceptar" class="form_submit" type="button" value="Aceptar" onclick="submitForma();" />
                    </div>
                    
                </div>
            </div>
        </div>
    </body>
    <script>
        function toggle_seleccion(caller)
        {
            var checked = $(caller).prop('checked');
            $(".permiso").children('input').each(function(){
                $(this).prop('checked', checked);
            });

        }
    </script>
</html>