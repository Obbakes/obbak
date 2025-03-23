<div class="admin_header">
	<div class="pricipal_header">
		<div class="principal_logo">
			<a  href="<?php echo base_url();?>inicio/principal">
				<img id="logfir" src="<?php echo base_url();?>img/logo-bimads.png">
			</a>
		</div>
		<nav class="principal_menu admin">
			<div class="mmenuMovil"><span></span></div>
			<ul class="menu anunciante">
				<li class="opt_movil">
					<a href="<?php echo base_url() . 'anunciantes/perfil';?>">
						<div class="icoMenu" title="Nombre" >
							<div>
								<?php echo $this->session->userdata('nombre'); ?>
							</div>
						</div>
					</a>
					<a href="<?php echo base_url() . 'inicio/logout';?>">
						<div class="icoMenu" title="salir" >
							<div>
								Salir <i class="iconS icon-sign-out"></i>
							</div>
						</div>
					</a>
				</li>
				<li class="<?php echo ($opc == 'ofertas') ? 'active' : ''; ?>">
					<a href="<?php echo base_url() . 'anunciantes/ofertas';?>">
						<div class="icoMenu" title="Ofertas" >
							<div>
								Ofertas
							</div>
						</div>
					</a>
				</li>
				<li class="<?php echo ($opc == 'inscripciones') ? 'active' : ''; ?>">
					<a href="<?php echo base_url() . 'anunciantes/inscripciones';?>">
						<div class="icoMenu" title="Inscripciones" >
							<div>
								Inscripciones
							</div>
						</div>
					</a>
				</li>
				<li class="<?php echo ($opc == 'perfil') ? 'active' : ''; ?>">
					<a href="<?php echo base_url() . 'anunciantes/perfil';?>">
						<div class="icoMenu" title="Perfil" >
							<div>
								Perfil
							</div>
						</div>
					</a>
				</li><?php if($this->session->userdata('estado')==1){?>
				<li class="datosanunciante <?php echo ($opc == 'datosanunciante') ? 'active' : ''; ?>">
					<a href="<?php echo base_url() . 'anunciantes/datosanunciante';?>">
						<div class="icoMenu" title="Perfil" >
							<div>
								Datos Anunciante
							</div>
						</div>
					</a>
				</li><?php } ?>
			</ul>
		</nav>
	</div>
</div>
<script type="text/javascript">
	  	$('.mmenuMovil').click(function(){
	  		$('.menu.anunciante').toggleClass('active');
	  	});
</script>