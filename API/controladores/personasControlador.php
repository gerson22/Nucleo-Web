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
        if($metodo == "GET")
        {
            switch($verbo)
            {
                case "":
                    return $this->registrarAsistencia($argumentos[0]);      /** API/personas/{id_persona}           */
                    break;
                default:
                    return 404;
                    break;
            }
        }
        return 404;
    }

    /** Autorización: Todos */
    protected function registrarAsistencia($id_persona)
    {
        PersonaModelo::registrarAsistencia($id_persona);
        return [];
    }
}