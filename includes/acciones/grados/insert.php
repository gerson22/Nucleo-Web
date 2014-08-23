<?php
include_once("../../validar_admin.php");
include_once("../../clases/class_lib.php");
extract($_POST);
# grado
# area
# materias[]

$materias = json_decode($materias);
$ciclo_actual = CicloEscolar::getActual();

if(!isset($grado) || !isset($area))
{
    return 0;
    exit();
}
else
{
    if(Grado::insert($ciclo_actual->id_ciclo_escolar, $area, $grado, $materias))
    {
        return 1;
        exit();
    }
}