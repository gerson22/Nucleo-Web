<?php
/**
 * Created by PhpStorm.
 * User: Yozki
 * Date: 11/19/14
 * Time: 6:15 PM
 */

$id_modulo = 48; // 48 - Configuración - Niveles
include_once("../../../validar_admin.php");
include_once("../../../clases/class_lib.php");
include_once("../../../validar_acceso.php");

$niveles = Area::getLista();

$json = array();

if(is_array($niveles))
{
    foreach($niveles as $nivel)
    {
        $temp = array();
        array_push($temp, $nivel['id_area']);
        array_push($temp, $nivel['area']);
        array_push($temp, $nivel['prefijo']);
        array_push($temp, $nivel['no_parciales']);
        array_push($temp, "<img onclick='modificar(".$nivel['id_area'].", \"".$nivel['area']."\", \"".$nivel['prefijo']."\", \"".$nivel['no_parciales']."\")' style='width:16px; height:16px' src='/media/iconos/icon_modify.png' alt='M' >");
        array_push($temp, "<img onclick='eliminar(".$nivel['id_area'].")' style='width:16px; height:16px' src='/media/iconos/close.png' alt='X' >");
        array_push($json, $temp);
    }
}

echo json_encode(array("aaData" => $json));