<?php
/**
 * Created by PhpStorm.
 * User: Yozki
 * Date: 10/4/14
 * Time: 3:44 PM
 */

$id_modulo = 47; // 47 - Configuración - Ocupaciones
include_once("../../../validar_admin.php");
include_once("../../../clases/class_lib.php");
include_once("../../../validar_acceso.php");
extract($_POST);
# nombre

if(Tutor::insertOcupacion($nombre) > 0) echo 1;
else echo 0;