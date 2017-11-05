<nav class="navbar navbar-expand-lg navbar-dark santa-rosa_bg-dark fixed-top" id="mainNav">
	<a class="navbar-brand" href="index.html">Molino Santa Rosa	</a>
	<button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	</button>
	<div class="collapse navbar-collapse" id="navbarResponsive">
		<ul class="navbar-nav navbar-sidenav" id="exampleAccordion">
			@role('admin')
				<li class="nav-item" data-toggle="tooltip" data-placement="right" title="Administraci&oacute;n de Usuarios">
					<a class="nav-link" href="index.html">
						<i class="fa fa-fw fa-users santa_rosa-icon-users-sidebar"></i>
						<span class="nav-link-text">Administraci&oacute;n de Usuarios</span>
					</a>
				</li>
			@endrole
			<li class="nav-item" data-toggle="tooltip" data-placement="right" title="Registro de Balanzas">
				<a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapseExamplePages" data-parent="#exampleAccordion">
					<i class="fa fa-fw fa-balance-scale santa_rosa-icon-balance-sidebar"></i>
					<span class="nav-link-text">Registro de Balanzas</span>
				</a>
				<ul class="sidenav-second-level collapse" id="collapseExamplePages">
					<li>
						<a href="login.html">Blanza 1</a>
					</li>
					<li>
						<a href="register.html">Blanza 2</a>
					</li>
					<li>
						<a href="forgot-password.html">Blanza 3</a>
					</li>
				</ul>
			</li>
			<li class="nav-item" data-toggle="tooltip" data-placement="right" title="Gr&aacute;ficas">
				<a class="nav-link" href="tables.html">
					<i class="fa fa-fw fa-bar-chart santa_rosa-icon-charts-sidebar"></i>
					<span class="nav-link-text">Gr&aacute;ficas</span>
				</a>
			</li>
		</ul>
		<ul class="navbar-nav sidenav-toggler">
			<li class="nav-item">
				<a class="nav-link text-center" id="sidenavToggler">
					<i class="fa fa-fw fa-angle-left"></i>
				</a>
			</li>
		</ul>
		<ul class="navbar-nav ml-auto">
			<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle mr-lg-2" id="alertsDropdown" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					<span class="small">{{ Auth::user()->name }}</span>
					<i class="fa fa-fw fa-user santa_rosa-icon-user-top"></i>
				</a>
				<div class="dropdown-menu dropdown-menu-right" aria-labelledby="alertsDropdown">
					<h6 class="dropdown-header">Usuario</h6>
					<a class="dropdown-item" href="#">
						<div class="dropdown-message"><i class="fa fa-fw fa-wrench"></i> Editar Perfil</div>
					</a>
					<a class="dropdown-item" href="#">
						<div class="dropdown-message"><i class="fa fa-fw fa-cogs"></i> Cambiar Contrase&ntilde;a</div>
					</a>
					<a class="dropdown-item" href="#">
						<div class="dropdown-message" id="side-bar-logout">
							<i class="fa fa-fw fa-power-off"></i> Salir
							<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
								{{ csrf_field() }}
							</form>
						</div>
					</a>
				</div>
			</li>
		</ul>
	</div>
</nav>
