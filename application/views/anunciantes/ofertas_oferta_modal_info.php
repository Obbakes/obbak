<div class="modal fade" id="info<?php echo $oferta->id_oferta; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 id="myModalLabel">Informaci&oacute;n de la oferta</h4>
        		<button type="button" data-dismiss="modal" class="close" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
				
			</div>
			
        	 <div class="modal-header-modal">
    			<h4 class="modal-title" id="myModalLabel">Descripción de la oferta</h4>
        	</div>
    		<div class="ibox-content-modal">
                <?php echo $oferta->detalle; ?>
        	</div>
            <div class="modal-header-modal">
    			<h4 class="modal-title" id="myModalLabel">Condiciones de la oferta</h4>
        	</div>
    		<div class="ibox-content-modal">
    	    	<?php echo $oferta->condiciones; ?>
           	</div>
    		<div class="modal-footer">
    			<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
    		</div>
   		</div>
	</div>
</div>