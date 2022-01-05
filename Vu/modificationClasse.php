<?php

/**
 * Date création: 26.05.2020
 * Auteur : Thibault Cart
 * Nom fichier: modificationClasse.php
 */
require_once "../Controleur/function.php";

//Vérifie si l'utilisateur est connecté Sinon l'envoie vers le formulaire de login
if (!isset($_SESSION["infoUtilisateur"])) {
    header("location: home.php");
    exit();
}


$peuxLeFaire = false;
foreach ($_SESSION["droits"] as $droit) {
    if ($droit["rightName"] == "ModificationClasse") {
        $peuxLeFaire = true;
    }
}

if ($peuxLeFaire == false) {
    header("location: gestionClasse.php");
    exit();
}


//recupere l'id de la classe et on sécurise le contenu de la variable
$idClasse = $_GET["idClasse"];
$idClasse = filter_var($idClasse, FILTER_SANITIZE_NUMBER_INT);
if ($idClasse != "" || $idClasse != null) {
    $infoClasse = getClasseInfoId($idClasse);
} else {
    header("location: gestionClasse.php");

    exit();
}


//traitement formulaire
if (isset($_POST["submitModification"])) {

    //récuperation des données du formulaire
    $nouveauNomClasse = filter_input(INPUT_POST, 'inputNomClasse', FILTER_SANITIZE_STRING);

    if ($nouveauNomClasse != "" && $nouveauNomClasse != null) {
        changeNomClasse($idClasse, $nouveauNomClasse);
        header("location: gestionClasse.php");
        exit();
    } else {
        header("location: gestionClasse.php");
        exit();
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
        <form action="#" method="post">

            <div class="form-group">
                Modifier le Nom de la classe
                <input type="text" required class="form-control" name="inputNomClasse" placeholder="<?php echo $infoClasse[0]["className"] ?>">

            </div>

            <button type="submit" name="submitModification" class="btn btn-primary">Modifier</button>

        </form>
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