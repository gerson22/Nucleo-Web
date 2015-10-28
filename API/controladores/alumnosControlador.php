<?php
/**
 * Created by PhpStorm.
 * User: Yozki
 * Date: 28/07/2015
 * Time: 07:56 PM
 */

class alumnosControlador
{
    public function procesar($metodo, $verbo, $argumentos)
    {
        switch ($metodo) {
            case "GET":
                switch ($verbo) {
                    case "login":
                        return $this->alumnoLogin();
                        break;
                    case "tareas":
                        return $this->getTareas();
                    default:
                        return $this->getAlumnos();
                        break;
                }
                break;
            case "POST":
                switch ($verbo) {
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

    public function alumnoLogin()
    {
        $alumno = AlumnoModelo::getAlumnoPorCredenciales($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']);
        if($alumno->id_persona == null)
        {
            return 401;
            exit();
        }

        $respuesta = (array)$alumno;

        $respuesta['peso']      = $alumno->getPesoActual();
        $respuesta['talla']     = $alumno->getTallaActual();
        $respuesta['IMC']       = $alumno->getIMCActual();
        $respuesta['estado']    = $alumno->getStatus();
        /*$respuesta['beca']      = $alumno->getBecaActual();
        $respuesta['beca']      = "10";
        $respuesta['nivel']     = "X";
        $respuesta['grupo']     = "X";*/

        return $respuesta;
    }

    public function getTareas()
    {
        return AlumnoModelo::getTareas();
    }

    public function getAlumnos()
    {
        return AlumnoModelo::getLista();
    }
}