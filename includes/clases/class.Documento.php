<?php
/**
 * Created by PhpStorm.
 * User: Gustavo
 * Date: 7/05/14
 * Time: 04:51 PM
 */

class Documento
{
    public $id_documento;
    public $documento;

    function __construct($id_documento)
    {
        $documento = Database::select("SELECT * FROM documento WHERE id_documento = $id_documento");
        $documento = $documento[0];
        $this->id_documento = $documento['id_documento'];
        $this->documento    = $documento['documento'];
    }

    // Métodos estáticos
    static function getLista()
    {
        $query = "SELECT * FROM documento";
        return Database::select($query);
    }

    static function insert($nombre)
    {
        $query = "INSERT INTO documento VALUES(null, '$nombre')";
        return Database::insert($query);
    }
} 