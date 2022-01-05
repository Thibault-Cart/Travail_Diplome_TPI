<?php

/**
 * Date création: 26.05.2020
 * Auteur : Thibault Cart
 * Nom fichier: gestionClasse.php
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


$roles = getTousLesRoles();
$codeSelect = select("selectRole[]", null, $roles, null, "form-control");

if (isset($_POST["submitFormAjoutUser"])) {
    $nomUser = filter_input(INPUT_POST, "inputNomUser", FILTER_SANITIZE_STRING);
    $prenomUser = filter_input(INPUT_POST, "inputPrenomUser", FILTER_SANITIZE_STRING);
    $entreprise = filter_input(INPUT_POST, "inputEntrepriseUser", FILTER_SANITIZE_STRING);
    $Email = filter_input(INPUT_POST, "inputEmailUser", FILTER_SANITIZE_EMAIL);
    $telephone = filter_input(INPUT_POST, "inputTelephoneUser", FILTER_SANITIZE_NUMBER_INT);
    $roleUser = $_POST["selectRole"];
    $adresse = filter_input(INPUT_POST, "inputAdresseUser", FILTER_SANITIZE_STRING);

    $return = gestionAjoutUtilisateur($nomUser, $prenomUser, $entreprise, $Email, $telephone, $roleUser, $adresse);
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

        <h3>Liste des utilisateurs</h3>
        <?php
        $peuxLeFaire = false;
        foreach ($_SESSION["droits"] as $droit) {
            if ($droit["rightName"] == "AjoutUtilisateur") {
                $peuxLeFaire = true;
            }
        }

        if ($peuxLeFaire == true) {
            echo "<button type=\"button\" class=\"btn btn-primary\" data-toggle=\"modal\" data-target=\"#exampleModal\">
            Ajouter un utilisateur
        </button>";
        }
        ?>


        </br>
        <!---FORMULAIRE MODALE -->
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Ajout d'un utilisateur</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true"></span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="#" method="post">
                            Nom:
                            <input type="text" required name="inputNomUser"><br>
                            Prénom:
                            <input type="text" required name="inputPrenomUser"><br>
                            Entreprise:
                            <input type="text" required name="inputEntrepriseUser"><br>
                            Adresse:
                            <input type="text" required name="inputAdresseUser"><br>
                            Email:
                            <input type="email" required name="inputEmailUser"><br>
                            Telephone :
                            <input type="" required name="inputTelephoneUser"><br>
                            Role :
                            <?php
                            echo ($codeSelect);
                            ?>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <input type="submit" class="btn btn-primary" name="submitFormAjoutUser">
                    </div>
                    </form>
                </div>
            </div>
        </div>

        <table class="table">
            <tr>
                <thead>
                    <th scope="col">Nom</th>
                    <th scope="col">Prenom</th>
                    <th scope="col">Entreprise</th>
                    <th scope="col">Email</th>
                    <th scope="col">Telephone</th>
                    <th scope="col">Role</th>
                    <th scope="col">Modifier</th>
                    <th scope="col">Supprimer</th>

            </tr>
            </thead>
            </tbody>

            <?php
            $arrayUtilisateur = getTousLesUtilisateurs();

            //var_dump($arrayUtilisateur);
            foreach ($arrayUtilisateur as $valeur) {

                $role = getRoleParID($valeur["userID"]);
                echo "<tr>";
                echo '<td>' . $valeur["lastName"] . '</td>';
                echo '<td>' . $valeur["firstName"] . '</td>';
                echo '<td>' . $valeur["companyName"] . '</td>';
                echo '<td>' . $valeur["email"] . '</td>';
                echo '<td>' . $valeur["phone"] . '</td>';
                echo '<td>' . $role["role"] . '</td>';

                $peuxLeFaire = false;
                foreach ($_SESSION["droits"] as $droit) {
                    if ($droit["rightName"] == "ModificationUtilisateur") {
                        $peuxLeFaire = true;
                    }
                }

                if ($peuxLeFaire == true) {
                    echo '<td><a style="font: bold; color: blue" href="modificationUtilisateur.php?idUser=' . $valeur["userID"] . '"><button type="button" class="btn btn-primary">Modifier</button></a></td>';
                } else {
                    echo "<td></td>";
                }

                $peuxLeFaire = false;
                foreach ($_SESSION["droits"] as $droit) {
                    if ($droit["rightName"] == "SupressionUtilisateur") {
                        $peuxLeFaire = true;
                    }
                }

                if ($peuxLeFaire == true) {
                    echo '<td><a  style="font: bold; color: red" href="supressionUtilisateur.php?idUser=' . $valeur["userID"] . '"><button type="button" class="btn btn-danger">Supprimer</button>
</a></td>';
                } else {
                    echo "<td></td>";
                }






                echo "</tr>";
            }

            ?>
            </tbody>
            </tr>
        </table>
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