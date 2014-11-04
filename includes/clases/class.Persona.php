<?php
include_once("class.Database.php");

class Persona
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

    function __construct($id_persona)
    {
        $persona = Database::select("SELECT * FROM persona WHERE persona.id_persona = $id_persona LIMIT 1;");
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
        $this->foto                 = $persona['foto'];
        $this->sexo                 = $persona['sexo'];
    }

    function getEmails()
    {
        $query = "SELECT * FROM email
                JOIN tipo_email ON email.tipo_email = tipo_email.id_tipo_email 
                WHERE id_persona = $this->id_persona";
        return Database::select($query);
    }

    function getTelefonos()
    {
        $query = "SELECT * FROM telefono 
                JOIN tipo_telefono ON telefono.tipo_telefono = tipo_telefono.id_tipo_telefono 
                WHERE id_persona = $this->id_persona";
        return Database::select($query);
    } 

    function agregarEmail($email, $tipo_email)
    {
        $query = "INSERT INTO email SET id_persona = $this->id_persona, email = '$email', tipo_email = $tipo_email";
        return Database::insert($query);
    }

    function agregarTelefono($telefono, $tipo_telefono)
    {
        $query = "INSERT INTO telefono SET id_persona = $this->id_persona, telefono = $telefono, tipo_telefono = $tipo_telefono";
        return Database::insert($query);
    }

    function getDireccion()
    {
        $query = "SELECT calle, numero, colonia.nombre AS colonia, CP FROM persona_direccion
            JOIN colonia ON colonia.id_colonia = persona_direccion.id_colonia WHERE id_persona = $this->id_persona";
        $res = Database::select($query);
        return $res[0];
    }

    function setDireccion($calle, $numero, $colonia, $CP)
    {
        $query = "REPLACE INTO persona_direccion SET id_persona = $this->id_persona,
            numero = '$numero', calle = '$calle', id_colonia = $colonia, CP = '$CP'";

        return Database::insert($query);
    }

    function asignarFoto($foto)
    {
        $query = "UPDATE persona SET foto = '$foto' WHERE id_persona = $this->id_persona";
        return Database::update($query);
    }

    function cambiarPassword($password)
    {
        $query = "UPDATE persona SET password = '$password' WHERE id_persona = $this->id_persona";
        return Database::update($query);
    }

    function delete()
    {
        $query = "DELETE FROM persona WHERE id_persona = $this->id_persona";
        return Database::update($query);
    }

    function accesoModulo($id_modulo)
    {
        $query = "SELECT COUNT(*) AS permiso FROM permisos
                WHERE id_persona = $this->id_persona AND id_modulo = $id_modulo";
        $res = Database::select($query);
        if($res[0]['permiso'] == 1) return true;
        else return false;
    }

    function getModulosPermiso()
    {
        $query = "SELECT modulo.* FROM permisos
            JOIN modulo ON modulo.id_modulo = permisos.id_modulo
            WHERE id_persona = $this->id_persona";
        return Database::select($query);
    }

    function getPermisos()
    {
        $query = "SELECT modulo.id_modulo FROM permisos
            JOIN modulo ON modulo.id_modulo = permisos.id_modulo
            WHERE id_persona = $this->id_persona";
        $res = Database::select($query);
        $arr = array();
        foreach($res as $re) array_push($arr, $re['id_modulo']);
        return $arr;
    }

    function updateNombres($nombres)
    {
        $query = "UPDATE persona SET nombres = '$nombres' WHERE id_persona = $this->id_persona";
        return Database::update($query);
    }

    function updateApellidoPaterno($apellidoP)
    {
        $query = "UPDATE persona SET apellido_paterno = '$apellidoP' WHERE id_persona = $this->id_persona";
        return Database::update($query);
    }

    function updateApellidoMaterno($apellidoM)
    {
        $query = "UPDATE persona SET apellido_materno = '$apellidoM' WHERE id_persona = $this->id_persona";
        return Database::update($query);
    }

    # Métodos estáticos
    public static function login($matricula, $password)
    {
        $query = "SELECT * FROM persona WHERE matricula = '$matricula' AND password = '$password' LIMIT 1;";
        $personas = Database::select($query);
        print_r($personas);
        if(count($personas) > 0)
        {
            $id_persona = $personas[0]['id_persona'];
            $tipo_persona = $personas[0]['tipo_persona'];
            switch($tipo_persona)
            {
                case 1:
                    return new Alumno($id_persona);
                    break;
                case 2:
                    return new Maestro($id_persona);
                    break;
                case 3:
                    return new Administrador($id_persona);
                    break;
                default:
                    return FALSE;
                    break;
            }
        }
        else return FALSE;
    }

    public static function generarPassword($length = 8)
    {
        $chars = 'abcdefghijklmnopqrstuvwxyz23456789';
        $count = mb_strlen($chars);

        for ($i = 0, $result = ''; $i < $length; $i++)
        {
            $index = rand(0, $count - 1);
            $result .= mb_substr($chars, $index, 1);
        }

        return $result;
    }
}