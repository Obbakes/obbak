


   
    <h4>Medios</h4>
    <p>
    	<fieldset>
           <div id="filtro-medios" class="opciones">
           		<div id="detalle_medios">
        			<?php
                      if(!empty($medios)){
                    	$i = 0;
                    	foreach($medios as $medio){
                     ?>
                     
                     <label class="control " id="lbl-medios-<?php echo $medio->id_tipo_medio;?>-<?php echo $medio->id_medio;?>"><?php echo $medio->nombre; ?>
                          <input type="checkbox" checked="checked" id="chk-medios-<?php echo $medio->id_tipo_medio;?>-<?php echo $medio->id_medio;?>"
               				 name="nm-chk-filtro-medios-<?php echo $medio->id_tipo_medio;?>-<?php echo $medio->id_medio;?>">
                          <span class="checkmark"></span>
                    </label>
                    
                    
                

                <?php
    	               }
                 } 
                 ?>			
        	</div>
        </fieldset>
	</p>