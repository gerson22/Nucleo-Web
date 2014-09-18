<?php
include_once("class.Database.php");

class Clase
{
    public $id_clase;
    public $id_grupo;
    public $id_materia;
    public $id_maestro;

    function __construct($_id_clase)
    {
        $clase = Database::select("SELECT * FROM clase WHERE id_clase = $_id_clase LIMIT 1");
        $clase = $clase[0];
        $this->id_clase     = $clase['id_clase'];
        $this->id_grupo     = $clase['id_grupo'];
        $this->id_materia   = $clase['id_materia'];
        $this->id_maestro   = $clase['id_maestro'];
    }

    function update()
    {
        $query = "UPDATE clase SET id_grupo = $this->id_grupo, id_materia = $this->id_materia,
            id_maestro = $this->id_maestro
            WHERE id_clase = $this->id_clase";
        return Database::update($query);
    }

    function getCalificaciones($parcial)
    {
        $query = "SELECT * FROM calificacion WHERE id_clase = $this->id_clase AND parcial = $parcial";
        return Database::select($query);
    }

    # Métodos estáticos
    public static function getLista()
    {
        return Database::select("SELECT id_clase, grado, grupo.grupo, materia.materia,
            CONCAT(apellido_paterno, ' ', apellido_materno, ' ', nombres) AS maestro
            FROM clase
            JOIN grupo ON grupo.id_grupo = clase.id_grupo
			JOIN grado ON grado.id_grado = grupo.id_grado
            JOIN materia ON materia.id_materia = clase.id_materia
            JOIN persona ON persona.id_persona = clase.id_maestro");
    }

    public static function insert($id_grupo, $id_materia, $id_maestro)
    {
        $query = "INSERT INTO clase SET id_grupo = $id_grupo, id_materia = $id_materia, id_maestro = $id_maestro";
        return Database::insert($query);
    }

    public static function getClase($id_grupo, $id_materia)
    {
        $query = "SELECT id_clase FROM clase WHERE id_grupo = $id_grupo AND id_materia = $id_materia";
        $clase = Database::select($query);
        return $clase[0]['id_clase'];
    }

    public static function eliminar($id_clase)
    {
        $query = "DELETE FROM clase WHERE id_clase = $id_clase";
        return Database::update($query);
    }
}