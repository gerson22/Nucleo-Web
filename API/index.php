<?php
/**
 * Created by PhpStorm.
 * User: Yozki
 * Date: 8/26/14
 * Time: 6:36 PM
 */

include_once("classes/MyAPI.php");
include_once("classes/APIDatabase.php");
include_once("classes/Acceso.php");

/** AUTOLOADER de clases */
spl_autoload_register('apiAutoload');

function apiAutoload($classname)
{
    if (preg_match('/[a-zA-Z]+Controlador$/', $classname))
    {
        /** @noinspection PhpIncludeInspection */
        include __DIR__ . '/controladores/' . $classname . '.php';
        return true;
    } elseif (preg_match('/[a-zA-Z]+Modelo$/', $classname))
    {
        /** @noinspection PhpIncludeInspection */
        include __DIR__ . '/modelos/' . $classname . '.php';
        return true;
    } elseif (preg_match('/[a-zA-Z]+View$/', $classname))
    {
        /** @noinspection PhpIncludeInspection */
        include __DIR__ . '/views/' . $classname . '.php';
        return true;
    }
    return null;
}

if (!array_key_exists('HTTP_ORIGIN', $_SERVER))
{
    $_SERVER['HTTP_ORIGIN'] = $_SERVER['SERVER_NAME'];
}

try
{
    $API = new MyAPI($_REQUEST['request'], $_SERVER['HTTP_ORIGIN']);
    echo json_encode($API->processAPI());
} catch (Exception $e) {
    echo Array('error' => $e->getMessage());
}