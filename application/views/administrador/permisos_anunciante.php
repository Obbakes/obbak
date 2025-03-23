<script type="text/javascript" src="<?php echo base_url();?>js/jquery-ui.js"></script>
<script type="application/javascript">
    
	function mostrarBotonCambio(){
		$('#botonCambios').css('display', 'block');
		$('#changeOpt').val(0);
	}

	function realizarCambios(){
		var cambios = '';

		$('.select_permiso').each(function(){
			if(cambios != '')
				cambios += ' ';

			cambios += $(this).find('select').attr('data-id') + ' ' + $(this).find('select').val();
		});

		$('#cambios').val(cambios);

		$('#form_permisos').submit();
	}
	function cambiarPermiso(value){
		$('.contacto_form_input.changeGroup > option[value="'+ value + '"]').attr('selected', 'selected');
		$('#botonCambios').css('display', 'block');
	}
</script>
<link rel="stylesheet" href="<?php echo base_url();?>css/jquery-ui.min.css" type="text/css" media="screen"/>
<?php $this->load->helper('form'); ?>
<div>
	<div style="position: relative; width: 360px; display:inline-table; margin-bottom: 5px; text-align: left; right: 20px;">
		<h3 style="text-align: center;font-size: 22px;font-weight: bold;">
			Editar Anunciante (<?php echo $cliente->nombre; ?>)
		</h3>
		<div style="display: table; margin: auto; width: 50%; padding: 0;">
			<div style="display: table-cell; width: 50%; text-align: center;">
				<input type="button" class="boton-pestanya-activo" value="Datos" style="width: 90%;"
					onclick="document.location.href='<?php echo base_url() . 'administrador/editarAnunciante/' . $cliente->id_cliente; ?>';">
			</div>
			<div style="display: table-cell; width: 50%; text-align: center;">
				<input type="button" class="boton-pestanya-inactivo" value="Permisos" disabled="disabled" style="width: 90%;">
			</div>
		</div>
		<form accept-charset="utf-8" method="post" action="<?php echo base_url() . 'administrador/permisosAnunciante/' . $cliente->id_cliente . '/1'; ?>" >
			<div class="filtro_ofertas">
				<div>
					<span style="font-size: 16px;" >
						Estado:
					</span>
					<select id="estado" name="estado" class="contacto_form_input">
						<option value="todos" <?php echo (!isset($filtro['estado']) || $filtro['estado'] == 'todos') ? 'selected="selected"' : ''; ?>>
							Todos
						</option>
						<option value="1" <?php echo (isset($filtro['estado']) && $filtro['estado'] == '1') ? 'selected="selected"' : ''; ?>>
							Autorizado
						</option>
						<option value="2" <?php echo (isset($filtro['estado']) && $filtro['estado'] == '2') ? 'selected="selected"' : ''; ?>>
							NO Autorizado
						</option>
                                                <option value="3" <?php echo (isset($filtro['estado']) && $filtro['estado'] == '3') ? 'selected="selected"' : ''; ?>>
							Pendiente Autorizar
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
					<a href="<?php echo base_url().'administrador/permisosAnunciante/' . $cliente->id_cliente; ?>">
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
					Autorizado
				</td>
			</tr>
			<tr>
				<td style="padding: 5px;">
				</td>
				<td style="padding: 5px;">
					NO Autorizado
				</td>
			</tr>
                        <tr>
				<td style="padding: 5px;">
				</td>
				<td style="padding: 5px;">
					Pendiente Autorizar
				</td>
			</tr>
			<tr>
				<td colspan="2" style="padding: 5px;">
					Después de cambiar los permisos de los anunciantes pincha en el botón realizar cambios para que se hagan efectivos.
				</td>
			</tr>
		</table>
	</div>
	<div class="help" onclick="$('#texto-ayuda').fadeIn('slow');">
		<img src="<?php echo base_url(); ?>images/ayuda.png" alt="Ayuda" title="Ayuda">
	</div>
		<div style="margin-top: 20px;">
			<div class="actionGroup">
				Acciones para la lista
				<select id="changeOpt" name="cambiarEstado" onchange="cambiarPermiso(this.value);">
					<option value="0" selected disabled>Selecciona..</option>}
					<option value="1" >Autorizar</option>
					<option value="2" >NO Autorizar</option>
				</select>
			</div>
			<table style="width:998px;" class="tab_fav_his">
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
					<td class="td_pdtes" style="border-top-right-radius: 10px;background-color: rgb(84, 84, 84);">
						Permiso
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
					<td style="vertical-align: middle;" class="select_permiso">                                                
						<select name="permiso" class="contacto_form_input changeGroup" data-id="<?php echo $permiso->id_medio; ?>" autocomplete="off"
								onchange="mostrarBotonCambio();" <?php echo (!is_numeric($permiso->estado) || $permiso->estado == 3) ? ' style="background-color: rgba(255, 0, 0, 0.3);"' : ''; ?>>

							<option value="1" <?php echo (is_numeric($permiso->estado) && $permiso->estado == 1) ? 'selected="selected"' : ''; ?>>
								Autorizado
							</option>
							<option value="2" <?php echo (is_numeric($permiso->estado) && $permiso->estado == 2) ? 'selected="selected"' : ''; ?>>
								NO Autorizado
							</option>
                            <option value="3" <?php echo (!is_numeric($permiso->estado) || $permiso->estado == 3) ? 'selected="selected"' : ''; ?>>
								Pendiente Autorizar
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
		<form id="form_permisos" action="<?php echo base_url() .'administrador/cambiarPermisosAnunciante/' . $cliente->id_cliente; ?>"
				method="post" style="display: none;">
			<input type="hidden" name="cambios" id="cambios" value=""/>
			<input type="hidden" name="pagina" id="pagina" value="<?php echo $filtro['pagina']; ?>"/>
		</form>
	</div>
</div>
