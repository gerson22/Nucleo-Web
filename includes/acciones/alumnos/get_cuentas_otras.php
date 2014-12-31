<?php
/**
 * Created by PhpStorm.
 * User: Yozki
 * Date: 12/30/14
 * Time: 10:13 PM
 */

include_once("../../validar_admin.php");
include_once("../../clases/class_lib.php");
extract($_POST);
# id_alumno
# id_ciclo_escolar

$alumno = new Alumno($id_alumno);
$cuentas = $alumno->getCuentasOtras($id_ciclo_escolar);

echo json_encode($cuentas);