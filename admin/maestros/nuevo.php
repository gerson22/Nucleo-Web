<?php
include_once("../../includes/validar_admin.php");
include_once("../../includes/clases/class_lib.php");
extract($_GET);
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Sistema Integral Meze - Nuevo maestro</title>
        <link rel="stylesheet" href="../../estilo/general.css" />
        <link rel="stylesheet" href="../../estilo/formas.css" />
        <link rel="stylesheet" href="../../estilo/formas_extensas.css" />
        <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
        <style>
            #forma_nuevo_maestro
            {
                font-size: 12px;
            }

            #boton_nuevo_telefono
            {
                border: 1px solid #CCCCCC;
                height: 30px;
                width: 90px;
            }

            .telefono
            {
                height: 60px;
                overflow: auto;
            }

            .telefonoVal
            {
                border: 1px solid #A4C7E1;
                height: 26px;
                width: 290px;
            }

            #boton_nuevo_telefono img
            {
                float: left;
                margin: 5px;
            }

            .tipo_telefono
            {
                border: 1px solid #A4C7E1;
                height: 30px;
                padding: 5px 0 0 5px;
                width: 100px;
            }

            #boton_nuevo_telefono:hover{ border: 1px solid #BBBBBB; background-color: #EEEEEE; }
            .img_eliminar_telefono
            {
                float: left;
                height: 30px;
                margin: 15px 0;
                width: 30px;
            }
        </style>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
        <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
        <script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
        <script src="/librerias/messages_es.js"></script>
        <script>
            var string_telefono = "" +
                "<div class='telefono' >" +
                "<div class='telefono_apartado' style='overflow: auto; width: 120px; float: left'>" +
                "<label>Tipo</label>" +
                "<?php
                    $tipos = Telefono::getTipos();
                    echo "<select class='tipo_telefono'>";
                    if(is_array($tipos))
                    {
                        foreach($tipos as $tipo)
                        {
                            echo "<option value='".$tipo['id_tipo_telefono']."' >".$tipo['tipo_telefono']."</option>";
                        }
                    }
                    echo "</select>";
                ?>"
                +
                "</div>" +
                "<div style='float: left; width: 300px; overflow: auto'>" +
                "<label>Teléfono</label>" +
                "<input type='text' class='telefonoVal' />" +
                "</div>" +
                "<img src='/media/iconos/close.png' alt='X' class='img_eliminar_telefono' onclick='$(this).parent().remove();'/>" +
                "</div>";
            $(document).ready(function ()
            {
                asignarReglasValidacion();
                $("#forma_nuevo_maestro").tabs();
            });

            function asignarReglasValidacion()
            {
                $('#forma_nuevo_maestro').validate({
                    rules:
                    {
                        "apellido_paternoVal": { required: true },
                        "nombresVal": { required: true }
                    },
                    ignore: ""
                })
            }

            function submitClicked()
            {
                if($("#forma_nuevo_maestro").valid())
                {
                    if(confirm("¿Desea agregar al docente?"))
                    {
                        $("#boton_aceptar").attr('disabled', 'disabled');
                        /** Datos */
                        var apellido_paterno    = $("#apellido_paternoVal").val();
                        var apellido_materno    = $("#apellido_maternoVal").val();
                        var nombres             = $("#nombresVal").val();
                        var calle               = $("#calleVal").val();
                        var numero              = $("#numeroVal").val();
                        var colonia             = $("#coloniaVal").val();
                        var CP                  = $("#CPVal").val();

                        /** Escolaridad */
                        var titulo      = $("#tituloVal").val();
                        var egresado    = $("#egresadoDeVal").val();
                        var ano         = $("#anoVal").val();

                        /** Teléfonos */
                        var telefonos = [];

                        $(".telefono").each(function(){
                            var tipo = $(this).find('.tipo_telefono').val();
                            var telefono = $(this).find('.telefonoVal').val();
                            if(telefono.length > 0) telefonos.push({"tipo":tipo, "telefono":telefono});
                        });

                        $.ajax({
                            type: "POST",
                            url: "../../includes/acciones/maestros/insert.php",
                            data: "apellido_paterno=" + apellido_paterno + "&apellido_materno=" + apellido_materno
                                + "&nombres=" + nombres + "&calle=" + calle + "&numero=" + numero
                                + "&colonia=" + colonia + "&CP=" + CP + "&titulo=" + titulo
                                + "&egresado=" + egresado + "&ano=" + ano + "&telefonos=" + JSON.stringify(telefonos),
                            success: function (data)
                            {
                                if(data == 0)
                                {
                                    alert("Maestro agregado");
                                    window.location.href = "/admin/maestros/index.php";
                                }
                            }
                        });
                    }
                }
            }

            function nuevoTelefono()
            {
                $("#div_telefonos").append(string_telefono);
            }
        </script>
    </head>
    <body>
        <div id="wrapper">
            <?php include("../../includes/header.php"); ?>
            <div id="content">

                <div id="inner_content">

                    <h3>Nuevo maestro</h3>

                    <form id="forma_nuevo_maestro" >
                        <ul>
                            <li><a href="#tab-datos_principales">Datos</a></li>
                            <li><a href="#tab-datos_escolaridad">Escolaridad</a></li>
                            <li><a href="#tab-telefonos">Teléfonos</a></li>
                        </ul>
                        <div id="tab-datos_principales">
                            <div class="form_row_2">
                                <label class="form_label" for="apellido_paternoVal">Apellido paterno</label>
                                <input type="text" name="apellido_paternoVal" id="apellido_paternoVal" class="form_input" />
                            </div>
                            <div class="form_row_2">
                                <label class="form_label" for="apellido_maternoVal">Apellido materno</label>
                                <input class="form_input" type="text" name="apellido_maternoVal" id="apellido_maternoVal" />
                            </div>
                            <div class="form_row">
                                <label class="form_label" for="nombresVal">Nombres</label>
                                <input class="form_input" type="text" name="nombresVal" id="nombresVal" required />
                            </div>

                            <!-- Dirección -->
                            <div class="form_row_4">
                                <label class="form_label" for="calleVal">Calle</label>
                                <input class="form_input" type="text" name="calleVal" id="calleVal" />
                            </div>
                            <div class="form_row_4">
                                <label class="form_label" for="numeroVal">Número</label>
                                <input class="form_input" type="text" name="numeroVal" id="numeroVal" />
                            </div>
                            <div class="form_row_4">
                                <label class="form_label" for="coloniaVal">Colonia</label>
                                <select class="form_input" name="coloniaVal" id="coloniaVal" required >
                                    <?php
                                    $colonias = Colonia::getColonias();
                                    foreach($colonias as $colonia)
                                    {
                                        echo "<option value='".$colonia['id_colonia']."' >".$colonia['nombre']."</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form_row_4">
                                <label class="form_label" for="CPVal">CP</label>
                                <input class="form_input" type="text" name="CPVal" id="CPVal" />
                            </div>
                        </div>
                        <div id="tab-datos_escolaridad">
                            <div class="form_row_3">
                                <label class="form_label" for="tituloVal">Título</label>
                                <input class="form_input" type="text" name="tituloVal" id="tituloVal" />
                            </div>
                            <div class="form_row_3">
                                <label class="form_label" for="egresadoDeVal">Egresado de</label>
                                <input class="form_input" type="text" name="egresadoDeVal" id="egresadoDeVal" />
                            </div>
                            <div class="form_row_3">
                                <label class="form_label" for="anoVal">Año</label>
                                <input class="form_input" type="text" name="anoVal" id="anoVal" />
                            </div>
                        </div>
                        <div id="tab-telefonos">
                            <div id="div_telefonos">

                            </div>
                            <div onclick="nuevoTelefono();" id="boton_nuevo_telefono">
                                <img alt="+" src="/media/iconos/icon_add.png">
                                <div style="margin: 7px 0; overflow: auto;">Agregar</div>
                            </div>
                        </div>
                    </form>

                    <div class="form_row">
                        <input id="boton_aceptar" class="form_submit" type="button" value="Aceptar" onclick="submitClicked()" />
                    </div>


                </div>
            </div>
        </div>
    </body>
</html>