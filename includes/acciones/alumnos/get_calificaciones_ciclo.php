<?php
/** Created by Gustavo Carrillo
 * gus@yozki.net
 * @yozki
 */

include_once("../../validar_admin.php");
include_once("../../clases/class_lib.php");
extract($_GET);
# id_persona
# id_ciclo

$alumno = new Alumno($id_persona);
$materias  = $alumno->getMateriasCiclo($id_ciclo);
$area = $alumno->getArea();
$area = new Area($area['id_area']);
$parciales = $area->no_parciales;

$json = array();

if(is_array($materias))
{
    foreach($materias as $materia)
    {
        $materiaARR = array();

        $materiaARR[] = $materia['materia'];

        $promedio = 0;
        $periodos_calificados = 0;
        for($p = 1; $p <= $parciales; $p++)
        {
            $calificacion = $alumno->getCalificacion($materia['id_clase'], $p);
            if($calificacion) $periodos_calificados = $periodos_calificados + 1;
            $promedio += $calificacion;
            $materiaARR[] = $calificacion;
        }

        if($periodos_calificados == 0) $periodos_calificados = 1;
        $materiaARR[] = $promedio / $periodos_calificados;


        $json[] = $materiaARR;
    }

    $promedios = array();
    $promedios[] = "Promedios";
    $promedios[] = $alumno->getPromedioParcial(1);
    $promedios[] = $alumno->getPromedioParcial(2);
    $promedios[] = $alumno->getPromedioParcial(3);
    $promedios[] = $alumno->getPromedioParcial(4);
    $promedios[] = $alumno->getPromedioParcial(5);
    $promedios[] = " ";
    $json[] = $promedios;
    echo json_encode(array("aaData" => $json));
}
else echo json_encode(array("aaData" => ""));