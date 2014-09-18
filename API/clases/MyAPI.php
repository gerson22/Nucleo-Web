<?php
/**
 * Created by PhpStorm.
 * User: Yozki
 * Date: 8/26/14
 * Time: 6:33 PM
 */

require_once("API.php");

class MyAPI extends API
{
    public function __construct($request, $origin)
    {
        parent::__construct($request);
    }
} 