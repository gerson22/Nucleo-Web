<?php
/**
 * Created by PhpStorm.
 * User: Yozki
 * Date: 9/30/14
 * Time: 3:01 PM
 */

class Colonia
{
    public $id_colonia;
    public $nombre;

    function __construct($id_colonia)
    {
        $colonia = Database::select("SELECT * FROM colonia WHERE id_colonia = $id_colonia LIMIT 1;");
        $colonia = $colonia[0];

        $this->id_colonia   = $colonia['id_colonia'];
        $this->nombre       = $colonia['nombre'];
    }

    // Métodos estáticos
    public static function insert($nombre)
    {
        $query = "INSERT INTO colonia VALUES(null, '$nombre')";
        return Database::insert($query);
    }

    public static function getColonias()
    {
        $query = "SELECT * FROM colonia";
        return Database::select($query);
    }

    public static function getDistribucion()
    {
        $query = "SELECT COUNT(persona.id_persona) AS alumnos, colonia.nombre FROM persona
            JOIN persona_direccion ON persona.id_persona = persona_direccion.id_persona
            JOIN colonia ON colonia.id_colonia = persona_direccion.id_colonia
            WHERE tipo_persona = 1
            GROUP BY persona_direccion.id_colonia";
        return Database::select($query);
    }
} 