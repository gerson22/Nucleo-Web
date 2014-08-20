<?php
/**
 * Created by PhpStorm.
 * User: Gustavo
 * Date: 19/08/14
 * Time: 11:44 AM
 */

include_once("../../validar_admin.php");
include_once("../../clases/class_lib.php");
extract($_GET);
# grado
# ciclo

$grado = new Grado($grado);
$materias = $grado->getMateriasCiclo($ciclo);

$json = array();

if(is_array($materias))
{
    foreach($materias as $materia)
    {
        $materiaARR = array();

        $materiaARR[] = $materia['id_materia'];
        $materiaARR[] = $materia['materia'];
        $materiaARR[] = "<img src='/media/iconos/icon_close.gif'
            style='width: 15px' onclick='eliminarMateria(".$materia['id_materia'].")' />";

        $json[] = $materiaARR;
    }
    echo json_encode(array("aaData" => $json));
}