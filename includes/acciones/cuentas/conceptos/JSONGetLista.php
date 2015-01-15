<?php
/**
 * Created by PhpStorm.
 * User: Yozki
 * Date: 11/01/2015
 * Time: 02:03 PM
 */

include_once("../../../clases/class_lib.php");
$conceptos = Concepto::getLista();
echo json_encode($conceptos);