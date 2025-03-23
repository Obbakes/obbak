<div class="modal fade" id="compra<?= $oferta->id_oferta; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<form id="form-compra" method="post" action="<?=base_url()?>anunciantes/inscribirseOferta/<?=$oferta->id_oferta?>">
    			<div class="modal-header">
    				<h4 class="modal-title" id="myModalLabel">Inscripci&oacute;n en oferta</h4>
    				<button type="button" data-dismiss="modal" class="close" aria-label="Close">
    					 <span aria-hidden="true">&times;</span>
    				</button>
    			</div>
    			<div class="ibox-content">
    				<div class="form-group">
    					<p>¿Quieres inscribirte a esta oferta?</p>
    					<p>Nos llegará un aviso de tu interés y nos pondremos en contacto contigo para tramitar la posible compra. No obliga a la contratación ni al pago.</p>
    				</div>
    			</div>
    				<div class="modal-footer">
            			<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
          			<button type="submit" class="btn btn-primary" id="btn-compra-<?= $oferta->id_oferta?>" >Aceptar</button>
                   	</div>
    		</form>
    	</div>
	</div>
</div>
