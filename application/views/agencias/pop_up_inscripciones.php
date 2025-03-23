<span class="popUpClose" onclick="cerrarPop();">x</span>
<h2>
	Inscribir anunciantes
</h2>
<br />
<div class="filtro_ofertas contentgrid" style="max-width: 500px;width:100%; margin: auto;">
	<div clasS="grid-8">
		<span style="font-size: 16px;" >
			Estado:
		</span>
		<select id="estado" name="estado" class="contacto_form_input">
			<option value="todos" <?php echo (!isset($filtro['estado']) || $filtro['estado'] == 'todos') ? 'selected="selected"' : ''; ?>>
				Todos
			</option>
			<option value="0" <?php echo (isset($filtro['estado']) && $filtro['estado'] == '0') ? 'selected="selected"' : ''; ?>>
				No inscritos
			</option>
			<option value="1" <?php echo (isset($filtro['estado']) && $filtro['estado'] == '1') ? 'selected="selected"' : ''; ?>>
				Inscritos
			</option>
		</select>
	</div>
	<div class="filtro_ofertas grid-4">
		<div class="contact_form_area" >
			<input type="button" onclick="obtener_inscripciones_oferta(1, 1, <?php echo $filtro['oferta']; ?>);" value="Buscar"/>
		</div>
		<div class="contact_form_area">
			<input type="button" onclick="obtener_inscripciones_oferta(0, 1, <?php echo $filtro['oferta']; ?>);" value="Todos"/>
		</div>
	</div>
</div>
<div class="filtro_ofertas" style="width: 385px; padding-top: 10px; float: right;">
	<div class="contact_form_area" >
		<input type="button" onclick="selectAll();" style="cursor: pointer" value="Seleccionar todos"/>
	</div>
	<div class="contact_form_area">
		<input type="button" onclick="unselectAll();" style="cursor: pointer" value="Deseleccionar todos"/>
	</div>
</div>
<div class="list_mod" class="tab_fav_his" style="margin-top: 65px;">
	<ul class="head_mod_lista_2">
		<li>
			Anunciante
		</li>
		<li>
			Inscribir
		</li>
	</ul>
	<div class="limitHeight">
<?php
	if(!empty($anunciantes)){
		$i=0;

		foreach($anunciantes as $anunciante) {
?>
	<ul class="body_mod_lista_2 <?php if ($i%2==0) echo 'item_fav';?>">
		<li>
			<?php echo $anunciante->nombre ?>
		</li>
		<li class="select_permiso">
<?php
			if($anunciante->inscrito == 0){
?>
			<input type="checkbox" name="permiso" value="<?php echo $anunciante->id_cliente; ?>" autocomplete="off" onclick="checkInput(this);"/>
<?php
			}
			else{
?>
			Inscrito
<?php
			}
?>
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
</div>
<div style="width: 800px; margin: auto;">
	<div class="contact_form_area" id="botonCambios" style="width: 200px; float: right; display: none;"
			onclick="inscribir_clientes_oferta(1, <?php echo $filtro['pagina']; ?>, <?php echo $filtro['oferta']; ?>);">
		<input type="button" class="classname" value="Inscribir anunciantes" />
	</div>
	<div class="paginacion">
		<?php echo $paginacion; ?>
	</div>
</div>