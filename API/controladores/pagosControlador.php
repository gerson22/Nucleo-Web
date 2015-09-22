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

    protected function getLayout()
    {
        return PagoModelo::getReciboLayout();
    }

    protected function setLayout()
    {
        return PagoModelo::setReciboLayout($_POST);
    }
}