<?php
include_once("class.Database.php");
include_once("class.CicloEscolar.php");

class Grado
{
    public $id_grado;
    public $grado;
    public $id_area;

    function __construct($id_grado)
    {
        $grado = Database::select("SELECT * FROM grado WHERE id_grado = $id_grado");
        $grado = $grado[0];
        $this->id_grado = $grado['id_grado'];
        $this->grado    = $grado['grado'];
        $this->id_area  = $grado['id_area'];
    }

    function asignarMateria($id_materia, $id_ciclo)
    {
        /** IMPORTANTE. Esta función no crea las clases necesarias para la nueva materia, una por cada grupo derivado
         * Se deben de crear dichas clases independientemente */
        $query = "INSERT INTO grado_materia SET id_grado = $this->id_grado, id_materia = $id_materia,
            id_ciclo_escolar = $id_ciclo";
        return Database::insert($query);
    }

    function getGruposActuales()
    {
        $ciclo_actual = CicloEscolar::getActual();
        $query = "SELECT id_grupo, grupo FROM grupo 
        WHERE id_ciclo_escolar = $ciclo_actual->id_ciclo_escolar AND id_grado = $this->id_grado";
        return Database::select($query);
    }

    function getGruposCiclo($id_ciclo)
    {
        $query = "SELECT id_grupo, grupo FROM grupo
          WHERE id_ciclo_escolar = $id_ciclo AND id_grado = $this->id_grado";
        return Database::select($query);
    }

    function getArea()
    {
        $query = "SELECT * FROM grado
            JOIN area ON grado.id_area = area.id_area
            WHERE id_grado = $this->id_grado";
        $res = Database::select($query);
        return $res[0]['area'];
    }

    function getMateriasActuales()
    {
        $ciclo_escolar = CicloEscolar::getActual();
        $query = "SELECT materia.id_materia, materia FROM grado_materia 
            JOIN materia ON grado_materia.id_materia = materia.id_materia 
            WHERE id_ciclo_escolar = $ciclo_escolar->id_ciclo_escolar AND id_grado = $this->id_grado";
        return Database::select($query);
    }

    function getMateriasCiclo($ciclo)
    {
        $query = "SELECT materia.id_materia, materia FROM grado_materia
            JOIN materia ON grado_materia.id_materia = materia.id_materia
            WHERE id_ciclo_escolar = $ciclo AND id_grado = $this->id_grado";
        return Database::select($query);
    }

    function update()
    {
        $query = "UPDATE grado SET grado = '$this->grado' WHERE id_grado = $this->id_grado";
        return Database::update($query);
    }

    function eliminarMateria($id_materia, $ciclo)
    {
        // 1. Eliminar la entrada en grado_materia
        $query = "DELETE FROM grado_materia WHERE id_materia = $id_materia AND id_ciclo_escolar = $ciclo";
        if(Database::update($query))
        {
            // 2. Eliminar las clases con la materia de la lista de grupos del grado y ciclo
            $grupos = $this->getGruposCiclo($ciclo); // [{id_grupo, grupo}]
            if(is_array($grupos))
            {
                foreach($grupos as $grupo)
                {
                    $query = "DELETE FROM clase WHERE id_grupo = ".$grupo['id_grupo']." AND id_materia = $id_materia";
                    echo $query;
                    Database::update($query);
                }
            }
        }
    }

    function getAreaObj()
    {
        $query = "SELECT * FROM grado
            JOIN area ON grado.id_area = area.id_area
            WHERE id_grado = $this->id_grado";
        $res = Database::select($query);
        return new Area($res[0]['id_area']);
    }

    # Métodos estáticos
    static function getLista()
    {
        return Database::select("SELECT grado.*, area FROM grado JOIN area ON grado.id_area = area.id_area");
    }

    static function insert($ciclo, $area, $grado, $materias)
    {
        $id_grado = Database::insert("INSERT INTO grado SET grado = '$grado', id_area = $area");
        $grado = new Grado($id_grado);
        foreach($materias as $materia)
        {
            $grado->asignarMateria($materia, $ciclo);
        }
        return $id_grado;
    }
}