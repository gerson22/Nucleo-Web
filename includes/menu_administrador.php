		
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
							<a id="estadisticas" href="#Menu1" data-toggle="collapse" data-parent="#MainMenu" class="letter22"><i class="fa fa-line-chart ico"></i>  Estadisticas</a>
						</li>
							<div class="collapse" id="Menu1">
								<li><a id="est_personal" class="list-group-item letter3" href="/admin/estadisticas/stats.php">Personal</a></li>
								<li><a id="est_cuentas" class="list-group-item letter3" href="/admin/estadisticas/cuentas.php">Cuentas</a></li>
							</div>
						<li>
							<a href="#Menu2" data-toggle="collapse" data-parent="#MainMenu" class="letter22"><i class="fa fa-clock-o ico"></i> Ciclos</a>
						</li>
							<div class="collapse" id="Menu2">
								<li><a id="ciclos_lista" class="list-group-item letter3" href="/admin/ciclos_escolares/index.php">Lista</a></li>
								<li><a id="ciclos_nuevo" class="list-group-item letter3" href="/admin/ciclos_escolares/nuevo.php">Nuevo</a></li>
							</div>
						<li>
							<a href="#Menu3" data-toggle="collapse" data-parent="#MainMenu" class="letter22"><i class="fa fa-child ico"></i> Alumnos</a>
						</li>
							<div class="collapse" id="Menu3">
								<li><a id="alum_todos" class="list-group-item letter3" href="/admin/alumnos/index.php">Todos</a></li>
								<li><a id="alum_inscritos" class="list-group-item letter3" href="/admin/alumnos/alumnos_inscritos.php">Inscritos</a></li>
								<li><a id="alum_inscribir" class="list-group-item letter3" href="/admin/alumnos/inscribir.php">Inscribir</a></li>
								<li><a id="alum_reinscribir" class="list-group-item letter3" href="/admin/alumnos/reinscribir.php">Reinscribir</a></li>
							</div>
						<li>
							<a href="#Menu4" data-toggle="collapse" data-parent="#MainMenu" class="letter22"><i class="fa fa-users ico"></i> Maestros</a>
						</li>
							<div class="collapse" id="Menu4">
								<li><a id="mtro_todos" class="list-group-item letter3" href="/admin/maestros/index.php">Todos</a></li>
								<li><a id="mtro_nuevos" class="list-group-item letter3" href="/admin/maestros/nuevo.php">Nuevos</a></li>
								<li><a id="mtro_vig" class="list-group-item letter3" href="/admin/maestros/maestros_actuales.php">Vigentes</a></li>
								<li><a id="mtro_asistencias" class="list-group-item letter3 " href="/admin/maestros/asistencia.php">Asistencias</a></li>
							</div>
						<li>
							<a href="#Menu5" data-toggle="collapse" data-parent="#MainMenu" class="letter22"><i class="fa fa-cogs ico"></i> Admin</a>
						</li>
							<div class="collapse" id="Menu5">
								<li><a id="adm_todos" class="list-group-item letter3" href="/admin/administradores/index.php">Ver Todos</a></li>
								<li><a id="adm_nuevo" class="list-group-item letter3" href="/admin/administradores/nuevo.php">Nuevo</a></li>
							</div>
						<li>
							<a href="#Menu6" data-toggle="collapse" data-parent="#MainMenu" class="letter22"><i class="fa fa-graduation-cap ico"></i> Becas</a>
						</li>
							<div class="collapse" id="Menu6">
								<li><a id="becas_list" class="list-group-item letter3" href="/admin/becas/lista.php">Listas</a></li>
								<li><a id="becas_nuevas" class="list-group-item letter3 " href="/admin/becas/nueva.php">Nuevas</a></li>
								<li><a id="becas_tip" class="list-group-item letter3 " href="/admin/becas/tipos.php">Tipos</a></li>
							</div>
						<li>
							<a href="#Menu7" data-toggle="collapse" data-parent="#MainMenu" class="letter22"><i class="fa fa-sort-alpha-asc ico"></i> Grados</a>
						</li>
							<div class="collapse" id="Menu7">
								<li><a id="grados_list" class="list-group-item letter3" href="/admin/grados/index.php">Lista</a></li>
								<li><a id="grados_nuevos" class="list-group-item letter3" href="/admin/grados/nuevo.php">Nuevo</a></li>
							</div>
						<li>
							<a class="letter22" href="/admin/grupos/index.php"><i class="fa fa-graduation-cap ico"></i> Grupos</a>
						</li>
						<li>
							<a class="letter22" href="/admin/materias/index.php"><i class="fa fa-book ico"></i> Materias</a>
						</li>
						<li>
							<a href="#Menu10" data-toggle="collapse" data-parent="#MainMenu" class="letter22"><i class="fa fa-money ico"></i> Cuentas </a>
						</li>
							<div class="collapse" id="Menu10">
								<li><a  href="#submenu1" data-toggle="collapse" data-parent="#MainMenu" class="list-group-item subMen letter3 " ><i class="fa fa-usd"></i> Pagos</a></li>
								<div class="collapse" id="submenu1">
									
									<li><a id="pago_inscripcion" class="list-group-item letter3" href="/admin/cuentas/pagos/inscripcion.php">Inscripción</a></li>
									<li><a id="pago_coleg" class="list-group-item letter3 " href="/admin/cuentas/pagos/colegiaturas.php">Colegiaturas</a></li>
									<li><a id="pago_coleg" class="list-group-item letter3 " href="/admin/cuentas/pagos/cuotas.php">Cuotas</a></li>
									
								</div>
								<li><a id="becas_nuevas" href="#submenu2" data-toggle="collapse" data-parent="#MainMenu" class="list-group-item subMen letter3"><i class="fa fa-usd"></i>Descuentos</a></li>
								
								<div class="collapse" id="submenu2">
									<li><a id="desc_list" class="list-group-item letter3 " href="/admin/cuentas/descuentos/lista.php">Lista</a></li>
									<li><a id="desc_nuevos" class="list-group-item letter3" data-anim-delay="50" href="/admin/cuentas/descuentos.php">Nuevos</a></li>
								</div>
								
								<li><a id="cuentas_rec" class="list-group-item letter3 subMen" href="/admin/cuentas/recibos.php">Recibos</a></li>
							</div>
						<li>
							<a href="#Menu11" data-toggle="collapse" data-parent="#MainMenu" class="letter22"><i class="fa fa-sort-alpha-asc ico"></i> Config.</a>
						</li>
							<div class="collapse" id="Menu11">
								<li><a id="config_club" class="list-group-item letter3" href="/admin/configuracion/clubs/index.php">Club</a></li>
								<li><a id="config_colonias" class="list-group-item letter3 " href="/admin/configuracion/colonias/index.php">Colonias</a></li>
								<li><a id="config_papeleria" class="list-group-item letter3 " href="/admin/configuracion/papeleria/index.php">Papeleria</a></li>
								<li><a id="config_ocup" class="list-group-item letter3 " href="/admin/configuracion/ocupaciones/index.php">Ocupaciones</a></li>
								<li><a id="config_ocup" class="list-group-item letter3 " href="/admin/configuracion/cuentas_dinamicas/index.php">Cuentas Dinamicas</a></li>
							</div>
						<li>


					</ul>
				</div>
</div>
