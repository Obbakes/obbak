<!doctype html>

<html class="no-js" lang="es">

<head>

<meta charset="utf-8">

<meta http-equiv="X-UA-Compatible" content="IE=Edge">

<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

<meta name="description" content="Responsive Bootstrap 4 and web Application ui kit.">



<title>Bimads | Dashboard</title>

<link rel="icon" href="favicon.ico" type="image/x-icon">

<!-- Favicon-->

<link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/bootstrap/css/bootstrap.min.css">



<link href="<?php echo base_url();?>assets/plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />

<!-- Custom Css -->

<link rel="stylesheet" href="<?php echo base_url();?>assets/css/style.min.css">
<link rel="stylesheet" href="<?php echo base_url();?>assets/css/main.css">
<link rel="stylesheet" href="<?php echo base_url();?>assets/css/medios.css">


<script src="//cdn.amcharts.com/lib/4/core.js"></script>
<script src="//cdn.amcharts.com/lib/4/charts.js"></script>
<script src="//cdn.amcharts.com/lib/4/themes/animated.js"></script>

<!-- Jquery Core Js -->

<script src="<?php echo base_url();?>assets/bundles/libscripts.bundle.js"></script> <!-- Lib Scripts Plugin Js -->

<script src="<?php echo base_url();?>assets/bundles/vendorscripts.bundle.js"></script> <!-- Lib Scripts Plugin Js -->

<script src="<?php echo base_url();?>assets/bundles/mainscripts.bundle.js"></script><!-- Custom Js -->

<script src="<?php echo base_url();?>assets/plugins/nouislider/nouislider.js"></script>

<script src="<?php echo base_url();?>assets/js/main.js"></script>
</head>



<body class="theme-blue default_gestor">



<!-- Page Loader -->

<div class="page-loader-wrapper">

    <div class="loader">

        <div class="m-t-30"><img class="zmdi-hc-spin" src="<?php echo base_url();?>assets/images/loader.svg" width="48" height="48" alt="Aero"></div>

        <p>Por favor espera...</p>

    </div>

</div>



<!-- Overlay For Sidebars -->

<div class="overlay"></div>



<!-- Main Search 

<div id="search">

    <button id="close" type="button" class="close btn btn-primary btn-icon btn-icon-mini btn-round">x</button>

    <form>

        <input type="search" value="" placeholder="Buscar..." />

        <button type="submit" class="btn btn-primary">Buscar</button>

    </form>

</div>

-->

<!-- Right Icon menu Sidebar -->

<div class="navbar-right ">

    <ul class="navbar-nav">

        <li>

            <!-- <a href="#search" class="main_search" title="Buscar..."><i class="zmdi zmdi-search"></i></a> -->

            <form action="">

                <input type="text" name="buscar" id="buscar" placeholder="Buscar...">

                <button type="submit" onclick="search();return false;"><i class="zmdi zmdi-search"></i></button>

            </form>
            <ul id="buscar_result" style="display:none">
            </ul>
        </li>
 

        

        <li>

            <a href="<?php echo base_url() . 'inicio/logout';?>" class="mega-menu cerrar-sesion" title="Sign Out">

                <i class="zmdi zmdi-power"></i>

                <span>Cerrar sesión</span>

            </a>

        </li>

    </ul>

</div>



<!-- Left Sidebar -->

<aside id="leftsidebar" class="sidebar">
 
    <div class="navbar-brand">

        <?php if(1==2){ ?><button class="btn-menu ls-toggle-btn" type="button"><i class="zmdi zmdi-menu"></i></button><?php } ?>

        <a href="<?php echo base_url();?>medios/home"><img src="<?php echo base_url();?>assets/images/medios/logo.png" width="328" alt="Bimads | Medios"></a>

    </div>

    <div class="menu">

        <ul class="list">
          

        <?php foreach($medios as $medio){?>

            <li class="medios">

                <div class="user-info">

                    <a class="image" alt="<?php echo $medio->nombre;?>" title="<?php echo $medio->nombre;?>" href="<?php echo base_url();?>medios/perfil?id_medio=<?php echo $medio->id_medio;?>"><img src="<?php echo base_url()."/".$medio->logo;?>" alt="User"></a>

 

                </div>

            </li>

              <?php }?>

             <li class="separador"></li>

            <li <?php echo ($opc==='home') ? 'class="active"':''?>><a class="page-scroll" href="<?php echo base_url();?>medios/home"><i class="mr-2 zmdi zmdi-view-dashboard"></i><span>Página principal</span></a></li>

             <li <?php echo ($opc==='perfil' || $opc==='medio') ? 'class="active"':''?>><a class="page-scroll" href="<?php echo base_url();?>medios/perfil"><i class="mr-2 zmdi zmdi-account"></i><span>Perfil</span></a>
            </li>

            <li <?php echo ($opc==='ofertas') ? 'class="active"':''?>><a class="page-scroll" href="<?php echo base_url();?>medios/ofertas"><i class="mr-2 zmdi zmdi-apps"></i><span>Ofertas</span></a></li>

            <li <?php echo ($opc==='permisos') ? 'class="active"':''?>><a class="page-scroll" href="<?php echo base_url();?>medios/permisos"><i class="mr-2 zmdi zmdi-lock"></i></i><span>Permisos</span></a></li>

            <li <?php echo ($opc==='clientes') ? 'class="active"':''?>><a class="page-scroll" href="<?php echo base_url();?>medios/clientes"><i class="mr-2 zmdi zmdi-accounts-alt"></i><span>Clientes</span></a></li>

        </ul>

    </div>

</aside>



<section class="content">

    <div class="body_scroll">

        <div class="block-header">

            <div class="row">

                <div class="col-lg-12 col-md-12 col-sm-12">

                    <h2><?php echo $h1; ?></h2>

                    <ul class="breadcrumb">

                        <li class="breadcrumb-item"><a href="/medios/home">Medios</a></li>

                        <li class="breadcrumb-item active"><?php echo $h1; ?></li>

                    </ul>

                    <button class="btn btn-primary btn-icon mobile_menu" type="button"><i class="zmdi zmdi-sort-amount-desc"></i></button>

                </div>

            </div>

        </div>

        <div class="container-fluid">

        <div class="row clearfix">

            <div class="col-lg-12 col-md-12 col-sm-12">
                <?php $this->load->view('avisos'); ?>
            </div>

        </div>




       
        <?php $this->load->view($page); ?>

    </div>

</section>



<footer id="contact" class="gray-section contact">

    <div class="container">

        <div class="row m-b-lg">

            <div class="col-lg-12 text-center">

                <div class="navy-line"></div>

                <span>Contacta con nosotros</span>

                <p>¿Tienes algo que contarnos? Escríbenos</p>

            </div>

        </div>



        <div class="row">

            <div class="col-lg-12 text-center">

                <a href="mailto:info@bimads.com" class="boton">E-mail</a>

                <p class="m-t-sm mt-3"> O síguenos en las redes sociales</p>

                <ul class="list-inline social-icon">

                    <li><a href="https://twitter.com/Bimadscom?lang=es" target="_blank"><i class="fa fa-twitter"></i></a></li>

                    <li><a href="https://www.facebook.com/bimadscom/" target="_blank"><i class="fa fa-facebook"></i></a> </li>

                    <li><a href="https://www.linkedin.com/company/bimads/" target="_blank"><i class="fa fa-linkedin"></i></a> </li>

                </ul>

            </div>

        </div>

        <div class="row">

            <div class="col-lg-12 text-center m-t-lg m-b-lg">

                <p class="avisoLegal">&copy; <?php echo date("Y"); ?> Bimads SL. Todos los derechos reservados.<br>

                    <a href="<?php echo base_url();?>inicio/privacidad">Pol&iacute;tica de Privacidad</a>  &nbsp;&nbsp;&nbsp;&nbsp;

                    <a href="<?php echo base_url();?>inicio/terminos">T&eacute;rminos de Uso</a>

                </p>

            </div>

        </div>

    </div>

</footer>





<script>

// Página ofertas, mostrar filtros

$(document).ready(function() { 



    $('#mostrarForm').on('click', 



        // Primer click

        function(e) { 

            $('#formAMostrar').addClass('mostrarForm');

            e.preventDefault();

        },

        

        // Segundo click

        function(e) { 

            $('#formAMostrar').toggleClass('mostrarForm');

            e.preventDefault();

        }



    );



});

function search(){
    $("#buscar_result").hide();
        $.ajax({
          method: "POST",
          url: "<?php echo base_url() . 'medios/buscar'; ?>",
          data: { buscar: $("#buscar").val() }
        })
          .done(function( msg ) {
            $("#buscar_result").html(msg);
        $("#buscar_result").show();
          });
}

   $(document).on('click', function(e) {
            if ($(e.target).closest("#buscar_result").length === 0 && $(e.target).closest("#buscar").length === 0) {  
                  $("#buscar_result").hide();
            }
        });


</script>



</body>

</html>