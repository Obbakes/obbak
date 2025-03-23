<div class="navbar-wrapper">
    <nav class="navbar navbar-default navbar-scroll" role="navigation" style="padding: 0px 20px;">
        <div class="navbar-header page-scroll">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                            <span class="sr-only">Menú</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
            <a class="navbar-brand" href="http://www.obbak.com"><img src="<?php echo base_url();?>img/logo.png" width="180" height="61" alt=""/></a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav navbar-left">
                <li <?php echo ($opc=='ofertas')?'class="active"':''?>><a class="page-scroll" href="<?php echo base_url();?>anunciantes/ofertas">OFERTAS</a></li>
                <li <?php echo ($opc=='inscripciones')?'class="active"':''?>><a class="page-scroll" href="<?php echo base_url();?>anunciantes/inscripciones">MIS OFERTAS</a></li>
             </ul>
             <ul class="nav navbar-nav navbar-right">
                <li  class="nav-item dropdown <?php echo ($opc=='perfil')?'active':''?>">
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Mi perfil&nbsp;<i class="fa fa-caret-down"></i></a>
                    <div class="dropdown-menu" style="    ">
                      <a class="dropdown-item" href="<?php echo base_url();?>anunciantes/perfilEmpresa">Mi empresa</a><br>
                      <a class="dropdown-item" href="<?php echo base_url();?>anunciantes/perfilContactos">Contactos</a><br>
                      <a class="dropdown-item" href="<?php echo base_url();?>anunciantes/preferencias">Preferencias</a>
                      <a class="dropdown-item" href="<?php echo base_url();?>anunciantes/notificaciones">Notificaciones</a>
				</li>
                <li>
                    <a href="http://www.obbak.com">Salir&nbsp;<i class="fa fa-sign-out"></i></a>
                </li>
            </ul>
        </div>
    </nav>
</div>