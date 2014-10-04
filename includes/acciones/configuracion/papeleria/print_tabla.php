<?php
/**
 * Created by PhpStorm.
 * User: Yozki
 * Date: 10/4/14
 * Time: 1:08 PM
 */

$id_modulo = 46; // 46 - Configuración - Papeleria
include_once("../../../validar_admin.php");
include_once("../../../clases/class_lib.php");
include_once("../../../validar_acceso.php");

$documentos = Documento::getLista();

$json = array();

if(is_array($documentos))
{
    foreach($documentos as $documento)
    {
        $temp = array();
        array_push($temp, $documento['documento']);
        array_push($json, $temp);
    }
}

echo json_encode(array("aaData" => $json));