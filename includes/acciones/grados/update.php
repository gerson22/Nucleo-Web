<?php
include_once("../../validar_admin.php");
include_once("../../clases/class_lib.php");
extract($_POST);
#id_gradoVal
#gradoVal

$grado = new Grado($id_gradoVal);

if($gradoVal == "")
{
    header("Location: ../../../admin/admin/grados/modificar.php?error=1");
}

$grado->grado = $gradoVal;
if($grado->update())
{
    echo 1;
}