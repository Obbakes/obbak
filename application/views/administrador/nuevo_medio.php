<script type="text/javascript" src="<?php echo base_url();?>js/jquery-ui.js"></script>
<link rel="stylesheet" href="<?php echo base_url();?>css/jquery-ui.min.css" type="text/css" media="screen"/>
<script>
	function cancelarSeleccion(tipo){
		switch (tipo) {
			case 'logo':
				jQuery('#vistaPrevia_logo').css('background-image', 'url(\'\')');
				$('#logo').val('');
				$('#nombre_logo').val('');
				$('#cancela_logo').hide();
				$('#vistaPrevia_logo').hide();
				break;
			case 'imagen':
				jQuery('#vistaPrevia_imagen').css('background-image', 'url(\'\')');
				$('#imagen').val('');
				$('#nombre_imagen').val('');
				$('#cancela_imagen').hide();
				$('#vistaPrevia_imagen').hide();
				break;
			default:
				// statements_def
				break;
		}
	}

	window.mostrarVistaPrevia = function mostrarVistaPrevia(tipo_archivo){
		var Archivos, Lector;

		//Para navegadores antiguos
		if(typeof FileReader !== "function" ){
			/*jQuery('#infoNombre').text('[Vista previa no disponible]');
			jQuery('#infoTamaño').text('(su navegador no soporta vista previa!)');*/
			return;
		}

		Archivos = jQuery('#' + tipo_archivo)[0].files;

		if(Archivos.length>0){
			Lector = new FileReader();
			Lector.onloadend = function(e){
				var origen, tipo;

				//Envía la imagen a la pantalla
				origen = e.target; //objeto FileReader

				//Prepara la información sobre la imagen
				tipo = window.obtenerTipoMIME(origen.result.substring(0, 30));

				//Si el tipo de archivo es válido lo muestra,
				//sino muestra un mensaje
				if(tipo!=='image/jpeg' && tipo!=='image/png' && tipo!=='image/gif'){
					if(tipo_archivo == 'imagen'){
						jQuery('#vistaPrevia_imagen').css('background-image', 'url(\'\')');
						$('#imagen').val('');
						$('#nombre_imagen').val('');
						$('#cancela_imagen').hide();
						$('#vistaPrevia_imagen').hide();
					}
					else{
						jQuery('#vistaPrevia_logo').css('background-image', 'url(\'\')');
						$('#logo').val('');
						$('#nombre_logo').val('');
						$('#cancela_logo').hide();
						$('#vistaPrevia_logo').hide();
					}

					jQuery('#vistaPrevia_' + tipo_archivo).css('display', 'none');

					alert('El formato de imagen no es válido: debe seleccionar una imagen JPG, PNG o GIF');
				}
				else{
					jQuery('#vistaPrevia_' + tipo_archivo).css('background-image', 'url(\'' + origen.result + '\')');
					$('#vistaPrevia_' + tipo_archivo).show();
					$('#cancela_' + tipo_archivo).show();
				}
			};

			Lector.onerror = function(e){
				console.log(e)
			}

			Lector.readAsDataURL(Archivos[0]);
		}
		else{
			var objeto = jQuery('#imagen');

			objeto.replaceWith(objeto.val('').clone());

			if(tipo_archivo == 'imagen'){
				jQuery('#vistaPrevia_imagen').css('background-image', 'url(\'\')');
				$('#imagen').val('');
				$('#nombre_imagen').val('');
				$('#cancela_imagen').hide();
				$('#vistaPrevia_imagen').hide();
			}
			else{
				jQuery('#vistaPrevia_logo').css('background-image', 'url(\'\')');
				$('#logo').val('');
				$('#nombre_logo').val('');
				$('#cancela_logo').hide();
				$('#vistaPrevia_logo').hide();
			}
		}
	};

	//Lee el tipo MIME de la cabecera de la imagen
	window.obtenerTipoMIME = function obtenerTipoMIME(cabecera){
		return cabecera.replace(/data:([^;]+).*/, '\$1');
	}

	function mostrarFichero(tipo){
		$('#nombre_' + tipo).val($('#' + tipo).val());
	}

	$(function() {
		$( ".datepicker" ).datepicker();
	});

	function caracteres_descripcion(){
		var num_caracteres = $('#descripcion').val().length;

		$('#descripcion_label').html('Descripción (' + (2000 - num_caracteres) + ' caracteres restantes)');

		return;
	}
</script>
<?php $this->load->helper('form'); ?>
<div>
	<div style="position: relative; width: 360px; display:inline-table; margin-bottom: 5px; text-align: left; right: 20px;">
		<h3>
			Nuevo medio
		</h3>
		<?php echo form_open_multipart("administrador/nuevoMedio/1"); ?>
			<p>
				<label for="nick">
					Nickname (usuario):
				</label>
				<input type="text" id="nick" class="contacto_form_input <?php echo (form_error('nick') != '') ? 'input_error' : ''; ?>" placeholder=""
					name="nick" value="<?php echo set_value('nick'); ?>" style="width: 335px; height: 40px;" />
				<?php echo form_error('nick'); ?>
			</p>
			<p>
				<label for="nombre">
					Nombre:
				</label>
				<input type="text" id="nombre" class="contacto_form_input <?php echo (form_error('nombre') != '') ? 'input_error' : ''; ?>" placeholder=""
					name="nombre" value="<?php echo set_value('nombre'); ?>" style="width: 335px; height: 40px;" />
				<?php echo form_error('nombre'); ?>
			</p>
			<p>
				<label id="descripcion_label" for="descripcion">
					Descripción (<?php echo (2000 - strlen(set_value('descripcion'))); ?> caracteres restantes)
				</label>
				<textarea type="text" id="descripcion" class="contacto_form_input <?php echo (form_error('descripcion') != '') ? 'input_error' : ''; ?>"
					maxlength="2000" onkeypress="caracteres_descripcion();" name="descripcion" style="width: 335px; height: 100px;" ><?php echo set_value('descripcion'); ?></textarea>
				<?php echo form_error('descripcion'); ?>
			</p>
			<p>
				<label for="tipo_medio">
					Tipo:
				</label>
				<select id="tipo_medio" class="contacto_form_input <?php echo (form_error('id_tipo_medio') != '') ? 'input_error' : ''; ?>"
					 style="width: 335px; height: 40px;" name="tipo_medio">
<?php
if(!empty($tipos_medios)){
	foreach($tipos_medios as $tipo_medio){
?>
					<option value="<?php echo $tipo_medio->id_tipo ?>" <?php echo set_select('tipo_medio', $tipo_medio->id_tipo); ?>>
						<?php echo $tipo_medio->tipo ?>
					</option>
<?php
	}
}
?>
				</select>
				<?php echo form_error('tipo_medio'); ?>
			</p>
			<p>
				<label for="email">
					Email Contacto:
				</label>
				<input type="text" id="email" class="contacto_form_input <?php echo (form_error('email') != '') ? 'input_error' : ''; ?>" placeholder=""
					name="email" value="<?php echo set_value('email'); ?>" style="width: 335px; height: 40px;" />
				<?php echo form_error('email'); ?>
			</p>
			<p>
				<label for="web">
					Web:
				</label>
				<input type="text" id="web" class="contacto_form_input <?php echo (form_error('web') != '') ? 'input_error' : ''; ?>" placeholder=""
					name="web" value="<?php echo set_value('web'); ?>" style="width: 335px; height: 40px;" />
				<?php echo form_error('web'); ?>
			</p>
			<p>
				<label for="imagen">
					Imagen:
					<br>
					(Formatos permitidos: gif, jpg, png.
					<br>
					Tamaño máximo: 3Mb.
					<br>
					Dimensiones máximas: 1024x768 px.)
				</label>
				<input type="file" name="imagen" id="imagen" size="20" onchange="mostrarVistaPrevia('imagen'); mostrarFichero('imagen');" style="display: none;"/>
				<input type="text" id="nombre_imagen" name="nombre_imagen" class="contacto_form_input" value="" style="width: 335px; height: 40px;" disabled="disabled"/>
				<input type="button" class="cupid-orange" id="sign_in_sub" value="Examinar" onclick="$('#imagen').click();"
					style="background: none repeat scroll 0 0 rgb(202, 208, 209); color: gray;" />
				<?php echo (!empty($error_imagen)) ? $error_imagen : ''; ?>
				<div id="vistaPrevia_imagen" style="background-position: center center; background-repeat: no-repeat; background-size: contain; height: 200px;
						background-image: url(''); margin-left: 1px; margin-top: 1px; width: 360px; display: none;">
				</div>
				<div id="cancela_imagen" class="eliminaImg" style="background: rgb(202, 208, 209) none repeat scroll 0px 0px; color: gray; border-radius: 0px; box-shadow: none;
						cursor: pointer; height: auto; margin: 20px auto; text-shadow: none; transition: all 0.2s linear; -moz-transition: all 0.2s linear;
						-ms-transition: all 0.2s linear; -o-transition: all 0.2s linear; -webkit-transition: all 0.2s linear; width: 90px; padding: 8px 20px;
						font-weight: bold; text-align: center; display: none;"
						onclick="cancelarSeleccion('imagen');">
					Cancelar selección
				</div>
			</p>
			<p>
				<label for="logo">
					Logo:
					<br>
					(Formatos permitidos: gif, jpg, png.
					<br>
					Tamaño máximo: 3Mb.
					<br>
					Dimensiones máximas: 1024x768 px.)
				</label>
				<input type="file" name="logo" id="logo" size="20" onchange="mostrarVistaPrevia('logo'); mostrarFichero('logo');" style="display: none;"/>
				<input type="text" id="nombre_logo" name="nombre_logo" class="contacto_form_input" value="" style="width: 335px; height: 40px;" disabled="disabled"/>
				<input type="button" class="cupid-orange" id="sign_in_sub" value="Examinar" onclick="$('#logo').click();"
					style="background: none repeat scroll 0 0 rgb(202, 208, 209); color: gray;" />
				<?php echo (!empty($error_logo)) ? $error_logo : ''; ?>
				<div id="vistaPrevia_logo" style="background-position: center center; background-repeat: no-repeat; background-size: contain; height: 150px;
						background-image: url(''); margin: 1px auto; width: 270px; display: none;">
				</div>
				<div id="cancela_logo" class="eliminaImg" style="background: rgb(202, 208, 209) none repeat scroll 0px 0px; color: gray; border-radius: 0px; box-shadow: none;
						cursor: pointer; height: auto; margin: 20px auto; text-shadow: none; transition: all 0.2s linear; -moz-transition: all 0.2s linear;
						-ms-transition: all 0.2s linear; -o-transition: all 0.2s linear; -webkit-transition: all 0.2s linear; width: 90px; padding: 8px 20px;
						font-weight: bold; text-align: center; display: none;"
						onclick="cancelarSeleccion('logo');">
					Cancelar selección
				</div>
			</p>
			<div class="contact_form_area" >
				<input type="submit" class="cupid-orange" id="sign_in_sub" value="Guardar" />
				<a href="<?php echo base_url() ?>administrador/medios">
					<input type="button" class="cupid-orange" id="sign_in_sub" value="Volver a medios" />
				</a>
			</div>
		<?php echo form_close(); ?>
	</div>
</div>