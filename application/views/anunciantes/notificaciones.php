<?php include 'partials/main.php'; ?>

<head>
    <?php includeFileWithVariables('partials/title-meta.php', array('title' => 'Dashboard')); ?>
	<!-- slick css -->
    <link href="https://app.obbak.es/assets/libs/slick-slider/slick/slick.css" rel="stylesheet" type="text/css" />
    <link href="https://app.obbak.es/assets/libs/slick-slider/slick/slick-theme.css" rel="stylesheet" type="text/css" />    
		<!-- Incluir Slimscroll -->
    <script src="https://app.obbak.es/assets/js/jquery.slimscroll.min.js"></script>
				
	<!-- Plugin Js-->
    <script src="https://app.obbak.es/assets/libs/apexcharts/apexcharts.min.js"></script>
    <!-- demo js-->
    <script src="https://app.obbak.es/assets/js/pages/apex.init.js"></script>
    <script src="https://app.obbak.es/assets/js/app.js"></script>
    <script src="https://app.obbak.es/assets/libs/slick-slider/slick/slick.min.js"></script>
    <script src="https://app.obbak.es/assets/js/pages/timline.init.js"></script>
    <!-- jvectormap -->
    <link href="https://app.obbak.es/assets/libs/jqvmap/jqvmap.min.css" rel="stylesheet" />
    <script src="https://app.obbak.es/assets/js/pages/dashboard.init.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
	<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>

<section id="features" class="container services">
    <div class="row" style="height: 21px;"> </div>
</section>

<script type="application/javascript">
    function recibirTodas() {
	   if (document.getElementById('recibir').checked){
           document.getElementById('newsletter').value = 1;
           if (document.getElementById('notifica_oferta_nueva').value==0){
               document.getElementById('notifica_oferta_nueva').value = 1;
           }
	   }
       document.getElementById('newsletter_select').disabled = document.getElementById('recibir').checked;
       document.getElementById('notifica_oferta_nueva_select').disabled = document.getElementById('recibir').checked;
    }

    $(document).ready(function() {

    	$('#newsletter_select').change( function(){
        	    $('#newsletter').val($(this).val());
    		});

    	$('#notifica_oferta_nueva_select').change( function(){
    	    $('#notifica_oferta_nueva').val($(this).val());
		});
    });
</script>
</head>
<?php include 'partials/head-css.php'; ?>
<?php include 'partials/body.php'; ?>

<!-- Begin page -->
<div id="layout-wrapper">
    <?php include 'partials/menu.php'; ?>
    <!-- ============================================================== -->
    <!-- Start right Content here -->
    <!-- ============================================================== -->
    <div class="main-content">
        <div class="page-content">
                <div class="row">


	<div id="wrapper" class="gray-bg">

    	<div id="page-wrapper" class="gray-bg">
            <div class="wrapper wrapper-content">
                <div class="ibox">
                	<?php $this->load->view('avisos'); ?>
                	<div class="ibox-title"><h5>Opciones de las notificaciones</h5></div>
                	<div class="ibox-content">
                		<form method="post" class="form-horizontal" action="<?php echo base_url() . 'anunciantes/notificaciones/1'; ?>">
                			<div class="form-group"><label class="form-check form-switchl">Recibir todas las notificaciones</label>
                				<div class="form-check form-switch">
									<input class="form-check form-switch" type="checkbox" id="switch1" checked switch="none">
                                            <label class="form-label" for="switch1" data-on-label="On"
                                                   data-off-label="Off"<?php if($notificaciones['recibir_todas']){ echo 'checked';} ?> id="recibir" name="recibir" onchange="javascript:recibirTodas();"></label>
                                </div>
                            </div>

                            <div class="form-group">
                            	<label class="col-sm-2 control-label">Newsletter</label>
                                <div class="col-sm-10">
                                	<select <?php if($notificaciones['recibir_todas']){ echo 'disabled';} ?> id="newsletter_select" class="form-control m-b" name="newsletter_select">
                                    	<option value="1" <?php if($notificaciones['newsletter']==1){echo 'selected';}?>>Sí</option>
                                    	<option value="0" <?php if($notificaciones['newsletter']==0){echo 'selected';}?>>No</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                            	<label class="col-sm-2 control-label">Oferta Nueva Publicada</label>
                                <div class="col-sm-10">
                                	<select <?php if($notificaciones['recibir_todas']){ echo 'disabled';} ?> id="notifica_oferta_nueva_select" class="form-control m-b" name="notifica_oferta_nueva_select">
                                		<option value="1" <?php if($notificaciones['notifica_oferta_nueva']==1){echo 'selected';}?>>Inmediatamente</option>
                                		<option value="2" <?php if($notificaciones['notifica_oferta_nueva']==2){echo 'selected';}?>>Agrupar alertas diariamente</option>
                                		<option value="0" <?php if($notificaciones['notifica_oferta_nueva']==0){echo 'selected';}?>>No recibir</option>
                                	</select>
                                </div>
                            </div>

                            <input type="hidden" id="newsletter" name="newsletter" value="<?php echo $notificaciones['newsletter'];?>" />
                            <input type="hidden" id="notifica_oferta_nueva" name="notifica_oferta_nueva" value="<?php echo $notificaciones['notifica_oferta_nueva'];?>" />

                            <div class="form-group interior_btn">
                                <div class="col-sm-4 col-sm-offset-2">
                                    <a class="btn btn-secondary" href=""<?php echo base_url() . 'anunciantes/notificaciones/0'; ?>"">Cancelar</a>
                                    <button class="btn btn-primary" type="submit">Guardar cambios</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
                </script>    
                <!-- Plugin Js-->
                <script src="https://app.obbak.es/assets/libs/apexcharts/apexcharts.min.js"></script>
                <!-- demo js-->
                <script src="https://app.obbak.es/assets/js/pages/apex.init.js"></script>
                <script src="https://app.obbak.es/assets/js/app.js"></script>
                <script src="https://app.obbak.es/assets/libs/slick-slider/slick/slick.min.js"></script>
                <script src="https://app.obbak.es/assets/js/pages/timline.init.js"></script>
				<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>