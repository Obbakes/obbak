<section id="features" class="container services">
    <div class="row" style="height: 21px;"> </div>
</section>
 <script type="application/javascript">
    function desinscribirse(id_aplicativo) {
        if (!confirm('¿Quieres cancelar este aplicativo?'))
            return;
        document.location.href = '<?php echo base_url() . 'anunciantes/cancelarAplicativo/'; ?>' + id_aplicativo;
    }

 
</script>
<div id="wrapper" class="gray-bg">


    <div id="page-wrapper">
        <div class="wrapper wrapper-content">

            <?php $this->load->helper('form'); ?>
            <div class="ibox float-e-margins">

                <div class="header header-medios">
                    <h2 class="">Aplicativos</h2>
                </div>

              
      


                  <?php
                  if(!empty($aplicativos)){

                  ?>
                <div class="card">
                    <div class="body">
                    <div class="table-responsive">
                        <table id="inscripcionesTabla" class="table">
                            <thead>
                                <tr>
 <th>fecha Cita</th>
 
                                    <th>Dirección del centro</th>
                                   
                                
                                    <th>Edad</th>
                                    <th>seguro</th>
                                      <th>estado</th>
                                   <th> </th>
                                  
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($aplicativos as $aplicativo) { ?>
                                    <tr>
                                         <td>                                          
                                                <?php echo $aplicativo->Fecha_Cita; ?>                                      
                                        </td>
                                
                                      
                                           <td>                                          
                                                <?php echo $aplicativo->Localizacion; ?>                                      
                                        </td>
                                         
                                          <td>                                          
                                                <?php echo $aplicativo->Edad; ?>                                      
                                        </td>
                                          <td>                                          
                                                <?php echo $aplicativo->Dientes_4; ?>                                      
                                        </td>
                                          <td>
                                            <span style="color: <?php echo getEstadoColor($aplicativo->estado); ?>"><?php echo ($aplicativo->estado == "Uv0DxHFPPo4Dug1OsE7Qm7") ? 'Pendiente' : (($aplicativo->estado == "5GJux4xnG-4GQhxyPyi611") ? 'Realizado' : (($aplicativo->estado == 2) ? 'Diseño de Sonrisa' : 'Cancelada')); ?></span>
                                        </td>
                                        <td>
                                            <?php
                                            if($inscripcion->estado == 0){
                                            ?>
                                                <a href="javascript:desinscribirse(<?php echo $aplicativo->id_aplicativo; ?>);" alt="Cancelar" style="color: #212529; text-decoration: underline;">Cancelar</a>
                                                <?php
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    </div>
                </div>
            <?php  } else {
            ?>

                <div id="inscripcionesSinResultados" class="card">
                    <div class="body">
                        <img src="data:image/png;base64,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" />
                        <span>Ninguna oferta para mostrar</span>
                    </div>
                    </div>
                </div>

            <?php
            	}
            ?>

                    <!-- PAGINACION, REVISAR ANTONIO -->
                    <div class="paginacion">
                    	<?php echo $paginacion; ?>
                    </div>
                    <!--
                    <nav aria-label="...">
                        <ul class="pagination">
                            <li class="page-item disabled">
                                <a class="page-link" href="#" tabindex="-1">Previous</a>
                            </li>
                            <li class="page-item"><a class="page-link" href="#">1</a></li>
                            <li class="page-item active">
                                <a class="page-link" href="#">2 <span class="sr-only">(current)</span></a>
                            </li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item">
                                <a class="page-link" href="#">Next</a>
                            </li>
                        </ul>
                    </nav>
                    -->
                </div>
            </div>
        </div>
    </div>
</div>

<?php
//para pasar al controlador

function getEstadoColor($idEstado){
    switch ($idEstado) {
        case 0:
            return "#FECB90";
            break;
        case 1:
            return "#398AFF";
            break;
        case 1:
            return "#FC755E";
            break;

        default:
            return "#FC755E";
            break;
    }
}

