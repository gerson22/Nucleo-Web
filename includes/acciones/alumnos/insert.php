<?php
include_once("../../validar_admin.php");
include_once("../../clases/class_lib.php");
extract($_POST);
# apellido_materno
# apellido_paterno
# nombres
# sexo
# area
# grado
# grupo
# calle
# numero
# colonia
# CP
# club
# CURP
# beca_tipo
# beca_subtipo
# beca_porcentaje
# tutores[]
# id_ciclo_escolar
# papeleria_entregada

$tutores = str_replace('\"','"', $tutores);
$tutores = json_decode($tutores);

$papeleria_entregada = str_replace('\"','"', $papeleria_entregada);
$papeleria_entregada = json_decode($papeleria_entregada);

# exit();

if(!isset($apellido_paterno) || !isset($apellido_materno) || !isset($nombres) || !isset($grupo) || !isset($grado))
{
    header('Location: /admin/alumnos/nuevo.php?error=1');
    exit();
}
else
{
    /** Paso 1. Inscribir al alumno */
    $id_alumno = Alumno::insert($apellido_paterno, $apellido_materno, $nombres, $sexo, $id_ciclo_escolar);

    if($id_alumno != 0)
    {
        $alumno = new Alumno($id_alumno);
        /** Paso 2. Inscribirlo al grupo */
        $alumno->inscribirEnGrupo($grupo);

        /** Dirección */
        if(!is_null($calle) && ($calle !== '')){ $alumno->setDireccion($calle, $numero, $colonia, $CP); }

        /** Paso 3. Asignar tutores */
        if(is_array($tutores))
        {
            foreach($tutores as $tutor)
            {
                $alumno->asignarTutor($tutor->id_tipo_tutor, $tutor->nombres, $tutor->calle,
                    $tutor->numero, $tutor->colonia, $tutor->CP, $tutor->telefonos, $tutor->celular,
                    $tutor->ocupacion, $tutor->lugarTrabajo);
            }
        }

        /** Paso 4. Guardar el club deportivo y CURP*/
        if(!is_null($club) && ($club !== '')){ $alumno->setClubDeportivo($club); }
        if(!is_null($CURP) && ($CURP !== '')){ $alumno->setCURP($CURP); }

        /** Paso 5. Beca */
        if(!is_null($beca_porcentaje))
        {
            $alumno->asignarBeca($beca_porcentaje, $beca_subtipo, $id_ciclo_escolar);
        }

        /** Paso 6. Registrar papeleria entregada */
        if(is_array($papeleria_entregada))
        {
            foreach($papeleria_entregada as $documento)
            {
                $alumno->agregarDocumento($documento->id_documento, $documento->original, $documento->copia);
            }
        }

        /** Paso 7. Crearle la cuenta de inscripción. */
        $alumno->crearCuentaInscripcion($id_ciclo_escolar);

        /** Paso 8. Crearle las cuentas de colegiaturas */
        $alumno->crearCuentasColegiaturas($id_ciclo_escolar);

        echo $id_alumno;
        exit();
    }
    else
    {
        echo "2. El alumno ya se encuentra inscrito";
        exit();
    }
}