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
<?php $this->load->helper('form'); ?>
<div>
<?php if(!empty($mensajeNuevoGestor)){ ?>
	<div class="content_update" id="contentUpdate">
		<div class="Cupdate">
			<a class="close_update" onclick="cerrar_pop();" id="" title="Cerrar">X</a>
			<span class="msj_update">
				<?php echo $mensajeNuevoGestor; ?>
			</span>
		</div>
	</div>
<?php } ?>
	<div style="position: relative; width: 700px; display:inline-table; margin-bottom: 5px; text-align: left; right: 20px;">
		<?php echo form_open_multipart("administrador/editarGestorMedio/" . $gestor->id_gestor . '/1'); ?>
			<div class="contentgrid">
				<div class="grid-12">
					<div class="grid-6">
						<h1 class="titlePerfilA">
							<i class="icon icon-create"></i>
							Editando Gestor
						</h1>
					</div>
					<div class="contact_form_area grid-6 modSubmitButtom" >
						<input type="submit" class="cupid-orange" id="sign_in_sub" value="Guardar Cambios"/>
					</div>
				</div>
				<div class="grid-6 formMod">
					<p >
						<label for="nombre">
							Nombre:
						</label>
						<span class="iconP icon-built"><input type="text" id="nombre" class="contacto_form_input <?php echo (form_error('nombre') != '') ? 'input_error' : ''; ?>" placeholder=""
							name="nombre" value="<?php echo (!empty($validado)) ? set_value('nombre') : $gestor->nombre; ?>"/></span>
						<?php echo form_error('nombre'); ?>
					</p>
					
					
				</div>
				<div class="grid-6 formMod right">
					<p >
						<label for="email">
							Email:
						</label>
						<span class="iconP icon-email"><input type="text" id="email" class="contacto_form_input <?php echo (form_error('email') != '') ? 'input_error' : ''; ?>" placeholder=""
							name="email" value="<?php echo (!empty($validado)) ? set_value('email') : $gestor->email; ?>"/></span>
						<?php echo form_error('email'); ?>
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
