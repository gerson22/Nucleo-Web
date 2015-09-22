<?php
/**
 * Created by PhpStorm.
 * User: Yozki
 * Date: 28/07/2015
 * Time: 07:28 PM
 */

require_once("PersonaModelo.php");

class AlumnoModelo extends PersonaModelo
{
    public static function getAlumnoPorCredenciales($matricula, $password)
    {
        $query = "SELECT * FROM persona WHERE matricula = '$matricula' AND password = '$password' AND tipo_persona = 1 LIMIT 1";
        $res = APIDatabase::select($query);
        $usuario = new PersonaModelo($res[0]['id_persona']);
        return $usuario;
    }

    public function getBecaActual()
    {
        $ciclo_actual = CicloEscolar::getActual();
        $query = "SELECT beca.*, CONCAT(tipo_beca, '-', subtipo_beca) AS tipo FROM beca
            JOIN beca_subtipo ON beca_subtipo.id_subtipo_beca = beca.id_subtipo
            JOIN beca_tipo ON beca_tipo.id_tipo_beca = beca_subtipo.id_tipo_beca
            WHERE id_alumno = $this->id_persona AND id_ciclo_escolar = $ciclo_actual->id_ciclo_escolar";
            $res = Database::select($query);
        return $res[0];
    }

    public function getTareas()
    {
        $query = "SELECT * FROM tarea WHERE id_clase IN (SELECT clase.id_clase FROM alumno_grupo 
            JOIN clase ON clase.id_grupo = alumno_grupo.id_grupo
            WHERE id_alumno = $this->id_persona)";
        return APIDatabase::select($query);
    }
}