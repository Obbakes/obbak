<script type="text/javascript">
		$('.popUpDiv.Clientes .popUpClose.d').click(function(event) {
			$('#div_clientes').hide();
			$('#lista_clientes').html('');
		});
</script>
<div class="popUpDiv Clientes">
	<span class="popUpClose d">x</span>
	<div>
		<div class="infoDetalle">
			<ul>
				<li>
					<p class="tituloOferta">Clientes</p>
				</li>
<?php
	if(!empty($clientes)){
		foreach($clientes as $cliente){
?>
				<li>
					<p><?php echo $cliente->nombre; ?></p>
				</li>
<?php
		}
	}
	else{
?>
				<li>
					<p>Ninguno</p>
				</li>
<?php
	}
?>
			</ul>
		</div>
	</div>
</div>