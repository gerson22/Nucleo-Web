<?php
$id_modulo = 10; // Becas - Nueva
include_once("../../validar_admin.php");
include_once("../../clases/class_lib.php");
include_once("../../validar_acceso.php");
extract($_POST);
# id_alumnoVal
# alumnoVal
# becaVal
# tipoVal
# subtipoVal
# cicloVal

$alumno = new Alumno($id_alumnoVal);
if($alumno->asignarBeca($becaVal, $subtipoVal, $cicloVal))
{
    header('Location: /admin/becas/lista.php');
}
else
{
    header('Location: /admin/becas/nueva.php?error=1');
}