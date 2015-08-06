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
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
		<link rel="stylesheet" type="text/css" href="plugins/assets/css/animations.css">
		<link rel="stylesheet" type="text/css" href="plugins/font-awesome-4.3.0/css/font-awesome.css">
		<link rel="stylesheet" type="text/css" href="plugins/assets/css/animationxtra.css">
		<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
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
<script src="js/jquery-2.1.3.js" type="text/javascript"></script>
<script src="js/bootstrap.js" type="text/javascript"></script>