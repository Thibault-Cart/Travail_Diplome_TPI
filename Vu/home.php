<?php

/**
 * Auteur: Thibault Cart
 * Nom du fichier: home.php
 * Description: page principale du site
 * Date création: 25.05.2020
 */



require_once "../Controleur/function.php";

//Vérifie si l'utilisateur est connecté Sinon l'envoie vers le formulaire de login
if (!isset($_SESSION["infoUtilisateur"])) {
    header("location: login.php");
    exit();
}

?>
<?php
//Include la nav
include_once "./inc/inc_HeaderNav.php";
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Collège d'Experts Informatique de Genève</title>
    <!-- Mise en page basée sur le template Start Bootstrap - Admin (https://startbootstrap.com/templates/sb-admin/) -->
    <link href="../css/styles.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/js/all.min.js" crossorigin="anonymous"></script>

</head>
<div id="layoutSidenav_content">
    <main>
        <!-- Début du contenu de la page -->
        <div class="container-fluid">
            <h1 class="mt-4">Collège d'Experts Informatique de Genève</h1>
            <h2>Outil de collaboration et de gestion des TPIs </h2>
            <p>Le but de cette application est de donner aux membres du collège d'experts en informatique
                du canton de Genève un outil leur permettant de gérer leur travail tout au long de l'année scolaire.
                Le développement a été confié a des élèves de l'école d'informatique, dans le cadre de leur TPI.
                Les modules développés initialement sont :</p>
            <ul>
                <li>L'administration des utilisateurs</li>
                <li>La rédaction des énoncés des TPIs</li>
                <li>La répartition des TPIs entre les experts</li>
                <li>La validation des énoncés</li>
                <li>L'évaluation des TPis</li>
            </ul>
            <p>Cette partie du développement concerne le module XXX, développé par NOM PRENOM</p>
        </div>
        <!-- fin du contenu de la page-->
    </main>
    <footer class="py-4 bg-light mt-auto">
        <div class="container-fluid">
            <div class="d-flex align-items-center justify-content-between small">
                <div class="text-muted">TPI 2020 - développé par Cart Thibault</div>
                <div class="text-muted">Application en cours de développement</div>
            </div>
        </div>
    </footer>
</div>
</div>
<script src="https://code.jquery.com/jquery-3.4.1.min.js" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="../js/scripts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
</body>

</html>