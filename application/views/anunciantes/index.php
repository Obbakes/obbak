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
                        <h5 class="m-b-10">Juegos</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="https://app.obbak.es/anunciantes/ofertaspanel">Home</a></li>
                        <li class="breadcrumb-item">Juegos</li>
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
            </div>
            <!-- [ page-header ] end -->
            <!-- [ Main Content ] start -->
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
                    <div class="position-relative">
                        <div class="progress-label text-success border-success mb-3"> <!-- mb-3 en lugar de mb-2 -->
                            Porcentaje de inversión <?php echo number_format($oferta->porc_inversion, 0, ",", ".") ?>%
                        </div>
                    </div>                                    
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
                <div style="height: 20px;"></div> <!-- Espacio extra -->
        <!-- [ Footer ] start -->
		<?php include 'partials/footer.php'; ?> 
        <!-- [ Footer ] end -->			
		</div>		
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
        <!-- [ Footer ] start -->
		<?php include 'partials/footer.php'; ?> 
        <!-- [ Footer ] end -->
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
