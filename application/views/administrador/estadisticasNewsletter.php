<script>

	function mostrarDetalles(cliente){
		$('#'+cliente).css('display', 'block');
                                
	}

	function cancelarNewsletter(newsletter){
		jQuery.ajax({
			type: 'POST',
			url: '<?php echo base_url(); ?>administrador/cancelarNewsletter/' + newsletter,
			success: function(resp) {
				document.location.href = document.location.href;
			}
		});
	}
</script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery-ui.js"></script>
<link rel="stylesheet" href="<?php echo base_url();?>css/jquery-ui.min.css" type="text/css" media="screen"/>
<?php $this->load->helper('form'); ?>
<div>
	<div style="position: relative; margin-bottom: 5px; text-align: left; right: 20px;">
		<h1 style="text-align: center;">
			Estadísticas newsletter - <?php echo $newsletter->nombre; ?>
		</h1>
		<h1 style="text-align: center;">
			Generales
		</h1>
		<ul class="head_mod_lista_3" style="max-width: 700px; margin-top: 30px;">
			<li >
				Clicks totales Email
			</li>
			<li>
				Clicks totales Detalles oferta
			</li>
			<li>
				Clicks totales Contacto
			</li>
		</ul>
		<ul class="body_mod_lista_3 item_fav" style="max-width: 700px;">
			<li>
				<?php echo (empty($estadisticasGenerales)) ? 0 : $estadisticasGenerales->clicksNewsletter; ?>
			</li>
			<li>
				<?php echo (empty($estadisticasGenerales)) ? 0 : $estadisticasGenerales->clicksOfertas; ?>
			</li>
			<li>
				<?php echo (empty($estadisticasGenerales)) ? 0 : $estadisticasGenerales->clicksContacto; ?>
			</li>
		</ul>
		<ul class="head_mod_lista" style="max-width: 700px; margin-top: 30px;">
			<li >
				Ofertas más visitadas
			</li>
		</ul>
<?php
	if(!empty($estadisticasGenerales->ofertas)){
		foreach($estadisticasGenerales->ofertas as $oferta){
?>
		<ul class="body_mod_lista_2 item_fav" style="max-width: 700px;">
			<li>
				<?php echo $oferta->titulo; ?>
			</li>
			<li style="text-align: right;">
				<?php echo $oferta->clicksOferta; ?> clicks
			</li>
		</ul>
<?php
		}
	}
	else{
?>
		<ul class="body_mod_lista item_fav" style="max-width: 700px;">
			<li>
				Ninguna oferta ha sido visitada aun
			</li>
		</ul>
<?php
	}
?>
		<br>
		<h1 style="text-align: center;">
			Anunciantes
		</h1>
<?php
	if(!empty($estadisticasClientes)){?>
		<ul class="head_mod_lista_5" style="max-width: 700px; margin-top: 30px;">
			<li >
				Cliente
			</li>
			<li >
				Clicks totales Contacto
			</li>
			<li>
				Clicks totales Solicitud pass
			</li>
			<li>
				Clicks totales Login
			</li>
			<li>
				Clicks en Ofertas
			</li>
		</ul>
		
<?php 
		foreach($estadisticasClientes as $cliente){
?>
		
		<ul class="body_mod_lista_5 item_fav" style="max-width: 700px;">
			<li>
				<?php echo $cliente->nombre; ?>
			</li>
			<li>
				<?php echo (empty($cliente)) ? 0 : $cliente->clicksContacto; ?>
			</li>
			<li>
				<?php echo (empty($cliente)) ? 0 : $cliente->clicksPass; ?>
			</li>
			<li>
				<?php echo (empty($cliente)) ? 0 : $cliente->clicksLogin; ?>
			</li>
			<li>
				<a onclick="mostrarDetalles('ofertas_<?php echo $cliente->id_cliente; ?>');" id="botonDetalles" style="text-decoration: none; width: 50px; float: right;cursor: pointer;">
					<div class="contact_form_area" style="width: 50px;text-align: center;">
						<span style="color: #ffffff;">Detalles</span>
					</div>
				</a>
			</li>
		</ul>
		<ul id="ofertas_<?php echo $cliente->id_cliente; ?>" class="head_mod_lista" style="max-width: 700px; margin-top: 5px;display:none;color: black;">
			<li style="color: #ffffff;">
				Ofertas más visitadas
			</li>
		
<?php
			if(!empty($cliente->ofertas)){
				foreach($cliente->ofertas as $oferta){
?>
			<ul class="body_mod_lista_2 item_fav" style="max-width: 700px;">
				<li>
					<?php echo $oferta->titulo; ?>
				</li>
				<li style="text-align: right;">
					<?php echo $oferta->clicksOferta; ?> clicks
				</li>
			</ul>
		
<?php
				}
			}
			else{
?>
		<ul class="body_mod_lista item_fav" style="max-width: 700px;">
			<li>
				No ha sido visitado ninguna oferta aun
			</li>
		</ul>
<?php
			}?>
		</ul>
<?php
		}
	}
	else{
?>
		<h2 style="text-align: center;">
			No hay clientes
		</h2>
<?php
	}
?>
		<br>
	</div>
</div>
