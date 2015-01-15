<?php
include_once("class.Database.php");

class Pago
{
    public $id_pago;
    public $id_cuenta;
    public $monto;
    public $fecha;
    public $id_forma_pago;
    public $id_usuario;
    public $recibo;
    public $comentario;

    function __construct($id_pago)
    {
        $pago = Database::select("SELECT * FROM cuentas_pago WHERE id_pago = $id_pago LIMIT 1;");
        $pago = $pago[0];

        $this->id_pago         = $pago['id_pago'];
        $this->id_cuenta       = $pago['id_cuenta'];
        $this->monto           = $pago['monto'];
        $this->fecha           = $pago['fecha'];
        $this->id_forma_pago   = $pago['id_forma_pago'];
        $this->id_usuario      = $pago['id_usuario'];
        $this->recibo          = $pago['recibo'];
        $this->comentario      = $pago['comentario'];
    }

    public function agregarDetalle($monto, $comentarios, $id_forma)
    {
        session_start();
        $id_usuario = $_SESSION['id_persona'];
        $query = "INSERT INTO cuentas_detalle VALUES(null, $this->id_pago, NOW(), $monto, $id_usuario, '$comentarios', $id_forma)";
        return Database::insert($query);
    }

    public function getConcepto()
    {
        $cuenta = new Cuenta($this->id_cuenta);
        return $cuenta->getConcepto();
    }

    # Método estáticos
    public static function insert($id_conceptoVal, $id_alumnoVal, $montoVal, $descripcionVal, $id_ciclo)
    {
        session_start();
        $id_usuario = $_SESSION['id_persona'];
        $query = "INSERT INTO cuentas_pago VALUES(null, $id_alumnoVal, $id_conceptoVal, $id_ciclo,
            $montoVal, NOW(), $id_usuario, '$descripcionVal');";
        return Database::insert($query);
    }

    public static function getLista($ciclo_escolar, $concepto)
    {
        $query = "SELECT id_pago,
            CONCAT(alumno.nombres, ' ', alumno.apellido_paterno, ' ', alumno.apellido_materno) AS alumno, concepto, 
            YEAR(fecha_inicio) AS ciclo_escolar, monto, CAST(fecha AS DATE) AS fecha, 
            usuario.nombres AS usuario, descripcion  
            FROM cuentas_pago 
            JOIN persona AS alumno ON cuentas_pago.id_persona = alumno.id_persona 
            JOIN cuentas_concepto ON cuentas_pago.id_concepto = cuentas_concepto.id_concepto
            JOIN ciclo_escolar ON cuentas_pago.id_ciclo_escolar = ciclo_escolar.id_ciclo_escolar
            JOIN persona AS usuario ON cuentas_pago.id_usuario = usuario.id_persona 
            WHERE cuentas_pago.id_ciclo_escolar LIKE '%$ciclo_escolar%' AND cuentas_concepto.id_concepto LIKE '%$concepto%' ";
        return Database::select($query);
    }

    public static function getRecibos($recibo, $alumno, $ciclo)
    {
        $filtros = "WHERE folio > 0 ";
        if($recibo) $filtros .= " AND folio = $recibo";
        if($alumno) $filtros .= " AND alumno LIKE '%$alumno%'";
        if($ciclo) $filtros .= " AND id_ciclo_escolar = $ciclo";
        $query = "SELECT folio, fecha, alumno, total FROM
            (SELECT recibo AS folio, CAST(fecha AS DATE) AS fecha,
            CONCAT(nombres, ' ', apellido_paterno, ' ', apellido_materno) AS alumno, SUM(cuentas_pago.monto) AS total,
            id_ciclo_escolar FROM cuentas_pago
            JOIN cuentas_cuenta ON cuentas_cuenta.id_cuenta = cuentas_pago.id_cuenta
            JOIN persona ON persona.id_persona = cuentas_cuenta.id_persona
            GROUP BY recibo) TBR
            $filtros";
        return Database::select($query);
    }

    public static function getPagos($id_nivel, $id_concepto, $fecha_inicio, $fecha_fin)
    {
        $query = "SELECT fecha, cuentas_cuenta.id_cuenta, cuentas_pago.id_pago, id_usuario,
            CONCAT(persona.nombres, ' ', persona.apellido_paterno, ' ', persona.apellido_materno) AS nombre,
            CONCAT(usuario.nombres, ' ', usuario.apellido_paterno, ' ', usuario.apellido_materno) AS usuario,
            cuentas_pago.monto, concepto FROM cuentas_pago
            JOIN cuentas_cuenta ON cuentas_cuenta.id_cuenta = cuentas_pago.id_cuenta
            JOIN cuentas_concepto ON cuentas_concepto.id_concepto = cuentas_cuenta.id_concepto
            JOIN persona ON persona.id_persona = cuentas_cuenta.id_persona
            JOIN persona AS usuario ON usuario.id_persona = cuentas_pago.id_usuario
            JOIN alumno_grupo ON alumno_grupo.id_alumno = persona.id_persona
            JOIN grupo ON grupo.id_grupo = alumno_grupo.id_grupo
            JOIN grado ON grado.id_grado = grupo.id_grado
            WHERE fecha >= '$fecha_inicio' AND fecha <= '$fecha_fin'";
        if ($id_nivel != 0) $query .= " AND id_area = $id_nivel";
        if ($id_concepto != 0) $query .= " AND cuentas_cuenta.id_concepto = $id_concepto";
        return Database::select($query);
    }
}