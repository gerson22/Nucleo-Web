<?php
include_once("class.Persona.php");
include_once("class.CicloEscolar.php");

class Maestro extends Persona
{
    function __construct($id_persona)
    {
        $persona = Database::select("SELECT * FROM persona WHERE persona.id_persona = $id_persona AND tipo_persona = 2 LIMIT 1;");
        $persona = $persona[0];
        $this->id_persona           = $persona['id_persona'];
        $this->matricula            = $persona['matricula'];
        $this->nombres              = $persona['nombres'];
        $this->apellido_paterno     = $persona['apellido_paterno'];
        $this->apellido_materno     = $persona['apellido_materno'];
        $this->password             = $persona['password'];
        $this->tipo_persona         = $persona['tipo_persona'];
        $this->fecha_alta           = $persona['fecha_alta'];
        $this->fecha_baja           = $persona['fecha_baja'];
        $this->foto                 = $persona['foto'];
    }

    function getTipoPersona()
    {
        return "maestro";
    }

    function getClases()
    {
        $id_ciclo = CicloEscolar::getActual()->id_ciclo_escolar;
        $query = "SELECT clase.id_clase, grado, grupo, materia.id_materia, materia, alumnos FROM clase 
            LEFT JOIN grupo ON clase.id_grupo = grupo.id_grupo
            LEFT JOIN grado ON grado.id_grado = grupo.id_grado
            LEFT JOIN materia ON materia.id_materia = clase.id_materia
            LEFT JOIN (SELECT id_grupo, COUNT(id_alumno) AS alumnos FROM alumno_grupo GROUP BY id_grupo) AS cont
            ON cont.id_grupo = clase.id_grupo
            WHERE id_maestro = $this->id_persona AND id_ciclo_escolar = $id_ciclo AND id_area >= 3";
        return Database::select($query);
    }

    function getGradosActuales()
    {
        $ciclo_actual = CicloEscolar::getActual();
        $query = "SELECT grado.id_grado, CONCAT(grado.grado, ' de ', area) AS grado FROM clase 
            JOIN grupo ON grupo.id_grupo = clase.id_grupo 
            JOIN grado ON grado.id_grado = grupo.id_grado
            JOIN area ON grado.id_area = area.id_area 
            WHERE id_maestro = $this->id_persona AND id_ciclo_escolar = $ciclo_actual->id_ciclo_escolar
            GROUP BY grado.id_grado";
        return Database::select($query);
    }

    function getGrupos()
    {
        $ciclo_actual = CicloEscolar::getActual();
        $query = "SELECT grupo.id_grupo, CONCAT(grado, ' ', grupo) AS grupo, area FROM clase
            JOIN grupo ON grupo.id_grupo = clase.id_grupo
            JOIN grado ON grado.id_grado = grupo.id_grado
            JOIN area ON area.id_area = grado.id_area
            WHERE id_ciclo_escolar = $ciclo_actual->id_ciclo_escolar AND id_maestro = $this->id_persona
            GROUP BY id_grupo";
        return Database::select($query);
    }

    function teachesClass($id_clase)
    {
        $rs = Database::select("SELECT * FROM clase WHERE id_clase = $id_clase");
        if($this->id_persona == $rs[0]['id_maestro']) return TRUE;
        else return FALSE; 
    }

    function getClasesCiclo()
    {
        $ciclo_actual = CicloEscolar::getActual();
        $query = "SELECT id_clase, grado, grupo, materia FROM clase 
            JOIN grupo ON clase.id_grupo = grupo.id_grupo 
            JOIN grado ON grupo.id_grado = grado.id_grado 
            JOIN materia ON clase.id_materia = materia.id_materia 
            WHERE id_maestro = $this->id_persona AND grupo.id_ciclo_escolar = $ciclo_actual->id_ciclo_escolar";
        return Database::select($query);
    }

    function darBaja()
    {
        return Database::update("UPDATE persona SET fecha_baja = NOW() WHERE id_persona = $this->id_persona");
    }

    function getEstado()
    {
        if(is_null($this->fecha_baja)) return TRUE;
        else return FALSE;    
    }

    function setDireccion($calle, $numero, $colonia, $CP)
    {
        $query = "REPLACE INTO persona_direccion SET id_persona = $this->id_persona, calle = '$calle',
          numero = '$numero', id_colonia = $colonia, CP = '$CP'";
        return Database::insert($query);
    }

    function getDireccion()
    {
        $query = "SELECT *, colonia.nombre AS colonia FROM persona_direccion
          JOIN colonia ON colonia.id_colonia = persona_direccion.id_colonia
          WHERE id_persona = $this->id_persona";
        $rs = Database::select($query);
        return $rs[0];
    }

    function setEscolaridad($titulo, $egresadode, $ano)
    {
        $query = "INSERT INTO persona_escolaridad SET id_persona = $this->id_persona, titulo = '$titulo',
          egresadode = '$egresadode', ano = '$ano'";
        return Database::insert($query);
    }

    function getEscolaridad()
    {
        $query = "SELECT *FROM persona_escolaridad WHERE id_persona = $this->id_persona";
        $rs = Database::select($query);
        return $rs[0];
    }

    function setNombres($nombres)
    {
        $query = "UPDATE persona SET nombres = '$nombres' WHERE id_persona = $this->id_persona";
        return Database::update($query);
    }

    function setApellidoPaterno($apellido_paterno)
    {
        $query = "UPDATE persona SET apellido_paterno = '$apellido_paterno' WHERE id_persona = $this->id_persona";
        return Database::update($query);
    }

    function setApellidoMaterno($apellido_materno)
    {
        $query = "UPDATE persona SET apellido_materno = '$apellido_materno' WHERE id_persona = $this->id_persona";
        return Database::update($query);
    }

    # Métodos estáticos

    static function getLista()
    {
        return Database::select("SELECT * FROM persona WHERE tipo_persona = 2");
    }

    static function getListaVigentes()
    {
        return Database::select("SELECT *, CONCAT(nombres, ' ', apellido_paterno, ' ', apellido_materno) AS nombre
            FROM persona WHERE tipo_persona = 2 AND (ISNULL(fecha_baja) OR fecha_baja > NOW())");
    }

    public static function insert($apellido_paterno, $apellido_materno, $nombres)
    {
        $password = parent::generarPassword(8);
        $query = "INSERT INTO persona
            (SELECT null, CONCAT('DOC', DATE_FORMAT(NOW(), '%y'), LPAD(CAST(COALESCE(MAX(SUBSTRING(matricula, 6, 3)), '0') + 1 AS CHAR(3)), 3, '0')),
            '$apellido_paterno', '$apellido_materno', '$nombres',
            2, '$password', NOW(), null, 'photo_NA.jpg'
            FROM persona
            WHERE tipo_persona = 2 AND SUBSTRING(matricula, 4, 2) = DATE_FORMAT(NOW(), '%y'))";
        return Database::insert($query);
    }
}