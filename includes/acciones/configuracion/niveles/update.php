<?php
/**
 * Created by PhpStorm.
 * User: Yozki
 * Date: 11/21/14
 * Time: 4:51 PM
 */

$id_modulo = 48; // 48 - Configuración - Niveles
include_once("../../../validar_admin.php");
include_once("../../../clases/class_lib.php");
include_once("../../../validar_acceso.php");
extract($_POST);
# id_nivel
# nombre
# prefijo
# no_parciales

$nivel = new Area($id_nivel);
$nivel->area            = $nombre;
$nivel->prefijo         = $prefijo;
$nivel->no_parciales    = $no_parciales;

if($nivel->update()) echo 1;
else echo 0;