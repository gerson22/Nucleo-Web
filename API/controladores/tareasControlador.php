<?php
/**
 * Created by PhpStorm.
 * User: Yozki
 * Date: 20/07/2015
 * Time: 08:32 PM
 */

class tareasControlador
{
    public function procesar($metodo, $verbo, $argumentos)
    {
        switch($metodo)
        {
            case "GET":
                $persona = PersonaModelo::getPersonaPorCredenciales($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']);
                if($persona->tipo_persona == 1)
                {
                    $alumno = new AlumnoModelo($persona->id_persona);
                    return $alumno->getTareas();
                }
                else if($persona->tipo_persona == 3)
                {
                    return TareaModelo::getLista();
                }
                return 401;
            case "POST":
                return $this->asignarTarea();
                break;
            default:
                break;
        }
        return 404;
    }

    public function asignarTarea()
    {
        TareaModelo::addTarea($_POST['id_clase'], $_POST['descripcion'], $_POST['fecha_entrega']);
    }
}