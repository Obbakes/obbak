<script type="application/javascript">
	function mostrarBotonCambio(){
		$('#botonCambios').css('display', 'block');
	}

	function realizarCambios(){
		if(!confirm('¿Quieres realizar estos cambios para las agencias?'))
			return;

		var cambios = '';

		$('.select_permiso').each(function(){
			if(cambios != '')
				cambios += ' ';

			cambios += $(this).find('select').attr('data-id') + ' ' + $(this).find('select').val();
		});

		$('#cambios').val(cambios);

		$('#form_permisos').submit();
	}
</script>
<?php $this->load->helper('form'); ?>
<h1>
	Listado de agencias
</h1>
<form accept-charset="utf-8" method="post" action="<?php echo base_url() . 'administrador/agencias/1'; ?>" >
	<div class="filtro_ofertas">
		<div>
			<span style="font-size: 16px;" >
				Estado:
			</span>
			<select id="estado" name="estado" class="contacto_form_input">
				<option value="todos" <?php echo (empty($filtro['estado']) || $filtro['estado'] == 'todos') ? 'selected="selected"' : ''; ?>>
					Todos
				</option>
				<option value="activo" <?php echo (!empty($filtro['estado']) && $filtro['estado'] == 'activo') ? 'selected="selected"' : ''; ?>>
					Aceptadas
				</option>
				<option value="pendiente" <?php echo (!empty($filtro['estado']) && $filtro['estado'] == 'pendiente') ? 'selected="selected"' : ''; ?>>
					Pendientes
				</option>
				<option value="denegado" <?php echo (!empty($filtro['estado']) && $filtro['estado'] == 'denegado') ? 'selected="selected"' : ''; ?>>
					Denegadas
				</option>
			</select>
		</div>
		<div>
			<span style="font-size: 16px;" >
				Agencia:
			</span>
			<input type="text" id="agencia" name="agencia" class="contacto_form_input" value="<?php echo (!empty($filtro['agencia'])) ? $filtro['agencia'] : ''; ?>"/>
		</div>
	</div>
	<div class="filtro_ofertas" style="width: 385px;padding-top: 10px;">
		<div class="contact_form_area" >
			<input type="submit" class="classname" value="Buscar"/>

		</div>
		<div class="contact_form_area">
			<a href="<?php echo base_url() . '/administrador/agencias/0/1'; ?>">
				<input type="button" class="classname" value="Todas"/>
			</a>
		</div>
	</div>
</form>
<div id="texto-ayuda">
	<table>
		<tr>
			<td colspan="2" style="padding: 5px; text-align: right;">
				<img title="Cerrar" style="cursor: pointer; width: 12px;" src="http://192.168.1.2/nolimitsmedia/images/tachado_checkbox.png"
						onclick="$('#texto-ayuda').fadeOut('slow');" />
			</td>
		</tr>
		<tr>
			<td style="padding: 5px;">
				<div class="cb-tristate ayuda">
					<div >
						<div>
							<img title="Pendiente" class="solicitado" style="display: inline;" src="http://192.168.1.2/nolimitsmedia/images/solicitado_checkbox.png"/>
						</div>
					</div>
				</div>
			</td>
			<td style="padding: 5px;">
				Pendiente (Se registró por medio del registro público. Aun no tiene acceso a la plataforma)
			</td>
		</tr>
		<tr>
			<td style="padding: 5px;">
				<div class="cb-tristate ayuda">
					<div >
						<div>
							<img title="Aceptada" class="visto" style="display: inline;" src="http://192.168.1.2/nolimitsmedia/images/visto_checkbox.png"/>
						</div>
					</div>
				</div>
			</td>
			<td style="padding: 5px;">
				Aceptada (No tiene acceso a la plataforma)
			</td>
		</tr>
		<tr>
			<td style="padding: 5px;">
				<div class="cb-tristate ayuda">
					<div >
						<div>
							<img title="Denegada" class="tachado" style="display: inline;" src="http://192.168.1.2/nolimitsmedia/images/tachado_checkbox.png"/>
						</div>
					</div>
				</div>
			</td>
			<td style="padding: 5px;">
				Denegada (No tiene acceso a la plataforma)
			</td>
		</tr>
		<tr>
			<td colspan="2" style="padding: 5px;">
				Después de cambiar los estados de las agencias pincha en el botón realizar cambios para que se hagan efectivos.
			</td>
		</tr>
	</table>
</div>
<div class="list_mod">
	<a href="<?php echo base_url(); ?>administrador/nuevaAgencia">
		<div class="contact_form_area" style="width: 200px;">
			<input type="button" class="classname" value="Nueva Agencia" />
		</div>
	</a>
	<div class="help" onclick="$('#texto-ayuda').fadeIn('slow');">
		<img src="<?php echo base_url(); ?>images/ayuda.png" alt="Ayuda" title="Ayuda">
	</div>
	<ul class="head_mod_lista_5">
		<li>
			Nombre
		</li>
		<li>
			Email
		</li>
		<li>
			Num Anunciantes
		</li>
		<li>
			Estado
		</li>
		<li>
			Opciones
		</li>
	</ul>
<?php
	if(!empty($agencias)){
		$i=0;

		foreach($agencias as $agencia) {
?>
	<ul class="body_mod_lista_5 <?php if ($i%2==0) echo 'item_fav';?>" style="<?php echo ($agencia->estado == 2) ? 'color: lightgray;' : ''; ?>">
		<li>
			<?php echo $agencia->nombre ?>
		</li>
		<li>
			<p><?php echo $agencia->email?></p>
		</li>
		<li>
			<?php echo $agencia->num_anunciantes ?>
		</li>
		<li class="select_permiso">
			<select name="permiso" class="contacto_form_input" data-id="<?php echo $agencia->id_usuario; ?>" autocomplete="off" onchange="mostrarBotonCambio();">
				<option value="0" <?php echo ($agencia->estado == 0) ? 'selected="selected"' : ''; ?>>
					Aceptada
				</option>
<?php
			if($agencia->estado == 1){
?>
				<option value="1" selected="selected">
					Pendiente
				</option>
<?php
			}
?>
				<option value="2" <?php echo ($agencia->estado == 2) ? 'selected="selected"' : ''; ?>>
					Denegada
				</option>
			</select>
		</li>
		<li>
			<a style="text-decoration: none;" href="<?php echo base_url().'administrador/editarAgencia/' . $agencia->id_agencia; ?>">
				<img src="<?php echo base_url(); ?>images/icono_editar.png" title="Editar" height="30px" />
			</a>
		</li>
	</ul>
<?php
			$i++;
		}
	}
	else{
?>
	<div>
		<p>
			No existen agencias.
		</p>
	</div>
<?php
	}
?>
</div>
<a onclick="realizarCambios();" id="botonCambios" style="text-decoration: none; display: none; width: 200px; float: right;">
	<div class="contact_form_area" style="width: 200px;">
		<input type="button" class="classname" value="Realizar cambios" />
	</div>
</a>
<div class="paginacion">
	<?php echo $paginacion; ?>
</div>
<form id="form_permisos" action="<?php echo base_url() .'administrador/cambiarAccesoAgencias'; ?>"
		method="post" style="display: none;">
	<input type="hidden" name="cambios" id="cambios" value=""/>
	<input type="hidden" name="pagina" id="pagina" value="<?php echo $filtro['pagina']; ?>"/>
</form>