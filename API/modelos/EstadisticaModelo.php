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