<script type="text/javascript" src="<?php echo base_url();?>js/jquery-ui.js"></script>
<link rel="stylesheet" href="<?php echo base_url();?>css/jquery-ui.min.css" type="text/css" media="screen"/>
<script type="application/javascript">
	function desinscribir_oferta(){
		if(!confirm('¿Quieres desinscribir a este anunciante de estas ofertas?'))
			return;

		var ofertas = '';

		$('.select_permiso input:checked').each(function(){
			if(ofertas != '')
				ofertas += ' ';

			ofertas += $(this).val();
		});

		$('#ofertas').val(ofertas);
		$('#form_inscripciones').submit();
	}

	function checkInput(obj){
		if($(obj).prop('checked'))
			desinscribir_oferta.numCambios++;
		else if(desinscribir_oferta.numCambios > 0)
			desinscribir_oferta.numCambios--;

		mostrarBotonCambio();
	}

	function mostrarBotonCambio(){
		if(desinscribir_oferta.numCambios == 0)
			$('#botonCambios').css('display', 'none');
		else
			$('#botonCambios').css('display', 'block');
	}

	$(document).ready(function(){
		desinscribir_oferta.numCambios = 0;
	});
</script>
<?php $this->load->helper('form'); ?>
<div>
	<div class="generalEdit">
		<?php $this->load->view('agencias/menu_opciones'); ?>
		<form accept-charset="utf-8" method="post" action="<?php echo base_url() . 'agencias/inscripcionesAnunciante/' . $cliente->id_cliente . '/1'; ?>"
				style="text-align: center; margin: 20px;">
			<div class="filtro_ofertas" style="width: 500px; margin: auto;">
				<div>
					<span style="font-size: 16px;" >
						Estado:
					</span>
					<select id="estado" name="estado" class="contacto_form_input">
						<option value="todos" <?php echo (!isset($filtro['estado']) || $filtro['estado'] == 'todos') ? 'selected="selected"' : ''; ?>>
							Todos
						</option>
						<option value="0" <?php echo (isset($filtro['estado']) && $filtro['estado'] == '0') ? 'selected="selected"' : ''; ?>>
							Pendiente
						</option>
						<option value="1" <?php echo (isset($filtro['estado']) && $filtro['estado'] == '1') ? 'selected="selected"' : ''; ?>>
							Autorizada
						</option>
						<option value="2" <?php echo (isset($filtro['estado']) && $filtro['estado'] == '2') ? 'selected="selected"' : ''; ?>>
							Pagada
						</option>
						<option value="3" <?php echo (isset($filtro['estado']) && $filtro['estado'] == '3') ? 'selected="selected"' : ''; ?>>
							Cancelada
						</option>
					</select>
				</div>
			</div>
			<div class="filtro_ofertas" style="width: 385px; padding-top: 10px; margin: auto;">
				<div class="contact_form_area" >
					<input type="submit" class="classname" value="Buscar"/>
				</div>
				<div class="contact_form_area">
					<a href="<?php echo base_url().'agencias/inscripcionesAnunciante/' . $cliente->id_cliente . '/0/1'; ?>">
						<input type="button" class="classname" value="Todas"/>
					</a>
				</div>
			</div>
		</form>
		<table id="texto-ayuda" style="width: 400px; margin: auto;">
			<tr>
				<td colspan="2" style="padding: 5px; text-align: right;">
					<img title="Cerrar" style="cursor: pointer; width: 12px;" src="http://192.168.1.2/nolimitsmedia/images/tachado_checkbox.png"
							onclick="$('#texto-ayuda').fadeOut('slow');" />
				</td>
			</tr>
			<tr>
				<td colspan="2" style="padding: 5px;">
					Después de especificar de que ofertas quieres desinscribir al anunciante pincha en el botón realizar cambios para que se hagan efectivos.
				</td>
			</tr>
		</table>
		<div style="margin-top: 20px;">
			<table style="width:960px;" class="tab_fav_his">
				<tr class="tr_fav">
					<td class="td_pdtes" style="border-top-left-radius: 10px;background-color: rgb(84, 84, 84);">
						Oferta
					</td>
					<td style="background-color: rgb(84, 84, 84);"class="td_pdtes">
						Fecha inscripción
					</td>
					<td class="td_pdtes" style="background-color: rgb(84, 84, 84);">
						Estado
					</td>
					<td class="td_pdtes" style="border-top-right-radius: 10px;background-color: rgb(84, 84, 84);">
						Desinscribirse
					</td>
				</tr>
<?php
	if(!empty($inscripciones)){
		$i=0;

		foreach($inscripciones as $inscripcion) {
?>
				<tr <?php if ($i%2==0) echo 'class="item_fav"';?>>
					<td style="padding: 5px 0;">
						<a href="<?php echo base_url() . 'anunciantes/oferta/' . $inscripcion->id_oferta; ?>" target="_blank" style="text-decoration: none;">
							<?php echo $inscripcion->oferta; ?>
						</a>
					</td>
					<td style="padding: 5px 0;">
						<?php echo $inscripcion->fecha ?>
					</td>
					<td style="padding: 5px 0;">
						<?php echo ($inscripcion->estado == 0) ? 'Pendiente' : (($inscripcion->estado == 1) ? 'Autorizada' : (($inscripcion->estado == 1) ? 'Pagada' : 'Cancelada')); ?>
					</td>
					<td style="padding: 5px 0; vertical-align: middle;" class="select_permiso">
<?php
			if($inscripcion->estado == 0){
?>
						<input type="checkbox" name="permiso" value="<?php echo $inscripcion->id_oferta; ?>" autocomplete="off" onclick="checkInput(this);"/>
<?php
			}
?>
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
					onclick="desinscribir_oferta();">
				<input type="button" class="classname" value="Desinscribir de las seleccionadas" />
			</div>
			<div class="paginacion">
				<?php echo $paginacion; ?>
			</div>
		</div>
		<form id="form_inscripciones" action="<?php echo base_url() .'agencias/desinscribirAnunciante/' . $cliente->id_cliente; ?>"
				method="post" style="display: none;">
			<input type="hidden" name="ofertas" id="ofertas" value=""/>
		</form>
	</div>
</div>