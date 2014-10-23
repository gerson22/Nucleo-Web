<?php
include_once("../../validar_admin.php");
include_once("../../clases/class_lib.php");
extract($_POST);
# apellido_paterno
# apellido_materno
# nombres
# sexo
# calle
# numero
# colonia
# CP
# titulo
# egresado
# ano
# telefonos[]

$telefonos = str_replace('\"','"', $telefonos);
$telefonos = json_decode($telefonos);

if(!isset($apellido_paterno) || !isset($apellido_materno) || !isset($nombres))
{
    echo 1; // Datos vacios o incompletos
    exit();
} 
else
{
    $id_maestro = Maestro::insert($apellido_paterno, $apellido_materno, $nombres, $sexo);
    if(!is_null($id_maestro))
    {
        $maestro = new Maestro($id_maestro);
        if(!is_null($calle) && $calle != "")
        {
            $maestro->setDireccion($calle, $numero, $colonia, $CP);
        }
        if(!is_null($titulo) && !is_null($egresado) && !is_null($ano))
        {
            if(!$maestro->setEscolaridad($titulo, $egresado, $ano)) echo 4; // Error al asignarle escolaridad
        }
        if(is_array($telefonos))
        {
            foreach($telefonos as $telefono)
            {
                $maestro->agregarTelefono($telefono->telefono, $telefono->tipo);
            }
        }
    }
    else
    {
        echo 2; // Error al agregar un nuevo maestro
        exit();
    }
}
echo 0; // No errores