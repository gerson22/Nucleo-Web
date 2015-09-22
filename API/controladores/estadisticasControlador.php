<?php
/**
 * Created by PhpStorm.
 * User: Yozki
 * Date: 20/07/2015
 * Time: 08:32 PM
 */

class estadisticasControlador
{
    public function procesar($metodo, $verbo, $argumentos)
    {
        switch($metodo)
        {
            case "GET":
                switch($verbo)
                {
                    case "alumnos":
                        $json = array();
                        $json['distribucion']   = EstadisticaModelo::getAlumnosDistribucionArea()[0];
                        $json['inscritos']      = EstadisticaModelo::getAlumnosInscritosCiclo()[0];
                        $json['inscripciones']  = EstadisticaModelo::getAlumnosInscripcionesCiclo()[0];
                        return $json;
                        break;
                    case "docentes":
                        $json = array();
                        $json['distribucion']   = EstadisticaModelo::getDocentesDistribucionArea()[0];
                        return $json;
                        break;
                    case "recibo":
                        if($argumentos[0] == "layout")
                        {
                            return $this->getLayout();
                        }
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
                            return $this->setLayout();
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
}