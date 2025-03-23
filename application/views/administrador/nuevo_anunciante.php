<script type="text/javascript" src="<?php echo base_url();?>js/jquery-ui.js"></script>
<link rel="stylesheet" href="<?php echo base_url();?>css/jquery-ui.min.css" type="text/css" media="screen"/>
<?php $this->load->helper('form'); ?>
<div>
	<div style="position: relative; width: 360px; display:inline-table; margin-bottom: 5px; text-align: left; right: 20px;">
		<h3 style="text-align: center;">
			Nuevo Cliente
		</h3>
		<?php echo form_open_multipart("administrador/nuevoAnunciante/1"); ?>
			<div class="reg_form">
				<div class="first_part_reg">
					<p style="padding: 0 10px;">
						<label for="nombre">
							Empresa:
						</label>
						<input type="text" id="nombre" class="contacto_form_input <?php echo (form_error('nombre') != '') ? 'input_error' : ''; ?>" placeholder="" 
							name="nombre" value="<?php echo set_value('nombre'); ?>" style="width: 335px; height: 40px;" />
						<?php echo form_error('nombre'); ?>
					</p>
                                        <p style="padding: 0 10px;">
						<label for="nick">
							Nickname (puede poner su email):
						</label>
						<input type="text" id="nick" class="contacto_form_input <?php echo (form_error('nick') != '') ? 'input_error' : ''; ?>" placeholder="" 
							name="nick" value="<?php echo set_value('nick'); ?>" style="width: 335px; height: 40px;" />
						<?php echo form_error('nick'); ?>
					</p>
					<p style="padding: 0 10px;">
						<label for="email">
							Email:
						</label>
						<input type="text" id="email" class="contacto_form_input <?php echo (form_error('email') != '') ? 'input_error' : ''; ?>" placeholder="" 
							name="email" value="<?php echo set_value('email'); ?>" style="width: 335px; height: 40px;" />
						<?php echo form_error('email'); ?>
					</p>
					<p style="padding: 0 10px;">
						<label for="telefono">
							Teléfono:
						</label>
						<input type="text" id="telefono" class="contacto_form_input <?php echo (form_error('telefono') != '') ? 'input_error' : ''; ?>" placeholder="" 
							name="telefono" value="<?php echo set_value('telefono'); ?>" style="width: 335px; height: 40px;" />
						<?php echo form_error('telefono'); ?>
					</p>
                                        <p <?php echo (form_error('provincia')) ? 'class="tooltips"' : ''; ?>>
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
                                                            <option value="<?php echo $provincia->id_provincia ?>" <?php echo (set_value('provincia') == $provincia->id_provincia) ? 'selected="selected"' : ''; ?>>
                                                                <?php echo $provincia->provincia ?>
                                                            </option>
                                                <?php
                                                        }
                                                    }
                                                ?>
                                            </select>
                                        </p>
                                        <p <?php echo (form_error('sector')) ? 'class="tooltips"' : ''; ?>>
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
                                                            <option value="<?php echo $sector->id_sector ?>" <?php echo (set_value('sector') == $sector->id_sector) ? 'selected="selected"' : ''; ?>>
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
							name="web" value="<?php echo set_value('web'); ?>" style="width: 335px; height: 40px;" />
						<?php echo form_error('web'); ?>
					</p>
					<p style="padding: 0 10px;">
						<label for="agencia">
							Agencia:
						</label>
						<select id="agencia" class="contacto_form_input <?php echo (form_error('agencia') != '') ? 'input_error' : ''; ?>" 
								style="width: 335px; height: 40px;" name="agencia">
<?php
	if(!empty($agencias)){
		foreach($agencias as $agencia){ 
?>
							<option value="<?php echo $agencia->id_agencia ?>" <?php echo (!empty($validado)) ? set_select('agencia', $agencia->id_agencia) : ''; ?>>
								<?php echo $agencia->nombre ?>
							</option>
<?php 
		}
	} 
?>
						</select>
					</p>
                                        <p>
                                                <label class="oferta_label" for="publicada">
                                                        ¿Notificar alta por email?
                                                </label>
                                                <input type="checkbox" id="notificacion" name="notificacion" value="1"
                                                        <?php echo (!empty($validado)) ? set_checkbox('notificacion', 1) : ''; ?> style="width: auto;"/>
                                                <?php echo form_error('notificacion'); ?>
                                        </p>
				</div>
			</div>
			<div class="contact_form_area" >
				<a href="<?php echo base_url() ?>administrador/anunciantes">
					<input type="button" class="cupid-orange" id="sign_in_sub" value="Volver a anunciantes" style="width: 50%; float: left; margin-top: 0;"/>
				</a>
				<input type="submit" class="cupid-orange" id="sign_in_sub" value="Crear" style="width: 50%;"/>
			</div>
		<?php echo form_close(); ?>
	</div>
</div>
