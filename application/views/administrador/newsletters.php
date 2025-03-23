<script>
	function cancelarNewsletter(newsletter){
		jQuery.ajax({
			type: 'POST',
			url: '<?php echo base_url(); ?>administrador/cancelarNewsletter/' + newsletter,
			success: function(resp) {
				document.location.href = '<?php echo base_url() . 'administrador/newsletters/2'; ?>';
			}
		});
	}
</script>
<?php $this->load->helper('form'); ?>
<h1>
	Listado de newsletters
</h1>
<form accept-charset="utf-8" method="post" action="<?php echo base_url() . 'administrador/newsletters/1'; ?>" >
	<div class="filtro_ofertas">
		<div>
			<span style="font-size: 16px;" >
				Estado:
			</span>
			<select id="estado" name="estado" class="contacto_form_input">
				<option value="" <?php echo (empty($filtro['estado']) || $filtro['estado'] == '') ? 'selected="selected"' : ''; ?>>
					Todos
				</option>
				<option value="d" <?php echo (!empty($filtro['estado']) && $filtro['estado'] == 'd') ? 'selected="selected"' : ''; ?>>
					En edición
				</option>
				<option value="p" <?php echo (!empty($filtro['estado']) && $filtro['estado'] == 'p') ? 'selected="selected"' : ''; ?>>
					Pendientes
				</option>
				<option value="e" <?php echo (!empty($filtro['estado']) && $filtro['estado'] == 'e') ? 'selected="selected"' : ''; ?>>
					En proceso
				</option>
				<option value="t" <?php echo (!empty($filtro['estado']) && $filtro['estado'] == 't') ? 'selected="selected"' : ''; ?>>
					Enviadas
				</option>
				<option value="c" <?php echo (!empty($filtro['estado']) && $filtro['estado'] == 'c') ? 'selected="selected"' : ''; ?>>
					Canceladas
				</option>
			</select>
		</div>
		<div>
			<span style="font-size: 16px;" >
				Newsletter:
			</span>
			<input type="text" id="palabra" name="palabra" class="contacto_form_input" value="<?php echo (!empty($filtro['palabra'])) ? $filtro['palabra'] : ''; ?>"/>
		</div>
	</div>
	<div class="filtro_ofertas" style="width: 385px;padding-top: 10px;">
		<div class="contact_form_area" >
			<input type="submit" class="classname" value="Buscar"/>

		</div>
		<div class="contact_form_area">
			<a href="<?php echo base_url() . '/administrador/newsletters/0/1'; ?>">
				<input type="button" class="classname" value="Todas"/>
			</a>
		</div>
	</div>
</form>
<div class="list_mod">
	<a href="<?php echo base_url(); ?>administrador/nuevaNewsletterMedios/3">
		<div class="contact_form_area" style="width: 200px;">
			<input type="button" class="classname" value="Nueva Newsletter" />
		</div>
	</a>
	<ul class="head_mod_lista_7">
		<li >
			Nombre
		</li>
		<li>
			Num Medios
		</li>
		<li>
			Num Ofertas
		</li>
		<li>
			Num destinatarios
		</li>
		<li>
			% completado
		</li>
		<li>
			Estado
		</li>
		<li>
			Opciones
		</li>
	</ul>
<?php
	if(!empty($newsletters)){
		$i=0;

		foreach($newsletters as $newsletter) {
?>
	<ul class="body_mod_lista_7 <?php if ($i%2==0) echo 'item_fav';?>" style="<?php echo ($newsletter->estado == 'c') ? 'color: lightgray;' : ''; ?>">
		<li>
			<?php echo $newsletter->nombre ?>
		</li>
		<li>
			<?php echo (empty($newsletter->medios)) ? 0 : count(explode(' ', $newsletter->medios)); ?>
		</li>
		<li>
			<?php echo (empty($newsletter->ofertas)) ? 0 : count(explode(' ', $newsletter->ofertas)); ?>
		</li>
		<li>
			<?php echo $newsletter->total; ?>
		</li>
		<li>
			<?php echo ($newsletter->total == 0) ? '0.00' : number_format(($newsletter->enviados / $newsletter->total * 100), 2); ?>
		</li>
		<li>
			<?php echo ($newsletter->confirmada == 0 && $newsletter->estado != 'c')
						? 'En edición'
						: (($newsletter->estado == 'p')
							? 'Pendiente'
							: (($newsletter->estado == 'e')
								? 'En proceso'
								: (($newsletter->estado == 't')
									? 'Enviada'
									: 'Cancelada'))); ?>
		</li>
		<li>
<?php
			if($newsletter->confirmada == 0 && $newsletter->estado != 'c'){
?>
			<a style="text-decoration: none;" href="<?php echo base_url().'administrador/continuarEdicionNewsletter/' . $newsletter->id_newsletter; ?>">
				<img src="<?php echo base_url(); ?>images/icono_editar.png" title="Editar" height="30px" />
			</a>
<?php
			}
			else{
?>
			<a style="text-decoration: none;" href="<?php echo base_url().'administrador/newsletter/' . $newsletter->id_newsletter; ?>">
				<img src="<?php echo base_url(); ?>images/icono_ver.png" title="Ver" height="30px" />
			</a>
<?php
			}
?>
			<a style="text-decoration: none;"
					onclick="if(confirm('¿Quieres utilizar los datos de esta newsletter para crear otra?')) return true; else return false;"
					href="<?php echo base_url().'administrador/duplicarNewsletter/' . $newsletter->id_newsletter; ?>">
				<img src="<?php echo base_url(); ?>images/icono_duplicar.png" title="Duplicar" height="30px" />
			</a>
<?php
			if($newsletter->estado == 'p'){
?>
			<a style="text-decoration: none; cursor: pointer;"
					onclick="if(confirm('¿Quieres cancelar esta newsletter?')){ cancelarNewsletter(<?php echo $newsletter->id_newsletter; ?>);}">
				<img src="<?php echo base_url(); ?>images/icono_cancelar.png" title="Cancelar" height="30px" />
			</a>
<?php
			}
			
			if($newsletter->estado == 't'){
?>
			<a style="text-decoration: none;" href="<?php echo base_url().'administrador/estadisticasNewsletter/' . $newsletter->id_newsletter; ?>">
				<img src="<?php echo base_url(); ?>images/icono_estadisticas.png" title="Ver Estadísticas" height="30px" />
			</a>
<?php
			}
?>
		</li>
	</ul>
<?php
			$i++;
		}
	}
	else{
?>
	<div>
		<p style="text-align: center;">
			No existen newsletters.
		</p>
	</div>
<?php
	}
?>
</div>
<div class="paginacion">
	<?php echo $paginacion; ?>
</div>