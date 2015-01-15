<?php
/**
 * Created by PhpStorm.
 * User: Yozki
 * Date: 14/01/2015
 * Time: 05:44 PM
 */

include_once("../../../validar_admin.php");
include_once("../../../clases/class_lib.php");
extract($_GET);
# id_nivel
# id_concepto
# fecha_inicio
# fecha_fin

$pagos = Pago::getPagos($id_nivel, $id_concepto, $fecha_inicio, $fecha_fin);

$json = array();

if(is_array($pagos))
{
    foreach($pagos as $pago)
    {
        $temp = array();
        array_push($temp, $pago['fecha']);
        array_push($temp, $pago['concepto']);
        array_push($temp, "<input type='hidden' class='montoVal' value='".$pago['monto']." '/>$".$pago['monto']);
        array_push($temp, $pago['nombre']);
        array_push($temp, $pago['usuario']);
        array_push($json, $temp);
    }
}

echo json_encode(array("aaData" => $json));