<?php
/**
 * Created by PhpStorm.
 * User: Yozki
 * Date: 10/4/14
 * Time: 1:08 PM
 */

$id_modulo = 46; // 46 - Configuración - Papeleria
include_once("../../../validar_admin.php");
include_once("../../../clases/class_lib.php");
include_once("../../../validar_acceso.php");
extract($_POST);
# nombre

if(Documento::insert($nombre) > 0) echo 1;
else echo 0;