<link rel="stylesheet" type="text/css" href="css/sistema_css.css">
<!--Barra del Header --->
		        <!-- Barra de Navegacion -->
       <nav class="navbar navbar-inverse navbar-fixed-top">
			<div class="container-fluid">
				<div class="navbar-header">
					<button id="btnResp" type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target ="#menus" aria-expanded="false" aria-controls="navbar">
					<span class="sr-only">Toggle Navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					</button>
					<img id="logo" src="media/img/logo.png" height="48" style="margin-bottom:0px; margin-top:15px;">
				</div>
				<div id="barra" class="navbar-collapse collapse">
					<ul class="nav navbar-nav navbar-right">
						<li>
							<img src="" id="usuario" class="center-block img-circle" max-width:30px;>
						</li>
						<li class="dropdown">
							<a href="#" class="dropdown-toggle letter2" data-toggle="dropdown" role="button" aria-expanded="false"><img src="/img/user.png" height="48"><?php if(isset($_SESSION['nombres'])) echo $_SESSION['nombres'] ; ?><span class="caret"></span></a>
							<ul class="dropdown-menu animate-in" data-anim-type="fade-in-left" data-anim-delay="400">
								<li id="logout"><a href="/includes/logout.php"><span class="glyphicon glyphicon-off ico"></span> Salir</a></li>
							</ul>
					</ul>
				</div>
			</div>
		</nav>
<?php
switch($_SESSION['tipo_persona'])
{
    case 1: include("menu_alumno.php"); break;
    case 2: include("menu_maestro.php"); break;
    case 3: include("menu_administrador.php"); break;
    default: break;
}
?>