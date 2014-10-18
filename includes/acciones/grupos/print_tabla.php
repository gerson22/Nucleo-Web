<?php
include_once("../../clases/class_lib.php");
extract($_GET);
# id_ciclo_escolar

$grupos = Grupo::getListaCiclo($id_ciclo_escolar);

$json = array();

if(is_array($grupos))
{
    foreach($grupos as $grupo)
    {
        $temp = array();
        array_push($temp, $grupo['grupo']);
        array_push($temp, $grupo['grado']);
        array_push($temp, $grupo['area']);
        array_push($temp, $grupo['alumnos']);
        $link = '<a href="grupo.php?id_grupo='.$grupo["id_grupo"].'"><img alt="G" src="../../media/iconos/icon_stats.png"></a>';
        array_push($temp, $link);
        array_push($json, $temp);
    }
}

echo json_encode(array("aaData" => $json));