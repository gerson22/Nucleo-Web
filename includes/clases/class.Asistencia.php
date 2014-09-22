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
    public static function getListaDia($dia, $tipo_persona)
    {
        $hora_limite_llegada = '14:00:00'; // La hora a partir de la cual siempre se considera salida.

        $query = "SELECT docente, IF(entrada <= '$hora_limite_llegada', entrada, '') AS entrada,
            IF(entrada = salida, IF(entrada <= '$hora_limite_llegada', '', salida), salida) AS salida FROM (
            SELECT CONCAT(persona.nombres, ' ', persona.apellido_paterno) AS docente,
            CAST(MIN(fecha) AS TIME) AS entrada, CAST(MAX(fecha) AS TIME) AS salida FROM asistencia
            JOIN persona ON persona.id_persona = asistencia.id_persona
            WHERE CAST(fecha AS DATE) = CAST('{$dia}' AS DATE) AND tipo_persona = 2
            GROUP BY asistencia.id_persona) TB";
        $res = Database::select($query);
        return $res;
    }
} 