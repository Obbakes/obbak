<?php
	if(!empty($ofertas)){
		foreach($ofertas as $oferta){
			$texto = '';
			
			if($oferta->publicada == 0){
				$texto = 'Oferta no publicada';
			}
			else{
				if($oferta->visible == 0){
					$texto = 'Oferta fuera del periodo de publicación';
				}
				else if($oferta->caducada == 1){
					$texto = 'Oferta caducada';
				}
			}
?>
	
		<div <?php echo (empty($texto)) ? ('id="oferta_' . $oferta->id_oferta . '"') : '';?>
				style="padding: 5px; background-color: white; display: table; border: 1px solid white; width: 97%;
				<?php echo (empty($texto)) ? 'cursor: pointer;' : ''; ?>" 
				<?php echo (empty($texto)) ? ('onclick="seleccionarOferta(\'ckoferta_' . $oferta->id_oferta . '\', \'oferta_' . $oferta->id_oferta . '\');"') : ''; ?>
				<?php echo (empty($texto)) ? '' : ('title="' . $texto . '"'); ?>>
			<div <?php echo (empty($texto)) ? 'id="ofertaAnyadido"' : ''; ?> style="display: table-cell; height: 20px; padding: 0 10px; text-align: left; 
					width: 100%;">
<?php
			if(empty($texto)){
?>
				<input type="checkbox" id="ckoferta_<?php echo $oferta->id_oferta;?>" name="ckoferta" value="<?php echo $oferta->id_oferta;?>" style="display: none;" />
<?php
			}
?>
				<label <?php echo (empty($texto)) ? 'style="cursor:pointer;"' : 'style="color: lightgray;"'; ?>>
					<b><?php echo $oferta->titulo;?></b> - <?php echo $oferta->descripcion; ?>
				</label>
			</div>
		</div>
<?php
		}
		
		if($num_ofertas > count($ofertas)){
?>
		<div style="padding: 5px; background-color: white; display: table; border: 1px solid white; width: 97%;" >
			<div style="display: table-cell; height: 20px; padding: 0 10px; text-align: left; width: 100%; text-align: center;">
				<label style="color: gray;">
					+ <?php echo ($num_ofertas - count($ofertas)); ?> ofertas
				</label>
			</div>
		</div>
<?php
		}
	}
?>
