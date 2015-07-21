<?php
/**
 * Created by PhpStorm.
 * User: Yozki
 * Date: 20/07/2015
 * Time: 08:42 PM
 */

class PagoModelo
{
    public function __construct()
    {

    }

    public static function getPago($pagoID)
    {
        $query = "SELECT * FROM cuentas_pago WHERE id_pago = $pagoID LIMIT 1";
        return APIDatabase::select($query);
    }
}