<div class="navbar-wrapper">
        <nav class="navbar navbar-default navbar-scroll" role="navigation">
            <div class="container">
                <div class="navbar-header page-scroll">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Menú</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="<?php echo base_url();?>inicio/#page-top"><img src="<?php echo base_url();?>/img/logo_bimads.png" width="180" height="61" alt=""/></a>
                </div>
                <div id="navbar" class="navbar-collapse collapse">
                    <ul class="nav navbar-nav navbar-right">
                        <li><a class="page-scroll" href="<?php echo base_url();?>inicio/#page-top">INICIO</a></li>
                        <li><a class="page-scroll" href="<?php echo base_url();?>inicio/#features">¿POR QUÉ BIMADS?</a></li>
                        <li><a class="page-scroll" href="<?php echo base_url();?>inicio/#testimonials">¿CÓMO FUNCIONA?</a></li>
                        <li><a class="page-scroll" href="<?php echo base_url();?>inicio/#team">EQUIO</a></li>
                        <li><a class="page-scroll" href="<?php echo base_url();?>inicio/#contact">CONTACTO</a></li>
                        <li><a class="page-scroll" href="<?php echo base_url();?>inicio/login">ACCEDER</a></li>
                    </ul>
                </div>
            </div>
        </nav>
        <?php 
        $this->load->view('avisos'); 
        ?>
</div>