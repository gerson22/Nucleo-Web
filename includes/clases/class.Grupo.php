<?php
include_once("class.Database.php");

class Grupo
{
    public $id_grupo;
    public $grupo;
    public $id_grado;
    public $id_ciclo_escolar;

    function __construct($_id_grupo)
    {
        $query = "SELECT * FROM grupo WHERE id_grupo = $_id_grupo LIMIT 1";
        $grupo = Database::select($query);
        $grupo = $grupo[0];
        $this->id_grupo         = $grupo['id_grupo'];
        $this->grupo            = $grupo['grupo'];
        $this->id_grado         = $grupo['id_grado'];
        $this->id_ciclo_escolar = $grupo['id_ciclo_escolar'];
    }

    function getAlumnos()
    {
        $query = "SELECT id_alumno, matricula, apellido_paterno, apellido_materno, nombres, area, grado, grupo
            FROM alumno_grupo
            JOIN persona ON persona.id_persona = alumno_grupo.id_alumno
            JOIN grupo ON alumno_grupo.id_grupo = grupo.id_grupo
            JOIN grado ON grupo.id_grado = grado.id_grado
            JOIN area ON grado.id_area = area.id_area
            WHERE grupo.id_grupo = $this->id_grupo";
        return Database::select($query);
    }

    function esActivo()
    {
        $ciclo_actual = CicloEscolar::getActual();
        $query = "SELECT IF(id_ciclo_escolar = $ciclo_actual->id_ciclo_escolar, TRUE, FALSE) AS activo 
            FROM grupo WHERE id_grupo = $this->id_grupo";
        $res = Database::select($query);
        if($res[0]['activo'] == 1) return TRUE;
        else return FALSE;
    }

    function getClases()
    {
        $query = "SELECT id_clase, materia.id_materia, materia.materia,
            CONCAT(nombres, ' ', apellido_paterno, ' ', apellido_materno) AS nombre
            FROM clase 
            JOIN materia ON clase.id_materia = materia.id_materia
            JOIN persona ON clase.id_maestro = persona.id_persona
            WHERE id_grupo = $this->id_grupo";
        return Database::select($query);
    }

    function getArea()
    {
        $query = "SELECT area FROM grupo
            JOIN grado ON grupo.id_grado = grado.id_grado
            JOIN area ON area.id_area = grado.id_area
            WHERE grupo.id_grupo = $this->id_grupo";
        $res = Database::select($query);
        return $res[0]['area'];
    }

    function getClasesMaestro($id_maestro)
    {
        $query = "SELECT id_clase, materia.id_materia, materia.materia FROM clase
            JOIN materia ON clase.id_materia = materia.id_materia
            WHERE id_grupo = $this->id_grupo AND id_maestro = $id_maestro";
        return Database::select($query);
    }

    function getGrado()
    {
        $grado = new Grado($this->id_grado);
        return $grado->grado;
    }

    function getClase($id_materia)
    {
        $query = "SELECT id_clase FROM clase WHERE id_grupo = $this->id_grupo AND id_materia = $id_materia";
        $res = Database::select($query);
        return $res[0]['id_clase'];
    }

    function nuevaClase($id_materia, $id_docente)
    {
        $query = "INSERT INTO clase VALUES(null, $this->id_grupo, $id_materia, $id_docente);";
        return Database::insert($query);
    }

    # Métodos estáticos

    public static function getLista()
    {
        $query = "SELECT grupo.*, grado, area, COALESCE(alumnos, 0) AS alumnos
            FROM grupo JOIN grado ON grado.id_grado = grupo.id_grado
            LEFT JOIN area ON grado.id_area = area.id_area
            LEFT JOIN (SELECT id_grupo, COUNT(id_alumno) AS alumnos FROM alumno_grupo GROUP BY id_grupo) AS alumnos
            ON alumnos.id_grupo = grupo.id_grupo";
        return Database::select($query);
    }

    public static function getListaCiclo($id_ciclo_escolar)
    {
        $query = "SELECT grupo.*, grado, area, COALESCE(alumnos, 0) AS alumnos
            FROM grupo JOIN grado ON grado.id_grado = grupo.id_grado
            LEFT JOIN area ON grado.id_area = area.id_area
            LEFT JOIN (SELECT id_grupo, COUNT(id_alumno) AS alumnos FROM alumno_grupo GROUP BY id_grupo) AS alumnos
            ON alumnos.id_grupo = grupo.id_grupo WHERE id_ciclo_escolar = $id_ciclo_escolar";
        return Database::select($query);
    }

    public static function insert($grupo, $id_grado, $id_ciclo_escolar)
    {
        $query = "INSERT INTO grupo SET grupo = '$grupo', 
            id_grado = $id_grado, id_ciclo_escolar = $id_ciclo_escolar";
        return Database::insert($query);
    }
}