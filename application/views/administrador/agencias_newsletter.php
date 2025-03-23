<?php
	if(!empty($agencias)){
		foreach($agencias as $agencia){
?>
	
		<div <?php echo (!empty($agencia->email)) ? ('id="agencia_' . $agencia->id_agencia . '"') : ''; ?>
				style="padding: 5px; background-color: white; display: table; border: 1px solid white; width: 97%;
				<?php echo (!empty($agencia->email)) ? 'cursor: pointer;' : ''; ?>" 
				<?php echo (!empty($agencia->email)) ? ('onclick="seleccionarAgencia(\'ckagencia_' . $agencia->id_agencia . '\', \'agencia_' . $agencia->id_agencia . '\');"') : ''; ?>
				<?php echo (empty($agencia->email)) ? ('title="No tiene email asociado"') : ''; ?>>
			<div <?php echo (!empty($agencia->email)) ? 'id="agenciaAnyadido"' : ''; ?>
					style="display: table-cell; height: 20px; padding: 0 10px; text-align: left; width: 100%;">
<?php
			if(!empty($agencia->email)){
?>
				<input type="checkbox" id="ckagencia_<?php echo $agencia->id_agencia;?>" name="ckagencia" value="<?php echo $agencia->id_agencia;?>" style="display: none;" />
<?php
			}
?>
				<label <?php echo (!empty($agencia->email)) ? 'style="cursor:pointer;"' : 'style="color: lightgray;"'; ?>>
					<?php echo $agencia->nombre;?>
				</label>
			</div>
		</div>
<?php
		}
		
		if($num_agencias > count($agencias)){
?>
		<div style="padding: 5px; background-color: white; display: table; border: 1px solid white; width: 97%;" >
			<div style="display: table-cell; height: 20px; padding: 0 10px; text-align: left; width: 100%; text-align: center;">
				<label style="color: gray;">
					+ <?php echo ($num_agencias - count($agencias)); ?> agencias
				</label>
			</div>
		</div>
<?php
		}
	}
?>
