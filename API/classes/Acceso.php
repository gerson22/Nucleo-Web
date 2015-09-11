<?php
/**
 * Created by PhpStorm.
 * User: Gustavo
 * Date: 29/08/14
 * Time: 04:58 PM
 */

class Acceso
{
    /**
     * @return string
     * regresa "Administrador", "Docente", "Alumno" o NULL según las credenciales por Basic Auth
     */
    public static function tipoUsuario()
    {
        if(isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW']))
        {
            $res = APIDatabase::select("SELECT tipo_persona.tipo_persona FROM persona
              JOIN tipo_persona ON tipo_persona.id_tipo_persona = persona.tipo_persona
              WHERE matricula = '{$_SERVER['PHP_AUTH_USER']}' AND password = '{$_SERVER['PHP_AUTH_PW']}'");
            return $res[0]['tipo_persona'];
        }
        return FALSE;
    }
} 