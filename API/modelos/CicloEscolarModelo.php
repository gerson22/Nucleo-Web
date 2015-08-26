<?php
/**
 * Created by PhpStorm.
 * User: Yozki
 * Date: 28/07/2015
 * Time: 07:30 PM
 */

class CicloEscolarModelo
{
    public $id_ciclo_escolar;
    public $fecha_inicio;
    public $fecha_fin;

    #external fields
    public $ciclo;

    function __construct($id_ciclo_escolar)
    {
        $ciclo_escolar = APIDatabase::select("SELECT *, CONCAT(YEAR(fecha_inicio), ' - ', YEAR(fecha_fin)) AS ciclo
            FROM ciclo_escolar WHERE id_ciclo_escolar = $id_ciclo_escolar LIMIT 1");
        $ciclo_escolar = $ciclo_escolar[0];
        $this->id_ciclo_escolar = $ciclo_escolar['id_ciclo_escolar'];
        $this->fecha_inicio     = $ciclo_escolar['fecha_inicio'];
        $this->fecha_fin        = $ciclo_escolar['fecha_fin'];
        $this->ciclo            = $ciclo_escolar['ciclo'];
    }

    static function getActual()
    {
        $ciclo_actual = APIDatabase::select("SELECT * FROM ciclo_escolar WHERE NOW() BETWEEN fecha_inicio AND fecha_fin LIMIT 1");
        return new static($ciclo_actual[0]['id_ciclo_escolar']);
    }

    static function getLista($orden = "ascendente")
    {
        return APIDatabase::select("SELECT id_ciclo_escolar, YEAR(fecha_inicio) AS ciclo_escolar, fecha_inicio, fecha_fin
            FROM ciclo_escolar ORDER BY fecha_inicio ASC");
    }

    function getCountAlumnosInscritos()
    {
        $alumnos = self::getAlumnosInscritos();
        if($alumnos) return count($alumnos);
        else return 0;
    }

    function getAlumnosInscritos()
    {
        $query = "SELECT persona.*, grado.grado, grupo.grupo, area FROM persona
            JOIN alumno_grupo ON alumno_grupo.id_alumno = persona.id_persona
            JOIN grupo ON grupo.id_grupo = alumno_grupo.id_grupo 
            JOIN grado ON grado.id_grado = grupo.id_grado
            JOIN area ON area.id_area = grado.id_area
            WHERE tipo_persona = 1 AND grupo.id_ciclo_escolar = $this->id_ciclo_escolar";
        return APIDatabase::select($query);
    }
}