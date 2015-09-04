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

    public function __construct($id_tutor)
    {
        $query = "SELECT * FROM persona_tutor WHERE id_tutor = $id_tutor";
        $res = APIDatabase::select($query);
        $this->id_tutor     = $res[0]['id_tutor'];
        $this->id_alumno    = $res[0]['id_alumno'];
        $this->nombre       = $res[0]['nombre'];
        $this->telefonos    = $res[0]['telefonos'];
        $this->celular      = $res[0]['celular'];
    }

    public static function getLista()
    {
        $query = 'SELECT * FROM persona_tutor';
        return APIDatabase::select($query);
    }

    public static function addTutor($id_alumno, $tipo, $nombre, $telefono, $celular, $email)
    {
        $query = "INSERT INTO persona_tutor SET id_alumno = $id_alumno, id_tipo_tutor = $tipo, nombre = '$nombre', telefonos = '$telefono', celular = '$celular'";
        $id_tutor = APIDatabase::insert($query);
        $tutor = new self($id_tutor);
        return $tutor;
    }
}