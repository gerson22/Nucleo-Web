<?php

class ClaseModelo
{
    public $id_clase;
    public $id_grupo;
    public $id_materia;
    public $id_maestro;

    #external fields
    public $descripcion; // Grado - Grupo - Materia

    public function __construct($id_clase)
    {
        $query = "SELECT * FROM clase WHERE id_clase = $id_clase";
        $res = APIDatabase::select($query);
        $this->id_clase     = $res[0]['id_clase'];
        $this->id_grupo     = $res[0]['id_grupo'];
        $this->id_maestro   = $res[0]['id_maestro'];
        $this->id_maestro   = $res[0]['id_maestro'];
    }

    public static function getLista()
    {
        $query = 'SELECT clase.*, CONCAT(grado.grado, "-", grupo.grupo, " ", materia.materia) AS descripcion FROM clase
            JOIN grupo ON clase.id_grupo = grupo.id_grupo
            JOIN grado ON grupo.id_grado = grado.id_grado
            JOIN materia ON clase.id_materia = materia.id_materia';
        return APIDatabase::select($query);
    }
}