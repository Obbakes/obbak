<?php $this->load->helper('form'); ?>
<h1>
	Listado de Anunciantes
</h1>
<form accept-charset="utf-8" method="post" action="<?php echo base_url() . 'agencias/anunciantes/1'; ?>" >
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
			</select>
		</div>
		<div>
			<span style="font-size: 16px;" >
				Anunciante:
			</span>
			<input type="text" id="anunciante" name="anunciante" class="contacto_form_input" value="<?php echo (!empty($filtro['anunciante'])) ? $filtro['anunciante'] : ''; ?>"/>
		</div>
	</div>
	<div class="filtro_ofertas" style="width: 385px;padding-top: 10px;">
		<div class="contact_form_area" >
			<input type="submit" class="classname" value="Buscar"/>

		</div>
		<div class="contact_form_area">
			<a href="<?php echo base_url() . 'agencias/anunciantes/0/1'; ?>">
				<input type="button" class="classname" value="Todas"/>
			</a>
		</div>
	</div>
</form>
<div class="list_mod">
<a href="<?php echo base_url(); ?>agencias/nuevoAnunciante" >
	<div class="contact_form_area" style="width: 200px;">
		<input type="button" class="classname" value="Nuevo Anunciante" />
	</div>
</a>
	<ul class="head_mod_lista_5">
		<li>
			Empresa
		</li>
		<li>
			Email
		</li>
		<li>
			Fecha de registro
		</li>
		<li>
			Estado
		</li>
		<li>
			Opciones
		</li>
	</ul>
<?php
	if(!empty($clientes)){
		$i=0;

		foreach($clientes as $cliente) {
?>
	<ul class="body_mod_lista_5 <?php if ($i%2==0) echo 'item_fav';?>">
		<li>
			<?php echo $cliente->nombre ?>
		</li>
		<li>
			<p class="ellipsis"><?php echo $cliente->email; ?></p>
		</li>
		<li>
			<?php echo $cliente->fecha_registro; ?>
		</li>
		<li>
			<?php echo ($cliente->estado == 0) ? 'Aceptado' : (($cliente->estado == 1) ? 'Pendiente' : 'Denegado'); ?>
		</li>
		<li>
			<a href="<?php echo base_url().'agencias/inscripcionesAnunciante/' . $cliente->id_cliente; ?>">
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
<div class="paginacion">
	<?php echo $paginacion; ?>
</div>