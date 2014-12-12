<?php
/**
 * Created by PhpStorm.
 * User: Yozki
 * Date: 12/11/14
 * Time: 9:09 PM
 */

include_once("../../validar_admin.php");
include_once("../../clases/class_lib.php");
extract($_POST);
# id_persona
# peso
# talla
# IMC

$persona = new Persona($id_persona);
$persona->nuevoRegistroNutricion($peso, $talla, $IMC);
