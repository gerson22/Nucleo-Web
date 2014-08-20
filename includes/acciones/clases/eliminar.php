<?php
/**
 * Created by PhpStorm.
 * User: Gustavo
 * Date: 13/08/14
 * Time: 03:14 PM
 */

$id_modulo = 28; // Grupos - Nuevo
include_once("../../validar_admin.php");
include_once("../../clases/class_lib.php");
include_once("../../validar_acceso.php");
extract($_POST);
# id_clase

if(isset($id_clase))
{
    if(Clase::eliminar($id_clase)) echo 1;
}