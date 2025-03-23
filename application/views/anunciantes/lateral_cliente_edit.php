
  	   <link rel="stylesheet" href="<?php echo base_url();?>css/privada/cropper/cropper.min.css">
	   <script src="<?php echo base_url();?>js/plugins/cropper/cropper.min.js"></script>
	   <script>
    	   $(document).ready(function(){
           	<?php if (!empty ($cliente->imagen)){?>
           		$('#btn-subir-imagen').hide();
           	<?php } else { ?>
           		$('#btn-borrar-imagen').hide();
           	<?php } ?>
    			 $('#btn-borrar-imagen').on('click', function(event) {	
    				 $.ajax('<?php echo base_url();?>anunciantes/imagenUsuarioBorrar', {
    		 	        method: 'POST',
    		 	        data: false,
    		 	        success: function (response) {
    		 	        	$('#avatar').attr('src','<?php echo base_url();?>img/cliente_default.png');
    		 	        	$('#btn-borrar-imagen').hide(); 
    		 	        	$('#btn-subir-imagen').show();
    		 	        	var boxTitle = document.getElementsByClassName('ibox-title');
    		                boxTitle[0].insertAdjacentHTML('beforebegin', response)
    		 	        },
    
    		 	        error: function () {
    		 	        	
    		 	        }
    		 		});	
    			 });
    		 });
		 
	   </script>
	   <nav class="navbar-default navbar-static-side text-center" role="navigation">
            <div class="sidebar-collapse ">
                <ul class="nav metismenu " id="side-menu ">
                    <li class="nav-header ">
                        <div class="dropdown profile-element ">
                            	<?php if (empty ($cliente->imagen)){?>
                            		<img id="avatar" class="img-circle" src="<?php echo base_url();?>img/cliente_default.png" alt="perfil"/>

                            	<?php } else {?>
                                	<img id="avatar" alt="imagen" class="img-circle" src="<?php echo base_url().$cliente->imagen;?>" />

                            	<?php } ?>
                            	
                            	<div id="btn-subir-imagen" <?php echo (!empty ($cliente->imagen)) ? 'style="display:none"':''; ?>>
                                    <label class="label" data-toggle="tooltip" title="Sube tu foto de usuario">
                                     	cambiar imagen
                                    	<input type="file" class="sr-only" id="input" name="image" accept="image/*">
                                    </label>
                                </div>
                                    
                          	    <div id="btn-borrar-imagen" <?php echo (empty ($cliente->imagen)) ? 'style="display:none"':''; ?>>
                                    <label class="label" data-toggle="tooltip" title="Elimina tu foto de usuario">
                                      	eliminar imagen
                                    </label>
                                </div>
    						<br />
    						<br />
                            <span class="clear">
                            	<span class="block m-t-xs"> 
                                	<strong class="font-bold"><?php echo $cliente->nombre_contacto.' '.$cliente->apellidos_contacto;?></strong>
                                </span> 
                                <span class="text-muted text-xs block"><?php echo $cliente->nombre;?></span> 
                            </span>
                        </div>
                        
    					<?php $this->load->view('anunciantes/perfil_modal_imagen'); ?>
                    </li>
                </ul>
            </div>
        </nav>
