<?php
include("../../includes/validar_admin.php");
include_once("../../includes/clases/class_lib.php");
extract($_GET);
# id_maestro

$maestro = new Maestro($id_maestro);
if(is_null($maestro->id_persona)){ header('Location: /admin/maestros/index.php'); exit; } 
$emails = $maestro->getEmails();
$telefonos = $maestro->getTelefonos();
$clases = $maestro->getClasesCiclo();
$escolaridad = $maestro->getEscolaridad();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Sistema Integral Meze - Perfil de maestro</title>
        <link rel="stylesheet" href="../../estilo/general.css" />
        <link rel="stylesheet" href="../../estilo/perfil_maestro.css" />
        <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
        <link rel="stylesheet" href="../../estilo/jquery.dataTables.css" />
        <link rel="stylesheet" href="../../estilo/formas_extensas.css" />
        <link rel="stylesheet" href="../../estilo/fixed_form.css" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<script src="../../js/jquery.js" type="text/javascript"></script>
		<script src="../../js/bootstrap.js" type="text/javascript"></script>
		<script src="../../plugins/assets/js/appear.min.js" type="text/javascript"></script>
		<script src="../../plugins/assets/js/animations.js" type="text/javascript"></script>
        <style>
            #prompt_email, #prompt_telefono
            {
                width: 200px;
                height: 100px;
                position: fixed;
                top: 150px;
                left: 40%;
                border: 1px solid #CCC;
                background-color: #FFF;
                padding: 10px;
                display: none;
            }

            #nuevo_registro_nut
            {
                font-size: 12px;
            }

            .form_row_4 input
            {
                width: 90%;
            }

            #prompt_modificar_direccion, #prompt_modificar_escolaridad
            {
                width: 400px;
            }
        </style>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
        <script src="/librerias/jquery.ocupload-1.1.2.packed.js"></script>
        <script src="/librerias/htmlbarcode.js"></script>
        <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
        <script src="../../librerias/jquery.dataTables.min.js" ></script>
        <script src="/librerias/jquery.form.js"></script>
        <script>
            var id_maestro;

            $(document).ready(function ()
            {
                id_maestro = $("#id_maestroVal").val();

                declararDataTables();

                get_object("barcode").innerHTML = DrawCode39Barcode($("#matriculaVal").val(), 0);
                $("#tabs").tabs();

            });

            function declararDataTables()
            {
                $('#tabla_emails').dataTable({
                    "oLanguage": {
                        "sLengthMenu": "Mostrar _MENU_ emails por página",
                        "sZeroRecords": "El maestro no cuenta con ningún email registrado",
                        "sInfo": "Mostrando _START_ a _END_ de _TOTAL_ emails",
                        "sInfoEmpty": "Mostrando 0 a 0 de 0 emails",
                        "sInfoFiltered": "(Encontrados de _MAX_ emails)"
                    },
                    "bFilter": false,
                    "bLengthChange": false,
                    "bPaginate": false,
                    "bInfo": false
                });

                $('#tabla_telefonos').dataTable({
                    "oLanguage": {
                        "sLengthMenu": "Mostrar _MENU_ telefonos por página",
                        "sZeroRecords": "El maestro no cuenta con ningún teléfono registrado",
                        "sInfo": "Mostrando _START_ a _END_ de _TOTAL_ telefonos",
                        "sInfoEmpty": "Mostrando 0 a 0 de 0 telefonos",
                        "sInfoFiltered": "(Encontrados de _MAX_ telefonos)"
                    },
                    "bFilter": false,
                    "bLengthChange": false,
                    "bPaginate": false,
                    "bInfo": false
                });

                $('#tabla_clases').dataTable({
                    "oLanguage": {
                        "sLengthMenu": "Mostrar _MENU_ clases por página",
                        "sZeroRecords": "El maestro no está impartiendo ninguna clase",
                        "sInfo": "Mostrando _START_ a _END_ de _TOTAL_ clases",
                        "sInfoEmpty": "Mostrando 0 a 0 de 0 clases",
                        "sInfoFiltered": "(Encontrados de _MAX_ clases)"
                    },
                    "bFilter": false,
                    "bLengthChange": false,
                    "bPaginate": false,
                    "bInfo": false
                });
            }

            function getURLParameter(name)
            {
                return decodeURIComponent((new RegExp('[?|&]' + name + '=' + '([^&;]+?)(&|#|;|$)').exec(location.search) || [, ""])[1].replace(/\+/g, '%20')) || null
            }

            var id_maestro = getURLParameter("id_maestro");
            function baja(id_persona)
            {
                if (confirm("¿Está seguro que desea dar de baja al maestro? Una vez dado de baja no se le podrá asignar una nueva clase"))
                {
                    $.ajax({
                        type: "POST",
                        url: "/includes/acciones/maestros/baja.php",
                        data: "id_persona=" + id_persona,
                        success: function (data)
                        {
                            if (data == 1)
                            {
                                window.location.reload(true);
                            }
                        }
                    });
                }
            }

            function mostrarPassword()
            {
                alert($("#param_password").val());
            }

            function toggleEmail()
            {
                $("#prompt_email").fadeIn();
            }

            function addEmail(caller)
            {
                var email = $("#emailVal").val();
                var tipo_email = $("#tipo_emailVal").val();

                if (email.length > 0)
                {
                    $(caller).attr('disabled', 'disabled');

                    $.ajax({
                        type: "POST",
                        url: "/includes/acciones/maestros/agregar_email.php",
                        data: "id_maestro=" + id_maestro + "&email=" + email + "&tipo_email=" + tipo_email,
                        success: function (data)
                        {
                            if (data == 1)
                            {
                                window.location.reload(true);
                            }
                        }
                    });
                }
                else
                {
                    alert("No ingresó ningún correo electrónico");
                    $(caller).removeAttr('disabled');
                }
            }

            function toggleTelefono()
            {
                $("#prompt_telefono").fadeIn();
            }

            function addTelefono()
            {
                var telefono = $("#telefonoVal").val();
                var tipo_telefono = $("#tipo_telefonoVal").val();

                if (telefono.length > 0)
                {
                    $.ajax({
                        type: "POST",
                        url: "/includes/acciones/maestros/agregar_telefono.php",
                        data: "id_maestro=" + id_maestro + "&telefono=" + telefono + "&tipo_telefono=" + tipo_telefono,
                        success: function (data)
                        {
                            if (data == 1)
                            {
                                window.location.reload(true);
                            }
                        }
                    });
                }
                else
                {
                    alert("No ingresó ningún teléfono");
                }
            }

            function get_object(id)
            {
                var object = null;
                if (document.layers)
                {
                    object = document.layers[id];
                }
                else if (document.all)
                {
                    object = document.all[id];
                }
                else if (document.getElementById)
                {
                    object = document.getElementById(id);
                }
                return object;
            }

            function modificarNombresClicked()
            {
                var nombres = prompt("Nombres:");
                if(nombres.length > 0)
                {
                    $.post("/includes/acciones/maestros/cambiar_nombres.php", {id_maestro:id_maestro, nombres:nombres}, function (data)
                    {
                        if(data == 1) window.location.reload();
                        else { alert("Error al actualizar los datos"); }
                    });
                }
            }

            function modificarPaternoClicked()
            {
                var apellido_paterno = prompt("Apellido paterno:");
                if(apellido_paterno.length > 0)
                {
                    $.post("/includes/acciones/maestros/cambiar_apellido_paterno.php", {id_maestro:id_maestro, apellido_paterno:apellido_paterno}, function (data)
                    {
                        if(data == 1) window.location.reload();
                        else { alert("Error al actualizar los datos"); }
                    });
                }
            }

        function modificarMaternoClicked()
        {
            var apellido_materno = prompt("Apellido materno:");
            if(apellido_materno.length > 0)
            {
                $.post("/includes/acciones/maestros/cambiar_apellido_materno.php", {id_maestro:id_maestro, apellido_materno:apellido_materno}, function (data)
                {
                    if(data == 1) window.location.reload();
                    else { alert("Error al actualizar los datos"); }
                });
            }
        }
        </script>
    </head>
    <body>
		<?php include("../../includes/header.php"); ?>
		<div id="principal" class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2" style="margin-top:20px;">
            <div id="area_trabajo">
				<input type="hidden" id='param_password' value="<?php echo $maestro->password; ?>" />
                <input type="hidden" id="id_maestroVal" value="<?php echo $maestro->id_persona; ?>" />
                <input type="hidden" id="matriculaVal" value="<?php echo $maestro->matricula; ?>" />
				
                <div id="wrapper_top" >
                    <div id="profile_picture">

                        <div id="profile_picture_inner">
                            <img src="../../media/fotos/<?php echo $maestro->foto; ?>" alt="N/A" id="foto_maestro" />
                        </div>

                    </div>

                    <div id="datos_generales">
                        <div class="datos_generales_row">
                            <div class="datos_generales_label">Nombre(s):</div>
                            <div class="datos_generales_value">
                                <img src="/media/iconos/icon_modify.png" style="width: 15px;" onclick="modificarNombresClicked();" />
                                <?php echo $maestro->nombres; ?>
                            </div>
                        </div>
                        <div class="datos_generales_row">
                            <div class="datos_generales_label">Apellido paterno:</div>
                            <div class="datos_generales_value">
                                <img src="/media/iconos/icon_modify.png" style="width: 15px;" onclick="modificarPaternoClicked();" />
                                <?php echo $maestro->apellido_paterno; ?>
                            </div>
                        </div>
                        <div class="datos_generales_row">
                            <div class="datos_generales_label">Apellido materno:</div>
                            <div class="datos_generales_value">
                                <img src="/media/iconos/icon_modify.png" style="width: 15px;" onclick="modificarMaternoClicked();" />
                                <?php echo $maestro->apellido_materno; ?>
                            </div>
                        </div>
                        <div class="datos_generales_row">
                            <div class="datos_generales_label">Matricula:</div>
                            <div class="datos_generales_value"><?php echo $maestro->matricula; ?></div>
                        </div>
                        <div class="datos_generales_row">
                            <div class="datos_generales_label">Registrad@ desde:</div>
                            <div class="datos_generales_value"><?php echo $maestro->fecha_alta; ?></div>
                        </div>
                        <div class="datos_generales_row">
                            <div class="datos_generales_label">Contraseña:</div>
                            <div class="datos_generales_value">
                                <input type="button" onclick="mostrarPassword()" value="Mostrar" />
                            </div>
                        </div>
                        <?php
                        if($maestro->getEstado())
                        {
                            echo '
                                <div class="datos_generales_row">
                                    <div class="datos_generales_label">Estado:</div>
                                    <div class="datos_generales_value" style="color: green">
                                        Activo
                                        <img src="/media/iconos/icon_close.gif" alt="Baja" onclick="baja('.$maestro->id_persona.')" />
                                    </div>
                                </div>
                            ';
                        }
                        else
                        {
                            echo '
                                <div class="datos_generales_row">
                                    <div class="datos_generales_label">Fecha de baja:</div>
                                    <div class="datos_generales_value">'.$maestro->fecha_baja.'</div>
                                </div>
                                <div class="datos_generales_row">
                                    <div class="datos_generales_label">Estado:</div>
                                    <div class="datos_generales_value" style="color: red">Inactivo</div>
                                </div>
                            ';
                        }
                        ?>
                        <div class="datos_generales_row">
                            <div class="datos_generales_label">Peso:</div>
                            <div class="datos_generales_value">
                                <?php echo $maestro->getPeso(); ?>
                                <img src="/media/iconos/icon_modify.png"
                                     ALT="M" onclick="$('#nuevo_registro_nut').dialog('open');"
                                     style="width: 15px" title="Cambiar grupo" />
                            </div>
                        </div>
                        <div class="datos_generales_row">
                            <div class="datos_generales_label">Talla:</div>
                            <div class="datos_generales_value">
                                <?php echo $maestro->getTalla(); ?>
                            </div>
                        </div>
                        <div class="datos_generales_row">
                            <div class="datos_generales_label">IMC:</div>
                            <div class="datos_generales_value">
                                <?php echo $maestro->getIMC(); ?>
                            </div>
                        </div>
                    </div>

                    <div id="barcode"></div>

                    <img 
                        id="qr_code"
                        alt ="<?php echo $maestro->matricula; ?>" 
                        src="https://chart.googleapis.com/chart?chs=200x200&cht=qr&chl=<?php echo $maestro->matricula; ?>&chld=L|0"  
                    />

                    <div id="panel_imagen">
                        <!-- loader.gif -->
                        <img style="display:none" id="loader" src="/media/imagenes/loader.gif" alt="Cargando...." title="Cargando...." />
                        <!-- simple file uploading form -->
                        <form id="form_imagen" action="/includes/ajaxupload.php" method="post" enctype="multipart/form-data">
                            <input id="uploadImage" type="file" accept="image/*" name="image" value="Seleccionar foto"/>
                            <input id="button" type="submit" value="Subir" class="btn btn-warning" style="margin-top:10px;">
                        </form>
                    </div>

                </div>

                <div id="tabs">
                    <ul>
                        <li><a href="#tabs-1">Clases</a></li>
                        <li><a href="#tabs-2">E-Mails</a></li>
                        <li><a href="#tabs-3">Teléfonos</a></li>
                        <li><a href="#tabs-4">Dirección</a></li>
                        <li><a href="#tabs-5">Escolaridad</a></li>
                    </ul>
                    <div id="tabs-1" class="table-responsive">
                        <table id="tabla_clases" class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Grado</th>
                                    <th>Grupo</th>
                                    <th>Materia</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            if(is_array($clases))
                            {
                                foreach($clases as $clase)
                                {
                                    echo "
                                        <tr>
                                            <td>".$clase['grado']."</td>
                                            <td>".$clase['grupo']."</td>
                                            <td>".$clase['materia']."</td>
                                        </tr>
                                    ";
                                }
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                    <div id="tabs-2" class="table-responsive">
                        <table id="tabla_emails" class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Tipo de correo electrónico</th>
                                    <th>Correo electrónico</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            if(is_array($emails))
                            {
                                foreach($emails as $email)
                                {
                                    echo "
                                        <tr>
                                            <td>".$email['tipo_email']."</td>
                                            <td>".$email['email']."</td>
                                            <td>
                                                <a href='../../includes/acciones/maestros/eliminar_email.php?id_email=".$email['id_email']."' >
                                                    <img src='../../media/iconos/icon_close.gif' alt='borrar' />
                                                </a>
                                            </td>
                                        </tr>
                                    ";
                                }
                            }
                            ?>
                            </tbody>
                        </table>
                        <img src="../../media/iconos/icon_add.png" alt="Nuevo" style="float: right;" onclick="toggleEmail();" />
                    </div>
                    <div id="tabs-3" class="table-responsive">
                        <table id="tabla_telefonos" class="table table-hover">
                            <thead>
                            <tr>
                                <th>Tipo de teléfono</th>
                                <th>Teléfono</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if(is_array($telefonos))
                            {
                                foreach($telefonos as $telefono)
                                {
                                    echo "
                                        <tr>
                                            <td>".$telefono['tipo_telefono']."</td>
                                            <td>".$telefono['telefono']."</td>
                                            <td>
                                                <a href='../../includes/acciones/alumnos/eliminar_telefono.php?id_telefono=".$telefono['id_telefono']."' >
                                                    <img src='../../media/iconos/icon_close.gif' alt='borrar' />
                                                </a>
                                            </td>
                                        </tr>
                                    ";
                                }
                            }
                            ?>
                            </tbody>
                        </table>
                        <img src="/media/iconos/icon_add.png" style="float: right;" alt="Nuevo" onclick="toggleTelefono();" />
                    </div>
                    <div id="tabs-4">
                        <?php $direccion = $maestro->getDireccion(); ?>
                        <div class="form_row_4">
                            <label>Calle</label>
                            <input type="text" value="<?php echo $direccion['calle']; ?>" class="form-control" readonly />
                        </div>
                        <div class="form_row_4">
                            <label>Número</label>
                            <input type="text" value="<?php echo $direccion['numero']; ?>" class="form-control" readonly />
                        </div>
                        <div class="form_row_4">
                            <label>Colonia</label>
                            <input type="text" value="<?php echo $direccion['colonia']; ?>" class="form-control" readonly />
                        </div>
                        <div class="form_row_4">
                            <label>CP</label>
                            <input type="text" value="<?php echo $direccion['CP']; ?>" class="form-control" />
                        </div>
                        <img src="/media/iconos/icon_modify.png" style="width: 15px; float: right;" alt="M" onclick="mostrarModificarDireccion()" />
                    </div>
                    <div id="tabs-5">
                        <div class="form_row_3">
                            <label class="form_label">Título</label>
                            <input type="text" class="form-control" value="<?php echo $escolaridad['titulo']; ?>" readonly />
                        </div>
                        <div class="form_row_3">
                            <label class="form_label">Egresado de</label>
                            <input type="text" class="form-control" value="<?php echo $escolaridad['egresadode']; ?>" readonly />
                        </div>
                        <div class="form_row_3">
                            <label class="form_label">Año</label>
                            <input type="text" class="form-control" value="<?php echo $escolaridad['ano']; ?>" readonly />
                        </div>
                        <img src="/media/iconos/icon_modify.png" style="width: 15px; float: right;" alt="M" onclick="mostrarModificarEscolaridad()" />
                    </div>
                </div>

                <div id="prompt_email" style="height:230px; width:250px; box-shadow: 2px 2px 10px #5f5f5f;">
                    <label>E-Mail:</label>
                    <input id="emailVal" type="email" class="form-control"  />
                    <label>Tipo:</label>
                    <select id="tipo_emailVal" class="form-control" >
                    <?php
                    $tipos_email = Email::getTipos();
                    if(is_array($tipos_email))
                    {
                        foreach($tipos_email as $tipo)
                        {
                            echo "<option value='".$tipo['id_tipo_email']."' >".$tipo['tipo_email']."</option>";
                        }
                    }
                    ?>
                    </select>
                    <input type="button" value="Aceptar"  class="btn btn-primary btn-small" style="float: left; width: 40%; margin: 10px;" onclick="addEmail(this)" />
                    <input type="button" value="Cancelar" class="btn btn-danger btn-small" style="float: right; width: 40%; margin: 10px;" onclick="$(this).parent().fadeOut();"/>
                </div>

                 <div id="prompt_telefono" style="height:230px; width:250px; box-shadow: 2px 2px 10px #5f5f5f;">
                    <label>Teléfono:</label>
                    <input id="telefonoVal" type="tel" class="form-control"  />
                    <label>Tipo:</label>
                    <select id="tipo_telefonoVal" class="form-control"  >
                    <?php
                    $tipos_telefono = Telefono::getTipos();
                    if(is_array($tipos_telefono))
                    {
                        foreach($tipos_telefono as $tipo)
                        {
                            echo "<option value='".$tipo['id_tipo_telefono']."' >".$tipo['tipo_telefono']."</option>";
                        }
                    }
                    ?>
                    </select>
                    <input type="button" value="Aceptar"  class="btn btn-primary btn-small" style="float: left; width: 40%; margin: 10px;" onclick="addTelefono()" />
                    <input type="button" value="Cancelar"  class="btn btn-danger btn-small" style="float: right; width: 40%; margin: 10px;" onclick="$(this).parent().fadeOut();"/>
                </div>

                <div id="prompt_modificar_direccion" class="fixed_form" style="box-shadow: 2px 2px 10px #5f5f5f;" >
                    <div id="prompt_modificar_direccion_handle" class="fixed_form_handle">
                        <img src="/media/iconos/icon_close.gif" alt="Cerrar" onclick="$(this).parent().parent().fadeOut();" />
                    </div>
                    <div class="fixed_form_content">
                        <div class="fixed_form_row">
                            <label>Calle:</label>
                            <input id="calleValMdy" type="text" class="fixed_form_value form-control"  />
                        </div>
                        <div class="fixed_form_row">
                            <label>Número:</label>
                            <input id="numeroValMdy" type="text" class="fixed_form_value form-control"  />
                        </div>
                        <div class="fixed_form_row">
                            <label>Colonia:</label>
                            <select class="form_input form-control" name="coloniaValMdy" id="coloniaValMdy" required >
                                <?php
                                $colonias = Colonia::getColonias();
                                if(is_array($colonias))
                                {
                                    foreach($colonias as $colonia)
                                    {
                                        echo "<option value='".$colonia['id_colonia']."' >".$colonia['nombre']."</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="fixed_form_row">
                            <label>CP:</label>
                            <input id="CPValMdy" type="text" class="fixed_form_value form-control"  />
                        </div>
                        <div class="fixed_form_row">
                            <input type="button" id="boton_update" value="Aceptar" class="btn btn-primary btn-block" onclick="updateDireccion(this)" />
                        </div>
                    </div>
                </div>

                <!-- MODIFICAR ESCOLARIDAD -->

                <div id="prompt_modificar_escolaridad" class="fixed_form" style="box-shadow: 2px 2px 10px #5f5f5f;" >
                    <div id="prompt_modificar_direccion_handle" class="fixed_form_handle">
                        <img src="/media/iconos/icon_close.gif" alt="Cerrar" onclick="$(this).parent().parent().fadeOut();" />
                    </div>
                    <div class="fixed_form_content">
                        <div class="fixed_form_row">
                            <label>Título:</label>
                            <input id="tituloValMdy" type="text" class="fixed_form_value form-control"  />
                        </div>
                        <div class="fixed_form_row">
                            <label>Egresado de:</label>
                            <input id="egresadoDeValMdy" type="text" class="fixed_form_value form-control"  />
                        </div>
                        <div class="fixed_form_row">
                            <label>Año:</label>
                            <input id="anoValMdy" type="text" class="fixed_form_value form-control"  />
                        </div>

                        <div class="fixed_form_row">
                            <input type="button" id="boton_update" value="Aceptar" class="btn btn-primary btn-block" onclick="updateEscolaridad(this)" />
                        </div>
                    </div>
                </div>

                <!-- --------------------- -->

            </div>
        </div>

        <!-- Dialogo nutrición -->
        <div id="nuevo_registro_nut">
            <div class="dialogo_row">
                <label>Peso</label>
                <input type="text" id="nuevoPesoVal" />
            </div>
            <div class="dialogo_row">
                <label>Talla</label>
                <input type="text" id="nuevaTallaVal" />
            </div>
            <div class="dialogo_row">
                <label>IMC</label>
                <input type="text" id="nuevoIMCVal" />
            </div>
            <span>NOTA. Puedes dejar los campos vacios y no se hará cambio al valor actual</span>
            <button type="button" class="btn btn-primary" onclick="nuevoRegistroNut()">Aceptar</button>
        </div>
        <!-- Fin dialogo nutrición -->
		</div>
    </body>
    <script>
        /** Cosas del Ajax image loader */
        var f = $('#form_imagen');
        var l = $('#loader'); // loder.gif image
        var b = $('#button'); // upload button
        var imagen;

        b.click(function(){
            // implement with ajaxForm Plugin
            f.ajaxForm({
                beforeSend: function(){
                    l.show();
                    b.attr('disabled', 'disabled');
                },
                success: function(img){
                    l.hide();
                    f.resetForm();
                    b.removeAttr('disabled');

                    asignarFoto(img);
                },
                error: function(e){
                    b.removeAttr('disabled');
                }
            });
        });

        $("#nuevo_registro_nut").dialog({ autoOpen: false });

        $("#prompt_modificar_direccion").draggable({ handle: "#prompt_modificar_direccion_handle" });

        function asignarFoto(img)
        {
            if(img == "photo_NA.jpg") alert("La foto debe de tener un tamaño no mayor a 400kb y tener una terminación .jpg, .jpeg, .gif o .png");
            $.ajax({
                type: "POST",
                url: "/includes/acciones/personas/asignar_foto.php",
                data: "id_persona=" + id_maestro + "&imagen=" + img,
                success: function (data)
                {
                    document.location.reload(true);
                }
            });
        }

        function mostrarModificarDireccion()
        {
            $("#calleValMdy").val('<?php echo $direccion['calle']; ?>');
            $("#numeroValMdy").val('<?php echo $direccion['numero']; ?>');
            $("#coloniaValMdy").val('<?php echo $direccion['colonia']; ?>');
            $("#CPValMdy").val('<?php echo $direccion['CP']; ?>');

            $("#prompt_modificar_direccion").fadeIn();
        }

        function mostrarModificarEscolaridad()
        {
            $("#tituloValMdy").val('<?php echo $escolaridad['titulo']; ?>');
            $("#egresadoDeValMdy").val('<?php echo $escolaridad['egresadode']; ?>');
            $("#anoValMdy").val('<?php echo $escolaridad['ano']; ?>');

            $("#prompt_modificar_escolaridad").fadeIn();
        }

        function updateDireccion(boton)
        {
            $(boton).attr('disabled','disabled');

            var calle   = $("#calleValMdy").val();
            var numero  = $("#numeroValMdy").val();
            var colonia = $("#coloniaValMdy").val();
            var CP      = $("#CPValMdy").val();

            $.ajax({
                type: "POST",
                url: "/includes/acciones/maestros/updateDireccionAJAX.php",
                data: "id_maestro=" + id_maestro + "&calle=" + calle
                    + "&numero=" + numero + "&colonia=" + colonia + "&CP=" + CP,
                success: function (data)
                {
                    document.location.reload(true);
                }
            });
        }

        function updateEscolaridad(boton)
        {
            $(boton).attr('disabled','disabled');

            var titulo      = $("#tituloValMdy").val();
            var egresadoDe  = $("#egresadoDeValMdy").val();
            var ano         = $("#anoValMdy").val();

            $.ajax({
                type: "POST",
                url: "/includes/acciones/maestros/updateEscolaridadAJAX.php",
                data: "id_maestro=" + id_maestro + "&titulo=" + titulo + "&egresadoDe=" + egresadoDe + "&ano=" + ano,
                success: function (data)
                {
                    document.location.reload(true);
                }
            });
        }

        function nuevoRegistroNut()
        {
            var peso = $("#nuevoPesoVal").val();
            var talla = $("#nuevaTallaVal").val();
            var IMC = $("#nuevoIMCVal").val();

            if(confirm("¿Desea agregar el nuevo registro de nutrición?"))
            {
                $.ajax({
                    type: "POST",
                    url: "/includes/acciones/personas/nuevo_registro_nutricion.php",
                    data: "id_persona=" + id_maestro + "&peso=" + peso + "&talla=" + talla + "&IMC=" + IMC,
                    success: function (data)
                    {
                        $('#nuevo_registro_nut').dialog('close');
                        document.location.reload(true);
                    }
                });
            }
        }
    </script>
</html>