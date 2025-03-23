<script type="text/javascript" src="<?php echo base_url();?>js/jquery-ui.js"></script>
<link rel="stylesheet" href="<?php echo base_url();?>css/jquery-ui.min.css" type="text/css" media="screen"/>
<script type="application/javascript">
	function mostrarBotonCambio(){
		$('#botonCambios').css('display', 'block');
	}

	function realizarCambios(){
		if(!confirm('¿Quieres realizar estos cambios para las inscripciones?'))
			return;

		var cambios = '';

		$('.select_permiso').each(function(){
			if(cambios != '')
				cambios += ' ';

			cambios += $(this).find('select').attr('data-id') + ' ' + $(this).find('select').val();
		});

		$('#cambios').val(cambios);

		$('#form_inscripciones').submit();
	}
</script>
<h2 style="text-align: center;">
	Editar Oferta
</h2>
<div style="display: table; margin: 20px auto; width: 50%; padding: 0;">
	<div style="display: table-cell; width: 50%; text-align: center;">
		<input type="button" class="boton-pestanya-activo" value="Datos" style="width: 90%;"
			onclick="document.location.href='<?php echo base_url() . 'administrador/editarOferta/' . $oferta->id_oferta; ?>';">
	</div>
	<div style="display: table-cell; width: 50%; text-align: center;">
		<input type="button" class="boton-pestanya-inactivo" value="Inscripciones" disabled="disabled" style="width: 90%;">
	</div>
</div>
<?php $this->load->helper('form'); ?>
<div>
	<div style="position: relative; width: 360px; display:inline-table; margin-bottom: 5px; text-align: left; right: 20px;">
		<form accept-charset="utf-8" method="post" action="<?php echo base_url() . 'administrador/inscripcionesOferta/' . $oferta->id_oferta . '/1'; ?>"
				style="text-align: center; margin: 20px;">
			<div class="filtro_ofertas" style="width: 300px; margin: auto; text-align: left;">
				<div>
					<span style="font-size: 16px;" >
						Estado:
					</span>
					<select id="estado" name="estado" class="contacto_form_input" >
						<option value="todos" <?php echo (!isset($filtro['estado']) || $filtro['estado'] == 'todos') ? 'selected="selected"' : ''; ?>>
							Todos
						</option>
						<option value="0" <?php echo (isset($filtro['estado']) && $filtro['estado'] == '0') ? 'selected="selected"' : ''; ?>>
							Pendiente
						</option>
						<option value="1" <?php echo (isset($filtro['estado']) && $filtro['estado'] == '1') ? 'selected="selected"' : ''; ?>>
							Autorizada - Falta Pago
						</option>
						<option value="2" <?php echo (isset($filtro['estado']) && $filtro['estado'] == '2') ? 'selected="selected"' : ''; ?>>
							Pagado
						</option>
						<option value="3" <?php echo (isset($filtro['estado']) && $filtro['estado'] == '3') ? 'selected="selected"' : ''; ?>>
							Denegado
						</option>
					</select>
				</div>
			</div>
			<div class="filtro_ofertas" style="width: 385px; padding-top: 10px; margin: auto;">
				<div class="contact_form_area" >
					<input type="submit" class="classname" value="Buscar"/>
				</div>
				<div class="contact_form_area">
					<a href="<?php echo base_url().'administrador/inscripcionesOferta/' . $oferta->id_oferta . '/0/1'; ?>">
						<input type="button" class="classname" value="Todas"/>
					</a>
				</div>
			</div>
		</form>
	<div id="texto-ayuda">
		<table>
			<tr>
				<td colspan="2" style="padding: 5px; text-align: right;">
					<img title="Cerrar" style="cursor: pointer; width: 12px;" src="<?php echo base_url();?>images/tachado_checkbox.png"
							onclick="$('#texto-ayuda').fadeOut('slow');" />
				</td>
			</tr>
			<tr>
				<td style="padding: 5px;">
				</td>
				<td style="padding: 5px;">
					Pendiente (La inscripción no ha sido gestionada)
				</td>
			</tr>
			<tr>
				<td style="padding: 5px;">
				</td>
				<td style="padding: 5px;">
					Autorizado - Falta Pago (Se ha autorizado al anunciante para quedarse con la oferta. Falta recibir la confirmación del pago)
				</td>
			</tr>
			<tr>
				<td style="padding: 5px;">
				</td>
				<td style="padding: 5px;">
					Pagado (La confirmación del pago para la oferta ha sido recibida)
				</td>
			</tr>
			<tr>
				<td style="padding: 5px;">
				</td>
				<td style="padding: 5px;">
					Denegado (Se ha denegado la inscripción del anunciante para esta oferta)
				</td>
			</tr>
			<tr>
				<td colspan="2" style="padding: 5px;">
					Después de cambiar los estados de las inscripciones pincha en el botón realizar cambios para que se hagan efectivos.
				</td>
			</tr>
		</table>
	</div>
	<div class="help" onclick="$('#texto-ayuda').fadeIn('slow');">
		<img src="<?php echo base_url(); ?>images/ayuda.png" alt="Ayuda" title="Ayuda">
	</div>
		<div style="margin-top: 20px;">
			<table style="width:960px;" class="tab_fav_his">
				<tr class="tr_fav">
					<td class="td_pdtes" style="border-top-left-radius: 10px;background-color: rgb(84, 84, 84);">
						Anunciante
					</td>
					<td class="td_pdtes" style="background-color: rgb(84, 84, 84);">
						Agencia
					</td>
					<td style="background-color: rgb(84, 84, 84);"class="td_pdtes">
						Fecha inscripción
					</td>
					<td class="td_pdtes" style="border-top-right-radius: 10px;background-color: rgb(84, 84, 84);">
						Estado
					</td>
				</tr>
<?php
	if(!empty($inscripciones)){
		$i=0;

		foreach($inscripciones as $inscripcion) {
?>
				<tr <?php if ($i%2==0) echo 'class="item_fav"';?>>
					<td style="padding: 5px 0;">
						<a href="<?php echo base_url() . 'administrador/editarAnunciante/' . $inscripcion->id_cliente; ?>" target="_blank"
								style="text-decoration: none;">
							<?php echo $inscripcion->cliente; ?>
						</a>
					</td>
					<td style="padding: 5px 0;">
						<a href="<?php echo base_url() . 'administrador/editarAgencia/' . $inscripcion->id_agencia; ?>" target="_blank"
								style="text-decoration: none;">
							<?php echo $inscripcion->agencia; ?>
						</a>
					</td>
					<td style="padding: 5px 0;">
						<?php echo $inscripcion->fecha ?>
					</td>
					<td style="padding: 5px 0; vertical-align: middle;" class="select_permiso">
						<select name="permiso" autocomplete="off" class="contacto_form_input" onchange="mostrarBotonCambio();" data-id="<?php echo $inscripcion->id_cliente; ?>" >
							<option value="0" <?php echo ($inscripcion->estado == 0) ? 'selected="selected"' : ''; ?>>
								Pendiente
							</option>
							<option value="1" <?php echo ($inscripcion->estado == 1) ? 'selected="selected"' : ''; ?>>
								Autorizada - Falta Pago
							</option>
							<option value="2" <?php echo ($inscripcion->estado == 2) ? 'selected="selected"' : ''; ?>>
								Pagado
							</option>
							<option value="3" <?php echo ($inscripcion->estado == 3) ? 'selected="selected"' : ''; ?>>
								Denegado
							</option>
						</select>
					</td>
				</tr>
<?php
			$i++;
		}
	}
	else{
?>
				<tr>
					<td>
						No existen inscripciones.
					</td>
				</tr>
<?php
	}
?>
			</table>
			<div class="contact_form_area" id="botonCambios" style="width: 300px; float: right; display: none;"
					onclick="realizarCambios();">
				<input type="button" class="classname" value="Guardar cambios" />
			</div>
			<div class="paginacion">
				<?php echo $paginacion; ?>
			</div>
		</div>
		<form id="form_inscripciones" action="<?php echo base_url() .'administrador/cambiarEstadoInscripciones/' . $oferta->id_oferta; ?>"
				method="post" style="display: none;">
			<input type="hidden" name="cambios" id="cambios" value=""/>
			<input type="hidden" name="pagina" id="pagina" value="<?php echo $filtro['pagina']; ?>"/>
		</form>
	</div>
</div>