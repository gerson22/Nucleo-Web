<?php
/**
 * Created by PhpStorm.
 * User: Yozki
 * Date: 10/4/14
 * Time: 11:54 AM
 */

include_once("../../validar_admin.php");
include_once("../../clases/class_lib.php");
extract($_POST);
# id_persona
# club
# CURP

$alumno = new Alumno($id_persona);

$alumno->setClubDeportivo($club);
$alumno->setCURP($CURP);