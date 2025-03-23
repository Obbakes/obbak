<?php $this->load->helper('form'); ?>
<div>
	<div style="position: relative; width: 360px; display:inline-table; margin-bottom: 5px; text-align: left; right: 20px;">
		<h3 style="text-align: center;">
			Editar Medio (<?php echo $medio->nombre; ?>)
		</h3>
		<div style="display: table; margin: auto; width: 50%; padding: 0;">
			<div style="display: table-cell; width: 50%; text-align: center;">
				<input type="button" class="boton-pestanya-activo" value="Datos" style="width: 90%;"
					onclick="document.location.href='<?php echo base_url() . 'administrador/editarMedio/' . $medio->id_medio; ?>';">
			</div>			
                        <div style="display: table-cell; width: 50%; text-align: center;">
				<input type="button" class="boton-pestanya-activo" value="Permisos" style="width: 90%;"
					onclick="document.location.href='<?php echo base_url() . 'administrador/permisosMedio/' . $medio->id_medio; ?>';">
			</div>
                        <div style="display: table-cell; width: 50%; text-align: center;">
				<input type="button" class="boton-pestanya-inactivo" value="Logs" disabled="disabled" style="width: 90%;">
			</div>
		</div>
                <div style="display: table; margin: auto; width: 50%; padding: 0;" class="actionGroup">
                        <p style="display: table-cell; width: 30%; text-align: center;">
                            Estadísticas Permisos <br/>(Inactivos inc.):
                        </p>
			<div style="display: table-cell; width: 20%; text-align: center;">
                            <p>
                                Porcentaje Aprobado:<br/><?php
                                                    echo round($permisos[0]->aceptados*100/$permisos[0]->permisos,0).' %';
                                ?>
                            </p>
			</div>			
                        <div style="display: table-cell; width: 20%; text-align: center;">
                            <p>
                                Porcentaje Rechazado:<br/> <?php
                                                    echo round($permisos[0]->rechazados*100/$permisos[0]->permisos,0).' %';
                                ?>
                            </p>
			</div>
                        <div style="display: table-cell; width: 20%; text-align: center;">
                            <p>
                                Porcentaje Pendiente:<br/> <?php
                                                    echo round($permisos[0]->pendientes*100/$permisos[0]->permisos,0).' %';
                                ?>
                            </p>
			</div>
		</div>
		<?php /*<form accept-charset="utf-8" method="post" action="<?php echo base_url() . 'administrador/logsMedio/' . $medio->id_medio . '/1'; ?>" >
			<div class="filtro_ofertas">
				<div>
					<span style="font-size: 16px;" >
						Estado:
					</span>
					<select id="estado" name="estado" class="contacto_form_input">
						<option value="todos" <?php echo (!isset($filtro['estado']) || $filtro['estado'] == 'todos') ? 'selected="selected"' : ''; ?>>
							Todos
						</option>
						<option value="0" <?php echo (isset($filtro['estado']) && $filtro['estado'] == '0') ? 'selected="selected"' : ''; ?>>
							Solicitados
						</option>
						<option value="1" <?php echo (isset($filtro['estado']) && $filtro['estado'] == '1') ? 'selected="selected"' : ''; ?>>
							Autorizado
						</option>
						<option value="2" <?php echo (isset($filtro['estado']) && $filtro['estado'] == '2') ? 'selected="selected"' : ''; ?>>
							NO Autorizado
						</option>
						<option value="3" <?php echo (isset($filtro['estado']) && $filtro['estado'] == '3') ? 'selected="selected"' : ''; ?>>
							Pendiente Autorizar
						</option>
					</select>
				</div>
				<div>
					<span style="font-size: 16px;" >
						Provincia:
					</span>
					<select id="provincia" name="provincia" class="contacto_form_input">
                                                <option value="todas" <?php echo (!isset($filtro['provincia']) || $filtro['provincia'] == 'todas') ? 'selected="selected"' : ''; ?>>
							Todas
						</option>
<?php
	if(!empty($provincias)){
		foreach($provincias as $provincia){
?>
						<option value="<?php echo $provincia->id_provincia ?>" <?php echo (isset($filtro['provincia']) && $filtro['provincia'] == $provincia->id_provincia) ? 'selected="selected"' : ''; ?>>
						<?php echo $provincia->provincia ?>
					</option>
<?php
		}
	}
?>                                      </select>
				</div>
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
						<option value="<?php echo $agencia->id_agencia; ?>"
								<?php echo (!empty($filtro['agencia']) && $filtro['agencia'] == $agencia->id_agencia) ? 'selected="selected"' : ''; ?>>
							<?php echo $agencia->nombre; ?>
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
			<div class="filtro_ofertas" style="width: 385px; padding-top: 10px; margin: auto;">
				<div class="contact_form_area" >
					<input type="submit" class="classname" value="Buscar"/>
				</div>
				<div class="contact_form_area">
					<a href="<?php echo base_url().'administrador/permisosMedio/' . $medio->id_medio; ?>">
						<input type="button" class="classname" value="Todas"/>
					</a>
				</div>
			</div>
		</form>
	<div id="texto-ayuda">
		<table>
			<tr>
				<td colspan="2" style="padding: 5px; text-align: right;">
					<img title="Cerrar" style="cursor: pointer; width: 12px;" src="<?php echo base_url();?>images/tachado_checkbox.png"
							onclick="$('#texto-ayuda').fadeOut('slow');" />
				</td>
			</tr>
			<tr>
				<td style="padding: 5px;">
				</td>
				<td style="padding: 5px;">
					Solicitado (La agencia a la que pertence el anunciante ha solicitado este permiso, pero aun no lo tiene)
				</td>
			</tr>
			<tr>
				<td style="padding: 5px;">
				</td>
				<td style="padding: 5px;">
					Autorizado
				</td>
			</tr>
			<tr>
				<td style="padding: 5px;">
				</td>
				<td style="padding: 5px;">
					NO Autorizado
				</td>
			</tr>
			<tr>
				<td style="padding: 5px;">
				</td>
				<td style="padding: 5px;">
					Pendiente Autorizar
				</td>
			</tr>
			<tr>
				<td colspan="2" style="padding: 5px;">
					Después de cambiar los permisos de los anunciantes pincha en el botón realizar cambios para que se hagan efectivos.
				</td>
			</tr>
		</table>
	</div>
	<div class="help" onclick="$('#texto-ayuda').fadeIn('slow');">
		<img src="<?php echo base_url(); ?>images/ayuda.png" alt="Ayuda" title="Ayuda">
	</div>*/?>
		<div style="margin-top: 20px;">
			<div class="actionGroup">                         
				Permisos: 0 pendiente, 1 aceptado, 2 denegado, 3 sin asignar
                                
			</div>
			<table style="width:998px;" class="tab_fav_his">
				<tr class="tr_fav">
					<td class="td_pdtes" style="border-top-left-radius: 10px;background-color: rgb(84, 84, 84);">
						Id Log
					</td>
					<td style="background-color: rgb(84, 84, 84);"class="td_pdtes">
						Fecha
					</td>
                                        <td style="background-color: rgb(84, 84, 84);"class="td_pdtes">
						Acción
					</td>					
				</tr>
<?php
	if(!empty($acciones)){
		$i=0;

		foreach($acciones as $accion) {
?>
				<tr <?php if ($i%2==0) echo 'class="item_fav"';?>>
					<td>
						<?php echo $accion->id_log ?>
					</td>
					<td>
						<?php echo $accion->fecha ?>
					</td>
                                        <td>
						<?php
                                                // decode the JSON data
                                                $result = json_decode($accion->accion);
                                                if (!$result) {
                                                    echo $accion->accion;
                                                }else{
                                                    echo 'Permiso '.$result[0]->permiso.' al Cliente <a href="'.base_url().'administrador/editarAnunciante/'.$result[0]->id_cliente.'">'.$result[0]->id_cliente.'</a>';
                                                }
                                                
                                                 ?>
					</td>					
				</tr>
<?php
			$i++;
		}
	}
	else{
?>
				<tr>
					<td>
						No existen registros.
					</td>
				</tr>
<?php
	}
?>
			</table>			
			<div class="paginacion">
				<?php echo $paginacion; ?>&nbsp;&nbsp;<a href="<?php echo base_url().'administrador/logsMedio/'.$medio->id_medio.'/2/1/400'?>">Mostrar Todos</a>
			</div>
		</div>		
	</div>
</div>
