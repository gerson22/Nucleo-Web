<?php
/**
 * Created by PhpStorm.
 * User: Gustavo
 * Date: 11/09/14
 * Time: 03:07 PM
 */

class PersonaModelo
{
    public $id_persona;
    public $matricula;
    public $nombres;
    public $apellido_paterno;
    public $apellido_materno;
    public $password;
    public $tipo_persona;
    public $fecha_alta;
    public $fecha_baja;
    public $foto;
    public $sexo;

    public function __construct($id_persona)
    {
        $persona = APIDatabase::select("SELECT * FROM persona WHERE persona.id_persona = $id_persona LIMIT 1;");
        $persona = $persona[0];
        $this->id_persona           = $persona['id_persona'];
        $this->matricula            = $persona['matricula'];
        $this->apellido_paterno     = $persona['apellido_paterno'];
        $this->apellido_materno     = $persona['apellido_materno'];
        $this->nombres              = $persona['nombres'];
        $this->password             = $persona['password'];
        $this->tipo_persona         = $persona['tipo_persona'];
        $this->fecha_alta           = $persona['fecha_alta'];
        $this->fecha_baja           = $persona['fecha_baja'];
        $this->foto                 = "http://plataforma.colegiomeze.com/media/fotos/".$persona['foto'];
        $this->sexo                 = $persona['sexo'];
    }

    public function getPesoActual()
    {
        $query = "SELECT valor FROM nut_checkin WHERE id_persona = $this->id_persona AND id_concepto = 1 ORDER BY fecha DESC LIMIT 1";
        $res = APIDatabase::select($query);
        return $res[0]['valor'];
    }

    public function getTallaActual()
    {
        $query = "SELECT valor FROM nut_checkin WHERE id_persona = $this->id_persona AND id_concepto = 2 ORDER BY fecha DESC LIMIT 1";
        $res = APIDatabase::select($query);
        return $res[0]['valor'];
    }

    public function getIMCActual()
    {
        $query = "SELECT valor FROM nut_checkin WHERE id_persona = $this->id_persona AND id_concepto = 3 ORDER BY fecha DESC LIMIT 1";
        $res = APIDatabase::select($query);
        return $res[0]['valor'];
    }

    public function getStatus()
    {
        $query = "SELECT IF(fecha_baja <= NOW(), 'Inactivo', 'Activo') AS status FROM persona WHERE id_persona = $this->id_persona";
        $res = APIDatabase::select($query);
        return $res[0]['status'];
    }

    public static function registrarAsistencia($id_persona)
    {
        $query = "INSERT INTO asistencia VALUES($id_persona, NOW())";
        return APIDatabase::update($query);
    }

    public static function getPersonaPorCredenciales($matricula, $password)
    {
        $query = "SELECT * FROM persona WHERE matricula = '$matricula' AND password = '$password' LIMIT 1";
        $res = APIDatabase::select($query);
        $persona = new PersonaModelo($res[0]['id_persona']);
        return $persona;
    }
}