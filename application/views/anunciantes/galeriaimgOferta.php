<div class="modal-dialog">
	<div class="modal-content">
			<div class="modal-header">
				<h4 id="myModalLabel">Galeria de im&aacute;genes</h4>
        		<button class="close" aria-label="Close" type="button" data-dismiss="modal">
                  <span aria-hidden="true">&times;</span>
                </button>
				
			</div>
		<?php 
		if (empty($oferta->galeria_img)){
		?>
		   <div class="ibox-title">
                    <div class="alert alert-warning" role="alert">
                        <h4 class="alert-heading">Ninguna imagen para mostrar</h4>
                        <p></p>
                    </div>
                </div>
        <?php 
		} else {
		    $imagenes = explode(',', $oferta->galeria_img);
        ?>
		<div id="carouselIndicators" class="carousel slide" data-ride="carousel">
		<?php 
		
		    if(count($imagenes)>1){
		?>
  			<ol class="carousel-indicators">
  	 		<?php

                foreach($imagenes as $imagen){
            ?>
                   <li data-target="#carouselIndicators" data-slide-to="0" <?php if ($imagen === reset($imagenes)) echo 'class="active"';?>></li>                
            <?php 
                }	
            ?>  
            </ol>
         <?php 
		    }
         ?>
      
            <div class="carousel-inner">
  	 		<?php

			    if(!empty($imagenes)){
                    foreach($imagenes as $imagen){?>
                     <div class="item <?php if ($imagen === reset($imagenes)) echo ' active';?>">
        			 	<img class="d-block w-100" src="<?php echo base_url();?>images/ofertas/<?php echo $imagen; ?>" />
    				</div>
            <?php 
                    }			
                }
            ?>
  			</div>
  			<?php 
  			if (count($imagenes)>1){
  			?>
    			<a class="left carousel-control" href="#carouselIndicators" data-slide="prev">
         			<span class="glyphicon glyphicon-chevron-left"></span>
         			<span class="sr-only">Previous</span>
       			</a>
       			<a class="right carousel-control" href="#carouselIndicators" data-slide="next">
         			<span class="glyphicon glyphicon-chevron-right"></span>
         			<span class="sr-only">Next</span>
       			</a>
   			<?php 
  			}
   			?>
		</div>
		<?php 
		}
		?>
		<div class="modal-footer">
    			<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
    	</div>
	</div>
</div>
	