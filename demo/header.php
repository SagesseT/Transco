<?php
require "../config/config.php";
if (!isset($_SESSION["user_id"]) || !isset($_SESSION["role_id"])) {

    header("Location: login.php");
    exit();
}


// Ensure $role_id is initialized from the session
$role_id = isset($_SESSION["role_id"]) ? $_SESSION["role_id"] : null;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Custom fonts for this template-->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link href="../vendor/bootstrapc/css/bootstrap.min.css" rel="stylesheet">
    <link href="../vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="../vendor/aos/aos.css" rel="stylesheet">
    <link href="../vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="../vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
    <link href="../vendor/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link rel="shortcut icon" href="../img/transco.png" type="image/x-icon">
    <title>TRANSCO - SUIVI BILLETTERIE</title>

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
                <div class="sidebar-brand-icon">
                    <img src="../img/transco.png" alt="Transco Logo" style="width: 100; height: 50px;">
                </div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item">
                <a class="nav-link" href="index.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Tableau de bord</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">
                        <!-- Heading -->
            <div class="sidebar-heading">
                Controle Retour Tickets
            </div>
            <li class="nav-item">
                <a class="nav-link" href="gindex.php">
                    <i class="bi bi-graph-up-arrow"></i>
                <span>Controle Retour Tickets
                </span></a>
                <a class="nav-link" href="affectation.php">
                    <i class="bi bi-journal-text"></i> <!-- Changed icon -->
                <span>Registre d'affectation
                </span></a>
                <a class="nav-link" href="affectations_list.php">
                    <i class="bi bi-journal-text"></i> <!-- Changed icon -->
                <span>Historique d'affectation
                </span></a>
                <a class="nav-link" href="ajouter_affectation.php">
                    <i class="bi bi-plus-circle"></i> <!-- Changed icon -->
                <span>Ajouter un affectation
                </span></a>
            </li>
                        <!-- Heading -->
            <div class="sidebar-heading">
                Gestion de stock
            </div>
            <li class="nav-item">
                <a class="nav-link" href="sindex.php">
                    <i class="bi bi-ticket-perforated"></i>
                <span>Gestion de stocks</span></a>
                <a class="nav-link" href="mouvements.php">
                    <i class="bi bi-arrow-left-right"></i> <!-- Changed icon -->
                <span>Mouvements</span></a>
                <a class="nav-link" href="stock.php">
                    <i class="bi bi-box-seam"></i> <!-- Changed icon -->
                <span>Inventaire</span></a>

            </li>
            <div class="sidebar-heading">
                Administrateur
            </div>
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
                    aria-expanded="true" aria-controls="collapseTwo">
                    <i class="bi bi-tools"></i>
                    <span>Eléments de base</span>
                </a>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Eléments de base:</h6>
                        <a class="collapse-item" href="role.php">Rôle</a>
                        <a class="collapse-item" href="grade.php">Grade</a>
                        <a class="collapse-item" href="fonction.php">Fonction</a>
                        <a class="collapse-item" href="services.php">Service</a>
                        <a class="collapse-item" href="ligne.php">Ligne</a>
                    </div>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
                    aria-expanded="true" aria-controls="collapseUtilities">
                    <i class="fas fa-fw fa-wrench"></i>
                    <span>Administrateur</span>
                </a>
                <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Poste Administrateur:</h6>
                        <a class="collapse-item" href="utilisateur.php">Utulisateurs</a>
                        <a class="collapse-item" href="statut.php">Utilisateurs connecter</a>
                    </div>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../config/logout.php">
                <i class="bi bi-power"></i>
                    <span>Deconnection</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

            

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                        <li class="nav-item dropdown no-arrow d-sm-none">
                            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-search fa-fw"></i>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                                aria-labelledby="searchDropdown">
                                <form class="form-inline mr-auto w-100 navbar-search">
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-light border-0 small"
                                            placeholder="Search for..." aria-label="Search"
                                            aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button">
                                                <i class="fas fa-search fa-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </li>

                        <!-- Nav Item - Messages -->
                        

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"> <?php echo htmlspecialchars($_SESSION["compte_utilisateur"]); ?><br>
                                <?php echo htmlspecialchars($_SESSION["matricule"]); ?>. <br>
                                <?php echo htmlspecialchars($_SESSION["fonction"]); ?>.
                            </span>
                            <h1><i class="bi bi-person-fill"></i></h1>
                            </a>
                            <!-- Dropdown - User Information -->
</l>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">