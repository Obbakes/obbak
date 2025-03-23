<script type="text/javascript" src="<?php echo base_url();?>js/jquery-ui.js"></script>
<link rel="stylesheet" href="<?php echo base_url();?>css/jquery-ui.min.css" type="text/css" media="screen"/>
<script>
	window.mostrarVistaPrevia = function mostrarVistaPrevia(){
		var Archivos, Lector;

		//Para navegadores antiguos
		if(typeof FileReader !== "function" ){
			/*jQuery('#infoNombre').text('[Vista previa no disponible]');
			jQuery('#infoTamaño').text('(su navegador no soporta vista previa!)');*/
			return;
		}

		Archivos = jQuery('#imagen')[0].files;

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
					jQuery('#vistaPrevia').css('background-image', 'none');
					jQuery('#vistaPrevia').css('display', 'none');
					jQuery('#vistaPreviaLista').css('background-image', 'none');
					jQuery('#vistaPreviaDetalles').css('background-image', 'none');
					$('#imagen').val('');
					mostrarFichero();
					alert('El formato de imagen no es válido: debe seleccionar una imagen JPG, PNG o GIF');
				}
				else{
					jQuery('#vistaPrevia').css('background-image', 'url(\'' + origen.result + '\')');
					jQuery('#vistaPrevia').css('display', 'inline-block');
					jQuery('#vistaPreviaLista').css('background-image', 'url(\'' + origen.result + '\')');
					jQuery('#vistaPreviaDetalles').css('background-image', 'url(\'' + origen.result + '\')');
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
			jQuery('#vistaPrevia').css('background-image', 'none');
			jQuery('#vistaPrevia').css('display', 'none');
			$('#imagen').val('');
			mostrarFichero();
			jQuery('#vistaPreviaLista').css('background-image', 'none');
			jQuery('#vistaPreviaDetalles').css('background-image', 'none');
		}
	};

	//Lee el tipo MIME de la cabecera de la imagen
	window.obtenerTipoMIME = function obtenerTipoMIME(cabecera){
		return cabecera.replace(/data:([^;]+).*/, '\$1');
	}

	$(function() {
		$('.datepicker').datetimepicker({
			lang:'es',
			i18n:{
				es:{
					months:[
						'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
					],
					dayOfWeek:[
						'Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sá'
					]
				}
			},
			timepicker:false,
			format:'Y-m-d'
		});
	});
	
	$(function() {
		$('.datepicker_time').datetimepicker({
			lang:'es',
			i18n:{
				es:{
					months:[
						'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
					],
					dayOfWeek:[
						'Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sá'
					]
				}
			},
			timepicker:true,
			format:'Y-m-d H:i:s'
		});
	});

	function cambia_valores_check(campo){
		if($('#' + campo + '_indef').prop('checked')){
			$('#' + campo).val('');
			$('#' + campo).attr('disabled', 'disabled');
                        jQuery('#lbldet_duracion_camp').css('display', 'block');
                        jQuery('#det_duracion_camp').css('display', 'block');
		}
		else{
			$('#' + campo).removeAttr('disabled');
                        
                        jQuery('#lbldet_duracion_camp').css('display', 'none');
                        jQuery('#det_duracion_camp').css('display', 'none');
                        $('#det_duracion_camp').val("");
		}
	}

	function obtenerDescripcionMedio(){
		var medio = $('#id_medio_oferta').val();

		jQuery.ajax({
			type: 'POST',
			url: '<?php echo base_url(); ?>medios/obtener_descripcion_medio/' + medio,
			success: function(resp) {
				jQuery('#descripcion_vista_previa_detalles').html(resp);
			}
		});
	}

	function actualizarDescuento(){
		if($('#precio_anterior').val() == "" || $('#precio_oferta').val() == ""){
			return;
		}

		var precioAnterior = parseFloat($('#precio_anterior').val());
		var precioOferta = parseFloat($('#precio_oferta').val());
		var descuento = parseInt(100 - ((precioOferta / precioAnterior) * 100));

		$('#descuento').val(descuento);
		$('#por_descuento_vista_previa').html(parseInt(descuento) + ' %');
		$('#por_descuento_vista_previa_detalles').html(parseInt(descuento) + ' %');
	}

	function caracteres_titulo(){
		var num_caracteres = $('#titulo').val().length;

		$('#titulo_label').html('Título * (' + (40 - num_caracteres) + ' caracteres restantes)');

		return;
	}

	function caracteres_descripcion_breve(){
		var num_caracteres = $('#descripcion').val().length;

		$('#descripcion_label').html('Descripción breve * (' + (50 - num_caracteres) + ' caracteres restantes)');

		return;
	}

	function caracteres_detalles(){
		var num_caracteres = $('#detalle').val().length;

		$('#detalle_label').html('Descripción oferta * (' + (5000 - num_caracteres) + ' caracteres restantes)');

		return;
	}

	function caracteres_condiciones(){
		var num_caracteres = $('#condiciones').val().length;

		$('#condiciones_label').html('Condiciones * (' + (5000 - num_caracteres) + ' caracteres restantes)');

		return;
	}

	function actualizarCampoVista(campo_form, campo_vista, char_final){
		if(char_final == ''){
			$('#' + campo_vista).html($('#' + campo_form).val());
		}
		else{
			if(campo_form == 'precio_anterior' || campo_form == 'precio_oferta'){
				var numero = '';

				if(!isNaN($('#' + campo_form).val())){
					var valor = $('#' + campo_form).val().replace('.', ',');
					var ind_decimal = (valor.indexOf(',') == -1) ? valor.length : valor.indexOf(',');
					var flag = 0;

					numero = (ind_decimal == valor.length) ? '' : valor.substr(ind_decimal);

					for(var i = ind_decimal - 3; i > -3; i -= 3){
						if(flag > 0)
							numero = '.' + numero;

						flag++;

						numero = valor.substr((i < 0) ? 0 : i, (i < 0) ? (3 + i) : 3) + numero;
					}
				}

				$('#' + campo_vista).html(numero + ' ' + char_final);
			}
			else{
				$('#' + campo_vista).html($('#' + campo_form).val() + ' ' + char_final);
			}
		}
	}

	function actualizarFechaFinPublicacion(){
		if($('#fecha_fin_pub_indef').prop('checked') == true){
                        if($('#det_duracion_camp').val() != ''){
                            $('#fecha_fin_pub_vista_previa').html($('#det_duracion_camp').val());
                            $('#fecha_fin_pub_vista_previa_detalles').html($('#det_duracion_camp').val());
                        }else{
                            $('#fecha_fin_pub_vista_previa').html('Hasta fin de existencias');
                            $('#fecha_fin_pub_vista_previa_detalles').html('Hasta fin de existencias');
                        }
		}
		else{
			$('#fecha_fin_pub_vista_previa').html($('#fecha_fin_pub').val());
			$('#fecha_fin_pub_vista_previa_detalles').html($('#fecha_fin_pub').val());
		}
	}

	function mostrarFichero(){
		$('#nombre_imagen').val($('#imagen').val());
	}

	function actualizarMedioVistaPrevia(){
		$('#medio_tipo_vista_previa').attr('src', $('#id_medio_oferta option:selected').attr('data-logo'));
		$('#medio_tipo_vista_detalles').attr('src', $('#id_medio_oferta option:selected').attr('data-logo'));
	}

	function getMedios(medio){
		var tipoMedio = $('#id_tipo_medio').val();

		jQuery.ajax({
			type: 'POST',
			url: '<?php echo base_url(); ?>administrador/getMedios/' + tipoMedio + '/' + medio,
			success: function(resp) {
				$('#id_medio_oferta').html(resp);
				obtenerDescripcionMedio();
				actualizarMedioVistaPrevia();
			}
		});
	}

	function cambiar_vista_previa(tipo){
		$('#vista_previa_ficha').hide();
		$('#vista_previa_detalles').hide();
		$('#vista_previa_' + tipo).show();
	}

	jQuery(document).ready(function(){
		actualizarCampoVista('titulo', 'titulo_vista_previa', '');
		actualizarCampoVista('titulo', 'titulo_vista_detalles', '');
		actualizarCampoVista('descripcion', 'descripcion_vista_previa', '');
		actualizarCampoVista('condiciones', 'condiciones_vista_previa_detalles', '');
		actualizarCampoVista('detalle', 'detalles_vista_previa_detalles', '');
		actualizarCampoVista('precio_anterior', 'precio_real_vista_previa', '€');
		actualizarCampoVista('precio_oferta', 'precio_oferta_vista_previa', '€');
		actualizarCampoVista('duracion_camp', 'duracion_vista_previa', '');
		actualizarCampoVista('precio_anterior', 'precio_real_vista_previa_detalles', '€');
		actualizarCampoVista('precio_oferta', 'precio_oferta_vista_previa_detalles', '€');
		actualizarCampoVista('duracion_camp', 'duracion_vista_previa_detalles', '');
		actualizarDescuento();
		actualizarFechaFinPublicacion();
		obtenerDescripcionMedio();
		getMedios(<?php echo (!empty($validado)) ? ((set_value('id_medio') != '') ? set_value('id_medio') : 0) : ((!empty($datos_oferta->id_medio)) ? $datos_oferta->id_medio : 0); ?>);
	});

	function checkOffset() {
		if ($('#vista_previa_ficha').offset().top + $('#vista_previa_ficha').height() >= $('#bottom').offset().top - 10)
			$('#vista_previa_ficha').css('position', 'absolute').css('bottom', '0');

		if ($(document).scrollTop() + window.innerHeight < $('#bottom').offset().top)
			$('#vista_previa_ficha').css('position', 'fixed').css('bottom', 'inherit'); // restore when you scroll up

		if ($('#vista_previa_detalles').offset().top + $('#vista_previa_detalles').height() >= $('#bottom').offset().top - 10)
			$('#vista_previa_detalles').css('position', 'absolute').css('bottom', '0');

		if ($(document).scrollTop() + window.innerHeight < $('#bottom').offset().top)
			$('#vista_previa_detalles').css('position', 'fixed').css('bottom', 'inherit'); // restore when you scroll up
	}

	$(document).scroll(function() {
		checkOffset();
	});
</script>
<style type="text/css">
	.div_fieldset{
		display: inline-flex;
	}

	@-moz-document url-prefix() {
		.div_fieldset{
			display: inline-flex;
			position: relative;
			top: -15px;
		}
	}
</style>
<?php $this->load->helper('form');?>
<div style="display: table; margin: auto;max-width:960px;">
	<div style="display: table-cell; width: 50%; margin-bottom: 5px; text-align: left;">
		<h3>
			Nueva oferta
		</h3>
		<?php echo form_open_multipart("administrador/nuevaOferta/1"); ?>
			<p>
				<label id="titulo_label" for="titulo" style="display: block; width: 100%;">
					Título * (<?php echo (40 - strlen(set_value('titulo'))); ?> caracteres restantes)
				</label>
				<textarea id="titulo" class="contacto_form_input <?php echo (form_error('titulo') != '') ? 'input_error' : ''; ?>" maxlength="40"
					name="titulo" onkeypress="caracteres_titulo();" style="width: 335px; height: 40px;"
					onchange="actualizarCampoVista('titulo', 'titulo_vista_previa', ''); actualizarCampoVista('titulo', 'titulo_vista_detalles', '');"><?php echo (!empty($validado)) ? set_value('titulo') : ((!empty($datos_oferta->titulo)) ? $datos_oferta->titulo : ''); ?></textarea>
				<?php echo form_error('titulo'); ?>
			</p>
			<p>
				<label for="id_tipo_oferta" style="display: block; width: 100%;">
					Tipo Oferta:
				</label>
				<select id="id_tipo_oferta" name="id_tipo_oferta" class="contacto_form_input" style="width: 70%;">
                                    <option value="">
                                            Seleccione Tipo de Oferta
                                    </option>
                                    <?php
                                        if(!empty($tipos_oferta)){
                                            foreach($tipos_oferta as $tipo_oferta){
                                    ?>
                                                <option value="<?php echo $tipo_oferta->id_tipo_oferta ?>" <?php echo (!empty($validado)) ? set_select('id_tipo_oferta', $tipo_oferta->id_tipo_oferta) : ((!empty($datos_oferta->id_tipo_oferta)) ? (($datos_oferta->id_tipo_oferta == $tipo_oferta->id_tipo_oferta) ? 'selected="selected"' : ''): ''); ?>>
                                                    <?php echo $tipo_oferta->nombre_tipo_oferta ?>
                                                </option>
                                    <?php
                                            }
                                        }
                                    ?>
                                </select>
				<?php echo form_error('id_tipo_oferta'); ?>
			</p>
			<p>
					<label for="id_tipo_medio" style="display: block; width: 100%;">
					Tipo de medio:
				</label>
				<select id="id_tipo_medio" class="contacto_form_input <?php echo (form_error('id_tipo_medio') != '') ? 'input_error' : ''; ?>"
					 style="width: 335px; height: 40px;" name="id_tipo_medio" onchange="getMedios(0);">
<?php
	if(!empty($tipos_medio)){
		foreach($tipos_medio as $tipo_medio){
?>
					<option value="<?php echo $tipo_medio->id_tipo ?>" <?php echo (!empty($validado)) ? set_select('id_tipo_medio', $tipo_medio->id_tipo) : ((!empty($datos_oferta->id_tipo_medio)) ? (($datos_oferta->id_tipo_medio == $tipo_medio->id_tipo) ? 'selected="selected"' : '') : ''); ?>>
						<?php echo $tipo_medio->tipo ?>
					</option>
<?php
		}
	}
?>
				</select>
				<?php echo form_error('id_tipo_medio'); ?>
			</p>
			<p>
				<label for="id_medio" style="display: block; width: 100%;">
					Medio:
				</label>
				<select id="id_medio_oferta" class="contacto_form_input <?php echo (form_error('id_medio') != '') ? 'input_error' : ''; ?>"
					 style="width: 335px; height: 40px;" name="id_medio" onchange="obtenerDescripcionMedio(); actualizarMedioVistaPrevia();">
				</select>
				<?php echo form_error('id_medio'); ?>
			</p>
			<p>
				<label for="id_medio" style="display: block; width: 100%;">
					Provincia:
				</label>
				<select id="provincia" name="provincia" class="contacto_form_input" style="width: 70%;">
                                    <option value="">
                                            Seleccione Provincia
                                    </option>
                                    <?php
                                        if(!empty($provincias)){
                                            foreach($provincias as $provincia){
                                    ?>
                                                <option value="<?php echo $provincia->id_provincia ?>" <?php echo (set_value('provincia') == $provincia->id_provincia) ? 'selected="selected"' : ''; ?>>
                                                    <?php echo $provincia->provincia ?>
                                                </option>
                                    <?php
                                            }
                                        }
                                    ?>
                                </select>
				<?php echo form_error('provincia'); ?>
			</p>
			<p>
				<label for="id_medio" style="display: block; width: 100%;">
					Sector:
				</label>
				<select id="sector" name="sector" class="contacto_form_input" style="width: 70%;">
                                    <option value="">
                                            Seleccione Sector
                                    </option>
                                    <?php
                                        if(!empty($sectores)){
                                            foreach($sectores as $sector){
                                    ?>
                                                <option value="<?php echo $sector->id_sector ?>" <?php echo (set_value('sector') == $sector->id_sector) ? 'selected="selected"' : ''; ?>>
                                                    <?php echo $sector->sector ?>
                                                </option>
                                    <?php
                                            }
                                        }
                                    ?>
                                </select>
				<?php echo form_error('sector'); ?>
			</p>
			<p>
				<label id="descripcion_label" for="descripcion" style="display: block; width: 100%;">
					Descripcion breve * (<?php echo (50 - strlen(set_value('descripcion'))); ?> caracteres restantes)
				</label>
				<textarea id="descripcion" class="contacto_form_input <?php echo (form_error('descripcion') != '') ? 'input_error' : ''; ?>"
					maxlength="50" name="descripcion" onkeypress="caracteres_descripcion_breve();" style="width: 335px; height: 40px;"
					onchange="actualizarCampoVista('descripcion', 'descripcion_vista_previa', '');"><?php echo (!empty($validado)) ? set_value('descripcion') : ((!empty($datos_oferta->descripcion)) ? $datos_oferta->descripcion : ''); ?></textarea>
				<?php echo form_error('descripcion'); ?>
			</p>
			<p>
				<label id="detalle_label" for="detalle" style="display: block; width: 100%;">
					Descripción oferta * (<?php echo (5000 - strlen(set_value('detalle'))); ?> caracteres restantes)
				</label>
				<textarea id="detalle" class="ckeditor contacto_form_input <?php echo (form_error('detalle') != '') ? 'input_error' : ''; ?>" name="detalle"
					maxlength="5000" onkeypress="caracteres_detalles();" style="width: 335px; height: 100px;"
					onchange="actualizarCampoVista('detalle', 'detalles_vista_previa_detalles', '');"><?php echo (!empty($validado)) ? set_value('detalle') : ((!empty($datos_oferta->detalle)) ? $datos_oferta->detalle : ''); ?></textarea>
				<?php echo form_error('detalle'); ?>
			</p>
			<p>
				<label id="condiciones_label" for="condiciones" style="display: block; width: 100%;">
					Condiciones * (<?php echo (5000 - strlen(set_value('condiciones'))); ?> caracteres restantes)
				</label>
				<textarea id="condiciones" class="ckeditor contacto_form_input <?php echo (form_error('condiciones') != '') ? 'input_error' : ''; ?>" name="condiciones"
					maxlength="5000" onkeypress="caracteres_condiciones();" style="width: 335px; height: 100px;"
					onchange="actualizarCampoVista('condiciones', 'condiciones_vista_previa_detalles', '');"><?php echo (!empty($validado)) ? set_value('condiciones') : ((!empty($datos_oferta->condiciones)) ? $datos_oferta->condiciones : ''); ?></textarea>
				<?php echo form_error('condiciones'); ?>
			</p>
			<p>
				<label for="duracion_camp" style="display: block; width: 100%;">
					Duración de la Campaña *:
				</label>
				<input type="text" id="duracion_camp" class="contacto_form_input <?php echo (form_error('duracion_camp') != '') ? 'input_error' : ''; ?>"
					name="duracion_camp" value="<?php echo (!empty($validado)) ? set_value('duracion_camp') : ((!empty($datos_oferta->duracion_camp)) ? $datos_oferta->duracion_camp : ''); ?>" style="width: 335px; height: 40px;"
					onchange="actualizarCampoVista('duracion_camp', 'duracion_vista_previa', ''); actualizarCampoVista('duracion_camp', 'duracion_vista_previa_detalles', '');"/>
				<?php echo form_error('duracion_camp'); ?>
			</p>
			<p>
				<label for="precio_anterior" style="display: block; width: 100%;">
					Precio Anterior *
				</label>
				<input type="text" id="precio_anterior" name="precio_anterior"
					class="contacto_form_input <?php echo (form_error('precio_anterior') != '') ? 'input_error' : ''; ?>"
					value="<?php echo (!empty($validado)) ? set_value('precio_anterior') : ((!empty($datos_oferta->precio_anterior)) ? $datos_oferta->precio_anterior : ''); ?>"
					style="background-position: right center; padding-right: 36px; background-image: url(<?php echo base_url(); ?>images/ico_euro.png);
					background-repeat: no-repeat; width: 335px; height: 40px; text-align: right;"
					onchange="actualizarCampoVista('precio_anterior', 'precio_real_vista_previa', '€'); actualizarCampoVista('precio_anterior', 'precio_real_vista_previa_detalles', '€'); actualizarDescuento();"/>
				<?php echo form_error('precio_anterior'); ?>
			</p>
			<p>
				<label for="precio_oferta" style="display: block; width: 100%;">
					Precio Oferta *
				</label>
				<input type="text" id="precio_oferta" name="precio_oferta"
					class="contacto_form_input <?php echo (form_error('precio_oferta') != '') ? 'input_error' : ''; ?>"
					value="<?php echo (!empty($validado)) ? set_value('precio_oferta') : ((!empty($datos_oferta->precio_oferta)) ? $datos_oferta->precio_oferta : ''); ?>"
					style="background-position: right center; padding-right: 36px; background-image: url(<?php echo base_url(); ?>images/ico_euro.png);
					background-repeat: no-repeat; width: 335px; height: 40px; text-align: right;"
					onchange="actualizarCampoVista('precio_oferta', 'precio_oferta_vista_previa', '€'); actualizarCampoVista('precio_oferta', 'precio_oferta_vista_previa_detalles', '€'); actualizarDescuento();"/>
				<?php echo form_error('precio_oferta'); ?>
			</p>
			<p>
				<label for="descuento" style="display: block; width: 100%;">
					Descuento
				</label>
				<input type="text" id="descuento" name="descuento" disabled="disabled"
					class="contacto_form_input <?php echo (form_error('descuento') != '') ? 'input_error' : ''; ?>"
					value="<?php echo (!empty($validado)) ? set_value('descuento') : ((!empty($datos_oferta->descuento)) ? $datos_oferta->descuento : ''); ?>"
					style="background-position: right center; padding-right: 36px; background-image: url(<?php echo base_url(); ?>images/ico_porcentaje.png);
						background-repeat: no-repeat; width: 335px; height: 40px; text-align: right;" />
				<?php echo form_error('descuento'); ?>
			</p>
			<p>
				<label for="coste_real" style="display: block; width: 100%;">
					Coste Real *
				</label>
				<input type="text" id="coste_real" name="coste_real" class="contacto_form_input <?php echo (form_error('coste_real') != '') ? 'input_error' : ''; ?>"
					value="<?php echo (!empty($validado)) ? set_value('coste_real') : ((!empty($datos_oferta->coste_real)) ? $datos_oferta->coste_real : ''); ?>"
					style="background-position: right center; padding-right: 36px; background-image: url(<?php echo base_url(); ?>images/ico_euro.png);
						background-repeat: no-repeat; width: 335px; height: 40px; text-align: right;" />
				<?php echo form_error('coste_real'); ?>
			</p>
			<p>
				<label for="fecha_fin_pub" style="display: block; width: 100%;">
					Fecha límite contratación
				</label>
				<input type="text" id="fecha_fin_pub" name="fecha_fin_pub"
					class="datepicker_time contacto_form_input <?php echo (form_error('fecha_fin_pub') != '') ? 'input_error' : ''; ?>"
					<?php echo (!empty($validado)) ? ((set_value('fecha_fin_pub') != '') ? 'disabled="disabled"' : '') : ((!empty($datos_oferta)) ? ((empty($datos_oferta->fecha_fin_pub)) ? 'disabled="disabled"' : '') : ''); ?>
					value="<?php echo (!empty($validado)) ? set_value('fecha_fin_pub') : ((!empty($datos_oferta->fecha_fin_pub)) ? $datos_oferta->fecha_fin_pub : ''); ?>" style="width: 335px; height: 40px;"  readonly
					onchange="actualizarFechaFinPublicacion();"/>
			</p>
			<p>
				<label class="oferta_label" for="fecha_fin_pub">
					Duración periodo contratación indefinida
				</label>
				<input type="checkbox" id="fecha_fin_pub_indef" name="fecha_fin_pub_indef" value="1"
					onclick="cambia_valores_check('fecha_fin_pub'); actualizarFechaFinPublicacion();"
					<?php echo (!empty($validado)) ? set_checkbox('fecha_fin_pub_indef', 1) : ((!empty($datos_oferta)) ? ((empty($datos_oferta->fecha_fin_pub)) ? 'checked="checked"' : '') : ''); ?> style="width: auto;"/>
				<?php echo form_error('fecha_fin_pub'); ?>
                                
                                
				<label for="lbldet_duracion_camp" id="lbldet_duracion_camp" style="display: none; width: 100%;">
					Detalle Contratación indefinida:
				</label>
				<input type="text" id="det_duracion_camp" class="contacto_form_input <?php echo (form_error('det_duracion_camp') != '') ? 'input_error' : ''; ?>"
					name="det_duracion_camp" value="<?php echo (!empty($validado)) ? set_value('det_duracion_camp') : ((!empty($datos_oferta->detalle_fin_camp)) ? $datos_oferta->detalle_fin_camp : ''); ?>" style="display:none;width: 335px; height: 40px"
                                        onchange="actualizarFechaFinPublicacion();"/>
				<?php echo form_error('det_duracion_camp'); ?> 
			
			</p>
			<p>
				<label for="link" style="display: block; width: 100%;">
					Link a medio
				</label>
				<input type="text" id="link" name="link"
					class="contacto_form_input <?php echo (form_error('link') != '') ? 'input_error' : ''; ?>"
					value="<?php echo (!empty($validado)) ? set_value('link') : ((!empty($datos_oferta->link)) ? $datos_oferta->link : ''); ?>" 
					style="width: 335px; height: 40px;"/>
				<?php echo form_error('link'); ?>
			</p>
			<p>
				<label class="oferta_label" for="publicada">
					Publicar
				</label>
				<input type="checkbox" id="publicada" name="publicada" value="1"
					<?php echo (!empty($validado)) ? set_checkbox('publicada', 1) : ((!empty($datos_oferta)) ? ((!empty($datos_oferta->publicada)) ? 'checked="checked"' : '') : ''); ?> style="width: auto;"/>
				<?php echo form_error('publicada'); ?>
			</p>
			<p>
				<label>
					Imagen *
					<br>
					(Formatos permitidos: gif, jpg, png.
					<br>
					 Tamaño máximo: 3Mb.
					<br>
					 Dimensiones máximas: 1024x768 px.)
				</label>
				<input type="file" name="imagen" id="imagen" size="20" onchange="mostrarVistaPrevia(); mostrarFichero();" style="display: none;"/>
				<input type="text" id="nombre_imagen" name="nombre_imagen" class="contacto_form_input" value="" style="width: 335px; height: 40px;"
					disabled="disabled"/>
				<input type="button" class="cupid-orange" id="sign_in_sub" value="Examinar" onclick="$('#imagen').click();"
					style="background: none repeat scroll 0 0 rgb(202, 208, 209); color: gray;" />
				<?php echo (!empty($error_imagen)) ? $error_imagen : ''; ?>
			</p>
                        <p>
				<label for="galeria_img" style="display: block; width: 100%;">
					Galería Imágenes 
                                        <br>
					(Nombres de las imágenes separadas por , sin espacios)
					<br>
					 Ejemplo: imagen1.jpg,imagen2.jpg,imagen3.jpg
					<br>
					 Ruta FTP de las imágenes: /web/images/ofertas
					
				</label>
				<input type="text" id="galeria_img" name="galeria_img"
					class="contacto_form_input <?php echo (form_error('galeria_img') != '') ? 'input_error' : ''; ?>"
					value="<?php echo (!empty($validado)) ? set_value('galeria_img') : ((!empty($datos_oferta->galeria_img)) ? $datos_oferta->galeria_img : ''); ?>" 
					style="width: 335px; height: 40px;"/>
				<?php echo form_error('galeria_img'); ?>
			</p>
			<div class="contact_form_area" >
				<input type="submit" class="cupid-orange" id="sign_in_sub" value="Guardar" />
				<a href="<?php echo base_url() ?>administrador/ofertas">
					<input type="button" class="cupid-orange" id="sign_in_sub" value="Volver a ofertas" />
				</a>
			</div>
		<?php echo form_close(); ?>
	</div>
	<div style="display: table-cell; width: 50%; position: relative;">
		<div id="vista_previa_ficha" style="width: 480px; margin: auto; position: fixed;">
			<h3 style="text-align: center;">
				Vista previa ficha
				<div id="boton_detalles" style="float: right; padding: 3.5px 0; border-radius: 4px; background: #ff8600; text-align: center; color: white;
						font-weight: bold; font-size: 13.4px; width: 130px; cursor: pointer;"  onclick="cambiar_vista_previa('detalles');">
					VER VISTA DETALLES
				</div>
			</h3>
			<table cellspacing="0" cellpadding="0"
				style="border: 1px solid lightgray; width: 100%; border-spacing: 0; font-family: calibri; color: #333; margin-bottom: 10px;">
				<tr>
					<td id="vistaPreviaLista" rowspan="10" width="40%" style="background-position: center center; background-repeat: no-repeat;
						background-size: contain; background-image: none; margin-left: 1px; margin-top: 1px;">
					</td>
					<td colspan="4" width="60%" style="padding: 10.6px 16.3px 3.5px 16.3px; text-align: center;" bgcolor="white">
						<span id="titulo_vista_previa" style="font-weight: bold; font-size: 12.7px; color: gray;">
						</span>
					</td>
				</tr>
				<tr>
					<td colspan="4" width="60%" style="padding: 3.5px 16.3px 10.6px 16.3px; text-align: center;" bgcolor="white">
						<span id="descripcion_vista_previa" style="font-size: 11.3px;">
						</span>
					</td>
				</tr>
				<tr>
					<td width="21.34%" style="text-align: center; padding: 0 0 0 0;" bgcolor="white">
						<span style="color: gray; font-size: 11.3px;">
							ANTES
						</span>
					</td>
					<td colspan="2" width="17.32%" style="border-right: 1px solid lightgray; border-left: 1px solid lightgray; text-align: center;
							padding: 0 0 0 0;" bgcolor="white">
						<span style="color: gray; font-size: 11.3px;">
							DTO.
						</span>
					</td>
					<td width="21.34%" style="text-align: center; padding: 0 0 0 0;" bgcolor="white">
						<span style="color: gray; font-size: 11.3px;">
							AHORA
						</span>
					</td>
				</tr>
				<tr>
					<td width="21.34%" style="text-align: center; padding: 3.5px 0 0 0;" bgcolor="white">
						<span id="precio_real_vista_previa" style="color: gray; text-decoration: line-through; font-size: 14.1px;">
						</span>
					</td>
					<td colspan="2" width="17.32%" style="border-right: 1px solid lightgray; border-left: 1px solid lightgray; text-align: center;
							padding: 3.5px 0 0 0;" bgcolor="white">
						<span id="por_descuento_vista_previa" style="font-weight: bold; color: #F00; font-size: 17px;">
						</span>
					</td>
					<td width="21.34%" style="text-align: center; padding: 3.5px 0 0 0;" bgcolor="white">
						<span id="precio_oferta_vista_previa" style="font-weight: bold; font-size: 17px;">
						</span>
					</td>
				</tr>
				<tr>
					<td colspan="4" width="60%" height="5px" style="" bgcolor="white">
						<hr style="width: 93%; background-color: lightgray; height: 1px; border: 0px none;">
					</td>
				</tr>
				<tr>
					<td colspan="2" width="30%" style="border-right: 1px solid lightgray; padding: 3.5px 7.8px 3.5px 16.3px; text-align: center;"
							bgcolor="white">
						<span style="color: gray; font-size: 8.5px;">
							FECHA LÍMITE
						</span>
					</td>
					<td width="30%" colspan="2" style="padding: 3.5px 16.3px 3.5px 7.8px; text-align: center;" bgcolor="white">
						<span style="color: gray; font-size: 8.5px;">
							DURACIÓN CAMPAÑA
						</span>
					</td>
				</tr>
				<tr>
					<td colspan="2" width="30%" style="border-right: 1px solid lightgray; padding: 3.5px 7.8px 0 16.3px; vertical-align: top; text-align: center;"
							bgcolor="white">
						<span id="fecha_fin_pub_vista_previa" style=" font-size: 8.5px;">
						</span>
					</td>
					<td colspan="2" width="30%" style="padding: 3.5px 16.3px 0 7.8px; vertical-align: top; text-align: center;" bgcolor="white">
						<span id="duracion_vista_previa" style=" font-size: 8.5px;">
						</span>
					</td>
				</tr>
				<tr>
					<td colspan="4" width="60%" height="5px" style="" bgcolor="white">
						<hr style="width: 93%; background-color: lightgray; height: 1px; border: 0px none;">
					</td>
				</tr>
				<tr>
					<td colspan="2" width="30%" style="padding: 3.5px 0 7.1px 16.3px; text-align: center; vertical-align: middle;" bgcolor="white">
						<img id="medio_tipo_vista_previa" src="" style="max-height: 23.3px; max-width: 106px;"/>
					</td>
					<td colspan="2" width="30%" style="text-align: right; padding: 3.5px 16.3px 7.1px 0;" bgcolor="white">
						<div style="margin-left: auto; padding: 3.5px 0; border-radius: 4px; background: #ff8600; text-align: center; color: white;
							font-weight: bold; font-size: 13.4px;" >
							VER DETALLES
						</div>
					</td>
				</tr>
				<tr>
					<td width="21.34%" height="1px" style="" bgcolor="white">
					</td>
					<td width="8.66%" height="1px" style="" bgcolor="white">
					</td>
					<td width="8.66%" height="1px" style="" bgcolor="white">
					</td>
					<td width="21.34%" height="1px" style="" bgcolor="white">
					</td>
				</tr>
			</table>
		</div>
		<div id="vista_previa_detalles" style="width: 480px; margin: auto; position: fixed; display: none;">
			<h3 style="text-align: center;">
				Vista previa detalles
				<div id="boton_ficha" style="float: right; padding: 3.5px 0; border-radius: 4px; background: #ff8600; text-align: center; color: white;
						font-weight: bold; font-size: 13.4px; width: 106px; cursor: pointer;" onclick="cambiar_vista_previa('ficha');">
					VER VISTA FICHA
				</div>
			</h3>
			<table style="border: 1px solid lightgray; width: 100%; border-spacing: 0; font-family: calibri; color: #333; margin-bottom: 10px;">
				<tr>
					<td colspan="2" style="padding: 10px;">
						<h3 id="titulo_vista_detalles" style="font-size: 18px; text-align: center;">
						</h3>
					</td>
				</tr>
				<tr>
					<td id="vistaPreviaDetalles" width="50%" id="vistaPreviaDetalles" style="background-position: center center;
							background-repeat: no-repeat; background-size: contain; background-image: none; margin-left: 1px; margin-top: 1px;">
					</td>
					<td style="text-align: center; font-weight: bold; width:50%" >
						<table cellspacing="0" cellpadding="0"
							style="border: none; width: 100%; border-spacing: 0; font-family: calibri; color: #333; margin-bottom: 5px;">
							<tr>
								<td width="33%" style="text-align: center; padding: 0 0 0 0;" bgcolor="white">
									<span style="color: gray; font-size: 8.9px;">
										ANTES
									</span>
								</td>
								<td colspan="2" width="33%" style="border-right: 1px solid lightgray; border-left: 1px solid lightgray; text-align: center;
									padding: 0 0 0 0;" bgcolor="white">
									<span style="color: gray; font-size: 8.9px;">
										DTO.
									</span>
								</td>
								<td width="33%" style="text-align: center; padding: 0 0 0 0;" bgcolor="white">
									<span style="color: gray; font-size: 8.9px;">
										AHORA
									</span>
								</td>
							</tr>
							<tr>
								<td width="33%" style="text-align: center; padding: 2.8px 0 0 0;" bgcolor="white">
									<span id="precio_real_vista_previa_detalles" style="color: gray; text-decoration: line-through; font-size: 11.2px;">
									</span>
								</td>
								<td colspan="2" width="33%" style="border-right: 1px solid lightgray; border-left: 1px solid lightgray; text-align: center;
									padding: 2.8px 0 0 0;" bgcolor="white">
									<span id="por_descuento_vista_previa_detalles" style="font-weight: bold; color: #F00; font-size: 13.4px;">
									</span>
								</td>
								<td width="33%" style="text-align: center; padding: 2.8px 0 0 0;" bgcolor="white">
									<span id="precio_oferta_vista_previa_detalles" style="font-weight: bold; font-size: 13.4px;">
									</span>
								</td>
							</tr>
							<tr>
								<td colspan="4" width="100%" height="2px" style="" bgcolor="white">
									<hr style="width: 93%; background-color: lightgray; height: 1px; border: 0px none;">
								</td>
							</tr>
							<tr>
								<td colspan="2" width="50%" style="border-right: 1px solid lightgray; padding: 2.8px 6.1px 2.8px 12.9px; text-align: center;"
										bgcolor="white">
									<span style="color: gray; font-size: 6.7px;">
										FECHA LÍMITE
									</span>
								</td>
								<td width="50%" colspan="2" style="padding: 2.8px 12.9px 2.8px 6.1px; text-align: center;" bgcolor="white">
									<span style="color: gray; font-size: 6.7px;">
										DURACIÓN CAMPAÑA
									</span>
								</td>
							</tr>
							<tr>
								<td colspan="2" width="50%" style="border-right: 1px solid lightgray; padding: 2.8px 6.1px 0 12.9px; vertical-align: top; text-align: center;" bgcolor="white">
									<span id="fecha_fin_pub_vista_previa_detalles" style="font-size: 6.7px;">
									</span>
								</td>
								<td colspan="2" width="50%" style="padding: 2.8px 12.9px 0 6.1px; vertical-align: top; text-align: center;" bgcolor="white">
									<span id="duracion_vista_previa_detalles" style="font-size: 6.7px;">
									</span>
								</td>
							</tr>
							<tr>
								<td colspan="2" width="50%" style="padding: 2.8px 0 5.6px 12.9px; text-align: center; vertical-align: middle;"
										bgcolor="white">
									<img id="medio_tipo_vista_detalles" src="" style="max-height: 18.5px; max-width: 83.8px;"/>
								</td>
								<td colspan="2" width="50%" style="text-align: right; padding: 2.8px 8.9px 5.6px 0px;" bgcolor="white">
									<div style="margin-left: auto; padding: 2.8px 0; border-radius: 4px; background: #ff8600; text-align: center; color: white;
										font-weight: bold; font-size: 10.6px;" >
										SUSCRIBIRSE
									</div>
								</td>
							</tr>
							<tr>
								<td width="33%" height="1px" style="" bgcolor="white">
								</td>
								<td width="16.7%" height="1px" style="" bgcolor="white">
								</td>
								<td width="16.7%" height="1px" style="" bgcolor="white">
								</td>
								<td width="33%" height="1px" style="" bgcolor="white">
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<div style="padding: 3.3px 7.8px 6.1px; margin: 3.3px; background-color: #E6E6E6;">
							<h3 style="font-size: 10px;">
								Descripción de la oferta.
							</h3>
							<p id="detalles_vista_previa_detalles" style="text-align:justify; font-size:6.7px;" >
							</p>
						</div>
					</td>
				</tr>
				<tr style="vertical-align: top;">
					<td style="width:50%">
						<div style="padding: 3.3px 7.8px 6.1px; margin: 3.3px; background-color: #E6E6E6;">
							<h3 style="font-size: 10px;">
								Condiciones de la oferta.
							</h3>
							<p id="condiciones_vista_previa_detalles" style="text-align:justify; font-size:6.7px;">
							</p>
						</div>
					</td>
					<td style="width:50%;">
						<div style="padding: 3.3px 7.8px 6.1px; margin: 3.3px; background-color: #E6E6E6;">
							<h3 style="font-size: 10px;">
								Sobre el medio...
							</h3>
							<p id="descripcion_vista_previa_detalles" style="text-align:justify; font-size:6.7px;">
							</p>
						</div>
					</td>
				</tr>
			</table>
		</div>
	</div>
</div>