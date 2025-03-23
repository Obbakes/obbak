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
	<div class="maxW">
		<?php echo form_open_multipart(base_url() . 'agencias/perfil/1'); ?>
			<div class="contentgrid">
				<div class="grid-12">
					<div class="grid-6">
						<h1 class="titlePerfilA">
							<i class="icon icon-create"></i>
							Rellenando perfil
						</h1>
					</div>
					<div class="contact_form_area grid-6 modSubmitButtom" >
						<input type="submit" class="cupid-orange" id="sign_in_sub" value="GUARDAR CAMBIOS" style="padding: 8px 18px; margin: 20px 5px;"/>
						<input type="button" class="cupid-orange" id="sign_in_sub" value="CAMBIAR CONTRASEÑA" style="padding: 8px 18px; margin: 20px 5px;"
								 onclick="document.location.href='<?php echo base_url() . 'agencias/cambiarPass'; ?>';"/>
					</div>
				</div>
				<div class="grid-6 formMod">
					<p >
						<label for="nombre">
							Nombre:
						</label>
						<span class="iconP icon-user"><input type="text" id="nombre" name="nombre" class="contacto_form_input <?php echo (form_error('nombre') != '') ? 'input_error' : ''; ?>" placeholder="" value="<?php echo (!empty($validado)) ? set_value('nombre') : $agencia->nombre; ?>"  /></span>
						<?php echo form_error('nombre'); ?>
					</p>
					<p >
						<label for="email">
							Email:
						</label>
						<span class="iconP icon-email"><input type="text" id="email" class="contacto_form_input" placeholder="" disabled="disabled" name="email"
							value="<?php echo $agencia->email; ?>" /></span>
					</p>
					<p >
						<label for="fecha_alta">
							Fecha de registro:
						</label>
						<span class="iconP icon-date"><input type="text" id="fecha_alta" class="contacto_form_input" disabled="disabled" name="fecha_registro"
							value="<?php echo $agencia->fecha_alta; ?>" /></span>
					</p>
					<p >
						<label for="telefono">
							Teléfono:
						</label>
						<span class="iconP icon-phone" ><input type="text" id="telefono" name="telefono" class="contacto_form_input <?php echo (form_error('telefono') != '') ? 'input_error' : ''; ?>" placeholder=""
							value="<?php echo (!empty($validado)) ? set_value('telefono') : $agencia->telefono; ?>" /></span>
						<?php echo form_error('telefono'); ?>
					</p>
					<p >
						<label for="direccion">
							Dirección:
						</label>
						<span class="iconP icon-address"><input type="text" name="direccion" id="direccion" class="contacto_form_input <?php echo (form_error('direccion') != '') ? 'input_error' : ''; ?>" placeholder="" value="<?php echo (!empty($validado)) ? set_value('direccion') : $agencia->direccion; ?>" /></span>
						<?php echo form_error('direccion'); ?>
					</p>
				</div>
				<div class="grid-6 formMod right">
					<p >
						<label for="cp">
							Código Postal:
						</label>
						<span class="iconP icon-cp" ><input type="text" id="cp" name="cp" class="contacto_form_input <?php echo (form_error('cp') != '') ? 'input_error' : ''; ?>" placeholder=""	value="<?php echo (!empty($validado)) ? set_value('cp') : $agencia->cp; ?>" /></span>
						<?php echo form_error('cp'); ?>
					</p>
					<p >
						<label for="cif">
							CIF:
						</label>
						<span class="iconP icon-id" ><input type="text" id="cif" name="cif" class="contacto_form_input <?php echo (form_error('cif') != '') ? 'input_error' : ''; ?>" placeholder="" value="<?php echo (!empty($validado)) ? set_value('cif') : $agencia->cif; ?>" /></span>
						<?php echo form_error('cif'); ?>
					</p>
					<p >
						<label for="poblacion">
							Población:
						</label>
						<span class="iconP icon-country" ><input type="text" name="poblacion" id="poblacion" class="contacto_form_input <?php echo (form_error('poblacion') != '') ? 'input_error' : ''; ?>" placeholder="" value="<?php echo (!empty($validado)) ? set_value('poblacion') : $agencia->poblacion; ?>" /></span>
						<?php echo form_error('poblacion'); ?>
					</p>
					<p >
						<label for="agencia">
							Porcentaje:
						</label>
						<span><input type="text" id="agencia" class="contacto_form_input" disabled="disabled" name="agencia"
							value="<?php echo $agencia->porcentaje; ?>%" /></span>
					</p>
				</div>
			</div>
		<?php echo form_close(); ?>
	</div>
</div>
