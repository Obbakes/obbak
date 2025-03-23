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
    <main class="nxl-container">
        <div class="nxl-content">
            <!-- [ page-header ] start -->
            <div class="page-header">
                <div class="page-header-left d-flex align-items-center">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Customers</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                        <li class="breadcrumb-item">Create</li>
                    </ul>
                </div>
                <div class="page-header-right ms-auto">
                    <div class="page-header-right-items">
                        <div class="d-flex d-md-none">
                            <a href="javascript:void(0)" class="page-header-right-close-toggle">
                                <i class="feather-arrow-left me-2"></i>
                                <span>Back</span>
                            </a>
                        </div>
                        <div class="d-flex align-items-center gap-2 page-header-right-items-wrapper">
                            <a href="javascript:void(0);" class="btn btn-light-brand successAlertMessage">
                                <i class="feather-layers me-2"></i>
                                <span>Save as Draft</span>
                            </a>
                            <a href="javascript:void(0);" class="btn btn-primary successAlertMessage">
                                <i class="feather-user-plus me-2"></i>
                                <span>Create Customer</span>
                            </a>
                        </div>
                    </div>
                    <div class="d-md-none d-flex align-items-center">
                        <a href="javascript:void(0)" class="page-header-right-open-toggle">
                            <i class="feather-align-right fs-20"></i>
                        </a>
                    </div>
                </div>
            </div>
            <!-- [ page-header ] end -->
            <!-- [ Main Content ] start -->
            <div class="main-content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card border-top-0">
                            <div class="card-header p-0">
                                <!-- Nav tabs -->
                                <ul class="nav nav-tabs flex-wrap w-100 text-center customers-nav-tabs" id="myTab" role="tablist">
                                    <li class="nav-item flex-fill border-top" role="presentation">
                                        <a href="javascript:void(0);" class="nav-link active" data-bs-toggle="tab" data-bs-target="#profileTab" role="tab">Profile</a>
                                    </li>
                                    <li class="nav-item flex-fill border-top" role="presentation">
                                        <a href="javascript:void(0);" class="nav-link" data-bs-toggle="tab" data-bs-target="#passwordTab" role="tab">Password</a>
                                    </li>
                                    <li class="nav-item flex-fill border-top" role="presentation">
                                        <a href="javascript:void(0);" class="nav-link" data-bs-toggle="tab" data-bs-target="#billingTab" role="tab">Billing & Shipping</a>
                                    </li>
                                    <li class="nav-item flex-fill border-top" role="presentation">
                                        <a href="javascript:void(0);" class="nav-link" data-bs-toggle="tab" data-bs-target="#subscriptionTab" role="tab">Subscription</a>
                                    </li>
                                    <li class="nav-item flex-fill border-top" role="presentation">
                                        <a href="javascript:void(0);" class="nav-link" data-bs-toggle="tab" data-bs-target="#notificationsTab" role="tab">Notifications</a>
                                    </li>
                                    <li class="nav-item flex-fill border-top" role="presentation">
                                        <a href="javascript:void(0);" class="nav-link" data-bs-toggle="tab" data-bs-target="#connectionTab" role="tab">Connection</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="profileTab" role="tabpanel">
                                    <div class="card-body personal-info">
                                        <div class="mb-4 d-flex align-items-center justify-content-between">
                                            <h5 class="fw-bold mb-0 me-4">
                                                <span class="d-block mb-2">Personal Information:</span>
                                                <span class="fs-12 fw-normal text-muted text-truncate-1-line">Following information is publicly displayed, be careful! </span>
                                            </h5>
                                            <a href="javascript:void(0);" class="btn btn-sm btn-light-brand">Add New</a>
                                        </div>
                                        <div class="row mb-4 align-items-center">
                                            <div class="col-lg-4">
                                                <label class="fw-semibold">Avatar: </label>
                                            </div>
<form action="imagenUsuario" method="post" enctype="multipart/form-data">
    <div class="col-lg-8">
        <div class="mb-4 mb-md-0 d-flex gap-4 your-brand">
            <div class="wd-100 ht-100 position-relative overflow-hidden border border-gray-2 rounded">
                <img id="preview" src="<?= base_url('' . $cliente->imagen); ?>" class="upload-pic img-fluid rounded h-100 w-100" alt="">
                <div class="position-absolute start-50 top-50 end-0 bottom-0 translate-middle h-100 w-100 hstack align-items-center justify-content-center c-pointer upload-button">
                    <i class="feather feather-camera" aria-hidden="true"></i>
                </div>
            </div>
            <div class="d-flex flex-column gap-1">
                <div class="fs-11 text-gray-500 mt-2"># Upload your profile</div>
                <div class="fs-11 text-gray-500"># Avatar size 150x150</div>
                <div class="fs-11 text-gray-500"># Max upload size 2MB</div>
                <div class="fs-11 text-gray-500"># Allowed file types: png, jpg, jpeg</div>
            </div>
        </div>
		    <input type="file" name="avatar">
        <button type="submit" class="btn btn-primary mt-3">Subir Imagen</button>
    </div>
</form>
                                        </div>
								<form method="post" action="<?php echo base_url();?>anunciantes/perfilEmpresa/1" class="form-horizontal">	
                                        <div class="row mb-4 align-items-center">
                                            <div class="col-lg-4">
                                                <label for="fullnameInput" class="fw-semibold">Name: </label>
                                            </div>
                                            <div class="col-lg-8">
                                                <div class="input-group">
                                                    <div class="input-group-text"><i class="feather-user"></i></div>
                                        <input name="nombre" id="nombre" type="text" value="<?php echo (!empty($aviso_error)) ? set_value('nombre') : $cliente->nombre; ?>" class="form-control" >
                                        <?php echo form_error('nombre'); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-4 align-items-center">
                                            <div class="col-lg-4">
                                                <label for="mailInput" class="fw-semibold">Email: </label>
                                            </div>
                                            <div class="col-lg-8">
                                                <div class="input-group">
                                                    <div class="input-group-text"><i class="feather-mail"></i></div>
                                        <input name="email" id="email" type="text" value="<?php echo (!empty($aviso_error)) ? set_value('email') : $cliente->email; ?>" class="form-control">
                                        <?php echo form_error('email'); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-4 align-items-center">
                                            <div class="col-lg-4">
                                                <label for="usernameInput" class="fw-semibold">Username: </label>
                                            </div>
                                            <div class="col-lg-8">
                                                <div class="input-group">
                                                    <div class="input-group-text"><i class="feather-link-2"></i></div>
                                        <input name=nombre_comercial id="nombre_comercial" type="text" value="<?php echo (!empty($aviso_error)) ? set_value('nombre_comercial') : $cliente->nombre_comercial; ?>" class="form-control" >
                                        <?php echo form_error('nombre_comercial'); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-4 align-items-center">
                                            <div class="col-lg-4">
                                                <label for="phoneInput" class="fw-semibold">Phone: </label>
                                            </div>
                                            <div class="col-lg-8">
                                                <div class="input-group">
                                                    <div class="input-group-text"><i class="feather-phone"></i></div>
                                        <input name="telefono" id="telefono" type="text" value="<?php echo (!empty($aviso_error)) ? set_value('telefono') : $cliente->telefono; ?>" class="form-control" >
                                        <?php echo form_error('telefono'); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-4 align-items-center">
                                            <div class="col-lg-4">
                                                <label for="companyInput" class="fw-semibold">Company: </label>
                                            </div>
                                            <div class="col-lg-8">
                                                <input name=nombre_comercial id="nombre_comercial" type="text" value="<?php echo (!empty($aviso_error)) ? set_value('nombre_comercial') : $cliente->nombre_comercial; ?>" class="form-control" >
                                        <?php echo form_error('nombre_comercial'); ?>
                                            </div>
                                        </div>
                                        <div class="row mb-4 align-items-center">
                                            <div class="col-lg-4">
                                                <label for="designationInput" class="fw-semibold">Población: </label>
                                            </div>
                                            <div class="col-lg-8">
                                                <div class="input-group">
                                                    <div class="input-group-text"><i class="feather-briefcase"></i></div>
                                        <input name="poblacion" id="poblacion" type="text" value="<?php echo (!empty($aviso_error)) ? set_value('poblacion') : $cliente->poblacion; ?>" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-4 align-items-center">
                                            <div class="col-lg-4">
                                                <label for="websiteInput" class="fw-semibold">Codigo Postal: </label>
                                            </div>
                                            <div class="col-lg-8">
                                                <div class="input-group">
                                                    <div class="input-group-text"><i class="feather-link"></i></div>
                                                    <input name="cp" id="cp" type="text" value="<?php echo (!empty($aviso_error)) ? set_value('cp') : $cliente->cp; ?>" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-4 align-items-center">
                                            <div class="col-lg-4">
                                                <label for="VATInput" class="fw-semibold">CIF: </label>
                                            </div>
                                            <div class="col-lg-8">
                                                <div class="input-group">
                                                    <div class="input-group-text"><i class="feather-dollar-sign"></i></div>
                                        <input name="cif" id="cif" type="text" value="<?php echo (!empty($aviso_error)) ? set_value('cif') : $cliente->cif; ?>" class="form-control">
                                        <?php echo form_error('cif'); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-4 align-items-center">
                                            <div class="col-lg-4">
                                                <label for="addressInput_2" class="fw-semibold">Address: </label>
                                            </div>
                                            <div class="col-lg-8">
                                                <div class="input-group">
                                                    <div class="input-group-text"><i class="feather-map-pin"></i></div>
                                                    <input name="direccion" id="direccion" type="text" value="<?php echo (!empty($aviso_error)) ? set_value('direccion') : $cliente->direccion; ?>" class="form-control">
                                                </div>
                                            </div>
                                        </div>
										<div class="row clearfix m-t-30">
                                <div class="col-lg-6 col-md-12">
                                    <div class="form-group">
                                        <button class="boton waves-effect" type="submit">Guardar cambios</button>
                                    </div>
                                </div>
                            </div>
						</form>	
						
						<form action="imagenUsuario" method="post" enctype="multipart/form-data">
    <input type="file" name="avatar">
    <button type="submit">Subir Imagen</button>
</form>

 
                                        </div>
                                    </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ Main Content ] end -->
        </div>

    </main>
    <!--! ================================================================ !-->
    <!--! Footer Script !-->
    <!--! ================================================================ !-->
    <!--! BEGIN: Vendors JS !-->
    <script src="https://app.obbak.es/assets/vendors/js/vendors.min.js"></script>
    <!-- vendors.min.js {always must need to be top} -->
    <script src="https://app.obbak.es/assets/vendors/js/select2.min.js"></script>
    <script src="https://app.obbak.es/assets/vendors/js/select2-active.min.js"></script>
    <script src="https://app.obbak.es/assets/vendors/js/datepicker.min.js"></script>
    <script src="https://app.obbak.es/assets/vendors/js/lslstrength.min.js"></script>
    <!--! END: Vendors JS !-->
    <!--! BEGIN: Apps Init  !-->
    <script src="https://app.obbak.es/assets/js/common-init.min.js"></script>
    <script src="https://app.obbak.es/assets/js/customers-create-init.min.js"></script>
    <!--! END: Apps Init !-->
</body>

</html>


