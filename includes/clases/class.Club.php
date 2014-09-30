<?php
/**
 * Created by PhpStorm.
 * User: Yozki
 * Date: 9/30/14
 * Time: 2:34 PM
 */

class Club
{
    public $id_club;
    public $nombre;

    function __construct($id_club)
    {
        $club = Database::select("SELECT * FROM club WHERE id_club = $id_club LIMIT 1;");
        $club = $club[0];

        $this->id_club = $club['id_club'];
        $this->nombre  = $club['nombre'];
    }

    // Métodos estáticos
    public static function insert($nombre)
    {
        $query = "INSERT INTO club VALUES(null, '$nombre')";
        return Database::insert($query);
    }

    public static function getClubs()
    {
        $query = "SELECT * FROM club";
        return Database::select($query);
    }
} 