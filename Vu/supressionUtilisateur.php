<?php
/**
 * Auteur: Thibault Cart
 * Nom du fichier: supressionUtilisateur.php
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
    if ($droit["rightName"] == "SupressionUtilisateur") {
        $peuxLeFaire = true;
    }
}

if ($peuxLeFaire == false) {
    header("location: gestionUtilisateur.php");
    exit();
}

$idUser=$_GET["idUser"];
if(filter_var($idUser,FILTER_VALIDATE_INT)){
    gestionSuppressionUtilisateur($idUser);
}
header("location: gestionUtilisateur.php");
exit();
