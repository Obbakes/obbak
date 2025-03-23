<?php include 'partials/main.php'; ?>

<head>
    <?php includeFileWithVariables('partials/title-meta.php', array('title' => 'Dashboard')); ?>
    <!-- jvectormap -->
    <link href="https://app.obbak.es/assets/libs/jqvmap/jqvmap.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>
<?php include 'partials/head-css.php'; ?>
<?php include 'partials/body.php'; ?>

<!-- Begin page -->
<div id="layout-wrapper">
    <?php include 'partials/menu.php'; ?>
    <!-- ============================================================== -->
    <!-- Start right Content here -->
    <!-- ============================================================== -->
 <div class="main-content">
            <div class="page-content">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-4">Wizard with Progressbar</h4>
                            <div id="progrss-wizard" class="twitter-bs-wizard">
                                <ul class="twitter-bs-wizard-nav nav-justified">
                                    <li class="nav-item">
                                        <a href="#progress-seller-details" class="nav-link active" data-toggle="tab">
                                            <span class="step-number">01</span>
                                            <span class="step-title">Seller Details</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#progress-company-document" class="nav-link" data-toggle="tab">
                                            <span class="step-number">02</span>
                                            <span class="step-title">Company Document</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#progress-bank-detail" class="nav-link" data-toggle="tab">
                                            <span class="step-number">03</span>
                                            <span class="step-title">Bank Details</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#progress-confirm-detail" class="nav-link" data-toggle="tab">
                                            <span class="step-number">04</span>
                                            <span class="step-title">Confirm Detail</span>
                                        </a>
                                    </li>
                                </ul>
                                <div class="tab-content twitter-bs-wizard-tab-content">
                                    <div class="tab-pane active" id="progress-seller-details">
                                        <div class="row">
                                            <form id="miFormulario" method="post" action="<?php echo base_url();?>anunciantes/perfilinver/1" class="form-horizontal">
                                                <div class="col-xl-12">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <div class="row clearfix">
                                                                <div class="col-lg-6 col-md-12">
                                                                    <div class="form-group">
                                                                        <label class="control-label">Nombre</label>
                                                                        <input name="nombre" id="nombre" type="text" value="<?php echo (!empty($aviso_error)) ? set_value('nombre') : $cliente->nombre; ?>" class="form-control">
                                                                        <?php echo form_error('nombre'); ?>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-6 col-md-12">
                                                                    <div class="form-group">
                                                                        <label class="control-label">NIF</label>
                                                                        <input name="cif" id="cif" type="text" value="<?php echo (!empty($aviso_error)) ? set_value('cif') : $cliente->cif; ?>" class="form-control">
                                                                        <?php echo form_error('cif'); ?>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-6 col-md-12">
                                                                    <div class="form-group">
                                                                        <label class="control-label">Código Postal</label>
                                                                        <input name="cp" id="cp" type="text" value="<?php echo (!empty($aviso_error)) ? set_value('cp') : $cliente->cp; ?>" class="form-control">
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-6 col-md-12">
                                                                    <div class="form-group">
                                                                        <label class="control-label">Población</label>
                                                                        <input name="poblacion" id="poblacion" type="text" value="<?php echo (!empty($aviso_error)) ? set_value('poblacion') : $cliente->poblacion; ?>" class="form-control">
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-6 col-md-12">
                                                                    <div class="form-group">
                                                                        <label class="control-label">Teléfono</label>
                                                                        <input name="telefono" id="telefono" type="text" value="<?php echo (!empty($aviso_error)) ? set_value('telefono') : $cliente->telefono; ?>" class="form-control">
                                                                        <?php echo form_error('telefono'); ?>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-6 col-md-12">
                                                                    <div class="form-group">
                                                                        <label class="control-label">Dirección</label>
                                                                        <input name="direccion" id="direccion" type="text" value="<?php echo (!empty($aviso_error)) ? set_value('direccion') : $cliente->direccion; ?>" class="form-control">
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-6 col-md-12">
                                                                    <div class="form-group">
                                                                        <label class="control-label">Fecha Nacimiento</label>
                                                                        <input name="Fecha_nacimiento" id="Fecha_nacimiento" type="date" value="<?php echo (!empty($aviso_error)) ? set_value('Fecha_nacimiento') : $cliente->Fecha_nacimiento; ?>" class="form-control">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group d-grid flex-wrap gap-2 align-items-center">
                                                                    <div class="d-grid flex-wrap gap-2 align-items-center">
        <button  class="boton waves-effect" type="submit">Guardar cambios</button>
		    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="progress-company-document">
                                        <div class="row">
                                            <div class="col-xl-12">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <form method="post" action="<?php echo base_url();?>anunciantes/perfilinversor/1" class="form-horizontal">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-floating mb-3">
                                                                        <div class="form-group">
                                                                            <label class="control-label">¿Qué tipo de inversor eres? Es necesario que nos indiques cuál es tu perfil de inversor para que sepamos que comprendes perfectamente el alcance, los riesgos y las limitaciones de este tipo de inversión. Saber más sobre tipos de inversor</label>
                                                                            <div class="custom-checkbox-inline m-t-30">
<div class="custom-control custom-checkbox custom-checkbox-full">
    <input type="radio" class="custom-control-input" id="id_tipo_inver-si" value="1" onchange='$("#id_tipo_inver").removeClass("d-none")' name="id_tipo_inver" <?php if (isset($cliente->id_tipo_inver) && $cliente->id_tipo_inver == 1) {echo 'checked';} ?>
>
    <label class="custom-control-label" for="id_tipo_inver-si">Inversor con patrimonio</label>
</div>

                                                                                <div class="custom-control custom-checkbox custom-checkbox-full">
                                                                                    <input type="radio" class="custom-control-input" id="id_tipo_inver-no" value="0" onchange='$("#id_tipo_inver").addClass("d-none")' name="id_tipo_inver-no" <?php if (isset($cliente->id_tipo_inver) && $cliente->id_tipo_inver == 0) {echo 'checked';} ?>>
                                                                                    <label class="custom-control-label" for="id_tipo_inver-no">Auto-cualificado experimentado.</label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-floating mb-3">
                                                                            <label for="id_origen">Seleccione el origen:</label>
                                                                            <select name="id_origen" id="id_origen" class="form-control">
                                                                                <option value="">Seleccione una opción</option>
                                                                                <option value="1" <?php echo (!empty($aviso_error) && set_value('id_origen') == '1') || (isset($cliente->id_origen) && $cliente->id_origen == '1') ? 'selected' : ''; ?>>Web</option>
                                                                                <option value="2" <?php echo (!empty($aviso_error) && set_value('id_origen') == '2') || (isset($cliente->id_origen) && $cliente->id_origen == '2') ? 'selected' : ''; ?>>Otro</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="d-grid flex-wrap gap-2 align-items-center">
        <button class="boton waves-effect" type="submit">Guardar cambios</button>
    </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="progress-bank-detail">
                                        <div class="row justify-content-center">
                                            <div class="mb-3">
                                                <label class="form-label">
                                                    <p class="card-title-desc">TÉRMINOS Y CONDICIONES Lee atentamente los términos y condiciones de nuestra plataforma En esta plataforma vas a acceder a información confidencial, por eso debes entender perfectamente las condiciones antes de firmar. Acuerdo de confidencialidad - Disclaimer Información confidencial, tal como se utiliza en esta Plataforma, se refiere a cualquier información relacionada con los proyectos y las empresas vinculadas a los proyectos, incluyendo los directores, empleados y representantes de dichas empresas, así como información comercial, técnica y no técnica divulgada por Hulahoop al Receptor (tú, el usuario) que esté marcada o iniciada verbalmente o por escrito por Hulahoop como confidencial o propietaria o de alguna otra manera que indique su naturaleza confidencial, e incluirá, sin limitación: a. Guiones, libretos, imágenes, trailers, clips de películas, material no publicado y sinopsis de historias, demos, letras, maquetas, etc… b. Información financiera, presupuestos, modelos y proyecciones de ingresos. c. Contratos y acuerdos. d. Entidades participantes, individuos, nombres, títulos o marcas. e. Cualquier otra información designada como confidencial. El Receptor declara, garantiza y acuerda con Hulahoop que: No hará, reproducirá, difundirá ni de ninguna manera divulgará a ninguna persona, empresa o negocio, ninguna información confidencial. Toda la información y los materiales proporcionados por Hulahoop al Receptor seguirán siendo propiedad de Hulahoop (o de sus socios) y nada de lo contenido en este documento pretende otorgar al receptor ningún derecho o licencia con respecto a la información confidencial divulgada o a los derechos de propiedad intelectual correspondientes. El presente acuerdo se rige por sus propias reglas y, en su defecto, por las disposiciones de la legislación española relacionada. Para cualquier disputa que pueda surgir entre las partes en virtud de este acuerdo, las partes se someten a los juzgados y tribunales de la ciudad de Barcelona, renunciando expresamente a cualquier otra jurisdicción que pueda ser competente. Y en prueba de conformidad con todo lo expuesto, tú, el usuario, aceptas el presente acuerdo de confidencialidad.</p>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="mb-3">
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" id="invalidCheck" required>
                                                        <label class="form-check-label" for="invalidCheck">Agree to terms and conditions</label>
                                                        <div class="invalid-feedback">
                                                            You must agree before submitting.
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="progress-confirm-detail">
                                        <div class="row justify-content-center">
                                            <div class="col-lg-6">
                                                <div class="text-center">
                                                    <div class="mb-4">
                                                        <i class="mdi mdi-check-circle-outline text-success display-4"></i>
                                                    </div>
                                                    <div>
                                                        <h5>Confirm Detail</h5>
                                                        <p class="text-muted">If several languages coalesce, the grammar of the resulting</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <ul class="pager wizard twitter-bs-wizard-pager-link">
                                    <li class="previous"><a href="javascript: void(0);">Previous</a></li>
                                    <li class="next"><a href="javascript: void(0);">Next</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- END layout-wrapper -->
        <?php include 'partials/vendor-scripts.php'; ?>
        <!-- jquery.vectormap map -->
        <script src="https://app.obbak.es/assets/libs/jqvmap/jquery.vmap.min.js"></script>
        <script src="https://app.obbak.es/assets/libs/jqvmap/maps/jquery.vmap.usa.js"></script>
        <script src="https://app.obbak.es/assets/js/jquery.slimscroll.min.js"></script>
        <script src="https://app.obbak.es/assets/js/app.js"></script>
		    <!-- twitter-bootstrap-wizard css -->
    <link rel="stylesheet" href="https://app.obbak.es/assets/libs/twitter-bootstrap-wizard/prettify.css" type="text/css">

    <!-- Incluir Slimscroll -->
    <script src="https://app.obbak.es/assets/js/jquery.slimscroll.min.js"></script>

	</div>
</body>
</html>