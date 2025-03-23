<script type="text/javascript" src="<?php echo base_url();?>js/jquery-ui.js"></script>
<link rel="stylesheet" href="<?php echo base_url();?>css/jquery-ui.min.css" type="text/css" media="screen"/>
<script>
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
    
        window.imagenVacia = '<?php echo ($medio->imagen == 'images/medios/medio_default.png') ? '' : (base_url() . $medio->imagen); ?>';
	window.logoVacio = '<?php echo ($medio->logo == 'images/medios/logo/medio_logo_default.png') ? '' : (base_url() . $medio->logo); ?>';

	function eliminarArchivo(tipo){
		if(tipo != 'imagen' && tipo != 'logo')
			return;

		if(tipo == 'imagen'){
			if(!confirm('¿Quieres eliminar la imagen de este medio?'))
				return;

			$.ajax({
				type: 'POST',
				url: '<?php echo base_url(); ?>administrador/deleteImagenMedio/<?php echo $medio->id_medio; ?>',
				success: function(resp) {
					alert('Imagen eliminada');

					location.reload();
				}
			});
		}
		else{
			if(!confirm('¿Quieres eliminar el logo de este medio?'))
				return;

			$.ajax({
				type: 'POST',
				url: '<?php echo base_url(); ?>administrador/deleteLogoMedio/<?php echo $medio->id_medio; ?>',
				success: function(resp) {
					alert('Logo eliminado');

					location.reload();
				}
			});
		}
	}

	function cancelarSeleccion(tipo){
		switch (tipo) {
			case 'logo':
				jQuery('#vistaPrevia_logo').css('background-image', 'url(\'' + window.logoVacio + '\')');
				$('#logo').val('');
				$('#nombre_logo').val('');
				$('#cancela_logo').hide();

				if(window.logoVacio == ''){
					$('#vistaPrevia_logo').hide();
					$('#elimina_logo').hide();
				}
				else{
					$('#vistaPrevia_logo').show();
					$('#elimina_logo').show();
				}
				break;
			case 'imagen':
				jQuery('#vistaPrevia_imagen').css('background-image', 'url(\'' + window.imagenVacia + '\')');
				$('#imagen').val('');
				$('#nombre_imagen').val('');
				$('#cancela_imagen').hide();

				if(window.imagenVacia == ''){
					$('#vistaPrevia_imagen').hide();
					$('#elimina_imagen').hide();
				}
				else{
					$('#vistaPrevia_imagen').show();
					$('#elimina_imagen').show();
				}
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
						jQuery('#vistaPrevia_imagen').css('background-image', 'url(\'' + window.imagenVacia + '\')');
						$('#imagen').val('');
						$('#nombre_imagen').val('');
						$('#cancela_imagen').hide();

						if(window.imagenVacia == ''){
							$('#vistaPrevia_imagen').hide();
							$('#elimina_imagen').hide();
						}
						else{
							$('#vistaPrevia_imagen').show();
							$('#elimina_imagen').show();
						}
					}
					else{
						jQuery('#vistaPrevia_logo').css('background-image', 'url(\'' + window.logoVacio + '\')');
						$('#logo').val('');
						$('#nombre_logo').val('');
						$('#cancela_logo').hide();

						if(window.logoVacio == ''){
							$('#vistaPrevia_logo').hide();
							$('#elimina_logo').hide();
						}
						else{
							$('#vistaPrevia_logo').show();
							$('#elimina_logo').show();
						}
					}

					alert('El formato de imagen no es válido: debe seleccionar una imagen JPG, PNG o GIF');
				}
				else{
					jQuery('#vistaPrevia_' + tipo_archivo).css('background-image', 'url(\'' + origen.result + '\')');
					$('#vistaPrevia_' + tipo_archivo).show();
					$('#elimina_' + tipo_archivo).hide();
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
				jQuery('#vistaPrevia_imagen').css('background-image', 'url(\'' + window.imagenVacia + '\')');
				$('#imagen').val('');
				$('#nombre_imagen').val('');
				$('#cancela_imagen').hide();

				if(window.imagenVacia == ''){
					$('#vistaPrevia_imagen').hide();
					$('#elimina_imagen').hide();
				}
				else{
					$('#vistaPrevia_imagen').show();
					$('#elimina_imagen').show();
				}
			}
			else{
				jQuery('#vistaPrevia_logo').css('background-image', 'url(\'' + window.logoVacio + '\')');
				$('#logo').val('');
				$('#nombre_logo').val('');
				$('#cancela_logo').hide();

				if(window.logoVacio == ''){
					$('#vistaPrevia_logo').hide();
					$('#elimina_logo').hide();
				}
				else{
					$('#vistaPrevia_logo').show();
					$('#elimina_logo').show();
				}
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
	<div style="position: relative; width: 360px; display:inline-table; margin-bottom: 5px; text-align: left; right: 20px;">
		<h3 style="text-align: center;">
			Editar medio
		</h3>
		<div style="display: table; margin: auto; width: 50%; padding: 0;">
			<div style="display: table-cell; width: 50%; text-align: center;">
				<input type="button" class="boton-pestanya-inactivo" value="Datos" disabled="disabled" style="width: 90%;">
			</div>
			<div style="display: table-cell; width: 50%; text-align: center;">
				<input type="button" class="boton-pestanya-activo" value="Permisos" style="width: 90%;"
					onclick="document.location.href='<?php echo base_url() . 'administrador/permisosMedio/' . $medio->id_medio; ?>';">
			</div>
                        <div style="display: table-cell; width: 50%; text-align: center;">
				<input type="button" class="boton-pestanya-activo" value="Logs" style="width: 90%;"
					onclick="document.location.href='<?php echo base_url() . 'administrador/logsMedio/' . $medio->id_medio; ?>';">
			</div>
		</div>
		<?php echo form_open_multipart("administrador/editarMedio/" . $medio->id_medio . '/1'); ?>
			<p>
				<label for="nick">
					Nickname (usuario):
				</label>
				<input type="text" id="nick" class="contacto_form_input <?php echo (form_error('nick') != '') ? 'input_error' : ''; ?>" placeholder=""
					name="nick" value="<?php echo (!empty($validado)) ? set_value('nick') : $medio->nick; ?>" style="width: 335px; height: 40px;" disabled="disabled"/>
				<?php echo form_error('nick'); ?>
                                <div style="display: table; margin: 20px auto; width: 35%; padding: 0;">
				<div style="display: table-cell; width: 100%; text-align: center;">
					<input type="button" class="boton-pestanya-activo" value="Enviar datos de acceso" onclick="if(confirm('¿Quieres enviar los datos de acceso al medio?')) document.location.href='<?php echo base_url() . 'administrador/enviarDatosAcceso/' . $medio->id_medio; ?>';">
				</div>
                            </div>
			</p>
			<p>
				<label for="nombre">
					Nombre:
				</label>
				<input type="text" id="nombre" class="contacto_form_input <?php echo (form_error('nombre') != '') ? 'input_error' : ''; ?>" placeholder=""
					name="nombre" value="<?php echo (!empty($validado)) ? set_value('nombre') : $medio->nombre; ?>" style="width: 335px; height: 40px;" />
				<?php echo form_error('nombre'); ?>
			</p>
			<p>
				<label id="descripcion_label" for="descripcion">
					Descripción (<?php echo (!empty($validado)) ? (2000 - strlen(set_value('descripcion'))) : (2000 - strlen($medio->descripcion)); ?> caracteres restantes)
				</label>
				<textarea type="text" id="descripcion" class="contacto_form_input <?php echo (form_error('descripcion') != '') ? 'input_error' : ''; ?>"
					maxlength="2000" onkeypress="caracteres_descripcion();" name="descripcion" style="width: 335px; height: 100px;" ><?php echo (!empty($validado)) ? set_value('descripcion') : br2nl($medio->descripcion); ?></textarea>
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
					<option value="<?php echo $tipo_medio->id_tipo ?>" <?php echo (!empty($validado)) ? set_select('tipo_medio', $tipo_medio->id_tipo) : (($medio->id_tipo_medio == $tipo_medio->id_tipo) ? 'selected="selected"' : ''); ?>>
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
					Emails (Puede indicar más de un email separándolos con una coma):
				</label>
				<input type="text" id="email" class="contacto_form_input <?php echo (form_error('email') != '') ? 'input_error' : ''; ?>" placeholder=""
					name="email" value="<?php echo (!empty($validado)) ? set_value('email') : $medio->email; ?>" style="width: 335px; height: 40px;"/>
				<?php echo form_error('email'); ?>
			</p>
			<p>
				<label for="web">
					Web:
				</label>
				<input type="text" id="web" class="contacto_form_input <?php echo (form_error('web') != '') ? 'input_error' : ''; ?>" placeholder=""
					name="web" value="<?php echo (!empty($validado)) ? set_value('web') : $medio->web; ?>" style="width: 335px; height: 40px;" />
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
						background-image: url('<?php echo ($medio->imagen == 'images/medios/medio_default.png') ? '' : (base_url() . $medio->imagen); ?>');
						margin-left: 1px; margin-top: 1px; width: 360px;
						<?php echo ($medio->imagen == 'images/medios/medio_default.png') ? 'display: none;' : ''; ?>">
				</div>
				<div id="elimina_imagen" class="eliminaImg" style="background: rgb(202, 208, 209) none repeat scroll 0px 0px; color: gray; border-radius: 0px; box-shadow: none;
						cursor: pointer; height: auto; margin: 20px auto; text-shadow: none; transition: all 0.2s linear; -moz-transition: all 0.2s linear;
						-ms-transition: all 0.2s linear; -o-transition: all 0.2s linear; -webkit-transition: all 0.2s linear; width: 85px; padding: 8px 20px;
						font-weight: bold; text-align: center; <?php echo ($medio->imagen == 'images/medios/medio_default.png') ? 'display: none;' : ''; ?>"
						onclick="eliminarArchivo('imagen');">
					Eliminar Imagen
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
						background-image: url('<?php echo ($medio->logo == 'images/medios/logo/medio_logo_default.png') ? '' : (base_url() . $medio->logo); ?>');
						margin: 1px auto; width: 270px;
						<?php echo ($medio->logo == 'images/medios/logo/medio_logo_default.png') ? 'display: none;' : ''; ?>">
				</div>
				<div id="elimina_logo" class="eliminaImg" style="background: rgb(202, 208, 209) none repeat scroll 0px 0px; color: gray; border-radius: 0px; box-shadow: none;
						cursor: pointer; height: auto; margin: 20px auto; text-shadow: none; transition: all 0.2s linear; -moz-transition: all 0.2s linear;
						-ms-transition: all 0.2s linear; -o-transition: all 0.2s linear; -webkit-transition: all 0.2s linear; width: 85px; padding: 8px 20px;
						font-weight: bold; text-align: center; <?php echo ($medio->logo == 'images/medios/logo/medio_logo_default.png') ? 'display: none;' : ''; ?>"
						onclick="eliminarArchivo('logo');">
					Eliminar Logo
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
				<a href="javascript:history.go(-1)">
					<input type="button" class="cupid-orange" id="sign_in_sub" value="Volver a medios" />
				</a>
			</div>
		<?php echo form_close(); ?>
	</div>
</div>
