<?php
include_once("class.Database.php");

class Materia
{
    public $id_materia;
    public $materia;

    function __construct($id_materia)
    {
        $query = "SELECT * FROM materia WHERE id_materia = $id_materia";
        $materia = Database::select($query);
        $materia = $materia[0];
        $this->id_materia   = $materia['id_materia'];
        $this->materia      = $materia['materia'];
    }

    function update()
    {
        $query = "UPDATE materia SET materia = '$this->materia' WHERE id_materia = $this->id_materia";
        return Database::update($query);
    }

    # Métodos estáticos
    static function getLista()
    {
        return Database::select("SELECT materia.*, area.area FROM materia
            JOIN area ON materia.id_area = area.id_area");
    }

    static function getListaParametro($parametro)
    {
        return Database::select("SELECT * FROM materia WHERE materia LIKE '%$parametro%'");
    }

    static function getListaArea($id_area)
    {
        return Database::select("SELECT * FROM materia WHERE id_area = $id_area");
    }

    static function insert($materia, $id_area)
    {
        return Database::insert("INSERT INTO materia SET materia = '$materia', id_area = $id_area");
    }
}