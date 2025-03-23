<h1>Administrador</h1>
<div class="panel_control">
	<a style="cursor:pointer; text-decoration:none; color:black;" href="<?php echo base_url() . 'administrador/conexionListadoAnunciantes/1';?>">
		<div class="icono_panel_control">
			<span class="numero_icono_panel_control"><?php echo $usuarios_pendientes; ?></span>
			<br>
			<span class="texto_icono_panel_control">Anunciantes pendientes de aceptación</span>
		</div>
	</a>
	<a style="cursor:pointer; text-decoration:none; color:black;" href="<?php echo base_url() . 'administrador/conexionListadoAnunciantes/2';?>">
		<div class="icono_panel_control">
			<span class="numero_icono_panel_control"><?php echo $usuarios_permisos_pendientes; ?></span>
			<br>
			<span class="texto_icono_panel_control">Anunciantes con permisos pendientes de aceptación</span>
		</div>
	</a>
	<a style="cursor:pointer; text-decoration:none; color:black;" href="<?php echo base_url() . 'administrador/conexionListadoOfertas/1';?>">
		<div class="icono_panel_control">
			<span class="numero_icono_panel_control"><?php echo $ofertas_gestionar; ?></span>
			<br>
			<span class="texto_icono_panel_control">Ofertas con inscripciones por gestionar</span>
		</div>
	</a>
	<a style="cursor:pointer; text-decoration:none; color:black;" href="<?php echo base_url() . 'administrador/conexionListadoOfertas/2';?>">
		<div class="icono_panel_control">
			<span class="numero_icono_panel_control"><?php echo $ofertas_publicadas; ?></span>
			<br>
			<span class="texto_icono_panel_control">Ofertas publicadas</span>
		</div>
	</a>
	<a style="cursor:pointer; text-decoration:none; color:black;" href="<?php echo base_url() . 'administrador/conexionListadoOfertas/3';?>">
		<div class="icono_panel_control">
			<span class="numero_icono_panel_control"><?php echo $ofertas_caducan_hoy; ?></span>
			<br>
			<span class="texto_icono_panel_control">Ofertas que caducan hoy</span>
		</div>
	</a>
	<a style="cursor:pointer; text-decoration:none; color:black;" href="<?php echo base_url() . 'administrador/conexionListadoOfertas/4';?>">
		<div class="icono_panel_control">
			<span class="numero_icono_panel_control"><?php echo $ofertas_caducan_10_dias; ?></span>
			<br>
			<span class="texto_icono_panel_control">Ofertas que caducan en 10 días</span>
		</div>
	</a>
	<a style="cursor:pointer; text-decoration:none; color:black;" href="<?php echo base_url() . 'administrador/newsletters';?>">
		<div class="icono_panel_control">
			<img class="imagen_newsletter" src="<?php echo base_url(); ?>/images/sobre_newsletter.png" width="60px"/>
			<br>
			<span class="newsletter_icono_panel_control">Newsletters</span>
		</div>
	</a>
</div>