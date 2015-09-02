<?php
/**
 * Created by PhpStorm.
 * User: Yozki
 * Date: 20/07/2015
 * Time: 08:42 PM
 */

class EstadisticaModelo
{
    public function __construct()
    {

    }

    public static function getAlumnosDistribucionArea()
    {
        $query = "SELECT TB1.alumnos AS A1, TB2.alumnos AS A2,
            TB3.alumnos AS A3, TB4.alumnos AS A4, TB5.alumnos AS A5 FROM
            (SELECT COALESCE(id_area, 0) AS id_area, COALESCE(SUM(alumnos), 0) AS alumnos
            FROM (SELECT id_grupo, COUNT(*) AS alumnos FROM alumno_grupo GROUP BY id_grupo) AS cant_alumnos
            JOIN grupo ON grupo.id_grupo = cant_alumnos.id_grupo
            JOIN grado ON grado.id_grado = grupo.id_grado
            WHERE id_area = 1) TB1
            JOIN (SELECT COALESCE(id_area, 0) AS id_area, COALESCE(SUM(alumnos), 0) AS alumnos
            FROM (SELECT id_grupo, COUNT(*) AS alumnos FROM alumno_grupo GROUP BY id_grupo) AS cant_alumnos
            JOIN grupo ON grupo.id_grupo = cant_alumnos.id_grupo
            JOIN grado ON grado.id_grado = grupo.id_grado
            WHERE id_area = 2) TB2
            JOIN (SELECT COALESCE(id_area, 0) AS id_area, COALESCE(SUM(alumnos), 0) AS alumnos
            FROM (SELECT id_grupo, COUNT(*) AS alumnos FROM alumno_grupo GROUP BY id_grupo) AS cant_alumnos
            JOIN grupo ON grupo.id_grupo = cant_alumnos.id_grupo
            JOIN grado ON grado.id_grado = grupo.id_grado
            WHERE id_area = 3) TB3
            JOIN (SELECT COALESCE(id_area, 0) AS id_area, COALESCE(SUM(alumnos), 0) AS alumnos
            FROM (SELECT id_grupo, COUNT(*) AS alumnos FROM alumno_grupo GROUP BY id_grupo) AS cant_alumnos
            JOIN grupo ON grupo.id_grupo = cant_alumnos.id_grupo
            JOIN grado ON grado.id_grado = grupo.id_grado
            WHERE id_area = 4) TB4
            JOIN (SELECT COALESCE(id_area, 0) AS id_area, COALESCE(SUM(alumnos), 0) AS alumnos
            FROM (SELECT id_grupo, COUNT(*) AS alumnos FROM alumno_grupo GROUP BY id_grupo) AS cant_alumnos
            JOIN grupo ON grupo.id_grupo = cant_alumnos.id_grupo
            JOIN grado ON grado.id_grado = grupo.id_grado
            WHERE id_area = 5) TB5";
        return APIDatabase::select($query);
    }

    public static function getDocentesDistribucionArea()
    {
        $query = "SELECT * FROM
            (SELECT COUNT(A1.docentes) AS A1
            FROM
            (SELECT COUNT(*) AS docentes FROM persona
            LEFT JOIN clase ON clase.id_maestro = persona.id_persona
            LEFT JOIN grupo ON grupo.id_grupo = clase.id_grupo
            LEFT JOIN grado ON grado.id_grado = grupo.id_grado
            WHERE tipo_persona = 2 AND id_area = 1
            GROUP BY id_persona) AS A1) A1
            JOIN 
            (SELECT COUNT(A2.docentes) AS A2
            FROM
            (SELECT COUNT(*) AS docentes FROM persona
            LEFT JOIN clase ON clase.id_maestro = persona.id_persona
            LEFT JOIN grupo ON grupo.id_grupo = clase.id_grupo
            LEFT JOIN grado ON grado.id_grado = grupo.id_grado
            WHERE tipo_persona = 2 AND id_area = 2 
            GROUP BY id_persona) AS A2) A2
            JOIN 
            (SELECT COUNT(A3.docentes) AS A3
            FROM
            (SELECT COUNT(*) AS docentes FROM persona
            LEFT JOIN clase ON clase.id_maestro = persona.id_persona
            LEFT JOIN grupo ON grupo.id_grupo = clase.id_grupo
            LEFT JOIN grado ON grado.id_grado = grupo.id_grado
            WHERE tipo_persona = 2 AND id_area = 3
            GROUP BY id_persona) AS A3) A3
            JOIN 
            (SELECT COUNT(A4.docentes) AS A4
            FROM
            (SELECT COUNT(*) AS docentes FROM persona
            LEFT JOIN clase ON clase.id_maestro = persona.id_persona
            LEFT JOIN grupo ON grupo.id_grupo = clase.id_grupo
            LEFT JOIN grado ON grado.id_grado = grupo.id_grado
            WHERE tipo_persona = 2 AND id_area = 4 
            GROUP BY id_persona) AS A4) A4";
        return APIDatabase::select($query);
    }

    public static function getAlumnosInscritosCiclo()
    {
        $ciclos = CicloEscolarModelo::getLista();

        if(is_array($ciclos))
        {
            $json = array();
            foreach($ciclos as $ciclo)
            {
                $ciclo_escolar = new CicloEscolarModelo($ciclo['id_ciclo_escolar']);
                array_push($json, array("ciclo" => $ciclo['ciclo_escolar'], "alumnos" => $ciclo_escolar->getCountAlumnosInscritos()));
                array_push($json, array("ciclo" => "2020", "alumnos" => $ciclo_escolar->getCountAlumnosInscritos()));
            }
            return $json;
        }
        else
        {
            return array('');
        }
    }

    public static function getAlumnosInscripcionesCiclo()
    {
        $ciclos = CicloEscolarModelo::getLista();

        if(is_array($ciclos))
        {
            $json = array();
            foreach($ciclos as $ciclo)
            {
                $ciclo_escolar = new CicloEscolarModelo($ciclo['id_ciclo_escolar']);
                array_push($json, array("ciclo" => $ciclo['ciclo_escolar'], "alumnos" => $ciclo_escolar->getCountAlumnosInscritos()));
            }
            return $json;
        }
        else
        {
            return array('');
        }
    }
}