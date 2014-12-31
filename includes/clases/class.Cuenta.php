<?php
/**
 * Created by PhpStorm.
 * User: Gustavo
 * Date: 14/03/14
 * Time: 02:32 PM
 */

class Cuenta
{
    public $id_cuenta;
    public $id_persona;
    public $id_concepto;
    public $mes;
    public $id_ciclo_escolar;
    public $monto;
    public $recargos;
    public $descuento;
    public $pagado;
    public $fecha_limite;

    function __construct($id_cuenta)
    {
        $cuenta = Database::select("SELECT * FROM cuentas_cuenta WHERE id_cuenta = $id_cuenta LIMIT 1");
        $cuenta = $cuenta[0];
        $this->id_cuenta        = $cuenta['id_cuenta'];
        $this->id_persona       = $cuenta['id_persona'];
        $this->id_concepto      = $cuenta['id_concepto'];
        $this->mes              = $cuenta['mes'];
        $this->id_ciclo_escolar = $cuenta['id_ciclo_escolar'];
        $this->monto            = $cuenta['monto'];
        $this->recargos         = $cuenta['recargos'];
        $this->descuento        = $cuenta['descuento'];
        $this->pagado           = $cuenta['pagado'] * 1.0;
        $this->fecha_limite     = $cuenta['fecha_limite'];
    }

    function agregarPago($monto, $id_forma_pago, $recibo, $comentario)
    {
        #id_pago, id_cuenta, monto, fecha, id_forma_pago, id_usuario, comentario
        session_start();
        $id_usuario = $_SESSION['id_persona'];
        $query = "INSERT INTO cuentas_pago VALUES(null, $this->id_cuenta, $monto, NOW(),
          $id_forma_pago, $id_usuario, $recibo, '$comentario')";
        Database::insert($query);

        $this->pagado = $this->pagado + ($monto * 1.0);

        $this->update();
        return $this->getIDUltimoPago();
    }

    function update()
    {
        $query = "UPDATE cuentas_cuenta SET
            id_persona = $this->id_persona,
            id_concepto = $this->id_concepto,
            id_ciclo_escolar = $this->id_ciclo_escolar,
            monto = $this->monto,
            recargos = $this->recargos,
            descuento = $this->descuento,
            pagado = $this->pagado,
            fecha_limite = '$this->fecha_limite'
            WHERE id_cuenta = $this->id_cuenta";
        return Database::update($query);
    }

    function recalcularDescuento()
    {
        $query = "UPDATE cuentas_cuenta SET descuento = (SELECT SUM(monto)
        FROM cuentas_descuento WHERE id_cuenta = $this->id_cuenta) WHERE id_cuenta = $this->id_cuenta";
        return Database::update($query);
    }

    function fechaUltimoPago()
    {
        $query = "SELECT CAST(MAX(fecha) AS DATE) fecha FROM cuentas_pago WHERE id_cuenta = $this->id_cuenta";
        $res = Database::select($query);
        return $res[0]['fecha'];
    }

    function getIDUltimoPago()
    {
        $query = "SELECT id_pago, MAX(fecha) FROM cuentas_pago WHERE id_cuenta = $this->id_cuenta";
        $res = Database::select($query);
        return $res[0]['id_pago'];
    }

    function getConcepto()
    {
        switch($this->id_concepto)
        {
            case 1: //Inscripción
                return "Inscripción";
                break;
            case 2: // Colegiatura
                return "Colegiatura de ".self::getNombreMes($this->mes);
                break;
            default: break;
        }
    }

    # Métodos estáticos ------------------------------------------------------------------------------------

    static function getMonto($id_concepto, $id_area)
    {
        $query = "SELECT monto FROM cuentas_monto WHERE id_concepto = $id_concepto AND id_area = $id_area";
        $res =  Database::select($query);
        return $res[0]['monto'];
    }

    static function getDescuento($id_persona, $id_concepto, $id_ciclo_escolar)
    {
        $query = "SELECT IFNULL(SUM(monto), 0) AS monto FROM cuentas_descuento
        WHERE id_persona = $id_persona AND id_concepto = $id_concepto AND id_ciclo_escolar = $id_ciclo_escolar";
        $res = Database::select($query);
        return $res[0]['monto'];
    }

    static function getMontoPagado($id_persona, $id_concepto, $id_ciclo_escolar)
    {
        $query = "SELECT IFNULL(SUM(monto), 0) AS pagado FROM cuentas_pago
        WHERE id_persona = $id_persona AND id_concepto = $id_concepto AND id_ciclo_escolar = $id_ciclo_escolar;";
        $res = Database::select($query);
        return $res[0]['pagado'];
    }

    static function getFechaLimite($id_concepto, $mes, $id_ciclo_escolar)
    {
        $query = "SELECT CONCAT(IF($mes <= 6, CAST(YEAR(fecha_inicio) AS CHAR),
            YEAR(DATE_ADD(fecha_inicio, INTERVAL 1 YEAR))),
            '-', MONTH(fecha_limite),
            '-', DAY(fecha_limite)) AS fecha_limite
            FROM cuentas_fechas
            JOIN ciclo_escolar
            WHERE id_concepto = $id_concepto AND mes = $mes AND id_ciclo_escolar = $id_ciclo_escolar";
        $res = Database::select($query);
        return $res[0]['fecha_limite'];
    }

    static function getMontoRecargo($id_concepto)
    {
        $query = "SELECT recargo_diario FROM cuentas_concepto WHERE id_concepto = 2";
        $res = Database::select($query);
        return $res[0]['recargo_diario'];
    }

    static function generarNuevoRecibo()
    {
        /** Regresa un string con el nuevo número de recibo */
        $query = "SELECT MAX(recibo) AS ultimo FROM cuentas_pago";
        $res = Database::select($query);
        $ultimo = $res[0]['ultimo'];

        $ultimo = $ultimo * 1.0 + 1;
        return $ultimo;
    }

    static function getPagosDeRecibo($recibo)
    {
        $query = "SELECT * FROM cuentas_pago WHERE recibo = $recibo";
        $res = Database::select($query);
        if(is_array($res))
        {
            $pagos = array();
            foreach($res as $pag)
            {
                $pago = new Pago($pag['id_pago']);
                array_push($pagos, $pago);
            }
        }
        return $pagos;
    }

    static function getTotalRecibo($recibo)
    {
        $query = "SELECT SUM(monto) AS pagado FROM cuentas_pago WHERE recibo = $recibo";
        $res = Database::select($query);
        return $res[0]['pagado'];
    }

    static function getDescuentoRecibo($recibo)
    {
        $query = "SELECT SUM(descuento) AS descuento FROM cuentas_pago
            JOIN cuentas_cuenta ON cuentas_cuenta.id_cuenta = cuentas_pago.id_cuenta
            WHERE recibo = $recibo";
        $res = Database::select($query);
        return $res[0]['descuento'];
    }

    static function getConceptos()
    {
        $query = "SELECT * FROM cuentas_concepto";
        return Database::select($query);
    }

    static function getDescuentosCiclo($ciclo)
    {
        /** PENDIENTE. FALTA FILTRAR POR CICLO Y PROBAR */
        /** PENDIENTE. FALTA FILTRAR POR CICLO Y PROBAR */
        /** PENDIENTE. FALTA FILTRAR POR CICLO Y PROBAR */
        /** PENDIENTE. FALTA FILTRAR POR CICLO Y PROBAR */
        /** PENDIENTE. FALTA FILTRAR POR CICLO Y PROBAR */
        $query = "SELECT id_descuento AS ID,
                CONCAT(alumno.nombres, ' ', alumno.apellido_paterno, ' ', alumno.apellido_materno) AS alumno,
                concepto, cuentas_descuento.monto, cuentas_descuento.fecha,
				CONCAT(usuario.nombres, ' ', usuario.apellido_materno, ' ', usuario.apellido_materno) AS usuario
            FROM cuentas_descuento
            LEFT JOIN cuentas_cuenta ON cuentas_descuento.id_cuenta = cuentas_cuenta.id_cuenta
            LEFT JOIN persona AS alumno ON cuentas_cuenta.id_persona = alumno.id_persona
            LEFT JOIN cuentas_concepto ON cuentas_cuenta.id_concepto = cuentas_concepto.id_concepto
			LEFT JOIN persona AS usuario ON usuario.id_persona = cuentas_descuento.id_usuario
            WHERE cuentas_cuenta.id_ciclo_escolar = $ciclo";
        return Database::select($query);
        /** PENDIENTE. FALTA FILTRAR POR CICLO Y PROBAR */
        /** PENDIENTE. FALTA FILTRAR POR CICLO Y PROBAR */
        /** PENDIENTE. FALTA FILTRAR POR CICLO Y PROBAR */
        /** PENDIENTE. FALTA FILTRAR POR CICLO Y PROBAR */
        /** PENDIENTE. FALTA FILTRAR POR CICLO Y PROBAR */
    }

    static function numtoletras($xcifra)
    {
        $xarray = array(0 => "Cero",
            1 => "UN", "DOS", "TRES", "CUATRO", "CINCO", "SEIS", "SIETE", "OCHO", "NUEVE",
            "DIEZ", "ONCE", "DOCE", "TRECE", "CATORCE", "QUINCE", "DIECISEIS", "DIECISIETE", "DIECIOCHO", "DIECINUEVE",
            "VEINTI", 30 => "TREINTA", 40 => "CUARENTA", 50 => "CINCUENTA", 60 => "SESENTA", 70 => "SETENTA", 80 => "OCHENTA", 90 => "NOVENTA",
            100 => "CIENTO", 200 => "DOSCIENTOS", 300 => "TRESCIENTOS", 400 => "CUATROCIENTOS", 500 => "QUINIENTOS", 600 => "SEISCIENTOS", 700 => "SETECIENTOS", 800 => "OCHOCIENTOS", 900 => "NOVECIENTOS"
        );

        $xcifra = trim($xcifra);
        $xlength = strlen($xcifra);
        $xpos_punto = strpos($xcifra, ".");
        $xaux_int = $xcifra;
        $xdecimales = "00";
        if (!($xpos_punto === false)) {
            if ($xpos_punto == 0) {
                $xcifra = "0" . $xcifra;
                $xpos_punto = strpos($xcifra, ".");
            }
            $xaux_int = substr($xcifra, 0, $xpos_punto); // obtengo el entero de la cifra a covertir
            $xdecimales = substr($xcifra . "00", $xpos_punto + 1, 2); // obtengo los valores decimales
        }

        $XAUX = str_pad($xaux_int, 18, " ", STR_PAD_LEFT); // ajusto la longitud de la cifra, para que sea divisible por centenas de miles (grupos de 6)
        $xcadena = "";
        for ($xz = 0; $xz < 3; $xz++) {
            $xaux = substr($XAUX, $xz * 6, 6);
            $xi = 0;
            $xlimite = 6; // inicializo el contador de centenas xi y establezco el límite a 6 dígitos en la parte entera
            $xexit = true; // bandera para controlar el ciclo del While
            while ($xexit) {
                if ($xi == $xlimite) { // si ya llegó al límite máximo de enteros
                    break; // termina el ciclo
                }

                $x3digitos = ($xlimite - $xi) * -1; // comienzo con los tres primeros digitos de la cifra, comenzando por la izquierda
                $xaux = substr($xaux, $x3digitos, abs($x3digitos)); // obtengo la centena (los tres dígitos)
                for ($xy = 1; $xy < 4; $xy++) { // ciclo para revisar centenas, decenas y unidades, en ese orden
                    switch ($xy) {
                        case 1: // checa las centenas
                            if (substr($xaux, 0, 3) < 100) { // si el grupo de tres dígitos es menor a una centena ( < 99) no hace nada y pasa a revisar las decenas

                            } else {
                                $key = (int) substr($xaux, 0, 3);
                                if (TRUE === array_key_exists($key, $xarray)){  // busco si la centena es número redondo (100, 200, 300, 400, etc..)
                                    $xseek = $xarray[$key];
                                    $xsub = self::subfijo($xaux); // devuelve el subfijo correspondiente (Millón, Millones, Mil o nada)
                                    if (substr($xaux, 0, 3) == 100)
                                        $xcadena = " " . $xcadena . " CIEN " . $xsub;
                                    else
                                        $xcadena = " " . $xcadena . " " . $xseek . " " . $xsub;
                                    $xy = 3; // la centena fue redonda, entonces termino el ciclo del for y ya no reviso decenas ni unidades
                                }
                                else { // entra aquí si la centena no fue numero redondo (101, 253, 120, 980, etc.)
                                    $key = (int) substr($xaux, 0, 1) * 100;
                                    $xseek = $xarray[$key]; // toma el primer caracter de la centena y lo multiplica por cien y lo busca en el arreglo (para que busque 100,200,300, etc)
                                    $xcadena = " " . $xcadena . " " . $xseek;
                                } // ENDIF ($xseek)
                            } // ENDIF (substr($xaux, 0, 3) < 100)
                            break;
                        case 2: // checa las decenas (con la misma lógica que las centenas)
                            if (substr($xaux, 1, 2) < 10) {

                            } else {
                                $key = (int) substr($xaux, 1, 2);
                                if (TRUE === array_key_exists($key, $xarray)) {
                                    $xseek = $xarray[$key];
                                    $xsub = self::subfijo($xaux);
                                    if (substr($xaux, 1, 2) == 20)
                                        $xcadena = " " . $xcadena . " VEINTE " . $xsub;
                                    else
                                        $xcadena = " " . $xcadena . " " . $xseek . " " . $xsub;
                                    $xy = 3;
                                }
                                else {
                                    $key = (int) substr($xaux, 1, 1) * 10;
                                    $xseek = $xarray[$key];
                                    if (20 == substr($xaux, 1, 1) * 10)
                                        $xcadena = " " . $xcadena . " " . $xseek;
                                    else
                                        $xcadena = " " . $xcadena . " " . $xseek . " Y ";
                                } // ENDIF ($xseek)
                            } // ENDIF (substr($xaux, 1, 2) < 10)
                            break;
                        case 3: // checa las unidades
                            if (substr($xaux, 2, 1) < 1) { // si la unidad es cero, ya no hace nada

                            } else {
                                $key = (int) substr($xaux, 2, 1);
                                $xseek = $xarray[$key]; // obtengo directamente el valor de la unidad (del uno al nueve)
                                $xsub = self::subfijo($xaux);
                                $xcadena = " " . $xcadena . " " . $xseek . " " . $xsub;
                            } // ENDIF (substr($xaux, 2, 1) < 1)
                            break;
                    } // END SWITCH
                } // END FOR
                $xi = $xi + 3;
            } // ENDDO

            if (substr(trim($xcadena), -5, 5) == "ILLON") // si la cadena obtenida termina en MILLON o BILLON, entonces le agrega al final la conjuncion DE
                $xcadena.= " DE";

            if (substr(trim($xcadena), -7, 7) == "ILLONES") // si la cadena obtenida en MILLONES o BILLONES, entoncea le agrega al final la conjuncion DE
                $xcadena.= " DE";

            // ----------- esta línea la puedes cambiar de acuerdo a tus necesidades o a tu país -------
            if (trim($xaux) != "") {
                switch ($xz) {
                    case 0:
                        if (trim(substr($XAUX, $xz * 6, 6)) == "1")
                            $xcadena.= "UN BILLON ";
                        else
                            $xcadena.= " BILLONES ";
                        break;
                    case 1:
                        if (trim(substr($XAUX, $xz * 6, 6)) == "1")
                            $xcadena.= "UN MILLON ";
                        else
                            $xcadena.= " MILLONES ";
                        break;
                    case 2:
                        if ($xcifra < 1) {
                            $xcadena = "CERO PESOS $xdecimales/100 M.N.";
                        }
                        if ($xcifra >= 1 && $xcifra < 2) {
                            $xcadena = "UN PESO $xdecimales/100 M.N. ";
                        }
                        if ($xcifra >= 2) {
                            $xcadena.= " PESOS $xdecimales/100 M.N. "; //
                        }
                        break;
                } // endswitch ($xz)
            } // ENDIF (trim($xaux) != "")
            // ------------------      en este caso, para México se usa esta leyenda     ----------------
            $xcadena = str_replace("VEINTI ", "VEINTI", $xcadena); // quito el espacio para el VEINTI, para que quede: VEINTICUATRO, VEINTIUN, VEINTIDOS, etc
            $xcadena = str_replace("  ", " ", $xcadena); // quito espacios dobles
            $xcadena = str_replace("UN UN", "UN", $xcadena); // quito la duplicidad
            $xcadena = str_replace("  ", " ", $xcadena); // quito espacios dobles
            $xcadena = str_replace("BILLON DE MILLONES", "BILLON DE", $xcadena); // corrigo la leyenda
            $xcadena = str_replace("BILLONES DE MILLONES", "BILLONES DE", $xcadena); // corrigo la leyenda
            $xcadena = str_replace("DE UN", "UN", $xcadena); // corrigo la leyenda
        } // ENDFOR ($xz)
        return trim($xcadena);
    }

    static function subfijo($xx)
    { // esta función regresa un subfijo para la cifra
        $xx = trim($xx);
        $xstrlen = strlen($xx);
        if ($xstrlen == 1 || $xstrlen == 2 || $xstrlen == 3)
            $xsub = "";
        //
        if ($xstrlen == 4 || $xstrlen == 5 || $xstrlen == 6)
            $xsub = "MIL";
        //
        return $xsub;
    }

    static function getNombreMes($mes)
    {
        switch ($mes)
        {
            case 1: return 'Agosto';        break;
            case 2: return 'Septiembre';    break;
            case 3: return 'Octubre';       break;
            case 4: return 'Noviembre';     break;
            case 5: return 'Diciembre';     break;
            case 6: return 'Julio';         break;
            case 7: return 'Enero';         break;
            case 8: return 'Febrero';       break;
            case 9: return 'Marzo';         break;
            case 10: return 'Abril';        break;
            case 11: return 'Mayo';         break;
            case 12: return 'Junio';        break;
        }
    }
} 