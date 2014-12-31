<?php
/**
 * Created by PhpStorm.
 * User: Yozki
 * Date: 12/29/14
 * Time: 6:17 PM
 */

$id_modulo = 49; // Cuentas - Dinámicas
include_once("../../../../includes/validar_admin.php");
include_once("../../../../includes/clases/class_lib.php");
include_once("../../../../includes/validar_acceso.php");
extract($_GET);

$conceptos = Cuenta::getConceptos();
$json = array();

if(is_array($conceptos))
{
    foreach($conceptos as $concepto)
    {
        $temp = array();
        array_push($temp, $concepto['concepto']);
        $link = '<a href="/admin/cuentas/conceptos/detalles.php?id_concepto='.$concepto['id_concepto'].'" >
            <img src="/media/iconos/icon_profile.png" alt="X" /></a>';
        array_push($temp, $link);
        array_push($json, $temp);
    }
}

echo json_encode(array("aaData" => $json));