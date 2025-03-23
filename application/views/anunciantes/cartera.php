<?php include 'partials/main.php'; ?>

    <head>
        
    <?php includeFileWithVariables('partials/title-meta.php', array('title' => 'Dashboard')); ?>
        
        <!-- jvectormap -->
        <link href="https://app.obbak.es/assets/libs/jqvmap/jqvmap.min.css" rel="stylesheet" />

        <?php include 'partials/head-css.php'; ?>

    </head>

    <?php include 'partials/body.php'; ?>

        <!-- Begin page -->
        <div id="layout-wrapper">

        <?php include 'partials/menu.php'; ?>

            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <?php includeFileWithVariables('partials/page-title.php', array('pagetitle' => 'Upzet', 'title' => 'Dashboard')); ?>
                 <div class="row">
                            <div class="col-xl-3 col-sm-6">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex text-muted">
                                            <div class="flex-shrink-0  me-3 align-self-center">
                                                <div class="avatar-sm">
                                                    <div class="avatar-title bg-light rounded-circle text-primary font-size-20">
                                                        <i class="ri-money-euro-circle-fill"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 overflow-hidden">
                                                <p class="mb-1">Total Invertido</p>
                                                <h5 class="mb-3"><?=$inscripciones_total?></h5>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- end card-body -->
                                </div>
                                <!-- end card -->
                            </div>
                            <!-- end col -->

                            <div class="col-xl-3 col-sm-6">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0  me-3 align-self-center">
                                                <div class="avatar-sm">
                                                    <div class="avatar-title bg-light rounded-circle text-primary font-size-20">
                                                        <i class="ri-steam-fill"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 overflow-hidden">
                                                <p class="mb-1">Juegos invertidos</p>
                                                <h5 class="mb-3"><?=$numInscripciones?></h5>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- end card-body -->
                                </div>
                                <!-- end card -->
                            </div>
                            <!-- end col -->

                            <div class="col-xl-3 col-sm-6">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex text-muted">
                                            <div class="flex-shrink-0  me-3 align-self-center">
                                                <div class="avatar-sm">
                                                    <div class="avatar-title bg-light rounded-circle text-primary font-size-20">
                                                        <i class="ri-percent-fill"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 overflow-hidden">
                                                <p class="mb-1">Rentabilidad</p>
                                                <h5 class="mb-3"><?=$renta_med?></h5>
                                            </div>
                                        </div>                                        
                                    </div>
                                    <!-- end card-body -->
                                </div>
                                <!-- end card -->
                            </div>
                            <!-- end col -->

                            <div class="col-xl-3 col-sm-6">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex text-muted">
                                            <div class="flex-shrink-0  me-3 align-self-center">
                                                <div class="avatar-sm">
                                                    <div class="avatar-title bg-light rounded-circle text-primary font-size-20">
                                                        <i class="ri-group-line"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 overflow-hidden">
                                                <p class="mb-1">Saldo en Cuenta</p>
                                                <h5 class="mb-3"><?php if (is_array($saldo)) {echo "Error: saldo es un array, revisar la función getSaldoPorCliente()";} else {echo number_format((float)$saldo, 2);}?></h5>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- end card-body -->
                                </div>
                                <!-- end card -->
                            </div>
                            <!-- end col -->
                        </div>
                        <!-- end row -->
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
											<a href="https://app.obbak.es/<?php echo $inscripcion->documento; ?>" download target="_blank" style="color: #212529;">
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
                        <!-- end row -->
                <!-- End Page-content -->
                <?php include 'partials/footer.php'; ?>
            </div>
            <!-- end main content-->
        </div>
	</div>	
        <!-- END layout-wrapper -->
        <?php include 'partials/right-sidebar.php'; ?>
        <?php include 'partials/vendor-scripts.php'; ?>
        <!-- apexcharts js -->
        <script src="https://app.obbak.es/assets/libs/apexcharts/apexcharts.min.js"></script>
        <!-- jquery.vectormap map -->
        <script src="https://app.obbak.es/assets/libs/jqvmap/jquery.vmap.min.js"></script>
        <script src="https://app.obbak.es/assets/libs/jqvmap/maps/jquery.vmap.usa.js"></script>
        <script src="https://app.obbak.es/assets/js/jquery.slimscroll.min.js"></script>
        <script src="https://app.obbak.es/assets/js/app.js"></script>
        <script src="https://app.obbak.es/assets/js/pages/dashboard.init.js"></script>		
        <!-- Chart JS -->
        <script src="https://app.obbak.es/assets/libs/chart.js/Chart.bundle.min.js"></script>
        <script src="https://app.obbak.es/assets/js/pages/chartjs.init.js"></script>
    </body>
</html>
