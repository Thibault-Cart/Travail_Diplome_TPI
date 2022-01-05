<?php
/**
 * Auteur: Thibault Cart
 * Nom du fichier: supressionClasse.php
 * Description: recupere l'id de la classe passé et appel la fonction qui gere la supression
 */
require_once "../Controleur/function.php";

//Vérifie si l'utilisateur est connecté 
if (!isset($_SESSION["infoUtilisateur"])) {
    header("location: login.php");
    exit();
}

$peuxLeFaire = false;
foreach ($_SESSION["droits"] as $droit) {
    if ($droit["rightName"] == "SupressionClasse") {
        $peuxLeFaire = true;
    }
}

if ($peuxLeFaire == false) {
    header("location: gestionClasse.php");
    exit();
}


$idclasse=$_GET["idClasse"];
gestionSupressionClasse($idclasse);
header("location: gestionClasse.php");
exit();
