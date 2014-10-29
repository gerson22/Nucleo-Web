<?php
/**
 * Created by PhpStorm.
 * User: Yozki
 * Date: 10/29/14
 * Time: 4:04 PM
 */

include_once("../../validar_admin.php");
include_once("../../clases/class_lib.php");
extract($_POST);
# id_persona
# imagen

$persona = new Persona($id_persona);
echo $persona->asignarFoto($imagen);