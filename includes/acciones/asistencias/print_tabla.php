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

$asistencias = Asistencia::getListaDia($fecha, 2);

$json = array();

if(is_array($asistencias))
{
    foreach($asistencias as $asistencia)
    {
        $entrada = "";
        $salida = "";
        if($asistencia['entrada'] !== null && $asistencia['entrada'] !== "")
        {
            $entrada = date('h:i A', strtotime($asistencia['entrada']));
        }
        if($asistencia['salida'] !== null && $asistencia['salida'] !== "")
        {
            $salida = date('h:i A', strtotime($asistencia['salida']));
        }

        $temp = array();
        array_push($temp, $asistencia['docente']);
        array_push($temp, $entrada);
        array_push($temp, $salida);
        array_push($json, $temp);
    }
}

echo json_encode(array("aaData" => $json));