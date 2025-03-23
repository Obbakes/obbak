<?php include 'partials/main.php'; ?>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="" />
    <meta name="keyword" content="" />
    <meta name="author" content="flexilecode" />
    <!--! The above 6 meta tags *must* come first in the head; any other head content must come *after* these tags !-->
    <!--! BEGIN: Apps Title-->
    <title>Obbak || Panel</title>
    <!--! END:  Apps Title-->
    <!--! BEGIN: Favicon-->
    <link rel="shortcut icon" type="image/x-icon" href="https://app.obbak.es/assets/images/favicon.ico" />
    <!--! END: Favicon-->
    <!--! BEGIN: Bootstrap CSS-->
    <link rel="stylesheet" type="text/css" href="https://app.obbak.es/assets/css/bootstrap.min.css" />
    <!--! END: Bootstrap CSS-->
    <!--! BEGIN: Vendors CSS-->
    <link rel="stylesheet" type="text/css" href="https://app.obbak.es/assets/vendors/css/vendors.min.css" />
    <link rel="stylesheet" type="text/css" href="https://app.obbak.es/assets/vendors/css/daterangepicker.min.css" />
    <!--! END: Vendors CSS-->
    <!--! BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="https://app.obbak.es/assets/css/theme.min.css" />
    <!--! END: Custom CSS-->
    <!--! HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries !-->
    <!--! WARNING: Respond.js doesn"t work if you view the page via file: !-->
    <!--[if lt IE 9]>
			<script src="https:oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
			<script src="https:oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->

</head>

<!-- Begin page -->
    <?php include 'partials/menu.php'; ?>
<body>
    <!-- ============================================================== -->
    <!-- Start right Content here -->
    <!-- ============================================================== -->
<div class="nxl-container">
    <div class="nxl-content">
                <div class="row">    
					<div class="col-xxl-8 col-xl-6">
                        <h1 class="card-title"><?=$oferta->titulo?></h1>
                            <div class="card">
                                <div class="card-body">						
                                    <div id="carouselVideoExample" class="carousel slide carousel-fade" data-mdb-ride="carousel">
										<!-- Indicators -->
										<div class="carousel-indicators">
    <button type="button" data-mdb-target="#carouselVideoExample" data-mdb-slide-to="0" class="active"
      aria-current="true" aria-label="Slide 1"></button>
    <button type="button" data-mdb-target="#carouselVideoExample" data-mdb-slide-to="1" aria-label="Slide 2"></button>
    <button type="button" data-mdb-target="#carouselVideoExample" data-mdb-slide-to="2" aria-label="Slide 3"></button>
  </div>
										<!-- Inner -->
										<div class="carousel-inner">
    <!-- Slide 1 (Video) -->
    <div class="carousel-item active">
      <video class="d-block w-100" autoplay loop muted playsinline style="object-fit: cover; height: 500px;">
        <source src="https://app.obbak.es/images/sonrisa/2113040/video_1.mp4" type="video/mp4" />
      </video>
      <div class="carousel-caption d-none d-md-block">
        <h5>First Slide</h5>
        <p>Descripción del primer video.</p>
      </div>
    </div>

    <!-- Slide 2 (Imagen) -->
    <div class="carousel-item">
      <img src="https://app.obbak.es/images/sonrisa/2113040/imagen_2.png" class="d-block w-100" style="object-fit: cover; height: 500px;" alt="Imagen 2">
      <div class="carousel-caption d-none d-md-block">
        <h5>Second Slide</h5>
        <p>Descripción de la imagen.</p>
      </div>
    </div>

    <!-- Slide 3 (Video) -->
    <div class="carousel-item">
      <video class="d-block w-100" autoplay loop muted playsinline style="object-fit: cover; height: 500px;">
        <source src="https://mdbcdn.b-cdn.net/img/video/Agua-natural.mp4" type="video/mp4" />
      </video>
      <div class="carousel-caption d-none d-md-block">
        <h5>Third Slide</h5>
        <p>Descripción del tercer video.</p>
      </div>
    </div>
  </div>
										<!-- Controls -->
										<button class="carousel-control-prev" type="button" data-mdb-target="#carouselVideoExample" data-mdb-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
										<button class="carousel-control-next" type="button" data-mdb-target="#carouselVideoExample" data-mdb-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>
									</div>
								</div>
								<hr>
								<div class="card">
									<div class="card-body">
										<h5 class="h5"><?=$oferta->titulo?></h5>
										<h4 class="card-title">Información del Juego</h4>
										<p class="card-title-desc"></p>
									</div>
								</div>
								<div class="tab-content p-3">
									<div class="card border-top-0">
								<div class="card-header p-0">
                                <!-- Nav tabs -->
									<ul class="nav nav-tabs flex-wrap w-100 text-center customers-nav-tabs" id="myTab" role="tablist">
                                    <li class="nav-item flex-fill border-top" role="presentation">
                                        <a href="javascript:void(0);" class="nav-link active" data-bs-toggle="tab" data-bs-target="#overviewTab" role="tab">Overview</a>
                                    </li>
                                    <li class="nav-item flex-fill border-top" role="presentation">
                                        <a href="javascript:void(0);" class="nav-link" data-bs-toggle="tab" data-bs-target="#billingTab" role="tab">Billing</a>
                                    </li>
                                    <li class="nav-item flex-fill border-top" role="presentation">
                                        <a href="javascript:void(0);" class="nav-link" data-bs-toggle="tab" data-bs-target="#activityTab" role="tab">Activity</a>
                                    </li>
                                    <li class="nav-item flex-fill border-top" role="presentation">
                                        <a href="javascript:void(0);" class="nav-link" data-bs-toggle="tab" data-bs-target="#notificationsTab" role="tab">Notifications</a>
                                    </li>
                                    <li class="nav-item flex-fill border-top" role="presentation">
                                        <a href="javascript:void(0);" class="nav-link" data-bs-toggle="tab" data-bs-target="#connectionTab" role="tab">Connection</a>
                                    </li>
                                    <li class="nav-item flex-fill border-top" role="presentation">
                                        <a href="javascript:void(0);" class="nav-link" data-bs-toggle="tab" data-bs-target="#securityTab" role="tab">Security</a>
                                    </li>
                                </ul>
								</div>
								<div class="tab-content">
                                <div class="tab-pane fade show active p-4" id="overviewTab" role="tabpanel">
                                    <div class="about-section mb-5">
                                        <div class="mb-4 d-flex align-items-center justify-content-between">
                                            <h5 class="fw-bold mb-0">Profile About:</h5>
                                        </div>
                                        <?php echo $oferta->descripcion; ?>
                                    </div>
                                 </div>
                                <div class="tab-pane fade" id="billingTab" role="tabpanel">
                                     <!-- [Project Report] start -->
									<div class="col-xxl-12">
										<div class="card stretch stretch-full">
                            <div class="card-header">
                                <h5 class="card-title">Project Report</h5>
                                <div class="card-header-action">
                                    <div class="card-header-btn">
                                        <div data-bs-toggle="tooltip" title="Delete">
                                            <a href="javascript:void(0);" class="avatar-text avatar-xs bg-danger" data-bs-toggle="remove"> </a>
                                        </div>
                                        <div data-bs-toggle="tooltip" title="Refresh">
                                            <a href="javascript:void(0);" class="avatar-text avatar-xs bg-warning" data-bs-toggle="refresh"> </a>
                                        </div>
                                        <div data-bs-toggle="tooltip" title="Maximize/Minimize">
                                            <a href="javascript:void(0);" class="avatar-text avatar-xs bg-success" data-bs-toggle="expand"> </a>
                                        </div>
                                    </div>
                                    <div class="dropdown">
                                        <a href="javascript:void(0);" class="avatar-text avatar-sm" data-bs-toggle="dropdown" data-bs-offset="25, 25">
                                            <div data-bs-toggle="tooltip" title="Options">
                                                <i class="feather-more-vertical"></i>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body custom-card-action">
                                <div id="project-statistics-chart"></div>
                            </div>
                        </div>
									</div>
									<!-- [Project Report] end -->  
                                </div>
                                <div class="tab-pane fade" id="activityTab" role="tabpanel">
                                    <div class="recent-activity p-4 pb-0">
                                        <div class="mb-4 pb-2 d-flex justify-content-between">
                                            <h5 class="fw-bold">Recent Activity:</h5>
                                            <a href="javascript:void(0);" class="btn btn-sm btn-light-brand">View Alls</a>
                                        </div>
                                        <ul class="list-unstyled activity-feed">
                                            <li class="d-flex justify-content-between feed-item feed-item-success">
                                                <div>
                                                    <span class="text-truncate-1-line lead_date">Reynante placed new order <span class="date">[April 19, 2023]</span></span>
                                                    <span class="text">New order placed <a href="javascript:void(0);" class="fw-bold text-primary">#456987</a></span>
                                                </div>
                                                <div class="ms-3 d-flex gap-2 align-items-center">
                                                    <a href="javascript:void(0);" class="avatar-text avatar-sm" data-bs-toggle="tooltip" data-bs-trigger="hover" title="Make Read"><i class="feather feather-check fs-12"></i></a>
                                                    <a href="javascript:void(0);" class="avatar-text avatar-sm" data-bs-toggle="tooltip" data-bs-trigger="hover" title="View Activity"><i class="feather feather-eye fs-12"></i></a>
                                                    <a href="javascript:void(0);" class="avatar-text avatar-sm" data-bs-toggle="tooltip" data-bs-trigger="hover" title="More Options"><i class="feather feather-more-vertical"></i></a>
                                                </div>
                                            </li>
                                            <li class="d-flex justify-content-between feed-item feed-item-info">
                                                <div>
                                                    <span class="text-truncate-1-line lead_date">5+ friends join this group <span class="date">[April 20, 2023]</span></span>
                                                    <span class="text">Joined the group <a href="javascript:void(0);" class="fw-bold text-primary">"Duralux"</a></span>
                                                </div>
                                                <div class="ms-3 d-flex gap-2 align-items-center">
                                                    <a href="javascript:void(0);" class="avatar-text avatar-sm" data-bs-toggle="tooltip" data-bs-trigger="hover" title="Make Read"><i class="feather feather-check fs-12"></i></a>
                                                    <a href="javascript:void(0);" class="avatar-text avatar-sm" data-bs-toggle="tooltip" data-bs-trigger="hover" title="View Activity"><i class="feather feather-eye fs-12"></i></a>
                                                    <a href="javascript:void(0);" class="avatar-text avatar-sm" data-bs-toggle="tooltip" data-bs-trigger="hover" title="More Options"><i class="feather feather-more-vertical"></i></a>
                                                </div>
                                            </li>
                                            <li class="d-flex justify-content-between feed-item feed-item-secondary">
                                                <div>
                                                    <span class="text-truncate-1-line lead_date">Socrates send you friend request <span class="date">[April 21, 2023]</span></span>
                                                    <span class="text">New friend request <a href="javascript:void(0);" class="badge bg-soft-success text-success ms-1">Conform</a></span>
                                                </div>
                                                <div class="ms-3 d-flex gap-2 align-items-center">
                                                    <a href="javascript:void(0);" class="avatar-text avatar-sm" data-bs-toggle="tooltip" data-bs-trigger="hover" title="Make Read"><i class="feather feather-check fs-12"></i></a>
                                                    <a href="javascript:void(0);" class="avatar-text avatar-sm" data-bs-toggle="tooltip" data-bs-trigger="hover" title="View Activity"><i class="feather feather-eye fs-12"></i></a>
                                                    <a href="javascript:void(0);" class="avatar-text avatar-sm" data-bs-toggle="tooltip" data-bs-trigger="hover" title="More Options"><i class="feather feather-more-vertical"></i></a>
                                                </div>
                                            </li>
                                            <li class="d-flex justify-content-between feed-item feed-item-warning">
                                                <div>
                                                    <span class="text-truncate-1-line lead_date">Reynante make deposit $565 USD <span class="date">[April 22, 2023]</span></span>
                                                    <span class="text">Make deposit <a href="javascript:void(0);" class="fw-bold text-primary">$565 USD</a></span>
                                                </div>
                                                <div class="ms-3 d-flex gap-2 align-items-center">
                                                    <a href="javascript:void(0);" class="avatar-text avatar-sm" data-bs-toggle="tooltip" data-bs-trigger="hover" title="Make Read"><i class="feather feather-check fs-12"></i></a>
                                                    <a href="javascript:void(0);" class="avatar-text avatar-sm" data-bs-toggle="tooltip" data-bs-trigger="hover" title="View Activity"><i class="feather feather-eye fs-12"></i></a>
                                                    <a href="javascript:void(0);" class="avatar-text avatar-sm" data-bs-toggle="tooltip" data-bs-trigger="hover" title="More Options"><i class="feather feather-more-vertical"></i></a>
                                                </div>
                                            </li>
                                            <li class="d-flex justify-content-between feed-item feed-item-primary">
                                                <div>
                                                    <span class="text-truncate-1-line lead_date">New event are coming soon <span class="date">[April 23, 2023]</span></span>
                                                    <span class="text">Attending the event <a href="javascript:void(0);" class="fw-bold text-primary">"Duralux Event"</a></span>
                                                </div>
                                                <div class="ms-3 d-flex gap-2 align-items-center">
                                                    <a href="javascript:void(0);" class="avatar-text avatar-sm" data-bs-toggle="tooltip" data-bs-trigger="hover" title="Make Read"><i class="feather feather-check fs-12"></i></a>
                                                    <a href="javascript:void(0);" class="avatar-text avatar-sm" data-bs-toggle="tooltip" data-bs-trigger="hover" title="View Activity"><i class="feather feather-eye fs-12"></i></a>
                                                    <a href="javascript:void(0);" class="avatar-text avatar-sm" data-bs-toggle="tooltip" data-bs-trigger="hover" title="More Options"><i class="feather feather-more-vertical"></i></a>
                                                </div>
                                            </li>
                                            <li class="d-flex justify-content-between feed-item feed-item-info">
                                                <div>
                                                    <span class="text-truncate-1-line lead_date">5+ friends join this group <span class="date">[April 20, 2023]</span></span>
                                                    <span class="text">Joined the group <a href="javascript:void(0);" class="fw-bold text-primary">"Duralux"</a></span>
                                                </div>
                                                <div class="ms-3 d-flex gap-2 align-items-center">
                                                    <a href="javascript:void(0);" class="avatar-text avatar-sm" data-bs-toggle="tooltip" data-bs-trigger="hover" title="Make Read"><i class="feather feather-check fs-12"></i></a>
                                                    <a href="javascript:void(0);" class="avatar-text avatar-sm" data-bs-toggle="tooltip" data-bs-trigger="hover" title="View Activity"><i class="feather feather-eye fs-12"></i></a>
                                                    <a href="javascript:void(0);" class="avatar-text avatar-sm" data-bs-toggle="tooltip" data-bs-trigger="hover" title="More Options"><i class="feather feather-more-vertical"></i></a>
                                                </div>
                                            </li>
                                            <li class="d-flex justify-content-between feed-item feed-item-danger">
                                                <div>
                                                    <span class="text-truncate-1-line lead_date">New meeting joining are pending <span class="date">[April 23, 2023]</span></span>
                                                    <span class="text">Duralux meeting <a href="javascript:void(0);" class="badge bg-soft-warning text-warning ms-1">Join</a></span>
                                                </div>
                                                <div class="ms-3 d-flex gap-2 align-items-center">
                                                    <a href="javascript:void(0);" class="avatar-text avatar-sm" data-bs-toggle="tooltip" data-bs-trigger="hover" title="Make Read"><i class="feather feather-check fs-12"></i></a>
                                                    <a href="javascript:void(0);" class="avatar-text avatar-sm" data-bs-toggle="tooltip" data-bs-trigger="hover" title="View Activity"><i class="feather feather-eye fs-12"></i></a>
                                                    <a href="javascript:void(0);" class="avatar-text avatar-sm" data-bs-toggle="tooltip" data-bs-trigger="hover" title="More Options"><i class="feather feather-more-vertical"></i></a>
                                                </div>
                                            </li>
                                            <li class="d-flex justify-content-between feed-item feed-item-info">
                                                <div>
                                                    <span class="text-truncate-1-line lead_date">5+ friends join this group <span class="date">[April 20, 2023]</span></span>
                                                    <span class="text">Joined the group <a href="javascript:void(0);" class="fw-bold text-primary">"Duralux"</a></span>
                                                </div>
                                                <div class="ms-3 d-flex gap-2 align-items-center">
                                                    <a href="javascript:void(0);" class="avatar-text avatar-sm" data-bs-toggle="tooltip" data-bs-trigger="hover" title="Make Read"><i class="feather feather-check fs-12"></i></a>
                                                    <a href="javascript:void(0);" class="avatar-text avatar-sm" data-bs-toggle="tooltip" data-bs-trigger="hover" title="View Activity"><i class="feather feather-eye fs-12"></i></a>
                                                    <a href="javascript:void(0);" class="avatar-text avatar-sm" data-bs-toggle="tooltip" data-bs-trigger="hover" title="More Options"><i class="feather feather-more-vertical"></i></a>
                                                </div>
                                            </li>
                                            <li class="d-flex justify-content-between feed-item feed-item-secondary">
                                                <div>
                                                    <span class="text-truncate-1-line lead_date">Socrates send you friend request <span class="date">[April 21, 2023]</span></span>
                                                    <span class="text">New friend request <a href="javascript:void(0);" class="badge bg-soft-success text-success ms-1">Conform</a></span>
                                                </div>
                                                <div class="ms-3 d-flex gap-2 align-items-center">
                                                    <a href="javascript:void(0);" class="avatar-text avatar-sm" data-bs-toggle="tooltip" data-bs-trigger="hover" title="Make Read"><i class="feather feather-check fs-12"></i></a>
                                                    <a href="javascript:void(0);" class="avatar-text avatar-sm" data-bs-toggle="tooltip" data-bs-trigger="hover" title="View Activity"><i class="feather feather-eye fs-12"></i></a>
                                                    <a href="javascript:void(0);" class="avatar-text avatar-sm" data-bs-toggle="tooltip" data-bs-trigger="hover" title="More Options"><i class="feather feather-more-vertical"></i></a>
                                                </div>
                                            </li>
                                            <li class="d-flex justify-content-between feed-item feed-item-warning">
                                                <div>
                                                    <span class="text-truncate-1-line lead_date">Reynante make deposit $565 USD <span class="date">[April 22, 2023]</span></span>
                                                    <span class="text">Make deposit <a href="javascript:void(0);" class="fw-bold text-primary">$565 USD</a></span>
                                                </div>
                                                <div class="ms-3 d-flex gap-2 align-items-center">
                                                    <a href="javascript:void(0);" class="avatar-text avatar-sm" data-bs-toggle="tooltip" data-bs-trigger="hover" title="Make Read"><i class="feather feather-check fs-12"></i></a>
                                                    <a href="javascript:void(0);" class="avatar-text avatar-sm" data-bs-toggle="tooltip" data-bs-trigger="hover" title="View Activity"><i class="feather feather-eye fs-12"></i></a>
                                                    <a href="javascript:void(0);" class="avatar-text avatar-sm" data-bs-toggle="tooltip" data-bs-trigger="hover" title="More Options"><i class="feather feather-more-vertical"></i></a>
                                                </div>
                                            </li>
                                            <li class="d-flex justify-content-between feed-item feed-item-primary">
                                                <div>
                                                    <span class="text-truncate-1-line lead_date">New event are coming soon <span class="date">[April 23, 2023]</span></span>
                                                    <span class="text">Attending the event <a href="javascript:void(0);" class="fw-bold text-primary">"Duralux Event"</a></span>
                                                </div>
                                                <div class="ms-3 d-flex gap-2 align-items-center">
                                                    <a href="javascript:void(0);" class="avatar-text avatar-sm" data-bs-toggle="tooltip" data-bs-trigger="hover" title="Make Read"><i class="feather feather-check fs-12"></i></a>
                                                    <a href="javascript:void(0);" class="avatar-text avatar-sm" data-bs-toggle="tooltip" data-bs-trigger="hover" title="View Activity"><i class="feather feather-eye fs-12"></i></a>
                                                    <a href="javascript:void(0);" class="avatar-text avatar-sm" data-bs-toggle="tooltip" data-bs-trigger="hover" title="More Options"><i class="feather feather-more-vertical"></i></a>
                                                </div>
                                            </li>
                                        </ul>
                                        <a href="javascript:void(0);" class="d-flex align-items-center text-muted">
                                            <i class="feather feather-more-horizontal fs-12"></i>
                                            <span class="fs-10 text-uppercase ms-2 text-truncate-1-line">Load More</span>
                                        </a>
                                    </div>
                                    <hr>
                                    <div class="logs-history mb-0">
                                        <div class="px-4 mb-4 d-flex justify-content-between">
                                            <h5 class="fw-bold">Logs History</h5>
                                            <a href="javascript:void(0);" class="btn btn-sm btn-light-brand">View Alls</a>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead class="text-dark text-center border-top">
                                                    <tr>
                                                        <th class="text-start ps-4">Browser</th>
                                                        <th>IP</th>
                                                        <th>Time</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="text-center">
                                                    <tr>
                                                        <td class="fw-medium text-dark text-start ps-4">Chrome on Window</td>
                                                        <td><span class="text-muted">192.149.122.128</span></td>
                                                        <td>
                                                            <span class="text-muted"> <span class="d-none d-sm-inline-block">11:34 PM</span></span>
                                                        </td>
                                                        <td><i class="feather feather-check-circle text-success"></i></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-medium text-dark text-start ps-4">Mozilla on Window</td>
                                                        <td><span class="text-muted">186.188.154.225</span></td>
                                                        <td>
                                                            <span class="text-muted">Nov 20, 2023 <span class="d-none d-sm-inline-block">10:34 PM</span></span>
                                                        </td>
                                                        <td>
                                                            <a href="javascript:void(0);"><i class="feather feather-x text-danger"></i></a>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-medium text-dark text-start ps-4">Chrome on iMac</td>
                                                        <td><span class="text-muted">192.149.122.128</span></td>
                                                        <td>
                                                            <span class="text-muted">Oct 23, 2023 <span class="d-none d-sm-inline-block">04:16 PM</span></span>
                                                        </td>
                                                        <td>
                                                            <a href="javascript:void(0);"><i class="feather feather-x text-danger"></i></a>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-medium text-dark text-start ps-4">Mozilla on Window</td>
                                                        <td><span class="text-muted">186.188.154.225</span></td>
                                                        <td>
                                                            <span class="text-muted">Nov 20, 2023 <span class="d-none d-sm-inline-block">10:34 PM</span></span>
                                                        </td>
                                                        <td>
                                                            <a href="javascript:void(0);"><i class="feather feather-x text-danger"></i></a>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-medium text-dark text-start ps-4">Chrome on Window</td>
                                                        <td><span class="text-muted">192.149.122.128</span></td>
                                                        <td>
                                                            <span class="text-muted">Oct 23, 2023 <span class="d-none d-sm-inline-block">04:16 PM</span></span>
                                                        </td>
                                                        <td>
                                                            <a href="javascript:void(0);"><i class="feather feather-x text-danger"></i></a>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-medium text-dark text-start ps-4">Chrome on iMac</td>
                                                        <td><span class="text-muted">192.149.122.128</span></td>
                                                        <td>
                                                            <span class="text-muted">Oct 15, 2023 <span class="d-none d-sm-inline-block">11:41 PM</span></span>
                                                        </td>
                                                        <td>
                                                            <a href="javascript:void(0);"><i class="feather feather-x text-danger"></i></a>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-medium text-dark text-start ps-4">Mozilla on Window</td>
                                                        <td><span class="text-muted">186.188.154.225</span></td>
                                                        <td>
                                                            <span class="text-muted">Oct 13, 2023 <span class="d-none d-sm-inline-block">05:43 AM</span></span>
                                                        </td>
                                                        <td>
                                                            <a href="javascript:void(0);"><i class="feather feather-x text-danger"></i></a>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-medium text-dark text-start ps-4">Chrome on iMac</td>
                                                        <td><span class="text-muted">192.149.122.128</span></td>
                                                        <td>
                                                            <span class="text-muted">Oct 03, 2023 <span class="d-none d-sm-inline-block">04:12 AM</span></span>
                                                        </td>
                                                        <td>
                                                            <a href="javascript:void(0);"><i class="feather feather-x text-danger"></i></a>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="notificationsTab" role="tabpanel">
                                    <div class="table-responsive">
                                        <table class="table mb-0">
                                            <thead>
                                                <tr>
                                                    <th>Description</th>
                                                    <th class="wd-250 text-end">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <div class="fw-bold text-dark">Successful payments</div>
                                                        <small class="fs-12 text-muted">Receive a notification for every successful payment.</small>
                                                    </td>
                                                    <td class="text-end">
                                                        <div class="form-group select-wd-lg">
                                                            <select class="form-control" data-select2-selector="icon">
                                                                <option value="SMS" data-icon="feather-smartphone">SMS</option>
                                                                <option value="Push" data-icon="feather-bell">Push</option>
                                                                <option value="Email" data-icon="feather-mail" selected>Email</option>
                                                                <option value="Repeat" data-icon="feather-repeat">Repeat</option>
                                                                <option value="Deactivate" data-icon="feather-bell-off">Deactivate</option>
                                                                <option value="SMS+Push" data-icon="feather-smartphone">SMS + Push</option>
                                                                <option value="Email+Push" data-icon="feather-mail">Email + Push</option>
                                                                <option value="SMS+Email" data-icon="feather-smartphone">SMS + Email</option>
                                                                <option value="SMS+Push+Email" data-icon="feather-smartphone">SMS + Push + Email</option>
                                                            </select>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="fw-bold text-dark">Customer payment dispute</div>
                                                        <small class="fs-12 text-muted">Receive a notification if a payment is disputed by a customer and for dispute purposes. </small>
                                                    </td>
                                                    <td class="text-end">
                                                        <div class="form-group select-wd-lg">
                                                            <select class="form-control" data-select2-selector="icon">
                                                                <option value="SMS" data-icon="feather-smartphone">SMS</option>
                                                                <option value="Push" data-icon="feather-bell">Push</option>
                                                                <option value="Email" data-icon="feather-mail">Email</option>
                                                                <option value="Repeat" data-icon="feather-repeat">Repeat</option>
                                                                <option value="Deactivate" data-icon="feather-bell-off" selected>Deactivate</option>
                                                                <option value="SMS+Push" data-icon="feather-smartphone">SMS + Push</option>
                                                                <option value="Email+Push" data-icon="feather-mail">Email + Push</option>
                                                                <option value="SMS+Email" data-icon="feather-smartphone">SMS + Email</option>
                                                                <option value="SMS+Push+Email" data-icon="feather-smartphone">SMS + Push + Email</option>
                                                            </select>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="fw-bold text-dark">Refund alerts</div>
                                                        <small class="fs-12 text-muted">Receive a notification if a payment is stated as risk by the Finance Department. </small>
                                                    </td>
                                                    <td class="text-end">
                                                        <div class="form-group select-wd-lg">
                                                            <select class="form-control" data-select2-selector="icon">
                                                                <option value="SMS" data-icon="feather-smartphone">SMS</option>
                                                                <option value="Push" data-icon="feather-bell" selected>Push</option>
                                                                <option value="Email" data-icon="feather-mail">Email</option>
                                                                <option value="Repeat" data-icon="feather-repeat">Repeat</option>
                                                                <option value="Deactivate" data-icon="feather-bell-off">Deactivate</option>
                                                                <option value="SMS+Push" data-icon="feather-smartphone">SMS + Push</option>
                                                                <option value="Email+Push" data-icon="feather-mail">Email + Push</option>
                                                                <option value="SMS+Email" data-icon="feather-smartphone">SMS + Email</option>
                                                                <option value="SMS+Push+Email" data-icon="feather-smartphone">SMS + Push + Email</option>
                                                            </select>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="fw-bold text-dark">Invoice payments</div>
                                                        <small class="fs-12 text-muted">Receive a notification if a customer sends an incorrect amount to pay their invoice. </small>
                                                    </td>
                                                    <td class="text-end">
                                                        <div class="form-group select-wd-lg">
                                                            <select class="form-control" data-select2-selector="icon">
                                                                <option value="SMS" data-icon="feather-smartphone">SMS</option>
                                                                <option value="Push" data-icon="feather-bell">Push</option>
                                                                <option value="Email" data-icon="feather-mail" selected>Email</option>
                                                                <option value="Repeat" data-icon="feather-repeat">Repeat</option>
                                                                <option value="Deactivate" data-icon="feather-bell-off">Deactivate</option>
                                                                <option value="SMS+Push" data-icon="feather-smartphone">SMS + Push</option>
                                                                <option value="Email+Push" data-icon="feather-mail">Email + Push</option>
                                                                <option value="SMS+Email" data-icon="feather-smartphone">SMS + Email</option>
                                                                <option value="SMS+Push+Email" data-icon="feather-smartphone">SMS + Push + Email</option>
                                                            </select>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="fw-bold text-dark">Rating reminders</div>
                                                        <small class="fs-12 text-muted">Send an email reminding me to rate an item a week after purchase </small>
                                                    </td>
                                                    <td class="text-end">
                                                        <div class="form-group select-wd-lg">
                                                            <select class="form-control" data-select2-selector="icon">
                                                                <option value="SMS" data-icon="feather-smartphone">SMS</option>
                                                                <option value="Push" data-icon="feather-bell">Push</option>
                                                                <option value="Email" data-icon="feather-mail">Email</option>
                                                                <option value="Repeat" data-icon="feather-repeat">Repeat</option>
                                                                <option value="Deactivate" data-icon="feather-bell-off" selected>Deactivate</option>
                                                                <option value="SMS+Push" data-icon="feather-smartphone">SMS + Push</option>
                                                                <option value="Email+Push" data-icon="feather-mail">Email + Push</option>
                                                                <option value="SMS+Email" data-icon="feather-smartphone">SMS + Email</option>
                                                                <option value="SMS+Push+Email" data-icon="feather-smartphone">SMS + Push + Email</option>
                                                            </select>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="fw-bold text-dark">Item update notifications</div>
                                                        <small class="fs-12 text-muted">Send an email when an item I've purchased is updated </small>
                                                    </td>
                                                    <td class="text-end">
                                                        <div class="form-group select-wd-lg">
                                                            <select class="form-control" data-select2-selector="icon">
                                                                <option value="SMS" data-icon="feather-smartphone">SMS</option>
                                                                <option value="Push" data-icon="feather-bell">Push</option>
                                                                <option value="Email" data-icon="feather-mail">Email</option>
                                                                <option value="Repeat" data-icon="feather-repeat">Repeat</option>
                                                                <option value="Deactivate" data-icon="feather-bell-off">Deactivate</option>
                                                                <option value="SMS+Push" data-icon="feather-smartphone">SMS + Push</option>
                                                                <option value="Email+Push" data-icon="feather-mail">Email + Push</option>
                                                                <option value="SMS+Email" data-icon="feather-smartphone">SMS + Email</option>
                                                                <option value="SMS+Push+Email" data-icon="feather-smartphone" selected>SMS + Push + Email</option>
                                                            </select>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="fw-bold text-dark">Item comment notifications</div>
                                                        <small class="fs-12 text-muted">Send me an email when someone comments on one of my items </small>
                                                    </td>
                                                    <td class="text-end">
                                                        <div class="form-group select-wd-lg">
                                                            <select class="form-control" data-select2-selector="icon">
                                                                <option value="SMS" data-icon="feather-smartphone">SMS</option>
                                                                <option value="Push" data-icon="feather-bell">Push</option>
                                                                <option value="Email" data-icon="feather-mail">Email</option>
                                                                <option value="Repeat" data-icon="feather-repeat">Repeat</option>
                                                                <option value="Deactivate" data-icon="feather-bell-off">Deactivate</option>
                                                                <option value="SMS+Push" data-icon="feather-smartphone">SMS + Push</option>
                                                                <option value="Email+Push" data-icon="feather-mail">Email + Push</option>
                                                                <option value="SMS+Email" data-icon="feather-smartphone" selected>SMS + Email</option>
                                                                <option value="SMS+Push+Email" data-icon="feather-smartphone">SMS + Push + Email</option>
                                                            </select>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="fw-bold text-dark">Team comment notifications</div>
                                                        <small class="fs-12 text-muted">Send me an email when someone comments on one of my team items </small>
                                                    </td>
                                                    <td class="text-end">
                                                        <div class="form-group select-wd-lg">
                                                            <select class="form-control" data-select2-selector="icon">
                                                                <option value="SMS" data-icon="feather-smartphone">SMS</option>
                                                                <option value="Push" data-icon="feather-bell">Push</option>
                                                                <option value="Email" data-icon="feather-mail">Email</option>
                                                                <option value="Repeat" data-icon="feather-repeat">Repeat</option>
                                                                <option value="Deactivate" data-icon="feather-bell-off">Deactivate</option>
                                                                <option value="SMS+Push" data-icon="feather-smartphone">SMS + Push</option>
                                                                <option value="Email+Push" data-icon="feather-mail" selected>Email + Push</option>
                                                                <option value="SMS+Email" data-icon="feather-smartphone">SMS + Email</option>
                                                                <option value="SMS+Push+Email" data-icon="feather-smartphone">SMS + Push + Email</option>
                                                            </select>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="fw-bold text-dark">Item review notifications</div>
                                                        <small class="fs-12 text-muted">Send me an email when my items are approved or rejected </small>
                                                    </td>
                                                    <td class="text-end">
                                                        <div class="form-group select-wd-lg">
                                                            <select class="form-control" data-select2-selector="icon">
                                                                <option value="SMS" data-icon="feather-smartphone">SMS</option>
                                                                <option value="Push" data-icon="feather-bell">Push</option>
                                                                <option value="Email" data-icon="feather-mail">Email</option>
                                                                <option value="Repeat" data-icon="feather-repeat">Repeat</option>
                                                                <option value="Deactivate" data-icon="feather-bell-off" selected>Deactivate</option>
                                                                <option value="SMS+Push" data-icon="feather-smartphone">SMS + Push</option>
                                                                <option value="Email+Push" data-icon="feather-mail">Email + Push</option>
                                                                <option value="SMS+Email" data-icon="feather-smartphone">SMS + Email</option>
                                                                <option value="SMS+Push+Email" data-icon="feather-smartphone">SMS + Push + Email</option>
                                                            </select>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="fw-bold text-dark">Buyer review notifications</div>
                                                        <small class="fs-12 text-muted">Send me an email when someone leaves a review with their rating </small>
                                                    </td>
                                                    <td class="text-end">
                                                        <div class="form-group select-wd-lg">
                                                            <select class="form-control" data-select2-selector="icon">
                                                                <option value="SMS" data-icon="feather-smartphone">SMS</option>
                                                                <option value="Push" data-icon="feather-bell">Push</option>
                                                                <option value="Email" data-icon="feather-mail">Email</option>
                                                                <option value="Repeat" data-icon="feather-repeat">Repeat</option>
                                                                <option value="Deactivate" data-icon="feather-bell-off">Deactivate</option>
                                                                <option value="SMS+Push" data-icon="feather-smartphone" selected>SMS + Push</option>
                                                                <option value="Email+Push" data-icon="feather-mail">Email + Push</option>
                                                                <option value="SMS+Email" data-icon="feather-smartphone">SMS + Email</option>
                                                                <option value="SMS+Push+Email" data-icon="feather-smartphone">SMS + Push + Email</option>
                                                            </select>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="fw-bold text-dark">Expiring support notifications</div>
                                                        <small class="fs-12 text-muted">Send me emails showing my soon to expire support entitlements </small>
                                                    </td>
                                                    <td class="text-end">
                                                        <div class="form-group select-wd-lg">
                                                            <select class="form-control" data-select2-selector="icon">
                                                                <option value="SMS" data-icon="feather-smartphone">SMS</option>
                                                                <option value="Push" data-icon="feather-bell">Push</option>
                                                                <option value="Email" data-icon="feather-mail" selected>Email</option>
                                                                <option value="Repeat" data-icon="feather-repeat">Repeat</option>
                                                                <option value="Deactivate" data-icon="feather-bell-off">Deactivate</option>
                                                                <option value="SMS+Push" data-icon="feather-smartphone">SMS + Push</option>
                                                                <option value="Email+Push" data-icon="feather-mail">Email + Push</option>
                                                                <option value="SMS+Email" data-icon="feather-smartphone">SMS + Email</option>
                                                                <option value="SMS+Push+Email" data-icon="feather-smartphone">SMS + Push + Email</option>
                                                            </select>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="fw-bold text-dark">Daily summary emails</div>
                                                        <small class="fs-12 text-muted">Send me a daily summary of all items approved or rejected </small>
                                                    </td>
                                                    <td class="text-end">
                                                        <div class="form-group select-wd-lg">
                                                            <select class="form-control" data-select2-selector="icon">
                                                                <option value="SMS" data-icon="feather-smartphone">SMS</option>
                                                                <option value="Push" data-icon="feather-bell" selected>Push</option>
                                                                <option value="Email" data-icon="feather-mail">Email</option>
                                                                <option value="Repeat" data-icon="feather-repeat">Repeat</option>
                                                                <option value="Deactivate" data-icon="feather-bell-off">Deactivate</option>
                                                                <option value="SMS+Push" data-icon="feather-smartphone">SMS + Push</option>
                                                                <option value="Email+Push" data-icon="feather-mail">Email + Push</option>
                                                                <option value="SMS+Email" data-icon="feather-smartphone">SMS + Email</option>
                                                                <option value="SMS+Push+Email" data-icon="feather-smartphone">SMS + Push + Email</option>
                                                            </select>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <hr>
                                    <div class="notify-activity-section">
                                        <div class="px-4 mb-4 d-flex justify-content-between">
                                            <h5 class="fw-bold">Account Activity</h5>
                                            <a href="javascript:void(0);" class="btn btn-sm btn-light-brand">View Alls</a>
                                        </div>
                                        <div class="px-4">
                                            <div class="hstack justify-content-between p-4 mb-3 border border-dashed border-gray-3 rounded-1">
                                                <div class="hstack me-4">
                                                    <div class="avatar-text">
                                                        <i class="feather-message-square"></i>
                                                    </div>
                                                    <div class="ms-4">
                                                        <a href="javascript:void(0);" class="fw-bold mb-1 text-truncate-1-line">Someone comments on one of my items</a>
                                                        <div class="fs-12 text-muted text-truncate-1-line">If someone comments on one of your items, it's important to respond in a timely and appropriate manner.</div>
                                                    </div>
                                                </div>
                                                <div class="form-check form-switch form-switch-sm">
                                                    <label class="form-check-label fw-500 text-dark c-pointer" for="formSwitchComment"></label>
                                                    <input class="form-check-input c-pointer" type="checkbox" id="formSwitchComment">
                                                </div>
                                            </div>
                                            <div class="hstack justify-content-between p-4 mb-3 border border-dashed border-gray-3 rounded-1">
                                                <div class="hstack me-4">
                                                    <div class="avatar-text">
                                                        <i class="feather-briefcase"></i>
                                                    </div>
                                                    <div class="ms-4">
                                                        <a href="javascript:void(0);" class="fw-bold mb-1 text-truncate-1-line">Someone replies to my job posting</a>
                                                        <div class="fs-12 text-muted text-truncate-1-line">Great! It's always exciting to hear from someone who's interested in a job posting you've put out.</div>
                                                    </div>
                                                </div>
                                                <div class="form-check form-switch form-switch-sm">
                                                    <label class="form-check-label fw-500 text-dark c-pointer" for="formSwitchReplie"></label>
                                                    <input class="form-check-input c-pointer" type="checkbox" id="formSwitchReplie">
                                                </div>
                                            </div>
                                            <div class="hstack justify-content-between p-4 mb-3 border border-dashed border-gray-3 rounded-1">
                                                <div class="hstack me-4">
                                                    <div class="avatar-text">
                                                        <i class="feather-briefcase"></i>
                                                    </div>
                                                    <div class="ms-4">
                                                        <a href="javascript:void(0);" class="fw-bold mb-1 text-truncate-1-line">Someone mentions or follows me</a>
                                                        <div class="fs-12 text-muted text-truncate-1-line">If you received a notification that someone mentioned or followed you, take a moment to read it and understand what it means.</div>
                                                    </div>
                                                </div>
                                                <div class="form-check form-switch form-switch-sm">
                                                    <label class="form-check-label fw-500 text-dark c-pointer" for="formSwitchFollow"></label>
                                                    <input class="form-check-input c-pointer" type="checkbox" id="formSwitchFollow">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="connectionTab" role="tabpanel">
                                    <div class="development-connections p-4 pb-0">
                                        <div class="mb-4 d-flex align-items-center justify-content-between">
                                            <h5 class="fw-bold">Developement Connections:</h5>
                                            <a href="javascript:void(0);" class="btn btn-sm btn-light-brand">View Alls</a>
                                        </div>
                                        <div class="hstack justify-content-between p-4 mb-3 border border-dashed border-gray-3 rounded-1">
                                            <div class="hstack me-4">
                                                <div class="wd-40">
                                                    <img src="https://app.obbak.es/assets/images/brand/google-drive.png" class="img-fluid" alt="">
                                                </div>
                                                <div class="ms-4">
                                                    <a href="javascript:void(0);" class="fw-bold mb-1 text-truncate-1-line">Google Drive: Cloud Storage & File Sharing</a>
                                                    <div class="fs-12 text-muted text-truncate-1-line">Google's powerful search capabilities are embedded in Drive and offer speed, reliability, and collaboration. And features like Drive search chips help your team ...</div>
                                                </div>
                                            </div>
                                            <div class="form-check form-switch form-switch-sm">
                                                <label class="form-check-label fw-500 text-dark c-pointer" for="formSwitchGDrive"></label>
                                                <input class="form-check-input c-pointer" type="checkbox" id="formSwitchGDrive">
                                            </div>
                                        </div>
                                        <div class="hstack justify-content-between p-4 mb-3 border border-dashed border-gray-3 rounded-1">
                                            <div class="hstack me-4">
                                                <div class="wd-40">
                                                    <img src="https://app.obbak.es/assets/images/brand/dropbox.png" class="img-fluid" alt="">
                                                </div>
                                                <div class="ms-4">
                                                    <a href="javascript:void(0);" class="fw-bold mb-1 text-truncate-1-line">Dropbox: Cloud Storage & File Sharing</a>
                                                    <div class="fs-12 text-muted text-truncate-1-line">Dropbox brings everything—traditional files, cloud content, and web shortcuts—together in one place. ... Save and access your files from any device, and share ...</div>
                                                </div>
                                            </div>
                                            <div class="form-check form-switch form-switch-sm">
                                                <label class="form-check-label fw-500 text-dark c-pointer" for="formSwitchDropbox"></label>
                                                <input class="form-check-input c-pointer" type="checkbox" id="formSwitchDropbox" checked>
                                            </div>
                                        </div>
                                        <div class="hstack justify-content-between p-4 mb-3 border border-dashed border-gray-3 rounded-1">
                                            <div class="hstack me-4">
                                                <div class="wd-40">
                                                    <img src="https://app.obbak.es/assets/images/brand/github.png" class="img-fluid" alt="">
                                                </div>
                                                <div class="ms-4">
                                                    <a href="javascript:void(0);" class="fw-bold mb-1 text-truncate-1-line">GitHub: Where the world builds software</a>
                                                    <div class="fs-12 text-muted text-truncate-1-line">GitHub is where over 83 million developers shape the future of software, together. Contribute to the open source community, manage your Git repositories, ...</div>
                                                </div>
                                            </div>
                                            <div class="form-check form-switch form-switch-sm">
                                                <label class="form-check-label fw-500 text-dark c-pointer" for="formSwitchGitHub"></label>
                                                <input class="form-check-input c-pointer" type="checkbox" id="formSwitchGitHub" checked>
                                            </div>
                                        </div>
                                        <div class="hstack justify-content-between p-4 mb-3 border border-dashed border-gray-3 rounded-1">
                                            <div class="hstack me-4">
                                                <div class="wd-40">
                                                    <img src="https://app.obbak.es/assets/images/brand/gitlab.png" class="img-fluid" alt="">
                                                </div>
                                                <div class="ms-4">
                                                    <a href="javascript:void(0);" class="fw-bold mb-1 text-truncate-1-line">GitLab: The One DevOps Platform</a>
                                                    <div class="fs-12 text-muted text-truncate-1-line">GitLab helps you automate the builds, integration, and verification of your code. With SAST, DAST, code quality analysis, plus pipelines that enable ...</div>
                                                </div>
                                            </div>
                                            <div class="form-check form-switch form-switch-sm">
                                                <label class="form-check-label fw-500 text-dark c-pointer" for="formSwitchGitLab"></label>
                                                <input class="form-check-input c-pointer" type="checkbox" id="formSwitchGitLab">
                                            </div>
                                        </div>
                                        <div class="hstack justify-content-between p-4 mb-3 border border-dashed border-gray-3 rounded-1">
                                            <div class="hstack me-4">
                                                <div class="wd-40">
                                                    <img src="https://app.obbak.es/assets/images/brand/shopify.png" class="img-fluid" alt="">
                                                </div>
                                                <div class="ms-4">
                                                    <a href="javascript:void(0);" class="fw-bold mb-1 text-truncate-1-line">Shopify: Ecommerce Developers Platform</a>
                                                    <div class="fs-12 text-muted text-truncate-1-line">Try Shopify free and start a business or grow an existing one. Get more than ecommerce software with tools to manage every part of your business.</div>
                                                </div>
                                            </div>
                                            <div class="form-check form-switch form-switch-sm">
                                                <label class="form-check-label fw-500 text-dark c-pointer" for="formSwitchShopify"></label>
                                                <input class="form-check-input c-pointer" type="checkbox" id="formSwitchShopify" checked>
                                            </div>
                                        </div>
                                        <div class="hstack justify-content-between p-4 border border-dashed border-gray-3 rounded-1">
                                            <div class="hstack me-4">
                                                <div class="wd-40">
                                                    <img src="https://app.obbak.es/assets/images/brand/whatsapp.png" class="img-fluid" alt="">
                                                </div>
                                                <div class="ms-4">
                                                    <a href="javascript:void(0);" class="fw-bold mb-1 text-truncate-1-line">WhatsApp: WhatsApp from Facebook is a FREE messaging and video calling app</a>
                                                    <div class="fs-12 text-muted text-truncate-1-line">Reliable messaging. With WhatsApp, you'll get fast, simple, secure messaging and calling for free*, available on phones all ...</div>
                                                </div>
                                            </div>
                                            <div class="form-check form-switch form-switch-sm">
                                                <label class="form-check-label fw-500 text-dark c-pointer" for="formSwitchWhatsApp"></label>
                                                <input class="form-check-input c-pointer" type="checkbox" id="formSwitchWhatsApp">
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="social-connections px-4 mb-4">
                                        <div class="mb-4 d-flex align-items-center justify-content-between">
                                            <h5 class="fw-bold">Social Connections:</h5>
                                            <a href="javascript:void(0);" class="btn btn-sm btn-light-brand">View Alls</a>
                                        </div>
                                        <div class="hstack justify-content-between p-4 mb-3 border border-dashed border-gray-3 rounded-1">
                                            <div class="hstack me-4">
                                                <div class="wd-40">
                                                    <img src="https://app.obbak.es/assets/images/brand/facebook.png" class="img-fluid" alt="">
                                                </div>
                                                <div class="ms-4">
                                                    <a href="javascript:void(0);" class="fw-bold mb-1 text-truncate-1-line">Facebook: The World Most Popular Social Network</a>
                                                    <div class="fs-12 text-muted text-truncate-1-line">Create an account or log into Facebook. Connect with friends, family and other people you know. Share photos and videos, send messages and get updates.</div>
                                                </div>
                                            </div>
                                            <div class="form-check form-switch form-switch-sm">
                                                <label class="form-check-label fw-500 text-dark c-pointer" for="formSwitchFacebook"></label>
                                                <input class="form-check-input c-pointer" type="checkbox" id="formSwitchFacebook" checked>
                                            </div>
                                        </div>
                                        <div class="hstack justify-content-between p-4 mb-3 border border-dashed border-gray-3 rounded-1">
                                            <div class="hstack me-4">
                                                <div class="wd-40">
                                                    <img src="https://app.obbak.es/assets/images/brand/instagram.png" class="img-fluid" alt="">
                                                </div>
                                                <div class="ms-4">
                                                    <a href="javascript:void(0);" class="fw-bold mb-1 text-truncate-1-line">Instagram: Edit & Share photos, Videos & Dessages</a>
                                                    <div class="fs-12 text-muted text-truncate-1-line">Create an account or log in to Instagram - A simple, fun & creative way to capture, edit & share photos, videos & messages with friends & family.</div>
                                                </div>
                                            </div>
                                            <div class="form-check form-switch form-switch-sm">
                                                <label class="form-check-label fw-500 text-dark c-pointer" for="formSwitchInstagram"></label>
                                                <input class="form-check-input c-pointer" type="checkbox" id="formSwitchInstagram">
                                            </div>
                                        </div>
                                        <div class="hstack justify-content-between p-4 mb-3 border border-dashed border-gray-3 rounded-1">
                                            <div class="hstack me-4">
                                                <div class="wd-40">
                                                    <img src="https://app.obbak.es/assets/images/brand/twitter.png" class="img-fluid" alt="">
                                                </div>
                                                <div class="ms-4">
                                                    <a href="javascript:void(0);" class="fw-bold mb-1 text-truncate-1-line">Twitter: It's what's happening / Twitter </a>
                                                    <div class="fs-12 text-muted text-truncate-1-line">From breaking news and entertainment to sports and politics, get the full story with all the live commentary.</div>
                                                </div>
                                            </div>
                                            <div class="form-check form-switch form-switch-sm">
                                                <label class="form-check-label fw-500 text-dark c-pointer" for="formSwitchTwitter"></label>
                                                <input class="form-check-input c-pointer" type="checkbox" id="formSwitchTwitter" checked>
                                            </div>
                                        </div>
                                        <div class="hstack justify-content-between p-4 mb-3 border border-dashed border-gray-3 rounded-1">
                                            <div class="hstack me-4">
                                                <div class="wd-40">
                                                    <img src="https://app.obbak.es/assets/images/brand/spotify.png" class="img-fluid" alt="">
                                                </div>
                                                <div class="ms-4">
                                                    <a href="javascript:void(0);" class="fw-bold mb-1 text-truncate-1-line">Spotify: Web Player: Music for everyone </a>
                                                    <div class="fs-12 text-muted text-truncate-1-line">Spotify is a digital music service that gives you access to millions of songs.</div>
                                                </div>
                                            </div>
                                            <div class="form-check form-switch form-switch-sm">
                                                <label class="form-check-label fw-500 text-dark c-pointer" for="formSwitchSpotify"></label>
                                                <input class="form-check-input c-pointer" type="checkbox" id="formSwitchSpotify" checked>
                                            </div>
                                        </div>
                                        <div class="hstack justify-content-between p-4 mb-3 border border-dashed border-gray-3 rounded-1">
                                            <div class="hstack me-4">
                                                <div class="wd-40">
                                                    <img src="https://app.obbak.es/assets/images/brand/youtube.png" class="img-fluid" alt="">
                                                </div>
                                                <div class="ms-4">
                                                    <a href="javascript:void(0);" class="fw-bold mb-1 text-truncate-1-line">YouTube: The World Largest Video Sharing Platform</a>
                                                    <div class="fs-12 text-muted text-truncate-1-line">Enjoy the videos and music you love, upload original content, and share it all with friends, family, and the world on YouTube.</div>
                                                </div>
                                            </div>
                                            <div class="form-check form-switch form-switch-sm">
                                                <label class="form-check-label fw-500 text-dark c-pointer" for="formSwitchYouTube"></label>
                                                <input class="form-check-input c-pointer" type="checkbox" id="formSwitchYouTube">
                                            </div>
                                        </div>
                                        <div class="hstack justify-content-between p-4 border border-dashed border-gray-3 rounded-1">
                                            <div class="hstack me-4">
                                                <div class="wd-40">
                                                    <img src="https://app.obbak.es/assets/images/brand/pinterest.png" class="img-fluid" alt="">
                                                </div>
                                                <div class="ms-4">
                                                    <a href="javascript:void(0);" class="fw-bold mb-1 text-truncate-1-line">Pinterest: Discover recipes, home ideas, style inspiration and other ideas to try</a>
                                                    <div class="fs-12 text-muted text-truncate-1-line">Pinterest is an image sharing and social media service designed to enable saving and discovery of information on the internet using images.</div>
                                                </div>
                                            </div>
                                            <div class="form-check form-switch form-switch-sm">
                                                <label class="form-check-label fw-500 text-dark c-pointer" for="formSwitchPinterest"></label>
                                                <input class="form-check-input c-pointer" type="checkbox" id="formSwitchPinterest" checked>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade p-4" id="securityTab" role="tabpanel">
                                    <div class="p-4 mb-4 border border-dashed border-gray-3 rounded-1">
                                        <h6 class="fw-bolder"><a href="javascript:void(0);">Two-factor Authentication</a></h6>
                                        <div class="fs-12 text-muted text-truncate-3-line mt-2 mb-4">Two-factor authentication is an enhanced security meansur. Once enabled, you'll be required to give two types of identification when you log into Google Authentication and SMS are Supported.</div>
                                        <div class="form-check form-switch form-switch-sm">
                                            <label class="form-check-label fw-500 text-dark c-pointer" for="2faVerification">Enable 2FA Verification</label>
                                            <input class="form-check-input c-pointer" type="checkbox" id="2faVerification" checked>
                                        </div>
                                    </div>
                                    <div class="p-4 mb-4 border border-dashed border-gray-3 rounded-1">
                                        <h6 class="fw-bolder"><a href="javascript:void(0);">Secondary Verification</a></h6>
                                        <div class="fs-12 text-muted text-truncate-3-line mt-2 mb-4">The first factor is a password and the second commonly includes a text with a code sent to your smartphone, or biometrics using your fingerprint, face, or retina.</div>
                                        <div class="form-check form-switch form-switch-sm">
                                            <label class="form-check-label fw-500 text-dark c-pointer" for="secondaryVerification">Set up secondary method</label>
                                            <input class="form-check-input c-pointer" type="checkbox" id="secondaryVerification" checked>
                                        </div>
                                    </div>
                                    <div class="p-4 mb-4 border border-dashed border-gray-3 rounded-1">
                                        <h6 class="fw-bolder"><a href="javascript:void(0);">Backup Codes</a></h6>
                                        <div class="fs-12 text-muted text-truncate-3-line mt-4 mb-4">A backup code is automatically generated for you when you turn on two-factor authentication through your iOS or Android Twitter app. You can also generate a backup code on twitter.com.</div>
                                        <div class="form-check form-switch form-switch-sm">
                                            <label class="form-check-label fw-500 text-dark c-pointer" for="generateBackup">Generate backup codes</label>
                                            <input class="form-check-input c-pointer" type="checkbox" id="generateBackup">
                                        </div>
                                    </div>
                                    <div class="p-4 border border-dashed border-gray-3 rounded-1">
                                        <h6 class="fw-bolder"><a href="javascript:void(0);">Login Verification</a></h6>
                                        <div class="fs-12 text-muted text-truncate-3-line mt-2 mb-4">Login verification is an enhanced security meansur. Once enabled, you'll be required to give two types of identification when you log into Google Authentication and SMS are Supported.</div>
                                        <div class="form-check form-switch form-switch-sm">
                                            <label class="form-check-label fw-500 text-dark c-pointer" for="loginVerification">Enable Login Verification</label>
                                            <input class="form-check-input c-pointer" type="checkbox" id="loginVerification" checked>
                                        </div>
                                    </div>
                                    <hr class="my-5">
                                    <div class="alert alert-dismissible mb-4 p-4 d-flex alert-soft-danger-message" role="alert">
                                        <div class="me-4 d-none d-md-block">
                                            <i class="feather feather-alert-triangle text-danger fs-1"></i>
                                        </div>
                                        <div>
                                            <p class="fw-bold mb-0 text-truncate-1-line">You Are Delete or Deactivating Your Account</p>
                                            <p class="text-truncate-3-line mt-2 mb-4">Two-factor authentication adds an additional layer of security to your account by requiring more than just a password to log in.</p>
                                            <a href="javascript:void(0);" class="btn btn-sm btn-danger d-inline-block">Learn more</a>
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>
                                    </div>
                                    <div class="card mt-5">
                                        <div class="card-body">
                                            <h6 class="fw-bold">Delete Account</h6>
                                            <p class="fs-11 text-muted">Go to the Data & Privacy section of your profile Account. Scroll to "Your data & privacy options." Delete your Profile Account. Follow the instructions to delete your account:</p>
                                            <div class="my-4 py-2">
                                                <input type="password" class="form-control" placeholder="Enter your password">
                                                <div class="mt-3">
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input" id="acDeleteDeactive">
                                                        <label class="custom-control-label c-pointer" for="acDeleteDeactive">I confirm my account deletations or deactivation.</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="d-sm-flex gap-2">
                                                <a href="javascript:void(0);" class="btn btn-danger" data-action-target="#acSecctingsActionMessage">Delete Account</a>
                                                <a href="javascript:void(0);" class="btn btn-warning mt-2 mt-sm-0 successAlertMessage">Deactiveted Account</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </div>
								</div>
								<div class="row">
                            <div class="col-xl-6 col-md-6">
                                <div class="card">
                                    <div class="card-body p-4">
                                        <div class="d-flex mb-1">
                                            <div class="flex-shrink-0 me-3">
                                                <div class="avatar-sm">
                                                    <span class="avatar-title rounded-circle bg-primary">
                                                        <i class="fas fa-shield-alt font-size-20"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h5 class="font-size-16">Enterprise</h5>
                                                <p class="text-muted">Sed neque unde</p>
                                            </div>
                                        </div>
                                        <div class="d-grid flex-wrap gap-2 align-items-cente">
                                            <?php 
                                            ($oferta->itipo); ($oferta->inscrip);
                                            switch (true) {
                                                case (($oferta->inscrip) != 11) : ?>
                                                    <div onclick="inscribirse();" class="btn btn-primary btn-lg waves-effect waves-light">Petición de Inversión</div>
                                                    <?php break;
                                                case (($oferta->inscrip) == 11 && ($oferta->itipo)==1 ): ?>
                                                    <div class="btn btn-primary btn-lg waves-effect waves-light">Pendiente de revisión</div>
                                                    <?php break;
                                            } ?>
                                            <br>
                                        </div>
                                        <div class="plan-features mt-4">
                                            <h5 class="text-center font-size-15 mb-4">Plan Features :</h5>
                                            <p><i class="mdi mdi-checkbox-marked-circle-outline font-size-16 align-middle text-primary me-2"></i> Free Live Support</p>
                                            <p><i class="mdi mdi-checkbox-marked-circle-outline font-size-16 align-middle text-primary me-2"></i> Unlimited User</p>
                                            <p><i class="mdi mdi-checkbox-marked-circle-outline font-size-16 align-middle text-primary me-2"></i> No Time Tracking</p>
                                            <p><i class="mdi mdi-checkbox-marked-circle-outline font-size-16 align-middle text-primary me-2"></i> Free Setup</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6 col-md-6">
                                <div class="card">
                                    <div class="card-body p-4">
                                        <div class="d-flex mb-1">
                                            <div class="flex-shrink-0 me-3">
                                                <div class="avatar-sm">
                                                    <span class="avatar-title rounded-circle bg-primary">
                                                        <i class="fas fa-headset font-size-20"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h5 class="font-size-16">Unlimited</h5>
                                                <p class="text-muted">Itaque earum rerum</p>
                                            </div>
                                        </div>
                                        <div class="d-grid flex-wrap gap-2 align-items-cente">
                                            <?php 
                                            ($oferta->iestado); ($oferta->inscrip);
                                            switch (true) {
                                                case ($oferta->inscrip != 11 && $oferta->inscrip != 12): ?>
                                                    <div onclick="inscribirse2();" class="btn btn-primary btn-lg waves-effect waves-light">Petición de Inversión</div>
                                                    <?php break;
                                                case (($oferta->inscrip) == 11 && $oferta->itipo == 2 ): ?>
                                                    <div onclick="inscribirse2();" class="btn btn-primary btn-lg waves-effect waves-light">Petición de Inversión</div>
                                                    <?php break;
                                                case (($oferta->inscrip) == 12 && $oferta->itipo == 2): ?>
                                                    <div class="btn btn-primary btn-lg waves-effect waves-light">Pendiente de revisión</div>
                                                    <?php break;
                                                case (($oferta->inscrip) == 11 && $oferta->itipo == 1): ?>
                                                    <div class="btn btn-primary btn-lg waves-effect waves-light">Pendiente de revisión</div>
                                                    <?php break;
                                            } ?>
                                            <br>
                                        </div>
                                        <div class="plan-features mt-4">
                                            <h5 class="text-center font-size-15 mb-4">Plan Features :</h5>
                                            <p><i class="mdi mdi-checkbox-marked-circle-outline font-size-16 align-middle text-primary me-2"></i> Free Live Support</p>
                                            <p><i class="mdi mdi-checkbox-marked-circle-outline font-size-16 align-middle text-primary me-2"></i> Unlimited User</p>
                                            <p><i class="mdi mdi-checkbox-marked-circle-outline font-size-16 align-middle text-primary me-2"></i> No Time Tracking</p>
                                            <p><i class="mdi mdi-checkbox-marked-circle-outline font-size-16 align-middle text-primary me-2"></i> Free Setup</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
								<hr>
								<div class="row">
								<div class="col-lg-12">
                                <div class="card">
                                    <div class="body">
                                        <div class="table-responsive">
                                            <table id="tech-companies-1" class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Juego</th>
                                                        <th>Usuario</th>
                                                        <th>Estado</th>
                                                        <th>Importe</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach($inscripciones as $inscripcion) if ($oferta->id_oferta == $inscripcion->id_oferta) { ?>
                                                        <tr>
                                                            <td>
                                                                <a href="<?php echo base_url() . 'anunciantes/oferta/' . $inscripcion->id_oferta; ?>" target="_self" style="color: #212529;">
                                                                    <?php echo $inscripcion->titulo; ?>
                                                                </a>
                                                            </td>
                                                            <td>
                                                                <?php echo $inscripcion->nombre ?>
                                                            </td>
                                                            <td>
                                                                <span style="color: "><?php echo ($inscripcion->estado == 0) ? 'Pendiente' : (($inscripcion->estado == 1) ? 'Autorizada' : (($inscripcion->estado == 2) ? 'Pagada' : 'Cancelada')); ?></span>
                                                            </td>
                                                            <td>
                                                                <?php ($inscripcion->estado);
                                                                switch (true) {
                                                                    case (($inscripcion->estado)==0):?>
                                                                        <span style="color: ">Pendiente</span>
                                                                        <?php break;
                                                                    case (($inscripcion->estado)==1):?>
                                                                        <span style="color: "><?php echo (($inscripcion->tipo_inscripion == 1)  ?  ' 7.000€' : (($inscripcion->tipo_inscripion == 2) ? '3.500' : 'Cancelada')); ?></span>
                                                                        <?php break;
                                                                } ?>
                                                            </td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>		
						</div>		
							</div>
					</div>
					<div class="col-xxl-4 col-xl-6">
						<div class="card">
							<div id="ficha_info">
                                <div id="ficha_info_afinidad" class="card stretch stretch-full"><span><?=number_format ($oferta->porc_inversion,0,",",".")?>%</span> Porcentaje de inversión.</div>
                                <div id="ficha_info_container">
                                    <div id="ficha_tarifa" class="row">
                                        <div class="col-md-6"><span>Inversión Total:</span><strong><?=number_format ($oferta->inversion,0,",",".")?>€</strong></div>
                                        <div class="col-md-6"><span>Rentabilidad Estimada:</span><strong><?=number_format ($oferta->renta_esti,0,",",".")?>%</strong></div>
                                        <hr>
                                        <div id="ficha_precio"><i class="bimico-oferta"></i><span>Inversión Mínima</span><strong><?=number_format ($oferta->inversion_min,0,",",".")?></strong></div>
                                        <hr>
                                        <div id="ficha_fecha"><i class="bimico-calendar"></i> <span>Fecha</span><strong>Inicio Programación <?=strftime("%B %G",strtotime($oferta->fecha_inicio_pub))?> - <?=strftime("%B %G",strtotime($oferta->fecha_fin_pub))?> Salida Plataforma</strong></div>
                                        <hr>
                                        <div id="ficha_precio"><i class="bimico-time"></i><span>Duración Producción </span><?= ($oferta->duracion_camp) ?></div>
                                        <hr>
                                        <div id="ficha_oferta_termina"><i class="bimico-time"></i> <span>La oferta termina en </span><strong><?php
                                            if($oferta->fecha_fin_pub != ""){
                                                $origin = new DateTimeImmutable($oferta->fecha_fin_pub);
                                                $target = new DateTimeImmutable("now");
                                                $diff = $target->diff($origin);
                                                if($diff->m > 0){
                                                    echo $diff->m . " meses " . $diff->d . " días " ;
                                                } else {
                                                    echo $diff->d . " días " . $diff->h . " horas " . $diff->i . " minutos" ;
                                                }
                                            }
                                            ?></strong></div>
                                        <div id="ficha_inscribirse_aviso">Nos llegará un aviso de tu interés y nos pondremos en contacto contigo para tramitar la posible inversión. <strong>No obliga a la contratación ni al pago.</strong></div>
                                        <hr>
                                        <div id="ficha_agente">
                                            <img src="https://dev.bimads.com/img/Caricatura_pope.jpg" alt="Javier" />
                                            ¡Hola! Soy Javier Sentri. Estoy a tu disposición para resolver cualquier duda que puedas tener, solo tienes que mandarme un mail o llamarme.
                                        </div>
                                        <div id="ficha_agente_contacto">
                                            <a href="#"></a><a href="mailto:"></a>
                                        </div>
                                        <hr>
                                    </div>
                                    <div id="ficha_preferencias">
                                        <h4 class="sidebar_title">Mejora tus preferencias contestando a estas preguntas sobre el juego</h4>
                                        <form id="updateOfertasClientes_id">
                                            <div class="form-group m-t-30">
                                                <label class="control-label">¿Te ha gustado el Juego?</label>
                                                <div class="custom-checkbox-inline m-t-10">
                                                    <div class="custom-control custom-checkbox custom-checkbox-full">
                                                        <input type="radio" onchange="updateOfertasClientes()" class="custom-control-input" <?php if(isset($ofertas_clientes) && isset($ofertas_clientes[0]) && isset($ofertas_clientes[0]->precio) && $ofertas_clientes[0]->precio == 1){ echo "checked"; }?> id="lomismosi" name="precio" value="1"   >
                                                        <label class="custom-control-label" for="lomismosi">Si</label>
                                                    </div>
                                                    <div class="custom-control custom-checkbox custom-checkbox-full">
                                                        <input type="radio" onchange="updateOfertasClientes()" class="custom-control-input" <?php if(isset($ofertas_clientes) && isset($ofertas_clientes[0]) && isset($ofertas_clientes[0]->precio) && $ofertas_clientes[0]->precio == 0){ echo "checked"; }?> id="lomismono" name="precio" value="0"  >
                                                        <label class="custom-control-label" for="lomismono">No</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group m-t-30">
                                                <label class="control-label">¿Te ha gustado el genero del Juego?</label>
                                                <div class="custom-checkbox-inline m-t-10">
                                                    <div class="custom-control custom-checkbox custom-checkbox-full">
                                                        <input type="radio" onchange="updateOfertasClientes()" class="custom-control-input" <?php if(isset($ofertas_clientes) && isset($ofertas_clientes[0]) && isset($ofertas_clientes[0]->audiencia) && $ofertas_clientes[0]->audiencia == 1){ echo "checked"; }?> id="lomismosi1"  name="audiencia" value="1"   >
                                                        <label class="custom-control-label" for="lomismosi1">Si</label>
                                                    </div>
                                                    <div class="custom-control custom-checkbox custom-checkbox-full">
                                                        <input type="radio" onchange="updateOfertasClientes()" class="custom-control-input" <?php if(isset($ofertas_clientes) && isset($ofertas_clientes[0]) && isset($ofertas_clientes[0]->audiencia) && $ofertas_clientes[0]->audiencia == 0){ echo "checked"; }?> id="lomismono1"  name="audiencia" value="0"   >
                                                        <label class="custom-control-label" for="lomismono1">No</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group m-t-30">
                                                <label class="control-label">¿Te ha gustado el plazo de producción del Juego?</label>
                                                <div class="custom-checkbox-inline m-t-10">
                                                    <div class="custom-control custom-checkbox custom-checkbox-full">
                                                        <input type="radio" onchange="updateOfertasClientes()" class="custom-control-input" <?php if(isset($ofertas_clientes) && isset($ofertas_clientes[0]) && isset($ofertas_clientes[0]->fecha) && $ofertas_clientes[0]->fecha == 1){ echo "checked"; }?> id="lomismosi2" name="fecha" value="1"   >
                                                        <label class="custom-control-label" for="lomismosi2">Si</label>
                                                    </div>
                                                    <div class="custom-control custom-checkbox custom-checkbox-full">
                                                        <input type="radio" onchange="updateOfertasClientes()" class="custom-control-input" <?php if(isset($ofertas_clientes) && isset($ofertas_clientes[0]) && isset($ofertas_clientes[0]->fecha) && $ofertas_clientes[0]->fecha == 0){ echo "checked"; }?> id="lomismono2"  name="fecha" value="0"  >
                                                        <label class="custom-control-label" for="lomismono2">No</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="d-grid flex-wrap gap-2 align-items-center">          
                                    </div>
                                </div> 
                            </div> 
						</div> 
					</div>
				</div>  
                    <!-- end col -->
	</div>
</div>				
				<!-- End Page-content -->           
                <?php include 'partials/vendor-scripts.php'; ?>
                <!-- Modal  Info -->
                <?php 
                $aux['oferta']=$oferta;
                ?>    
                <?php $this->load->view('anunciantes/ofertas_oferta_modal_info', $aux); ?>
                <!-- Modal  Galeria -->
                <?php $this->load->view('anunciantes/ofertas_oferta_modal_galeria', $aux); ?>
                <!-- Modal  Contacto -->
                <?php $this->load->view('anunciantes/ofertas_oferta_modal_contacto', $aux); ?>
                <!-- Modal  Compra -->
                <?php $this->load->view('anunciantes/ofertas_oferta_modal_compra', $aux); ?>
                <script>
document.addEventListener("DOMContentLoaded", function () {
    function iniciarGrafico() {
        let chartContainer = document.querySelector("#project-statistics-chart");

        // Esperar hasta que el div aparezca en el DOM
        if (!chartContainer) {
            console.warn("⏳ Esperando a que el contenedor del gráfico aparezca...");
            setTimeout(iniciarGrafico, 500); // Reintentar cada 500ms
            return;
        }

        fetch("https://app.obbak.es/get_data.php")
        .then(response => response.json())
        .then(data => {
            console.log("Datos recibidos:", data);

            if (!data || !data.categorias || !Array.isArray(data.categorias) || data.categorias.length === 0) {
                console.warn("⚠️ No hay datos válidos.");
                return;
            }

            let options = {
                chart: { height: 365, type: "area", toolbar: { show: false } },
                xaxis: { categories: data.categorias, labels: { style: { fontSize: "11px", colors: "#64748b" } } },
                series: [
                    { name: "Previsiones Descargas", data: data.tareas || [0, 0, 0] },
                    { name: "Descargas Reales", data: data.proyectos || [0, 0, 0] }
                ],
                stroke: { curve: "smooth" },
                grid: { borderColor: "#ebebf3", strokeDashArray: 3 }
            };

            console.log("Configuración de ApexCharts:", options);

            let chart = new ApexCharts(chartContainer, options);
            chart.render();
        })
        .catch(error => console.error("❌ Error al obtener datos:", error));
    }

    iniciarGrafico();
});




</script> 
                <script>
                    function inscribirse2() {
                        if (!confirm('¿Quieres invertir en el Juego?'))
                            return;
                        document.location.href = '<?php echo base_url() . 'anunciantes/inscribirseOferta2/' . $oferta->id_oferta; ?>';
                    }
                    function inscribirse() {
                        if (!confirm('¿Quieres invertir en el Juego?'))
                            return;
                        document.location.href = '<?php echo base_url() . 'anunciantes/inscribirseOferta/' . $oferta->id_oferta; ?>';
                    }
                    function updateOfertasClientes(){
                        var action= "<?php echo base_url();?>anunciantes/updateOfertasClientes/<?=$oferta->id_oferta?>";
                        $.post( action, $("#updateOfertasClientes_id").serialize());
                    }
</script>    
    <!--! BEGIN: Vendors JS !-->
    <script src="https://app.obbak.es/assets/vendors/js/vendors.min.js"></script>
    <!-- vendors.min.js {always must need to be top} -->
    <script src="https://app.obbak.es/assets/vendors/js/daterangepicker.min.js"></script>
    <script src="https://app.obbak.es/assets/vendors/js/apexcharts.min.js"></script>
    <script src="https://app.obbak.es/assets/vendors/js/circle-progress.min.js"></script>
    <!--! END: Vendors JS !-->
    <!--! BEGIN: Apps Init  !-->
    <script src="https://app.obbak.es/assets/js/common-init.min.js"></script>
    <script src="https://app.obbak.es/assets/js/dashboard-init.min.js"></script>
    <!--! END: Apps Init !-->
    <!--! BEGIN: Theme Customizer  !-->
    <script src="https://app.obbak.es/assets/js/theme-customizer-init.min.js"></script>
    <!--! END: Theme Customizer !-->


</body>
</html>