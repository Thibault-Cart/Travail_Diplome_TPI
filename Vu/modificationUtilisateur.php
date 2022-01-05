<?php

/**
 * Date création: 02.06.2020
 * Auteur : Thibault Cart
 * Nom fichier: modificationUtilisateur.php
 */
require_once "../Controleur/function.php";

//Vérifie si l'utilisateur est connecté Sinon l'envoie vers le formulaire de login
if (!isset($_SESSION["infoUtilisateur"])) {
    header("location: home.php");
    exit();
}

$peuxLeFaire = false;
foreach ($_SESSION["droits"] as $droit) {
    if ($droit["rightName"] == "ModificationUtilisateur") {
        $peuxLeFaire = true;
    }
}

if ($peuxLeFaire == false) {
    header("location: gestionUtilisateur.php");
    exit();
}



//recupere l'id de la classe et on sécurise le contenu de la variable
$idUser = $_GET["idUser"];

if ($idUser != "" || filter_var($idUser, FILTER_VALIDATE_INT)) {

    $infoUtilisateur = getUtilisateurParID($idUser);
    $roles = getTousLesRoles();
    $codeSelect = select("selectRole[]", null, $roles, null, "form-control");
} else {
    header("location: gestionClasse.php");
    exit();
}




if (isset($_POST["submitModification"])) {
    $nomUser = filter_input(INPUT_POST, "inputNomUser", FILTER_SANITIZE_STRING);
    $prenomUser = filter_input(INPUT_POST, "inputPrenomUser", FILTER_SANITIZE_STRING);
    $entreprise = filter_input(INPUT_POST, "inputEntrepriseUser", FILTER_SANITIZE_STRING);
    $Email = filter_input(INPUT_POST, "inputEmailUser", FILTER_SANITIZE_EMAIL);
    $telephone = filter_input(INPUT_POST, "inputTelephoneUser", FILTER_SANITIZE_NUMBER_INT);
    $roleUser = $_POST["selectRole"];
    $adresse = filter_input(INPUT_POST, "inputAdresseUser", FILTER_SANITIZE_STRING);
    $mdp = filter_input(INPUT_POST, "inputmdpUser", FILTER_SANITIZE_STRING);


    $messageErreur = gestionModificationUtilisateur($idUser, $nomUser, $prenomUser, $entreprise, $Email, $telephone, $roleUser, $adresse, $mdp);
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

        if (isset($messageErreur)) {
            foreach ($messageErreur as $message) {
                echo ' <h6 style="color: red;">' . $message . '</h6>';
            }
        }

        ?>
        <br>
        <h3>Modification de l'utilisateur</h3>
        <form class="form-group" action="#" method="post">
            Nom:
            <input type="text" class="form-control" value="<?php echo $infoUtilisateur["lastName"]; ?>" name="inputNomUser"><br>
            Prénom:
            <input type="text" class="form-control" value="<?php echo $infoUtilisateur["firstName"]; ?>" required name="inputPrenomUser"><br>
            Entreprise:
            <input type="text" class="form-control" value="<?php echo $infoUtilisateur["companyName"]; ?>" required name="inputEntrepriseUser"><br>
            Adresse:
            <input type="text" class="form-control" value="<?php echo $infoUtilisateur["address"]; ?>" required name="inputAdresseUser"><br>
            Email:
            <input type="email" class="form-control" value="<?php echo $infoUtilisateur["email"]; ?>" required name="inputEmailUser"><br>
            Telephone :
            <input class="form-control" value="<?php $affichage = $infoUtilisateur["phone"];
                                                echo $affichage; ?>" type="text" required name="inputTelephoneUser"><br>
            Mots de passe :
            <input class="form-control" value="<?php $affichagemdp = strtolower($infoUtilisateur["firstName"][0]);
                                                $affichagemdp .= strtolower($infoUtilisateur["lastName"][0]);
                                                $affichagemdp .= 1234;
                                                echo $affichagemdp; ?>" type="text" required name="inputmdpUser"><br>
            Role :
            <?php
            echo $codeSelect;
            ?>


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