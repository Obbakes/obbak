<div class="popUpDiv">
	<span class="popUpClose d">x</span>
	<div>
		<div class="imgDetalle" style="background: url('<?php echo base_url() . $oferta->imagen . '?num=' . mt_rand(1, 1000000000); ?>');"></div>
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
						<b><?php echo (empty($oferta->fecha_fin_pub)) ? 'Hasta fin de existencias' : date_format(date_create($oferta->fecha_fin_pub), "d/m/Y"); ?></b>
					</span>
				</li>
				<li>
					<span class="desOferta">
						<i class="iconO icon-ofert-time">DURACIÓN CAMPAÑA</i>
						</br>
						<b><?php echo $oferta->duracion_camp; ?></b>
					</span>
				</li>
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
					<div onclick="obtener_inscripciones_oferta(0, 1, <?php echo $oferta->id_oferta; ?>);">
						INSCRIBIRSE A OFERTA
					</div>
					<label class="precioOferta">
						<b><?php echo number_format($oferta->precio_oferta, 2, ',', '.'); ?>€</b>
					</label>
				</li>
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
		<div class="ofertaDetalle">
<?php
			if($oferta->destacada == 1){
?>
					<label class="destacadas">
						<i>Oferta destacada</i>
						<img title="Oferta destacada" class="destacado" src="<?php echo base_url() . 'images/destacado.png'; ?>" height="20px" style=""/>
					</label>
<?php
			}
?>
		</div>
	</div>
</div>