<script type="text/javascript" src="<?php echo base_url() .'js/utilTablas.js'; ?>">
<script type="text/javascript">

        var cambios = '';
    
	function mostrarBotonCambio(select){
		$('#botonCambios').css('display', 'block');
		$('#botonNotificar').css('display', 'block');
                
                if(cambios != ''){
                    cambios += ' ';
                }
                
                cambios += $(select).attr('data-id') + ' ' + $(select).val();
                
                $('#cambios').val(cambios);
                
	}
        
	function clickCheckeo(checkbox){
		
                if (checkbox.checked) {
                    document.getElementById("notificacion").value = "1";
                } else {
                    document.getElementById("notificacion").value = '0';
                }
                
	}
        
        

	function realizarCambios(){
		if(!confirm('¿Quieres realizar estos cambios para los anunciantes?'))
			return;
                
                /**
                 * He hecho que cada vez que se hace un cambio en el on_change del select se agregue
                 * sólamente ese cambio a la variable que se envía por el formulario al dar al botón
                 * de "Realizar Cambios"
                 */
		//var cambios = '';

		/*$('.select_permiso').each(function(){
			if(cambios != '')
				cambios += ' ';

			cambios += $(this).find('select').attr('data-id') + ' ' + $(this).find('select').val();
		});*/

		//$('#cambios').val(cambios);

		$('#form_permisos').submit();
	}
</script>
<?php $this->load->helper('form'); ?>
<h1>
	Listado de Anunciantes ( <?php echo $total_clientes; ?> clientes)
</h1>
<form id="formAnunciantes" accept-charset="utf-8" method="post" action="<?php echo base_url() . 'administrador/anunciantes/1'; ?>" >
	
	<input type="hidden" id="order_by_campo" name="order_by_campo" value="<?php if (isset($filtro['order_by_campo'])) { echo $filtro['order_by_campo'];}?>" ></input>
	<input type="hidden" id="order_by_sentido" name="order_by_sentido" value="<?php if (isset($filtro['order_by_sentido'])) {echo $filtro['order_by_sentido'];}?>"> </input>
	
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
					Aceptados
				</option>
				<option value="pendiente" <?php echo (!empty($filtro['estado']) && $filtro['estado'] == 'pendiente') ? 'selected="selected"' : ''; ?>>
					Pendientes
				</option>
				<option value="denegado" <?php echo (!empty($filtro['estado']) && $filtro['estado'] == 'denegado') ? 'selected="selected"' : ''; ?>>
					Denegados
				</option>
				<option value="denegado" <?php echo (!empty($filtro['estado']) && $filtro['estado'] == 'desactivado') ? 'selected="selected"' : ''; ?>>
					Desactivados
				</option>
			</select>
		</div>
		<div>
			<span style="font-size: 16px;" >
				Estado Permisos:
			</span>
			<select id="permisos" name="permisos" class="contacto_form_input">
				<option value="todos" <?php echo (empty($filtro['permisos']) || $filtro['permisos'] == 'todos') ? 'selected="selected"' : ''; ?>>
					Todos
				</option>
				<option value="pendiente" <?php echo (!empty($filtro['permisos']) && $filtro['permisos'] == 'pendiente') ? 'selected="selected"' : ''; ?>>
					Pendientes
				</option>
				<option value="no pendiente" <?php echo (!empty($filtro['permisos']) && $filtro['permisos'] == 'no pendiente') ? 'selected="selected"' : ''; ?>>
					Sin pendientes
				</option>
			</select>
		</div>
		<?php /*
                <div>
			<span style="font-size: 16px;" >
				Agencia:
			</span>
			<select id="agencia" name="agencia" class="contacto_form_input">
				<option value="0" <?php echo (empty($filtro['agencia']) || $filtro['agencia'] == '0') ? 'selected="selected"' : ''; ?>>
					Todas
				</option>
<?php
	if(!empty($agencias)){
		foreach($agencias as $agencia){
?>
				<option value="<?php echo $agencia->id_agencia; ?>" <?php echo (!empty($filtro['agencia']) && $filtro['agencia'] == $agencia->id_agencia) ? 'selected="selected"' : ''; ?>>
					<?php echo $agencia->nombre; ?>
				</option>
<?php
		}
	}
?>
			</select>
		</div>*/ ?>
		<div>
			<span style="font-size: 16px;" >
				Sector:
			</span>
			<select id="sector" name="sector" class="contacto_form_input">
				<option value="0" <?php echo (empty($filtro['sector']) || $filtro['sector'] == '0') ? 'selected="selected"' : ''; ?>>
					Todos
				</option>
<?php
	if(!empty($sectores)){
		foreach($sectores as $sector){
?>
				<option value="<?php echo $sector->id_sector; ?>" <?php echo (!empty($filtro['sector']) && $filtro['sector'] == $sector->id_sector) ? 'selected="selected"' : ''; ?>>
					<?php echo $sector->sector; ?>
				</option>
<?php
		}
	}
?>
			</select>
		</div>
		<div>
			<span style="font-size: 16px;" >
				Nombre/Email:
			</span>
			<input type="text" id="anunciante" name="anunciante" class="contacto_form_input" value="<?php echo (!empty($filtro['anunciante'])) ? $filtro['anunciante'] : ''; ?>"/>
		</div>
	</div>
	<div class="filtro_ofertas" style="width: 385px; padding-top: 10px;">
		<div class="contact_form_area" >
			<input type="submit" class="classname" value="Buscar"/>
		</div>
		<div class="contact_form_area">
			<a href="<?php echo base_url() . 'administrador/anunciantes/0/1'; ?>">
				<input type="button" class="classname" value="Todas"/>
			</a>
		</div>
	</div>
</form>
<div id="texto-ayuda">
	<table>
		<tr>
			<td colspan="2" style="padding: 5px; text-align: right;">
				<img title="Cerrar" style="cursor: pointer; width: 12px;" src="<?php echo base_url() . 'images/tachado_checkbox.png'; ?>"
						onclick="$('#texto-ayuda').fadeOut('slow');" />
			</td>
		</tr>
		<tr>
			<td style="padding: 5px;">
			</td>
			<td style="padding: 5px;">
				Pendiente (Se registró por medio del registro público. Aun no tiene acceso a la plataforma)
			</td>
		</tr>
		<tr>
			<td style="padding: 5px;">
			</td>
			<td style="padding: 5px;">
				Aceptada (No tiene acceso a la plataforma)
			</td>
		</tr>
		<tr>
			<td style="padding: 5px;">
			</td>
			<td style="padding: 5px;">
				Denegada (No tiene acceso a la plataforma)
			</td>
		</tr>
		<tr>
			<td style="padding: 5px;">
			</td>
			<td style="padding: 5px;">
				Desactivada (Baja en la plataforma)
			</td>
		</tr>
		<tr>
			<td colspan="2" style="padding: 5px;">
				Después de cambiar los estados de los anunciantes pincha en el botón realizar cambios para que se hagan efectivos.
			</td>
		</tr>
	</table>
</div>
<div class="list_mod">
	<a href="<?php echo base_url(); ?>administrador/nuevoAnunciante" >
		<div class="contact_form_area" style="width: 200px;">
			<input type="button" class="classname" value="Nuevo Anunciante" />
		</div>
	</a>
	<div class="help" onclick="$('#texto-ayuda').fadeIn('slow');">
		<img src="<?php echo base_url(); ?>images/ayuda.png" alt="Ayuda" title="Ayuda">
	</div>
	<ul class="head_mod_lista_7">
		<li style="width:7%;">
			<a class="cabeceraOrden" id="cab_id_cliente" href="#">ID</a>
		</li>
		<li style="width:17%;">
			<a class="cabeceraOrden" id="cab_nombre" href="#">Empresa</a>
		</li>
		<li style="width:19%;">
			<a class="cabeceraOrden" id="cab_email" href="#">Email</a>
		</li>
		<li style="width:11%;">
			<a class="cabeceraOrden" id="cab_fecha_registro" href="#">Fecha de registro</a>
		</li>
		<li style="width:14%;">
			<a class="cabeceraOrden" id="cab_nombre_agencia" href="#">Agencia</a>
		</li>
		<li style="width:11%;">
			<a class="cabeceraOrden" id="cab_cif" href="#">CIF</a>
		</li>
		<li style="width:13%;">
			<a class="cabeceraOrden" id="cab_estado" href="#">Estado</a>
		</li>
		<li style="width:8%;">
			Opciones
		</li>
	</ul>
<?php
	if(!empty($clientes)){
		$i=0;

		foreach($clientes as $cliente) {
?>
	<ul class="body_mod_lista_7 <?php if ($i%2==0) echo 'item_fav';?>">
		<li style="width:7%;">
			<p title="<?php echo $cliente->id_cliente ?>"><?php echo $cliente->id_cliente ?></p>
		</li>
		<li style="width:17%;">
			<p title="<?php echo $cliente->nombre ?>"><?php echo $cliente->nombre ?></p>
		</li>
		<li style="width:19%;" >
			<p title="<?php echo $cliente->email; ?>"><?php echo $cliente->email; ?></p>
		</li>
		<li style="width:11%;">
			<?php echo $cliente->fecha_registro; ?>
		</li>
		<li style="width:14%;"  >
			<p title="<?php echo $cliente->nombre_agencia; ?>"><?php echo $cliente->nombre_agencia; ?></p>
		</li>
		<li style="width:11%;">
			<p><?php echo $cliente->cif; ?></p>
		</li>
		<li style="width:13%;" class="select_permiso">
			<select name="permiso" class="contacto_form_input" data-id="<?php echo $cliente->id_usuario; ?>" autocomplete="off" onchange="mostrarBotonCambio(this);">
				<option value="0" <?php echo ($cliente->estado == 0) ? 'selected="selected"' : ''; ?>>
					Aceptado
				</option>
				<option value="1" <?php echo ($cliente->estado == 1) ? 'selected="selected"' : ''; ?>>
					Pendiente
				</option>
				<option value="2" <?php echo ($cliente->estado == 2) ? 'selected="selected"' : ''; ?>>
					Denegado
				</option>
				<option value="3" <?php echo ($cliente->estado == 3) ? 'selected="selected"' : ''; ?>>
					Desactivado
				</option>
			</select>
		</li>
		<li style="width:8%;">
			<a href="<?php echo base_url().'administrador/permisosAnunciante/' . $cliente->id_cliente; ?>">
				<img src="<?php echo base_url(); ?>images/acceso.png" title="Permisos" height="30px" />
			</a>
			<a href="<?php echo base_url().'administrador/editarAnunciante/' . $cliente->id_cliente; ?>">
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
			No existen anunciantes.
		</p>
	</div>
<?php
	}
?>
</div>
<p id="botonNotificar" style="text-decoration: none; display: none; width: 200px; float: right;">
    <label class="oferta_label" for="publicada">
            ¿Notificar Cambio por email?
    </label>
    <input onclick="clickCheckeo(this);" type="checkbox" id="notificar" name="notificar" value="1" <?php echo (!empty($validado)) ? set_checkbox('notificacion', 1) : ''; ?> style="width: auto;"/>
</p>
<a onclick="realizarCambios();" id="botonCambios" style="text-decoration: none; display: none; width: 200px; float: right;">
	<div class="contact_form_area" style="width: 200px;">
		<input type="button" class="classname" value="Realizar cambios" />
	</div>
</a>
<div class="paginacion">
	<?php echo $paginacion; ?>&nbsp;&nbsp;<a href="<?php echo base_url();?>administrador/anunciantes/2/1/400">Mostrar Todos</a>
</div>
<form id="form_permisos" action="<?php echo base_url() .'administrador/cambiarAccesoAnunciantes'; ?>"
		method="post" style="display: none;">
	<input type="hidden" name="cambios" id="cambios" value=""/>
	<input type="hidden" name="notificacion" id="notificacion" value="0"/>
	<input type="hidden" name="pagina" id="pagina" value="<?php echo $filtro['pagina']; ?>"/>
</form>