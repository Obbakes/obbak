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

		obtener_ofertas(1,1);
	}

	function mostrarAutocompletar(menu){
		$('#' + menu).show();
	}

	$(document).mouseup(function (e){
		var container = $('#menu_autocompletar');

		if (!container.is(e.target) && container.has(e.target).length === 0){ // if the target of the click isn't the container nor a descendant of the container
			$('#menu_autocompletar').hide();
		}

		$('.popUpClose.d').click(function(event) {
			$('#div_detalles').hide();
			$('#lista_detalles').html('');
			$("body").css({"height": "auto","overflow":"auto"});
		});

		$('.popUpClose.i').click(function(event) {
			$('#div_inscripciones').hide();
			$('#lista_inscripciones').html('');
			$("body").css({"height": "auto","overflow":"auto"});
		});
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
		var tipos = '';
		var medios = '';
		var destacadas = '';

		if(modo == 1){
			cliente = $('#cliente').val();
			id_cliente = $('#id_cliente').val();
			max_precio = $('#max_precio').val();
			min_precio = $('#min_precio').val();
			ordenar = $('#ordenar').val();
			tipos = getTiposMedios();
			medios = getMedios();
			destacadas = getDestacadas();
		}
		$('#detalle_ofertas').html('<div class="fondoCargando"><div></div>Cargando...</div>');
		$.ajax({
			type: 'POST',
			data: {'cliente': cliente, 'id_cliente': id_cliente, 'max_precio': max_precio, 'min_precio': min_precio, 'ordenar': ordenar, 'tipos': tipos, 'medios': medios, 'destacadas': destacadas},
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
				var alto = document.body.clientHeight;
				$("body").css({"height": alto+"px","overflow":"hidden"});
			}
		});
	}
	//Función comentada de copiar filtros como etiquetas cabecera de lista de ofertas
	//var id=0;
	function cambiarEstadoCheck(obj, accion,event){
		var valor = $(obj).find('input').val();

		if(accion == '+')
			valor++;
		else if(accion == '-')
			valor--;

		valor = valor % 2;

		if(valor == 0){
			$(obj).find('.visto').hide();
			$(obj).css('font-weight', '100');
			/*var idRemove= $(obj).attr('id');
			$('#filter_'+ idRemove).remove();*/

		}else{
			$(obj).find('.visto').show();
			$(obj).css('font-weight', '900');
			/*$(obj).attr('id', id);
			var title=$(obj).parent().parent().find('span').text();
			title=title.trim();
			var text=$(obj).text();
			text=text.trim();
			$('#showFilter').append('<label for="" class="filterType" id="filter_'+ id +'"><span>' + title + ':</span>' + text + '<i onclick="cerrarShow(this)">X</i></label>');
			id++;*/
		}

		$(obj).find('input').val(valor);
	}

	function obtener_medios(medios,obj){
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
				obtener_ofertas(1, 1);
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

		return medios_seleccionados;
	}

	function getTiposMedios(){
		var tipos_medios_seleccionados = [];

		$('#filtro-tipos').find('input').each(function(){
			if($(this).val() == 1)
				tipos_medios_seleccionados.push($(this).attr('data-id'));
		});

		tipos_medios_seleccionados = tipos_medios_seleccionados.join(' ' );

		return tipos_medios_seleccionados;
	}

	function getDestacadas(){
		var destacadas = [];

		$('#filtro-destacadas').find('input').each(function(){
			if($(this).val() == 1)
				destacadas.push($(this).attr('data-id'));
		});

		destacadas = destacadas.join(' ' );

		return destacadas;
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

		obtener_ofertas(1, 1);
	}
/*--------------INSCRIBIR OFERTAS-----------------------*/
	function obtener_inscripciones_oferta(modo, pagina, oferta){
		$.ajax({
			type: 'POST',
			url: '<?php echo base_url(); ?>agencias/getInscripcionesOferta',
			data: {'modo': modo, 'pagina': pagina, 'oferta': oferta, 'estado': $('#estado').val()},
			success: function(msg){
				inscribir_clientes_oferta.numCambios = 0;
				$('#lista_inscripciones').html(msg);
				$('#div_inscripciones').show();
				var alto = document.body.clientHeight;
				$("body").css({"height": alto+"px","overflow":"hidden"});
				$('.limitHeight').css("max-height", alto-463+"px");
			}
		});
	}

	function inscribir_clientes_oferta(modo, pagina, oferta){
		if(!confirm('¿Quieres inscribir a estos anunciantes a esta oferta?'))
			return;

		var anunciantes = '';

		$('.select_permiso input:checked').each(function(){
			if(anunciantes != '')
				anunciantes += ' ';

			anunciantes += $(this).val();
		});

		$.ajax({
			type: 'POST',
			url: '<?php echo base_url(); ?>agencias/inscribirseOferta/' + oferta,
			data: {'anunciantes': anunciantes},
			success: function(msg){
				obtener_inscripciones_oferta(modo, pagina, oferta);
			}
		});
	}

	function checkInput(obj){
		if($(obj).prop('checked'))
			inscribir_clientes_oferta.numCambios++;
		else if(inscribir_clientes_oferta.numCambios > 0)
			inscribir_clientes_oferta.numCambios--;

		mostrarBotonCambio();
	}

	function selectAll(){
		$("input[name='permiso']").each(function(){
			if(!$(this).prop('checked'))
				$(this).click();
		});
	}

	function unselectAll(){
		$("input[name='permiso']").each(function(){
			if($(this).prop('checked'))
				$(this).click();
		});
	}

	function mostrarBotonCambio(){
		if(inscribir_clientes_oferta.numCambios == 0)
			$('#botonCambios').css('display', 'none');
		else
			$('#botonCambios').css('display', 'block');
	}

	function verDetallesOferta(id_oferta){
		$.ajax({
			type: 'POST',
			url: '<?php echo base_url(); ?>agencias/getDetallesOferta/' + id_oferta,
			success: function(msg){
				$('#lista_detalles').html(msg);
				$('#div_detalles').show();
				var alto = document.body.clientHeight;
				$("body").css({"height": alto+"px","overflow":"hidden"});
			}
		});
	}
	function toggleFilter(id){
		//console.log($('#' + id));
		//console.log($('#' + id + ' .dropdownFilter .grid-12').length);
		$('#' + id + ' .dropdownFilter').toggleClass('cerrar');
	}
	function cerrarShow(obj){
		console.log($(obj).parent());
		$(obj).parent().remove();
	}
</script>
<div id="div_clientes" class="popUp" style="display: none;">
	<div id="lista_clientes">
	</div>
</div>
<div id="div_detalles" class="popUp" style="display: none;">
	<div id="lista_detalles">
	</div>
</div>
<div class="contentgrid">

	<div class="grid-3">
		<form id="form_filtros" method="POST" action="<?php echo base_url();?>agencias/ofertas/1">
			<!--  Recoger los ids de los medios -->
			<input type="hidden" id="selec_medios" name="selec_medios" />
			<input type="hidden" id="selec_tipos_medios" name="selec_tipos_medios" />
			<input type="hidden" id="selec_destacadas" name="selec_destacadas" />
			<input type="hidden" id="selec_precios" name="selec_precios" />

			<div style="width: 100%; height: auto;">
				<div id="filtro-anunciante" class="filtro-anunciante" style="padding: 10px;">
					<div class="dropdownFilter">
						<span class="titleFilter" onclick="toggleFilter('filtro-anunciante');">
							Anunciante
							<i class="icon icon-filter"></i>
						</span>
						<div class="contentgrid">
							<input type="text" id="cliente" name="cliente" width="150px" class="contacto_filtro_input" onclick="mostrarAutocompletar('menu_autocompletar');"
							onkeyup="autocompletar('cliente', 'menu_autocompletar', 0, 1); obtener_ofertas(1, 1);" placeholder="Todos"
							value="<?php echo (!empty($filtro['cliente'])) ? $filtro['cliente'] : ''; ?>"/>
						</div>
						<div>
							<ul id="menu_autocompletar"></ul>
						</div>
						<input type="hidden" id="id_cliente" name="id_cliente" value="<?php echo (!empty($filtro['id_cliente'])) ? $filtro['id_cliente'] : ''; ?>"/>
					</div>
				</div>
				<div id="filtro-tipos" style="padding: 10px;">
					<div class="dropdownFilter">
						<span class="titleFilter" onclick="toggleFilter('filtro-tipos');">
							Tipos de medios
							<i class="icon icon-filter"></i>
						</span>
						<div class="contentgrid">
<?php
	if(!empty($tipos_medio)){
		$i = 0;

		foreach($tipos_medio as $tipo_medio){
?>
							<div onclick="cambiarEstadoCheck(this, '+',event); obtener_medios('',this); obtener_ofertas(1, 1);" class="grid-12" style="padding:0px 10px;margin-bottom: 5px; font-weigth: 100; cursor: pointer; <?php echo ($i >= 5) ? 'display: none;' : ''; ?>"	class="<?php echo ($i >= 5) ? 'ocultar' : ''; ?>" >
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
							<div onclick="mostrar('filtro-tipos');" id="button-mostrar" class="grid-12" data-value="0" style="cursor: pointer;">
								Mostrar más
							</div>
<?php
	}
?>
						</div>
					</div>
				</div>
			</div>

			<div id="filtro-medios" style="padding: 10px;">
				<div class="dropdownFilter">
					<span class="titleFilter" onclick="toggleFilter('filtro-medios');">
					MEDIOS
					<i class="icon icon-filter"></i>
					</span>
					<div class="contentgrid" id="detalle_medios">
					</div>
				</div>
			</div>
			<div id="filtro-destacadas" style="padding: 10px;">
				<div class="dropdownFilter">
					<span class="titleFilter" onclick="toggleFilter('filtro-destacadas');">
					Destacadas
					<i class="icon icon-filter"></i>
					</span>
					<div class="contentgrid">
						<div onclick="cambiarEstadoCheck(this, '+',event); obtener_ofertas(1, 1);" style="margin-bottom: 5px;padding: 0px 10px; font-weigth: 100; cursor: pointer;" >
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
						<div onclick="cambiarEstadoCheck(this, '+',event); obtener_ofertas(1, 1);" style="margin-bottom: 5px; padding: 0px 10px;font-weigth: 100; cursor: pointer;" >
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
			</div>

				<div id="filtro-precios" class="filtro-precio" style="padding: 10px;display: none;">
					<div class="dropdownFilter" >
						<span class="titleFilter" onclick="toggleFilter('filtro-precios');">
						Precio
						<i class="icon icon-filter"></i>
						</span>
						<div class="contentgrid" style="padding: 30px 0px 15px;">
							<div class="range-price">
								<div id="slider-range" class="clearfix">
								</div>
								<div class="range_price contentgrid">
									<div class="grid-6">
										<input class="to range" type="hidden" name="min_precio" id="min_precio"/>
										<span class="to-price"></span>
									</div>
									<div class="grid-6">
										<input class="from range" type="hidden" name="max_precio" id="max_precio"/>
										<span class="from-price"></span>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div style="text-align: right; padding:10px;">
					<input type="button" onclick="limpiar_filtro();" value="QUITAR FILTROS"  class="clearFilter" />
				</div>
			</form>
		</div>
		<div  class="grid-9">
			<div id="showFilter">
			</div>
			<div id="detalle_ofertas"></div>
		</div>
</div>