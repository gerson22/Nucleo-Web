<?php    
include_once("class.Database.php");
include_once("class.CicloEscolar.php");
    
class Alumno extends Persona
{
    public $id_grado;
    public $grado;
    public $id_grupo;
    public $grupo;
    public $beca;

    function __construct($id_persona)
    {
        $ciclo_actual = CicloEscolar::getActual()->id_ciclo_escolar;
        if(is_null($ciclo_actual)) $ciclo_actual = 0;
        $query = "SELECT persona.*, grado.id_grado, grado, grupo.id_grupo, grupo,
            COALESCE(beca, 0) AS beca FROM persona
            LEFT JOIN alumno_grupo ON alumno_grupo.id_alumno = persona.id_persona
            LEFT JOIN grupo ON alumno_grupo.id_grupo = grupo.id_grupo
            LEFT JOIN grado ON grupo.id_grado = grado.id_grado
			LEFT JOIN (SELECT id_alumno, beca FROM beca WHERE id_ciclo_escolar = $ciclo_actual) AS beca
			ON beca.id_alumno = persona.id_persona
            WHERE id_persona = $id_persona LIMIT 1";
        $persona = Database::select($query);
        $persona = $persona[0];
        $this->id_persona           = $persona['id_persona'];
        $this->matricula            = $persona['matricula'];
        $this->nombres              = $persona['nombres'];
        $this->apellido_paterno     = $persona['apellido_paterno'];
        $this->apellido_materno     = $persona['apellido_materno'];
        $this->id_grado             = $persona['id_grado'];
        $this->grado                = $persona['grado'];
        $this->id_grupo             = $persona['id_grupo'];
        $this->grupo                = $persona['grupo'];
        $this->password             = $persona['password'];
        $this->tipo_persona         = $persona['tipo_persona'];
        $this->fecha_alta           = $persona['fecha_alta'];
        $this->fecha_baja           = $persona['fecha_baja'];
        $this->beca                 = $persona['beca'];
    }

    function getTipoPersona()
    {
        return "alumno";
    }

    function getMateriasCiclo($id_ciclo)
    {
        $query = "SELECT materia.id_materia, id_clase, materia
            FROM alumno_grupo
            JOIN clase ON clase.id_grupo = alumno_grupo.id_grupo
            JOIN grupo ON alumno_grupo.id_grupo = grupo.id_grupo
            JOIN materia ON clase.id_materia = materia.id_materia
            WHERE id_alumno = $this->id_persona AND id_ciclo_escolar = $id_ciclo";
        return Database::select($query);
    }

    function getMateriasCursando()
    {
        $id_ciclo = CicloEscolar::getActual()->id_ciclo_escolar;
        $query = "SELECT materia.id_materia, id_clase, materia
            FROM alumno_grupo
            JOIN clase ON clase.id_grupo = alumno_grupo.id_grupo
            JOIN grupo ON alumno_grupo.id_grupo = grupo.id_grupo
            JOIN materia ON clase.id_materia = materia.id_materia
            WHERE id_alumno = $this->id_persona AND id_ciclo_escolar = $id_ciclo";
        $materias = Database::select($query);
        if($materias) return $materias;
        else return -1;
    }

    function darBaja()
    {
        $id_ciclo = CicloEscolar::getActual()->id_ciclo_escolar;
        $query = "SELECT grupo.id_grupo FROM alumno_grupo
            JOIN grupo ON alumno_grupo.id_grupo = grupo.id_grupo
            WHERE id_alumno = $this->id_persona AND id_ciclo_escolar = $id_ciclo";
        $rs = Database::select($query);
        $id_grupo = $rs[0]['id_grupo'];
        if(!is_null($id_grupo))
        {
            $query = "DELETE FROM alumno_grupo WHERE id_alumno = $this->id_persona AND id_grupo = $id_grupo";
            return Database::update($query);
        }
        else return FALSE;
    }

    function getCURP()
    {
        $query = "SELECT CURP FROM CURP WHERE id_persona = $this->id_persona";
        $rs = Database::select($query);
        return $rs[0]['CURP'];
    }

    function getCalificacion($id_clase, $parcial)
    {
        $calificacion = Database::select("SELECT calificacion FROM calificacion
            WHERE id_alumno = $this->id_persona AND id_clase = $id_clase AND parcial = $parcial");
        return $calificacion[0]['calificacion'];
    }

    function inscribirEnGrupo($id_grupo)
    {
        if(!$this->estaEnGrupo($id_grupo))
        {
            $query = "INSERT INTO alumno_grupo SET id_alumno = $this->id_persona, id_grupo = $id_grupo";
            return Database::insert($query);
        }
        else return FALSE;
    }

    function estaEnGrupo($id_grupo)
    {
        $query = "SELECT IF(id_alumno, 1, 0) AS inscrito 
            FROM alumno_grupo WHERE id_alumno = $this->id_persona AND id_grupo = $id_grupo";
        $res = Database::select($query);
        if($res[0]['inscrito'] == 1) return TRUE;
        else return FALSE;
    }

    function getHistorialBecas()
    {
        $query = "SELECT ciclo_escolar.fecha_inicio AS ciclo_escolar, beca, tipo_beca, subtipo_beca,
        usuario.nombres AS usuario FROM beca
			JOIN beca_subtipo ON beca_subtipo.id_subtipo_beca = beca.id_subtipo
			JOIN beca_tipo ON beca_tipo.id_tipo_beca = beca_subtipo.id_tipo_beca
            JOIN ciclo_escolar ON beca.id_ciclo_escolar = ciclo_escolar.id_ciclo_escolar
            JOIN persona AS usuario ON usuario.id_persona = beca.id_usuario
            WHERE id_alumno = $this->id_persona";
        return Database::select($query);
    }

    function getBeca($id_ciclo_escolar)
    {
        $query = "SELECT beca.*, CONCAT(tipo_beca, '-', subtipo_beca) AS tipo FROM beca
            JOIN beca_subtipo ON beca_subtipo.id_subtipo_beca = beca.id_subtipo
            JOIN beca_tipo ON beca_tipo.id_tipo_beca = beca_subtipo.id_tipo_beca
            WHERE id_alumno = $this->id_persona AND id_ciclo_escolar = $id_ciclo_escolar";
        $res = Database::select($query);
        return $res[0];
    }

    function getBecaActual()
    {
        $ciclo_actual = CicloEscolar::getActual();
        $query = "SELECT beca.*, CONCAT(tipo_beca, '-', subtipo_beca) AS tipo FROM beca
            JOIN beca_subtipo ON beca_subtipo.id_subtipo_beca = beca.id_subtipo
            JOIN beca_tipo ON beca_tipo.id_tipo_beca = beca_subtipo.id_tipo_beca
            WHERE id_alumno = $this->id_persona AND id_ciclo_escolar = $ciclo_actual->id_ciclo_escolar";
        $res = Database::select($query);
        return $res[0];
    }

    function quitarBeca($id_ciclo, $id_subtipo)
    {
        $query = "DELETE FROM beca WHERE id_alumno = $this->id_persona
          AND id_ciclo_escolar = $id_ciclo AND id_subtipo = $id_subtipo";
        return Database::update($query);
    }

    function quitarBecasCiclo($id_ciclo)
    {
        $query = "DELETE FROM beca WHERE id_alumno = $this->id_persona AND id_ciclo_escolar = $id_ciclo";
        return Database::update($query);
    }

    function asignarBeca($becaVal, $subtipoVal, $id_ciclo_escolar)
    {
        $query_existe = "SELECT COUNT(beca) AS beca
          FROM beca WHERE id_alumno = $this->id_persona AND id_ciclo_escolar = $id_ciclo_escolar AND id_subtipo = $subtipoVal";
        $res = Database::select($query_existe);

        if($res[0]['becado'] != 1)
        {
            session_start();
            $id_usuario = $_SESSION['id_persona'];
            $query = "INSERT INTO beca SET id_alumno = $this->id_persona, beca = $becaVal,
                id_usuario = $id_usuario, id_ciclo_escolar = $id_ciclo_escolar, id_subtipo = $subtipoVal";
            return Database::insert($query);
        }
        else
        {
            return false;
        }
    }

    function getClasesActuales()
    {
        $ciclo_actual = CicloEscolar::getActual();
        $query = "SELECT grado, grupo, materia FROM alumno_grupo
            JOIN clase ON clase.id_grupo = alumno_grupo.id_grupo
            JOIN grupo ON grupo.id_grupo = alumno_grupo.id_grupo
            JOIN grado ON grupo.id_grado = grado.id_grado
            JOIN materia ON clase.id_materia = materia.id_materia 
            WHERE id_alumno = $this->id_persona AND id_ciclo_escolar = $ciclo_actual->id_ciclo_escolar";
        return Database::select($query);
    }

    function getEstado()
    {
        // Regresa un booleano según el estado de su cuenta de inscripción del semestre actual.
        $ciclo = CicloEscolar::getActual();

        $query = "SELECT IF(monto <= pagado, 1, 0) AS activo FROM cuentas_cuenta
            WHERE id_persona = $this->id_persona AND id_ciclo_escolar = $ciclo->id_ciclo_escolar AND id_concepto = 1";
        $res = Database::select($query);
        if($res[0]['activo'] == 1) return TRUE;
        else return FALSE;
    }

    function getPagosCuentasCiclo()
    {
        $ciclo_actual = CicloEscolar::getActual();
        $query = "SELECT CAST(fecha AS DATE) AS fecha, cuentas_concepto.concepto AS concepto, 
        monto, CONCAT(usuario.nombres, ' ', usuario.apellido_paterno) AS usuario, descripcion FROM cuentas_pago 
        JOIN persona ON cuentas_pago.id_persona = persona.id_persona
        JOIN cuentas_concepto ON cuentas_pago.id_concepto = cuentas_concepto.id_concepto
        JOIN ciclo_escolar ON cuentas_pago.id_ciclo_escolar = ciclo_escolar.id_ciclo_escolar 
        JOIN persona AS usuario ON cuentas_pago.id_usuario = usuario.id_persona 
        WHERE persona.id_persona = $this->id_persona AND ciclo_escolar.id_ciclo_escolar = $ciclo_actual->id_ciclo_escolar";
        return Database::select($query);
    }

    function asignarTutor($tipo, $nombre, $calle, $numero, $colonia, $CP, $telefono, $celular)
    {
        $query = "INSERT INTO persona_tutor VALUES(
            null, $this->id_persona, $tipo, '$nombre', '$calle', '$numero', '$colonia', '$CP', '$telefono', '$celular')";
        return Database::insert($query);
    }

    function setClubDeportivo($club)
    {
        $query = "REPLACE INTO persona_extra VALUES($this->id_persona, $club)";
        return Database::insert($query);
    }

    function getClubDeportivo()
    {
        $query = "SELECT club.nombre AS club_deportivo FROM persona_extra
            JOIN club ON club.id_club = persona_extra.club_deportivo
            WHERE id_persona = $this->id_persona";
        $rs = Database::select($query);
        return $rs[0]['club_deportivo'];
    }

    function setCURP($CURP)
    {
        $query = "REPLACE INTO CURP VALUES($this->id_persona, '$CURP');";
        return Database::insert($query);
    }

    function getTutores()
    {
        $query = "SELECT persona_tutor.id_tipo_tutor, tipo_tutor, nombre,
            calle, numero, colonia, CP, telefonos, celular
            FROM persona_tutor
            JOIN tipo_tutor ON tipo_tutor.id_tipo_tutor = persona_tutor.id_tipo_tutor
            WHERE id_alumno = $this->id_persona";
        return Database::select($query);
    }

    function getDescuentoAutorizado($id_concepto)
    {
        $query = "SELECT IF(count(monto) > 0, monto, 0) AS monto  FROM cuentas_descuento
        WHERE id_persona = $this->id_persona AND id_concepto = $id_concepto AND ISNULL(fecha_utilizacion)";
        $res = Database::select($query);
        return $res[0]['monto'];
    }

    function getInscripcionCuenta($id_ciclo_escolar)
    {
        return Cuenta::getCuenta($this->id_persona, $id_ciclo_escolar);
    }

    function getInscripcionStatus($id_ciclo_escolar)
    {
        # id_cuenta | subtotal | descuento | total | pagado | adeudo
        $query = "SELECT id_cuenta, monto AS subtotal, descuento, (monto - descuento) AS total,
            pagado, (monto - descuento - pagado) AS adeudo, fecha_limite FROM cuentas_cuenta
            WHERE id_persona = $this->id_persona AND id_concepto = 1 AND id_ciclo_escolar = $id_ciclo_escolar";
        $cuenta = Database::select($query);

        /** Crear un arreglo y meterle los datos */
        if(count($cuenta) > 0)
        {
            $arreglo['id_cuenta']   = $cuenta[0]['id_cuenta'];
            $arreglo['subtotal']    = $cuenta[0]['subtotal'];
            $arreglo['descuento']   = $cuenta[0]['descuento'];
            $arreglo['total']       = $cuenta[0]['total'];
            $arreglo['pagado']      = $cuenta[0]['pagado'];
            $arreglo['adeudo']      = $cuenta[0]['adeudo'];
            $arreglo['fecha']       = $cuenta[0]['fecha_limite'];
            return $arreglo;
        }
        else
        {
            return false;
        }
    }

    function isInscrito($id_ciclo_escolar)
    {
        $statusInscripcion = $this->getInscripcionStatus($id_ciclo_escolar);
        if($statusInscripcion['adeudo'] == '0') return true;
        return falsa;
    }

    function getColegiaturasStatus($id_ciclo_escolar)
    {
        #  	Subtotal 	Recargos 	Descuento 	Total 	Pagado 	Adeudo 	Abono
        $query = "SELECT TBP.recibo, CAST(fecha_ultimo_pago AS DATE) AS fecha_ultimo_pago,
            cuentas_cuenta.id_cuenta, mes, monto AS subtotal, recargos, descuento, (monto - descuento) AS total,
            pagado, (monto - descuento - pagado) AS adeudo,
            MONTH(fecha_limite) AS limite_mes, DAY(fecha_limite) AS limite_dia, fecha_limite
            FROM cuentas_cuenta
			LEFT JOIN (SELECT id_pago, recibo, MAX(fecha) AS fecha_ultimo_pago, id_cuenta FROM cuentas_pago GROUP BY id_cuenta) AS TBP
			ON TBP.id_cuenta = cuentas_cuenta.id_cuenta
            WHERE id_persona = $this->id_persona AND id_concepto = 2
            AND id_ciclo_escolar = $id_ciclo_escolar ORDER BY mes ASC";
        return Database::select($query);
    }

    function getCiclosInscrito()
    {
        $query = "SELECT ciclo_escolar.id_ciclo_escolar, CONCAT(CAST(YEAR(fecha_inicio) AS CHAR), ' - ',
            YEAR(fecha_fin)) AS ciclo_escolar FROM alumno_grupo
            JOIN grupo ON grupo.id_grupo = alumno_grupo.id_grupo
            JOIN ciclo_escolar ON grupo.id_ciclo_escolar = ciclo_escolar.id_ciclo_escolar
            WHERE id_alumno = $this->id_persona";
        return Database::select($query);
    }

    // Regresa un arreglo Area
    function getArea()
    {
        $query = "SELECT area.* FROM persona
            JOIN (SELECT id_alumno, MAX(id_grupo) AS id_grupo FROM alumno_grupo GROUP BY id_alumno) AS alumno_grupo
            ON alumno_grupo.id_alumno = persona.id_persona
            JOIN grupo ON grupo.id_grupo = alumno_grupo.id_grupo
            JOIN grado ON grado.id_grado = grupo.id_grado
            JOIN area ON area.id_area = grado.id_area
            WHERE persona.id_persona = $this->id_persona";
        $areas = Database::select($query);
        return $areas[0];
    }

    function agregarDocumento($id_documento, $original, $copia)
    {
        $query = "INSERT INTO alumno_papeleria VALUES($this->id_persona, $id_documento, $original, $copia)";
        return Database::insert($query);
    }

    // Regresa el reporte del grupo y parcial especificados
    function getReportesKinderParcial($id_grupo, $id_parcial)
    {
        $query = "SELECT materia.id_materia, materia, reporte FROM grupo
            JOIN grado ON grado.id_grado = grupo.id_grado
            JOIN grado_materia ON grado.id_grado = grado_materia.id_grado
            JOIN materia ON materia.id_materia = grado_materia.id_materia
            LEFT JOIN (SELECT * FROM calificacion_kinder WHERE id_alumno = $this->id_persona AND parcial = $id_parcial) AS calificaciones
            ON materia.id_materia = calificaciones.id_materia
            WHERE grupo.id_grupo = $id_grupo";
        return Database::select($query);
    }

    function getReportesPrimariaParcial($id_grupo, $id_parcial)
    {
        $query = "";
        return Database::select($query);
    }

    function getCalificacionesPrimaria($id_grupo, $parcial)
    {
        $query = "SELECT materia.id_materia, materia, calificacion FROM grupo
            JOIN clase ON clase.id_grupo = grupo.id_grupo
            JOIN materia ON materia.id_materia = clase.id_materia
            LEFT JOIN (SELECT id_materia, calificacion FROM calificacion_cuantitativa
            JOIN clase ON calificacion_cuantitativa.id_clase = clase.id_clase
            WHERE id_alumno = $this->id_persona AND parcial = $parcial) AS tb_calis ON tb_calis.id_materia = materia.id_materia
            WHERE grupo.id_grupo = $id_grupo";
        return Database::select($query);
    }

    function crearCuentaInscripcion($id_ciclo_escolar)
    {
        $area = $this->getArea();
        $id_area = $area['id_area'];
        $query = "INSERT INTO cuentas_cuenta SET
            id_persona = $this->id_persona,
            id_concepto = 1,
            id_ciclo_escolar = $id_ciclo_escolar,
            monto = (SELECT monto FROM cuentas_monto WHERE id_concepto = 1 AND id_area = $id_area),
            descuento = 0,
            fecha_limite = CONCAT((SELECT CAST(YEAR(fecha_inicio) AS CHAR) FROM ciclo_escolar WHERE id_ciclo_escolar = $id_ciclo_escolar), '-',
            (SELECT CAST(MONTH(fecha_limite) AS CHAR) FROM cuentas_fechas WHERE id_concepto = 1 AND mes = 0), '-',
            (SELECT CAST(DAY(fecha_limite) AS CHAR) FROM cuentas_fechas WHERE id_concepto = 1 AND mes = 0))";
        return Database::insert($query);
    }

    function crearCuentasColegiaturas($id_ciclo_escolar)
    {
        $area = $this->getArea();
        $id_area = $area['id_area'];
        $beca = $this->getBeca($id_ciclo_escolar);
        $beca = $beca['beca'];

        $monto = Cuenta::getMonto(2, $id_area);
        $monto_beca = $monto - ($monto * $beca / 100);

        for($mes = 1; $mes <= 12; $mes++)
        {
            $fecha_limite = Cuenta::getFechaLimite(2, $mes, $id_ciclo_escolar);

            if($mes <= 1) $monto_temp = $monto;
            else $monto_temp = $monto_beca;

            $query = "INSERT INTO cuentas_cuenta SET
            id_persona = $this->id_persona,
            id_concepto = 2, mes = $mes,
            id_ciclo_escolar = $id_ciclo_escolar,
            monto = ".$monto_temp.",
            fecha_limite = '$fecha_limite'";

            Database::insert($query);
        }
        return true;
    }

    function actualizarRecargos($id_ciclo_escolar)
    {
        $cuentas = $this->getCuentas($id_ciclo_escolar);
        $recargo_diario = Cuenta::getMontoRecargo(2);

        if(is_array($cuentas))
        {
            foreach($cuentas as $cuenta)
            {
                if($cuenta['id_concepto'] != 1 AND $cuenta['mes'] != 6)
                {
                    $dias_recargo = floor((time() - strtotime($cuenta['fecha_limite'])) / 86400);

                    if($dias_recargo > 0)
                    {

                        # monto + recargos - descuento - pagado
                        $monto = $cuenta['monto'] + $cuenta['recargos'] - $cuenta['descuentos'] - $cuenta['pagado'];
                        echo "\nCuenta ".$cuenta['id_cuenta'].". Monto: ".$monto;
                        if($monto > 0)
                        {
                            // Cuenta vencida
                            echo ", ".$dias_recargo." dias de recargo";
                            $cuenta = new Cuenta($cuenta['id_cuenta']);
                            $cuenta->recargos = $dias_recargo * $recargo_diario;
                        }
                    }
                    else
                    {
                        // Cuenta no vencida
                        echo "\nCuenta ".$cuenta['id_cuenta']." aun no vence";
                    }
                }
            }
        }
    }

    function getCuentas($id_ciclo_escolar)
    {
        $query = "SELECT * FROM cuentas_cuenta
            WHERE id_persona = $this->id_persona AND id_ciclo_escolar = $id_ciclo_escolar";
        return Database::select($query);
    }

    function getPapeleria()
    {
        $query = "SELECT documento.id_documento, documento,
            COALESCE(original, 0) * 1 AS original,
            COALESCE(copia, 0) * 1 AS copia FROM documento
            LEFT JOIN (SELECT * FROM alumno_papeleria WHERE id_alumno = $this->id_persona)
            AS TB1 ON TB1.id_documento = documento.id_documento";
        return Database::select($query);
    }

    function setPapeleria($papeleria)
    {
        $query = "DELETE FROM alumno_papeleria WHERE id_alumno = $this->id_persona";
        Database::update($query);
        if(is_array($papeleria))
        {
            foreach($papeleria as $documento)
            {
                if($documento->original == 1 || $documento->copia == 1)
                $this->agregarDocumento($documento->id_documento, $documento->original, $documento->copia);
            }
        }
        echo 1;
    }

    function getNombreCompleto()
    {
        return $this->nombres." ".$this->apellido_paterno." ".$this->apellido_materno;
    }

    function updateTutor($tipo_tutor, $nombre, $calle, $numero, $colonia, $CP, $telefonos, $celular)
    {
        $query = "UPDATE persona_tutor
            SET nombre = '$nombre', calle = '$calle', numero = '$numero', colonia = '$colonia',
            CP = '$CP', telefonos = '$telefonos', celular = '$celular'
            WHERE id_alumno = $this->id_persona AND id_tipo_tutor = $tipo_tutor";
        return Database::update($query);
    }

    function getGrado($ciclo)
    {
        $query = "SELECT grado.grado FROM alumno_grupo
            JOIN grupo ON alumno_grupo.id_grupo = grupo.id_grupo
            JOIN grado ON grupo.id_grado = grado.id_grado
            WHERE alumno_grupo.id_alumno = $this->id_persona AND grupo.id_ciclo_escolar = $ciclo";
        $res = Database::select($query);
        return $res[0]['grado'];
    }

    function getGradoObj($ciclo)
    {
        $query = "SELECT grado.id_grado FROM alumno_grupo
            JOIN grupo ON alumno_grupo.id_grupo = grupo.id_grupo
            JOIN grado ON grupo.id_grado = grado.id_grado
            WHERE alumno_grupo.id_alumno = $this->id_persona AND grupo.id_ciclo_escolar = $ciclo";
        $res = Database::select($query);
        return new Grado($res[0]['id_grado']);
    }

    function getGrupoObj($ciclo)
    {
        $query = "SELECT grupo.id_grupo FROM alumno_grupo
            JOIN grupo ON alumno_grupo.id_grupo = grupo.id_grupo
            WHERE alumno_grupo.id_alumno = $this->id_persona AND grupo.id_ciclo_escolar = $ciclo";
        $res = Database::select($query);
        return new Grupo($res[0]['id_grupo']);
    }

    function getGrupo($ciclo)
    {
        $query = "SELECT grupo.grupo FROM alumno_grupo
            JOIN grupo ON alumno_grupo.id_grupo = grupo.id_grupo
            WHERE alumno_grupo.id_alumno = $this->id_persona AND grupo.id_ciclo_escolar = $ciclo";
        $res = Database::select($query);
        return $res[0]['grupo'];
    }

    function cambiarGrupo($id_grupo)
    {
        /**
         * Paso 1: Actualizar las calificaciones, se hace esto primero por que si se elimina la relación actual
         * <alumno - grupo> se eliminarian las calificaciones existentes por las foreign keys con cascade on delete.
         */
        $grupoA = new Grupo($this->id_grupo);
        $grupoB = new Grupo($id_grupo);

        $clases = $grupoA->getClases();
        foreach($clases as $claseA)
        {
            $id_materia = $claseA['id_materia'];
            $id_clase = $claseA['id_clase'];
            $id_claseB = $grupoB->getClase($id_materia);

            $query = "UPDATE calificacion SET id_clase = $id_claseB
              WHERE id_clase = $id_clase AND id_alumno = $this->id_persona";
            Database::update($query);
        }

        /**
         * Paso 2: Cambiar el grupo
         */
        $query = "UPDATE alumno_grupo SET id_grupo = $grupoB->id_grupo
                WHERE id_alumno = $this->id_persona AND id_grupo = $grupoA->id_grupo";
        Database::update($query);
        $this->id_grupo = $grupoB->id_grupo;
    }

    # Métodos estáticos
    static function getLista()
    {
        return Database::select("SELECT persona.*, grado.grado, grupo.grupo, area FROM persona
            JOIN alumno_grupo ON alumno_grupo.id_alumno = persona.id_persona
            JOIN grupo ON grupo.id_grupo = alumno_grupo.id_grupo
            JOIN grado ON grado.id_grado = grupo.id_grado
            JOIN area ON area.id_area = grado.id_area
            WHERE tipo_persona = 1");
    }

    public static function insert($apellido_paterno, $apellido_materno, $nombres, $id_ciclo_escolar)
    {
        if(!self::existe($nombres, $apellido_paterno, $apellido_materno))
        {
            $ciclo = new CicloEscolar($id_ciclo_escolar);
            $password = parent::generarPassword(8);
            $matricula = self::nuevaMatricula($ciclo->getAnoInicio());

            $query = "INSERT INTO persona VALUES
                (null, '$matricula',
                 '$apellido_paterno', '$apellido_materno', '$nombres',
                1, '$password', NOW(), null, 'photo_NA.jpg')";
            return Database::insert($query);

        }
        else return 0;
    }

    static function nuevaMatricula($ano)
    {
        //{A}{2 dígitos del código del colegio}{4 dígitos año}{4 dígitos contador}
        $colegio = '01'; // HARDCODED. Colegio Meze = 01
        $contador = Alumno::getSiguienteDelCiclo($ano);
        return 'A'.$colegio.$ano.$contador;
    }

    static function getSiguienteDelCiclo($ano)
    {
        $query = "SELECT LPAD(COALESCE(MAX(SUBSTRING(matricula, 8, 4)), '0000') + 1, 4, '0') AS siguiente
          FROM persona WHERE tipo_persona = 1";
        $res = Database::select($query);
        return $res[0]['siguiente'];
    }
    
    public static function login($matricula, $password)
    {
        $alumnos = Database::select("SELECT * FROM alumno WHERE matricula = $matricula AND password = '$password' LIMIT 1;");
        if(count($alumnos) > 0) return $alumnos[0]['id_alumno'];
        else return 0;
    }

    public static function buscarAlumnos($parametro)
    {
        $query = "SELECT * FROM persona 
            WHERE (apellido_paterno LIKE '%".$parametro."%' 
            OR apellido_materno LIKE '%".$parametro."%' 
            OR nombres LIKE '%".$parametro."%') 
            AND tipo_persona = 1";
        return Database::select($query);
    }

    public static function existe($nombres, $paterno, $materno)
    {
        $query = "SELECT id_persona IS NOT NULL AS existe FROM persona WHERE nombres = '$nombres'
            AND apellido_paterno = '$paterno' AND apellido_materno = '$materno'";
        $rs = Database::select($query);
        if($rs[0]['existe'] == 1) return true;
        else return false;
    }
}