<?php
/**
 * Created by PhpStorm.
 * User: Yozki
 * Date: 20/07/2015
 * Time: 08:32 PM
 */

class pagosControlador
{
    public function procesar($metodo, $verbo, $argumentos)
    {
        switch($metodo)
        {
            case "GET":
                switch($verbo)
                {
                    case "":
                        return PagoModelo::getPago($argumentos[0])[0];
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
                    case "recibo":
                        if($argumentos[0] == "layout")
                        {
                            print_r($_POST);
                        }
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
}