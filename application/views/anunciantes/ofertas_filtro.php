	<script src="<?php echo base_url();?>js/privada/anunciantes/ofertas_filtro.js"></script>
	
	<div class="modal fade" id="filterModal" tabindex="-1" role="dialog" aria-labelledby="filterModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
				<h4 id="myModalLabel">Filtros</h4>
                    <button type="button" data-dismiss="modal" class="close" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    <div>
                        <?php $this->load->view('anunciantes/ofertas_filtro_tipos'); ?>
                        <?php $this->load->view('anunciantes/ofertas_filtro_medios'); ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>