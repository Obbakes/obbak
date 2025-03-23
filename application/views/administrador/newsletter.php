<script>
	function cancelarNewsletter(newsletter){
		jQuery.ajax({
			type: 'POST',
			url: '<?php echo base_url(); ?>administrador/cancelarNewsletter/' + newsletter,
			success: function(resp) {
				document.location.href = document.location.href;
			}
		});
	}
</script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery-ui.js"></script>
<link rel="stylesheet" href="<?php echo base_url();?>css/jquery-ui.min.css" type="text/css" media="screen"/>
<?php $this->load->helper('form'); ?>
<div>
	<div style="position: relative; margin-bottom: 5px; text-align: left; right: 20px;">
		<h3 style="text-align: center;">
			Newsletter - <?php echo $newsletter->nombre; ?>
		</h3>
		<div class="reg_form">
			<p style="padding: 0;">
				<label for="nombre">
					Nombre:
				</label>
				<input type="text" id="nombre" class="contacto_form_input" placeholder="" name="nombre" disabled="disabled" 
					value="<?php echo $newsletter->nombre; ?>" style="height: 40px; width: 100%;" />
			</p>
			<p style="padding: 0;">
				<label for="asunto">
					Asunto:
				</label>
				<input type="text" id="asunto" class="contacto_form_input" placeholder="" name="asunto" disabled="disabled" 
					value="<?php echo $newsletter->asunto; ?>" style="height: 40px; width: 100%;" />
			</p>
			<p style="padding: 0;">
				<label for="email">
					Descripción:
				</label>
				<textarea id="descripcion" class="ckeditor contacto_form_input" placeholder="" name="descripcion" style="height: 130px;"
					disabled="disabled"><?php echo $newsletter->descripcion; ?></textarea>
			</p>
			<p style="padding: 0;">
				<label for="nombre">
					Fecha:
				</label>
				<label>
					<?php echo $newsletter->fecha; ?>
				</label>
			</p>
			<p style="padding: 0;">
				<label for="nombre">
					Estado:
				</label>
				<label>
					<?php echo ($newsletter->estado == 'p') ? 'Pendiente' : (($newsletter->estado == 'e') ? 'En proceso' : (($newsletter->estado == 't') ? 'Enviada' : 'Cancelada')); ?>
				</label>
<?php
	if($newsletter->estado == 'p'){
?>
				<a style="text-decoration: none;" onclick="if(confirm('¿Quieres cancelar esta newsletter?')){ cancelarNewsletter(<?php echo $newsletter->id_newsletter; ?>);}">
					<div class="contact_form_area" style="width: 200px;">
						<input type="button" class="classname" value="Cancelar" />
					</div>
				</a>
<?php
	}
?>
			</p>
			<p style="padding: 0;">
				<label for="nombre">
					% completado:
				</label>
				<label>
					<?php echo number_format(($newsletter->enviados / $newsletter->total * 100), 2); ?> %
				</label>
				<a style="text-decoration: none;" onclick="if(confirm('¿Quieres utilizar los datos de esta newsletter para crear otra?')) return true; else return false;" 
						href="<?php echo base_url().'administrador/duplicarNewsletter/' . $newsletter->id_newsletter; ?>">
					<div class="contact_form_area" style="width: 200px;">
						<input type="button" class="classname" value="Duplicar" />
					</div>
				</a>
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
	if(!empty($newsletter->medios)){
		foreach($newsletter->medios as $medio){
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
	if(!empty($newsletter->ofertas)){
		foreach($newsletter->ofertas as $oferta){
			$texto = '';
			
			if($oferta->publicada == 0){
				$texto = 'No publicada';
			}
			else{
				if($oferta->visible == 0){
					$texto = 'No está dentro del periodo de publicación';
				}
				else if($oferta->caducada == 1){
					$texto = 'Oferta caducada';
				}
			}
?>
					<div style="padding: 5px; display: table; border: 1px solid white; background-color: white; width: 97%; <?php echo (!empty($texto)) ? 'color: gray;' : ''; ?>"
							<?php echo (empty($texto)) ? '' : ('title="' . $texto . '"'); ?>>
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
	if(!empty($newsletter->clientes)){
		foreach($newsletter->clientes as $cliente){
			$estado = '';
			
			if($cliente->estado == 'p'){
				$estado = 'Pendiente';
			}
			else if($cliente->estado == 'e'){
				$estado = 'Enviado';
			}
			else if($cliente->estado == 's'){
				$estado = 'Sin ofertas';
			}
			else{
				$estado = 'Cancelado';
			}
?>
					<div style="padding: 5px; display: table; border: 1px solid white; background-color: white; width: 97%">
						<div style="display: table-cell; height: 20px; padding: 0 10px; text-align: left; width: 100%;">
							<label>
								<?php echo $estado . ' - ' . $cliente->nombre; ?>
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
	if(!empty($newsletter->agencias)){
		foreach($newsletter->agencias as $agencia){
			$estado = '';
			
			if($agencia->estado == 'p'){
				$estado = 'Pendiente';
			}
			else if($agencia->estado == 'e'){
				$estado = 'Enviado';
			}
			else if($agencia->estado == 's'){
				$estado = 'Sin ofertas';
			}
			else{
				$estado = 'Cancelado';
			}
?>
					<div style="padding: 5px; display: table; border: 1px solid white; background-color: white; width: 97%">
						<div style="display: table-cell; height: 20px; padding: 0 10px; text-align: left; width: 100%;">
							<label>
								<?php echo $estado . ' - ' . $agencia->nombre; ?>
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
	</div>
</div>
