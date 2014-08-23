<?php
include_once("clases/class_lib.php");
extract($_POST);

if(isset($matriculaVal) && isset($passwordVal))
{
    $persona = Persona::login($matriculaVal, $passwordVal);
    if($persona)
    {
        if($persona->id_persona != 0)
        {
            session_start();

            # Datos generales (Cualquier tipo de persona)
            $_SESSION['id_persona']         = $persona->id_persona;
            $_SESSION['matricula']          = $persona->matricula;
            $_SESSION['apellido_paterno']   = $persona->apellido_paterno;
            $_SESSION['apellido_materno']   = $persona->apellido_materno;
            $_SESSION['nombres']            = $persona->nombres;
            $_SESSION['password']           = $persona->password;
            $_SESSION['tipo_persona']       = $persona->tipo_persona;

            session_write_close();
            header('Location: ../index.php');
        }
    }
    else
    {
        header('Location: ../login.php?error=1');
    }
}
else
{
    header('Location: ../login.php?error=1');
}