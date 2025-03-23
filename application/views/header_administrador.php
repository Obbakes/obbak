<div class="admin_header">
	<div class="pricipal_header" style="margin-bottom: 0;">
		<div class="principal_logo">
			<a  href="<?php echo base_url();?>">
				<img id="logfir" src="<?php echo base_url();?>img/logo-bimads.png">
			</a>
		</div>
		<nav class="principal_menu admin">
			<ul class="menu">
				<li class="<?php echo ($opc == 'home') ? 'active' : ''; ?>">
					<a href="<?php echo base_url() . 'administrador';?>" style="border-right:none;">
						<div class="icoInicio <?php echo ($opc == 'home') ? 'active' : ''; ?>" title="Inicio" ></div>
					</a>
				</li>
				<li class="<?php echo ($opc == 'ofertas') ? 'active' : ''; ?>">
					<a href="<?php echo base_url() . 'administrador/ofertas';?>" style="border-left:solid 1px white;">
						<div class="icoMenu" title="Ofertas" >
							<div>
								Ofertas
							</div>
						</div>
					</a>
				</li>
				<li class="<?php echo ($opc == 'anunciantes') ? 'active' : ''; ?>">
					<a href="<?php echo base_url() . 'administrador/anunciantes';?>">
						<div class="icoMenu" title="Anunciantes" >
							<div>
								Anunciantes
							</div>
						</div>
					</a>
				</li>
				<li class="<?php echo ($opc == 'medios') ? 'active' : ''; ?>">
					<a href="<?php echo base_url() . 'administrador/medios';?>">
						<div class="icoMenu" title="Medios" >
							<div>
								Medios
							</div>
						</div>
					</a>
				</li>
				<li class="<?php echo ($opc == 'gestor_medios') ? 'active' : ''; ?>">
					<a href="<?php echo base_url() . 'administrador/gestorMedios';?>">
						<div class="icoMenu" title="Gestor Medios" >
							<div>
								Gestor Medios
							</div>
						</div>
					</a>
				</li>
				<li class="<?php echo ($opc == 'agencias') ? 'active' : ''; ?>">
					<a href="<?php echo base_url() . 'administrador/agencias';?>">
						<div class="icoMenu" title="Agencias" >
							<div>
								Agencias
							</div>
						</div>
					</a>
				</li>
				<li class="<?php echo ($opc == 'newsletters') ? 'active' : ''; ?>">
					<a href="<?php echo base_url() . 'administrador/newsletters';?>">
						<div class="icoMenu" title="Newsletters" >
							<div>
								Newsletters
							</div>
						</div>
					</a>
				</li>
				<li>
					<a href="<?php echo base_url() . 'inicio/logout';?>">
						<div class="icoMenu" title="Salir" >
							<div>
								Salir
							</div>
						</div>
					</a>
				</li>
			</ul>
		</nav>
	</div>
</div>