<?php

class Administrador extends Persona
{
    function getTipoPersona()
    {
        return "administrador";
    }

    private function clearPermisos()
    {
        $query = "DELETE FROM permisos WHERE id_persona = $this->id_persona";
        return Database::update($query);
    }

    function asignarPermisos($permisos)
    {
        self::clearPermisos();
        if(is_array($permisos))
        {
            foreach($permisos as $permiso)
            {
                $query = "INSERT INTO permisos VALUES($this->id_persona, $permiso)";
                Database::insert($query);
            }
        }
        return true;
    }

    # Método estáticos

    static function getLista()
    {
        return Database::select("SELECT * FROM persona WHERE tipo_persona = 3");
    }

    public static function insert($apellido_paterno, $apellido_materno, $nombres, $sexo = 'N/A')
    {
        $password = parent::generarPassword(8);
        $query = "INSERT INTO persona 
            (SELECT null, CONCAT('ADM', DATE_FORMAT(NOW(), '%y'), LPAD(CAST(COALESCE(MAX(SUBSTRING(matricula, 6, 3)), '0') + 1 AS CHAR(3)), 3, '0')),
            '$apellido_paterno', '$apellido_materno', '$nombres',
            3, '$password', NOW(), null, 'photo_NA.jpg', '$sexo' FROM persona
            WHERE tipo_persona = 3 AND SUBSTRING(matricula, 4, 2) = DATE_FORMAT(NOW(), '%y'))";
        return Database::insert($query);
    }
}