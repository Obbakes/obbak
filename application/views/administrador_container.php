<html xmlns="http://www.w3.org/1999/xhtml" >
	<head>
		<title><?php if(!empty($titulo)){echo $titulo;}else{ echo "Bimads";}?></title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8">
		<meta http-equiv="last-modified" content="Mon, 14 Sep 2015 12:58:00 GMT" />
		<link rel="stylesheet" href="<?php echo base_url();?>css/estilo.css" type="text/css"/>
		<link rel="stylesheet" href="<?php echo base_url();?>ckeditor/contents.css" type="text/css"/>
		<link rel="stylesheet" href="<?php echo base_url();?>css/webslide.css" type="text/css" media="screen"/>
		<link rel="stylesheet" href="<?php echo base_url();?>css/jquery.ui.autocomplete.css" type="text/css" media="screen"/>
		<link rel="stylesheet" href="<?php echo base_url();?>css/jquery.datetimepicker.css" type="text/css" media="screen"/>
		<script type="text/javascript" src="<?php echo base_url();?>js/jquery-1.8.2.min.js"></script>
		<script type="text/javascript" src="<?php echo base_url();?>js/jquery.multiselect.js"></script>
		<script type="text/javascript" src="<?php echo base_url();?>js/back-to-top.js"></script>
		<script type="text/javascript" src="<?php echo base_url();?>js/slick.min.js"></script>
		<script type="text/javascript" src="<?php echo base_url();?>js/jquery.carouFredSel-6.0.4-packed.js"></script>
		<script type="text/javascript" src="<?php echo base_url();?>js/cambiarOpacidadImagenes.js"></script>
		<script type="text/javascript" src="<?php echo base_url();?>js/lightbox.js"></script>
		<script type="text/javascript" src="<?php echo base_url();?>js/jquery-ui.js"></script>
		<script type="text/javascript" src="<?php echo base_url();?>js/jquery.tinycarousel.min.js"></script>
		<script type="text/javascript" src="<?php echo base_url();?>js/jquery.html5-placeholder-shim.js"></script>
		<script type="text/javascript" src="<?php echo base_url();?>ckeditor/ckeditor.js"></script>
		<script type="text/javascript" src="<?php echo base_url();?>js/jquery.datetimepicker.js"></script>
<?php 
	function br2nl($string){ //Cambia los <br /> de los textos por saltos de l�nea en los textarea de los formularios
		return preg_replace('#<br\s*?/?>#i', "", $string);
	}
?> 
	</head>
	<body class="background" style="margin-top: 0px;font-family: calibri;background-color: rgb(231, 225, 225); border:none;" >
		<div id="main" style="background-color: #fff;">
			<?php $this->load->view("header_administrador"); ?>
			<div id="wrapper" style="border-top: 1px solid rgb(215, 215, 215);">
				<div class="adminGestion" id="main-content">
					<div id="centercontent_bg">
						<div class="item-page">
							<div align="center">
								<?php $this->load->view($page); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div id="bottom">
                    <div class="tg" style="background-color: rgb(62, 187, 213); height: 40px; color: white; text-align: center;border-top: 1px solid rgb(215, 215, 215);">
                            <p>&copy; Bimads S.L. - <a id="contact_link" href="<?php echo base_url();?>inicio/contacto">Contacto</a></p>
                    </div>
                </div>
	</body>
</html>