<?php
include_once("../../validar_sesion.php");
include_once("../../clases/class_lib.php");
extract($_POST);
# id_persona
# passwordVal
# password2Val

if(!isset($passwordVal) || !isset($password2Val)) { echo 0; exit(); }
if((strlen($passwordVal) == 0) || strlen($password2Val) == 0) { echo 0; exit(); }

$persona = new Persona($id_persona);

if($persona->cambiarPassword($passwordVal))
{
    echo 1; exit();
}
else
{
    echo 0; exit();
}