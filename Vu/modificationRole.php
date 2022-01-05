<?php

/**
 * Date création: 05.06.2020
 * Auteur : Thibault Cart
 * Nom fichier: modificationRole.php
 */
require_once "../Controleur/function.php";

//Vérifie si l'utilisateur est connecté Sinon l'envoie vers le formulaire de login
if (!isset($_SESSION["infoUtilisateur"])) {
    header("location: home.php");
    exit();
}

$peuxLeFaire = false;
foreach ($_SESSION["droits"] as $droit) {
    if ($droit["rightName"] == "ModificationDroits") {
        $peuxLeFaire = true;
    }
}

if ($peuxLeFaire == false) {
    header("location: home.php");
    exit();
}

//recupere l'id de la classe et on sécurise le contenu de la variable
$idRole = $_GET["idRole"];

if ($idRole != "" && filter_var($idRole, FILTER_VALIDATE_INT)) {
    $touslesDroits = getTousDroit();
    $roles = getRoleParID($idRole);
} else {
    header("location: gestionRole.php");
    exit();
}


if (isset($_POST["submitDroit"])) {
    if (isset($_POST["inputDroit"])) {
        $nouveauDroits = $_POST["inputDroit"];
    } else {
        $nouveauDroits = array();
    }
    changementDroit($idRole, $nouveauDroits);
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

        ?>
        <br>
        <h3>Modification des droits</h3>
        <form action="#" method="POST">
            <table class="table">
                </tr>

                <?php

                echo "<th>Role</th>";
                foreach ($touslesDroits as $td) {
                    echo '<th>' . $td . '</th>';
                }
                echo "<th>MODIFICATION</th>";
                echo "</tr>";
                foreach ($roles as $value) {
                    echo "<tr>";
                    echo '<th>' . $value["roleName"] . '</th>';
                    $getDroit = getDroit($value["roleID"]);


                    $codeCheckBox = checkboxes("inputDroit", $touslesDroits, $getDroit, null);
                    for ($i = 0; $i < count($codeCheckBox); $i++) {
                        echo "<td>" . $codeCheckBox[$i] . "</td>";
                    }
                    echo '<td><input type="submit" value="Modifier"  name="submitDroit" type="button" class="btn btn-primary" >
                    </a> </td>';
                    echo "</tr>";
                }


                ?>

                </br>

            </table>
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