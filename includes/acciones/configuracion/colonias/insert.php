<?php
/**
 * Created by PhpStorm.
 * User: Yozki
 * Date: 9/30/14
 * Time: 3:04 PM
 */

$id_modulo = 45; // 45 - Configuración - Colonias
include_once("../../../validar_admin.php");
include_once("../../../clases/class_lib.php");
include_once("../../../validar_acceso.php");
extract($_POST);
# nombre

if(Colonia::insert($nombre) > 0) echo 1;
else echo 0;