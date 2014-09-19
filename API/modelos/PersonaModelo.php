<?php
/**
 * Created by PhpStorm.
 * User: Gustavo
 * Date: 11/09/14
 * Time: 03:07 PM
 */

class PersonaModelo
{
    public function __construct()
    {

    }

    public static function registrarAsistencia($id_persona)
    {
        return \APIDatabase::update("INSERT INTO asistencia VALUES($id_persona, NOW())");
    }
}