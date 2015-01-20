<?php
include("../../includes/validar_admin.php");
include_once("../../includes/clases/class_lib.php");
extract($_GET);
# id_alumno

$alumno = new Alumno($id_alumno);
if(is_null($alumno->id_persona)){ header('Location: /admin/alumnos/index.php'); exit; }
$area       = $alumno->getArea();
$emails     = $alumno->getEmails();
$telefonos  = $alumno->getTelefonos();
$clases     = $alumno->getClasesActuales();
$pagos      = $alumno->getPagosCuentasCiclo();
$ciclo      = CicloEscolar::getActual();
$grupo      = $alumno->getGrupo($ciclo->id_ciclo_escolar);
$grado      = $alumno->getGrado($ciclo->id_ciclo_escolar);
?>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Sistema Integral Meze - Perfil de alumno</title>
        <link rel="stylesheet" href="../../estilo/general.css" />
        <link rel="stylesheet" href="../../estilo/perfil_alumno.css" />
        <link rel="stylesheet" href="../../estilo/jquery.dataTables.css" />
        <link rel="stylesheet" href="../../estilo/fixed_form.css" />
        <link rel="stylesheet" href="../../estilo/perfiles.css" />
        <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
    </head>
    <body>
        <div id="wrapper">
            <?php include("../../includes/header.php"); ?>
            <div id="content">

                <div id="profile_picture">

                    <div id="profile_picture_inner">
                        <img src="../../media/fotos/<?php echo $alumno->foto; ?>" alt="N/A" id="foto_maestro" />
                    </div>

                </div>

                <!-- Datos del perfil. CSS: perfiles.css -->
                <div class="datos_perfil" >
                    <div class="datos_perfil_seccion" >
                        <div class="datos_perfil_dato">
                            <div class="perfil_dato_label">Nombre(s):</div>
                            <div class="perfil_dato_value"><?php echo $alumno->nombres; ?></div>
                            <img src="/media/iconos/icon_modify.png"
                                 ALT="M" onclick="cambiarNombresClicked()"
                                 style="width: 15px" title="Cambiar nombre" />
                        </div>
                        <div class="datos_perfil_dato">
                            <div class="perfil_dato_label">Apellido paterno:</div>
                            <div class="perfil_dato_value"><?php echo $alumno->apellido_paterno; ?></div>
                            <img src="/media/iconos/icon_modify.png"
                                 ALT="M" onclick="cambiarApellidoPaternoClicked()"
                                 style="width: 15px" title="Cambiar apellido paterno" />
                        </div>
                        <div class="datos_perfil_dato">
                            <div class="perfil_dato_label">Apellido materno:</div>
                            <div class="perfil_dato_value"><?php echo $alumno->apellido_materno; ?></div>
                            <img src="/media/iconos/icon_modify.png"
                                 ALT="M" onclick="cambiarApellidoMaternoClicked()"
                                 style="width: 15px" title="Cambiar apellido materno" />
                        </div>
                        <div class="datos_perfil_dato">
                            <div class="perfil_dato_label">Matricula:</div>
                            <div class="perfil_dato_value"><?php echo $alumno->matricula; ?></div>
                        </div>
                        <div class="datos_perfil_dato">
                            <div class="perfil_dato_label">Registrad@ desde:</div>
                            <div class="perfil_dato_value"><?php echo $alumno->fecha_alta; ?></div>
                        </div>
                        <div class="datos_perfil_dato">
                            <div class="perfil_dato_label">Peso:</div>
                            <div class="perfil_dato_value">
                                <?php echo $alumno->getPeso(); ?>
                                <img src="/media/iconos/icon_modify.png"
                                     ALT="M" onclick="$('#nuevo_registro_nut').dialog('open');"
                                     style="width: 15px" title="Cambiar grupo" />
                            </div>
                        </div>
                        <div class="datos_perfil_dato">
                            <div class="perfil_dato_label">Talla:</div>
                            <div class="perfil_dato_value">
                                <?php echo $alumno->getTalla(); ?>
                            </div>
                        </div>
                        <div class="datos_perfil_dato">
                            <div class="perfil_dato_label">IMC:</div>
                            <div class="perfil_dato_value">
                                <?php echo $alumno->getIMC(); ?>
                            </div>
                        </div>
                    </div>
                    <div class="datos_perfil_seccion" >
                        <div class="datos_perfil_dato">
                            <div class="perfil_dato_label">Estado:</div>
                            <?php
                                if($alumno->getEstado())
                                {
                                    echo '
                                        <div class="datos_generales_value" style="color: green">
                                            Activo
                                            <img onclick="baja('.$alumno->id_persona.')" src="/media/iconos/icon_close.gif" alt="X" />
                                        </div>
                                    ';
                                }
                                else
                                {
                                    echo '<div class="datos_generales_value" style="color: red">Inactivo</div>';
                                }
                            ?>
                        </div>
                        <div class="datos_perfil_dato">
                            <div class="perfil_dato_label">Beca:</div>
                            <div class="perfil_dato_value">
                                <?php
                                $beca = $alumno->getBecaActual();
                                if(is_null($beca))
                                {
                                    echo "No";
                                }
                                else
                                {
                                    echo $beca['beca']." (".$beca['tipo'].")";
                                    echo '
                                    <a href="../becas/modificar.php?id_alumno='.$id_alumno.'">
                                        <img width="11" height="12" src="/media/iconos/icon_modify.png" alt="M" />
                                    </a>
                                    ';
                                }
                                ?>
                            </div>
                        </div>
                        <div class="datos_perfil_dato">
                            <div class="perfil_dato_label">Contraseña:</div>
                            <div class="perfil_dato_value">
                                <?php echo $alumno->password; ?>
                                <img src="/media/iconos/icon_modify.png"
                                     ALT="M" onclick="cambiarPasswordClicked()"
                                     style="width: 15px" title="Cambiar contraseña" />
                            </div>
                        </div>
                        <div class="datos_perfil_dato">
                            <div class="perfil_dato_label">Nivel:</div>
                            <div class="perfil_dato_value">
                                <?php echo $grado." de ".$area['area']; ?>
                            </div>
                        </div>
                        <div class="datos_perfil_dato">
                            <div class="perfil_dato_label">Grupo:</div>
                            <div class="perfil_dato_value">
                                <?php echo $grupo; ?>
                                <img src="/media/iconos/icon_modify.png"
                                     ALT="M" onclick="cambiarGrupoClicked()"
                                     style="width: 15px" title="Cambiar grupo" />
                            </div>
                        </div>
                    </div>
                </div>

                <img 
                    id="qr_code"
                    alt ="<?php echo $alumno->matricula; ?>" 
                    src="https://chart.googleapis.com/chart?chs=150x150&cht=qr&chl=<?php echo $alumno->matricula; ?>&chld=L|0"  
                />

                <div id="panel_imagen">
                    <!-- loader.gif -->
                    <img style="display:none" id="loader" src="/media/imagenes/loader.gif" alt="Cargando...." title="Cargando...." />
                    <!-- simple file uploading form -->
                    <form id="form_imagen" action="/includes/ajaxuploadAlumnos.php" method="post" enctype="multipart/form-data">
                        <input id="uploadImage" type="file" accept="image/*" name="image" value="Seleccionar foto"/>
                        <input id="button" type="submit" value="Subir">
                    </form>
                </div>

                <div id="tabs">
                    <ul>
                        <li><a href="#tabs-1">Clases</a></li>
                        <li><a href="#tabs-2">Tutores</a></li>
                        <li><a href="#tabs-3">E-Mails</a></li>
                        <li><a href="#tabs-4">Teléfonos</a></li>
                        <li><a href="#tabs-5">Pagos</a></li>
                        <li><a href="#tabs-6">Información</a></li>
                        <li><a href="#tabs-7">Becas</a></li>
                        <li><a href="#tabs-8">Calificaciones</a></li>
                        <li><a href="#tabs-9">Papeleria</a></li>
                        <li><a href="#tabs-10">Nutrición</a></li>
                        <li><a href="#tabs-11">Cuentas</a></li>
                    </ul>
                    <div id="tabs-1">
                        <table id="tabla_clases">
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
                    <div id="tabs-2">
                        <table id="tabla_tutores">
                            <thead>
                            <tr>
                                <th>Tipo</th>
                                <th>Nombre</th>
                                <th>Calle</th>
                                <th>Número</th>
                                <th>Colonia</th>
                                <th>CP</th>
                                <th>Teléfonos</th>
                                <th>Celular</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $tutores = $alumno->getTutores();
                            if(is_array($tutores))
                            {
                                foreach($tutores as $tutor)
                                {
                                    echo "
                                        <tr class='tutor' >
                                            <input type='hidden' class='id_tipo_tutor' value='".$tutor['id_tipo_tutor']."' />
                                            <td class='tipo_tutor' >".$tutor['tipo_tutor']."</td>
                                            <td class='nombre' >".$tutor['nombre']."</td>
                                            <td class='calle' >".$tutor['calle']."</td>
                                            <td class='numero' >".$tutor['numero']."</td>
                                            <td class='colonia' >".$tutor['colonia']."</td>
                                            <td class='CP' >".$tutor['CP']."</td>
                                            <td class='telefonos' >".$tutor['telefonos']."</td>
                                            <td class='celular' >".$tutor['celular']."</td>
                                            <td><img src='/media/iconos/icon_modify.png' style='width:15px' onclick='modificarTutor(this)' /></td>
                                        </tr>
                                    ";
                                }
                            }
                            ?>
                            </tbody>
                        </table>
                        <img src="../../media/iconos/icon_add.png" alt="Nuevo" style="float: right;" onclick="toggleTutor();" />
                    </div>
                    <div id="tabs-3">
                        <table id="tabla_emails">
                            <thead>
                                <tr>
                                    <th>Tipo de correo electrónico</th>
                                    <th>Correo electrónico</th>
                                    <th>Eliminar</th>
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
                                                <a href='../../includes/acciones/alumnos/eliminar_email.php?id_email=".$email['id_email']."' >
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
                    <div id="tabs-4">
                        <table id="tabla_telefonos">
                            <thead>
                                <tr>
                                    <th>Tipo de teléfono</th>
                                    <th>Teléfono</th>
                                    <th>Eliminar</th>
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
                        <img src="../../media/iconos/icon_add.png" alt="Nuevo" style="float: right;" onclick="toggleTelefono();" />
                    </div>
                    <div id="tabs-5">
                        <table id="tabla_pagos">
                            <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Concepto</th>
                                <th>Monto</th>
                                <th>Usuario</th>
                                <th>Descripción</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if(is_array($pagos))
                            {
                                foreach($pagos as $pago)
                                {
                                    echo "
                                        <tr>
                                            <td>".$pago['fecha']."</td>
                                            <td>".$pago['concepto']."</td>
                                            <td>$ ".$pago['monto']."</td>
                                            <td>".$pago['usuario']."</td>
                                            <td>".$pago['descripcion']."</td>
                                        </tr>
                                    ";
                                }
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                    <div id="tabs-6">
                        <div style="width: 90%; margin: 10px 0;">
                            <?php $direccion = $alumno->getDireccion(); ?>
                            <label class="form_label" for="calleVal">Calle</label>
                            <input type="text" class="form_input" id="calleVal" value="<?php echo $direccion['calle']; ?>" readonly />
                            <label class="form_label" for="numeroVal">Número</label>
                            <input type="text" class="form_input" id="numeroVal" value="<?php echo $direccion['numero']; ?>" readonly />
                            <label class="form_label" for="coloniaVal">Colonia</label>
                            <input type="text" class="form_input" id="coloniaVal" value="<?php echo $direccion['colonia']; ?>" readonly />
                            <label class="form_label" for="CPVal">CP</label>
                            <input type="text" class="form_input" id="CPVal" value="<?php echo $direccion['CP']; ?>" readonly />
                            <img src="/media/iconos/icon_modify.png" width="15" ALT="M" onclick="mostrarMdyDireccion()" />
                        </div>
                        <label>Club</label>
                        <input type="text" class="form_input" value="<?php echo $alumno->getClubDeportivo(); ?>" readonly />
                        <label>CURP</label>
                        <input type="text" class="form_input" value="<?php echo $alumno->getCURP(); ?>" readonly />
                    </div>
                    <div id="tabs-7">
                        <table id="tabla_becas">
                            <thead>
                            <tr>
                                <th>Ciclo escolar</th>
                                <th>Beca</th>
                                <th>Tipo</th>
                                <th>Subtipo</th>
                                <th>Aprobada por</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $becas = $alumno->getHistorialBecas();
                            if(is_array($becas))
                            {
                                foreach($becas as $beca)
                                {
                                    echo "
                                        <tr>
                                            <td>".$beca['ciclo_escolar']."</td>
                                            <td>".$beca['beca']."</td>
                                            <td>".$beca['tipo_beca']."</td>
                                            <td>".$beca['subtipo_beca']."</td>
                                            <td>".$beca['usuario']."</td>
                                        </tr>
                                    ";
                                }
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                    <div id="tabs-8">
                        <label>Ciclo escolar</label>
                        <select id="ciclo_escolarVal" onchange="$('#tabla_calificaciones').fnReloadAjax()">
                            <?php
                                $ciclos = CicloEscolar::getLista();
                                if(is_array($ciclos))
                                {
                                    foreach($ciclos as $ciclo)
                                    {
                                        echo "<option value='".$ciclo['id_ciclo_escolar']."'>".$ciclo['ciclo_escolar']."</option>";
                                    }
                                }
                            ?>
                        </select>
                        <table id="tabla_calificaciones">
                            <thead>
                                <tr>
                                    <th>Materia</th>
                                    <?php
                                    for($p = 1; $p <= $area['no_parciales']; $p++)
                                    {
                                        echo "<th>Bloque ".$p."</th>";
                                    }
                                    ?>
                                    <th>Promedio</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- AJAX -->
                            </tbody>
                        </table>
                    </div>
                    <div id="tabs-9">
                        <table>
                            <thead>
                            <tr>
                                <th>Documento</th>
                                <th style="width: 120px" >Original</th>
                                <th>Copia</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $papeleria = $alumno->getPapeleria();
                            if(is_array($papeleria))
                            {
                                foreach($papeleria as $documento)
                                {
                                    $id_documento   = $documento['id_documento'];
                                    $nombre         = $documento['documento'];
                                    $original = ''; $copia = '';
                                    if($documento['original'] == 1) $original = 'checked';
                                    if($documento['copia'] == 1) $copia = 'checked';

                                    echo "
                                            <tr class='documento' >
                                                <input type='hidden' class='id_documento' value='".$id_documento."' />
                                                <td>".$nombre."</td>
                                                <td><input type='checkbox' class='original' value='".$id_documento."' ".$original." /></td>
                                                <td><input type='checkbox' class='copia' value='".$id_documento."' ".$copia." /></td>
                                            </tr>
                                        ";
                                }
                            }
                            ?>
                            </tbody>
                        </table>
                        <input type="button" value="Actualizar" onclick="updatePapeleria(this)" />
                    </div>
                    <div id="tabs-10">

                    </div>
                    <div id="tabs-11">
                        <table id="tabla_cuentas">
                            <thead>
                                <tr>
                                    <th>Concepto</th>
                                    <th>Total</th>
                                    <th>Pagado</th>
                                    <th>Adeudo</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                $cuentas = $alumno->getCuentasOtras($ciclo['id_ciclo_escolar']);
                                if(is_array($cuentas))
                                {
                                    foreach($cuentas as $cuenta)
                                    {
                                        echo "
                                            <tr>
                                                <td>".$cuenta['concepto']."</td>
                                                <td>$".$cuenta['monto']."</td>
                                                <td>$".$cuenta['pagado']."</td>
                                                <td>$".$cuenta['deuda']."</td>
                                            </tr>
                                        ";
                                    }
                                }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div id="div_boton_cuentas">
                    <div id="wrapper_select">
                        <label>Ciclo escolar</label>
                        <select id="ciclo_escolarCuentasVal" >
                            <?php
                            $ciclos = CicloEscolar::getLista();
                            if(is_array($ciclos))
                            {
                                foreach($ciclos as $ciclo)
                                {
                                    echo "<option value='".$ciclo['id_ciclo_escolar']."'>".$ciclo['ciclo_escolar']."</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <button onclick="perfilCuentas();" style="margin: 15px 0 0;" >Perfil de cuentas</button>
                </div>

                <div id="prompt_email" class="fixed_form" >
                    <div id="prompt_email_handle" class="fixed_form_handle">
                        <img src="/media/iconos/icon_close.gif" alt="Cerrar" onclick="$(this).parent().parent().fadeOut();" />
                    </div>
                    <div class="fixed_form_content">
                        <div class="fixed_form_row">
                            <label>Tipo:</label>
                            <select id="tipo_emailVal" class="fixed_form_value" >
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
                        </div>
                        <div class="fixed_form_row">
                            <label>E-Mail:</label>
                            <input id="emailVal" type="text" class="fixed_form_value" />
                        </div>
                        <div class="fixed_form_row">
                            <input type="button" value="Aceptar" class="fixed_form_button" onclick="addEmail()" />
                        </div>
                    </div>
                </div>

                <div id="prompt_telefono" class="fixed_form" >
                    <div id="prompt_telefono_handle" class="fixed_form_handle">
                        <img src="/media/iconos/icon_close.gif" alt="Cerrar" onclick="$(this).parent().parent().fadeOut();" />
                    </div>
                    <div class="fixed_form_content">
                        <div class="fixed_form_row">
                            <label>Tipo:</label>
                            <select id="tipo_telefonoVal" class="fixed_form_value" >
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
                        </div>
                        <div class="fixed_form_row">
                            <label>Teléfono:</label>
                            <input id="telefonoVal" type="tel" class="fixed_form_value"  />
                        </div>
                        <div class="fixed_form_row">
                            <input type="button" value="Aceptar" class="fixed_form_button" onclick="addTelefono()" />
                        </div>
                    </div>
                </div>

                <div id="prompt_tutor" class="fixed_form" >
                    <div id="prompt_tutor_handle" class="fixed_form_handle">
                        <img src="/media/iconos/icon_close.gif" alt="Cerrar" onclick="$(this).parent().parent().fadeOut();" />
                    </div>
                    <div class="fixed_form_content">
                        <div class="prompt_column">
                            <div class="fixed_form_row">
                                <label>Tipo:</label>
                                <select id="tipo_tutorVal" class="fixed_form_value" >
                                    <?php
                                    $tipos = Tutor::getTipos();
                                    if(is_array($tipos))
                                    {
                                        foreach($tipos as $tipo)
                                        {
                                            echo "<option value='".$tipo['id_tipo_tutor']."' >".$tipo['tipo_tutor']."</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="fixed_form_row">
                                <label>Nombre:</label>
                                <input id="nombreTutorVal" type="text" class="fixed_form_value"  />
                            </div>
                            <div class="fixed_form_row">
                                <label>Calle:</label>
                                <input id="calleTutorVal" type="text" class="fixed_form_value"  />
                            </div>
                            <div class="fixed_form_row">
                                <label>Número:</label>
                                <input id="numeroTutorVal" type="text" class="fixed_form_value"  />
                            </div>
                        </div>
                        <div class="prompt_column">
                            <div class="fixed_form_row">
                                <label>Colonia:</label>
                                <input id="coloniaTutorVal" type="text" class="fixed_form_value"  />
                            </div>
                            <div class="fixed_form_row">
                                <label>CP:</label>
                                <input id="CPTutorVal" type="text" class="fixed_form_value"  />
                            </div>
                            <div class="fixed_form_row">
                                <label>Teléfonos:</label>
                                <input id="telefonosTutorVal" type="text" class="fixed_form_value"  />
                            </div>
                            <div class="fixed_form_row">
                                <label>Celular:</label>
                                <input id="celularTutorVal" type="text" class="fixed_form_value"  />
                            </div>
                            <div class="fixed_form_row">
                                <input type="button" value="Aceptar" class="fixed_form_button" onclick="addTutor()" />
                            </div>
                        </div>
                    </div>
                </div>

                <div id="prompt_tutor_modificar" class="fixed_form" >
                    <div id="prompt_tutor_handle_modificar" class="fixed_form_handle">
                        <img src="/media/iconos/icon_close.gif" alt="Cerrar" onclick="$(this).parent().parent().fadeOut();" />
                    </div>
                    <div class="fixed_form_content">
                        <div class="prompt_column">
                            <div class="fixed_form_row">
                                <label>Nombre:</label>
                                <input id="nombreTutorVal_mdy" type="text" class="fixed_form_value"  />
                            </div>
                            <div class="fixed_form_row">
                                <label>Calle:</label>
                                <input id="calleTutorValMdy" type="text" class="fixed_form_value"  />
                            </div>
                            <div class="fixed_form_row">
                                <label>Número:</label>
                                <input id="numeroTutorValMdy" type="text" class="fixed_form_value"  />
                            </div>
                        </div>
                        <div class="prompt_column">
                            <div class="fixed_form_row">
                                <label>Colonia:</label>
                                <input id="coloniaTutorValMdy" type="text" class="fixed_form_value"  />
                            </div>
                            <div class="fixed_form_row">
                                <label>CP:</label>
                                <input id="CPTutorValMdy" type="text" class="fixed_form_value"  />
                            </div>
                            <div class="fixed_form_row">
                                <label>Teléfonos:</label>
                                <input id="telefonosTutorVal_mdy" type="text" class="fixed_form_value"  />
                            </div>
                            <div class="fixed_form_row">
                                <label>Celular:</label>
                                <input id="celularTutorVal_mdy" type="text" class="fixed_form_value"  />
                            </div>
                            <div class="fixed_form_row">
                                <input type="button" id="boton_update" value="Aceptar" class="fixed_form_button" onclick="updateTutor()" />
                            </div>
                        </div>
                    </div>
                </div>

                <div id="prompt_modificar_direccion" class="fixed_form" >
                    <div id="prompt_modificar_direccion_handle" class="fixed_form_handle">
                        <img src="/media/iconos/icon_close.gif" alt="Cerrar" onclick="$(this).parent().parent().fadeOut();" />
                    </div>
                    <div class="fixed_form_content">
                        <div class="fixed_form_row">
                            <label>Calle:</label>
                            <input id="calleValMdy" type="text" class="fixed_form_value"  />
                        </div>
                        <div class="fixed_form_row">
                            <label>Número:</label>
                            <input id="numeroValMdy" type="text" class="fixed_form_value"  />
                        </div>
                        <div class="fixed_form_row">
                            <label>Colonia:</label>
                            <select class="fixed_form_value" name="coloniaValMdy" id="coloniaValMdy" >
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
                        <div class="fixed_form_row">
                            <label>CP:</label>
                            <input id="CPValMdy" type="text" class="fixed_form_value"  />
                        </div>
                        <div class="fixed_form_row">
                            <label>Club:</label>
                            <select class="fixed_form_value" name="ClubValMdy" id="ClubValMdy" >
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
                        <div class="fixed_form_row">
                            <label>CURP:</label>
                            <input id="CURPValMdy" type="text" class="fixed_form_value"  />
                        </div>
                        <div class="fixed_form_row">
                            <input type="button" id="boton_update" value="Aceptar" class="fixed_form_button" onclick="updateDireccion(this)" />
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Dialogo para cambiar de grupo -->
        <div id="dialogo_cambio" title="Cambio de grupo" >
            <label>¿A que grupo desea cambiar al alumno?</label>
            <hr />
            <div class="dialogo_row">
                <label>Area</label>
                <select id="areaVal" onchange="loadGrados();">
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
            <div class="dialogo_row">
                <label>Grado</label>
                <select id="gradoVal" onchange="loadGrupos();">
                    <!-- AJAX -->
                </select>
            </div>
            <div class="dialogo_row">
                <label>Grupo</label>
                <select id="nuevoGrupoVal">
                    <!-- AJAX -->
                </select>
            </div>

            <button onclick="aceptarCambioClicked(this)">Aceptar</button>
            <!--<input type="button" value="Aceptar" onclick="aceptarCambioClicked(this)" />-->
        </div>
        <!-- -------Fin del dialogo------- -->

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
            <button type="button" onclick="nuevoRegistroNut()">Aceptar</button>
        </div>
        <!-- Fin dialogo nutrición -->

        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
        <script src="../../librerias/jquery.dataTables.min.js" ></script>
        <script src="../../librerias/fnAjaxReload.js" ></script>
        <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
        <script src="/librerias/jquery.form.js"></script>
        <script>
        var id_alumno = <?php echo $id_alumno; ?>;
        var id_ciclo = <?php echo $ciclo['id_ciclo_escolar']; ?>;
        var id_tipo_tutor;

        /** Document ready */
        $("#prompt_modificar_direccion").draggable({ handle: "#prompt_modificar_direccion_handle" });
        $("#dialogo_cambio").dialog({ autoOpen: false });
        $("#nuevo_registro_nut").dialog({ autoOpen: false });
        declararDataTables();
        $("#prompt_email").draggable({ handle: "#prompt_email_handle" });
        $("#prompt_telefono").draggable({ handle: "#prompt_telefono_handle" });
        $("#prompt_tutor").draggable({ handle: "#prompt_tutor_handle" });
        $("#prompt_tutor_modificar").draggable({ handle: "#prompt_tutor_handle_modificar" });
        $("#tabs").tabs();

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

        function asignarFoto(img)
        {
            if(img == "photo_NA.jpg") alert("La foto debe de tener un tamaño no mayor a 400kb y tener una terminación .jpg, .jpeg, .gif o .png");
            $.ajax({
                type: "POST",
                url: "/includes/acciones/personas/asignar_foto.php",
                data: "id_persona=" + id_alumno + "&imagen=" + img,
                success: function (data)
                {
                    document.location.reload(true);
                }
            });
        }

        function cambiarNombresClicked()
        {
            var nuevosNombres = prompt("Nombres ", "<?php echo $alumno->nombres; ?>");
            if(nuevosNombres !== null)
            {
                if(nuevosNombres.length > 2)
                {
                    if(confirm("¿Seguro que desea cambiar el nombre del alumno a: " + nuevosNombres))
                    {
                        $.post("/includes/acciones/personas/cambiar_nombres.php", {id_persona:id_alumno, nombres:nuevosNombres}, function (data)
                        {
                            document.location.reload(true);
                        });
                    }
                }
            }
        }

        function cambiarApellidoPaternoClicked()
        {
            var nuevoApellidoPaternoNuevo = prompt("Apellido paterno ", "<?php echo $alumno->apellido_paterno; ?>");
            if(nuevoApellidoPaternoNuevo !== null)
            {
                if(nuevoApellidoPaternoNuevo.length > 2)
                {
                    if(confirm("¿Seguro que desea cambiar el apellido paterno del alumno a: " + nuevoApellidoPaternoNuevo))
                    {
                        $.post("/includes/acciones/personas/cambiar_apellido_paterno.php", {id_persona:id_alumno, apellido:nuevoApellidoPaternoNuevo}, function (data)
                        {
                            document.location.reload(true);
                        });
                    }
                }
            }
        }

        function cambiarApellidoMaternoClicked()
        {
            var nuevaApellidoMaternoNuevo = prompt("Apellido materno ", "<?php echo $alumno->apellido_materno; ?>");
            if(nuevaApellidoMaternoNuevo !== null)
            {
                if(nuevaApellidoMaternoNuevo.length > 2)
                {
                    if(confirm("¿Seguro que desea cambiar el apellido materno del alumno a: " + nuevaApellidoMaternoNuevo))
                    {
                        $.post("/includes/acciones/personas/cambiar_apellido_materno.php", {id_persona:id_alumno, apellido:nuevaApellidoMaternoNuevo}, function (data)
                        {
                            document.location.reload(true);
                        });
                    }
                }
            }
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
            var id_grado = $("#gradoVal").val();

            $.post("/includes/acciones/grupos/print_select_grupos.php", { id_ciclo:id_ciclo, id_grado: id_grado }, function (data)
            {
                $("#nuevoGrupoVal").html(data);
            });
        }

        function updatePapeleria(boton)
        {
            $(boton).attr('disabled', 'disabled');

            var papeleria = [];
            $(".documento").each(function(){
                var documento = {};
                documento.id_documento  = $(this).find('.id_documento').val();
                documento.original      = $(this).find('.original').prop('checked') ? 1 : 0;
                documento.copia         = $(this).find('.copia').prop('checked') ? 1 : 0;
                papeleria.push(documento);
            });

            $.ajax({
                type: "POST",
                url: "/includes/acciones/alumnos/updatePapeleriaAJAX.php",
                data: "id_alumno=" + id_alumno + "&papeleria=" + JSON.stringify(papeleria),
                success: function (data)
                {
                    if(data == 1) document.location.reload(true);
                    else alert("Error al actualizar la papeleria");
                }
            });
        }

        function modificarTutor(caller)
        {
            var tutor = {};
            id_tipo_tutor = $(caller).parent(0).parent(0).children('.id_tipo_tutor').val();
            tutor.nombre        = $(caller).parent(0).parent(0).children('.nombre').html();
            tutor.calle         = $(caller).parent(0).parent(0).children('.calle').html();
            tutor.numero        = $(caller).parent(0).parent(0).children('.numero').html();
            tutor.colonia       = $(caller).parent(0).parent(0).children('.colonia').html();
            tutor.CP            = $(caller).parent(0).parent(0).children('.CP').html();
            tutor.telefonos     = $(caller).parent(0).parent(0).children('.telefonos').html();
            tutor.celular       = $(caller).parent(0).parent(0).children('.celular').html();

            $("#nombreTutorVal_mdy").val(tutor.nombre);
            $("#calleTutorValMdy").val(tutor.calle);
            $("#numeroTutorValMdy").val(tutor.numero);
            $("#coloniaTutorValMdy").val(tutor.colonia);
            $("#CPTutorValMdy").val(tutor.CP);
            $("#telefonosTutorVal_mdy").val(tutor.telefonos);
            $("#celularTutorVal_mdy").val(tutor.celular);

            console.dir(tutor);
            $("#prompt_tutor_modificar").fadeIn("slow");
        }

        function updateTutor()
        {
            $("#boton_update").attr('disabled','disabled');
            var tutor = {};
            tutor.id_alumno     = id_alumno;
            tutor.id_tipo_tutor = id_tipo_tutor;
            tutor.nombre        = $("#nombreTutorVal_mdy").val();
            tutor.calle         = $("#calleTutorValMdy").val();
            tutor.numero        = $("#numeroTutorValMdy").val();
            tutor.colonia       = $("#coloniaTutorValMdy").val();
            tutor.CP            = $("#CPTutorValMdy").val();
            tutor.telefonos     = $("#telefonosTutorVal_mdy").val();
            tutor.celular       = $("#celularTutorVal_mdy").val();

            $.ajax({
                type: "POST",
                url: "/includes/acciones/alumnos/updateTutorAJAX.php",
                data: "datos=" + JSON.stringify(tutor),
                success: function (data)
                {
                    if(data == 1) document.location.reload(true);
                    else alert("Error al actualizar datos del tutor");
                }
            });
        }

        function mostrarMdyDireccion()
        {
            $("#calleValMdy").val('<?php echo $direccion['calle']; ?>');
            $("#numeroValMdy").val('<?php echo $direccion['numero']; ?>');
            $("#coloniaValMdy option").filter(function(){
                return $(this).text() == "<?php echo $direccion['colonia']; ?>";
            }).prop('selected', true);
            $("#CPValMdy").val('<?php echo $direccion['CP']; ?>');

            $("#ClubValMdy option").filter(function(){
                return $(this).text() == "<?php echo $alumno->getClubDeportivo(); ?>";
            }).prop('selected', true);
            $("#CURPValMdy").val('<?php echo $alumno->getCURP(); ?>');

            $("#prompt_modificar_direccion").fadeIn();
        }

        function updateDireccion(boton)
        {
            $(boton).attr('disabled','disabled');

            var calle   = $("#calleValMdy").val();
            var numero  = $("#numeroValMdy").val();
            var colonia = $("#coloniaValMdy").val();
            var CP      = $("#CPValMdy").val();

            var club    = $("#ClubValMdy").val();
            var CURP    = $("#CURPValMdy").val();

            $.ajax({
                type: "POST",
                url: "../../includes/acciones/personas/update_direccion.php",
                data: "id_persona=" + id_alumno + "&calle=" + calle
                    + "&numero=" + numero + "&colonia=" + colonia + "&CP=" + CP,
                success: function (data)
                {
                    $.ajax({
                        type: "POST",
                        url: "../../includes/acciones/alumnos/update_club_curp.php",
                        data: "id_persona=" + id_alumno + "&club=" + club + "&CURP=" + CURP,
                        success: function (data)
                        {
                            document.location.reload(true);
                        }
                    });
                }
            });


        }

        function perfilCuentas()
        {
            console.log("perfilCuentas()");
            var id_ciclo_escolar = $("#ciclo_escolarCuentasVal").val();
            document.location.href = "/admin/alumnos/perfil_cuentas.php?id_alumno=" + id_alumno + "&ciclo=" + id_ciclo_escolar;
        }

        function cambiarGrupoClicked()
        {
            $("#dialogo_cambio").dialog( "open" );
        }

        function cambiarPasswordClicked()
        {
            var pass_nuevo = prompt("Contraseña nueva: ");
            if(pass_nuevo !== null)
            {
                if(pass_nuevo.length > 3)
                {
                    if(confirm("¿Desea cambiar la contraseña a '" + pass_nuevo + "'?"))
                    {
                        $.post("/includes/acciones/personas/cambiar_password.php", {id_persona:id_alumno, passwordVal:pass_nuevo, password2Val:pass_nuevo}, function (data)
                        {
                            document.location.reload(true);
                        });
                    }
                }
                else
                {
                    alert("El nuevo password debe de contener al menos 4 caracteres");
                }
            }
        }

        function aceptarCambioClicked(caller)
        {
            var id_grupo = $("#nuevoGrupoVal").val();
            if(id_grupo != '' && id_grupo != null)
            {
                if(confirm("¿Desea cambia de grupo al alumno?"))
                {
                    $(caller).attr('disabled', 'disabled');

                    $.ajax({
                        type: "POST",
                        url: "/includes/acciones/alumnos/cambiar_grupo.php",
                        data: "id_alumno=" + id_alumno + "&id_grupo=" + id_grupo,
                        success: function (data)
                        {
                            if(data == 1)
                            {
                                alert("Cambio completado.");
                                document.location.reload(true);
                            }
                        }
                    });
                }
            }
        }

        function getURLParameter(name)
        {
            return decodeURIComponent((new RegExp('[?|&]' + name + '=' + '([^&;]+?)(&|#|;|$)').exec(location.search) || [, ""])[1].replace(/\+/g, '%20')) || null
        }

        function declararDataTables()
        {
            $('#tabla_clases').dataTable({
                "oLanguage": {
                    "sLengthMenu": "Mostrar _MENU_ clases por página",
                    "sZeroRecords": "El alumno no se encuentra inscrito en ninguna clase",
                    "sInfo": "Mostrando _START_ a _END_ de _TOTAL_ clases",
                    "sInfoEmpty": "Mostrando 0 a 0 de 0 clases",
                    "sInfoFiltered": "(Encontrados de _MAX_ clases)"
                },
                "bFilter": false,
                "bLengthChange": false,
                "bPaginate": false,
                "bInfo": false
            });

            $("#tabla_tutores").dataTable({
                "oLanguage": {
                    "sLengthMenu": "Mostrar _MENU_ tutores por página",
                    "sZeroRecords": "El alumno no se cuenta con ningún tutor registrado",
                    "sInfo": "Mostrando _START_ a _END_ de _TOTAL_ tutores",
                    "sInfoEmpty": "Mostrando 0 a 0 de 0 tutores",
                    "sInfoFiltered": "(Encontrados de _MAX_ tutores)"
                },
                "bFilter": false,
                "bLengthChange": false,
                "bPaginate": false,
                "bInfo": false
            });

            $('#tabla_emails').dataTable({
                "oLanguage": {
                    "sLengthMenu": "Mostrar _MENU_ correos por página",
                    "sZeroRecords": "El alumno no cuenta con ningún correo registrado",
                    "sInfo": "Mostrando _START_ a _END_ de _TOTAL_ correos",
                    "sInfoEmpty": "Mostrando 0 a 0 de 0 correos",
                    "sInfoFiltered": "(Encontrados de _MAX_ correos)"
                },
                "bFilter": false,
                "bLengthChange": false,
                "bPaginate": false,
                "bInfo": false
            });

            $('#tabla_telefonos').dataTable({
                "oLanguage": {
                    "sLengthMenu": "Mostrar _MENU_ telefonos por página",
                    "sZeroRecords": "El alumno no cuenta con ningún telefono registrado",
                    "sInfo": "Mostrando _START_ a _END_ de _TOTAL_ telefonos",
                    "sInfoEmpty": "Mostrando 0 a 0 de 0 telefonos",
                    "sInfoFiltered": "(Encontrados de _MAX_ telefonos)"
                },
                "bFilter": false,
                "bLengthChange": false,
                "bPaginate": false,
                "bInfo": false
            });

            $('#tabla_pagos').dataTable({
                "oLanguage": {
                    "sLengthMenu": "Mostrar _MENU_ pagos por página",
                    "sZeroRecords": "El alumno no cuenta con ningún pago registrado",
                    "sInfo": "Mostrando _START_ a _END_ de _TOTAL_ pagos",
                    "sInfoEmpty": "Mostrando 0 a 0 de 0 pagos",
                    "sInfoFiltered": "(Encontrados de _MAX_ pagos)"
                },
                "bFilter": false,
                "bLengthChange": false,
                "bPaginate": false,
                "bInfo": false
            });

            $("#tabla_becas").dataTable({
                "oLanguage": {
                    "sLengthMenu": "Mostrar _MENU_ becas por página",
                    "sZeroRecords": "El alumno no cuenta con ninguna beca registrada",
                    "sInfo": "Mostrando _START_ a _END_ de _TOTAL_ becas",
                    "sInfoEmpty": "Mostrando 0 a 0 de 0 becas",
                    "sInfoFiltered": "(Encontrados de _MAX_ becas)"
                },
                "bFilter": false,
                "bLengthChange": false,
                "bPaginate": false,
                "bInfo": false
            });

            $("#tabla_calificaciones").dataTable({
                "bPaginate":   false,
                "oLanguage": {
                    "sLengthMenu": "Mostrar _MENU_ materias por página",
                    "sZeroRecords": "No se encontraron materias",
                    "sInfo": "Mostrando _START_ a _END_ de _TOTAL_ materias",
                    "sInfoEmpty": "Mostrando 0 a 0 de 0 materias",
                    "sInfoFiltered": "(Encontrados de _MAX_ materias)"
                },
                "aoColumns": [
                    {"sWidth":"40%"},{"sWidth":"10%"},{"sWidth":"10%"},
                    {"sWidth":"10%"},{"sWidth":"10%"},{"sWidth":"10%"},
                    {"sWidth":"10%"}
                ],
                "bProcessing": true,
                "sAjaxSource": '/includes/acciones/alumnos/get_calificaciones_ciclo.php',
                "fnServerParams": function (aoData)
                {
                    var id_ciclo = $("#ciclo_escolarVal").val();
                    var id_persona = <?php echo $alumno->id_persona; ?>;
                    aoData.push({ "name": "id_ciclo", "value": id_ciclo });
                    aoData.push({ "name": "id_persona", "value": id_persona });
                }
            });

            $("#tabla_cuentas").dataTable({
                "bPaginate":   false,
                "oLanguage": {
                    "sLengthMenu": "Mostrar _MENU_ cuentas por página",
                    "sZeroRecords": "No se encontraron cuentas",
                    "sInfo": "Mostrando _START_ a _END_ de _TOTAL_ cuentas",
                    "sInfoEmpty": "Mostrando 0 a 0 de 0 cuentas",
                    "sInfoFiltered": "(Encontrados de _MAX_ cuentas)"
                },
                "aoColumns": [
                    {"sWidth":"55%"},{"sWidth":"15%"},{"sWidth":"15%"},
                    {"sWidth":"15%"}
                ]
            });
        }

        var id_alumno = getURLParameter("id_alumno");

        function baja(id_persona)
        {
            if (confirm("¿Está seguro que desea dar de baja al alumno?"))
            {
                $.ajax({
                    type: "POST",
                    url: "/includes/acciones/alumnos/baja.php",
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

        function toggleEmail()
        {
            $("#prompt_email").fadeIn();
        }

        function addEmail()
        {
            var email = $("#emailVal").val();
            var tipo_email = $("#tipo_emailVal").val();

            if (email.length > 0)
            {
                $.ajax({
                    type: "POST",
                    url: "/includes/acciones/alumnos/agregar_email.php",
                    data: "id_alumno=" + id_alumno + "&email=" + email + "&tipo_email=" + tipo_email,
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
            }
        }

        function toggleTelefono()
        {
            $("#prompt_telefono").fadeIn();
        }

        function toggleTutor()
        {
            $("#prompt_tutor").fadeIn();
        }

        function addTelefono()
        {
            var telefono = $("#telefonoVal").val();
            var tipo_telefono = $("#tipo_telefonoVal").val();

            if (telefono.length > 0)
            {
                $.ajax({
                    type: "POST",
                    url: "/includes/acciones/alumnos/agregar_telefono.php",
                    data: "id_alumno=" + id_alumno + "&telefono=" + telefono + "&tipo_telefono=" + tipo_telefono,
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

        function addTutor()
        {
            if(confirm("¿Seguro que desea agregar el tutor?"))
            {
                var tipo_tutor  = $("#tipo_tutorVal").val();
                var nombre      = $("#nombreTutorVal").val();
                var calle       = $("#calleTutorVal").val();
                var numero      = $("#numeroTutorVal").val();
                var colonia     = $("#coloniaTutorVal").val();
                var CP          = $("#CPTutorVal").val();
                var telefonos   = $("#telefonosTutorVal").val();
                var celular     = $("#celularTutorVal").val();

                $.ajax({
                    type: "POST",
                    url: "/includes/acciones/alumnos/agregar_tutor.php",
                    data: "id_persona=" + id_alumno + "&tipo_tutor=" + tipo_tutor + "&nombre=" + nombre +
                        "&calle=" + calle + "&numero=" + numero + "&colonia=" + colonia
                        + "&CP=" + CP + "&telefonos=" + telefonos + "&celular=" + celular,
                    success: function (data)
                    {
                        if(data == 1) window.location.reload(true);
                        else alert("Código de error: " + data);
                    }
                });
            }
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
                    data: "id_persona=" + id_alumno + "&peso=" + peso + "&talla=" + talla + "&IMC=" + IMC,
                    success: function (data)
                    {
                        $('#nuevo_registro_nut').dialog('close');
                        document.location.reload(true);
                    }
                });
            }
        }
        </script>
    </body>
</html>