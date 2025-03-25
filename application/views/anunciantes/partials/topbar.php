<header class="nxl-header">
    <div class="header-wrapper">
        <!-- [Start] Header Left -->
        <div class="header-left d-flex align-items-center gap-4">
            <!-- [Start] Mobile Toggler -->
            <a href="javascript:void(0);" class="nxl-head-mobile-toggler" id="mobile-collapse">
                <div class="hamburger hamburger--arrowturn">
                    <div class="hamburger-box">
                        <div class="hamburger-inner"></div>
                    </div>
                </div>
            </a>
            <!-- [End] Mobile Toggler -->

            <!-- [Start] Navigation Toggle -->
            <div class="nxl-navigation-toggle">
                <a href="javascript:void(0);" id="menu-mini-button">
                    <i class="feather-align-left"></i>
                </a>
                <a href="javascript:void(0);" id="menu-expend-button" style="display: none;">
                    <i class="feather-arrow-right"></i>
                </a>
            </div>
            <!-- [End] Navigation Toggle -->

            <!-- [Start] Mega Menu Toggle -->
            <div class="nxl-lavel-mega-menu-toggle d-flex d-lg-none">
                <a href="javascript:void(0);" id="nxl-lavel-mega-menu-open">
                    <i class="feather-align-left"></i>
                </a>
            </div>
            <!-- [End] Mega Menu Toggle -->

            <!-- [Start] Mega Menu -->
            <div class="nxl-drp-link nxl-lavel-mega-menu">
                <div class="nxl-lavel-mega-menu-toggle d-flex d-lg-none">
                    <a href="javascript:void(0);" id="nxl-lavel-mega-menu-hide">
                        <i class="feather-arrow-left me-2"></i>
                        <span>Back</span>
                    </a>
                </div>
            </div>
            <!-- [End] Mega Menu -->
        </div>
        <!-- [End] Header Left -->

        <!-- [Start] Header Right -->
        <div class="header-right ms-auto">
            <div class="d-flex align-items-center">
                <!-- Full Screen Switcher -->
                <div class="nxl-h-item d-none d-sm-flex">
                    <div class="full-screen-switcher">
                        <a href="javascript:void(0);" class="nxl-head-link me-0" onclick="$('body').fullScreenHelper('toggle');">
                            <i class="feather-maximize maximize"></i>
                            <i class="feather-minimize minimize"></i>
                        </a>
                    </div>
                </div>

                <!-- Timesheets Dropdown -->
                <div class="dropdown nxl-h-item">
                    <a href="javascript:void(0);" class="nxl-head-link me-0" data-bs-toggle="dropdown" role="button" data-bs-auto-close="outside">
                        <i class="feather-clock"></i>
                        <span class="badge bg-success nxl-h-badge"><?= $actividades ?></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end nxl-h-dropdown nxl-timesheets-menu">
                        <div class="d-flex justify-content-between align-items-center timesheets-head">
                            <h6 class="fw-bold text-dark mb-0">Timesheets</h6>
                            <a href="javascript:void(0);" class="fs-11 text-success text-end ms-auto" data-bs-toggle="tooltip" title="Upcoming Timers">
                                <i class="feather-clock"></i>
                                <span><?= is_array($avisos) ? count($avisos) : 0; ?> Upcoming</span>
                            </a>
                        </div>

                        <?php if (!empty($avisos)) : ?>
                            <div class="timesheets-body">
                                <?php foreach ($avisos as $aviso) : ?>
                                    <div class="timesheets-item border-bottom py-2">
                                        <p class="mb-1 fw-bold"><?= htmlspecialchars($aviso->concepto); ?></p>
                                        <span class="text-muted">ID Oferta: <?= htmlspecialchars($aviso->id_oferta); ?></span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                                <?php else : ?>
                                    <div class="d-flex justify-content-between align-items-center flex-column timesheets-body">
                                <i class="feather-clock fs-1 mb-4"></i>
                                <p class="text-muted">No started timers found yet!</p>
                                <a href="javascript:void(0);" class="btn btn-sm btn-primary">Start Timer</a>
                                    </div>
                                <?php endif; ?>
                        <div class="text-center timesheets-footer">
                            <a href="javascript:void(0);" class="fs-13 fw-semibold text-dark">All Timesheets</a>
                        </div>
                    </div>
                </div>
                <!-- Notifications Dropdown -->
                <div class="dropdown nxl-h-item">
                    <a class="nxl-head-link me-3" data-bs-toggle="dropdown" href="#" role="button" data-bs-auto-close="outside">
                        <i class="feather-bell"></i>
                        <span class="badge bg-danger nxl-h-badge"></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end nxl-h-dropdown nxl-notifications-menu">
                        <div class="d-flex justify-content-between align-items-center notifications-head">
                            <h6 class="fw-bold text-dark mb-0">Notifications</h6>
                            <a href="javascript:void(0);" class="fs-11 text-success text-end ms-auto" data-bs-toggle="tooltip" title="Make as Read">
                                <i class="feather-check"></i>
                                <span>Make as Read</span>
                            </a>
                        </div>
                        <!-- Example Notification Items -->
                        <div class="notifications-item">
                            <img src="https://app.obbak.es/assets/images/avatar/2.png" alt="" class="rounded me-3 border" />
                            <div class="notifications-desc">
                                <a href="javascript:void(0);" class="font-body text-truncate-2-line">
                                    <span class="fw-semibold text-dark">Malanie Hanvey</span> We should talk about that at lunch!
                                </a>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="notifications-date text-muted border-bottom border-bottom-dashed">2 minutes ago</div>
                                    <div class="d-flex align-items-center float-end gap-2">
                                        <a href="javascript:void(0);" class="d-block wd-8 ht-8 rounded-circle bg-gray-300" data-bs-toggle="tooltip" title="Make as Read"></a>
                                        <a href="javascript:void(0);" class="text-danger" data-bs-toggle="tooltip" title="Remove">
                                            <i class="feather-x fs-12"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Add more notifications as needed -->
                        <div class="text-center notifications-footer">
                            <a href="javascript:void(0);" class="fs-13 fw-semibold text-dark">All Notifications</a>
                        </div>
                    </div>
                </div>

                <!-- User Dropdown -->
                <div class="dropdown nxl-h-item">
                    <a href="javascript:void(0);" data-bs-toggle="dropdown" role="button" data-bs-auto-close="outside">
                        <img src="<?= base_url($cliente->imagen); ?>" alt="user-image" class="img-fluid user-avtar me-0" />
                    </a>
                    <div class="dropdown-menu dropdown-menu-end nxl-h-dropdown nxl-user-dropdown">
                        <div class="dropdown-header">
                            <div class="d-flex align-items-center">
                                <img src="<?= base_url($cliente->imagen); ?>" alt="user-image" class="img-fluid user-avtar" />
                                <div>
                                    <h6 class="text-dark mb-0">
                                        <?= !empty($aviso_error) ? set_value('nick') : $cliente->nick; ?>
                                        <span class="badge bg-soft-success text-success ms-1">PRO</span>
                                    </h6>
                                    <span class="fs-12 fw-medium text-muted">
                                        <?= !empty($aviso_error) ? set_value('email') : $cliente->email; ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="dropdown-divider"></div>
                        <a href="http://localhost:8000/obbak/anunciantes/perfilEmpresa" class="dropdown-item">
                            <i class="feather-user"></i>
                            <span>Perfil Inversor</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="https://app.obbak.es/inicio/logout" class="dropdown-item">
                            <i class="feather-log-out"></i>
                            <span>Logout</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- [End] Header Right -->
    </div>
</header>