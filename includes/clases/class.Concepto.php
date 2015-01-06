<?php
include_once("class.Database.php");

class Concepto
{
    public $id_concepto;
    public $concepto;
    public $monto_sugerido;

    function __construct($id_concepto)
    {
        $concepto = Database::select("SELECT * FROM cuentas_concepto WHERE id_concepto = $id_concepto");
        $concepto = $concepto[0];
        $this->id_concepto      = $concepto['id_concepto'];
        $this->concepto         = $concepto['concepto'];
        $this->monto_sugerido   = $concepto['monto_sugerido'];
    }

    function update()
    {
        $query = "UPDATE cuentas_concepto SET concepto = '$this->concepto', monto_sugerido = $this->monto_sugerido
                WHERE id_concepto = $this->id_concepto";
        return Database::update($query);
    }

    function getMontoSugerido($id_area)
    {
        $query = "SELECT monto FROM cuentas_monto WHERE id_concepto = $this->id_concepto AND id_area = $id_area";
        $res = Database::select($query);
        return $res[0]['monto'];
    }

    # Métodos estáticos
    static function getLista()
    {
        return Database::select("SELECT * FROM cuentas_concepto");
    }

    static function insert($concepto, $montos)
    {
        print_r($montos);
        $id_concepto = Database::insert("INSERT INTO cuentas_concepto SET concepto = '$concepto', genera_recargos = 0, recargo_diario = 0");

        foreach($montos as $id_area => $monto)
        {
            $query = "INSERT INTO cuentas_monto VALUES($id_concepto, $id_area, $monto)";
            echo $query;
            Database::insert($query);
        }

        return true;
    }
}