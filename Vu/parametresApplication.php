<?php

/**
 * Date création: 28.05.2020
 * Auteur : Thibault Cart
 * Nom fichier: parametresApplication.php
 */
require_once "../Controleur/function.php";

//Vérifie si l'utilisateur est connecté Sinon l'envoie vers le formulaire de login

if (!isset($_SESSION["infoUtilisateur"])) {
    header("location: login.php");
    exit();
}
$peutVoir = false;
foreach ($_SESSION["userRoles"] as $role) {
    if ($role["roleName"] == "Administrator") {
        $peutVoir = true;
    }
}
if ($peutVoir != true) {
    header("location: home.php");
    exit();
}


if (isset($_POST["submitFormParametre"])) {

    $dateDebutTPI = filter_input(INPUT_POST, 'dateDebutTPI', FILTER_SANITIZE_STRING);
    $dateFinTPI = filter_input(INPUT_POST, 'dateFinTPI', FILTER_SANITIZE_STRING);
    $dateDebutAnnee = filter_input(INPUT_POST, 'debutAnnee', FILTER_SANITIZE_STRING);
    $dateFinAnnee = filter_input(INPUT_POST, 'finAnnee', FILTER_SANITIZE_STRING);
    $nbMax = filter_input(INPUT_POST, 'nbExpert', FILTER_SANITIZE_STRING);
    $messageErreur = gestionModificationParametre($dateDebutAnnee, $dateFinAnnee, $dateDebutTPI, $dateFinTPI, $nbMax);
    if ($messageErreur != "OK") {

        echo '<script>alert(' . $messageErreur . ')</script>';
    }
}


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

<?php

include_once "inc/inc_HeaderNav.php";

?>
<div id="layoutSidenav_content">
    <main>
        <h3>Les parametres de l'application</h3>
        <?php
        $infoParametre = getParametreInformation();

        echo '<h5> Année actuel :' . $infoParametre["CurrentYear"] . '';
        echo '<h5> Debut d année :' . $infoParametre["YearBegin"] . '';
        echo '<h5> Fin d année :' . $infoParametre["YearEnd"] . '';
        echo '<h5> Nombre d expert Max :' . $infoParametre["NbMaxExpertForOneCandidate"] . '';
        echo '<h5> Début souhaité du TPI :' . $infoParametre["WhishesSessionStart"] . '';
        echo '<h5> Fin souhaité du TPI :' . $infoParametre["WischesSessionEnd"] . '';

        ?>
        <?php
        $peuxLeFaire = false;
        foreach ($_SESSION["droits"] as $droit) {
            if ($droit["rightName"] == "ModificationParametre") {
                $peuxLeFaire = true;
            }
        }

        if ($peuxLeFaire == true) {
            echo "<br><button type=\"button\" class=\"btn btn-primary\" data-toggle=\"modal\" data-target=\"#exampleModal\">
            Modifier les paramètres
        </button>";
        }
        ?>


        </br>
        <!---FORMULAIRE MODALE -->
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Ajout d'une classe</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true"></span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="#" method="post">
                            <label for="debutAnneeID">Date début année</label><input id="debutAnneeID" type="date" name="debutAnnee">
                            <label for="finAnneeID">Date fin année</label><input id="finAnneeID" type="date" name="finAnnee">
                            <label for="nbExpertID">Nombre d'expert max</label><input id="nbExpertID" name="nbExpert" type="number">
                            <label for="dateDebutTPI">Date début TPI</label> <input id="dateDebutTPI" type="datetime-local" name="dateDebutTPI">
                            <label for="dateFinID">Date Fin TPI</label> <input id="dateFinID" type="datetime-local" name="dateFinTPI">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <input type="submit" class="btn btn-primary" name="submitFormParametre">
                    </div>
                    </form>
                </div>
            </div>
        </div>


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