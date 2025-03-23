<script type="text/javascript" src="<?php echo base_url();?>js/jquery-ui.js"></script>
<?php
	if(!empty($mensaje_newsletter_confirmacion)){
?>
<script>
	$(document).ready(function(){
		alert('<?php echo $mensaje_newsletter_confirmacion; ?>');
	});
</script>
<?php
	}
?>
<link rel="stylesheet" href="<?php echo base_url();?>css/jquery-ui.min.css" type="text/css" media="screen"/>
<?php $this->load->helper('form'); ?>
<div>
	<div style="position: relative; margin-bottom: 5px; text-align: left; right: 20px;">
		<h3 style="text-align: center;">
			Nueva Newsletter (Confirmación)
		</h3>
		<div class="contact_form_area" style="display: table; width: 100%; text-align: center;">
			<div style="display: table-cell; width: 100%; text-align: center;">
				<a href="<?php echo base_url() ?>administrador/emailNewsletterPrueba">
					<input type="button" class="cupid-orange" id="sign_in_sub" value="Enviar email de prueba" style="width: 30%; margin: auto;"/>
				</a>
			</div>
		</div>
		<?php echo form_open_multipart("administrador/nuevaNewsletterConfirmacion/1", array('id' => 'form_newsletter')); ?>
			<div class="reg_form">
				<p style="padding: 0;">
					<label for="nombre">
						Nombre:
					</label>
					<input type="text" id="nombre" class="contacto_form_input" placeholder="" name="nombre" disabled="disabled" 
						value="<?php echo $datos_newsletter['nombre']; ?>" style="height: 40px; width: 100%;" />
				</p>
				<p style="padding: 0;">
					<label for="asunto">
						Asunto:
					</label>
					<input type="text" id="asunto" class="contacto_form_input" placeholder="" name="asunto" disabled="disabled" 
						value="<?php echo $datos_newsletter['asunto']; ?>" style="height: 40px; width: 100%;" />
				</p>
				<p style="padding: 0;">
					<label for="email">
						Descripción:
					</label>
					<textarea id="descripcion" class="ckeditor contacto_form_input" placeholder="" name="descripcion" style="height: 130px;"
						disabled="disabled"><?php echo $datos_newsletter['descripcion']; ?></textarea>
				</p>
			</div>
			<div style="margin-top: 10px;">
				<label>
					Medios seleccionados
				</label>
			</div>
			<div class="reg_form">
				<div class="result_options_choose">
					<div class="contendor_divResult" style="min-height: 100px;">
<?php
	if(!empty($medios)){
		foreach($medios as $medio){
?>
						<div style="padding: 5px; display: table; border: 1px solid white; background-color: white; width: 97%">
							<div style="display: table-cell; height: 20px; padding: 0 10px; text-align: left; width: 100%;">
								<label>
									<?php echo $medio->nombre; ?>
								</label>
							</div>
						</div>
<?php
		}
	}
?>
					</div>
				</div>
			</div>
			<div style="margin-top: 10px;">
				<label>
					Ofertas seleccionadas
				</label>
			</div>
			<div class="reg_form">
				<div class="result_options_choose">
					<div class="contendor_divResult" style="min-height: 100px;">
<?php
	if(!empty($ofertas)){
		foreach($ofertas as $oferta){
?>
						<div style="padding: 5px; display: table; border: 1px solid white; background-color: white; width: 97%">
							<div style="display: table-cell; height: 20px; padding: 0 10px; text-align: left; width: 100%;">
								<label>
									<b><?php echo $oferta->titulo;?></b> - <?php echo $oferta->descripcion; ?>
								</label>
							</div>
						</div>
<?php
		}
	}
?>
					</div>
				</div>
			</div>
			<div style="margin-top: 10px;">
				<label>
					Clientes seleccionados
				</label>
			</div>
			<div class="reg_form">
				<div class="result_options_choose">
					<div class="contendor_divResult" style="min-height: 100px;">
<?php
	if(!empty($clientes)){
		foreach($clientes as $cliente){
?>
						<div style="padding: 5px; display: table; border: 1px solid white; background-color: white; width: 97%">
							<div style="display: table-cell; height: 20px; padding: 0 10px; text-align: left; width: 100%;">
								<label>
									<?php echo $cliente->nombre; ?>
								</label>
							</div>
						</div>
<?php
		}
	}
?>
					</div>
				</div>
			</div>
			<div style="margin-top: 10px;">
				<label>
					Agencias seleccionadas
				</label>
			</div>
			<div class="reg_form">
				<div class="result_options_choose">
					<div class="contendor_divResult" style="min-height: 100px;">
<?php
	if(!empty($agencias)){
		foreach($agencias as $agencia){
?>
						<div style="padding: 5px; display: table; border: 1px solid white; background-color: white; width: 97%">
							<div style="display: table-cell; height: 20px; padding: 0 10px; text-align: left; width: 100%;">
								<label>
									<?php echo $agencia->nombre; ?>
								</label>
							</div>
						</div>
<?php
		}
	}
?>
					</div>
				</div>
			</div>
			<div class="contact_form_area" style="display: table; width: 100%;">
				<input type="hidden" name="confirmacion" value="1" />
				<div style="display: table-cell; width: 33%; text-align: center;">
					<a href="<?php echo base_url() ?>administrador/nuevaNewsletterDestinatarios">
						<input type="button" class="cupid-orange" id="sign_in_sub" value="Volver a seleccionar destinatarios" style="width: 95%; margin: auto;"/>
					</a>
				</div>
				<div style="display: table-cell; width: 33%; text-align: center;">
					<input type="button" class="cupid-orange" id="sign_in_sub" value="Guardar" style="width: 95%; margin: auto;"
							onclick="$('#form_newsletter').attr('action', '<?php echo base_url() . 'administrador/nuevaNewsletterConfirmacion/2'; ?>'); $('#form_newsletter').submit();" />
				</div>
				<div style="display: table-cell; width: 33%; text-align: center;">
					<input type="submit" class="cupid-orange" id="sign_in_sub" value="Confirmar" style="width: 95%; margin: auto;"/>
				</div>
			</div>
		<?php echo form_close(); ?>
	</div>
</div>
