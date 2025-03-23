<html xmlns="http://www.w3.org/1999/xhtml">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>Obbak</title>

    <link href="<?php echo base_url();?>css/home/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url();?>css/home/animate.css" rel="stylesheet">
    <link href="<?php echo base_url();?>css/home/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="<?php echo base_url();?>css/home/style.css" rel="stylesheet">
    
    <link rel="stylesheet" href="<?php echo base_url();?>css/home/plugins/iCheck/custom.css">
    <link rel="stylesheet" href="<?php echo base_url();?>css/home/plugins/chosen/bootstrap-chosen.css">
    <link rel="stylesheet" href="<?php echo base_url();?>css/home/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css">
    <link rel="stylesheet" href="<?php echo base_url();?>css/home/plugins/colorpicker/bootstrap-colorpicker.min.css">
    <link rel="stylesheet" href="<?php echo base_url();?>css/home/plugins/cropper/cropper.min.css">
    <link rel="stylesheet" href="<?php echo base_url();?>css/home/plugins/switchery/switchery.css">
    <link rel="stylesheet" href="<?php echo base_url();?>css/home/plugins/jasny/jasny-bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url();?>css/home/plugins/nouslider/jquery.nouislider.css">
    <link rel="stylesheet" href="<?php echo base_url();?>css/home/plugins/datapicker/datepicker3.css">
    <link rel="stylesheet" href="<?php echo base_url();?>css/home//plugins/ionRangeSlider/ion.rangeSlider.css">
    <link rel="stylesheet" href="<?php echo base_url();?>css/home/plugins/ionRangeSlider/ion.rangeSlider.skinFlat.css">
    <link rel="stylesheet" href="<?php echo base_url();?>css/home/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css">
    <link rel="stylesheet" href="<?php echo base_url();?>css/home/plugins/clockpicker/clockpicker.css">
    <link rel="stylesheet" href="<?php echo base_url();?>css/home/plugins/daterangepicker/daterangepicker-bs3.css">
    <link rel="stylesheet" href="<?php echo base_url();?>css/home/plugins/select2/select2.min.css">
    <link rel="stylesheet" href="<?php echo base_url();?>css/home/plugins/touchspin/jquery.bootstrap-touchspin.min.css">
    <link rel="stylesheet" href="<?php echo base_url();?>css/home/plugins/dualListbox/bootstrap-duallistbox.min.css">
    
<!-- Mainly scripts -->
<script src="<?php echo base_url();?>js/privada/jquery-3.3.1.min.js"></script>
<script src="<?php echo base_url();?>js/privada/bootstrap.min.js"></script>

</head>

<body id="page-top" class="landing-page no-skin-config">
    <?php $this->load->view('anunciantes/header_cliente'); ?>
    <?php $this->load->view($page); ?>
    <?php $this->load->view('inicio/footer_principal'); ?>

    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-P3LR7MB"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
</body>

</html>