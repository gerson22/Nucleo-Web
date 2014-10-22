<?php
/**
 * Created by PhpStorm.
 * User: Yozki
 * Date: 10/22/14
 * Time: 2:08 PM
 */

$id_modulo = 34; // Materias - Nueva
include_once("../../validar_admin.php");
include_once("../../clases/class_lib.php");
include_once("../../validar_acceso.php");

extract($_POST);
# id_materia
# nombre

$materia = new Materia($id_materia);
$materia->materia = $nombre;
if($materia->update()) echo 1;