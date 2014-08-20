<?php
/**
 * Created by PhpStorm.
 * User: Yozki
 * Date: 8/20/14
 * Time: 4:31 PM
 */

include_once("../../validar_admin.php");
include_once("../../clases/class_lib.php");
extract($_POST);
# grado

$grado = new Grado($grado);
$ciclo = CicloEscolar::getActual();

$grupos = $grado->getGruposCiclo($ciclo->id_ciclo_escolar);
echo json_encode($grupos);