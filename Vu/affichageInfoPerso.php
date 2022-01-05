<?php

/**
 * Date création: 28.05.2020
 * Auteur : Thibault Cart
 * Nom fichier: affichagePerso.php
 */
require_once "../Controleur/function.php";

//Vérifie si l'utilisateur est connecté Sinon l'envoie vers le formulaire de login
if (!isset($_SESSION["infoUtilisateur"])) {
    header("location: login.php");
    exit();
}

//si le formulaire d'ajout est validé
if (isset($_POST["submitFormchangementMDP"])) {
    $nouveauMDP1 = filter_input(INPUT_POST, 'inputNouveauMDP', FILTER_SANITIZE_STRING);
    $nouveauMDP2 = filter_input(INPUT_POST, 'inputNouveauMDPVerification', FILTER_SANITIZE_STRING);
    $idUser = $_SESSION["infoUtilisateur"]["userID"];
    $resultat = gestionChangementMDP($idUser, $nouveauMDP1, $nouveauMDP2);
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

        <?php

        echo '<h5>Prenom : ' . $_SESSION["infoUtilisateur"]["firstName"] . '';
        echo '<h5>Nom : ' . $_SESSION["infoUtilisateur"]["lastName"] . '';
        echo '<h5>Adresse : ' . $_SESSION["infoUtilisateur"]["address"] . '';
        echo '<h5>Entreprise : ' . $_SESSION["infoUtilisateur"]["companyName"] . '';
        echo '<h5>Mail : ' . $_SESSION["infoUtilisateur"]["email"] . '';
        echo '<h5>Telephone : ' . $_SESSION["infoUtilisateur"]["phone"] . '';


        $nbrole = sizeof($_SESSION["userRoles"]);
        $estCandidat = false;
        if ($nbrole == 0) {
            echo "<h5> Vous n'avez pas de roles";
        } elseif ($nbrole == 1) {
            echo '<h5>Votre role :' . $_SESSION["userRoles"][0]["roleName"] . '';
        } elseif ($nbrole >= 2) {
            echo "<h5> Vos Roles";
            foreach ($_SESSION["userRoles"] as $role) {
                echo "<br>";
                echo $role["roleName"];
                if ($role["roleName"] == "Candidate") {
                    $estCandidat = true;
                }
            }
        }
        echo "<br>";
        if ($estCandidat == true) {

            $idUser = $_SESSION["infoUtilisateur"]["userID"];
            $listClasse = getClasseParIDEleve($idUser);

            if (count($listClasse) == 1) {
                echo "Votre classe: <br>";
                echo $listClasse[0]["className"];
            } elseif (count($listClasse) > 1) {
                echo "Vos classes";

                foreach ($listClasse as $classe) {
                    echo "<br>";
                    echo $classe["className"];
                }
            }
        }


        ?>
        </br>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
            Modifier mon mot de passe
        </button>

        </br>
        <!---FORMULAIRE MODALE -->
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Modification du mots de passe</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true"></span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="#" method="post">
                            Nouveau mot de passe
                            <input style="width: 150px;" type="password" required name="inputNouveauMDP">
                            Vérification mot de passe
                            <input style="width: 150px;" type="password" required name="inputNouveauMDPVerification">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <input type="submit" class="btn btn-primary" name="submitFormchangementMDP">
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