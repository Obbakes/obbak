<div class="admin_header fontAgencia">
	<div class="pricipal_header ">
		<div class="principal_logo">
			<a  href="<?php echo base_url();?>">
				<img id="logfir" src="<?php echo base_url();?>img/logo-bimads.png">
			</a>
		</div>
		<nav class="principal_menu admin">
			<ul class="menu">
				<li class="<?php echo ($opc == 'ofertas') ? 'active' : ''; ?>">
					<a href="<?php echo base_url() . 'agencias/ofertas';?>">
						<div class="icoMenu" title="Ofertas" style="border: none;">
							<div>
								Ofertas
							</div>
						</div>
					</a>
				</li>
				<li class="<?php echo ($opc == 'anunciantes') ? 'active' : ''; ?>">
					<a href="<?php echo base_url() . 'agencias/anunciantes';?>">
						<div class="icoMenu" title="Anunciantes" >
							<div>
								Anunciantes
							</div>
						</div>
					</a>
				</li>
				<li class="<?php echo ($opc == 'perfil') ? 'active' : ''; ?>">
					<a href="<?php echo base_url() . 'agencias/perfil';?>">
						<div class="icoMenu" title="Perfil" >
							<div>
								Perfil
							</div>
						</div>
					</a>
				</li>
			</ul>
		</nav>
	</div>
</div>