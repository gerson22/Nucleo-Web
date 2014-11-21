<?php
include_once("class.Database.php");
include_once("class.CicloEscolar.php");

class Area
{
    public $id_area;
    public $area;
    public $prefijo;
    public $no_parciales;

    function __construct($id_area)
    {
        $area = Database::select("SELECT * FROM area WHERE id_area = $id_area");
        $area = $area[0];
        $this->id_area      = $area['id_area'];
        $this->area         = $area['area'];
        $this->prefijo      = $area['prefijo'];
        $this->no_parciales = $area['no_parciales'];
    }

    function getGrados()
    {
        $query = "SELECT * FROM grado WHERE id_area = $this->id_area";
        return Database::select($query);
    }

    function getMaterias()
    {
        $query = "SELECT * FROM materia WHERE id_area = $this->id_area";
        return Database::select($query);
    }

    function update()
    {
        $query = "UPDATE area SET area = '$this->area', prefijo = '$this->prefijo', no_parciales = $this->no_parciales
            WHERE id_area = $this->id_area";
        return Database::update($query);
    }

    # Métodos estáticos
    static function getLista()
    {
        return Database::select("SELECT * FROM area");
    }

    static function insert($nombre, $prefijo, $no_parciales)
    {
        $query = "INSERT INTO area SET area = '$nombre', prefijo = '$prefijo', no_parciales = $no_parciales";
        return Database::insert($query);
    }

    static function eliminar($id_nivel)
    {
        $query = "DELETE FROM area WHERE id_area = $id_nivel";
        return Database::update($query);
    }
}