  <?php $this->load->view('anunciantes/index_oferta'); ?>
  <?php
//codigo viejo
if(1==2){
    ?>
<script>
    function inscribirse() {
      if (!confirm('¿Quieres inscribirte a esta oferta?'))
        return;
      document.location.href = '<?php echo base_url() . 'anunciantes / inscribirseOferta / ' . $oferta->id_oferta; ?>';
    }
  </script>
  <section id="features" class="container services">
    <div class="row" style="height: 21px;"></div>
  </section>

    <div id="wrapper" class="gray-bg">

      <div id="page-wrapper" class="gray-bg">
        <div class="wrapper wrapper-content">
          <div class="ibox float-e-margins">
            <?php $this->load->view('avisos'); ?>
            <div class="ibox-title">
              <h5>
                <?php echo $oferta->titulo;  ?>
              </h5>
              <div class="volver">
                  <a class="page-scroll" href="<?php echo ($nueva_inscripcion)? base_url().'anunciantes/ofertas' : base_url().'anunciantes/inscripciones';?>">&#60;&#60;&#32;Volver</a>
              </div>
            </div>
            <div class="ibox-content" style="text-align: left; padding-left: 70px;">
              <!--Descripción Oferta-->
              <div class="condDetalle">
                <h3>Descripción de la oferta.</h3>
                <p>
                  <?php echo $oferta->detalle;  ?>
                </p>
              </div>
              <!--Condiciones oferta-->
              <div class="condDetalle">
                <h3>Condiciones de la oferta.</h3>
                <p>
                  <?php echo $oferta->condiciones; ?>
                </p>
              </div>
              <!--Info-->
              <div class="condDetalle">
                <h3>Detalles de la oferta</h3>
                <ul>
                  <li>
                    <span class="desOferta">
                      <i class="iconO tituloOferta"><?php echo $oferta->titulo; ?>&nbsp;</i>
                      <?php echo $oferta->descripcion; ?>
                    </span>
                  </li>
                  <li>
                    <span class="desOferta">
                      <i class="iconO icon-ofert-limit">Fecha límite&nbsp;</i>
                      <?php echo (empty($oferta->fecha_fin_pub)) ? 'Hasta fin de existencias' : date_format(date_create($oferta->fecha_fin_pub), "d/m/Y"); ?>
                    </span>
                  </li>
                  <li>
                    <span class="desOferta">
                      <i class="iconO icon-ofert-time">Duración de la campaña&nbsp;</i>
                      <?php echo $oferta->duracion_camp; ?>
                    </span>
                  </li>
                  <li>
                    <span class="desOferta" style="border: none;">
                      <i class="iconO icon-ofert-before">Antes</i>
                      <strike> <?php echo number_format($oferta->precio_anterior, 2, ',', '.'); ?>€</strike>
                    </span>
                  </li>
                  <li>
                    <span class="desOferta">
                      <i class="iconO icon-ofert-discount">Descuento</i>
                      <?php echo intval($oferta->descuento); ?>%
                    </span>
                  </li>
                  <li>
                      <span class="desOferta">
                        <i class="iconO icon-ofert-discount">Precio final</i>
                        <?php echo number_format($oferta->precio_oferta, 2, ',', '.'); ?>€
                      </span>
                    </li>
                  <li>
                    <span class="desOferta">
                      <?php
                              if ($oferta->inscrito) {
                                  ?>
                      <i class="iconO">Ya estás inscrito</i>
                      <?php
                              } else if ((! empty($oferta->fecha_fin_pub) && $oferta->fecha_fin_pub < date('Y-m-d H:i:s'))) {
                                  ?>
                      <i class="iconO">CADUCADA</i>
                      <?php
                              } else {
                                  ?>
                      <i class="iconO" onclick="inscribirse();">INSCRIBIRSE</i>
                      <?php
                              }
                              ?>
                    </span>
                  </li>
                </ul>
              </div>
              <!--Condiciones Detalle-->
              <div class="condDetalle">
                <h3>Sobre el medio.</h3>
                <p>
                  <?php echo $oferta->descripcion_medio;  ?>
                </p>
                <a href="<?php echo $oferta->web_medio; ?>" target="_blank;">
                  <img class="imgLogoMedio" src="<?php echo base_url() . $oferta->logo_medio; ?>" title="<?php echo $oferta->medio . ' (' . $oferta->tipo . ')'; ?>" />
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <?php } ?> 