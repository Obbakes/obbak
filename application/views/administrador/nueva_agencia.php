<script type="text/javascript" src="<?php echo base_url();?>js/jquery-ui.js"></script>
<link rel="stylesheet" href="<?php echo base_url();?>css/jquery-ui.min.css" type="text/css" media="screen"/>
<?php $this->load->helper('form'); ?>
<div>
	<div style="position: relative; width: 700px; display:inline-table; margin-bottom: 5px; text-align: left; right: 20px;">
		<?php echo form_open_multipart("administrador/nuevaAgencia/1"); ?>
			<div class="contentgrid">
				<div class="grid-12">
					<div class="grid-6">
						<h1 class="titlePerfilA">
							<i class="icon icon-create"></i>
							Creando agencia
						</h1>
					</div>
					<div class="contact_form_area grid-6 modSubmitButtom" >
						<input type="submit" class="cupid-orange" id="sign_in_sub" value="Guardar Cambios" >
					</div>
				</div>
				<div class="grid-6 formMod">
					<p >
						<label for="nombre">
							Agencia:
						</label>
						<span class="iconP icon-built"><input type="text" id="nombre" class="contacto_form_input <?php echo (form_error('nombre') != '') ? 'input_error' : ''; ?>" placeholder=""
							name="nombre" value="<?php echo set_value('nombre'); ?>" /></span>
						<?php echo form_error('nombre'); ?>
					</p>
					<p >
						<label for="email">
							Email:
						</label>
						<span class="iconP icon-email"><input type="text" id="email" class="contacto_form_input" placeholder="" name="email" value="<?php echo set_value('email'); ?>" /></span>
					</p>
					<p >
						<label for="telefono">
							Teléfono:
						</label>
						<span class="iconP icon-phone" ><input type="text" id="telefono" class="contacto_form_input <?php echo (form_error('telefono') != '') ? 'input_error' : ''; ?>" placeholder=""
							name="telefono" value="<?php echo set_value('telefono'); ?>" /></span>
						<?php echo form_error('telefono'); ?>
					</p>
					<p >
						<label for="direccion">
							Dirección:
						</label>
						<span class="iconP icon-address"><input type="text" id="direccion" class="contacto_form_input <?php echo (form_error('direccion') != '') ? 'input_error' : ''; ?>" placeholder=""
							name="direccion" value="<?php echo set_value('direccion'); ?>"/></span>
						<?php echo form_error('direccion'); ?>
					</p>
				</div>
				<div class="grid-6 formMod right">
					<p >
						<label for="cp">
							Código Postal:
						</label>
						<span class="iconP icon-cp" ><input type="text" id="cp" class="contacto_form_input <?php echo (form_error('cp') != '') ? 'input_error' : ''; ?>" placeholder=""
							name="cp" value="<?php echo set_value('cp'); ?>" /></span>
						<?php echo form_error('cp'); ?>
					</p>
					<p>
						<label for="cif">
							CIF:
						</label>
						<span class="iconP icon-id" ><input type="text" id="cif" class="contacto_form_input <?php echo (form_error('cif') != '') ? 'input_error' : ''; ?>" placeholder=""
							name="cif" value="<?php echo set_value('cif'); ?>" /></span>
						<?php echo form_error('cif'); ?>
					</p>
					<p>
						<label for="poblacion">
							Población:
						</label>
						<span class="iconP icon-country" ><input type="text" id="poblacion" class="contacto_form_input <?php echo (form_error('poblacion') != '') ? 'input_error' : ''; ?>" placeholder=""
							name="poblacion" value="<?php echo set_value('poblacion'); ?>"/></span>
						<?php echo form_error('poblacion'); ?>
					</p>
					<p>
						<label for="porcentaje">
							Porcentaje:
						</label>
						<span class="iconP icon-percentage" ><input type="text" id="porcentaje" class="contacto_form_input <?php echo (form_error('porcentaje') != '') ? 'input_error' : ''; ?>" placeholder=""
							name="porcentaje" value="<?php echo set_value('porcentaje'); ?>" /></span>
						<?php echo form_error('porcentaje'); ?>
					</p>
				</div>
			</div>
			<div class="contact_form_area" >
				<a href="<?php echo base_url() ?>administrador/agencias">
					<input type="button" class="cupid-orange" id="sign_in_sub" value="Volver a agencias" style="width: 50%; float: left; margin-top: 0;"/>
				</a>
			</div>
		<?php echo form_close(); ?>
	</div>
</div>
