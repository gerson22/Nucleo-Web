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

    # Métodos estáticos
    static function getLista()
    {
        return Database::select("SELECT * FROM area");
    }
}