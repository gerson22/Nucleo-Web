<?php
include_once("includes/clases/class_lib.php");
session_start();
$usuario = new Persona($_SESSION['id_persona']);
?>
<link rel="stylesheet" type="text/css" href="../../../css/bootstrap.css">
		<link rel="stylesheet" type="text/css" href="../../../css/sistema_css.css">
		<link rel="stylesheet" type="text/css" href="../../../plugins/assets/css/animations.css">
		<link rel="stylesheet" type="text/css" href="../../../font-awesome/css/font-awesome.css">
		<link rel="stylesheet" type="text/css" href="../../../plugins/assets/css/animationxtra.css">
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
					<img id="logo" src="../../media/logos/mezeblanco.png" height="58" style="margin-bottom:0px; margin-top:15px;">
				</div>
				<div id="barra" class="navbar-collapse collapse">
					<ul class="nav navbar-nav navbar-right">
						
						<li class="dropdown">
							<a href="#" class="dropdown-toggle letter2" data-toggle="dropdown" role="button" aria-expanded="false"><img src="/media/fotos/<?php echo $usuario->foto; ?>" height="48" width="48" class="img-circle">&nbsp;<?php if(isset($_SESSION['nombres'])) echo $_SESSION['nombres'] ; ?><span class="caret"></span></a>
							<ul class="dropdown-menu">
								<li id="logout"><a href="/includes/logout.php"><span class="glyphicon glyphicon-off ico"></span> Salir</a></li>
							</ul>
					</ul>
				</div>
			</div>
		</nav>
<?php
switch($_SESSION['tipo_persona'])
{
    case 1: include_once("menu_alumno.php"); break;
    case 2: include_once("menu_maestro.php"); break;
    case 3: include_once("menu_administrador.php"); break;
    default: break;
}
?>