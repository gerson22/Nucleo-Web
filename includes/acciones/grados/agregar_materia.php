<?php
/**
 * Created by PhpStorm.
 * User: Gustavo
 * Date: 23/08/14
 * Time: 01:39 PM
 */

include_once("../../validar_admin.php");
include_once("../../clases/class_lib.php");
extract($_POST);
# id_grado
# id_ciclo
# id_materia
# nuevasClases[{id_grupo, id_docente}]

if(isset($id_grado) && isset($id_ciclo) && isset($id_materia))
{
    $grado = new Grado($id_grado);

    // 1. Agregar la materia
    $grado->asignarMateria($id_materia, $id_ciclo);

    // 2. Agregar las clases a los grupos derivados
    $nuevasClases = str_replace('\"','"', $nuevasClases);
    $nuevasClases = json_decode($nuevasClases);
    if(is_array($nuevasClases))
    {
        foreach($nuevasClases as $nuevaClase)
        {
            $grupo = new Grupo($nuevaClase->id_grupo);
            $grupo->nuevaClase($id_materia, $nuevaClase->id_docente);
        }
    }

    echo 1;
}