<?php
/**
 * Created by PhpStorm.
 * User: Yozki
 * Date: 9/30/14
 * Time: 6:24 PM
 */

$id_modulo = 35; // Maestros - Modificar
include_once("../../validar_admin.php");
include_once("../../clases/class_lib.php");
include_once("../../validar_acceso.php");
extract($_POST);
# id_maestro
# titulo
# egresadoDe
# ano

$maestro = new Maestro($id_maestro);
$maestro->setEscolaridad($titulo, $egresadoDe, $ano);