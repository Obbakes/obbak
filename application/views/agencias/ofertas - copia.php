<script type="text/javascript">
	function autocompletar(campo, menu, id, mostrar){
		var palabra = $('#' + campo).val();
		var id_cliente = $('#id_' + campo).val();

		$('#' + menu).html('');
		$('#' + menu).hide();
		$('#id_' + campo).val(0);

		$.ajax({
			url: "<?php echo base_url() . 'agencias/clientesAutocompletar'; ?>",
			dataType: "json",
			type: 'POST',
			data: {'palabra': palabra},
			success: function( data ) {
				var ul = '<li id="cliente_0" value="0" onclick="seleccionarAutocompletado(\'' + campo + '\', \'' + menu + '\', \'cliente_0\');">-- Todos --</li>';

				for(var item in data){
					var clase = '';

					if(id_cliente == data[item].value)
						clase = ' class="selected"';

					ul += '<li id="cliente_' + data[item].value + '" value="' + data[item].value + '" ' + clase + ' onclick="seleccionarAutocompletado(\'' + campo + '\', \'' + menu + '\', \'cliente_' + data[item].value + '\');">' +
					data[item].label +
					'</li>';
				}

				$('#' + menu).html(ul);

				if(mostrar == 1)
					$('#' + menu).show();
			}
		});
	}

	function seleccionarAutocompletado(campo, menu, item){
		if($('#' + item).attr('value') == '0'){
			$('#' + campo).val('');
			$('#id_' + campo).val(0);
		}
		else{
			$('#' + campo).val($('#' + item).html());
			$('#id_' + campo).val($('#' + item).attr('value'));
		}

		$('#' + menu).hide();
		$('#' + menu + ' li').removeClass('selected');
		$('#' + item).addClass('selected');

		//obtener_ofertas(1,1);
	}

	function mostrarAutocompletar(menu){
		$('#' + menu).show();
	}

	$(document).mouseup(function (e){
		var container = $('#menu_autocompletar');

		if (!container.is(e.target) && container.has(e.target).length === 0){ // if the target of the click isn't the container nor a descendant of the container
			$('#menu_autocompletar').hide();
		}

		var container = $('#lista_clientes');

		if (!container.is(e.target) && container.has(e.target).length === 0){ // if the target of the click isn't the container nor a descendant of the container
			$('#div_clientes').hide();
			container.html('');
		}
	});

	$(function() {
		autocompletar('cliente', 'menu_autocompletar', <?php echo (!empty($filtro['id_cliente'])) ? $filtro['id_cliente'] : 0; ?>, 0);
	});

	function obtener_ofertas(modo, pagina){

		var cliente = '';
		var id_cliente = '';
		var max_precio = '';
		var min_precio = '';
		var ordenar = '';

		if(modo == 1){
			var cliente = $('#cliente').val();
			var id_cliente = $('#id_cliente').val();
			var max_precio = $('#max_precio').val();
			var min_precio = $('#min_precio').val();
			var ordenar = $('#ordenar').val();
		}

		$.ajax({
			type: 'POST',
			data: {'cliente': cliente, 'id_cliente': id_cliente, 'max_precio': max_precio, 'min_precio': min_precio, 'ordenar': ordenar},
			url: '<?php echo base_url();?>agencias/lista_ofertas/' + modo + '/' + pagina,
			success: function(msg){
				$('#detalle_ofertas').html(msg);
			}
		});
	}

	function obtener_clientes_oferta(id_oferta){
		$.ajax({
			type: 'POST',
			url: '<?php echo base_url(); ?>agencias/getClientesOferta/' + id_oferta,
			success: function(msg){
				$('#lista_clientes').html(msg);
				$('#div_clientes').show();
			}
		});
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
			$(obj).css('font-weight', '100');
		}
		else{
			$(obj).find('.visto').show();
			$(obj).css('font-weight', '900');
		}

		$(obj).find('input').val(valor);
	}

	function obtener_medios(medios){
		var tipos = [];

		$('#filtro-tipos').find('input').each(function(){
			if($(this).val() == 1)
				tipos.push($(this).attr('data-id'));
		});

		tipos = tipos.join(' ');

		$.ajax({
			type: 'POST',
			data: {'tipos': tipos, 'seleccionados': medios},
			url: '<?php echo base_url(); ?>medios/obtenerMediosFiltro',
			success: function(msg){
				$('#detalle_medios').html(msg);

				$('.cb-tristate-small').each(function(){
					if(!$(this).hasClass('ayuda'))
						cambiarEstadoCheck($(this).parents().get(0), '=');
				});
			}
		});
	}

	function mostrar(filtro){
		var mostrado = $('#' + filtro).find('#button-mostrar').attr('data-value');

		if(mostrado == 0){
			$('#' + filtro).find('.ocultar').show();
			$('#' + filtro).find('#button-mostrar').attr('data-value', 1);
			$('#' + filtro).find('#button-mostrar').html('Mostrar menos');
		}
		else{
			$('#' + filtro).find('.ocultar').hide();
			$('#' + filtro).find('#button-mostrar').attr('data-value', 0);
			$('#' + filtro).find('#button-mostrar').html('Mostrar más');
		}
	}

	$(document).ready(function(){
		obtener_ofertas(0,1);

		$('.cb-tristate-small').each(function(){
			if(!$(this).hasClass('ayuda'))
				cambiarEstadoCheck($(this).parents().get(0), '=');
		});

		obtener_medios('<?php echo (!empty($filtro['medio'])) ? implode(' ', $filtro['medio']) : ''; ?>');

		$("#slider-range").slider({
			range: true,
			min: <?php echo $precios->min_precio; ?>,
			max: <?php echo $precios->max_precio; ?>,
			values: [<?php echo (!empty($filtro['min_precio'])) ? $filtro['min_precio'] : $precios->min_precio; ?>, <?php echo (!empty($filtro['max_precio'])) ? $filtro['max_precio'] : $precios->max_precio; ?>],
			slide: function (event, ui) {
				$(".from.range").val(ui.values[1]);
				$(".to.range").val(ui.values[0]);
				$(".from-price").text(ui.values[1]);
				$(".to-price").text(ui.values[0]);
			},
			create: function(event, ui) {
				$(".from.range").val($(this).slider("values", 1));
				$(".to.range").val($(this).slider("values", 0));
				$(".from-price").text($(this).slider("values", 1));
				$(".to-price").text($(this).slider("values", 0));
			}
		});
	});

	function getMedios(){
		var medios_seleccionados = [];

		$('#filtro-medios').find('input').each(function(){
			if($(this).val() == 1)
				medios_seleccionados.push($(this).attr('data-id'));
		});

		medios_seleccionados = medios_seleccionados.join(' ' );

		$('#selec_medios').val(medios_seleccionados);
	}

	function getTiposMedios(){
		var tipos_medios_seleccionados = [];

		$('#filtro-tipos').find('input').each(function(){
			if($(this).val() == 1)
				tipos_medios_seleccionados.push($(this).attr('data-id'));
		});

		tipos_medios_seleccionados = tipos_medios_seleccionados.join(' ' );

		$('#selec_tipos_medios').val(tipos_medios_seleccionados);
	}

	function getDestacadas(){
		var descatadas = [];

		$('#filtro-destacadas').find('input').each(function(){
			if($(this).val() == 1)
				descatadas.push($(this).attr('data-id'));
		});

		descatadas = descatadas.join(' ' );

		$('#selec_destacadas').val(descatadas);
	}

	function limpiar_filtro(){
		$('.cb-tristate-small').each(function(){
			if(!$(this).hasClass('ayuda'))
				$(this).find('input').val(0);
		});

		$('.cb-tristate-small').each(function(){
			if(!$(this).hasClass('ayuda'))
				cambiarEstadoCheck($(this).parents().get(0), '=');
		});
	}
</script>
<div id="div_clientes" style="display: none; left: 0; position: fixed; top: 0; width: 100%; height: 100%;">
	<div id="lista_clientes" style="text-align: center; background-color: white; border: 2px solid #ccc; color: #706f6f; display: block; overflow: auto;
			max-height: 300px; width: 300px; padding: 20px; margin: 150px auto;">
	</div>
</div>
<div style="display: table; width: 100%; padding: 10px;">
	<div style="width: 31.25%; display: table-cell; vertical-align: top;">
		<form id="form_filtros" method="POST" action="<?php echo base_url();?>agencias/ofertas/1">
			<!--  Recoger los ids de los medios -->
			<input type="hidden" id="selec_medios" name="selec_medios" />
			<input type="hidden" id="selec_tipos_medios" name="selec_tipos_medios" />
			<input type="hidden" id="selec_destacadas" name="selec_destacadas" />
			<input type="hidden" id="selec_precios" name="selec_precios" />

			<div style="width: 100%; height: auto; border: 1px solid #ff8600;">
				<div style="text-align: right; padding-right: 3%;">
					<input type="button" onclick="getMedios(); getTiposMedios(); getDestacadas(); $('#form_filtros').submit();" value="APLICAR FILTRO" style="cursor:pointer;padding: 1px 0px;border-radius: 4px;background: #FF8600 none repeat scroll 0% 0%;text-align: center;color: #FFF;font-weight: bold;font-size: 12px;width: 125px;margin-top: 10px;display: inline-block;" />
					<input type="button" onclick="limpiar_filtro();" value="QUITAR FILTROS" style="cursor:pointer;padding: 1px 0px;border-radius: 4px;background: #FF8600 none repeat scroll 0% 0%;text-align: center;color: #FFF;font-weight: bold;font-size: 12px;width: 120px;display: inline-block;" />
				</div>

				<div id="filtro-anunciante" class="filtro-anunciante" style="padding: 10px;">
					<div style="border-bottom: 1px solid #ff8600; margin-bottom: 5px; color: #ff8600;font-size: 18px;font-weight: bold;">
						Anunciante
					</div>
					<div>
						<input type="text" id="cliente" name="cliente" width="150px" class="contacto_filtro_input" onclick="mostrarAutocompletar('menu_autocompletar');"
							onkeyup="autocompletar('cliente', 'menu_autocompletar', 0, 1);" placeholder="Todos"
							value="<?php echo (!empty($filtro['cliente'])) ? $filtro['cliente'] : ''; ?>"/>
					</div>
					<div>
						<ul id="menu_autocompletar"></ul>
					</div>
					<input type="hidden" id="id_cliente" name="id_cliente" value="<?php echo (!empty($filtro['id_cliente'])) ? $filtro['id_cliente'] : ''; ?>"/>
				</div>

				<div id="filtro-precios" class="filtro-precio" style="padding: 10px;">
					<div class="range-price">
						<div style="border-bottom: 1px solid #ff8600; margin-bottom: 15px; color: #ff8600;">
							Precios
						</div>
						<div id="slider-range" class="clearfix">
						</div>
						<div class="range_price">
							<div class="price-right">
								<input class="from range" type="hidden" name="max_precio" id="max_precio"/>
								<span class="from-price"></span>€
							</div>
							<div class="price-left">
								<input class="to range" type="hidden" name="min_precio" id="min_precio"/>
								<span class="to-price"></span>€
							</div>
						</div>
					</div>
				</div>

				<div id="filtro-tipos" style="padding: 10px;">
					<div style="border-bottom: 1px solid #ff8600; margin-bottom: 5px; color: #ff8600;">
						Tipos de medios
					</div>
<?php
	if(!empty($tipos_medio)){
		$i = 0;

		foreach($tipos_medio as $tipo_medio){
?>
					<div onclick="cambiarEstadoCheck(this, '+'); obtener_medios('');" style="margin-bottom: 5px; font-weigth: 100; cursor: pointer; <?php echo ($i >= 5) ? 'display: none;' : ''; ?>"
							<?php echo ($i >= 5) ? 'class="ocultar"' : ''; ?>>
						<div class="cb-tristate-small" style="margin-right: 3px; display: inline-block;">
							<input type="hidden" name="tipos[]" data-id="<?php echo $tipo_medio->id_tipo; ?>" autocomplete="off"
									value="<?php echo (!empty($filtro['tipo_medio']) && in_array($tipo_medio->id_tipo, $filtro['tipo_medio'])) ? 1 : 0; ?>" />
							<div >
								<div>
									<img class="visto" src="<?php echo base_url(); ?>images/visto_checkbox.png"/>
								</div>
							</div>
						</div>
						<?php echo $tipo_medio->tipo; ?>
					</div>
<?php
			$i++;
		}
	}
	if(!empty($i) && $i > 5){
?>
					<div onclick="mostrar('filtro-tipos');" id="button-mostrar" data-value="0" style="cursor: pointer;">
						Mostrar más
					</div>
<?php
	}
?>
				</div>

				<div id="filtro-medios" style="padding: 10px;">
					<div style="border-bottom: 1px solid #ff8600; margin-bottom: 5px; color: #ff8600;">
						Medios
					</div>
					<div id="detalle_medios">
						<div style="background-color: #eee;height: 20px;width: 100%;padding-left:3px;padding-top:3px;">No hay medios</div>
					</div>
				</div>

				<div id="filtro-destacadas" style="padding: 10px;">
					<div style="border-bottom: 1px solid #ff8600; margin-bottom: 5px; color: #ff8600;">
						Destacadas
					</div>
					<div onclick="cambiarEstadoCheck(this, '+');" style="margin-bottom: 5px; font-weigth: 100; cursor: pointer;" >
						<div class="cb-tristate-small" style="margin-right: 3px; display: inline-block;">
							<input type="hidden" name="destacadas[]" data-id="1" autocomplete="off"
									value="<?php echo (!empty($filtro['destacadas']) && in_array(1, $filtro['destacadas'])) ? 1 : ''; ?>" />
							<div >
								<div>
									<img class="visto" src="<?php echo base_url(); ?>images/visto_checkbox.png"/>
								</div>
							</div>
						</div>
						Destacadas
					</div>
					<div onclick="cambiarEstadoCheck(this, '+');" style="margin-bottom: 5px; font-weigth: 100; cursor: pointer;" >
						<div class="cb-tristate-small" style="margin-right: 3px; display: inline-block;">
							<input type="hidden" name="destacadas[]" data-id="0" autocomplete="off"
									value="<?php echo (!empty($filtro['destacadas']) && in_array(0, $filtro['destacadas'])) ? 1 : ''; ?>" />
							<div >
								<div>
									<img class="visto" src="<?php echo base_url(); ?>images/visto_checkbox.png"/>
								</div>
							</div>
						</div>
						No destacadas
					</div>
				</div>
			</div>
		</form>
	</div>
	<div style="width: 1.04%; display: table-cell; height: 500px; vertical-align: top;">
	</div>

	<div id="detalle_ofertas" style="width: 67.71%; display: table-cell; vertical-align: top;">
	</div>
</div>