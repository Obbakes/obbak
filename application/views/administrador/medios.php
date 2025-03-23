<script type="text/javascript" src="<?php echo base_url() .'js/utilTablas.js'; ?>">
<script type="application/javascript">
    
	function mostrarBotonCambio(){
		$('#botonCambios').css('display', 'block');
	}

	function realizarCambios(){
		if(!confirm('¿Quieres realizar estos cambios para los medios?'))
			return;

		var cambios = '';

		$('.select_permiso').each(function(){
			if(cambios != '')
				cambios += ' ';

			cambios += $(this).find('select').attr('data-id') + ' ' + $(this).find('select').val();
		});

		$('#cambios').val(cambios);

		$('#form_alta').submit();
	}
</script>
<?php $this->load->helper('form'); ?>
<h1>
	Listado de medios ( <?php echo $total_medios; ?> medios)
</h1>
<form accept-charset="utf-8" method="post" action="<?php echo base_url() . 'administrador/medios/1'; ?>" >
	<input type="hidden" id="order_by_campo" name="order_by_campo" value="<?php if (isset($filtro['order_by_campo'])) { echo $filtro['order_by_campo'];}?>" ></input>
	<input type="hidden" id="order_by_sentido" name="order_by_sentido" value="<?php if (isset($filtro['order_by_sentido'])) {echo $filtro['order_by_sentido'];}?>"> </input>
	
	<div class="filtro_ofertas">
		<div>
			<span style="font-size: 16px;" >
				Estado:
			</span>
			<select id="estado" name="estado" class="contacto_form_input">
				<option value="0" <?php echo (empty($filtro['estado']) || $filtro['estado'] == 0) ? 'selected="selected"' : ''; ?>>
					Todos
				</option>
				<option value="1" <?php echo (!empty($filtro['estado']) && $filtro['estado'] == 1) ? 'selected="selected"' : ''; ?>>
					Dados de alta
				</option>
				<option value="2" <?php echo (!empty($filtro['estado']) && $filtro['estado'] == 2) ? 'selected="selected"' : ''; ?>>
					Dados de baja
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
		<div>
			<span style="font-size: 16px;" >
				Tipo Medio:
			</span>
			<select id="tipo_medio" name="tipo_medio" class="contacto_form_input">
				<option value="0" <?php echo (empty($filtro['tipo_medio']) || $filtro['tipo_medio'] == '0') ? 'selected="selected"' : ''; ?>>
					Todos
				</option>
<?php
	if(!empty($tipos_medios)){
		foreach($tipos_medios as $tipo_medio){
?>
				<option value="<?php echo $tipo_medio->id_tipo; ?>"
						<?php echo (!empty($filtro['tipo_medio']) && $filtro['tipo_medio'] == $tipo_medio->id_tipo) ? 'selected="selected"' : ''; ?>>
					<?php echo $tipo_medio->tipo; ?>
				</option>
<?php
		}
	}
?>
			</select>
		</div>
		<div>
			<span style="font-size: 16px;" >
				Medio:
			</span>
			<input type="text" id="medio" name="medio" class="contacto_form_input" value="<?php echo (!empty($filtro['medio'])) ? $filtro['medio'] : ''; ?>"/>
		</div>
	</div>
	<div class="filtro_ofertas" style="width: 385px;padding-top: 10px;">
		<div class="contact_form_area" >
			<input type="submit" class="classname" value="Buscar"/>

		</div>
		<div class="contact_form_area">
			<a href="<?php echo base_url() . '/administrador/medios/0/1'; ?>">
				<input type="button" class="classname" value="Todas"/>
			</a>
		</div>
	</div>
</form>
<div id="texto-ayuda">
	<table >
		<tr>
			<td colspan="2" style="padding: 5px; text-align: right;">
				<img title="Cerrar" style="cursor: pointer; width: 12px;" src="<?php echo base_url()?>images/tachado_checkbox.png"
						onclick="$('#texto-ayuda').fadeOut('slow');" />
			</td>
		</tr>
		<tr>
			<td style="padding: 5px;">
			</td>
			<td style="padding: 5px;">
				Dado de alta
			</td>
		</tr>
		<tr>
			<td style="padding: 5px;">
			</td>
			<td style="padding: 5px;">
				Dado de baja
			</td>
		</tr>
		<tr>
			<td colspan="2" style="padding: 5px;">
				Después de especificar los medios que quieres dar de alta y de baja pincha en el botón realizar cambios para que se hagan efectivos.
			</td>
		</tr>
	</table>
</div>
<div class="list_mod">
	<a href="<?php echo base_url(); ?>administrador/nuevoMedio" >
		<div class="contact_form_area" style="width: 200px;">
			<input type="button" class="classname" value="Nuevo Medio" />
		</div>
	</a>
	<div class="help" onclick="$('#texto-ayuda').fadeIn('slow');">
		<img src="<?php echo base_url(); ?>images/ayuda.png" alt="Ayuda" title="Ayuda">
	</div>
	<ul class="head_mod_lista_9">
		<li>
			<a class="cabeceraOrden" id="cab_id_medio" href="#">ID</a>
		</li>
		<li>
			<a class="cabeceraOrden" id="cab_nom_gestor" href="#">Gestor</a>
		</li>
		<li>
			<a class="cabeceraOrden" id="cab_nombre" href="#">Nombre</a>
		</li>
		<li>
			<a class="cabeceraOrden" id="cab_tipo" href="#">Tipo de Medio</a>
		</li>
		<li>
			<a class="cabeceraOrden" id="cab_logo" href="#">Logo</a>
		</li>
		<li>
			<a class="cabeceraOrden" id="cab_num_ofertas" href="#">Num Ofertas</a>
		</li>
		<li>
			<a class="cabeceraOrden" id="cab_permisos_pendientes" href="#">Permisos Pendientes</a>
		</li>
		<li>
			¿Dado de Alta?
		</li>
		<li>
			Opciones
		</li>
	</ul>
<?php
	if(!empty($medios)){
		$i=0;

		foreach($medios as $medio) {
?>
	<ul class="body_mod_lista_9 <?php if ($i%2==0) echo 'item_fav';?>" style="<?php echo (!empty($medio->fecha_baja)) ? 'color: lightgray;' : ''; ?>">
		<li>
			<?php echo $medio->id_medio ?>
		</li>
		<li>
			<?php echo $medio->nom_gestor ?>
		</li>
		<li>
			<?php echo $medio->nombre ?>
		</li>
		<li>
			<?php echo $medio->tipo ?>
		</li>
		<li>
			<img style="height:45px;" src="<?php echo(!empty($medio->logo) ? base_url() . $medio->logo : base_url() . 'news1.png');?>"/>
		</li>
		<li>
			<?php echo $medio->num_ofertas ?>
		</li>
		<li onclick="document.location.href='<?php echo base_url() . 'administrador/permisosMedio/' . $medio->id_medio; ?>';"style="cursor: pointer;">
			<p><span title="<?php echo $medio->permisos_pendientes; ?> Permisos" class="permisosAnun"></span><?php echo $medio->permisos_pendientes ?></p>
		</li>
		<li class="select_permiso">
			<select name="permiso" class="contacto_form_input" data-id="<?php echo $medio->id_medio; ?>" autocomplete="off" onchange="mostrarBotonCambio();">
				<option value="1" <?php echo (empty($medio->fecha_baja)) ? 'selected="selected"' : ''; ?>>
					Dado de alta
				</option>
				<option value="0" <?php echo (!empty($medio->fecha_baja)) ? 'selected="selected"' : ''; ?>>
					Dado de baja
				</option>
			</select>
		</li>
		<li>
			<a style="text-decoration: none;" href="<?php echo base_url().'administrador/editarMedio/'.$medio->id_medio; ?>">
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
			No existen medios.
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
	<?php echo $paginacion; ?>&nbsp;&nbsp;<a href="<?php echo base_url();?>administrador/medios/2/1/400">Mostrar Todos</a>
</div>
<form id="form_alta" action="<?php echo base_url() .'administrador/darAltaMedios'; ?>"
		method="post" style="display: none;">
	<input type="hidden" name="cambios" id="cambios" value=""/>
	<input type="hidden" name="pagina" id="pagina" value="<?php echo $filtro['pagina']; ?>"/>
</form>