<?php
	if(!empty($medios)){
		foreach($medios as $medio){
?>
		<div id="medio_<?php echo $medio->id_medio;?>"
			style="padding: 5px; cursor: pointer; background-color: white; display: table; border: 1px solid white; width: 97%;"
			onclick="seleccionarMedio('ckmedio_<?php echo $medio->id_medio; ?>', 'medio_<?php echo $medio->id_medio; ?>');">
			<div id="medioAnyadido" style="display: table-cell; height: 20px; padding: 0 10px; text-align: left; width: 100%;">
				<input type="checkbox" id="ckmedio_<?php echo $medio->id_medio;?>" name="ckmedio" value="<?php echo $medio->id_medio;?>" style="display: none;" />
				<label style="cursor:pointer;">
					<?php echo $medio->nombre;?>
				</label>
			</div>
		</div>
<?php
		}

		if($num_medios > count($medios)){
?>
		<div style="padding: 5px; background-color: white; display: table; border: 1px solid white; width: 97%;" >
			<div style="display: table-cell; height: 20px; padding: 0 10px; text-align: left; width: 100%; text-align: center;">
				<label style="color: gray;">
					+ <?php echo ($num_medios - count($medios)); ?> medios
				</label>
			</div>
		</div>
<?php
		}
	}
?>
