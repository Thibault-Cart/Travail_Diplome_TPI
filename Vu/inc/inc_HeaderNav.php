<?php

/**
 * Date creation: 27.05.2020
 * Auteur : Thibault Cart
 * Nom fichier: inc_HeaderNav.php
 */
?>

<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-light" style="background-color:lightgray">
        <a class="navbar-brand" href="#">
            <img src="../img/LogoExpertsDev.svg" width="30" height="30" class="d-inline-block align-top" alt="" loading="lazy">
            CdEIG
        </a>

        <button class="btn btn-link btn-sm order-1 order-lg-0" id="sidebarToggle" href="#"><i class="fas fa-bars"></i>
        </button>
        <!-- Navbar Search-->
        <div class="d-none d-md-inline-block ml-auto mr-0 mr-md-3 my-2 my-md-0">
            <ul class="navbar-nav mr-auto">
                <!-- Menu principal quand l'utilisateur est identifié -->
                <li class="nav-item active">
                    <a class="nav-link" href="#">A propos <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Module 1</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Module 2</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Module 3</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Module 4</a>
                </li>
                <!-- fin du menu principal -->
            </ul>

        </div>
        <!-- Navbar-->
        <ul class="navbar-nav ml-auto ml-md-0">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="userDropdown" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                    <a class="dropdown-item" href="affichageInfoPerso.php">Paramètres</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="logout.php">Déconnexion</a>
                </div>
            </li>
        </ul>
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <!-- Menu secondaire (qui reprend le menu principal...) -->
                        <a class="nav-link" href="home.php">A propos</a>

                        <?php
                        $peutVoir = false;
                        foreach ($_SESSION["userRoles"] as $role) {
                            if ($role["roleName"] == "Administrator") {
                                $peutVoir = true;
                            }
                        }
                        if ($peutVoir == true) {
                            echo '<div class="sb-sidenav-menu-heading">Module Administration</div>
<a class="nav-link" href="gestionClasse.php">Gestion des classes</a>
<a class="nav-link" href="parametresApplication.php">Parametres de l\'application</a>
<a class="nav-link" href="gestionUtilisateur.php">Gestion Utilisateur</a>
<a class="nav-link" href="gestionRole.php">Gestion rôles et droits</a>';
                        }

                        ?>

                        <div class="sb-sidenav-menu-heading">rédaction des énoncés</div>
                        <a class="nav-link" href="home.php">Non implémenté</a>
                        <a class="nav-link" href="home.php">Non implémenté</a>
                        <a class="nav-link" href="home.php">Non implémenté</a>
                        <a class="nav-link" href="home.php">Non implémenté</a>
                        <div class="sb-sidenav-menu-heading">répartition des TPIs</div>
                        <a class="nav-link" href="home.php">Non implémenté</a>
                        <a class="nav-link" href="home.php">Non implémenté</a>
                        <a class="nav-link" href="home.php">Non implémenté</a>
                        <a class="nav-link" href="home.php">Non implémenté</a>
                        <div class="sb-sidenav-menu-heading">validation des énoncés</div>
                        <a class="nav-link" href="home.php">Non implémenté</a>
                        <a class="nav-link" href="home.php">Non implémenté</a>
                        <a class="nav-link" href="home.php">Non implémenté</a>
                        <a class="nav-link" href="home.php">Non implémenté</a>
                        <!-- Fin du menu secondaire -->
                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <?php
                    //affiche le nom de l'utilisateur et ses roles
                    echo '<div class="small">Connecté en tant que:</div>' . $_SESSION["infoUtilisateur"]["lastName"] . ' ' . $_SESSION["infoUtilisateur"]["firstName"] . ' <br>';
                    for ($i = 0; $i < (count($_SESSION["userRoles"])); $i++) {

                        echo $_SESSION["userRoles"][$i]["roleName"] . " ";
                    }

                    ?>

            </nav>
        </div>