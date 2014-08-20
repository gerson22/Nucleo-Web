<?php
/**
 * Created by PhpStorm.
 * User: Gustavo
 * Date: 20/08/14
 * Time: 10:44 AM
 */

include_once("../../validar_admin.php");
include_once("../../clases/class_lib.php");
extract($_POST);
# id_grado
# id_materia
# ciclo

$grado = new Grado($id_grado);
if($grado->eliminarMateria($id_materia, $ciclo)) echo 1;