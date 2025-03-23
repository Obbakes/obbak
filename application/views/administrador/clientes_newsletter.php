<?php
	if(!empty($clientes)){
		foreach($clientes as $cliente){
?>
	
		<div <?php echo (!empty($cliente->email)) ? ('id="cliente_' . $cliente->id_cliente . '"') : ''; ?>
				style="padding: 5px; background-color: white; display: table; border: 1px solid white; width: 97%;
				<?php echo (!empty($cliente->email)) ? 'cursor: pointer;' : ''; ?>" 
				<?php echo (!empty($cliente->email)) ? ('onclick="seleccionarCliente(\'ckcliente_' . $cliente->id_cliente . '\', \'cliente_' . $cliente->id_cliente . '\');"') : ''; ?>
				<?php echo (empty($cliente->email)) ? ('title="No tiene email asociado"') : ''; ?>>
			<div <?php echo (!empty($cliente->email)) ? 'id="clienteAnyadido"' : ''; ?>
					style="display: table-cell; height: 20px; padding: 0 10px; text-align: left; width: 100%;">
<?php
			if(!empty($cliente->email)){
?>
				<input type="checkbox" id="ckcliente_<?php echo $cliente->id_cliente;?>" name="ckcliente" value="<?php echo $cliente->id_cliente;?>" style="display: none;" />
<?php
			}
?>
				<label <?php echo (!empty($cliente->email)) ? 'style="cursor:pointer;"' : 'style="color: lightgray;"'; ?>>
					<?php echo $cliente->nombre;?>
				</label>
			</div>
		</div>
<?php
		}
		
		if($num_clientes > count($clientes)){
?>
		<div style="padding: 5px; background-color: white; display: table; border: 1px solid white; width: 97%;" >
			<div style="display: table-cell; height: 20px; padding: 0 10px; text-align: left; width: 100%; text-align: center;">
				<label style="color: gray;">
					+ <?php echo ($num_clientes - count($clientes)); ?> anunciantes
				</label>
			</div>
		</div>
<?php
		}
	}
?>
