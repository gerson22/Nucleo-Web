<?php
/**
 * Created by PhpStorm.
 * User: Yozki
 * Date: 9/30/14
 * Time: 2:32 PM
 */

$id_modulo = 44; // 44 - Configuración - Clubs
include_once("../../../validar_admin.php");
include_once("../../../clases/class_lib.php");
include_once("../../../validar_acceso.php");

$clubs = Club::getClubs();

$json = array();

if(is_array($clubs))
{
    foreach($clubs as $club)
    {
        $temp = array();
        array_push($temp, $club['nombre']);
        array_push($json, $temp);
    }
}

echo json_encode(array("aaData" => $json));