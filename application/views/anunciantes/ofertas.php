    <!-- Hover Rows -->
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="card">
                <div class="header header-medios">
                    <h2 class="text-lowercase text-capitalize">Ofertas <span>de Tratamiento</span></h2>
                </div>

                <?php $this->load->view('avisos'); ?>

                <div class="">
                    <div class="ibox">
                        <?php $this->load->view('anunciantes/partials/ofertas_cards'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- #END# Hover Rows -->
</div>

<?php
//codigo viejo
if(1==2){
    ?>
    <link rel="stylesheet" href="<?php echo base_url();?>css/privada/marketplace/marketplace.css" media="screen" type="text/css">
    <!-- JS -->
    <!--
    <script src="<?php echo base_url();?>js/plugins/marketplace/jquery.js"></script>
    <script src="<?php echo base_url();?>js/plugins/marketplace/modal.js"></script>
    -->
    <script src="<?php echo base_url();?>js/plugins/marketplace/spin.js"></script>
    <script src="<?php echo base_url();?>js/privada/anunciantes/ofertas.js"></script>
    <?php
    function is_medio_in_array($id, $array){
        foreach ($array as $element) {
            if ($element->id_medio == $id) {
                return true;
            }
        }
    }
    ?>
    	<div class="gray-bg lateral_oferta">
        	<div id="page-wrapper" class="gray-bg">
                <div class="wrapper wrapper-content">
                    <div class="ibox">
                    	<?php $this->load->view('avisos'); ?>
                    	<div class="ibox-title">
                    		<h5>Ofertas disponibles (<span id="ofertasDisponibles"></span>)</h5>
                    	    <div class="justify-content-end">
                                <button id="btn-filter" type="button" class="btn btn-primary" data-toggle="modal" data-target="#filterModal">
                                    <i class="fa fa-filter"></i>
                                    Filtrar
                                </button>
                            </div>
                    	</div>
                    	<div class="ibox-content">
                    	   <!--Filtros -->
                            <?php $this->load->view('anunciantes/ofertas_filtro'); ?>
                            <div class="wrapper wrapper-content">
            					<div class="row active-with-click">
                                    <?php
                                    if (!empty($ofertas) && count($ofertas) > 0) {
                                        foreach ($ofertas as $oferta) {
                                            ?>
                                            <!-- Oferta -->
                                            <?php
                                            $aux['oferta']=$oferta;
    										$aux['tiposMedioAimc']=$tiposMedioAimc;
    										$aux['tiposOfertaAimc']=$tiposOfertaAimc;
                                            $this->load->view('anunciantes/ofertas_oferta', $aux);
                                            ?>
                                    <?php
                                        }
                                    } else {
                                    ?>
                                      <!-- ESTADO VACIO -->
                                    <div class="ibox-title">
                                        <div class="alert alert-warning" role="alert">
                                            <h4 class="alert-heading">Ninguna oferta para mostrar</h4>
                                            <p></p>
                                        </div>
                                    </div>
                                    <?php
                                    }
                                    ?>
                        		</div>
            				</div>
            			</div>
           			</div>
    			</div>
    		</div>
    	</div>
    <?php
} ?>