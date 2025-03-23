<script>
	$(document).ready(function(){
		agregarOfertas.ofertas = [<?php
	if(!empty($ofertas)){
		$i = 0;
		
		foreach($ofertas as $oferta){
			if($i == 0){
				$i = 1;
			}
			else{
				echo ', ';
			}
			
			echo $oferta->id_oferta;
		}
	}?>];

		seleccionarOferta.ofertas = 0;
		eliminarOferta.oferta = 0;
		obtenerOfertas();
	});
	
	function obtenerOfertas(){
		var palabra;

		if(palabra =='')
			return;

		getOfertas();

		palabra = $('#palabra').val();
		ofertas = $('#ofertas_anyadir').val();

		$.ajax({
			type: 'POST',
			data:{'palabra': palabra, 'ofertas': ofertas},
			url: '<?php echo base_url(); ?>administrador/getOfertasNewsletter',
			success: function(msg){
				$('#lista_ofertas').html(msg);
			}
		});
	}

	function seleccionarOferta(ck, campo){
		$('#' + ck).each(
			function(){
				if(this.checked){
					$('#' + campo).css('background-color', 'white');
					this.checked = false;
					$("#" + ck).removeAttr("checked");
					seleccionarOferta.ofertas--;

					if(seleccionarOferta.ofertas == 0){
						$('#icono-button-anyadir').removeClass('iconos-medium').addClass('iconos-medium-disabled');
						$('#button-anyadir').attr('disabled', 'disabled');
					}
				}
				else{
					$('#' + campo).css('background-color', 'lightgray');
					this.checked = true;
					$("#" + ck).attr("checked", "checked");
					seleccionarOferta.ofertas++;

					$('#icono-button-anyadir').removeClass('iconos-medium-disabled').addClass('iconos-medium');
					$('#button-anyadir').removeAttr('disabled');
				}
			}
		);
	}

	function eliminarOferta(ck, campo){
		$('#' + ck).each(
			function(){
				if(this.checked){
					$('#' + campo).css('background-color', 'white');
					this.checked = false;
					$("#" + ck).removeAttr("checked");
					eliminarOferta.ofertas--;

					if(eliminarOferta.ofertas == 0){
						$('#icono-button-eliminar').removeClass('iconos-medium').addClass('iconos-medium-disabled');
						$('#button-eliminar').attr('disabled', 'disabled');
					}
				}
				else{
					$('#' + campo).css('background-color', 'lightgray');
					this.checked = true;
					$("#" + ck).attr("checked", "checked");
					eliminarOferta.ofertas++;

					$('#icono-button-eliminar').removeClass('iconos-medium-disabled').addClass('iconos-medium');
					$('#button-eliminar').removeAttr('disabled');
				}
			}
		);
	}

	function agregarOfertas(){
		var seleccionado = 0;

		$("input[name='ckoferta']:checked").each(function(){
			seleccionado = parseInt($(this).val());

			if(agregarOfertas.ofertas.indexOf(seleccionado) == -1){
				var oferta = $(this).parent().find('label').html();

				$('#anyadidos').append(
					'<div id="oferta2_' + seleccionado + '" ' +
					'onclick="eliminarOferta(' + "'" + 'ckeliminar_'+ seleccionado + "'" + ', ' + "'" + 'oferta2_' + seleccionado + "'" + ');" ' +
					'style="padding: 5px; cursor: pointer; display: table; border: 1px solid white; background-color: white; width: 100%">' +
						'<div style="display: table-cell; height: 20px; padding: 0 10px; text-align: left; width: 100%;">' +
							'<input type="checkbox" id="ckeliminar_' + seleccionado +'" name="ckeliminar" value="'+ seleccionado + '" style="display: none;"/>' +
							'<label style="cursor:pointer;">' +
								oferta +
							'</label>' +
						'</div>' +
					'</div>'
				);

				agregarOfertas.ofertas.push(seleccionado);
			}
		});

		obtenerOfertas();
	}

	function eliminarOfertas(){
		var seleccionado = 0;

		$("input[name='ckeliminar']:checked").each(function(){
			seleccionado = parseInt($(this).val());

			agregarOfertas.ofertas.splice(agregarOfertas.ofertas.indexOf(seleccionado), 1);

			$(this).parent().parent().remove();
		});

		obtenerOfertas();
	}

	function getOfertas(){
		var ofertas = '';

		for(var i = 0; i < agregarOfertas.ofertas.length; i++){
			if(ofertas != '')
				ofertas += ' ';

			ofertas += agregarOfertas.ofertas[i];

		}

		$('#ofertas_anyadir').val(ofertas);
	}

	function selectAll(tipo){
		if(tipo == 'add'){
			$("input[name='ckoferta']").each(function(){
				if(!$(this).prop('checked'))
					seleccionarOferta('ckoferta_' + $(this).val(), 'oferta_' + $(this).val());
			});
		}
		else{
			$("input[name='ckeliminar']").each(function(){
				if(!$(this).prop('checked'))
					eliminarOferta('ckeliminar_' + $(this).val(), 'oferta2_' + $(this).val());
			});
		}
	}

	function unselectAll(tipo){
		if(tipo == 'add'){
			$("input[name='ckoferta']").each(function(){
				if($(this).prop('checked'))
					seleccionarOferta('ckoferta_' + $(this).val(), 'oferta_' + $(this).val());
			});
		}
		else{
			$("input[name='ckeliminar']").each(function(){
				if($(this).prop('checked'))
					eliminarOferta('ckeliminar_' + $(this).val(), 'oferta2_' + $(this).val());
			});
		}
	}
</script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery-ui.js"></script>
<link rel="stylesheet" href="<?php echo base_url();?>css/jquery-ui.min.css" type="text/css" media="screen"/>
<?php $this->load->helper('form'); ?>
<div>
	<div style="position: relative; margin-bottom: 5px; text-align: left; right: 20px;">
		<h3 style="text-align: center;">
			Nueva Newsletter (Selección de ofertas)
		</h3>
		<?php echo form_open_multipart("administrador/nuevaNewsletterOfertas/1", array('id' => 'form_newsletter')); ?>
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
			<div style="text-align: center; margin-top: 10px;">
				<label>
					Seleccionar ofertas
				</label>
			</div>
			<div class="reg_form">
				<div class="panel_buscar">
					<div class="head_buscar">
						<input class="campo-texto" type="text" id="palabra" name="palabra" value="" placeholder="Buscar nombre" style="width: 66%;"/>
						<input class="btn btn-personalizado button-buscar" type="button" onclick="obtenerOfertas();" value="Buscar"/>
					</div>
					<div class="actionGroupCheck" style="text-align: center;">
						<input class="btn btn-personalizado button-buscar" type="button" style="width: 35%;" onclick="selectAll('add');" value="Seleccionar todos"/>
						<input class="btn btn-personalizado button-buscar" type="button" style="width: 35%;" onclick="unselectAll('add');" value="Deseleccionar todos"/>
					</div>
					<div id="lista_ofertas" class="respuesta_buscar">
					</div>
				</div>
				<!-- fin div izq -->
				<!-- botones centrales -->
				<div class="editions_options_controls">
					<input class="btn btn-personalizado" type="button" onclick="agregarOfertas();" value="&gt;&gt;" style="width: 70%; margin: 20px 0"/>
					<input class="btn btn-personalizado" type="button" onclick="eliminarOfertas();" value="&lt;&lt;" style="width: 70%;"/>
				</div>
				<!-- div derecha -->
				<div class="result_options_choose">
					<div class="actionGroupCheck" style="text-align: center;">
						<input class="btn btn-personalizado button-buscar" type="button" style="width: 35%;" onclick="selectAll('remove');" value="Seleccionar todos"/>
						<input class="btn btn-personalizado button-buscar" type="button" style="width: 35%;" onclick="unselectAll('remove');" value="Deseleccionar todos"/>
					</div>
					<div id="anyadidos" class="contendor_divResult">
<?php
	if(!empty($ofertas)){
		foreach($ofertas as $oferta){
?>
						<div id="oferta2_<?php echo $oferta->id_oferta;?>" 
							onclick="eliminarOferta('ckeliminar_<?php echo $oferta->id_oferta;?>', 'oferta2_<?php echo $oferta->id_oferta;?>');"
							style="padding: 5px; cursor: pointer; display: table; border: 1px solid white; background-color: white; width: 97%">
							<div style="display: table-cell; height: 20px; padding: 0 10px; text-align: left; width: 100%;">
								<input type="checkbox" id="ckeliminar_<?php echo $oferta->id_oferta;?>" name="ckeliminar" value="<?php echo $oferta->id_oferta;?>" 
									style="display: none;" >
								<label style="cursor:pointer;">
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
			<div style="text-align: center;">
				<?php echo (form_error('ofertas') != '') ? '<label style="font-size: 10pt; font-weight: bold; color: red;">Debes seleccionar al menos una oferta</label>' : ''; ?>
			</div>
			<div class="contact_form_area" style="display: table; width: 100%;">
				<input type="hidden" name="ofertas" id="ofertas_anyadir" value="" />
				<div style="display: table-cell; width: 33%; text-align: center;">
					<a href="<?php echo base_url() ?>administrador/nuevaNewsletterMedios">
						<input type="button" class="cupid-orange" id="sign_in_sub" value="Volver a seleccionar medios" style="width: 95%; margin: auto;"/>
					</a>
				</div>
				<div style="display: table-cell; width: 33%; text-align: center;">
					<input type="button" class="cupid-orange" id="sign_in_sub" value="Guardar" style="width: 95%; margin: auto;"
							onclick="getOfertas(); $('#form_newsletter').attr('action', '<?php echo base_url() . 'administrador/nuevaNewsletterOfertas/2'; ?>'); $('#form_newsletter').submit();" />
				</div>
				<div style="display: table-cell; width: 33%; text-align: center;">
					<input type="button" class="cupid-orange" onclick="getOfertas(); $('#form_newsletter').submit();" id="sign_in_sub" value="Ir a selección de destinatarios" 
						 style="width: 95%; margin: auto;"/>
				</div>
			</div>
		<?php echo form_close(); ?>
	</div>
</div>
