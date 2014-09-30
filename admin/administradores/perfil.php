<?php
/** Created by Gustavo Carrillo
 * gus@yozki.net
 * @yozki
 */

$id_modulo = 3; // Administradores - Ver perfil
include_once("../../includes/clases/class_lib.php");
include_once("../../includes/validar_acceso.php");
include_once("../../includes/validar_admin.php");
extract($_GET);
# id_administrador

$admin = new Administrador($id_administrador);

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
            #forma_nuevo_administrador
            {
                font-size: 12px;
            }
            .grupo_permisos
            {
                border: 1px solid #CCCCCC;
                border-radius: 2px;
                float: left;
                margin: 10px;
                padding: 5px;
                height: 120px;
                width: 150px;
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
            var modulosPermitidos;
            var id_administrador = 0;

            $(document).ready(function()
            {
                id_administrador = <?php echo $id_administrador; ?>;
                $("#forma_nuevo_administrador").tabs();
                obtenerModulosPemitidos();
            });

            function obtenerModulosPemitidos()
            {
                $.getJSON( "/includes/acciones/administradores/get_modulos_accesiblesJSON.php", {id_persona:id_administrador}, function( data ) {
                    modulosPermitidos = data;
                    seleccionarPermisosActuales();
                });
            }

            function seleccionarPermisosActuales()
            {
                $(".permiso").each(function()
                {
                    var check = $(this).children('input');
                    if(modulosPermitidos.indexOf(check.attr('value')) >= 0)
                    {
                        check.prop('checked', true);
                    }
                });
            }

            function cambiarPermisos()
            {
                if(confirm("¿Seguro que desea guardar los cambios en los permisos?"))
                {
                    $("#boton_aceptar").attr('disabled','disabled');
                    modulosPermitidos = [];
                    $(".permiso").each(function()
                    {
                        var check = $(this).children('input');
                        if(check.prop('checked'))
                        {
                            modulosPermitidos.push(check.attr('value'));
                        }
                    });

                    $.ajax({
                        type: "POST",
                        url: "/includes/acciones/administradores/update_permisos.php",
                        data: "id_persona=" + id_administrador + "&permisos=" + JSON.stringify(modulosPermitidos),
                        success: function (data)
                        {
                            window.location.reload();
                        }
                    });
                }
            }
        </script>
    </head>
    <body>
    <div id="wrapper">
        <?php include("../../includes/header.php"); ?>
        <div id="content">

            <div id="inner_content">

                <h3>Perfil de administrador</h3>

                <form id="forma_nuevo_administrador" >
                    <ul>
                        <li><a href="#tab1-datos">Datos</a></li>
                        <li><a href="#tab2-permisos">Permisos</a></li>
                    </ul>
                    <div id="tab1-datos" >
                        <div class="form_row_2">
                            <label class="form_label" for="apellido_paternoVal">Apellido paterno</label>
                            <input type="text" value="<?php echo $admin->apellido_paterno; ?>" class="form_input" />
                        </div>
                        <div class="form_row_2">
                            <label class="form_label" for="apellido_maternoVal">Apellido materno</label>
                            <input class="form_input" type="text" value="<?php echo $admin->apellido_materno; ?>" id="apellido_maternoVal" required />
                        </div>
                        <div class="form_row">
                            <label class="form_label" for="nombresVal">Nombres</label>
                            <input class="form_input" type="text" value="<?php echo $admin->nombres; ?>" id="nombresVal" required />
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
                    <input id="boton_aceptar" class="form_submit" type="button" value="Aceptar" onclick="cambiarPermisos();" />
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