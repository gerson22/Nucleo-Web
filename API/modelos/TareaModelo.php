<?php

class TareaModelo
{
    public function __construct($id_tarea)
    {

    }

    public static function getLista()
    {
        $query = 'SELECT * FROM tarea';
        return APIDatabase::select($query);
    }

    public static function addTarea($id_clase, $descripcion, $fecha_entrega)
    {
        $query = "INSERT INTO tarea VALUES(null, $id_clase, '$descripcion', NOW(), '$fecha_entrega');";
        $id_tarea = APIDatabase::insert($query);
        return new self($id_tarea);
    }
}