<?php
include("includes/validar_sesion.php");
include_once("includes/clases/class_lib.php");
session_start();
$usuario = new Persona($_SESSION['id_persona']);
?>
<!DOCTYPE html>

<html class="no-js">
    <head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="">
		<meta name="author" content="">
		<link rel="Shortcut Icon" href="http://plataforma.colegiomeze.com/media/iconos/meze.ico">
		<title>Sistema Integral Meze</title>

		<!-- Bootstrap Core CSS -->
		<link rel="stylesheet" href="css/bootstrap.min.css" type="text/css">

		<!-- Custom Fonts -->
		<link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>
		<link href='http://fonts.googleapis.com/css?family=Merriweather:400,300,300italic,400italic,700,700italic,900,900italic' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" href="font-awesome/css/font-awesome.min.css" type="text/css">

		<!-- Plugin CSS -->
		<link rel="stylesheet" href="css/animate.min.css" type="text/css">
		<link rel="stylesheet" type="text/css" href="plugins/assets/css/animations.css">

		<!-- Custom CSS -->
		<link rel="stylesheet" href="css/creative.css" type="text/css">
    </head>

            <?php
                switch($usuario->tipo_persona)
                {
                    case 3: include_once("indexAdmin.php"); break;
                    case 2: include_once("indexDocente.php"); break;
                    case 1: include_once("indexAlumno.php"); break;
                    default:  break;
                }
            ?>
</html>
<!-- jQuery -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

    <!-- Plugin JavaScript -->
    <script src="js/jquery.easing.min.js"></script>
    <script src="js/jquery.fittext.js"></script>
    <script src="js/wow.min.js"></script>

    <!-- JavaScript Plugin-->
    <script src="js/creative.js"></script>
	<!---- Other Effects --->
	<script src="plugins/assets/js/backbone.js" type="text/javascript"></script>
	<script src="plugins/assets/js/appear.min.js" type="text/javascript"></script>
	<script src="plugins/assets/js/animations.js" type="text/javascript"></script>
<!-- SCRIPTS -->
<script src="js/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>
