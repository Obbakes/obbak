<html xmlns="http://www.w3.org/1999/xhtml" >
	<head>
		<title><?php if(!empty($titulo)){echo $titulo;}else{ echo "Bimads";}?></title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=10" />
		<meta http-equiv="X-UA-Compatible" content="IE=8" />
		<meta http-equiv="last-modified" content="Mon, 14 Sep 2015 12:58:00 GMT" />
		<meta name="google-site-verification" content="DrdMfF5gItgMRzlF1WvVi7ijWr2JUb9Pj70tG1I6eqk" />
		<link rel="stylesheet" href="<?php echo base_url();?>css/estilo.css" type="text/css"/>
		<script type="text/javascript" src="<?php echo base_url();?>js/jquery-1.8.2.min.js"></script>
		<script type="text/javascript" src="<?php echo base_url();?>js/jquery.multiselect.js"></script>
		<script type="text/javascript" src="<?php echo base_url();?>js/back-to-top.js"></script>
		<script type="text/javascript" src="<?php echo base_url();?>js/slick.min.js"></script>
		<script type="text/javascript" src="<?php echo base_url();?>js/jquery.carouFredSel-6.0.4-packed.js"></script>
		<script type="text/javascript" src="<?php echo base_url();?>js/cambiarOpacidadImagenes.js"></script>
		<script type="text/javascript" src="<?php echo base_url();?>js/lightbox.js"></script>
		<link rel="stylesheet" href="<?php echo base_url();?>css/webslide.css" type="text/css" media="screen"/>
		<script type="text/javascript" src="<?php echo base_url();?>js/jquery.tinycarousel.min.js"></script>
		<script type="text/javascript" src="<?php echo base_url();?>js/jquery.html5-placeholder-shim.js"></script>

	</head>
	<body class="background" style="margin-top: 0px;font-family: calibri;background-color: rgb(231, 225, 225); border:none;" >
		<div style="width: 960px; margin: auto; position:relative;">
			<div id="main" style="background-color: #fff;">
				<?php $this->load->view("header_login"); ?>

				<div id="wrapper" style="border-top: 1px solid rgb(215, 215, 215);">
					<?php //$this->load->view("menu"); ?>
					<div id="main-content">
						<div id="centercontent_bg">
							<?php //$this->load->view("submenu"); ?>
							<div class="item-page">
								<div align="center">
									<?php $this->load->view($page); ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php $this->load->view("footer"); ?>
		</div>
	</body>
</html>