<div class="container-fluid"> <!-- Hover Rows -->
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="card">
                <div class="header header-medios">
                    <h2 class="text-lowercase text-capitalize">Perfil<span>de Usuario</span></h2>
                </div>
                <div class="body sombreado">

                        <form class="form-horizontal" role="form" method="post" action="<?php echo base_url(); ?>anunciantes/perfil/1" enctype="multipart/form-data">

                            <div class="row clearfix">
                                <div class="col-lg-6 col-md-12">
                                    <div class="form-group">
                                        <label class="control-label">Usuario</label>
                                        <input type="text" id="nick" disabled="disabled" placeholder="" name="nick" value="<?php echo (!empty($aviso_error)) ? set_value('nick') : $cliente->nick; ?>" class="form-control contacto_form_input <?php echo (form_error('nick') != '') ? 'input_error' : ''; ?>"/>
                                        <?php echo form_error('nick'); ?>
                                    </div>
                                </div>

                            </div>

                            <div class="row clearfix">


                                <div class="col-lg-6 col-md-12">
                                    <div class="form-group">
                                        <label class="control-label">Nombre</label>
                                        <input type="text" id="nombre_contacto" placeholder="" name="nombre_contacto" value="<?php echo (!empty($aviso_error)) ? set_value('nombre_contacto') : $cliente->nombre_contacto; ?>" class="form-control <?php echo (form_error('nombre_contacto') != '') ? 'input_error' : ''; ?>"/>
                                        <?php echo form_error('nombre_contacto'); ?>
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-12">
                                    <div class="form-group">
                                        <label class="control-label">Apellidos</label>
                                        <input type="text" id="apellidos_contacto" placeholder="" name="apellidos_contacto" value="<?php echo (!empty($aviso_error)) ? set_value('apellidos_contacto') : $cliente->apellidos_contacto; ?>" class="form-control <?php echo (form_error('apellidos_contacto') != '') ? 'input_error' : ''; ?>"/>
                                        <?php echo form_error('apellidos_contacto'); ?>
                                    </div>
                                </div>


                            </div>

                            <div class="row clearfix">

                                <div class="col-lg-6 col-md-12">
                                    <div class="form-group">
                                        <label class="control-label">Email</label>
                                        <input type="text" id="email" placeholder="" disabled="disabled" name="email" value="<?php echo $cliente->email; ?>" class="form-control" />
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-12">
                                    <div class="form-group">
                                        <label class="control-label">Fecha de registro</label>
                                        <input type="text" id="fecha_registro" disabled="disabled" name="fecha_registro" value="<?php echo $cliente->fecha_registro; ?>" class="form-control" />
                                    </div>
                                </div>


                            </div>

                            <div class="row clearfix">
                                <div class="col-lg-6 col-md-12">
                                    <div class="form-group">
                                        <label class="control-label">Cambiar contraseña</label>
                                        <input type="password" id="pass" placeholder="Escribe una nueva contraseña" name="pass" value="" class="form-control" <?php echo (form_error('pass') != '') ? 'input_error' : ''; ?>/>
                                        <?php echo form_error('pass'); ?>
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-12">
                                    <div class="form-group">
                                        <label class="control-label">Confirmar contraseña</label>
                                        <input type="password" id="pass_conf" placeholder="Confirmar contraseña" name="pass_conf" value="" class="form-control" <?php echo (form_error('pass_conf') != '') ? 'input_error' : ''; ?>" />
                                            <?php echo form_error('pass_conf'); ?>
                                    </div>
                                </div>
                            </div>

                            <div class="row clearfix m-t-30">
                                <div class="col-lg-6 col-md-12">
                                    <div class="form-group">
                                        <button class="boton waves-effect" type="submit" id="sign_in_sub" value="GUARDAR CAMBIOS">Guardar cambios</button>
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-12 text-right">
                                    <a href="#" data-toggle="modal" data-target="#modalBaja">Cerrar cuenta</a>
                                </div>
                            </div>
                        </form>
                </div>
            </div>
        </div>
    </div>
    <!-- #END# Hover Rows -->

</div>


<?php $this->load->view('anunciantes/perfil_modal_baja');?>