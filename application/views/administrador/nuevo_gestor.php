<script type="text/javascript" src="<?php echo base_url();?>js/jquery-ui.js"></script>
<link rel="stylesheet" href="<?php echo base_url();?>css/jquery-ui.min.css" type="text/css" media="screen"/>
<?php $this->load->helper('form'); ?>
<div>
	<div style="position: relative; width: 700px; display:inline-table; margin-bottom: 5px; text-align: left; right: 20px;">
		<?php echo form_open_multipart("administrador/nuevoGestorMedio/1"); ?>
			<div class="contentgrid">
				<div class="grid-12">
					<div class="grid-6">
						<h1 class="titlePerfilA">
							<i class="icon icon-create"></i>
							Creando Gestor de Medios
						</h1>
					</div>
					<div class="contact_form_area grid-6 modSubmitButtom" >
						<input type="submit" class="cupid-orange" id="sign_in_sub" value="Guardar Cambios" >
					</div>
				</div>
				<div class="grid-6 formMod">
					<p >
						<label for="nombre">
							Nombre:
						</label>
						<span class="iconP icon-built"><input type="text" id="nombre" class="contacto_form_input <?php echo (form_error('nombre') != '') ? 'input_error' : ''; ?>" placeholder=""
							name="nombre" value="<?php echo set_value('nombre'); ?>" /></span>
						<?php echo form_error('nombre'); ?>
					</p>
					
					
				</div>
				<div class="grid-6 formMod right">
					
                                        <p >
						<label for="email">
							Email:
						</label>
						<span class="iconP icon-email"><input type="text" id="email" class="contacto_form_input" placeholder="" name="email" value="<?php echo set_value('email'); ?>" /></span>
                                                <?php echo form_error('email'); ?>
					</p>
                                        <p>
                                                <label class="oferta_label" for="publicada">
                                                        ¿Notificar alta por email?
                                                </label>
                                                <input type="checkbox" id="notificacion" name="notificacion" value="1"
                                                        <?php echo (!empty($validado)) ? set_checkbox('notificacion', 1) : ''; ?> style="width: auto;"/>
                                                <?php echo form_error('notificacion'); ?>
                                        </p>
				</div>
			</div>
			<div class="contact_form_area" >
				<a href="<?php echo base_url() ?>administrador/gestorMedios">
					<input type="button" class="cupid-orange" id="sign_in_sub" value="Volver a Gestores de Medios" style="width: 50%; float: left; margin-top: 0;"/>
				</a>
			</div>
		<?php echo form_close(); ?>
	</div>
</div>
