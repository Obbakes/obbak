

<!---- Formulario ---->

<div class="nuevo-usuario perfil">  
  <form name="datosanunciante" id="contact" action="<?php echo base_url();?>anunciantes/datosanunciante/1" method="post">
    <img align="left" src="<?php echo base_url();?>img/icono_perfil.png" width="52" height="52" alt="Perfil"/>
    <h3 style="margin-top:12px;">TU PERFIL</h3>
    <div><?php echo validation_errors(); ?></div>
    <fieldset>
      <input placeholder="<?php echo $cliente->nombre;?>" type="text" required readonly>
    </fieldset>
    <fieldset>
      <input placeholder="<?php echo $cliente->cif;?>" type="text" required readonly>
    </fieldset>
    <fieldset>
      	<select class="selector" name="sector" tabindex="1" required>
				<option value="" disabled style="display:none" selected>Sector</option>
	      	<?php foreach($sectores as $sector){?>
		  		<option name="sector" value="<?php echo $sector->id_sector;?>"><?php echo $sector->sector; ?></option>	      	
	      	<?php } ?>
		</select>
    </fieldset>
    
    <fieldset class="coloc">
     
      <input type="text" onclick="location.href = '#'" class="expand" placeholder="¿En qué tipo de oferta estás interesado?" readonly>
  		<section>   
                     <div class="opciones">
                        <?php foreach($tipos_medio as $tipo){?>
	                    	<label class="control control--checkbox"><?php echo $tipo->tipo;?>
							  <input name="tipo_medio[]" type="checkbox" value="<?php echo $tipo->tipo; ?>"/>
	                          <div class="control__indicator"></div>
						  	</label>    
                        <?php } ?>
                    </div>           
  		</section>
    </fieldset>
    
      
    <fieldset class="coloc">
      <input type="text" onclick="location.href = '#'" class="expand" placeholder="¿En qué meses realizas campañas de publicidad?" readonly>

  <section>
                     <div class="opciones">
                        <label class="control control--checkbox">Enero
                          <input name="meses[]" type="checkbox" value="Enero" />
                          <div class="control__indicator"></div>
                        </label>
                        <label class="control control--checkbox">Febrero
                          <input name="meses[]" type="checkbox" value="Febrero"/>
                          <div class="control__indicator"></div>
                        </label>
                        <label class="control control--checkbox">Marzo
                          <input name="meses[]" type="checkbox" value="Marzo"/>
                          <div class="control__indicator"></div>
                        </label>
                        <label class="control control--checkbox">Abril
                          <input name="meses[]" type="checkbox" value="Abril"/>
                          <div class="control__indicator"></div>
                        </label>
                        <label class="control control--checkbox">Mayo
                          <input name="meses[]" type="checkbox" value="Mayo"/>
                          <div class="control__indicator"></div>
                        </label>
                        <label class="control control--checkbox">Junio
                          <input name="meses[]" type="checkbox" value="Junio"/>
                          <div class="control__indicator"></div>
                        </label>
                        <label class="control control--checkbox">Julio
                          <input name="meses[]" type="checkbox" value="Julio"/>
                          <div class="control__indicator"></div>
                        </label>
                        <label class="control control--checkbox">Agosto
                          <input name="meses[]" type="checkbox" value="Agosto"/>
                          <div class="control__indicator"></div>
                        </label>
                        <label class="control control--checkbox">Septiembre
                          <input name="meses[]" type="checkbox" value="Septiembre"/>
                          <div class="control__indicator"></div>
                        </label>
                        <label class="control control--checkbox">Octubre
                          <input name="meses[]" type="checkbox" value="Octubre"/>
                          <div class="control__indicator"></div>
                        </label>
                        <label class="control control--checkbox">Noviembre
                          <input name="meses[]" type="checkbox" value="Noviembre"/>
                          <div class="control__indicator"></div>
                        </label>
                        <label class="control control--checkbox">Diciembre
                          <input name="meses[]" type="checkbox" value="Diciembre"/>
                          <div class="control__indicator"></div>
                        </label>
                      </div>
           
  </section>
    </fieldset>        
    <fieldset>
      <button name="submit" type="submit" id="contact-submit" data-submit="...enviando">Guardar datos</button>
    </fieldset>  
    
  </form>
</div>

</body>
</html>