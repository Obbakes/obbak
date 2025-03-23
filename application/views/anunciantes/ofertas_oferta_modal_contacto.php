<div class="modal fade" id="contacto<?= $oferta->id_oferta; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<form id="form-contacto" method="post" action="<?=base_url()?>anunciantes/contacto/<?=$oferta->id_oferta?>">
    			<div class="modal-header">
    				<h4 class="modal-title" id="myModalLabel">Contacto</h4>
    				<button type="button" data-dismiss="modal" class="close" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
        		</div>
    			<div class="ibox-content">
     	           	<div class="form-group">
                   		<label for="exampleFormControlTextarea1">D&eacute;janos tu mensaje</label>
                        <textarea class="form-control" name="mensaje" id="mensaje" rows="8" maxlength="500">Deseo m&aacute;s informaci&oacute;n acerca de esta oferta.</textarea>
                    </div>
                </div>
        		<div class="modal-footer">
        			<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          			<button type="button" class="btn btn-primary" id="btn-contacto-<?= $oferta->id_oferta?>" >Enviar</button>
               	</div>
    		</form>
    	</div>
    	
	</div>
</div>