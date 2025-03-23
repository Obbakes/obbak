<?php   

if (empty($ofertas)){?>

 <div class="ibox-title">
        <div class="alert alert-warning" role="alert">
            <strong>Error</strong> Ninguna oferta para mostrar
        </div>
    </div>

						<?php } else { ?>

							<div class="ofertas-list ofertas-anunciantes row">
								<?php foreach($ofertas as $oferta) { 
								
							 
 
								?>
								
								<div class="col-md-3 oferta oferta-<?=$oferta->id_oferta?>">
								
									<div class="sombreado text-center">
										<div class="afinidad">
                                            <div><span> <?php echo (int)$oferta->porc_inversion; ?>%</span></div>
                                            <div>Porcentaje de Inversión </div>
                                        </div>
										<a href="<?php echo base_url();?>anunciantes/oferta/<?=$oferta->id_oferta?>" target="_blank">
										<div class="oferta-img" style="background-image: url(<?=   base_url() . "/".$oferta->imagen?>);"><div class="oferta-img-overlay"></div></div>
										</a>
										<div class="oferta-info">
											<h4><?=$oferta->titulo?></h4>
											<div class="oferta-descripcion"><?php echo $oferta->descripcion; ?></div>
											<div class="oferta-fecha"><span>Duración producción </span><i class="bimico-calendar"></i><?php echo $oferta->duracion_camp; ?></div>
											<div class="bimico-arrow_up">
    <span>Rentabilidad estimada: </span>
    <span><?php echo htmlspecialchars($oferta->renta_esti, ENT_QUOTES, 'UTF-8'); ?></span>
    <span>%</span>
</div>
											<div class="oferta-fecha"><i class="bimico-calendar">pope</i><?php
if($oferta->fecha_fin_pub != ""){

$origin = new DateTimeImmutable($oferta->fecha_fin_pub);
$target = new DateTimeImmutable("now");
$diff = $target->diff($origin);

if($diff->m > 0){
 echo $diff->m . " meses " . $diff->d . " días "   ;
}else{
 echo $diff->d . " días " . $diff->h . " horas " . $diff->i . " minutos" ;
}

}



?></div>
											<div class="oferta-descripcion"><i class="bimico-sales"></i><?=number_format($oferta->precio_anterior, 0, ',', '.')?> €</div>
                                            <div class="oferta-precio pt-2"><span>Inversión total  </span><?=number_format($oferta->inversion, 0, ',', '.')?> €</div>
											<?php //number_format($oferta->coste_real, 0, ',', '.') ?>
											<?php //$oferta->envios ?>
										</div>
									</div>
									
								</div>
								
								<?php } ?>
							</div>
							<div class="paginacion">
								<?php if(isset($paginacion)) echo $paginacion; ?>
							</div>
 



<?php } ?>