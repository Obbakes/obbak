        <ul class="list">
            <li>
                <?php $this->load->view('anunciantes/lateral_cliente'); ?>
            </li>
             <li <?php echo ($opc=='ofertas')?'class="active"':''?>><a class="page-scroll" href="<?php echo base_url();?>anunciantes/aplicativos"><i class="mr-2 zmdi zmdi-accounts-alt"></i> Aplicativos</a></li>
            <li <?php echo ($opc=='ofertas')?'class="active"':''?>><a class="page-scroll" href="<?php echo base_url();?>anunciantes/ofertas"><i class="mr-2 zmdi zmdi-accounts-alt"></i> Ofertas</a></li>
            <li <?php echo ($opc=='inscripciones')?'class="active"':''?>><a class="page-scroll" href="<?php echo base_url();?>anunciantes/inscripciones"><i class="mr-2 zmdi zmdi-accounts-alt"></i> Inscripciones</a></li>
            <li <?php echo ($opc==='perfil' || $opc==='perfilEmpresa' || $opc==='perfilContactos' || $opc==='preferencias' || $opc==='notificaciones') ? 'class="active"':''?>><a class="page-scroll" href="<?php echo base_url();?>anunciantes/perfil"><i class="mr-2 zmdi zmdi-accounts-alt"></i><span>Mi perfil</span></a>
                <ul>
                    <li <?php echo ($opc==='perfil') ? 'class="active"':''?>><a href="<?php echo base_url();?>anunciantes/perfil">Mi cuenta</a></li>
                	 <!--<li <?php echo ($opc==='perfilEmpresa') ? 'class="active"':''?>><a href="<?php echo base_url();?>anunciantes/perfilEmpresa">Mi empresa</a></li>
                    <li <?php echo ($opc==='perfilContactos') ? 'class="active"':''?>><a href="<?php echo base_url();?>anunciantes/perfilContactos">Contactos</a></li>
                    <li <?php echo ($opc==='preferencias') ? 'class="active"':''?>><a href="<?php echo base_url();?>anunciantes/preferencias">Preferencias</a></li>
                    <li <?php echo ($opc==='notificaciones') ? 'class="active"':''?>><a href="<?php echo base_url();?>anunciantes/notificaciones">Notificaciones</a></li>-->
                </ul>
            </li>

        </ul>
