<?php
/**
 * Created by PhpStorm.
 * User: Yozki
 * Date: 28/07/2015
 * Time: 07:56 PM
 */

class clasesControlador
{
    public function procesar($metodo, $verbo, $argumentos)
    {
        switch ($metodo)
        {
            case "GET":
                $persona = PersonaModelo::getPersonaPorCredenciales($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']);
                if($persona->tipo_persona == 1)
                {
                    $alumno = new AlumnoModelo($persona->id_persona);
                    return $alumno->getClases();
                }
                else if($persona->tipo_persona == 3)
                {
                    return $this->getClases();
                }
                break;
            default:
                break;
        }
        return 404;
    }

    public function getClases()
    {
        return ClaseModelo::getLista();
    }
}