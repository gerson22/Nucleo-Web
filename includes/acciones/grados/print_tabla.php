<?php
include_once("../../clases/class_lib.php");
extract($_POST);
// id_reticula

$grados = Grado::getListaReticula($id_reticula);
if(is_array($grados))
{
    foreach($grados as $grado)
    {
        echo "
            <tr>
                <td>".$grado['id_grado']."</td>
                <td>".$grado['grado']."</td>
                <td>".$grado['reticula']."</td>
            </tr>
        ";
    }
}