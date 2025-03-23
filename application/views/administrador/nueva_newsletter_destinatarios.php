<script>
	$(document).ready(function(){
		agregarClientes.clientes = [<?php
	if(!empty($clientes)){
		$i = 0;
		
		foreach($clientes as $cliente){
			if($i == 0){
				$i = 1;
			}
			else{
				echo ', ';
			}
			
			echo $cliente->id_cliente;
		}
	}?>];
		agregarAgencias.agencias = [<?php
				if(!empty($agencias)){
					$i = 0;
					
					foreach($agencias as $agencia){
						if($i == 0){
							$i = 1;
						}
						else{
							echo ', ';
						}
						
						echo $agencia->id_agencia;
					}
				}?>];

		seleccionarCliente.clientes = 0;
		seleccionarAgencia.agencias = 0;
		eliminarCliente.clientes = 0;
		eliminarAgencia.agencias = 0;
		obtenerClientes();
		obtenerAgencias();
	});
	
	function obtenerClientes(){
		var palabra;

		if(palabra =='')
			return;

		getClientes();

		palabra = $('#palabraClientes').val();
		clientes = $('#clientes_anyadir').val();

		$.ajax({
			type: 'POST',
			data:{'palabra': palabra, 'clientes': clientes},
			url: '<?php echo base_url(); ?>administrador/getClientesNewsletter',
			success: function(msg){
				$('#lista_clientes').html(msg);
			}
		});
	}

	function seleccionarCliente(ck, campo){
		$('#' + ck).each(
			function(){
				if(this.checked){
					$('#' + campo).css('background-color', 'white');
					this.checked = false;
					$("#" + ck).removeAttr("checked");
					seleccionarCliente.clientes--;

					if(seleccionarCliente.clientes == 0){
						$('#icono-button-anyadir').removeClass('iconos-medium').addClass('iconos-medium-disabled');
						$('#button-anyadir').attr('disabled', 'disabled');
					}
				}
				else{
					$('#' + campo).css('background-color', 'lightgray');
					this.checked = true;
					$("#" + ck).attr("checked", "checked");
					seleccionarCliente.clientes++;

					$('#icono-button-anyadir').removeClass('iconos-medium-disabled').addClass('iconos-medium');
					$('#button-anyadir').removeAttr('disabled');
				}
			}
		);
	}

	function eliminarCliente(ck, campo){
		$('#' + ck).each(
			function(){
				if(this.checked){
					$('#' + campo).css('background-color', 'white');
					this.checked = false;
					$("#" + ck).removeAttr("checked");
					eliminarCliente.clientes--;

					if(eliminarCliente.clientes == 0){
						$('#icono-button-eliminar').removeClass('iconos-medium').addClass('iconos-medium-disabled');
						$('#button-eliminar').attr('disabled', 'disabled');
					}
				}
				else{
					$('#' + campo).css('background-color', 'lightgray');
					this.checked = true;
					$("#" + ck).attr("checked", "checked");
					eliminarCliente.clientes++;

					$('#icono-button-eliminar').removeClass('iconos-medium-disabled').addClass('iconos-medium');
					$('#button-eliminar').removeAttr('disabled');
				}
			}
		);
	}

	function agregarClientes(){
		var seleccionado = 0;

		$("input[name='ckcliente']:checked").each(function(){
			seleccionado = parseInt($(this).val());

			if(agregarClientes.clientes.indexOf(seleccionado) == -1){
				var cliente = $(this).parent().find('label').html();

				$('#anyadidosClientes').append(
					'<div id="cliente2_' + seleccionado + '" ' +
					'onclick="eliminarCliente(' + "'" + 'ckeliminarcliente_'+ seleccionado + "'" + ', ' + "'" + 'cliente2_' + seleccionado + "'" + ');" ' +
					'style="padding: 5px; cursor: pointer; display: table; border: 1px solid white; background-color: white; width: 97%">' +
						'<div style="display: table-cell; height: 20px; padding: 0 10px; text-align: left; width: 100%;">' +
							'<input type="checkbox" id="ckeliminarcliente_' + seleccionado +'" name="ckeliminarcliente" value="'+ seleccionado + '" style="display: none;"/>' +
							'<label style="cursor:pointer;">' +
								cliente +
							'</label>' +
						'</div>' +
					'</div>'
				);

				agregarClientes.clientes.push(seleccionado);
			}
		});

		obtenerClientes();
	}

	function eliminarClientes(){
		var seleccionado = 0;

		$("input[name='ckeliminarcliente']:checked").each(function(){
			seleccionado = parseInt($(this).val());

			agregarClientes.clientes.splice(agregarClientes.clientes.indexOf(seleccionado), 1);

			$(this).parent().parent().remove();
		});

		obtenerClientes();
	}

	function getClientes(){
		var clientes = '';

		for(var i = 0; i < agregarClientes.clientes.length; i++){
			if(clientes != '')
				clientes += ' ';

			clientes += agregarClientes.clientes[i];

		}

		$('#clientes_anyadir').val(clientes);
	}
	
	function obtenerAgencias(){
		var palabra;

		if(palabra =='')
			return;

		getAgencias();

		palabra = $('#palabraAgencias').val();
		agencias = $('#agencias_anyadir').val();

		$.ajax({
			type: 'POST',
			data:{'palabra': palabra, 'agencias': agencias},
			url: '<?php echo base_url(); ?>administrador/getAgenciasNewsletter',
			success: function(msg){
				$('#lista_agencias').html(msg);
			}
		});
	}

	function seleccionarAgencia(ck, campo){
		$('#' + ck).each(
			function(){
				if(this.checked){
					$('#' + campo).css('background-color', 'white');
					this.checked = false;
					$("#" + ck).removeAttr("checked");
					seleccionarAgencia.agencias--;

					if(seleccionarAgencia.agencias == 0){
						$('#icono-button-anyadir').removeClass('iconos-medium').addClass('iconos-medium-disabled');
						$('#button-anyadir').attr('disabled', 'disabled');
					}
				}
				else{
					$('#' + campo).css('background-color', 'lightgray');
					this.checked = true;
					$("#" + ck).attr("checked", "checked");
					seleccionarAgencia.agencias++;

					$('#icono-button-anyadir').removeClass('iconos-medium-disabled').addClass('iconos-medium');
					$('#button-anyadir').removeAttr('disabled');
				}
			}
		);
	}

	function eliminarAgencia(ck, campo){
		$('#' + ck).each(
			function(){
				if(this.checked){
					$('#' + campo).css('background-color', 'white');
					this.checked = false;
					$("#" + ck).removeAttr("checked");
					eliminarAgencia.agencias--;

					if(eliminarAgencia.agencias == 0){
						$('#icono-button-eliminar').removeClass('iconos-medium').addClass('iconos-medium-disabled');
						$('#button-eliminar').attr('disabled', 'disabled');
					}
				}
				else{
					$('#' + campo).css('background-color', 'lightgray');
					this.checked = true;
					$("#" + ck).attr("checked", "checked");
					eliminarAgencia.agencias++;

					$('#icono-button-eliminar').removeClass('iconos-medium-disabled').addClass('iconos-medium');
					$('#button-eliminar').removeAttr('disabled');
				}
			}
		);
	}

	function agregarAgencias(){
		var seleccionado = 0;

		$("input[name='ckagencia']:checked").each(function(){
			seleccionado = parseInt($(this).val());

			if(agregarAgencias.agencias.indexOf(seleccionado) == -1){
				var agencia = $(this).parent().find('label').html();

				$('#anyadidosAgencias').append(
					'<div id="agencia2_' + seleccionado + '" ' +
					'onclick="eliminarAgencia(' + "'" + 'ckeliminaragencia_'+ seleccionado + "'" + ', ' + "'" + 'agencia2_' + seleccionado + "'" + ');" ' +
					'style="padding: 5px; cursor: pointer; display: table; border: 1px solid white; background-color: white; width: 97%">' +
						'<div style="display: table-cell; height: 20px; padding: 0 10px; text-align: left; width: 100%;">' +
							'<input type="checkbox" id="ckeliminaragencia_' + seleccionado +'" name="ckeliminaragencia" value="'+ seleccionado + '" style="display: none;"/>' +
							'<label style="cursor:pointer;">' +
								agencia +
							'</label>' +
						'</div>' +
					'</div>'
				);

				agregarAgencias.agencias.push(seleccionado);
			}
		});

		obtenerAgencias();
	}

	function eliminarAgencias(){
		var seleccionado = 0;

		$("input[name='ckeliminaragencia']:checked").each(function(){
			seleccionado = parseInt($(this).val());

			agregarAgencias.agencias.splice(agregarAgencias.agencias.indexOf(seleccionado), 1);

			$(this).parent().parent().remove();
		});

		obtenerAgencias();
	}

	function getAgencias(){
		var agencias = '';

		for(var i = 0; i < agregarAgencias.agencias.length; i++){
			if(agencias != '')
				agencias += ' ';

			agencias += agregarAgencias.agencias[i];

		}

		$('#agencias_anyadir').val(agencias);
	}

	function selectAll(tipo, destinatario){
		if(tipo == 'add'){
			if(destinatario == 'agencia'){
				$("input[name='ckagencia']").each(function(){
					if(!$(this).prop('checked'))
						seleccionarAgencia('ckagencia_' + $(this).val(), 'agencia_' + $(this).val());
				});
			}
			else{
				$("input[name='ckcliente']").each(function(){
					if(!$(this).prop('checked'))
						seleccionarCliente('ckcliente_' + $(this).val(), 'cliente_' + $(this).val());
				});
			}
		}
		else{
			if(destinatario == 'agencia'){
				$("input[name='ckeliminaragencia']").each(function(){
					if(!$(this).prop('checked'))
						eliminarAgencia('ckeliminaragencia_' + $(this).val(), 'agencia2_' + $(this).val());
				});
			}
			else{
				$("input[name='ckeliminarcliente']").each(function(){
					if(!$(this).prop('checked'))
						eliminarCliente('ckeliminarcliente_' + $(this).val(), 'cliente2_' + $(this).val());
				});
			}
		}
	}

	function unselectAll(tipo, destinatario){
		if(tipo == 'add'){
			if(destinatario == 'agencia'){
				$("input[name='ckagencia']").each(function(){
					if($(this).prop('checked'))
						seleccionarAgencia('ckagencia_' + $(this).val(), 'agencia_' + $(this).val());
				});
			}
			else{
				$("input[name='ckcliente']").each(function(){
					if($(this).prop('checked'))
						seleccionarCliente('ckcliente_' + $(this).val(), 'cliente_' + $(this).val());
				});
			}
		}
		else{
			if(destinatario == 'agencia'){
				$("input[name='ckeliminaragencia']").each(function(){
					if($(this).prop('checked'))
						eliminarAgencia('ckeliminaragencia_' + $(this).val(), 'agencia2_' + $(this).val());
				});
			}
			else{
				$("input[name='ckeliminarcliente']").each(function(){
					if($(this).prop('checked'))
						eliminarCliente('ckeliminarcliente_' + $(this).val(), 'cliente2_' + $(this).val());
				});
			}
		}
	}
</script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery-ui.js"></script>
<link rel="stylesheet" href="<?php echo base_url();?>css/jquery-ui.min.css" type="text/css" media="screen"/>
<?php $this->load->helper('form'); ?>
<div>
	<div style="position: relative; margin-bottom: 5px; text-align: left; right: 20px;">
		<h3 style="text-align: center;">
			Nueva Newsletter (Selección de destinatarios)
		</h3>
		<?php echo form_open_multipart("administrador/nuevaNewsletterDestinatarios/1", array('id' => 'form_newsletter')); ?>
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
			<div style="text-align: center; margin-top: 10px;">
				<label>
					Seleccionar clientes
				</label>
			</div>
			<div class="reg_form">
				<div class="panel_buscar">
					<div class="head_buscar">
						<input class="campo-texto" type="text" id="palabraClientes" name="palabraClientes" value="" placeholder="Buscar nombre" style="width: 66%;"/>
						<input class="btn btn-personalizado button-buscar" type="button" onclick="obtenerClientes();" value="Buscar"/>
					</div>
					<div class="actionGroupCheck" style="text-align: center;">
						<input class="btn btn-personalizado button-buscar" type="button" style="width: 35%;" onclick="selectAll('add', 'anunciante');" value="Seleccionar todos"/>
						<input class="btn btn-personalizado button-buscar" type="button" style="width: 35%;" onclick="unselectAll('add', 'anunciante');" value="Deseleccionar todos"/>
					</div>
					<div id="lista_clientes" class="respuesta_buscar">
					</div>
				</div>
				<!-- fin div izq -->
				<!-- botones centrales -->
				<div class="editions_options_controls">
					<input class="btn btn-personalizado" type="button" onclick="agregarClientes();" value="&gt;&gt;" style="width: 70%; margin: 20px 0"/>
					<input class="btn btn-personalizado" type="button" onclick="eliminarClientes();" value="&lt;&lt;" style="width: 70%;"/>
				</div>
				<!-- div derecha -->
				<div class="result_options_choose">
					<div class="actionGroupCheck" style="text-align: center;">
						<input class="btn btn-personalizado button-buscar" type="button" style="width: 35%;" onclick="selectAll('remove', 'anunciante');" value="Seleccionar todos"/>
						<input class="btn btn-personalizado button-buscar" type="button" style="width: 35%;" onclick="unselectAll('remove', 'anunciante');" value="Deseleccionar todos"/>
					</div>
					<div id="anyadidosClientes" class="contendor_divResult">
<?php
	if(!empty($clientes)){
		foreach($clientes as $cliente){
?>
						<div id="cliente2_<?php echo $cliente->id_cliente;?>" 
							onclick="eliminarCliente('ckeliminarcliente_<?php echo $cliente->id_cliente;?>', 'cliente2_<?php echo $cliente->id_cliente;?>');"
							style="padding: 5px; cursor: pointer; display: table; border: 1px solid white; background-color: white; width: 97%">
							<div style="display: table-cell; height: 20px; padding: 0 10px; text-align: left; width: 100%;">
								<input type="checkbox" id="ckeliminarcliente_<?php echo $cliente->id_cliente;?>" name="ckeliminarcliente" value="<?php echo $cliente->id_cliente;?>" 
									style="display: none;" >
								<label style="cursor:pointer;">
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
			<div style="text-align: center; margin-top: 10px;">
				<label>
					Seleccionar Agencias
				</label>
			</div>
			<div class="reg_form">
				<div class="panel_buscar">
					<div class="head_buscar">
						<input class="campo-texto" type="text" id="palabraAgencias" name="palabraAgencias" value="" placeholder="Buscar nombre" style="width: 66%;"/>
						<input class="btn btn-personalizado button-buscar" type="button" onclick="obtenerAgencias();" value="Buscar"/>
					</div>
					<div class="actionGroupCheck" style="text-align: center;">
						<input class="btn btn-personalizado button-buscar" type="button" style="width: 35%;" onclick="selectAll('add', 'agencia');" value="Seleccionar todos"/>
						<input class="btn btn-personalizado button-buscar" type="button" style="width: 35%;" onclick="unselectAll('add', 'agencia');" value="Deseleccionar todos"/>
					</div>
					<div id="lista_agencias" class="respuesta_buscar">
					</div>
				</div>
				<!-- fin div izq -->
				<!-- botones centrales -->
				<div class="editions_options_controls">
					<input class="btn btn-personalizado" type="button" onclick="agregarAgencias();" value="&gt;&gt;" style="width: 70%; margin: 20px 0"/>
					<input class="btn btn-personalizado" type="button" onclick="eliminarAgencias();" value="&lt;&lt;" style="width: 70%;"/>
				</div>
				<!-- div derecha -->
				<div class="result_options_choose">
					<div class="actionGroupCheck" style="text-align: center;">
						<input class="btn btn-personalizado button-buscar" type="button" style="width: 35%;" onclick="selectAll('remove', 'agencia');" value="Seleccionar todos"/>
						<input class="btn btn-personalizado button-buscar" type="button" style="width: 35%;" onclick="unselectAll('remove', 'agencia');" value="Deseleccionar todos"/>
					</div>
					<div id="anyadidosAgencias" class="contendor_divResult">
<?php
	if(!empty($agencias)){
		foreach($agencias as $agencia){
?>
						<div id="agencia2_<?php echo $agencia->id_agencia;?>" 
							onclick="eliminarAgencia('ckeliminaragencia_<?php echo $agencia->id_agencia;?>', 'agencia2_<?php echo $agencia->id_agencia;?>');"
							style="padding: 5px; cursor: pointer; display: table; border: 1px solid white; background-color: white; width: 97%">
							<div style="display: table-cell; height: 20px; padding: 0 10px; text-align: left; width: 100%;">
								<input type="checkbox" id="ckeliminaragencia_<?php echo $agencia->id_agencia;?>" name="ckeliminaragencia" value="<?php echo $agencia->id_agencia;?>" 
									style="display: none;" >
								<label style="cursor:pointer;">
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
			<div style="text-align: center;">
				<?php echo (!empty($validado)) ? '<label style="font-size: 10pt; font-weight: bold; color: red;">Debes seleccionar al menos un cliente o una agencia</label>' : ''; ?>
			</div>
			<div class="contact_form_area" style="display: table; width: 100%;">
				<input type="hidden" name="clientes" id="clientes_anyadir" value="" />
				<input type="hidden" name="agencias" id="agencias_anyadir" value="" />
				<div style="display: table-cell; width: 33%; text-align: center;">
					<a href="<?php echo base_url() ?>administrador/nuevaNewsletterOfertas">
						<input type="button" class="cupid-orange" id="sign_in_sub" value="Volver a seleccionar ofertas" style="width: 95%; margin: auto;"/>
					</a>
				</div>
				<div style="display: table-cell; width: 33%; text-align: center;">
					<input type="button" class="cupid-orange" id="sign_in_sub" value="Guardar" style="width: 95%; margin: auto;"
							onclick="getClientes(); getAgencias(); $('#form_newsletter').attr('action', '<?php echo base_url() . 'administrador/nuevaNewsletterDestinatarios/2'; ?>'); $('#form_newsletter').submit();" />
				</div>
				<div style="display: table-cell; width: 33%; text-align: center;">
					<input type="button" class="cupid-orange" onclick="getClientes(); getAgencias(); $('#form_newsletter').submit();" id="sign_in_sub" 
						value="Ir a confirmación" style="width: 95%; margin: auto;"/>
				</div>
			</div>
		<?php echo form_close(); ?>
	</div>
</div>
