<script language="javascript" type="text/javascript">
	function getTime() {
		for(var i=0; i<document.getElementsByClassName("timeForm").length; i++ ){
			var date = new Date();
			var str = document.getElementsByClassName("fecha")[i].value;
			var y =str.slice(0,4);
			var m = str.slice(5,7);
			var d = str.slice(8,10);
			var h = str.slice(11);
			var ho = h.slice(0,2);
			var min = h.slice(3,5);
			var sec = h.slice(6,8);

			date.setMonth(m-1,d);
			date.setYear(y);
			date.setHours(ho);
			date.setMinutes(min);
			date.setSeconds(sec);

			now = new Date();
			y2k = date;
			days = (y2k - now) / 1000 / 60 / 60 / 24;
			daysRound = Math.floor(days);
			hours = (y2k - now) / 1000 / 60 / 60 - (24 * daysRound);
			hoursRound = Math.floor(hours);
			minutes = (y2k - now) / 1000 /60 - (24 * 60 * daysRound) - (60 * hoursRound);
			minutesRound = Math.floor(minutes);
			seconds = (y2k - now) / 1000 - (24 * 60 * 60 * daysRound) - (60 * 60 * hoursRound) - (60 * minutesRound);
			secondsRound = Math.round(seconds);
			sec = (secondsRound == 1) ? " s" : " s";
			min = (minutesRound == 1) ? " m " : " m ";
			hr = (hoursRound == 1) ? " h " : " h ";
			dy = (daysRound == 1)	? " días " : " días ";

			document.getElementsByClassName("timeForm")[i].innerHTML ="<span style=\'color:black;\' class=\'tiempo\'><img style=\ '	width:20px;position:relative;top:4px;\' src='<?php echo base_url() .'images/reloj-icon.png' ; ?>' /></span>" + daysRound + dy +	hoursRound + hr + minutesRound + min + secondsRound + sec;
		}

		newtime = window.setTimeout("getTime();", 1000);
	}

	window.onload = getTime()
</script>
<div id="div_inscripciones" class="popUp" style="display: none;">
	<div id="lista_inscripciones">
	</div>
</div>
<div id="filtro-ordenar" class="filtro-ordenar contentgrid" >
	<div class="grid-6" style="text-align: left;">
	<p><span><?php echo $numOfertas; ?>&nbsp;</span> ofertas disponibles</p>
	</div>
	<div class="grid-6" style="text-align: right">
		<label>
			Ordenar por:
		</label>
		<select name="ordenar" id="ordenar" style="padding: 7px 14px;background: rgb(224, 232, 236);color: rgb(119, 121, 124);font-size: 14px;outline: none;font-family: verdana;border: none;" onchange="obtener_ofertas(1, <?php echo (!empty($filtro['pagina'])) ? $filtro['pagina'] : 0; ?>);">
			<option value="masreciente" <?php echo (!empty($filtro['ordenar']) && $filtro['ordenar'] == 'masreciente') ? 'selected="selected"' : ''; ?>>
				Más reciente
			</option>
			<option value="menosreciente" <?php echo (!empty($filtro['ordenar']) && $filtro['ordenar'] == 'menosreciente') ? 'selected="selected"' : ''; ?>>
				Menos reciente
			</option>
			<option value="ascendente" <?php echo (!empty($filtro['ordenar']) && $filtro['ordenar'] == 'ascendente') ? 'selected="selected"' : ''; ?>>
				Precio ascentente
			</option>
			<option value="descendente" <?php echo (!empty($filtro['ordenar']) && $filtro['ordenar'] == 'descendente') ? 'selected="selected"' : ''; ?>>
				Precio descendente
			</option>
		</select>
	</div>
</div>
<?php
	if(!empty($ofertas)){
		foreach($ofertas as $oferta){
?>
	<div class="listOfertas optMovilInfo" >
			<div class="leftInfo">
<?php	if($oferta->destacada == 1){ ?>
			<label class="destacadas">
				<img title="Oferta destacada" class="destacado" src="<?php echo base_url() . 'images/destacado.png'; ?>" height="20px" style=""/><i>Oferta destacada</i>
			</label>
<?php	} ?>
				<div onclick="verDetallesOferta(<?php echo $oferta->id_oferta; ?>);" style="width: 100%; background-image: url(<?php echo base_url() . $oferta->imagen; ?>);">
				</div>
			</div>
			<div class="rightInfo">
				<ul class="descripInfoOferta">
					<li>
						<div class="titleInfoOferta">
							<p class="tituloOferta" style="cursor:pointer;" onclick="verDetallesOferta(<?php echo $oferta->id_oferta; ?>);"><?php echo $oferta->titulo; ?></p>
							<p class="descriptOferta"><?php echo $oferta->descripcion; ?></p>
						</div>
						<label class="precioOferta optMovilPrice"><?php echo number_format($oferta->precio_oferta, 0, ',', '.'); ?> €</label>
					</li>
					<li class="contentTable">
						<div class="ContentImgMedio">
							<span class="MedioImg" style="background:url(<?php echo base_url() . $oferta->logo_medio; ?>);" title="<?php echo $oferta->medio . ' (' . $oferta->tipo . ')'; ?>">
							</span>
						</div>
						<div class="contentRight">
							<div class="resInfoOferta">
								<?php /*<span class="desOferta">
									<i class="iconO icon-ofert-time">DURACIÓN CAMPAÑA</i>
									<b><?php echo $oferta->duracion_camp; ?></b>
								</span>*/ ?>
								<span class="desOferta" style="border:none;">
									<i class="iconO icon-ofert-before">ANTES</i>
									<strike><?php echo number_format($oferta->precio_anterior, 2, ',', '.'); ?>€</strike>
								</span> 
								<span class="desOferta">
								<?php if(empty($oferta->fecha_fin_pub)){ ?>
									<i class="iconO icon-ofert-limit">FECHA LÍMITE</i>
									<?php } ?>
									<b class="limiteTiempo" <?php echo (!empty($oferta->fecha_fin_pub)) ? 'style="margin-top:0;font-family:\'Nexalight\';color:rgb(149, 151, 154);"' : ''; ?>><?php echo (empty($oferta->fecha_fin_pub)) ? 'Hasta fin de existencias' : 'Quedan'; ?></b>
									<?php if(!empty($oferta->fecha_fin_pub)){ ?>
									<input class="fecha" type="hidden" value="<?php echo $oferta->fecha_fin_pub ?> 23:59:59" />
									<h3 value = "<?php echo $oferta->fecha_fin_pub;?>" <?php echo (!empty($oferta->fecha_fin_pub)) ? 'class="timeForm"' : ''; ?>>
									</h3>
									<?php	} ?>
								</span>
								<span class="desOferta" style="border:none;width:30%;">
									<i class="iconO icon-ofert-discount">DESCUENTO</i>
									<b  style=" color: #FF8600; "><?php echo intval($oferta->descuento); ?>%</b>
								</span>
							</div>
<?php /*if(empty($filtro['cliente'])){ ?>
		<div class="verclientes" onclick="obtener_clientes_oferta(<?php echo $oferta->id_oferta; ?>);">
			Ver clientes
		</div>
<?php	} */ ?>
<?php	if($oferta->inscrito == 1){ ?>
						<div class="desinscribirse" style="cursor: default;">
							YA ESTÁS INSCRITO
						</div>
<?php	}else{ ?>
						<div class="inscribirse" onclick="obtener_inscripciones_oferta(0, 1, <?php echo $oferta->id_oferta; ?>);">
							INSCRIBIRSE A OFERTA
							<span class="tooltipinscribirse">Inscribirte a esta oferta no implica el pago ni la reserva de la misma, solo nos informa de tu interés. Nos pondremos en contacto contigo de inmediato.</span>
						</div>
<?php } ?>
						<div class="verDetalles" >
							<a onclick="verDetallesOferta(<?php echo $oferta->id_oferta; ?>);" >
								VER DETALLES
							</a>
						</div>
					</div>
				</li>
			</ul>
		</div>
	</div>
<?php
		}
	}
	else{
?>
		<table cellspacing="0" cellpadding="0" border="0" style="width: 650px; border-spacing: 0; font-family: calibri; color: #333; margin: auto;">
			<tr>
				<td style="text-align: center;">
					La búsqueda no produjo resultados
				</td>
			</tr>
		</table>
<?php
	}
?>

<div class="paginacion">
	<?php echo (!empty($paginacion)) ? $paginacion : ''; ?>
</div>
<script type="text/javascript">
	function cerrarPop(){
		$('#div_inscripciones').hide();
		$('#lista_inscripciones').html('');
		$("body").css({"height": "auto","overflow":"auto"});
	}
</script>