<?php
/**
 * Created by PhpStorm.
 * User: Gustavo
 * Date: 28/08/14
 * Time: 05:38 PM
 */

class personasControlador
{
    public function procesar($metodo, $verbo, $argumentos)
    {
        switch($metodo)
        {
            case "GET":
                switch ($verbo) {
                    case "login":
                        return $this->personaLogin();
                        break;
                    default:
                        return 404;
                        break;
                }
                break;
            case "POST":
                switch($verbo)
                {
                    case "asistencia":
                        # API/personas/asistencia || {id_persona}
                        return $this->registrarAsistencia($_POST['id_persona']);
                        break;
                    default:
                        return 404;
                        break;
                }
                break;
            default:
                break;
        }
        return 404;
    }

    /** Autorización: Administrador */
    protected function registrarAsistencia($id_persona)
    {
        if(Acceso::tipoUsuario() == "Administrador")
        {
            if(PersonaModelo::registrarAsistencia($id_persona)) return ['Success'];
            return 500;
        }
        return 401;
    }

    public function personaLogin()
    {
        $persona = PersonaModelo::getPersonaPorCredenciales($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']);

        if($persona->id_persona != null) return $persona;
        else return 401;
    }
}