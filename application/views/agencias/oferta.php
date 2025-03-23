<script type="text/javascript">
	$(document).mouseup(function (e){
		var container = $('#lista_inscripciones');
		
		if (!container.is(e.target) && container.has(e.target).length === 0){ // if the target of the click isn't the container nor a descendant of the container
			$('#div_inscripciones').hide();
			container.html('');
		}
	});
	
	function obtener_inscripciones_oferta(modo, pagina){
		$.ajax({
			type: 'POST',
			url: '<?php echo base_url(); ?>agencias/getInscripcionesOferta',
			data: {'modo': modo, 'pagina': pagina, 'oferta': <?php echo $oferta->id_oferta; ?>, 'estado': $('#estado').val()},
			success: function(msg){
				inscribir_clientes_oferta.numCambios = 0;
				$('#lista_inscripciones').html(msg);
				$('#div_inscripciones').show();
			}
		});
	}
	
	function inscribir_clientes_oferta(modo, pagina){
		if(!confirm('¿Quieres inscribir a estos anunciantes a esta oferta?'))
			return;
		
		var anunciantes = '';
		
		$('.cb-tristate').each(function(){
			if($(this).find('input').val() == 1){
				if(anunciantes != '')
					anunciantes += ' ';
				
				anunciantes += $(this).find('input').attr('data-id');
			}
		});
		
		$.ajax({
			type: 'POST',
			url: '<?php echo base_url(); ?>agencias/inscribirseOferta/' + <?php echo $oferta->id_oferta; ?>,
			data: {'anunciantes': anunciantes},
			success: function(msg){
				obtener_inscripciones_oferta(modo, pagina);
			}
		});
	}
	
	function mostrarBotonCambio(){
		if(inscribir_clientes_oferta.numCambios == 0)
			$('#botonCambios').css('display', 'none');
		else
			$('#botonCambios').css('display', 'block');
	}

	function cambiarEstadoCheck(obj, accion){
		var valor = $(obj).find('input').val();

		if(accion == '+')
			valor++;
		else if(accion == '-')
			valor--;
		
		valor = valor % 2;

		if(valor == 0){
			$(obj).find('.visto').hide();

			if(accion != '=')
				inscribir_clientes_oferta.numCambios--;
		}
		else{
			$(obj).find('.visto').show();

			if(accion != '=')
				inscribir_clientes_oferta.numCambios++;
		}

		mostrarBotonCambio();

		$(obj).find('input').val(valor);
	}
</script>
<div id="div_inscripciones" class="popUp" style="display: none;">
	<div id="lista_inscripciones">
	</div>
</div>
<table style="display:inline-block;">
	<tr>
		<td colspan="2" style="text-align: center;">
			<h1 style="font-weight: bold">
				<?php echo $oferta->titulo; ?>
			</h1>
		</td>
	</tr>
	<tr>
		<td id="imagen_detalle" style="text-align: center;">
			<img style="width: 300;" src="<?php echo base_url() . $oferta->imagen . '?num=' . mt_rand(1, 1000000000); ?>"/>
		</td>
		<td id="precio_detalle" >
			<table cellspacing="0" cellpadding="0" border="0" style="width: 100%; border-spacing: 0; font-family: calibri; color: #333; margin-bottom: 10px;">
				<tr>
					<td width="35.57%" style="text-align: center; padding: 0 0 0 0;" bgcolor="white">
						<span style="color: gray;">
							ANTES
						</span>
					</td>
					<td colspan="2" width="28.86%" style="border-right: 1px solid lightgray; border-left: 1px solid lightgray; text-align: center; 
							padding: 0 0 0 0;" 
						bgcolor="white">
						<span style="color: gray;">
							DTO.
						</span>
					</td>
					<td width="35.57%" style="text-align: center; padding: 0 0 0 0;" bgcolor="white">
						<span style="color: gray;">
							AHORA
						</span>
					</td>
				</tr>
				<tr>
					<td width="35.57%" style="text-align: center; padding: 5px 0 0 0;" bgcolor="white">
						<span style="color: gray; text-decoration: line-through; font-size: 20px;">
							<?php echo number_format($oferta->precio_anterior, 2, ',', '.'); ?>€
						</span>
					</td>
					<td colspan="2" width="28.86%" style="border-right: 1px solid lightgray; border-left: 1px solid lightgray; text-align: center; 
							padding: 5px 0 0 0;" bgcolor="white">
						<span style="font-weight: bold; color: #F00; font-size: 24px;">
							<?php echo intval($oferta->descuento); ?>%
						</span>
					</td>
					<td width="35.57%" style="text-align: center; padding: 5px 0 0 0;" bgcolor="white">
						<span style="font-weight: bold; font-size: 24px;">
							<?php echo number_format($oferta->precio_oferta, 2, ',', '.'); ?>€
						</span>
					</td>
				</tr>
				<tr>
					<td colspan="4" width="100%" height="5px" style="" bgcolor="white">
						<hr style="width: 93%; background-color: lightgray; height: 1px; border: 0px none;">
					</td>
				</tr>
				<tr>
					<td colspan="2" width="50%" style="border-right: 1px solid lightgray; padding: 0px 11px 0px 23px; text-align: center; font-size: 12px;" 
							bgcolor="white">
						<span style="color: gray;">
							FECHA LÍMITE
						</span>
					</td>
					<td width="50%" colspan="2" style="padding: 0px 23px 0px 11px; text-align: center; font-size: 12px;" bgcolor="white">
						<span style="color: gray;">
							DURACIÓN CAMPAÑA
						</span>
					</td>
				</tr>
				<tr>
					<td colspan="2" width="50%" style="border-right: 1px solid lightgray; padding: 5px 11px 0 23px; vertical-align: top; text-align: center;
							font-size: 12px;" bgcolor="white">
						<span style="">
							<?php echo (empty($oferta->fecha_fin_pub)) ? 'Hasta fin de existencias' : date_format(date_create($oferta->fecha_fin_pub), "d/m/Y"); ?>
						</span>
					</td>
					<td colspan="2" width="50%" style="padding: 5px 23px 0 11px; vertical-align: top; text-align: center; font-size: 12px;" bgcolor="white">
						<span style="">
							<?php echo $oferta->duracion_camp; ?>
						</span>
					</td>
				</tr>
				<tr>
					<td colspan="4" width="100%" height="5px" style="" bgcolor="white">
						<hr style="width: 93%; background-color: lightgray; height: 1px; border: 0px none;">
					</td>
				</tr>
				<tr>
					<td colspan="2" width="50%" style="padding: 5px 0 10px 23px; text-align: center; vertical-align: middle;" bgcolor="white">
						<img src="<?php echo base_url() . $oferta->logo_medio; ?>" style="max-height: 33px; max-width: 150px;" 
								title="<?php echo $oferta->medio . ' (' . $oferta->tipo . ')'; ?>" />
					</td>
					<td colspan="2" width="50%" style="text-align: right; padding: 5px 16px 10px 0;" bgcolor="white">
						<div style="margin-left: auto; padding: 5px 0; border-radius: 4px; background: #ff8600; text-align: center; color: white; 
							font-weight: bold; font-size: 19px; width: 150px; cursor: pointer;" onclick="obtener_inscripciones_oferta(0, 1);">
							INSCRIBIR
						</div>
					</td>
				</tr>
				<tr>
					<td width="35.57%" height="1px" style="" bgcolor="white">
					</td>
					<td width="14.43%" height="1px" style="" bgcolor="white">
					</td>
					<td width="14.43%" height="1px" style="" bgcolor="white">
					</td>
					<td width="35.57%" height="1px" style="" bgcolor="white">
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<div style="padding: 6px 14px 11px; margin: 6px; background-color: #E6E6E6;">
				<h3>
					Descripción de la oferta.
				</h3>
				<p style="text-align: left;">
					<?php echo $oferta->detalle;  ?>
				</p>
			</div>
		</td>
	 </tr>
	<tr style="vertical-align: top;">
		<td style="width:50%">
			<div style="padding: 6px 14px 11px; margin: 6px; background-color: #E6E6E6;">
				<h3>
					Condiciones de la oferta.
				</h3>
				<p style="text-align: left;">
					<?php echo $oferta->condiciones; ?>
				</p>
			</div>
		</td>
		<td style="width:50%">
			<div style="padding: 6px 14px 11px; margin: 6px; background-color: #E6E6E6;">
				<h3>
					Sobre el medio...
				</h3>
				<p style="text-align: left;">
					<?php echo $oferta->descripcion_medio;  ?>
				</p>
			</div>
		</td>
	</tr>
</table>