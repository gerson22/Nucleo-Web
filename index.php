<?php
include("includes/validar_sesion.php");
include_once("includes/clases/class_lib.php");
session_start();
$usuario = new Persona($_SESSION['id_persona']);
?>
<!DOCTYPE html>

<html class="no-js">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Plataforma Meze</title>
        <meta name="description" content="Plataforma escuela inteligente">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="favicon.ico">
        <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="/estilo/bootstrap.min.css">
        <link rel="stylesheet" href="/estilo/bootstrap-theme.min.css">
        <link rel="stylesheet" href="/estilo/index.css" />
    </head>
    <body>
        <div id="wrapper">
            <div class="container">
            <?php
                switch($usuario->tipo_persona)
                {
                    case 3: include_once("indexAdmin.php"); break;
                    case 2: include_once("indexDocente.php"); break;
                    case 1: include_once("indexAlumno.php"); break;
                    default: break;
                }
            ?>
            </div>
        </div>

        <!-- SCRIPTS -->
        <script src="js/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>
    </body>
</html>