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
     * regresa "Administrador", "Cliente" o NULL según el tipo de usuario que envió las credenciales por Basic Auth
     */
    public static function tipoUsuario()
    {
        if(isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW']))
        {
            $res = Database::select("SELECT tipoUsuario FROM usuario
                JOIN tipo_usuario ON tipo_usuario.tipoUsuarioID = usuario.tipoUsuarioID
                WHERE usuarioID = {$_SERVER['PHP_AUTH_USER']} AND password = '{$_SERVER['PHP_AUTH_PW']}'");
            return $res[0]['tipoUsuario'];
        }
        return FALSE;
    }
} 