<?php

class TutorModelo
{
    public $id_tutor;
    public $id_alumno;
    public $id_tipo_tutor;
    public $nombre;
    public $calle;
    public $numero;
    public $colonia;
    public $CP;
    public $telefonos;
    public $celular;
    public $ocupacion;
    public $lugar_trabajo;

    public function __construct()
    {
        
    }

    public static function getLista()
    {
        $query = 'SELECT * FROM persona_tutor';
        return APIDatabase::select($query);
    }
}