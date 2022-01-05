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

//permet d'afficher le
$roles = getTousLesRoles();
$touslesDroits = getTousDroit();


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

        <h3>Gestions des roles et des droits</h3>
        <form action="#" method="POST">
            <table class="table row flex-row flex-nowrap">
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


                    $contructionName = "input" . $value["roleName"];
                    $codeCheckBox = checkboxes($contructionName, $touslesDroits, $getDroit, array("disabled" => "disabled"));

                    for ($i = 0; $i < count($codeCheckBox); $i++) {
                        echo "<td>" . $codeCheckBox[$i] . "</td>";
                    }

                    $peuxLeFaire = false;
                    foreach ($_SESSION["droits"] as $droit) {
                        if ($droit["rightName"] == "ModificationDroits") {
                            $peuxLeFaire = true;
                        }
                    }

                    if ($peuxLeFaire == true) {
                        echo '<td><a  style="font: bold; color: blue" href="modificationRole.php?idRole=' . $value["roleID"] . '"><button type="button" class="btn btn-primary">Modifier</button>
                    </a></td>';
                    } else {
                        echo "<td></td>";
                    }


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