<script type="text/javascript" src="<?php echo base_url();?>js/jquery-ui.js"></script>
<link rel="stylesheet" href="<?php echo base_url();?>css/jquery-ui.min.css" type="text/css" media="screen"/>
<?php $this->load->helper('form'); ?>
<div>
	<div style="position: relative; width: 360px; display:inline-table; margin-bottom: 5px; text-align: left; right: 20px;">
		<h1 class="titlePerfilA">
			<i class="icon icon-create"></i>
			Creando Cliente
		</h1>
		<?php echo form_open_multipart("agencias/nuevoAnunciante/1"); ?>
			<div class="contentgrid">
				<div class="grid-12 formMod">
					<p >
						<label for="nombre">
							Empresa:
						</label>
						<span class="iconP icon-built"><input type="text" id="nombre" class="contacto_form_input <?php echo (form_error('nombre') != '') ? 'input_error' : ''; ?>" placeholder="" name="nombre" value="<?php echo set_value('nombre'); ?>" /></span>
						<?php echo form_error('nombre'); ?>
					</p>
					<p >
						<label for="email">
							Email:
						</label>
						<span class="iconP icon-email"><input type="text" id="email" class="contacto_form_input <?php echo (form_error('email') != '') ? 'input_error' : ''; ?>" placeholder="" name="email" value="<?php echo set_value('email'); ?>" /></span>
						<?php echo form_error('email'); ?>
					</p>
					<p >
						<label for="telefono">
							Teléfono:
						</label>
						<span class="iconP icon-phone"><input type="text" id="telefono" class="contacto_form_input <?php echo (form_error('telefono') != '') ? 'input_error' : ''; ?>" placeholder="" name="telefono" value="<?php echo set_value('telefono'); ?>" /></span>
						<?php echo form_error('telefono'); ?>
					</p>
					<p >
						<label for="web">
							Web:
						</label>
						<span class="iconP icon-web"><input type="text" id="web" class="contacto_form_input <?php echo (form_error('web') != '') ? 'input_error' : ''; ?>" placeholder="" name="web" value="<?php echo set_value('web'); ?>" /></span>
						<?php echo form_error('web'); ?>
					</p>
				</div>
			</div>
			<div class="contentgrid">
				<div class="grid-6">
					<div class="contact_form_area" >
						<a href="<?php echo base_url() ?>agencias/anunciantes">
							<input type="button" class="cupid-orange" id="sign_in_sub" value="◄ Volver"/>
						</a>
					</div>
				</div>
				<div class="grid-6">
					<div class="contact_form_area" >
						<input type="submit" class="cupid-orange" id="sign_in_sub" value="Crear  Cliente" />
					</div>
				</div>
		<?php echo form_close(); ?>
	</div>
</div>
