		<!--- Contenedor de Menu lateral y de contenido ---->
		<div class="container-fluid">
				<!----Menu Lateral --->
				<div class="col-sm-2 col-md-2 col-lg-2 sidebar  collapse in" id="menus">
					<ul class="nav nav-sidebar">
						<li>
							<a href="#user" data-toggle="collapse" data-parent="#MainMenu" class="letter22"><img src="/media/fotos/<?php echo $usuario->foto; ?>" height="48" width="48" class="img-circle" style="margin-top:10px;"><br><label class="letter22"><?php if(isset($_SESSION['nombres'])) echo $_SESSION['nombres'] ; ?></label></a>
						</li>
							<div class="collapse" id="user">
								<li><a href="/includes/logout.php" class="list-group-item letter3">Salir</a></li>
							</div>
						<li>
							<a id="pag_home" class="letter22" href="/index.php"><span class="glyphicon glyphicon-home ico"></span><b> INICIO</b></a>
						</li>
						<li>
							<a id="pag_home" class="letter3" href="/calificaciones.php"><span class="glyphicon glyphicon-education ico"></span> Calificaciones</a>
						</li> 
						
					</ul>
				</div>
</div>