<script type="text/javascript" src="<?php echo base_url();?>js/jquery-ui.js"></script>
<link rel="stylesheet" href="<?php echo base_url();?>css/jquery-ui.min.css" type="text/css" media="screen"/>
<?php $this->load->helper('form'); ?>
<script type="text/javascript">
<!--
<?php
	if(!empty($datos_acceso_enviados)){
?>
	$(document).ready(function(){
		alert('<?php echo $datos_acceso_enviados; ?>');
	});
<?php
	}
?>
//-->
</script>
<div>
	<div class="generalEdit">
		<?php $this->load->view('agencias/menu_opciones'); ?>
		<?php echo form_open_multipart("agencias/editarAnunciante/" . $cliente->id_cliente . '/1'); ?>
<?php
	if(!empty($cliente->email)){
?>
			<div style="display: table; margin: 20px auto; width: 35%; padding: 0;">
				<div style="display: table-cell; width: 100%; text-align: center;">
					<input type="button" class="boton-pestanya-activo" value="Enviar datos de acceso" style="width: 90%;"
						onclick="if(confirm('¿Quieres enviar los datos de acceso al anunciante?')) document.location.href='<?php echo base_url() . 'agencias/enviarDatosAcceso/' . $cliente->id_cliente; ?>';">
				</div>
			</div>
<?php
	}
?>
			<div class="contentgrid">
				<div class="grid-6 formMod">
					<p >
						<label for="nombre">
							Empresa:
						</label>
						<span class="iconP icon-built"><input type="text" id="nombre" class="contacto_form_input <?php echo (form_error('nombre') != '') ? 'input_error' : ''; ?>" placeholder="" name="nombre" value="<?php echo (!empty($validado)) ? set_value('nombre') : $cliente->nombre; ?>" /></span>
						<?php echo form_error('nombre'); ?>
					</p>
					<p >
						<label for="nombre_contacto">
							Nombre:
						</label>
						<span class="iconP icon-user"><input type="text" id="nombre_contacto" class="contacto_form_input <?php echo (form_error('nombre_contacto') != '') ? 'input_error' : ''; ?>" placeholder="" name="nombre_contacto" value="<?php echo (!empty($validado)) ? set_value('nombre_contacto') : $cliente->nombre_contacto; ?>"/></span>
						<?php echo form_error('nombre_contacto'); ?>
					</p>
					<p >
						<label for="apellidos_contacto">
							Apellidos:
						</label>
						<span class="iconP icon-user"><input type="text" id="apellidos_contacto" class="contacto_form_input <?php echo (form_error('apellidos_contacto') != '') ? 'input_error' : ''; ?>" placeholder="" name="apellidos_contacto" value="<?php echo (!empty($validado)) ? set_value('apellidos_contacto') : $cliente->apellidos_contacto; ?>" /></span>
						<?php echo form_error('apellidos_contacto'); ?>
					</p>
					<p>
						<label for="email">
							Email:
						</label>
						<span class="iconP icon-email"><input type="text" id="email" class="contacto_form_input <?php echo (form_error('email') != '') ? 'input_error' : ''; ?>" placeholder="" name="email" value="<?php echo (!empty($validado)) ? set_value('email') : $cliente->email; ?>"  /></span>
						<?php echo form_error('email'); ?>
					</p>
					<p >
						<label for="fecha_registro">
							Fecha de registro:
						</label>
						<span class="iconP icon-date"><input type="text" id="fecha_registro" class="contacto_form_input" disabled="disabled" name="fecha_registro" value="<?php echo $cliente->fecha_registro; ?>"  /></span>
					</p>
					<p >
						<label for="telefono">
							Teléfono:
						</label>
						<span class="iconP icon-phone"><input type="text" id="telefono" class="contacto_form_input <?php echo (form_error('telefono') != '') ? 'input_error' : ''; ?>" placeholder="" name="telefono" value="<?php echo (!empty($validado)) ? set_value('telefono') : $cliente->telefono; ?>"  /></span>
						<?php echo form_error('telefono'); ?>
					</p>
					<p >
						<label for="direccion">
							Dirección:
						</label>
						<span class="iconP icon-address"><input type="text" id="direccion" class="contacto_form_input <?php echo (form_error('direccion') != '') ? 'input_error' : ''; ?>" placeholder="" name="direccion" value="<?php echo (!empty($validado)) ? set_value('direccion') : $cliente->direccion; ?>"  /></span>
						<?php echo form_error('direccion'); ?>
					</p>
				</div>
				<div class="grid-6 formMod right">
					<p >
						<label for="cp">
							Código Postal:
						</label>
						<span class="iconP icon-cp"><input type="text" id="cp" class="contacto_form_input <?php echo (form_error('cp') != '') ? 'input_error' : ''; ?>" placeholder="" name="cp" value="<?php echo (!empty($validado)) ? set_value('cp') : $cliente->cp; ?>"  /></span>
						<?php echo form_error('cp'); ?>
					</p>
					<p >
						<label for="cif">
							CIF:
						</label>
						<span class="iconP icon-id"><input type="text" id="cif" class="contacto_form_input <?php echo (form_error('cif') != '') ? 'input_error' : ''; ?>" placeholder="" name="cif" value="<?php echo (!empty($validado)) ? set_value('cif') : $cliente->cif; ?>"  /></span>
						<?php echo form_error('cif'); ?>
					</p>
					<p>
						<label for="poblacion">
							Población:
						</label>
						<span class="iconP icon-country"><input type="text" id="poblacion" class="contacto_form_input <?php echo (form_error('poblacion') != '') ? 'input_error' : ''; ?>" placeholder="" name="poblacion" value="<?php echo (!empty($validado)) ? set_value('poblacion') : $cliente->poblacion; ?>"  /></span>
						<?php echo form_error('poblacion'); ?>
					</p>
					<p >
						<label for="web">
							Web:
						</label>
						<span class="iconP icon-web"><input type="text" id="web" class="contacto_form_input <?php echo (form_error('web') != '') ? 'input_error' : ''; ?>" placeholder="" name="web" value="<?php echo (!empty($validado)) ? set_value('web') : $cliente->web; ?>"  /></span>
						<?php echo form_error('web'); ?>
					</p>
					<p >
						<label for="agencia">
							Pertenece a:
						</label>
						<span class=""><input type="text" id="agencia" class="contacto_form_input" disabled="disabled" name="agencia"
							value="<?php echo $cliente->agencia; ?>"  /></span>
					</p>
					<p >
						<label for="estado">
							Estado:
						</label>
						<span class=" "><input type="text" id="estado" class="contacto_form_input" disabled="disabled" name="estado"
							value="<?php echo ($cliente->estado == 0) ? 'Aceptado' : (($cliente->estado == 1) ? 'Pendiente' : 'Denegado'); ?>"  /></span>
					</p>
				</div>
			</div>
			<div class="contentgrid">
				<div class="grid-6">
					<div class="contact_form_area" >
						<a href="javascript:history.back(-1);">
							<input type="button" class="cupid-orange" id="sign_in_sub" value="◄ Volver" />
						</a>
					</div>
				</div>
				<div class="grid-6">
					<div class="contact_form_area" >
						<input type="submit" class="cupid-orange" id="sign_in_sub" value="Guardar Cliente" />
					</div>
				</div>
			</div>
		<?php echo form_close(); ?>
	</div>
</div>
