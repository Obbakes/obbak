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
		if(!confirm('¿Quieres realizar estos cambios para los gestores?'))
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
	Listado de Gestores de Medios ( <?php echo $total_gestores; ?> gestores)
</h1>
<form accept-charset="utf-8" method="post" action="<?php echo base_url() . 'administrador/gestorMedios/1'; ?>" >
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
				Nombre:
			</span>
			<input type="text" id="nombre" name="nombre" class="contacto_form_input" value="<?php echo (!empty($filtro['nombre'])) ? $filtro['nombre'] : ''; ?>"/>
		</div>
	</div>
	<div class="filtro_ofertas" style="width: 385px; padding-top: 10px;">
		<div class="contact_form_area" >
			<input type="submit" class="classname" value="Buscar"/>
		</div>
		<div class="contact_form_area">
			<a href="<?php echo base_url() . 'administrador/gestorMedios/0/1'; ?>">
				<input type="button" class="classname" value="Todos"/>
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
	<a href="<?php echo base_url(); ?>administrador/nuevoGestorMedio" >
		<div class="contact_form_area" style="width: 200px;">
			<input type="button" class="classname" value="Nuevo Gestor" />
		</div>
	</a>
	<div class="help" onclick="$('#texto-ayuda').fadeIn('slow');">
		<img src="<?php echo base_url(); ?>images/ayuda.png" alt="Ayuda" title="Ayuda">
	</div>
	<ul class="head_mod_lista_7">
		<li style="width:7%;">
			ID
		</li>
		<li style="width:17%;">
			Nombre
		</li>
		<li style="width:17%;">
			Nick
		</li>
		<li style="width:19%;">
			Email
		</li>
		<li style="width:11%;">
			Fecha de registro
		</li>
		<li style="width:13%;">
			Estado
		</li>
                <li>
			Num Medios
		</li>
		<li style="width:8%;">
			Opciones
		</li>
	</ul>
<?php
	if(!empty($gestores)){
		$i=0;

		foreach($gestores as $gestor) {
?>
	<ul class="body_mod_lista_7 <?php if ($i%2==0) echo 'item_fav';?>">
		<li style="width:7%;">
			<p title="<?php echo $gestor->id_usuario ?>"><?php echo $gestor->id_usuario ?></p>
		</li>
		<li style="width:17%;">
			<p title="<?php echo $gestor->nombre ?>"><?php echo $gestor->nombre ?></p>
		</li>
		<li style="width:17%;">
			<p title="<?php echo $gestor->nick ?>"><?php echo $gestor->nick ?></p>
		</li>
		<li style="width:19%;" >
			<p title="<?php echo $gestor->email; ?>"><?php echo $gestor->email; ?></p>
		</li>
		<li style="width:11%;">
			<?php echo $gestor->fecha_registro; ?>
		</li>		
		<li style="width:13%;" class="select_permiso">
			<select name="permiso" class="contacto_form_input" data-id="<?php echo $gestor->id_usuario; ?>" autocomplete="off" onchange="mostrarBotonCambio(this);">
				<option value="0" <?php echo ($gestor->estado == 0) ? 'selected="selected"' : ''; ?>>
					Aceptado
				</option>
				<option value="1" <?php echo ($gestor->estado == 1) ? 'selected="selected"' : ''; ?>>
					Pendiente
				</option>
				<option value="2" <?php echo ($gestor->estado == 2) ? 'selected="selected"' : ''; ?>>
					Denegado
				</option>
				<option value="3" <?php echo ($gestor->estado == 3) ? 'selected="selected"' : ''; ?>>
					Desactivado
				</option>
			</select>
		</li>
                <li onclick="document.location.href='<?php echo base_url() . 'administrador/permisosGestorMedio/' . $gestor->id_gestor; ?>';"style="cursor: pointer;">
			<p><span title="<?php echo $gestor->num_medios ?> Permisos" class="permisosAnun"></span><?php echo $gestor->num_medios ?></p>
                    
		</li>
		<li style="width:8%;">
			
			<a href="<?php echo base_url().'administrador/editarGestorMedio/' . $gestor->id_gestor; ?>">
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
	<?php echo $paginacion; ?>&nbsp;&nbsp;<a href="<?php echo base_url();?>administrador/gestorMedios/2/1/400">Mostrar Todos</a>
</div>
<form id="form_permisos" action="<?php echo base_url() .'administrador/cambiarAccesoGestorMedios'; ?>"
		method="post" style="display: none;">
	<input type="hidden" name="cambios" id="cambios" value=""/>
	<input type="hidden" name="notificacion" id="notificacion" value="0"/>
	<input type="hidden" name="pagina" id="pagina" value="<?php echo $filtro['pagina']; ?>"/>
</form>