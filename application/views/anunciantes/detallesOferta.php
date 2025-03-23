<script type="text/javascript">
	$(document).mouseup(function (e){

		$('.popUpClose.i').click(function(event) {
			$('#div_inscripciones').hide();
			$('#lista_inscripciones').html('');
			$("body").css({"height": "auto","overflow":"auto"});
		});

		$('.popUpClose.d').click(function(event) {
			$('#div_detalles').hide();
			$('#lista_detalles').html('');
			$("body").css({"height": "auto","overflow":"auto"});
		});
	});
</script>

<?php 
	function is_medio_in_array($id, $array){
		foreach($array as $element){
			if($element->id_medio == $id){
				return true;
			}
		}
	}
?>

<div id="div_inscripciones" class="popUp" style="display: none;">
	<div id="lista_inscripciones">
	</div>
</div>
<div class="popUpDiv">
	<span class="popUpClose d">x</span>
	<div>
		<div class="imgDetalle" style="background: url('<?php echo base_url() . $oferta->imagen . '?num=' . mt_rand(1, 1000000000); ?>');">
			<div id="popup1" class="overlay">
				<div class="popup">
					<a class="close" href="#">&times;</a>
					<div class="content">
						<img src="<?php echo base_url() . $oferta->imagen . '?num=' . mt_rand(1, 1000000000); ?>" alt="" id="imgPop"/>
					</div>
				</div>
			</div>
			<a href="#popup1" id="zoomIn"></a>
			<div class="ofertaDetalle">
<?php	if($oferta->destacada == 1){ ?>
				<label class="destacadas">
					<i>Oferta destacada</i>
					<img title="Oferta destacada" class="destacado" src="<?php echo base_url() . 'images/destacado.png'; ?>" height="20px" style=""/>
				</label>
<?php	} ?>
			</div>
		</div>
		<div class="condDetalle">
			<h3>
				Descripción de la oferta.
			</h3>
			<p>
				<?php echo $oferta->detalle;  ?>
			</p>
		</div>
		<div class="medioDetalle">
			<a href="<?php echo $oferta->web_medio; ?>" target="_blank;">
				<img class="imgLogoMedio" src="<?php echo base_url() . $oferta->logo_medio; ?>" style="max-height: 100px; max-width: 100px;" title="<?php echo $oferta->medio . ' (' . $oferta->tipo . ')'; ?>" />
			</a>
			<?php if($oferta->link != ''){ ?>
				<a href="<?php echo $oferta->link; ?>" target="_blank;" class="linkMedio">
					Ver detalles del medio
				</a>
			<?php } ?>
		</div>
	</div>
	<div>
		<div class="infoDetalle">
			<ul>
				<li>
					<p class="tituloOferta"><?php echo $oferta->titulo; ?></p>
					<p class="descriptOferta"><?php echo $oferta->descripcion; ?></p>
				</li>
				<li>
					<span class="desOferta">
						<i class="iconO icon-ofert-limit">FECHA LÍMITE</i>
						</br>
						<b><?php echo (empty($oferta->fecha_fin_pub)) ? ((empty($oferta->detalle_fin_camp)) ? 'Hasta fin de existencias' : $oferta->detalle_fin_camp) : date_format(date_create($oferta->fecha_fin_pub), "d/m/Y"); ?></b>
					</span>
				</li>
				<li>
					<span class="desOferta">
						<i class="iconO icon-ofert-time">DURACIÓN CAMPAÑA</i>
						</br>
						<b><?php echo $oferta->duracion_camp; ?></b>
					</span>
				</li>
				<?php if($cliente->estado!=1  && is_medio_in_array($oferta->id_medio, $cliente->permisos_medios)){?>
					<li>
					<span class="desOferta" style="border:none;">
						<i class="iconO icon-ofert-before">ANTES</i>
						</br>
						<strike><?php echo number_format($oferta->precio_anterior, 2, ',', '.'); ?>€</strike>
					</span>
					</li>
					<li>
						<span class="desOferta">
							<i class="iconO icon-ofert-discount">DESCUENTO</i>
							</br>
							<b style=" color: #FF8600; "><?php echo intval($oferta->descuento); ?>%</b>
						</span>
					</li>
					<li>
					<?php
			if($oferta->inscrito == 1){
?>
					<div style="cursor: default;" class="yaInscrito">
						YA ESTÁS INSCRITO
					</div>
<?php
			}
			else{
?>
					<div onclick="inscribirse(<?php echo $oferta->id_oferta; ?>, <?php echo $pagina; ?>, 'detalles');">
						INSCRIBIRSE A OFERTA
					</div>
<?php
			}
?>
					<label class="precioOferta">
						<b><?php echo number_format($oferta->precio_oferta, 2, ',', '.'); ?>€</b>
					</label>
				</li>
				<?php } ?>
				

			</ul>
		</div>
		<div class="condDetalle">
			<h3>
				Condiciones de la oferta.
			</h3>
			<p>
				<?php echo $oferta->condiciones; ?>
			</p>
		</div>
	</div>
</div>