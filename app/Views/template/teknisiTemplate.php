<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>MNT Tools</title>
    <meta
        content="width=device-width, initial-scale=1.0, shrink-to-fit=no"
        name="viewport" />
    <link
        rel="icon"
        href="<?= base_url(); ?>kaiadmin/assets/img/kaiadmin/favicon.ico"
        type="image/x-icon" />

    <!-- Fonts and icons -->
    <!-- <script src="https://kit.fontawesome.com/2b99eaa986.js" crossorigin="anonymous"></script> -->
    <script src="<?= base_url(); ?>kaiadmin/assets/js/plugin/webfont/webfont.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        WebFont.load({
            google: {
                families: ["Public Sans:300,400,500,600,700"]
            },
            custom: {
                families: [
                    "Font Awesome 5 Solid",
                    "Font Awesome 5 Regular",
                    "Font Awesome 5 Brands",
                    "simple-line-icons",
                ],
                urls: ["<?= base_url(); ?>kaiadmin/assets/css/fonts.min.css"],
            },
            active: function() {
                sessionStorage.fonts = true;
            },
        });
    </script>

    <!-- CSS Files -->
    <link rel="stylesheet" href="<?= base_url(); ?>kaiadmin/assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="<?= base_url(); ?>kaiadmin/assets/css/plugins.min.css" />
    <link rel="stylesheet" href="<?= base_url(); ?>kaiadmin/assets/css/kaiadmin.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.css" />

    <!-- CSS Just for demo purpose, don't include it in your project -->
    <!-- <link rel="stylesheet" href="assets/css/demo.css" /> -->
</head>

<body>
    <?php if (session('messages')): ?>
        <script>
            Swal.fire({
                position: "top-center",
                icon: "success",
                title: "Success",
                text: "<?= session('messages'); ?>",
                showConfirmButton: false,
                timer: 1000
            });
        </script>
    <?php endif; ?>
    <?php if (session('messages_error')): ?>
        <script>
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "<?= session('messages_error'); ?>",
            });
        </script>
    <?php endif; ?>
    <?php if (session('errors')): ?>
        <script>
            var errorList = <?php echo json_encode(session('errors')); ?>;
            var errorMessages = '<ul>';
            for (var key in errorList) {
                if (errorList.hasOwnProperty(key)) {
                    errorMessages += '<li>' + errorList[key] + '</li>';
                }
            }
            errorMessages += '</ul>';
            Swal.fire({
                icon: "error",
                title: "Oops...",
                html: errorMessages,
            });
        </script>
    <?php endif; ?>
    <div class="wrapper">
        <!-- Sidebar -->
        <div class="sidebar" data-background-color="dark">
            <div class="sidebar-logo">
                <!-- Logo Header -->
                <div class="logo-header" data-background-color="dark">
                    <a href="index.html" class="logo">
                        <img
                            src="<?= base_url(); ?>kaiadmin/assets/img/kaiadmin/logo_light.svg"
                            alt="navbar brand"
                            class="navbar-brand"
                            height="20" />
                    </a>
                    <div class="nav-toggle">
                        <button class="btn btn-toggle toggle-sidebar">
                            <i class="gg-menu-right"></i>
                        </button>
                        <button class="btn btn-toggle sidenav-toggler">
                            <i class="gg-menu-left"></i>
                        </button>
                    </div>
                    <button class="topbar-toggler more">
                        <i class="gg-more-vertical-alt"></i>
                    </button>
                </div>
                <!-- End Logo Header -->
            </div>
            <div class="sidebar-wrapper scrollbar scrollbar-inner">
                <div class="sidebar-content">
                    <ul class="nav nav-secondary">
                        <li class="nav-item  <?= $active == 'dashboard' ? 'active' : ''; ?>">
                            <a
                                href="<?= base_url(); ?>teknisi/dashboard"
                                class="collapsed"
                                aria-expanded="false">
                                <i class="fas fa-home"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>
                        <li class="nav-item  <?= $active == 'pengajuan-peminjaman' ? 'active' : ''; ?> ">
                            <a
                                href="<?= base_url(); ?>teknisi/request"
                                class="collapsed"
                                aria-expanded="false">
                                <i class="fas fa-inbox"></i>
                                <p>Request Peminjaman</p>
                            </a>
                        </li>
                        <li class="nav-item  <?= $active == 'peminjaman' ? 'active' : ''; ?> ">
                            <a
                                href="<?= base_url(); ?>teknisi/peminjaman"
                                class="collapsed"
                                aria-expanded="false">
                                <i class="fas fa-book"></i>
                                <p>Peminjaman</p>
                            </a>
                        </li>
                        <li class="nav-item  <?= $active == 'pengembalian' ? 'active' : ''; ?> ">
                            <a
                                href="<?= base_url(); ?>teknisi/pengembalian"
                                class="collapsed"
                                aria-expanded="false">
                                <i class="fas fa-retweet"></i>
                                <p>Pengembalian</p>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- End Sidebar -->

        <div class="main-panel">
            <div class="main-header">
                <div class="main-header-logo">
                    <!-- Logo Header -->
                    <div class="logo-header" data-background-color="dark">
                        <a href="index.html" class="logo">
                            <img
                                src="<?= base_url(); ?>kaiadmin/assets/img/kaiadmin/logo_light.svg"
                                alt="navbar brand"
                                class="navbar-brand"
                                height="20" />
                        </a>
                        <div class="nav-toggle">
                            <button class="btn btn-toggle toggle-sidebar">
                                <i class="gg-menu-right"></i>
                            </button>
                            <button class="btn btn-toggle sidenav-toggler">
                                <i class="gg-menu-left"></i>
                            </button>
                        </div>
                        <button class="topbar-toggler more">
                            <i class="gg-more-vertical-alt"></i>
                        </button>
                    </div>
                    <!-- End Logo Header -->
                </div>
                <!-- Navbar Header -->
                <nav
                    class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom">
                    <div class="container-fluid">
                        <nav
                            class="navbar navbar-header-left navbar-expand-lg navbar-form nav-search p-0 d-none d-lg-flex">
                        </nav>

                        <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
                            <li class="nav-item topbar-user dropdown hidden-caret">
                                <a
                                    class="dropdown-toggle profile-pic"
                                    data-bs-toggle="dropdown"
                                    href="#"
                                    aria-expanded="false">
                                    <div class="avatar-sm">
                                        <img
                                            src="<?= base_url(); ?>kaiadmin/assets/img/kaiadmin/favicon.png"
                                            alt="..."
                                            class="avatar-img rounded-circle" />
                                    </div>
                                    <span class="profile-username">
                                        <span class="op-7">Hi,</span>
                                        <span class="fw-bold"><?= session('nama'); ?></span>
                                    </span>
                                </a>
                                <ul class="dropdown-menu dropdown-user animated fadeIn">
                                    <div class="dropdown-user-scroll scrollbar-outer">
                                        <li>
                                            <div class="user-box">
                                                <div class="avatar-lg">
                                                    <img
                                                        src="<?= base_url(); ?>kaiadmin/assets/img/kaiadmin/favicon.png"
                                                        alt="image profile"
                                                        class="avatar-img rounded" />
                                                </div>
                                                <div class="u-text">
                                                    <h4><?= session('username'); ?></h4>
                                                    <p class="text-muted"><?= session('kontak'); ?></p>
                                                    <p class="text-muted"><span class="badge badge-success"><?= session('status'); ?></span></p>
                                                    <!-- <a
                                                        href="profile.html"
                                                        class="btn btn-xs btn-secondary btn-sm">View Profile</a> -->
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="<?= base_url('teknisi/profile'); ?>">My Profile</a>
                                            <!-- <a class="dropdown-item" href="#">My Balance</a>
                                            <a class="dropdown-item" href="#">Inbox</a>
                                            <div class="dropdown-divider"></div> -->
                                            <a class="dropdown-item"
                                                data-bs-toggle="modal" href=""
                                                data-bs-target="#modalChangePassword">
                                                Change Password
                                            </a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="<?= base_url('logout'); ?>">Logout</a>
                                        </li>
                                    </div>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </nav>
                <!-- End Navbar -->
            </div>

            <!-- //modal -->
            <div class="modal fade"
                id="modalChangePassword"
                tabindex="-1"
                role="dialog"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header border-0">
                            <h5 class="modal-title">
                                <span class="fw-mediumbold">Change Password </span>
                            </h5>
                            <button
                                type="button"
                                class="close"
                                data-bs-dismiss="modal"
                                aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="<?= base_url('changePassword'); ?>" method="post">
                                <?php csrf_field() ?>
                                <div class="row">
                                    <div class="col-sm-7">
                                        <div class="form-group form-group-default">
                                            <label>Old Password</label>
                                            <input
                                                id="oldPassword"
                                                name="oldPassword"
                                                type="password"
                                                class="form-control"
                                                placeholder="old Password"
                                                autofocus />
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group form-group-default">
                                            <label>New Password</label>
                                            <input
                                                id="newPassword"
                                                name="newPassword"
                                                type="password"
                                                class="form-control"
                                                placeholder="newPassword" />
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group form-group-default">
                                            <label>Confirm New Password</label>
                                            <input
                                                id="confirmNewPassword"
                                                name="confirmNewPassword"
                                                type="password"
                                                class="form-control"
                                                placeholder="confirm new password"
                                                autocomplete="off" />
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer border-0">
                                    <button
                                        type="submit"
                                        id="addRowButton"
                                        class="btn btn-primary">
                                        update
                                    </button>
                                    <button
                                        type="button"
                                        class="btn btn-danger"
                                        data-bs-dismiss="modal">
                                        Close
                                    </button>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>

            <?= $this->renderSection('content'); ?>

            <footer class="footer">
                <div class="container-fluid d-flex justify-content-between">
                    <nav class="pull-left">
                        <ul class="nav">
                            <li class="nav-item"> template by
                                ThemeKita
                                </a>
                            </li>
                        </ul>
                    </nav>
                    <div class="copyright">
                        &copy; 2024 - <?= date('Y'); ?>
                        <a href="http://chitos.site/">IKN</a>
                    </div>
                    <div>
                        MNT Tools
                    </div>
                </div>
            </footer>
        </div>

    </div>
    <!--   Core JS Files   -->
    <script src="<?= base_url(); ?>kaiadmin/assets/js/core/jquery-3.7.1.min.js"></script>
    <script src="<?= base_url(); ?>kaiadmin/assets/js/core/popper.min.js"></script>
    <script src="<?= base_url(); ?>kaiadmin/assets/js/core/bootstrap.min.js"></script>

    <!-- jQuery Scrollbar -->
    <script src="<?= base_url(); ?>kaiadmin/assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>

    <!-- Chart JS -->
    <script src="<?= base_url(); ?>kaiadmin/assets/js/plugin/chart.js/chart.min.js"></script>

    <!-- jQuery Sparkline -->
    <script src="<?= base_url(); ?>kaiadmin/assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js"></script>

    <!-- Chart Circle -->
    <script src="<?= base_url(); ?>kaiadmin/assets/js/plugin/chart-circle/circles.min.js"></script>

    <!-- Datatables -->
    <!-- <scrip src="<?= base_url(); ?>kaiadmin/assets/js/plugin/datatables/datatables.min.js"></script> -->
    <!-- <script src="https://code.jquery.com/jquery-3.7.1.js"></script> -->
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
    <!-- Bootstrap Notify -->
    <script src="<?= base_url(); ?>kaiadmin/assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js"></script>

    <!-- jQuery Vector Maps -->
    <script src="<?= base_url(); ?>kaiadmin/assets/js/plugin/jsvectormap/jsvectormap.min.js"></script>
    <script src="<?= base_url(); ?>kaiadmin/assets/js/plugin/jsvectormap/world.js"></script>

    <!-- Sweet Alert -->
    <!-- <script src="assets/js/plugin/sweetalert/sweetalert.min.js"></script> -->

    <!-- Kaiadmin JS -->
    <script src="<?= base_url(); ?>kaiadmin/assets/js/kaiadmin.min.js"></script>

    <!-- Kaiadmin DEMO methods, don't include it in your project! -->
    <!-- <script src="kaiadmin/assets/js/setting-demo.js"></script>
    <script src="kaiadmin/assets/js/demo.js"></script> -->

    <script>
        new DataTable('#usersData');
    </script>
</body>

</html>