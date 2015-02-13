<?php
$id_modulo = 15; // Ciclos escolares - Nuevo
include_once("../../validar_admin.php");
include_once("../../clases/class_lib.php");
include_once("../../validar_acceso.php");
extract($_POST);

if(CicloEscolar::insert($fecha_inicio, $fecha_fin)) echo 1;
else echo 0;