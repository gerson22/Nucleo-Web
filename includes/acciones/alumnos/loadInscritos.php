<?php
/**
 * Created by PhpStorm.
 * User: Yozki
 * Date: 5/26/14
 * Time: 3:36 PM
 */

$id_modulo = 5; // Alumnos - Ver inscritos
include_once("../../../includes/validar_admin.php");
include_once("../../../includes/clases/class_lib.php");
include_once("../../../includes/validar_acceso.php");
extract($_GET);
# id_ciclo_escolar

$ciclo_escolar = new CicloEscolar($id_ciclo_escolar);
$alumnos = $ciclo_escolar->getAlumnosInscritosPagados();

$json = array();

if(is_array($alumnos))
{
    foreach($alumnos as $alumno)
    {
        $temp = array();
        array_push($temp, $alumno['matricula']);
        array_push($temp, $alumno['apellido_paterno']);
        array_push($temp, $alumno['apellido_materno']);
        array_push($temp, $alumno['nombres']);
        array_push($temp, $alumno['area']);
        array_push($temp, $alumno['grado']);
        array_push($temp, $alumno['grupo']);
        $link = '<a href="perfil.php?id_alumno='.$alumno['id_persona'].'" ><img src="/media/iconos/icon_profile.png" alt="X" /></a>';
        array_push($temp, $link);
        array_push($json, $temp);
    }
}

echo json_encode(array("aaData" => $json));