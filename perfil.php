<?php
include_once("includes/validar_sesion.php");
include_once("includes/clases/class_lib.php");
session_start();
$persona = NULL;
switch($_SESSION['tipo_persona'])
{
    case 1:
        $persona = new Alumno($_SESSION['id_persona']);
        break;
    case 2:
        $persona = new Maestro($_SESSION['id_persona']);
        break;
    case 3:
        $persona = new Administrador($_SESSION['id_persona']);
        break;
    default:
        break;
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Sistema Integral Meze - Mi Perfil</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<script src="../../js/jquery.js" type="text/javascript"></script>
		<script src="../../js/bootstrap.js" type="text/javascript"></script>
		<script src="../../plugins/assets/js/appear.min.js" type="text/javascript"></script>
		<script src="../../plugins/assets/js/animations.js" type="text/javascript"></script>
        <link rel="stylesheet" href="estilo/general.css" />
        <link rel="stylesheet" href="estilo/perfil.css" />
        <style>
            #password_div
            {
                display: none;
                width: 300px;
                border: 1px solid #CCC;
                position: fixed;
                top: 100px;
                left: 40%;
                background-color: #FFF;
            }
            
            #password_div_bar
            {
                width: 100%;
                height: 15px;
                background-color: #001682;
            }
            
            #password_div_bar img
            {
                width: 15px;
                height: 15px;
                float: right;
            }
            
            .password_div_row
            {
                width: 280px;
                overflow: auto;
                padding: 10px;
            }
            
            .password_div_row label
            {
                font-size: 12px;
                width: 100%;
            }
            
            .password_div_row input
            {
                width: 270px;
                height: 30px;
            }
        </style>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
        <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
        <script>
            $(document).ready(function ()
            {
                $("#password_div").draggable({ handle: "#password_div_bar" });
            });

            function mostrarPassDiv()
            {
                $("#password_div").fadeIn();
            }

            function cambiarPassword()
            {
                $("#boton_aceptar_cambio").attr('disabled','disabled');
                var passwordVal = $("#passwordVal").val();
                var password2Val = $("#password2Val").val();

                if (passwordVal != password2Val) { alert("Las contraseñas no coinciden"); return false; }
                if (passwordVal.length == 0 || password2Val.length == 0) { alert("La contraseña no puede ser vacia"); return false; }

                $.ajax({
                    type: "POST",
                    url: "/includes/acciones/personas/cambiar_password.php",
                    data: "passwordVal=" + passwordVal + "&password2Val=" + password2Val,
                    success: function (data)
                    {
                        if (data == 1)
                        {
                            alert("Constraseña cambiada exitosamente");
                        }
                        else
                        {
                            alert("Error al cambiar la contraseña.");
                        }
                        $("#boton_aceptar_cambio").removeAttr("disabled");
                        $("#password_div").fadeOut();
                    }
                });
            }
        </script>
    </head>
    <body>
          <?php include("includes/header.php"); ?>
			<div id="principal" class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2" style="margin-top:30px;">
        		<div id="area_trabajo">
                    <h2>Perfil de <?php echo $persona->getTipoPersona(); ?></h2>
                    <div class="perfil_div_2">
                        <div class="perfil_label">Nombre(s)</div>
                        <div class="perfil_value"><?php echo $persona->nombres; ?></div>
                    </div>
                    <div class="perfil_div_2">
                        <div class="perfil_label">Matrícula</div>
                        <div class="perfil_value"><?php echo $persona->matricula; ?></div>
                    </div>
                    <div class="perfil_div_2">
                        <div class="perfil_label">Apellido Paterno</div>
                        <div class="perfil_value"><?php echo $persona->apellido_paterno; ?></div>
                    </div>
                    <div class="perfil_div_2">
                        <div class="perfil_label">Apellido Materno</div>
                        <div class="perfil_value"><?php echo $persona->apellido_materno; ?></div>
                    </div>
                    <?php
                        if($persona->tipo_persona == 1)
                        {
                            echo '
                                <div class="perfil_div_2">
                                    <div class="perfil_label">Grado</div>
                                    <div class="perfil_value">'.$persona->grado.'</div>
                                </div>
                                <div class="perfil_div_2">
                                    <div class="perfil_label">Grupo</div>
                                    <div class="perfil_value">'.$persona->grupo.'</div>
                                </div>
                            ';
                        }
                    ?>
                    <input type="button" class="perfil_boton btn btn-primary  " style="width:230px;" onclick="mostrarPassDiv()" value="Cambiar contraseña" />

                <!-- Fixed div -->
                <div id="password_div" style="box-shadow: 1px 2px 5px #5f5f5f;">
                    <div id="password_div_bar">
                        <img src="/media/iconos/icon_close.gif" alt="cerrar" onclick="$(this).parent().parent().fadeOut();" />
                    </div>
                    <div class="password_div_row">
                        <label>Nueva contraseña</label>
                        <input type="password" id="passwordVal" />
                    </div>
                    <div class="password_div_row">
                        <label>Confirmar contraseña</label>
                        <input type="password" id="password2Val" />
                    </div>
                    <div class="password_div_row">
                        <input id="boton_aceptar_cambio" type="button" onclick="cambiarPassword();" value="Aceptar" class="btn btn-primary btn-block" />
                    </div>
                </div>
                <!-- /Fixed div-->

            </div>
        </div>
    </body>
</html>