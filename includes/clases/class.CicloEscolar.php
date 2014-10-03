<?php
include_once("class.Database.php");

class CicloEscolar
{
    public $id_ciclo_escolar;
    public $ciclo; // No está en la base de datos, se genera al iniciar un objeto contatenando las fechas de inicio y fin
    public $fecha_inicio;
    public $fecha_fin;

    function __construct($id_ciclo_escolar)
    {
        $ciclo_escolar = Database::select("SELECT *, CONCAT(YEAR(fecha_inicio), ' - ', YEAR(fecha_fin)) AS ciclo
            FROM ciclo_escolar WHERE id_ciclo_escolar = $id_ciclo_escolar LIMIT 1");
        $ciclo_escolar = $ciclo_escolar[0];
        $this->id_ciclo_escolar = $ciclo_escolar['id_ciclo_escolar'];
        $this->ciclo            = $ciclo_escolar['ciclo'];
        $this->fecha_inicio     = $ciclo_escolar['fecha_inicio'];
        $this->fecha_fin        = $ciclo_escolar['fecha_fin'];
    }

    function getStatus()
    {
        if(isset($this->fecha_fin)) return FALSE;
        else return TRUE;
    }

    function getAlumnosInscritos()
    {
        $query = "SELECT persona.*, grado.grado, grupo.grupo, area FROM persona
            JOIN alumno_grupo ON alumno_grupo.id_alumno = persona.id_persona
            JOIN grupo ON grupo.id_grupo = alumno_grupo.id_grupo 
            JOIN grado ON grado.id_grado = grupo.id_grado
            JOIN area ON area.id_area = grado.id_area
            WHERE tipo_persona = 1 AND grupo.id_ciclo_escolar = $this->id_ciclo_escolar";
        return Database::select($query);
    }

    function getAlumnosInscritosPagados()
    {
        $query = "SELECT persona.id_persona, matricula, apellido_paterno, apellido_materno, nombres, area, grado, grupo
            FROM cuentas_cuenta
            JOIN persona ON persona.id_persona = cuentas_cuenta.id_persona
            JOIN alumno_grupo ON alumno_grupo.id_alumno = persona.id_persona
            JOIN grupo ON grupo.id_grupo = alumno_grupo.id_grupo
            JOIN grado ON grado.id_grado = grupo.id_grado
            JOIN area ON area.id_area = grado.id_area
            WHERE cuentas_cuenta.id_ciclo_escolar = $this->id_ciclo_escolar AND id_concepto = 1
              AND grupo.id_ciclo_escolar = $this->id_ciclo_escolar AND (monto + recargos) = pagado";
        return Database::select($query);
    }

    function getCountAlumnosInscritos()
    {
        $alumnos = self::getAlumnosInscritos();
        if($alumnos) return count($alumnos);
        else return 0;
    }

    function getGrupos()
    {
        $query = "SELECT grupo.*, grado, area, COALESCE(alumnos, 0) AS alumnos
            FROM grupo JOIN grado ON grado.id_grado = grupo.id_grado
            LEFT JOIN area ON grado.id_area = area.id_area
            LEFT JOIN (SELECT id_grupo, COUNT(id_alumno) AS alumnos FROM alumno_grupo GROUP BY id_grupo) AS alumnos 
            ON alumnos.id_grupo = grupo.id_grupo 
            WHERE id_ciclo_escolar = $this->id_ciclo_escolar";
        return Database::select($query);
    }

    function getCountGrupos()
    {
        $grupos = self::getGrupos();
        if($grupos) return count($grupos);
        else return 0;
    }

    function getPromedioGeneral()
    {
        $query = "SELECT ROUND(AVG(calificacion), 2) AS promedio FROM calificacion
            JOIN clase ON clase.id_clase = calificacion.id_clase
            JOIN grupo ON grupo.id_grupo = clase.id_grupo
            WHERE id_ciclo_escolar = $this->id_ciclo_escolar";
        $resultado = Database::select($query);
        return $resultado[0]['promedio'];
    }

    function getCountPendientes()
    {
        $query = "SELECT SUM(pendientes) AS pendientes FROM (
            SELECT id_alumno, clase.id_clase, (5 - COUNT(*)) AS pendientes 
            FROM calificacion 
            JOIN clase ON clase.id_clase = calificacion.id_clase
            JOIN grupo ON grupo.id_grupo = clase.id_grupo
            WHERE id_ciclo_escolar = $this->id_ciclo_escolar 
            GROUP BY id_clase, id_alumno
            ) tb_pendientes";
        $resultado = Database::select($query);
        return $resultado[0]['pendientes'];
    }

    function getCountAprobados()
    {
        $query = "SELECT COUNT(promedio) AS cont FROM (
            SELECT id_alumno, ROUND(AVG(calificacion), 2) AS promedio 
            FROM calificacion
            JOIN clase ON clase.id_clase = calificacion.id_clase
            JOIN grupo ON grupo.id_grupo = clase.id_grupo
            WHERE id_ciclo_escolar = 1 GROUP BY id_alumno) tb_promedios
            WHERE promedio >= 6";
        $resultado = Database::select($query);
        return $resultado[0]['cont'];
    }

    function getCountReprobados()
    {
        $query = "SELECT COUNT(promedio) AS cont FROM (
            SELECT id_alumno, ROUND(AVG(calificacion), 2) AS promedio 
            FROM calificacion
            JOIN clase ON clase.id_clase = calificacion.id_clase
            JOIN grupo ON grupo.id_grupo = clase.id_grupo
            WHERE id_ciclo_escolar = 1 GROUP BY id_alumno) tb_promedios
            WHERE promedio < 6";
        $resultado = Database::select($query);
        return $resultado[0]['cont'];
    }

    function cerrar()
    {
        if($this->getStatus())
        {
            $query = "UPDATE ciclo_escolar SET fecha_fin = NOW() WHERE id_ciclo_escolar = $this->id_ciclo_escolar";
            return Database::update($query);
        }
        else
        {
            return FALSE;
        }
    }

    function asignarMateria($id_grado, $id_materia)
    {
        $query = "INSERT INTO grado_materia 
            SET id_grado = $id_grado, id_materia = $id_materia, id_ciclo_escolar = $this->id_ciclo_escolar";
        return Database::insert($query);
    }

    function getMaestros()
    {
        $query = "SELECT persona.* FROM clase 
            JOIN grupo ON grupo.id_grupo = clase.id_grupo
            JOIN persona ON persona.id_persona = clase.id_maestro
            WHERE id_ciclo_escolar = $this->id_ciclo_escolar
            GROUP BY id_maestro";
        return Database::select($query);
    }

    function getCountMaestros()
    {
        $query = "SELECT COUNT(id_maestro) AS maestros FROM clase 
            JOIN grupo ON grupo.id_grupo = clase.id_grupo 
            WHERE id_ciclo_escolar = $this->id_ciclo_escolar";
        $res = Database::select($query);
        return $res[0]['maestros'];
    }

    function getBecas()
    {
        $query = "SELECT alumno.id_persona, 
            CONCAT(alumno.nombres, ' ', alumno.apellido_paterno, ' ', alumno.apellido_materno) AS alumno,
            usuario.nombres AS usuario, 
            beca, CONCAT(tipo_beca, ' - ', subtipo_beca) AS tipo 
            FROM beca 
            JOIN persona AS alumno ON beca.id_alumno = alumno.id_persona
            JOIN persona AS usuario ON beca.id_usuario = usuario.id_persona
			JOIN beca_subtipo ON beca_subtipo.id_subtipo_beca = beca.id_subtipo
			JOIN beca_tipo ON beca_tipo.id_tipo_beca = beca_subtipo.id_tipo_beca
            WHERE id_ciclo_escolar = $this->id_ciclo_escolar";
        return Database::select($query);
    }

    function getCountBecas()
    {
        $query = "SELECT COUNT(alumno.id_persona) AS count 
            FROM beca 
            JOIN persona AS alumno ON beca.id_alumno = alumno.id_persona
            JOIN persona AS usuario ON beca.id_usuario = usuario.id_persona
            WHERE id_ciclo_escolar = $this->id_ciclo_escolar";
        $rs = Database::select($query);
        return $rs[0]['count'];
    }

    function getDistribucionAlumnos()
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
        return Database::select($query);
    }

    function getDistribucionMaestros()
    {
        $query = "SELECT TB1.maestros AS A1, TB2.maestros AS A2, TB3.maestros AS A3,
            TB4.maestros AS A4, TB5.maestros AS A5 FROM
            (SELECT COALESCE(id_area, 0), COALESCE(COUNT(id_maestro), 0) AS maestros FROM
            (SELECT grupo.id_grupo, id_area, id_maestro FROM clase
            JOIN grupo ON grupo.id_grupo = clase.id_grupo
            JOIN grado ON grado.id_grado = grupo.id_grado
            GROUP BY id_maestro, id_area) TBI1
            WHERE id_area = 1) TB1
            JOIN (SELECT COALESCE(id_area, 0), COALESCE(COUNT(id_maestro), 0) AS maestros FROM
            (SELECT grupo.id_grupo, id_area, id_maestro FROM clase
            JOIN grupo ON grupo.id_grupo = clase.id_grupo
            JOIN grado ON grado.id_grado = grupo.id_grado
            GROUP BY id_maestro, id_area) TBI2
            WHERE id_area = 2) TB2
            JOIN (SELECT COALESCE(id_area, 0), COALESCE(COUNT(id_maestro), 0) AS maestros FROM
            (SELECT grupo.id_grupo, id_area, id_maestro FROM clase
            JOIN grupo ON grupo.id_grupo = clase.id_grupo
            JOIN grado ON grado.id_grado = grupo.id_grado
            GROUP BY id_maestro, id_area) TBI3
            WHERE id_area = 3) TB3
            JOIN (SELECT COALESCE(id_area, 0), COALESCE(COUNT(id_maestro), 0) AS maestros FROM
            (SELECT grupo.id_grupo, id_area, id_maestro FROM clase
            JOIN grupo ON grupo.id_grupo = clase.id_grupo
            JOIN grado ON grado.id_grado = grupo.id_grado
            GROUP BY id_maestro, id_area) TBI4
            WHERE id_area = 4) TB4
            JOIN (SELECT COALESCE(id_area, 0), COALESCE(COUNT(id_maestro), 0) AS maestros FROM
            (SELECT grupo.id_grupo, id_area, id_maestro FROM clase
            JOIN grupo ON grupo.id_grupo = clase.id_grupo
            JOIN grado ON grado.id_grado = grupo.id_grado
            GROUP BY id_maestro, id_area) TBI5
            WHERE id_area = 5) TB5";
        return Database::select($query);
    }

    public function getAnoInicio()
    {
        $fecha = DateTime::createFromFormat("Y-m-d", $this->fecha_inicio);
        return $fecha->format("Y");
    }

    # Métodos estáticos
    static function getLista()
    {
        // id_ciclo_escolar | ciclo_escolar
        return Database::select("SELECT id_ciclo_escolar, CONCAT(CAST(YEAR(fecha_inicio) AS CHAR), ' - ',
            YEAR(fecha_fin)) AS ciclo_escolar, fecha_inicio, fecha_fin
            FROM ciclo_escolar ORDER BY fecha_inicio DESC");
    }

    static function getListaProximos()
    {
        $query = "SELECT id_ciclo_escolar, CONCAT(CasT(YEAR(fecha_inicio) AS CHAR), ' - ', YEAR(fecha_fin)) AS ciclo
            FROM ciclo_escolar WHERE fecha_fin >= NOW() ORDER BY fecha_inicio DESC";
        return Database::select($query);
    }

    static function getListaAscendente()
    {
        // id_ciclo_escolar | ciclo_escolar
        return Database::select("SELECT id_ciclo_escolar, YEAR(fecha_inicio) AS ciclo_escolar, fecha_inicio, fecha_fin
            FROM ciclo_escolar ORDER BY fecha_inicio ASC");
    }

    static function insert($fecha_inicioVal, $fecha_finVal)
    {
        return Database::insert("INSERT INTO ciclo_escolar SET fecha_inicio = '$fecha_inicioVal', fecha_fin = '$fecha_finVal'");
    }

    static function iniciarNuevo()
    {
        $query = "INSERT INTO ciclo_escolar SET fecha_inicio = NOW()";
        return Database::insert($query);
    }

    static function checkOverlap($fecha_inicioVal)
    {
        $resultado = Database::select("SELECT IF(CAST('$fecha_inicioVal' AS DATE) < MAX(fecha_fin), 1, 0) AS overlap
                    FROM ciclo_escolar");
        if($resultado[0]['overlap'] == "1") return TRUE;
        else return FALSE;
    }

    static function getActual()
    {
        $ciclo_actual = Database::select("SELECT * FROM ciclo_escolar WHERE NOW() BETWEEN fecha_inicio AND fecha_fin LIMIT 1");
        return new static($ciclo_actual[0]['id_ciclo_escolar']);
    }

    static function getInscripciones()
    {
        $query = "SELECT YEAR(ciclo_escolar.fecha_inicio) ciclo, altas, COALESCE(bajas, 0) bajas
            FROM ciclo_escolar LEFT JOIN
            (SELECT id_ciclo_escolar, COUNT(*) AS altas
            FROM ciclo_escolar
            LEFT JOIN persona
            ON persona.fecha_alta > fecha_inicio
            AND persona.fecha_alta < COALESCE(fecha_fin, fecha_inicio + INTERVAL 1 YEAR)
            WHERE tipo_persona = 1 GROUP BY id_ciclo_escolar) TBaltas
            ON ciclo_escolar.id_ciclo_escolar = TBaltas.id_ciclo_escolar
            LEFT JOIN
            (SELECT id_ciclo_escolar, COUNT(*) AS bajas
            FROM ciclo_escolar
            LEFT JOIN persona
            ON persona.fecha_baja > fecha_inicio
            AND persona.fecha_baja < COALESCE(fecha_fin, fecha_inicio + INTERVAL 1 YEAR)
            WHERE tipo_persona = 1 GROUP BY id_ciclo_escolar) TBbajas
            ON ciclo_escolar.id_ciclo_escolar = TBbajas.id_ciclo_escolar";
        return Database::select($query);
    }
}