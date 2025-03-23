<!DOCTYPE html>
<html lang="es">

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
    <link rel="shortcut icon" type="image/x-icon" href="http://localhost:8000/obbak/assets/images/favicon.ico" />
    <!--! END: Favicon-->
    <!--! BEGIN: Bootstrap CSS-->
    <link rel="stylesheet" type="text/css" href="http://localhost:8000/obbak/assets/css/bootstrap.min.css" />
    <!--! END: Bootstrap CSS-->
    <!--! BEGIN: Vendors CSS-->
    <link rel="stylesheet" type="text/css" href="http://localhost:8000/obbak/assets/vendors/css/vendors.min.css" />
    <link rel="stylesheet" type="text/css" href="http://localhost:8000/obbak/assets/vendors/css/daterangepicker.min.css" />
    <!--! END: Vendors CSS-->
    <!--! BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="http://localhost:8000/obbak/assets/css/theme.min.css" />
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
    <!--! ================================================================ !-->
    <!--! [Start] Navigation Manu !-->
    <!--! ================================================================ !-->
    <!--! ================================================================ !-->
    <!--! [End]  Navigation Manu !-->
    <!--! ================================================================ !-->
    <!--! ================================================================ !-->
    <!--! [Start] Header !-->
    <!--! ================================================================ !-->

    <!--! ================================================================ !-->
    <!--! [End] Header !-->
    <!--! ================================================================ !-->
    <!--! ================================================================ !-->
    <!--! [Start] Main Content !-->
    <!--! ================================================================ !-->
    <main class="nxl-container">
        <div class="nxl-content">
            <!-- [ page-header ] start -->
            <div class="page-header">
                <div class="page-header-left d-flex align-items-center">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Panel</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="http://localhost:8000/obbak/anunciantes/ofertaspanel">Home</a></li>
                        <li class="breadcrumb-item">Panel</li>
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
                    </div>
                    <div class="d-md-none d-flex align-items-center">
                        <a href="javascript:void(0)" class="page-header-right-open-toggle">
                            <i class="feather-align-right fs-20"></i>
                        </a>
                    </div>
                </div>
				<div class="row">
				<div style="height: 20px;"></div> <!-- Espacio extra -->
				</div>
            </div>
            <!-- [ page-header ] end -->
            <!-- [ Main Content ] start -->
            <div style="height: 20px;"></div> <!-- Espacio extra -->
			<div class="row">
					<div class="col-xl-3 col-sm-6">
                        <div class="card">

                             <div class="card-header d-flex align-items-center justify-content-between">
                                 <div class="d-flex gap-4 align-items-center">
                                 <div class="avatar-text avatar-lg bg-gray-200">
                                            <i class="bi bi-currency-euro"></i>
                                        </div>
                                     <a href="javascript:void(0);" class="fw-semibold text-dark">Importe Invertido</a>
                                         <div class="w-100 text-end">
                                         <span class="mb-3"><?= htmlspecialchars($inscripciones_total) ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
  
                    </div>
					<div class="col-xl-3 col-sm-6">
                        <div class="card">

                             <div class="card-header d-flex align-items-center justify-content-between">
                                 <div class="d-flex gap-4 align-items-center">
                                 <div class="avatar-text avatar-lg bg-gray-200">
                                            <i class="bi bi-controller"></i>
                                        </div>
                                     <a href="javascript:void(0);" class="fw-semibold text-dark">Juegos Invertidos</a>
                                         <div class="w-100 text-end">
                                         <span class="mb-3"><?= htmlspecialchars($numInscripciones) ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
  
                    </div>
					<div class="col-xl-3 col-sm-6">
                        <div class="card">

                             <div class="card-header d-flex align-items-center justify-content-between">
                                 <div class="d-flex gap-4 align-items-center">
                                 <div class="avatar-text avatar-lg bg-gray-200">
                                            <i class="feather-trending-up"></i>
                                        </div>
                                     <a href="javascript:void(0);" class="fw-semibold text-dark">Rentabiliad Media</a>
                                         <div class="w-100 text-end">
                                            <span class="mb-3"><?= htmlspecialchars($renta_med) ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
  
                    </div>
					<div class="col-xl-3 col-sm-6">
                        <div class="card">
                         
                             <div class="card-header d-flex align-items-center justify-content-between">
                                 <div class="d-flex gap-4 align-items-center">
                                 <div class="avatar-text avatar-lg bg-gray-200">
                                            <i class="feather-dollar-sign"></i>
                                        </div>
                                     <a href="javascript:void(0);" class="fw-semibold text-dark">Saldo</a>
                                         <div class="w-100 text-end">
                                            <span class="mb-3"><?php 
                                if (is_array($saldo)) {
                                    echo "<span class='text-danger'>Error: saldo es un array, revisar la función getSaldoPorCliente()</span>";
                                } else {
                                    echo number_format((float)$saldo, 2);
                                }
                                     ?></span>
                                        </div>
                                    </div>
                                </div>
                            
                        </div>
                    </div>                                        

			</div>	
			<div class="row">
                 <div class="col-12">
                        <div class="card stretch stretch-full">
                            <div class="card-body">
                                
                                <div class="row">
									<div class="col-lg-4">
                        <div class="card stretch stretch-full">
                            <div class="card-body p-0">
                                <div class="mb-4 border-bottom">
                                    <div class="p-4 d-flex align-items-start justify-content-between">
                                        <div>
                                            <div class="fs-12 text-success fw-semibold mb-2">Invertido</div>
                                            <h4 class="text-success mb-2"><?= htmlspecialchars($inscripciones_total) ?></h4>
                                            <div class="fs-12 text-muted text-truncate-1-line"></div>
                                        </div>
                                    </div>
                                    <div id="earnings-area-chart"></div>
                                </div>
                                <div>
                                    <div class="p-4 d-flex align-items-start justify-content-between">
                                        <div>
                                            <div class="fs-12 text-danger fw-semibold mb-2">Retiradas</div>
                                            <h4 class="text-danger mb-2"><?= htmlspecialchars($retiradas) ?></h4>
                                            <div class="fs-12 text-muted text-truncate-1-line"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                                    <div class="col-xxl-2 col-lg-4 col-md-6">
                                        <div class="card stretch stretch-full border border-dashed border-gray-5">
                                            <div class="card-body rounded-3 text-center">
                                                <i class="bi bi-envelope fs-3 text-primary"></i>
                                                <div class="fs-4 fw-bolder text-dark mt-3 mb-1">50,545</div>
                                                <p class="fs-12 fw-medium text-muted text-spacing-1 mb-0 text-truncate-1-line">Total Email</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xxl-2 col-lg-4 col-md-6">
                                        <div class="card stretch stretch-full border border-dashed border-gray-5">
                                            <div class="card-body rounded-3 text-center">
                                                <i class="bi bi-envelope-plus fs-3 text-warning"></i>
                                                <div class="fs-4 fw-bolder text-dark mt-3 mb-1">25,000</div>
                                                <p class="fs-12 fw-medium text-muted text-spacing-1 mb-0 text-truncate-1-line">Email Sent</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xxl-2 col-lg-4 col-md-6">
                                        <div class="card stretch stretch-full border border-dashed border-gray-5">
                                            <div class="card-body rounded-3 text-center">
                                                <i class="bi bi-envelope-check fs-3 text-success"></i>
                                                <div class="fs-4 fw-bolder text-dark mt-3 mb-1">20,354</div>
                                                <p class="fs-12 fw-medium text-muted text-spacing-1 mb-0 text-truncate-1-line">Emails Delivered</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xxl-2 col-lg-4 col-md-6">
                                        <div class="card stretch stretch-full border border-dashed border-gray-5">
                                            <div class="card-body rounded-3 text-center">
                                                <i class="bi bi-envelope-open fs-3 text-indigo"></i>
                                                <div class="fs-4 fw-bolder text-dark mt-3 mb-1">12,422</div>
                                                <p class="fs-12 fw-medium text-muted text-spacing-1 mb-0 text-truncate-1-line">Emails Opened</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>			 
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <h2 class="display-6 mb-0 text-center">Juegos</h2>
                        </div>
                    </div>
                </div>    
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">        
					<?php foreach($ofertas as $oferta) { ?>    
					<div class="p-4"> <!-- Aumentamos el margen inferior -->
						<div class="card shadow-none border oferta oferta-<?=$oferta->id_oferta?>">
							<a href="<?php echo base_url(); ?>anunciantes/oferta/<?=$oferta->id_oferta?>" target="_blank">
								<div class="p-4">
                                   
                </div>
									<div class="p-4">
                           <div class="h5"><?=$oferta->titulo?></div>
                                        <div class="card-img-top img-fluid">
                                            <img src="<?= base_url() . "/".$oferta->imagen?>" class="img-fluid" alt="Responsive image">
                                        </div>
                                        <hr>
										<div class="my-4 text-muted text-truncate-2-line"><?php echo $oferta->descripcion; ?></div>
										<hr>
                                        <div class="mdi mdi-point-of-sale font-size-16 align-middle text-primary me-2">
                                            <span>Rentabilidad estimada: </span>
                                            <span><?php echo htmlspecialchars($oferta->renta_esti, ENT_QUOTES, 'UTF-8'); ?></span>
                                            <span>%</span>
                                        </div>
                                        <div class="mdi mdi-calendar font-size-16 align-middle text-primary me-2"><span> Duración producción </span><i class="bimico-calendar"></i><?php
                                            if($oferta->fecha_fin_pub != ""){
                                                $origin = new DateTimeImmutable($oferta->fecha_fin_pub);
                                                $target = new DateTimeImmutable("now");
                                                $diff = $target->diff($origin);
                                                if($diff->m > 0){
                                                    echo $diff->m . " meses " . $diff->d . " días ";
                                                } else {
                                                    echo $diff->d . " días " . $diff->h . " horas " . $diff->i . " minutos";
                                                }
                                            }
                                        ?></div>
                                        <div class="mdi mdi-currency-eur font-size-16 align-middle text-primary me-2"><span> Inversión total </span><?=number_format($oferta->inversion, 0, ',', '.')?> €</div>
                                    </div>
							</a>
						</div>
					</div>    
					<?php } ?>
				</div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <h2 class="display-6 mb-0 text-center">Inversiones</h2>
                        </div>
                        <div class="row">
                            <div class="ibox-content">
                                <div class="card">
                                    <div class="body">
                    <div class="table-responsive">
                        <table id="tech-companies-1" class="table table-striped">
                            <thead>
                                <tr>

                                    <th>Juego</th>
                                    <th>Fecha y hora de contratación</th>
                                    <th>Estado</th>
                                    <th>Descarga</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($inscripciones as $inscripcion) { ?>
                                    <tr>
                                        <td>
                                            <a href="<?php echo base_url() . 'anunciantes/oferta/' . $inscripcion->id_oferta; ?>" target="_self" style="color: #212529;">
                                                <?php echo $inscripcion->titulo; ?>
                                            </a>
                                        </td>
                                        <td>
                                            <?php echo $inscripcion->fecha ?>
                                        </td>
                                        <td>
                                            <span style="color:"><?php echo ($inscripcion->estado == 0) ? 'Pendiente' : (($inscripcion->estado == 1) ? 'Autorizada' : (($inscripcion->estado == 2) ? 'Pagada' : 'Cancelada')); ?></span>
                                        </td>
                                        <td>
											<div class="flex-shrink-0  me-3 align-self-center">
                                                <div class="avatar-sm">
                                                    <div class="avatar-title bg-light rounded-circle text-primary font-size-20">
                                                        <i class="ri-file-pdf-fill"></i>
                                                    </div>
                                                </div>
                                            </div>
											<a href="http://localhost:8000/obbak/<?php echo $inscripcion->documento; ?>" download target="_blank" style="color: #212529;">
												Descargar contrato
											</a>
										</td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                                    </div>
                                </div>
                                <!-- PAGINACION, REVISAR ANTONIO -->
                                <div class="paginacion">
                                    <?php echo $paginacion; ?>
                                </div>
                                <!--
                                <nav aria-label="...">
                                    <ul class="pagination">
                                        <li class="page-item disabled">
                                            <a class="page-link" href="#" tabindex="-1">Previous</a>
                                        </li>
                                        <li class="page-item"><a class="page-link" href="#">1</a></li>
                                        <li class="page-item active">
                                            <a class="page-link" href="#">2 <span class="sr-only">(current)</span></a>
                                        </li>
                                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                                        <li class="page-item">
                                            <a class="page-link" href="#">Next</a>
                                        </li>
                                    </ul>
                                </nav>
                                -->
                            </div>
                        </div>
                    </div>
					<div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title mb-4">Latest Transaction</h4>

                                        <div class="table-responsive">
                                            <table class="table table-centered table-nowrap mb-0">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">Movimiento</th>
                                                        <th scope="col">Fecha</th>
                                                        <th scope="col">Importe</th>
                                                        <th scope="col">Juego</th>
                                                        <th scope="col">Amount</th>
                                                        <th scope="col">Status</th>
                                                        <th scope="col">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                <?php foreach($movimientos as $movimiento) { ?>
                                    <tr>
                                        <td>
                                          <?php echo $movimiento->tipo_mov ?>
                                        </td>
                                        <td>
                                            <?php echo $movimiento->fecha ?>
                                        </td>
                                        <td>
                                            <?php echo $movimiento->importe ?>
                                        </td>
                                        <td>
											<?php echo $movimiento->titulo ?>
										</td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <!-- end card-body -->
                                </div>
                                <!-- end card -->
                            </div>
                            <!-- end col -->
                        </div>
					<div style="height: 20px;"></div> <!-- Espacio extra -->	
                        <!-- end row -->
				</div>
			</div>	
		</div>
	<div style="height: 20px;"></div> <!-- Espacio extra -->	
	<?php include 'partials/footer.php'; ?> 
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
    <script src="http://localhost:8000/obbak/assets/vendors/js/vendors.min.js"></script>
    <!-- vendors.min.js {always must need to be top} -->
    <script src="http://localhost:8000/obbak/assets/vendors/js/daterangepicker.min.js"></script>
    <script src="http://localhost:8000/obbak/assets/vendors/js/apexcharts.min.js"></script>
    <script src="http://localhost:8000/obbak/assets/vendors/js/circle-progress.min.js"></script>
    <!--! END: Vendors JS !-->
    <!--! BEGIN: Apps Init  !-->
    <script src="http://localhost:8000/obbak/assets/js/common-init.min.js"></script>
    <script src="http://localhost:8000/obbak/assets/js/dashboard-init.min.js"></script>
    <!--! END: Apps Init !-->
    <!--! BEGIN: Theme Customizer  !-->
    <script src="http://localhost:8000/obbak/assets/js/theme-customizer-init.min.js"></script>
    <!--! END: Theme Customizer !-->
    
</body>

</html>