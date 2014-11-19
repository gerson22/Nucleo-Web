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
            <?php /*include("includes/header.php");*/ ?>
            <div class="container">

                <div id="top">
                    <!-- Panel izquierdo -->
                    <div class="col-md-6 col-sm-12" id="panel_izquierdo" >

                        <a href="admin/stats.php">
                            <div class="index_opcion">
                                <img class="img-responsive" src="/media/iconos/stats.png" alt="Estadísticas" />
                                <footer>Estadísticas</footer>
                            </div>
                        </a>

                        <a href="admin/maestros/index.php">
                            <div class="index_opcion">
                                <img class="img-responsive" src="/media/iconos/icon_teacher.png" alt="Maestros" />
                                <footer>Maestros</footer>
                            </div>
                        </a>

                        <a href="admin/alumnos/index.php">
                            <div class="index_opcion_large">
                                <img class="img-responsive" src="/media/iconos/icon_student.png" alt="Alumnos" />
                                <footer>Alumnos</footer>
                            </div>
                        </a>

                        <a href="admin/becas/lista.php">
                            <div class="index_opcion">
                                <img class="img-responsive" src="/media/iconos/icon_beca.png" alt="Becas" />
                                <footer>Becas</footer>
                            </div>
                        </a>

                        <a href="admin/cuentas/recibos.php">
                            <div class="index_opcion">
                                <img class="img-responsive" src="/media/iconos/icon_cuentas.png" alt="Cuentas" />
                                <footer>Cuentas</footer>
                            </div>
                        </a>

                    </div>

                    <!-- Panel derecho -->
                    <div class="col-md-6 col-sm-12" id="panel_derecho">
                        <div id="foto">
                            <img src="/media/yo.jpg" />
                            <footer>Bienvenido <?php echo $usuario->nombres; ?></footer>
                        </div>

                        <div id="carousel_noticias">

                        </div>
                    </div>
                </div>

                <div id="bot">

                </div>

            </div>
        </div>

        <!-- SCRIPTS -->
        <script src="js/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>
    </body>
</html>