<?php
include_once("../../validar_admin.php");
include_once("../../clases/class_lib.php");
extract($_GET);
# id_email

$email = new Email($id_email);
$id_persona = $email->id_persona;

if($email->eliminar())
{
    header('Location: /admin/maestros/perfil.php?id_maestro='.$id_persona);
    exit();
}
else
{
    header('Location: /admin/maestros/perfil.php?id_maestro='.$id_persona.'&error=1');
    exit();
}