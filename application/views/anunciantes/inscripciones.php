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
                        <?php includeFileWithVariables('partials/page-title.php', array('pagetitle' => 'Upzet' , 'title' => 'Dashboard')); ?>
                     <div class="row">

                <div class="header header-medios">
                    <h2 class="">Inversiones en Juegos</h2>
                </div>

                <div class="ibox-content">

                  <?php
                  if(!empty($inscripciones)){

                  ?>
                <div class="card">
                    <div class="body">
                    <div class="table-responsive">
                        <table id="tech-companies-1" class="table table-striped">
                            <thead>
                                <tr>

                                    <th>Oferta</th>
                                    <th>Fecha y hora de contratación</th>
                                    <th>Estado</th>
                                    <th>Descargar</th>
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
            <?php  } else {
            ?>

                <div id="inscripcionesSinResultados" class="card">
                    <div class="body">
                        <img src="data:image/png;base64,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" />
                        <span>Ninguna oferta para mostrar</span>
                    </div>
                    </div>
                </div>

            <?php
            	}
            ?>

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
	                <?php include 'partials/footer.php'; ?>
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
<?php
//para pasar al controlador
