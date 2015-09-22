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
                return $this->getClases();
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