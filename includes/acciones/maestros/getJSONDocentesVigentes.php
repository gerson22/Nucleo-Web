<?php
/**
 * Created by PhpStorm.
 * User: Gustavo
 * Date: 21/08/14
 * Time: 05:33 PM
 */

include_once("../../validar_admin.php");
include_once("../../clases/class_lib.php");

$docentes = Maestro::getListaVigentes();
echo json_encode($docentes);