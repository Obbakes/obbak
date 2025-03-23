<h1 class="titlePerfilA">
	<i class="icon icon-create"></i>
	Editando anunciante
</h1>
<div class="mainEdit">
	<div >
		<input type="button" class="<?php echo(($page == 'agencias/inscripciones_anunciante') ? 'tab_active' : 'tab_inactive'); ?>" value="Inscripciones"
					onclick="document.location.href='<?php echo base_url() . 'agencias/inscripcionesAnunciante/' . $cliente->id_cliente; ?>';">
	</div><div >
		<input type="button" class="<?php echo(($page == 'agencias/editar_anunciante') ? 'tab_active' : 'tab_inactive'); ?>" value="Datos"
			onclick="document.location.href='<?php echo base_url() . 'agencias/editarAnunciante/' . $cliente->id_cliente; ?>';">
	</div><div >
		<input type="button" class="<?php echo(($page == 'agencias/permisos_anunciante') ? 'tab_active' : 'tab_inactive'); ?>" value="Permisos"
			onclick="document.location.href='<?php echo base_url() . 'agencias/permisosAnunciante/' . $cliente->id_cliente; ?>';">
	</div>
</div>
