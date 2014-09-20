<?php
/**
 * Created by PhpStorm.
 * User: Gustavo
 * Date: 19/09/14
 * Time: 06:22 PM
 */

class Asistencia
{
    # Métodos estáticos
    public static function getListaDia($dia)
    {
        $query = "SELECT docente, entrada, IF(entrada = salida, '', salida) AS salida FROM (
            SELECT CONCAT(persona.nombres, ' ', persona.apellido_paterno) AS docente,
            CAST(MIN(fecha) AS TIME) AS entrada, CAST(MAX(fecha) AS TIME) AS salida FROM asistencia
            JOIN persona ON persona.id_persona = asistencia.id_persona
            WHERE CAST(fecha AS DATE) = CAST('$dia' AS DATE) AND tipo_persona = 2
            GROUP BY asistencia.id_persona) TB";
        //echo $query;
        $res = Database::select($query);
        return $res;
    }
} 