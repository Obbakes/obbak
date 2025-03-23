<script type="text/javascript" src="<?php echo base_url();?>js/jquery-ui.js"></script>
<script type="text/javascript">
$(document).ready(function (){
	setTimeout(function(){
		$('#contentUpdate').removeClass('content_update');
		$('#contentUpdate').addClass('bounceInUp');
		setTimeout(function(){
			$('#contentUpdate').remove();
		}, 2000);
	}, 5000)
});

function cerrar_pop(){
	$('#contentUpdate').removeClass('content_update');
	$('#contentUpdate').addClass('bounceInUp');
	setTimeout(function(){
		$('#contentUpdate').remove();
	}, 2000);
}
</script>
<link rel="stylesheet" href="<?php echo base_url();?>css/jquery-ui.min.css" type="text/css" media="screen"/>
<?php $this->load->helper('form'); ?>
<div>
<?php
if(!empty($correcto)){ ?>
	<div class="content_update" id="contentUpdate">
		<div class="Cupdate">
			<a class="close_update" onclick="cerrar_pop();" id="" title="Cerrar">X</a>
			<span class="msj_update">
				<?php echo $correcto; ?>
			</span>
		</div>
	</div>
<?php } ?>
	<div style="position: relative; width: 100%;max-width: 800px; display:inline-table; margin-bottom: 5px; text-align: left; right: 20px;">
		<?php echo form_open_multipart('agencias/cambiarPass/1'); ?>
			<div class="contentgrid">
				<div class="grid-12">
					<div class="grid-6">
						<h1 class="titlePerfilA">
							<i class="icon icon-create"></i>
							Cambiar contraseña
						</h1>
					</div>
					<div class="contact_form_area grid-6 modSubmitButtom" >
						<input type="submit" class="cupid-orange" id="sign_in_sub" value="GUARDAR CAMBIOS" />
					</div>
				</div>
				<div class="grid-12 formMod">
					<p class="single">
						<label for="nombre">
							Nueva contraseña:
						</label>
						<span class="iconP icon-user"><input type="password" id="pass" class="contacto_form_input <?php echo (form_error('nombre') != '') ? 'input_error' : ''; ?>" placeholder="" name="pass" value="" /></span>
						<?php echo form_error('pass'); ?>
					</p>
					<p class="single">
						<label for="nombre">
							Repite la contraseña:
						</label>
						<span class="iconP icon-user"><input type="password" id="pass_conf" class="contacto_form_input <?php echo (form_error('nombre') != '') ? 'input_error' : ''; ?>" placeholder="" name="pass_conf" value="" /></span>
						<?php echo form_error('pass_conf'); ?>
					</p>
				</div>
			</div>
		<?php echo form_close(); ?>
	</div>
</div>
