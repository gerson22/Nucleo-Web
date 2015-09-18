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
        $query = 'SELECT recibo, DATE_FORMAT(fecha,"%d-%m-%Y") AS fecha, CONCAT(persona_pago.nombres, " ", persona_pago.apellido_paterno, " ", persona_pago.apellido_materno) AS persona,
            curp_persona.CURP AS CURP_persona,
             CONCAT(calle, " ", numero, " ", colonia.nombre, " ", CP) AS direccion, "Torreón" AS ciudad,
            cuentas_pago.monto AS cantidad, concepto, forma_pago, cuentas_cuenta.id_cuenta,
             CONCAT(persona.nombres, " ", persona.apellido_paterno, " ", persona.apellido_materno) AS alumno, curp_alumno.CURP AS CURP_alumno
            FROM cuentas_pago
            JOIN cuentas_cuenta ON cuentas_cuenta.id_cuenta = cuentas_pago.id_cuenta
            JOIN cuentas_forma_pago ON cuentas_forma_pago.id_forma_pago = cuentas_pago.id_forma_pago
            JOIN cuentas_concepto ON cuentas_concepto.id_concepto = cuentas_cuenta.id_concepto
            JOIN persona ON cuentas_cuenta.id_persona = persona.id_persona
            LEFT JOIN curp AS curp_alumno ON curp_alumno.id_persona = persona.id_persona
            LEFT JOIN persona_direccion ON persona_direccion.id_persona = persona.id_persona
            LEFT JOIN colonia ON colonia.id_colonia = persona_direccion.id_colonia
            LEFT JOIN persona AS persona_pago ON persona_pago.id_persona = cuentas_pago.id_persona
            LEFT JOIN curp AS curp_persona ON curp_persona.id_persona = persona_pago.id_persona
            WHERE cuentas_pago.id_pago = '.$pagoID.' LIMIT 1';
        return APIDatabase::select($query);
    }

    public static function setReciboLayout($layout)
    {  
        if(is_array($layout))
        {
            $query = "UPDATE cuentas_recibo_layout SET ";
            foreach($layout as $key => $value)
            {
                $query .= $key." = ".$value.", ";
            }
            $query = substr($query, 0, -2);
            $query .= " WHERE I1A >= 0";
            if(APIDatabase::update($query)) return 200; else return 500;
        }
        else
        {
            return 500;
        }   
    }

    public static function getReciboLayout()
    {
        $query = "SELECT * FROM cuentas_recibo_layout";
        return APIDatabase::select($query)[0];
    }
}