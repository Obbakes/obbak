<script type="text/javascript" src="<?php echo base_url();?>js/jquery-ui.js"></script>
<link rel="stylesheet" href="<?php echo base_url();?>css/jquery-ui.min.css" type="text/css" media="screen"/>
<script type="text/javascript">
	function mostrarOfertas(id){
		$('tr#tr_ofertas_' + id).css('display', 'block');
	}
</script>
<?php $this->load->helper('form'); ?>
<div>
	<div style="position: relative; width: 360px; display:inline-table; margin-bottom: 5px; text-align: left; right: 20px;">
		<h2 style="text-align: center;">
			Inscripciones Anunciantes
		</h2>
		<form accept-charset="utf-8" method="post" action="<?php echo base_url() . 'agencias/inscripcionesAnunciantes/1'; ?>"
				style="text-align: center; margin: 20px;">
			<span style="font-size: 16px;" >
				Estado:
			</span>
			<select id="estado" name="estado" style="width:80px; margin-bottom: 10px; color: rgb(0, 0, 0); font-size: 14px; line-height: 24px; overflow: hidden; padding: 3px; text-decoration: none; white-space: nowrap; width: 140px; -moz-appearance: none; background-color: rgb(255, 255, 255); font-weight: bold; background-image: linear-gradient(rgb(255, 255, 255) 20%, rgb(246, 246, 246) 50%, rgb(238, 238, 238) 52%, rgb(244, 244, 244) 100%); border-radius: 5px; background-clip: padding-box; border: 1px solid rgb(216, 216, 216); box-shadow: 0px 0px 3px rgb(255, 255, 255) inset, 0px 1px 1px rgba(0, 0, 0, 0.1); margin-right: 20px;">
				<option value="todos" <?php echo (!isset($filtro['estado']) || $filtro['estado'] == 'todos') ? 'selected="selected"' : ''; ?>>
					Todos
				</option>
				<option value="0" <?php echo (isset($filtro['estado']) && $filtro['estado'] == '0') ? 'selected="selected"' : ''; ?>>
					Pendiente
				</option>
				<option value="1" <?php echo (isset($filtro['estado']) && $filtro['estado'] == '1') ? 'selected="selected"' : ''; ?>>
					Autorizada
				</option>
				<option value="2" <?php echo (isset($filtro['estado']) && $filtro['estado'] == '2') ? 'selected="selected"' : ''; ?>>
					Pagada
				</option>
				<option value="3" <?php echo (isset($filtro['estado']) && $filtro['estado'] == '3') ? 'selected="selected"' : ''; ?>>
					Cancelada
				</option>
			</select>
			<button class="botonBuscarTodos" type="submit" style="width:85px;" >
				Buscar
			</button>
		</form>
		<div style="margin-top: 20px;">
			<table style="width:960px;" class="tab_fav_his">
				<tr class="tr_fav">
					<td class="td_pdtes" style="border-top-left-radius: 10px;background-color: rgb(84, 84, 84);">
						Cliente
					</td>
					<td style="background-color: rgb(84, 84, 84);"class="td_pdtes">
						Fecha inscripción
					</td>
					<td class="td_pdtes" style="background-color: rgb(84, 84, 84);">
						Estado
					</td>
				</tr>
<?php
	if(!empty($inscripciones)){
		foreach($inscripciones as $inscripcion) {
?>
				<tr class="item_fav">
					<td style="padding: 5px 0;cursor:pointer;" onclick="mostrarOfertas(<?php echo $inscripcion->id_cliente; ?>)">
						<?php echo $inscripcion->nombre; ?>
					</td>
					<?php foreach($inscripcion->ofertas as $oferta){?>
					<tr id="tr_ofertas_<?php echo $inscripcion->id_cliente; ?>" style="display:none;">
						<td style="padding: 5px 0;">
							<?php echo $oferta->titulo ?>
						</td>
						<td style="padding: 5px 0;">
							<?php echo $oferta->fecha ?>
						</td>
						<td style="padding: 5px 0;">
							<?php echo ($oferta->estado == 0) ? 'Pendiente' : (($oferta->estado == 1) ? 'Autorizada' : (($oferta->estado == 1) ? 'Pagada' : 'Cancelada')); ?>
						</td>
					</tr>
					<?php }?>
				</tr>
<?php
		}
	}
	else{
?>
				<tr>
					<td>
						No existen inscripciones.
					</td>
				</tr>
<?php
	}
?>
			</table>
			<div class="paginacion">
				<?php echo (!empty($paginacion)) ? $paginacion : ''; ?>
			</div>
		</div>
	</div>
</div>