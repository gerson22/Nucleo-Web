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
                        <a class="page-scroll" href="/calificaciones.php">Calificaciones</a>
                    </li>
					<li>
                        <a class="page-scroll" href="/Tareas.php">Tareas</a>
                    </li>
                    <li>
                        <a class="page-scroll" href="/includes/logout.php">Salir</a>
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
					<img class="img-responsive img-circle imgUser" src="/media/fotos/<?php echo $usuario->foto; ?>">
				</div>
				<div class="col-lg-8 col-sm-12 col-xs-12 text-center">
					<h1><?php if(isset($_SESSION['nombres'])) echo $_SESSION['nombres'] ; ?></h1>
					<p>Bienvenido al sistema del Colegio Meze aqui podras encontrar tus calificaciones y conocer un poco de los ultimos acontecimientos del colegio!!</p>
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
    <aside class="bg-dark">
        <div class="container text-center">
            <div class="call-to-action">
                <img class="img-responsive col-lg-4 col-sm-12 col-md-4 col-xs-12 logo" src="media/logos/mezeblanco.png">
                <div class="col-lg-8 col-sm-12">
					<p>Colegio Ma. Esther Zuno de Echeverria</p>
					<p>Adolfo López Mateos #1030 Col. Zaragoza C.P. 27297</p>
					<p>Tel. 792-93-93 Torreón, Coah.</p>
				</div>
            </div>
        </div>
    </aside>
</body>
<script src="js/bootstrap.min.js"></script>

    <!-- Plugin JavaScript -->
    <script src="js/jquery.easing.min.js"></script>
    <script src="js/jquery.fittext.js"></script>
    <script src="js/wow.min.js"></script>

    <!-- JavaScript Plugin-->
    <script src="js/creative.js"></script>
