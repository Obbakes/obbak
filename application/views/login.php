<?php include 'partials/main.php'; ?>

    <head>
        
    <?php includeFileWithVariables('partials/title-meta.php', array('title' => 'Default')); ?>

    <?php include 'partials/head-css.php'; ?>

    </head>

    <body class="">

        <!-- Begin page -->
        <div id="layout-wrapper">


            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->
        <div class="row">
				<div class="card">
					<div class="row justify-content-center">
						<div class="col-xl-6">
							<div class="row justify-content-center">
                        <span class="logo-sm">
                        <img src="https://app.obbak.es/assets/images/logos/para_web.png" alt="logo-sm-light" height="300"></span>
						</div>
						</div>
					</div>
				</div>
		</div>
                <!-- end row -->
				<div class="row justify-content-center">
					<div class="col-3">
						<div class="card">
							<div class="text-center">
                            <div class="card-body">
                                <div class="avatar-sm mx-auto mb-4">
                                    <div class="avatar-title rounded-circle">
                                        <i class="ri-broadcast-line font-size-24"></i>
                                    </div>
                                </div>
                                <h5 class="font-size-15 text-uppercase">Accede a oportunidades exclusivas</h5>
                                <p class="mb-0">Regístrate en Obbak y descubre proyectos de videojuegos en desarrollo con acceso a contenido exclusivo.</p>
                            </div>
                        </div>
						</div>
					</div>
					<div class="col-3">
						<div class="card">
							<div class="text-center">
								<div class="card-body">
                                <div class="avatar-sm mx-auto mb-4">
                                    <div class="avatar-title rounded-circle">
                                        <i class="ri-time-line font-size-24"></i>
                                    </div>
                                </div>
								</div>
                                <h5 class="font-size-15 text-uppercase">
                                    Diversifica tu catálogo apoyando proyectos indie innovadores</h5>
                                <p class="mb-0">Descubre, invierte y gestiona fácilmente desde tu panel de control</p>
                            </div>
                        </div>
					</div>
					<div class="col-3">
						<div class="card">
                           <div class="card-body">
                                <div class="avatar-sm mx-auto mb-4">
                                    <div class="avatar-title rounded-circle">
                                        <i class="ri-mail-line font-size-24"></i>
                                    </div>
                                </div>
                                <h5 class="font-size-15 text-uppercase">
                                    Invierte en videojuegos y optimiza tus impuestos.</h5>
                                <p class="mb-0">Si eres una empresa o trabajas por cuenta propia  en España, puedes beneficiarte de importantes deducciones fiscales al apoyar proyectos de desarrollo de videojuegos. Maximiza tu rentabilidad mientras impulsas la creación de contenido innovador en la industria del gaming <a
                                            href="	"
                                            class="text-decoration-underline"></a></p>
                            </div>
						</div>
					</div>
				</div>
				 <hr>
				<div class="row justify-content-center">				
					<div class="col-xl-3">
						<div class="card">
							<div class="card-body p-4">
                                <div class="middle-box text-center animated fadeInDown">
									<div class="card">
										<div class="text-center">
                <h2>Nuevo Inversor</h2>
                <p>Env&iacute;anos tus datos para acceder a tu &aacute;rea privada.</p>
            </div>
										<form class="m-t" role="form" method="post" action="<?php echo base_url();?><?php echo "inicio/registration/1 " . ((!empty($id_oferta)) ? ('/' . $id_oferta) : ''); ?>">

                <?php if(!empty($error_login)){ ?>
                <div id="error_login" class="errorMod">

                    <?php echo $error_login; ?>
                </div>
                <?php } ?>


                <div class="form-group">
                    <input type="email" name="email" value="<?php echo set_value('email'); ?>" class="form-control" placeholder="Email" required maxlength="100">
                    <div class="error-form">
                        <?php echo form_error('email'); ?>
                    </div>
                </div>

                <div class="form-group">
                    <input type="text" name="nombre" value="<?php echo set_value('nombre'); ?>" class="form-control" placeholder="Nombre" required maxlength="50">
                    <div class="error-form">
                        <?php echo form_error('nombre'); ?>
                    </div>
                </div>


                <div class="form-group">
                    <input type="text" name="nombre_comercial" value="<?php echo set_value('nombre_comercial'); ?>" class="form-control" placeholder="Apellidos" required maxlength="100">
                    <div class="error-form">
                        <?php echo form_error('nombre_comercial'); ?>
                    </div>
                </div>

                <div class="form-group">
                    <input type="text" name="cif" value="<?php echo set_value('cif'); ?>" class="form-control" placeholder="NIF (necesario para verificar tu condici&oacute;n de inversor)" required maxlength="20">
                    <div class="error-form">
                        <?php echo form_error('cif'); ?>
                    </div>
                </div>

                <div class="form-group">
                    <input class="form-control" type="date Fecha Nacimiento" name="Fecha_nacimiento" value="<?php echo set_value('Fecha_nacimiento'); ?>" class="form-control" id="example-date-input" placeholder="Fecha Nacimiento" required maxlength="20">
                    <span class="text-muted">e.g "mm/dd/yyyy"</span>
                    <div class="error-form">
                        <?php echo form_error('Fecha_nacimiento'); ?>
                    </div>
                </div>
                <div class="form-group">
                    <input type="tel" name="telefono" value="<?php echo set_value('telefono'); ?>" class="form-control" placeholder="Tel&eacute;fono" required maxlength="20">
                    <div class="error-form">
                        <?php echo form_error('telefono'); ?>
                    </div>
                </div>

                <div class="form-group">
                    <select id="provincia" name="provincia">
                                        <option value="">
                                                Provincia
                                        </option>
                                        <?php if(!empty($provincias)){ foreach($provincias as $prov){ ?>
                                             <option value="<?php echo $prov->id_provincia ?>" <?php echo set_select('provincia', $prov->id_provincia, (isset($provincia)) ? $prov->id_provincia==$provincia : FALSE) ?>>
                                                        <?php echo $prov->provincia ?>
                                                </option>
                                        <?php
                                                }
                                            }
                                        ?>
                                    </select>
                    <div class="error-form">
                        <?php echo form_error('provincia'); ?>
                    </div>
                </div>

                <div class="form-group">
                    <input type="password" name="password" class="form-control" placeholder="Contraseña" required maxlength="50">
                    <div class="error-form">
                        <?php echo form_error('password'); ?>
                    </div>
                </div>

                <div class="form-group">
                    <input type="password" name="con_password" class="form-control" placeholder="Confirmar Contraseña" required maxlength="50">
                    <div class="error-form">
                        <?php echo form_error('con_password'); ?>
                    </div>
                </div>

                <div class="form-group">
                    <input type="hidden" name="tipo_usuario" value="anunciante" />
                    <input type="checkbox" name="checkcondiciones" value="checkcondiciones" required <?php echo set_checkbox( 'checkcondiciones', 'checkcondiciones') ?>/> &nbsp; Acepto la <a class="enlace mod" href="<?php echo base_url();?>inicio/condiciones"
                        target="_blank">Política de Privacidad y Términos de uso</a>
                </div>

                <button type="submit" class="btn btn-primary block full-width m-b">Reg&iacute;strate</button>
                <br />
                <div>
                    <a href="<?php echo base_url();?>inicio/login">
                        <h4 class="fa fa-arrow-left features-icon" style="font-size: 14px;"></h4>
                        <small>volver</small>
                    </a>
                </div>
                <p style="text-align:justify;">
                    <small>*Politicsa
                                </small>
                </p>
                <br />
                <br />
            </form>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
</body>

<!-- Bootstrap Css -->
<link href="https://app.obbak.es/assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
<!-- Icons Css -->
<link href="https://app.obbak.es/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
<!-- App Css-->
<link href="https://app.obbak.es/assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />

                <!-- twitter-bootstrap-wizard js -->
<script src="https://app.obbak.es/assets/libs/twitter-bootstrap-wizard/jquery.bootstrap.wizard.min.js"></script>

<script src="https://app.obbak.es/assets/libs/twitter-bootstrap-wizard/prettify.js"></script>

<!-- form wizard init -->
<script src="https://app.obbak.es/assets/js/pages/form-wizard.init.js"></script>

<script src="https://app.obbak.es/assets/js/app.js"></script>

<?php include 'partials/vendor-scripts.php'; ?>

<script src="https://app.obbak.es/assets/js/app.js"></script>

    </body>
</html>