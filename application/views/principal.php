<!DOCTYPE html>
<html lang="es">
<head>
	<title>
		<?php if( !empty( $titulo ) ){ echo $titulo; } else { echo "Obbak"; } ?>
	</title>
        <?php if( !empty( $descripcion ) ){ echo $descripcion; }?>
	<meta https-equiv="content-type" content="text/html; charset=utf-8"/>
	<meta https-equiv="X-UA-Compatible" content="IE=10" />
	<meta https-equiv="X-UA-Compatible" content="IE=8" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- twitter-bootstrap-wizard css -->
<link rel="stylesheet" href="https://app.obbak.es/assets/libs/twitter-bootstrap-wizard/prettify.css">
<!-- Bootstrap Css -->
<link href="https://app.obbak.es/assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
<!-- Icons Css -->
<link href="https://app.obbak.es/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
<!-- App Css-->
<link href="https://app.obbak.es/assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
    
    <link rel="icon" href="/img/favicon-obbak.png" sizes="32x32" />
	<link rel="icon" href="/img/icon" sizes="192x192" />
	<link rel="apple-touch-icon-precomposed" href="/img/icon" />
     
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-K6V7VTPX');</script>
<!-- End Google Tag Manager -->


</head>
	
		
        <?php 
            if($page == "inicio/principal-inicio") {
                $this->load->view('inicio/menu_home'); 
            } else if($page == "inicio/politica-privacidad" or $page == "inicio/terminos-uso" or $page == "" or $page == "inicio/recuperar_pass") {
                $this->load->view('inicio/menu_home_2'); 
            }
            
            $this->load->view($page); 
            if($page != "inicio/default_login") {
                
            }
            
           ?>
       
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-K6V7VTPX"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->

<!-- Bootstrap Css -->
<link href="https://app.obbak.es/assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
<!-- Icons Css -->
<link href="https://app.obbak.es/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
<!-- App Css-->
<link href="https://app.obbak.es/assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
<!-- twitter-bootstrap-wizard js -->
<script src="https://app.obbak.es/assets/libs/twitter-bootstrap-wizard/jquery.bootstrap.wizard.min.js"></script>

<script src="https://app.obbak.es/assets/libs/twitter-bootstrap-wizard/prettify.js"></script>

<!-- form wizard init -->
<script src="https://app.obbak.es/assets/js/pages/form-wizard.init.js"></script>

<script src="https://app.obbak.es/assets/js/app.js"></script>
</body>
</html>