<script type="text/javascript" src="<?php echo base_url();?>js/jquery-ui.js"></script>
<link rel="stylesheet" href="<?php echo base_url();?>css/jquery-ui.min.css" type="text/css" media="screen"/>
<script type="application/javascript">
	
        
	function clickCheckeo(checkbox){
		
                if (checkbox.checked) {
                    document.getElementById("newsletter").value = "1";
                } else {
                    document.getElementById("newsletter").value = '0';
                }
                
	}
        
        

	
</script>
<?php $this->load->helper('form'); ?>
<div>
	<div style="position: relative; width: 360px; display:inline-table; margin-bottom: 5px; text-align: left; right: 20px;">
		<h3 style="text-align: center;">
			Editar Anunciante
		</h3>
		<div style="display: table; margin: auto; width: 50%; padding: 0;">
			<div style="display: table-cell; width: 50%; text-align: center;">
				<input type="button" class="boton-pestanya-inactivo" value="Datos" disabled="disabled" style="width: 90%;">
			</div>
			<div style="display: table-cell; width: 50%; text-align: center;">
				<input type="button" class="boton-pestanya-activo" value="Permisos" style="width: 90%;"
					onclick="document.location.href='<?php echo base_url() . 'administrador/permisosAnunciante/' . $cliente->id_cliente; ?>';">
			</div>
                        <div style="display: table-cell; width: 100%; text-align: center;">
                            <input type="button" class="boton-pestanya-activo" value="Enviar datos de acceso" onclick="if(confirm('¿Quieres enviar los datos de acceso al cliente?')) document.location.href='<?php echo base_url() . 'administrador/enviarDatosAccesoCliente/' . $cliente->id_cliente; ?>';">
                        </div>
		</div>
		<?php echo form_open_multipart("administrador/editarAnunciante/" . $cliente->id_cliente . '/1'); ?>
			<div class="reg_form">
				<div class="first_part_reg">
					<p style="padding: 0 10px;">
						<label for="nombre">
							Empresa:
						</label>
						<input type="text" id="nombre" class="contacto_form_input <?php echo (form_error('nombre') != '') ? 'input_error' : ''; ?>" placeholder="" 
							name="nombre" value="<?php echo (!empty($validado)) ? set_value('nombre') : $cliente->nombre; ?>" style="width: 335px; height: 40px;" />
						<?php echo form_error('nombre'); ?>
					</p>
					<p style="padding: 0 10px;">
						<label for="nick">
							Nickname (usuario):
						</label>
						<input type="text" id="nick" class="contacto_form_input <?php echo (form_error('nick') != '') ? 'input_error' : ''; ?>" placeholder="" 
							name="nick" value="<?php echo (!empty($validado)) ? set_value('nick') : $cliente->nick; ?>" style="width: 335px; height: 40px;" />
						<?php echo form_error('nick'); ?>
					</p>
					<p style="padding: 0 10px;">
						<label for="nombre_contacto">
							Nombre:
						</label>
						<input type="text" id="nombre_contacto" class="contacto_form_input <?php echo (form_error('nombre_contacto') != '') ? 'input_error' : ''; ?>" placeholder="" 
							name="nombre_contacto" value="<?php echo (!empty($validado)) ? set_value('nombre_contacto') : $cliente->nombre_contacto; ?>" style="width: 335px; height: 40px;" />
						<?php echo form_error('nombre_contacto'); ?>
					</p>
					<p style="padding: 0 10px;">
						<label for="apellidos_contacto">
							Apellidos:
						</label>
						<input type="text" id="apellidos_contacto" class="contacto_form_input <?php echo (form_error('apellidos_contacto') != '') ? 'input_error' : ''; ?>" placeholder="" 
							name="apellidos_contacto" value="<?php echo (!empty($validado)) ? set_value('apellidos_contacto') : $cliente->apellidos_contacto; ?>" style="width: 335px; height: 40px;" />
						<?php echo form_error('apellidos_contacto'); ?>
					</p>
					<p style="padding: 0 10px;">
						<label for="email">
							Emails (Puede indicar más de un email separándolos con una coma):
						</label>
						<input type="text" id="email" class="contacto_form_input <?php echo (form_error('email') != '') ? 'input_error' : ''; ?>" placeholder="" 
							name="email" value="<?php echo (!empty($validado)) ? set_value('email') : $cliente->email; ?>" style="width: 335px; height: 40px;" />
						<?php echo form_error('email'); ?>
					</p>
					<p style="padding: 0 10px;">
						<label for="fecha_registro">
							Fecha de registro:
						</label>
						<input type="text" id="fecha_registro" class="contacto_form_input" disabled="disabled" name="fecha_registro" 
							value="<?php echo $cliente->fecha_registro; ?>" style="width: 335px; height: 40px;" />
					</p>
					<p style="padding: 0 10px;">
						<label for="telefono">
							Teléfono:
						</label>
						<input type="text" id="telefono" class="contacto_form_input <?php echo (form_error('telefono') != '') ? 'input_error' : ''; ?>" placeholder="" 
							name="telefono" value="<?php echo (!empty($validado)) ? set_value('telefono') : $cliente->telefono; ?>" style="width: 335px; height: 40px;" />
						<?php echo form_error('telefono'); ?>
					</p>
					<p style="padding: 0 10px;">
						<label for="direccion">
							Dirección:
						</label>
						<input type="text" id="direccion" class="contacto_form_input <?php echo (form_error('direccion') != '') ? 'input_error' : ''; ?>" placeholder="" 
							name="direccion" value="<?php echo (!empty($validado)) ? set_value('direccion') : $cliente->direccion; ?>" style="width: 335px; height: 40px;" />
						<?php echo form_error('direccion'); ?>
					</p>
				</div>
				<div class="first_part_reg">
					<p style="padding: 0 10px;">
						<label for="cp">
							Código Postal:
						</label>
						<input type="text" id="cp" class="contacto_form_input <?php echo (form_error('cp') != '') ? 'input_error' : ''; ?>" placeholder="" 
							name="cp" value="<?php echo (!empty($validado)) ? set_value('cp') : $cliente->cp; ?>" style="width: 335px; height: 40px;" />
						<?php echo form_error('cp'); ?>
					</p>
					<p style="padding: 0 10px;">
						<label for="cif">
							CIF:
						</label>
						<input type="text" id="cif" class="contacto_form_input <?php echo (form_error('cif') != '') ? 'input_error' : ''; ?>" placeholder="" 
							name="cif" value="<?php echo (!empty($validado)) ? set_value('cif') : $cliente->cif; ?>" style="width: 335px; height: 40px;" />
						<?php echo form_error('cif'); ?>
					</p>
					<p style="padding: 0 10px;">
						<label for="poblacion">
							Población:
						</label>
						<input type="text" id="poblacion" class="contacto_form_input <?php echo (form_error('poblacion') != '') ? 'input_error' : ''; ?>" placeholder="" 
							name="poblacion" value="<?php echo (!empty($validado)) ? set_value('poblacion') : $cliente->poblacion; ?>" style="width: 335px; height: 40px;" />
						<?php echo form_error('poblacion'); ?>
					</p>
                                        <p style="padding: 0 10px;" <?php echo (form_error('provincia')) ? 'class="tooltips"' : ''; ?>>
                                                <?php echo (form_error('provincia')) ? form_error('provincia') : ''; ?>
                                            <label class="tooltips">Provincia<em>* </em></label>
                                            <select id="provincia" name="provincia" class="contacto_form_input" style="width: 100%;">
                                                <option value="">
                                                        Seleccione Provincia
                                                </option>
                                                <?php
                                                    if(!empty($provincias)){
                                                        foreach($provincias as $provincia){
                                                ?>
                                                            <option value="<?php echo $provincia->id_provincia ?>" <?php echo (!empty($validado)) ? set_select('provincia', $provincia->id_provincia) : (($cliente->id_provincia == $provincia->id_provincia) ? 'selected="selected"' : ''); ?>>
                                                                <?php echo $provincia->provincia ?>
                                                            </option>
                                                <?php
                                                        }
                                                    }
                                                ?>
                                            </select>
                                        </p>
                                        <p style="padding: 0 10px;" <?php echo (form_error('sector')) ? 'class="tooltips"' : ''; ?>>
                                                <?php echo (form_error('sector')) ? form_error('sector') : ''; ?>
                                            <label class="tooltips">Sector</label>
                                            <select id="sector" name="sector" class="contacto_form_input" style="width: 100%;">
                                                <option value="">
                                                        Seleccione Sector
                                                </option>
                                                <?php
                                                    if(!empty($sectores)){
                                                        foreach($sectores as $sector){
                                                ?>
                                                            <option value="<?php echo $sector->id_sector ?>" <?php echo (!empty($validado)) ? set_select('provincia', $sector->id_sector) : (($cliente->id_sector == $sector->id_sector) ? 'selected="selected"' : ''); ?>>
                                                                <?php echo $sector->sector ?>
                                                            </option>
                                                <?php
                                                        }
                                                    }
                                                ?>
                                            </select>
                                        </p>
					<p style="padding: 0 10px;">
						<label for="web">
							Web:
						</label>
						<input type="text" id="web" class="contacto_form_input <?php echo (form_error('web') != '') ? 'input_error' : ''; ?>" placeholder="" 
							name="web" value="<?php echo (!empty($validado)) ? set_value('web') : $cliente->web; ?>" style="width: 335px; height: 40px;" />
						<?php echo form_error('web'); ?>
					</p>
					<p style="padding: 0 10px;">
						<label for="agencia">
							Pertenece a:
						</label>
						<input type="text" id="agencia" class="contacto_form_input" disabled="disabled" name="agencia" 
							value="<?php echo $cliente->agencia; ?>" style="width: 335px; height: 40px;" />
					</p>
					<p style="padding: 0 10px;">
						<label for="medios_interesado">
							Medios interesado:
						</label>
						<textarea type="text" id="medios_interesado" class="contacto_form_input" disabled="disabled" name="medios_interesado" 
							 style="width: 335px; height: 40px;" /><?php echo $cliente->medios_interesado; ?></textarea>
					</p>
					<p style="padding: 0 10px;">
						<label for="meses_interesado">
							Meses interesado:
						</label>
						<textarea type="text" id="meses_interesado" class="contacto_form_input" disabled="disabled" name="meses_interesado" 
							 style="width: 335px; height: 40px;" /><?php echo $cliente->meses_interesado; ?></textarea>
					</p>
					<p style="padding: 0 10px;">
						<label for="estado">
							Estado:
						</label>
						<input type="text" id="estado" class="contacto_form_input" disabled="disabled" name="estado" 
							value="<?php echo ($cliente->estado == 0) ? 'Aceptado' : (($cliente->estado == 1) ? 'Pendiente' : (($cliente->estado == 3) ? 'Desactivado' : 'Denegado')); ?>" style="width: 335px; height: 40px;" />
					</p>
					<p style="padding: 0 10px; text-align: center;">
						<input type="checkbox" onclick="clickCheckeo(this);" id="newsletter" class="contacto_form_input" name="newsletter" 
							 <?php echo (!empty($validado)) ? set_checkbox('newsletter', 1) : ((empty($cliente->newsletter) || $cliente->newsletter==0) ? 'value="0" ' : 'value="1" checked="checked"'); ?> />¿Recibir Newsletter?
					</p>
				</div>
			</div>
			<div class="contact_form_area" >
				<a href="javascript:history.go(-1)">
					<input type="button" class="cupid-orange" id="sign_in_sub" value="Volver a anunciantes" style="width: 50%; float: left; margin-top: 0;"/>
				</a>
				<input type="submit" class="cupid-orange" id="sign_in_sub" value="Guardar" style="width: 50%;"/>
			</div>
		<?php echo form_close(); ?>
	</div>
</div>
