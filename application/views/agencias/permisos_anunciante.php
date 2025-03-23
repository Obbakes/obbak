<script type="text/javascript" src="<?php echo base_url();?>js/jquery-ui.js"></script>
<script type="application/javascript">
	function mostrarBotonCambio(){
		if(realizarCambios.numCambios == 0)
			$('#botonCambios').css('display', 'none');
		else
			$('#botonCambios').css('display', 'block');
	}

	function checkInput(obj){
		if($(obj).prop('checked'))
			realizarCambios.numCambios++;
		else if(realizarCambios.numCambios > 0)
			realizarCambios.numCambios--;

		mostrarBotonCambio();
	}

	function realizarCambios(){
		var cambios = '';

		$('.select_permiso input:checked').each(function(){
			if(cambios != '')
				cambios += ' ';

			cambios += $(this).val() + ' 1';
		});

		$('#cambios').val(cambios);

		$('#form_permisos').submit();
	}

	$(document).ready(function(){
		realizarCambios.numCambios = 0;
	});
</script>
<link rel="stylesheet" href="<?php echo base_url();?>css/jquery-ui.min.css" type="text/css" media="screen"/>
<?php $this->load->helper('form'); ?>
<div>
	<div class="generalEdit">
		<?php $this->load->view('agencias/menu_opciones'); ?>
		<form accept-charset="utf-8" method="post" action="<?php echo base_url() . 'agencias/permisosAnunciante/' . $cliente->id_cliente . '/1'; ?>"
				style="text-align: center; margin: 20px;">
			<div class="filtro_ofertas">
				<div>
					<span style="font-size: 16px;" >
						Estado:
					</span>
					<select id="estado" name="estado" class="contacto_form_input">
						<option value="todos" <?php echo (!isset($filtro['estado']) || $filtro['estado'] == 'todos') ? 'selected="selected"' : ''; ?>>
							Todos
						</option>
						<option value="0" <?php echo (isset($filtro['estado']) && $filtro['estado'] == '0') ? 'selected="selected"' : ''; ?>>
							Solicitados
						</option>
						<option value="1" <?php echo (isset($filtro['estado']) && $filtro['estado'] == '1') ? 'selected="selected"' : ''; ?>>
							Con permiso
						</option>
						<option value="2" <?php echo (isset($filtro['estado']) && $filtro['estado'] == '2') ? 'selected="selected"' : ''; ?>>
							Sin permiso
						</option>
					</select>
				</div>
				<div>
					<span style="font-size: 16px;" >
						Tipo de medio:
					</span>
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
			</div>
			<div class="filtro_ofertas" style="width: 385px; padding-top: 10px; margin: auto;">
				<div class="contact_form_area" >
					<input type="submit" class="classname" value="Buscar"/>
				</div>
				<div class="contact_form_area">
					<a href="<?php echo base_url().'agencias/permisosAnunciante/' . $cliente->id_cliente . '/0/1'; ?>">
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
					Después de especificar que permisos quieres solicitar pincha en el botón realizar cambios para que se hagan efectivos.
				</td>
			</tr>
		</table>
		<div style="margin-top: 20px;">
			<table style="width:100%;" class="tab_fav_his">
				<tr class="tr_fav">
					<td class="td_pdtes" style="border-top-left-radius: 10px;background-color: rgb(84, 84, 84);">
						Medio
					</td>
					<td style="background-color: rgb(84, 84, 84);"class="td_pdtes">
						Tipo de Medio
					</td>
					<td style="background-color: rgb(84, 84, 84);"class="td_pdtes">
						Logo
					</td>
					<td style="background-color: rgb(84, 84, 84);"class="td_pdtes">
						Permiso
					</td>
					<td class="td_pdtes" style="border-top-right-radius: 10px;background-color: rgb(84, 84, 84);">
						¿Solicitar?
					</td>
				</tr>
<?php
	if(!empty($permisos)){
		$i=0;

		foreach($permisos as $permiso) {
?>
				<tr <?php if ($i%2==0) echo 'class="item_fav"';?>>
					<td>
						<?php echo $permiso->nombre ?>
					</td>
					<td>
						<?php echo $permiso->tipo ?>
					</td>
					<td>
						<img style="height:45px;" src="<?php echo base_url() . $permiso->logo?>"/>
					</td>
					<td>
						<?php echo (!is_numeric($permiso->estado))
									? 'Pendiente de solicitar'
									: (($permiso->estado == 0)
										? 'Solicitado'
										: (($permiso->estado == 1)
											? 'Aceptado'
											: 'Rechazado')); ?>
					</td>
					<td style="vertical-align: middle;" class="select_permiso">
<?php
			if(!is_numeric($permiso->estado) || $permiso->estado == 2){
?>
						<input type="checkbox" name="permiso" value="<?php echo $permiso->id_medio; ?>" autocomplete="off" onclick="checkInput(this);"/>
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
						No existen medios.
					</td>
				</tr>
<?php
	}
?>
			</table>
			<a onclick="realizarCambios();" id="botonCambios" style="text-decoration: none; display: none; width: 200px; float: right;">
				<div class="contact_form_area" style="width: 200px;">
					<input type="button" class="classname" value="Realizar cambios" />
				</div>
			</a>
			<div class="paginacion">
				<?php echo $paginacion; ?>
			</div>
		</div>
		<form id="form_permisos" action="<?php echo base_url() .'agencias/cambiarPermisosAnunciante/' . $cliente->id_cliente; ?>"
				method="post" style="display: none;">
			<input type="hidden" name="cambios" id="cambios" value=""/>
			<input type="hidden" name="pagina" id="pagina" value="<?php echo $filtro['pagina']; ?>"/>
		</form>
	</div>
</div>
