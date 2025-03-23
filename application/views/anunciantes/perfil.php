<?php include 'partials/main.php'; ?>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="" />
    <meta name="keyword" content="" />
    <meta name="author" content="flexilecode" />
    <!--! The above 6 meta tags *must* come first in the head; any other head content must come *after* these tags !-->
    <!--! BEGIN: Apps Title-->
    <title>Obbak || Panel</title>
    <!--! END:  Apps Title-->
    <!--! BEGIN: Favicon-->
    <link rel="shortcut icon" type="image/x-icon" href="https://app.obbak.es/assets/images/favicon.ico" />
    <!--! END: Favicon-->
    <!--! BEGIN: Bootstrap CSS-->
    <link rel="stylesheet" type="text/css" href="https://app.obbak.es/assets/css/bootstrap.min.css" />
    <!--! END: Bootstrap CSS-->
    <!--! BEGIN: Vendors CSS-->
    <link rel="stylesheet" type="text/css" href="https://app.obbak.es/assets/vendors/css/vendors.min.css" />
    <link rel="stylesheet" type="text/css" href="https://app.obbak.es/assets/vendors/css/daterangepicker.min.css" />
    <!--! END: Vendors CSS-->
    <!--! BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="https://app.obbak.es/assets/css/theme.min.css" />
    <!--! END: Custom CSS-->
    <!--! HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries !-->
    <!--! WARNING: Respond.js doesn"t work if you view the page via file: !-->
    <!--[if lt IE 9]>
			<script src="https:oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
			<script src="https:oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->

</head>

<!-- Begin page -->
    <?php include 'partials/menu.php'; ?>
<body>
    <!-- ============================================================== -->
    <!-- Start right Content here -->
    <!-- ============================================================== -->
<div class="nxl-container">
    <div class="nxl-content">
        <div class="main-content"> 

<div class="page-content">
    <div class="container-fluid">


        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form class="form-horizontal" role="form" method="post" action="<?php echo base_url(); ?>anunciantes/perfil/1" enctype="multipart/form-data">
                                <div class="row">

                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                        <input type="text" id="form-control" disabled="disabled" placeholder="" name="nick" value="<?php echo (!empty($aviso_error)) ? set_value('nick') : $cliente->nick; ?>" class="form-control <?php echo (form_error('nick') != '') ? 'input_error' : ''; ?>"/>
                                                        <label for="floatingFirstnameInput">Usuario</label>
                                                        <?php echo form_error('nombre_contacto'); ?>
                                                    </div>
                                                </div>

                           
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                        <input type="text" id="form-control" placeholder="" name="nombre_contacto" value="<?php echo (!empty($aviso_error)) ? set_value('nombre_contacto') : $cliente->nombre; ?>" class="form-control <?php echo (form_error('nombre_contacto') != '') ? 'input_error' : ''; ?>"/>
                                                        <label for="floatingFirstnameInput">Nombre Contacto</label>
                                                        <?php echo form_error('nombre_contacto'); ?>
                                                    </div>
                                                </div>

                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                        <input type="text" id="form-control" placeholder="" name="apellidos_contacto" value="<?php echo (!empty($aviso_error)) ? set_value('apellidos_contacto') : $cliente->apellidos_contacto ?>" class="form-control <?php echo (form_error('apellidos_contacto') != '') ? 'input_error' : ''; ?>"/>
                                                        <label for="floatingFirstnameInput">apellidos_contacto</label>
                                                        <?php echo form_error('apellidos_contacto'); ?>
                                                    </div>
                                                </div>
                                                          

                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                        <input type="text" id="form-control" placeholder="" name="email" value="<?php echo (!empty($aviso_error)) ? set_value('email') : $cliente->email ?>" class="form-control <?php echo (form_error('email') != '') ? 'input_error' : ''; ?>"/>
                                                        <label for="floatingFirstnameInput">email</label>
                                                        <?php echo form_error('email'); ?>
                                                    </div>
                                                </div>

                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                        <input type="date" id="form-control" placeholder="" name="fecha_registro" value="<?php echo (!empty($aviso_error)) ? set_value('fecha_registro') : $cliente->fecha_registro ?>" class="form-control <?php echo (form_error('fecha_registro') != '') ? 'input_error' : ''; ?>"/>
                                                        <label for="floatingFirstnameInput">Fecha registro</label>
                                                        <?php echo form_error('fecha_registro'); ?>
                                                    </div>
                                                </div>            

                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                                        <input type="password" id="pass" placeholder="Escribe una nueva contraseña" name="pass" value="" class="form-control" <?php echo (form_error('pass') != '') ? 'input_error' : ''; ?>/>
                                                        <?php echo form_error('pass'); ?>
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
                                    <button class="boton waves-effect" type="submit">Guardar cambios</button>
                                    </div>
                                    </div>

                                    <div class="col-lg-6 col-md-12">
                                    <div class="form-group">
                                        <div class="baja">
                                            <a href="#" data-toggle="modal" data-target="#modalBaja">Cerrar cuenta</a>
                                        </div>
                                    </div>
                                </div>
                           
                        </form>
                </div>
            </div>
        </div>
        </div>
    </div>
    <!-- #END# Hover Rows -->

</div>

</div>
<!-- end main content-->

        </div>
		<div style="height: 20px;"></div> <!-- Espacio extra -->
        <!-- [ Footer ] start -->
		<?php include 'partials/footer.php'; ?> 
        <!-- [ Footer ] end -->
	</main>
    <!--! ================================================================ !-->
    <!--! [End] Main Content !-->
    <!--! ================================================================ !-->
    <!--! ================================================================ !-->
    <!--! BEGIN: Theme Customizer !-->
    <!--! ================================================================ !-->
    <!--! [End] Theme Customizer !-->
    <!--! ================================================================ !-->
    <!--! ================================================================ !-->
    <!--! Footer Script !-->
    <!--! ================================================================ !-->
    <!--! BEGIN: Vendors JS !-->
    <script src="https://app.obbak.es/assets/vendors/js/vendors.min.js"></script>
    <!-- vendors.min.js {always must need to be top} -->
    <script src="https://app.obbak.es/assets/vendors/js/daterangepicker.min.js"></script>
    <script src="https://app.obbak.es/assets/vendors/js/apexcharts.min.js"></script>
    <script src="https://app.obbak.es/assets/vendors/js/circle-progress.min.js"></script>
    <!--! END: Vendors JS !-->
    <!--! BEGIN: Apps Init  !-->
    <script src="https://app.obbak.es/assets/js/common-init.min.js"></script>
    <script src="https://app.obbak.es/assets/js/dashboard-init.min.js"></script>
    <!--! END: Apps Init !-->
    <!--! BEGIN: Theme Customizer  !-->
    <script src="https://app.obbak.es/assets/js/theme-customizer-init.min.js"></script>
    <!--! END: Theme Customizer !-->
    
</body>

</html>