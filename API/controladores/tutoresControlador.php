<?php
/**
 * Created by PhpStorm.
 * User: Yozki
 * Date: 28/07/2015
 * Time: 07:56 PM
 */

class tutoresControlador
{
    public function procesar($metodo, $verbo, $argumentos)
    {
        switch ($metodo)
        {
            case "GET":
                return $this->getTutores();
                break;
            default:
                break;
        }
        return 404;
    }

    public function getTutores()
    {
        return TutorModelo::getLista();
    }
}