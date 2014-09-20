<?php
/**
 * Created by PhpStorm.
 * User: Gustavo
 * Date: 19/09/14
 * Time: 06:05 PM
 */

include_once("../../clases/class_lib.php");
extract($_GET);
# fecha

$asistencias = Asistencia::getListaDia($fecha);

$json = array();

if(is_array($asistencias))
{
    foreach($asistencias as $asistencia)
    {
        $temp = array();
        array_push($temp, $asistencia['docente']);
        array_push($temp, $asistencia['entrada']);
        array_push($temp, $asistencia['salida']);
        array_push($json, $temp);
    }
}

echo json_encode(array("aaData" => $json));