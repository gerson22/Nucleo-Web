<?php
/**
 * Created by PhpStorm.
 * User: Yozki
 * Date: 1/5/15
 * Time: 8:38 PM
 */

$id_modulo = 17; // Cuentas - Pagos
include_once("../../validar_admin.php");
include_once("../../clases/class_lib.php");
include_once("../../validar_acceso.php");
extract($_POST);
# id_alumno
# pagos (JSON) {id_concepto, abono}
# id_ciclo_escolar

$pagos = json_decode($pagos);
if(is_array($pagos))
{
    foreach($pagos as $pago)
    {
        Cuenta::insertarPago($id_alumno, $pago->id_concepto, $pago->abono, $id_ciclo_escolar);
    }
}
echo 1;