<?php
/**
 * Created by PhpStorm.
 * User: Yozki
 * Date: 1/6/15
 * Time: 6:41 PM
 */

?>
<!DOCTYPE html>
<html lang="en" class="no-js">

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

    <!---Create by Gerson Isaias---->

</head>

<body id="page-top">

    <nav id="mainNav" class="navbar navbar-default navbar-fixed-top">
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Colegio Meze</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand page-scroll" href="#page-top">Colegio MEZE</a>
            </div>

            <!-- Seccion de ingreso al sistema!-->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li>
                        <a class="page-scroll" href="#sobre_ti">Sobre Ti</a>
                    </li>
                    <li>
                        <a class="page-scroll" href="#noticias">Noticias</a>
                    </li>
                    <li>
                        <a class="page-scroll" href="#sistema">Sistema</a>
                    </li>
                    <li>
                        <a class="page-scroll" href="#">Salir</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container-fluid -->
    </nav>

    <header>
        <div class="header-content">
            <div class="header-content-inner">
                
            </div>
        </div>
    </header>

    <section class="bg-primary" id="sobre_ti">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-sm-12 col-xs-12 box-center">
					<img class="img-responsive img-circle imgUser" src="img/user.png">
				</div>
				<div class="col-lg-8 col-sm-12 col-xs-12 text-center">
					<h1>Nombre del Usuario</h1>
					<p>Informacion acerca del usuario que describa al individuo</p>
				</div>
            </div>
        </div>
    </section>

    <section id="noticias">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h2 class="section-heading">Ultimos Acontecimientos</h2>
                    <hr class="primary">
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                    <div class="col-lg-8 col-md-8 col-md-offset-2 col-lg-offset-2 text-center">
<!--						<i class="fa fa-4x fa-diamond wow bounceIn text-primary"></i>-->
						<div class=" extras-carr animate-in" data-anim-type="fade-in-right-large" >
							<div id="fullcarousel-example" data-interval="2000" class="carousel slide ">
									<div class="carousel-inner">
									<div class="item active">

										<img src="img/carr1.jpg" alt="img1">
									</div>
									<div class="item">

										<img src="img/carr2.jpg" alt="img2">
									</div>
									<div class="item">
										<img src="img/carr3.jpg" alt="img3">
									</div>
									<div class="item">
										<img src="img/carr4.jpg" alt="img4" >	
									</div>
										<a class="left carousel-control" href="#fullcarousel-example" data-slide="prev"><i class="icon-prev fa fa-angle-left controles img-circle"></i></a>
										<a class="right carousel-control" href="#fullcarousel-example" data-slide="next"><i class="icon-next fa fa-angle-right controles img-circle"></i></a>
									</div>
							</div>
					</div>
                </div>
            </div>
        </div>
    </section>

    <section class="no-padding" id="sistema">
        <div class="container-fluid">
            <div class="row no-gutter">
                <div class="col-lg-4 col-sm-6 animate-in" data-anim-type="fade-in-up-large">
                    <a href="admin/estadisticas/stats.php" class="portfolio-box">
                        <img src="img/portfolio/1.jpg" class="img-responsive" alt="">
                        <div class="portfolio-box-caption">
                            <div class="portfolio-box-caption-content">
                                <div class="project-category text-faded">
                                    Estadisticas
                                </div>
                                <div class="project-name">
                                    Alumnos, Maestros , Clubs y Colonias
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-4 col-sm-6 animate-in" data-anim-type="fade-in-down-large">
                    <a href="admin/maestros/index.php" class="portfolio-box">
                        <img src="img/portfolio/2.jpg" class="img-responsive" alt="">
                        <div class="portfolio-box-caption">
                            <div class="portfolio-box-caption-content">
                                <div class="project-category text-faded">
                                    Maestros
                                </div>
                                <div class="project-name">
                                    Mestros Nuevos, Vigentes y Asistencias
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-4 col-sm-6 animate-in" data-anim-type="fade-in-up-large">
                    <a href="admin/alumnos/index.php" class="portfolio-box">
                        <img src="img/portfolio/3.jpg" class="img-responsive" alt="">
                        <div class="portfolio-box-caption">
                            <div class="portfolio-box-caption-content">
                                <div class="project-category text-faded">
                                    Alumnos
                                </div>
                                <div class="project-name">
                                    Todos, Inscritos, Inscribir, Reinscribir
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-4 col-sm-6 animate-in" data-anim-type="fade-in-down-large">
                    <a href="admin/becas/lista.php" class="portfolio-box">
                        <img src="img/portfolio/4.jpg" class="img-responsive" alt="">
                        <div class="portfolio-box-caption">
                            <div class="portfolio-box-caption-content">
                                <div class="project-category text-faded">
                                    Becas
                                </div>
                                <div class="project-name">
                                    Listas, Nuevas Tipos
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-4 col-sm-6 animate-in" data-anim-type="fade-in-up-large">
                    <a href="admin/cuentas/recibos.php" class="portfolio-box">
                        <img src="img/portfolio/5.jpg" class="img-responsive" alt="">
                        <div class="portfolio-box-caption">
                            <div class="portfolio-box-caption-content">
                                <div class="project-category text-faded">
                                    Cuentas
                                </div>
                                <div class="project-name">
                                    Pagos, Descuentos,Recibos
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-4 col-sm-6 animate-in" data-anim-type="fade-in-down-large">
                    <a href="admin/configuracion/clubs/index.php" class="portfolio-box">
                        <img src="img/portfolio/6.jpg" class="img-responsive" alt="">
                        <div class="portfolio-box-caption">
                            <div class="portfolio-box-caption-content">
                                <div class="project-category text-faded">
                                     Clubs
                                </div>
                                <div class="project-name">
                                    Ajedrez, Teatro, Futbol etc.
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
				<div class="col-lg-4 col-sm-6 animate-in" data-anim-type="fade-in-up-large">
                    <a href="admin/configuracion/colonias/index.php" class="portfolio-box">
                        <img src="img/portfolio/7.JPG" class="img-responsive" alt="">
                        <div class="portfolio-box-caption">
                            <div class="portfolio-box-caption-content">
                                <div class="project-category text-faded">
                                    Colonias
                                </div>
                                <div class="project-name">
                                    Colonias de nuestros alumnos
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
				<div class="col-lg-4 col-sm-6 animate-in" data-anim-type="fade-in-down-large">
                    <a href="admin/configuracion/ocupaciones/index.php" class="portfolio-box">
                        <img src="img/portfolio/8.jpg" class="img-responsive" alt="">
                        <div class="portfolio-box-caption">
                            <div class="portfolio-box-caption-content">
                                <div class="project-category text-faded">
                                    Ocupaciones
                                </div>
                                <div class="project-name">
                                   Distintas Ocupaciones
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
				<div class="col-lg-4 col-sm-6 animate-in" data-anim-type="fade-in-up-large">
                    <a href="admin/configuracion/papeleria/index.php" class="portfolio-box">
                        <img src="img/portfolio/9.jpg" class="img-responsive" alt="">
                        <div class="portfolio-box-caption">
                            <div class="portfolio-box-caption-content">
                                <div class="project-category text-faded">
                                    Papeleria
                                </div>
                                <div class="project-name">
                                    Papeleria Solicitada
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
				<div class="col-lg-4 col-sm-6 animate-in" data-anim-type="fade-in-down-large">
                    <a href="admin/configuracion/niveles/index.php" class="portfolio-box">
                        <img src="img/portfolio/10.jpg" class="img-responsive" alt="">
                        <div class="portfolio-box-caption">
                            <div class="portfolio-box-caption-content">
                                <div class="project-category text-faded">
                                    Niveles
                                </div>
                                <div class="project-name">
                                    Kinder,Primaria,Secundaria,Bachillerato,Universidad
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <aside class="bg-dark">
        <div class="container text-center">
            <div class="call-to-action">
                <img class="img-responsive col-lg-4 col-sm-12 col-md-4 col-xs-12 logo" src="img/logo.png">
                <div class="col-lg-8 col-sm-12">
					<p>Colegio Ma. Esther Zuno de Echeverria</p>
					<p>Direccion</p>
					<p>Numero</p>
				</div>
            </div>
        </div>
    </aside>

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

	

</body>

</html>
