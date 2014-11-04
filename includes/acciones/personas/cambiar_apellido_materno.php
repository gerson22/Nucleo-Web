<?php
/**
 * Created by PhpStorm.
 * User: Yozki
 * Date: 11/3/14
 * Time: 6:06 PM
 */

include_once("../../validar_admin.php");
include_once("../../clases/class_lib.php");
extract($_POST);
# id_persona
# apellido

$persona = new Persona($id_persona);
$persona->updateApellidoMaterno($apellido);