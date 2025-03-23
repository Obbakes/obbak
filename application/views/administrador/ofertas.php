<script type="text/javascript" src="<?php echo base_url() .'js/utilTablas.js'; ?>">
<script>
	function borrarOferta(oferta){
		jQuery.ajax({
			type: 'POST',
			url: '<?php echo base_url(); ?>administrador/borrarOferta/' + oferta,
			success: function(resp) {
				if(resp == 'false')
					alert('La oferta no puede borrarse porque tiene peticiones asociadas');
				else
					document.location.href = '<?php echo base_url() . 'administrador/ofertas/2'; ?>';
			}
		});
	}

	function cambiarDestacado(id_oferta){
		var value = $('#oferta' + id_oferta).attr('data-value');

		if(value == 0){
			value = 1;
			$('#oferta' + id_oferta).find('.destacado-vacio').hide();
			$('#oferta' + id_oferta).find('.destacado').show();
		}
		else{
			value = 0;
			$('#oferta' + id_oferta).find('.destacado').hide();
			$('#oferta' + id_oferta).find('.destacado-vacio').show();
		}

		$('#oferta' + id_oferta).attr('data-value', value);

		$.ajax({
			type: 'POST',
			url: '<?php echo base_url(); ?>administrador/cambiarDestacadaOferta/' + id_oferta,
			data: {'destacada': value},
			success: function(resp) {
			}
		});
	}
</script>
<?php $this->load->helper('form');?>
<h1>Listado de ofertas ( <?php echo $total_ofertas; ?> ofertas)</h1>
<form accept-charset="utf-8" method="post" action="<?php echo base_url() . 'administrador/ofertas/1'; ?>" style="">
		
	<input type="hidden" id="order_by_campo" name="order_by_campo" value="<?php if (isset($filtro['order_by_campo'])) { echo $filtro['order_by_campo'];}?>" ></input>
	<input type="hidden" id="order_by_sentido" name="order_by_sentido" value="<?php if (isset($filtro['order_by_sentido'])) {echo $filtro['order_by_sentido'];}?>"> </input>
	
	<div class="filtro_ofertas">
		<div>
			<label for="id_estado">Estado:</label>
			<select class="contacto_form_input" id="select_estado" name="select_estado">
				<option
					value="0" <?php echo (empty($filtro['estado']) || $filtro['estado'] == 0) ? 'selected="selected"' : ''; ?>>
					Todas
				</option>
				<option
					valu	e="1" <?php echo (!empty($filtro['estado']) && $filtro['estado'] == 1) ? 'selected="selected"' : ''; ?>>
					Gestionadas
				</option>
				<option
					value="2" <?php echo (!empty($filtro['estado']) && $filtro['estado'] == 2) ? 'selected="selected"' : ''; ?>>
					Por gestionar
				</option>
			</select>
		</div>
		<div>
			<label for="id_caducidad">Caducidad:</label>
			<select class="contacto_form_input" id="select_caducidad" name="select_caducidad">
				<option
					value="0" <?php echo (empty($filtro['caducidad']) || $filtro['caducidad'] == 0) ? 'selected="selected"' : ''; ?>>
					Todas
				</option>
				<option
					value="1" <?php echo (!empty($filtro['caducidad']) && $filtro['caducidad'] == 1) ? 'selected="selected"' : ''; ?>>
					Vigentes
				</option>
				<option
					value="2" <?php echo (!empty($filtro['caducidad']) && $filtro['caducidad'] == 2) ? 'selected="selected"' : ''; ?>>
					Caducan hoy
				</option>
				<option
					value="3" <?php echo (!empty($filtro['caducidad']) && $filtro['caducidad'] == 3) ? 'selected="selected"' : ''; ?>>
					Caducan en 10 días
				</option>
				<option
					value="4" <?php echo (!empty($filtro['caducidad']) && $filtro['caducidad'] == 4) ? 'selected="selected"' : ''; ?>>
					Caducadas
				</option>
			</select>
		</div>
		<div>
			<label for="id_publicada">Publicada:</label>
			<select class="contacto_form_input" id="select_publicada" name="select_publicada">
				<option
					value="0" <?php echo (empty($filtro['publicada']) || $filtro['publicada'] == 0) ? 'selected="selected"' : ''; ?>>
					Todas
				</option>
				<option
					value="1" <?php echo (!empty($filtro['publicada']) && $filtro['publicada'] == 1) ? 'selected="selected"' : ''; ?>>
					Publicadas
				</option>
				<option
					value="2" <?php echo (!empty($filtro['publicada']) && $filtro['publicada'] == 2) ? 'selected="selected"' : ''; ?>>
					No publicadas
				</option>
			</select>
		</div>
		<div>
			<label for="id_destacada">Destacada:</label>
			<select class="contacto_form_input" id="select_destacada" name="select_destacada">
				<option
					value="0" <?php echo (empty($filtro['destacada']) || $filtro['destacada'] == 0) ? 'selected="selected"' : ''; ?>>
					Todas
				</option>
				<option
					value="1" <?php echo (!empty($filtro['destacada']) && $filtro['destacada'] == 1) ? 'selected="selected"' : ''; ?>>
					Destacadas
				</option>
				<option
					value="2" <?php echo (!empty($filtro['destacada']) && $filtro['destacada'] == 2) ? 'selected="selected"' : ''; ?>>
					No destacadas
				</option>
			</select>
		</div>
                <div>
			<label for="id_destacada">Tipo Medio:</label>
			<select id="tipo_medio" name="tipo_medio" class="contacto_form_input">
				<option value="0" <?php echo (empty($filtro['tipo_medio']) || $filtro['tipo_medio'] == '0') ? 'selected="selected"' : ''; ?>>
					Todos
				</option>
<?php
	if(!empty($tipos_medios)){
		foreach($tipos_medios as $tipo_medio){
?>
				<option value="<?php echo $tipo_medio->id_tipo; ?>"
						<?php echo (!empty($filtro['tipo_medio']) && $filtro['tipo_medio'] == $tipo_medio->id_tipo) ? 'selected="selected"' : ''; ?>>
					<?php echo $tipo_medio->tipo; ?>
				</option>
<?php
		}
	}
?>
			</select>
		</div>
		<div>
			<label for="id_medio">Medio:</label>
			<select class="contacto_form_input" id="select_medio" name="select_medio">
				<option
					value="0" <?php echo (empty($filtro['medio']) || $filtro['medio'] == 0) ? 'selected="selected"' : ''; ?>>
					Todos
				</option>
				<?php
				if (!empty($medios)) {
					foreach ($medios as $medio) {
						?>
						<option <?php echo (!empty($filtro['medio']) && $filtro['medio'] == $medio->id_medio) ? 'selected="selected"' : ''; ?>
							value="<?php echo $medio->id_medio; ?>">
							<?php echo $medio->nombre; ?>
						</option>
						<?php
					}
				}
				?>
			</select>
		</div>
	</div>
	<div class="filtro_ofertas" style="width: 385px;padding-top: 10px;">
		<div class="contact_form_area" >
			<input type="submit" class="classname" value="Buscar"/>

		</div>
		<div class="contact_form_area">
			<a href="<?php echo base_url() . '/administrador/ofertas/0/1'; ?>">
				<input type="button" class="classname" value="Todas"/>
			</a>
		</div>
	</div>
</form>
<p style="color:red;">
	<?php if(!empty ($error)) echo $error ?>
</p>
<div class="list_mod">
	<a href="<?php echo base_url(); ?>administrador/nuevaOferta/2" >
		<div class="contact_form_area" style="width: 200px;">
			<input type="button" class="classname" value="Nueva Oferta" />
		</div>
	</a>
<?php
	if(!empty($ofertas)) {
?>
	<ul class="head_mod_lista">
		<li style="width: 4%;">
			<a class="cabeceraOrden" id="cab_id_oferta" href="#">ID</a>
		</li>
		<li style="width: 4%;">
			<a class="cabeceraOrden" id="cab_destacada" href="#">Dest.</a>
		</li>
		<li style="width: 18%;">
			<a class="cabeceraOrden" id="cab_titulo" href="#">Título</a>
		</li>
		<li style="width: 9%;">
			<a class="cabeceraOrden" id="cab_medio" href="#">Medio</a>
		</li>
		<li style="width: 9%;">
			<a class="cabeceraOrden" id="cab_tipo_medio" href="#">Tipo de Medio</a>
		</li>
		<li style="width: 10%;">
			<a class="cabeceraOrden" id="cab_fecha_insercion" href="#">Fecha creación</a>
		</li>
		<li style="width: 9%;">
			<a class="cabeceraOrden" id="cab_precio_oferta" href="#">Precio oferta</a>
		</li>
		<li style="width: 10%;">
			<a class="cabeceraOrden" id="cab_publicada" href="#">Publicada</a>
		</li>
		<li style="width: 9%;">
			<a class="cabeceraOrden" id="cab_gestionar" href="#">Estado</a>
		</li>
		<li style="width: 9%;">
			Caducidad
		</li>
		<li style="width: 5%;">
			Opciones
		</li>
	</ul>
<?php
		$i=0;

		foreach($ofertas as $oferta) {
?>
	<ul class="body_mod_lista <?php if ($i%2==0) echo 'item_fav';?>" >
		<li style="width: 4%;">
			<p><?php echo $oferta->id_oferta; ?></p>
		</li>
                <li  id="oferta<?php echo $oferta->id_oferta; ?>"  onclick="cambiarDestacado(<?php echo $oferta->id_oferta; ?>);"
				data-value="<?php echo $oferta->destacada; ?>" style="width: 4%;">
			<img class="destacado" src="<?php echo base_url() . 'images/destacado.png'; ?>" height="20px"
					style="<?php echo ($oferta->destacada == 1) ? '' : 'display: none;'; ?>"/>
			<img class="destacado-vacio" src="<?php echo base_url() . 'images/destacado_vacio.png'; ?>" height="20px"
					style="<?php echo ($oferta->destacada == 0) ? '' : 'display: none;'; ?>"/>
		</li>
		<li style="width: 18%;">
			<p><?php echo $oferta->titulo; ?></p>
		</li>
		<li style="width: 9%;">
			<p><?php echo $oferta->medio; ?></p>
		</li>
		<li style="width: 10%;">
			<?php echo $oferta->tipo_medio; ?>
		</li>
		<li style="width: 9%;">
			<?php echo date('Y-m-d H:i', strtotime($oferta->fecha_insercion)); ?>
		</li>
		<li style="width: 10%;">
			<?php echo $oferta->precio_oferta; ?>
		</li>
		<li style="width: 9%;">
			<?php echo ($oferta->publicada == 1) ? 'Publicada' : 'No publicada'; ?>
		</li>
		<li onclick="document.location.href='<?php echo base_url() . 'administrador/inscripcionesOferta/' . $oferta->id_oferta; ?>';" style="cursor: pointer;" style="width: 10.66%;">
			<p><?php if($oferta->gestionar > 0){ ?>
				<img src="<?php echo base_url(); ?>images/Icampana.png" alt="por gestionar" title="Por gestionar" clasS="porGestionar">Por gestionar
			<?php }else{  ?>
				<img src="<?php echo base_url(); ?>images/yaGestionada.png" alt="por gestionar" title="Por gestionar" clasS="porGestionar">Ya Gestionada
			<?php } ?></p>
		</li>
		<li style="width: 9%;">
			<?php echo ($oferta->caducidad == 'caducada') ? 'Caducada' : (($oferta->caducidad == 'hoy') ? 'Caduca hoy' : (($oferta->caducidad == 'menos 10') ? 'Caduca 10 días o menos' : 'Vigente')); ?>
		</li>
		<li style="width: 9%;">
			<a style="text-decoration: none;" href="<?php echo base_url().'administrador/editarOferta/'.$oferta->id_oferta; ?>">
				<img src="<?php echo base_url(); ?>images/icono_editar.png" title="Editar" height="30px" />
			</a>
			<a style="text-decoration: none;"
					onclick="if(confirm('¿Quieres utilizar los datos de esta oferta para crear otra?')) return true; else return false;"
					href="<?php echo base_url().'administrador/duplicarOferta/' . $oferta->id_oferta; ?>">
				<img src="<?php echo base_url(); ?>images/icono_duplicar.png" title="Duplicar" height="30px" />
			</a>
			<a style="text-decoration: none; cursor: pointer;"
					onclick="if(confirm('¿Quieres eliminar esta oferta?')){ borrarOferta(<?php echo $oferta->id_oferta; ?>);}">
				<img src="<?php echo base_url(); ?>images/icono_eliminar.png" title="Eliminar" height="30px" />
			</a>
		</li>
	</ul>
<?php
			$i++;
		}
	}
	else
		echo '<br><p>La búsqueda no produjo resultados.<p>';
?>
</div>
<div class="paginacion">
	<?php echo $paginacion; ?>&nbsp;&nbsp;<a href="<?php echo base_url();?>administrador/ofertas/2/1/400">Mostrar Todos</a>
</div>
