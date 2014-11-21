<?php
/**
 * Created by PhpStorm.
 * User: Yozki
 * Date: 11/19/14
 * Time: 6:23 PM
 */

$id_modulo = 48; // 48 - Configuración - Niveles
include_once("../../../validar_admin.php");
include_once("../../../clases/class_lib.php");
include_once("../../../validar_acceso.php");
extract($_POST);
# id_nivel

if(Area::eliminar($id_nivel)) echo 1;
else echo 0;