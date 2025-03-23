<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="keyword" content="">
    <meta name="author" content="theme_ocean">
    <!--! The above 6 meta tags *must* come first in the head; any other head content must come *after* these tags !-->
    <!--! BEGIN: Apps Title-->
    <title>Obbak || Registro Cliente</title>
    <!--! END:  Apps Title-->
    <!--! BEGIN: Favicon-->
    <link rel="shortcut icon" type="image/x-icon" href="https://app.obbak.es/assets/images/favicon.ico">
    <!--! END: Favicon-->
    <!--! BEGIN: Bootstrap CSS-->
    <link rel="stylesheet" type="text/css" href="https://app.obbak.es/assets/css/bootstrap.min.css">
    <!--! END: Bootstrap CSS-->
    <!--! BEGIN: Vendors CSS-->
    <link rel="stylesheet" type="text/css" href="https://app.obbak.es/assets/vendors/css/vendors.min.css">
    <!--! END: Vendors CSS-->
    <!--! BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="https://app.obbak.es/assets/css/theme.min.css">
    <!--! END: Custom CSS-->
    <!--! HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries !-->
    <!--! WARNING: Respond.js doesn"t work if you view the page via file: !-->
    <!--[if lt IE 9]>
			<script src="https:oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
			<script src="https:oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
</head>

<body>



            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->
            <main class="auth-minimal-wrapper">
        <div class="auth-minimal-inner">
            <div class="minimal-card-wrapper">
                <div class="card mb-4 mt-5 mx-4 mx-sm-0 position-relative">
                    <div class="wd-50 bg-white p-2 rounded-circle shadow-lg position-absolute translate-middle top-0 start-50">
                        <img src="https://app.obbak.es/assets/images/Obbak_naranja_mosca.png" alt="" class="img-fluid">
                    </div>
                    <div class="card-body p-sm-5">
                        <h2 class="fs-20 fw-bolder mb-4">Nuevo Inversor</h2>
                        <h4 class="fs-13 fw-bold mb-2">Manage all your Duralux crm</h4>
                        <p class="fs-12 fw-medium text-muted">Env&iacute;anos tus datos para acceder a tu &aacute;rea privada.</p>
                <!-- end row -->
				 <hr>
				<div class="row justify-content-center">				

						<div class="card">
							<div class="card-body p-4">
                                <div class="middle-box text-center animated fadeInDown">
										<form class="m-t" role="form" method="post" action="<?php echo base_url();?><?php echo "inicio/registration/1" . ((!empty($id_oferta)) ? ('/' . $id_oferta) : ''); ?>">

                <?php if(!empty($error_login)){ ?>
                <div id="error_login" class="errorMod">

                    <?php echo $error_login; ?>
                </div>
                <?php } ?>


                <div class="mb-3 row">
                    <input type="email" name="email" value="<?php echo set_value('email'); ?>" class="form-control" placeholder="Email" required maxlength="100">
                    <div class="error-form">
                        <?php echo form_error('email'); ?>
                    </div>
                </div>

                <div class="mb-3 row">
                    <input type="text" name="nombre" value="<?php echo set_value('nombre'); ?>" class="form-control" placeholder="Nombre" required maxlength="50">
                    <div class="error-form">
                        <?php echo form_error('nombre'); ?>
                    </div>
                </div>


                <div class="mb-3 row">
                    <input type="text" name="nombre_comercial" value="<?php echo set_value('nombre_comercial'); ?>" class="form-control" placeholder="Apellidos" required maxlength="100">
                    <div class="error-form">
                        <?php echo form_error('nombre_comercial'); ?>
                    </div>
                </div>

                <div class="mb-3 row">
                    <input type="text" name="cif" value="<?php echo set_value('cif'); ?>" class="form-control" placeholder="NIF (necesario para verificar tu condici&oacute;n de inversor)" required maxlength="20">
                    <div class="error-form">
                        <?php echo form_error('cif'); ?>
                    </div>
                </div>

                <div class="mb-3 row">
                    <input class="form-control" type="date" name="Fecha_nacimiento" value="<?php echo set_value('Fecha_nacimiento'); ?>" class="form-control" id="example-date-input" placeholder="Fecha Nacimiento" required maxlength="20">
                    <span class="text-muted">e.g "mm/dd/yyyy"</span>
                    <div class="error-form">
                        <?php echo form_error('Fecha_nacimiento'); ?>
                    </div>
                </div>
                <div class="mb-3 row">
                    <input type="tel" name="telefono" value="<?php echo set_value('telefono'); ?>" class="form-control" placeholder="Tel&eacute;fono" required maxlength="20">
                    <div class="error-form">
                        <?php echo form_error('telefono'); ?>
                    </div>
                </div>

                <div class="mb-3 row">
                    <select class="form-select" name="provincia">
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

                <div class="mb-3 row">
                    <input type="password" name="password" class="form-control" placeholder="Contraseña" required maxlength="50">
                    <div class="error-form">
                        <?php echo form_error('password'); ?>
                    </div>
                </div>

                <div class="mb-3 row">
                    <input type="password" name="con_password" class="form-control" placeholder="Confirmar Contraseña" required maxlength="50">
                    <div class="error-form">
                        <?php echo form_error('con_password'); ?>
                    </div>
                </div>
			    <div class="row">	
				<div class="mb-3 row">
                <div class="col-lg-12">
                    <input type="hidden" name="tipo_usuario" value="anunciante" />
                    <input type="checkbox" name="checkcondiciones" value="checkcondiciones" required <?php echo set_checkbox( 'checkcondiciones', 'checkcondiciones') ?>/> &nbsp; Acepto la <a class="form-check-label" href="<?php echo base_url();?>inicio/condiciones"
                        target="_blank">Política de Privacidad y Términos de uso</a>
                </div>

                <button type="submit" class="btn btn-primary block full-width m-b">Reg&iacute;strate</button>
                <br />
			    </div>
			
			 <div class="mb-3 row">    
				<div class="d-grid flex-wrap gap-2 align-items-center">
					<a class="btn btn-secondary btn-sm waves-effect waves-light "   href="<?php echo base_url();?>inicio/#page-top">
                	<i class="fa fa-arrow-left features-icon"></i>
                	<small>volver</small>
					</a>
				</div>	
            </div>
			<div class="mb-3 row">
                <p style="text-align:justify;">
                    <small>*Politicsa</small>
                </p>
			</div>	

									</div>

		</form>
				
</body>
    <!--! BEGIN: Vendors JS !-->
    <script src="https://app.obbak.es/assets/vendors/js/vendors.min.js"></script>
    <!-- vendors.min.js {always must need to be top} -->
    <script src="https://app.obbak.es/https://app.obbak.es/assets/vendors/js/lslstrength.min.js"></script>
    <!--! END: Vendors JS !-->
<script src="https://app.obbak.es/assets/js/common-init.min.js"></script>
<script
document.addEventListener("DOMContentLoaded", function () {
    document.querySelector(".gen-pass").addEventListener("click", function () {
        const passwordField = document.getElementById("newPassword");
        const generatedPassword = generatePassword(12); // Longitud de la contraseña
        passwordField.value = generatedPassword;
    });

    function generatePassword(length) {
        const chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()";
        let password = "";
        for (let i = 0; i < length; i++) {
            password += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        return password;
    }
});
</script>

</html>



