<?php
/**
 * Created by PhpStorm.
 * User: Yozki
 * Date: 11/01/2015
 * Time: 01:31 PM
 */

include_once("../../clases/class_lib.php");
$areas = Area::getLista();
echo json_encode($areas);