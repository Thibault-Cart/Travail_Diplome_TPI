<?php

/**
 * Date creation: 25.05.2020
 * Auteur : Thibault Cart
 * Nom fichier: function.php
 */
require_once "../Modele/requete.php";

/**
 * @param email et le mots de passe saisie par l'utilisateur
 * @author Thibault Cart
 * @return un message d'erreur si il y'en a un
 */


function procedureConnexion($emailLogin, $mdpLogin)
{
    //Vérifie si le mail et valide et si les données saisies sont valables
    if (!filter_var($emailLogin, FILTER_VALIDATE_EMAIL) || $emailLogin == "" || $mdpLogin == "") {
        return "Merci de saisir des données valides";
    } else {
        //Récupere les données de la base qui corresponde à cet email
        $info = getUtilisateurParMail($emailLogin);
        //si la base n'a pas cet email enregistrer
        if ($info == null) {
            return "l'adresse mail et le mot de passe ne correspondent à aucun compte";
        } else {
            //concaténation du MDP et du Sel
            $mdpCrypter = $mdpLogin . $info["pwdSalt"];
            // cryptage de du mots de passe en clair avec l'algorithme SHA-1
            $mdpCrypter = sha1($mdpCrypter);
            //si les mots de passe sont identique
            if ($mdpCrypter == $info["pwdHash"]) {

                $_SESSION["infoUtilisateur"] = $info;
                $idUser = $_SESSION["infoUtilisateur"]["userID"];

                //on va chercher tous les roles de l'utilistaeur et on les mets dans  une variable de session
                $_SESSION["userRoles"] = getRoleUtilisateur($idUser);
                $tousLesDroits = array();
                foreach ($_SESSION["userRoles"] as $role) {

                    $droit = getDroitName($role["roleID"]);
                    array_push($tousLesDroits, $droit);
                }
                $tousLesDroits = call_user_func_array('array_merge', $tousLesDroits);
                $_SESSION["droits"] = $tousLesDroits;


                return "ConnexionOk";
            } else {
                return "l'adresse mail et le mot de passe ne correspondent a aucun compte";
            }
        }
    }
}

/**
 * @param id de la classe passé en parametre
 * @return rien
 * @author Thibault Cart
 */
function gestionSupressionClasse($idclasse)
{

    if ($idclasse == "") {
    } else {
        $nombreEleve = GetNbEleve($idclasse);
        var_dump($nombreEleve);
        if ($nombreEleve[0] > 0) {
        } else {
            SupressionClasse($idclasse);
        }
    }
}

/**
 * @param rien
 * @return une chaine de 10 charactere aléatoire encrypter en sha-1
 * @author Thibault Cart
 */
function creationSel()
{
    $chaineEnCLair = "";
    $source = "abcdefghijklmnopqrstuvwxyz0987654321";
    for ($i = 0; $i < 10; $i++) {
        $poslettre = rand(0, 35);
        $caratere = $source[$poslettre];
        $chaineEnCLair .= $caratere;
    }
    $chainecrypter = sha1($chaineEnCLair);

    return $chainecrypter;
}

/**
 * @param identifiant de l'utilisateur, et les 2 mots de passe saisis pas l'utilisateur
 * @return un message d'erreur
 * @author Thibault Cart
 */
function gestionChangementMDP($idUser, $mdp1, $mdp2)
{
    if ($mdp1 == $mdp2 && $mdp1 != null && $mdp1 != "") {
        $sel = creationSel();
        var_dump($sel);
        $mdpCrypter = $mdp1 . $sel;
        $mdpCrypter = sha1($mdpCrypter);
        ModifierSelEtMDP($idUser, $mdpCrypter, $sel);
    } else {
        return "merci de saisir le nouveau mots de passe correctement";
    }
}

/**
 * @param identifiant de l'utilisateur, et les 2 mots de passe saisis pas l'utilisateur
 * @return Rien
 * @author Thibault Cart
 */
function gestionModificationParametre($dateDebutAnnee, $dateFinAnnee, $dateDebutTPI, $dateFinTPI, $nbMax)
{
    $tousOK = false;
    $anneeActuel = gmdate('Y');
    $tableauParametre = array();
    if ($dateFinAnnee > $dateDebutAnnee && isset($dateDebutAnnee) && isset($dateFinAnnee)) {
        $tousOK = true;
    } else {
        $tousOK = false;
    }
    if ($dateDebutTPI < $dateFinTPI && isset($dateDebutTPI) && isset($dateFinTPI)) {
        $tousOK = true;
        $dateDebutTPI = str_replace("T", " ", $dateDebutTPI);
        $dateFinTPI = str_replace("T", " ", $dateFinTPI);
    } else {
        $tousOK = false;
    }

    if (is_numeric($nbMax) && $nbMax > 0 && isset($nbMax)) {
        $tousOK = true;
    } else {
        $tousOK = false;
    }

    if ($tousOK == true) {
        array_push($tableauParametre, $anneeActuel);
        array_push($tableauParametre, $dateDebutAnnee);
        array_push($tableauParametre, $dateFinAnnee);
        array_push($tableauParametre, $nbMax);
        array_push($tableauParametre, $dateDebutTPI);
        array_push($tableauParametre, $dateFinTPI);
        array_push($tableauParametre, $dateFinTPI);

        for ($i = 0; $i < count($tableauParametre); $i++) {
            changeparametre($i + 1, $tableauParametre[$i]); // id param et nouveau param
        }
        return "OK";
    } else {
        return "Merci de revoir les valeurs saisies";
    }
}

/**
 * Construit un élément SELECT basé sur le tableau associatif $liste,
 * sélectionnant l'option $courant, et ajoute les éventuels attributs à
 * la balise select
 * @param string $name nom du champ de saisie
 * @param string $id id de la balise
 * @param array $options tableau associatif value=>texte de l'option
 * @param mixed $default sert à indiquer l'option initialement sélectionnée
 * @param array $class classe attribuér à la balise
 * @return string élement html select prêt à l'emploi
 */
function select($name, $id, $options, $default = 4, $htmlClass = null)
{
    $selectElement = '<select  multiple="multiple" name="' . $name . '" ';
    if (!empty($id)) {
        $selectElement .= 'id="' . $id . '" ';
    }
    if (!empty($htmlClass)) {
        $selectElement .= 'class="' . $htmlClass . '" ';
    }

    $selectElement .= ">\n";
    foreach ($options as $value)
        if ($value["roleID"] == $default) {
            $selectElement .= '<option value="' . $value["roleID"] . '" selected >' . $value["roleName"] . '</option>\n';
        } else {
            $selectElement .= '<option value="' . $value["roleID"] . '" selected >' . $value["roleName"] . '</option>\n';
        }

    $selectElement .= "</select>";
    return $selectElement;
}
/**
 * @param les informations personnelles du nouveau l'utlisateur (nom,prenom,entreprise,email,telephone,roles,adresse)
 * @return Rien
 * @author Thibault Cart
 */
function gestionAjoutUtilisateur($nomUser, $prenomUser, $entreprise, $email, $telephone, $roleUser, $adresse)
{
    $estOK = true;
    if ($nomUser == "" || $prenomUser == "" || $entreprise == "" || $email == "" || $telephone == "" || $adresse == "") {
        $estOK = false;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $estOK = false;
    }
    $estInscrit = estDejaInscrit($email);

    if (!empty($estInscrit)) {
        $estOK = false;
    }
    if (empty($roleUser)) {
        $roleUser[0] = 5;
    } else {
        foreach ($roleUser as $numeroRole) {
            if (!is_numeric($numeroRole)) {
                $estOK = false;
            }
        }
    }

    if ($estOK == true) {
        $sel = creationSel();
        $mdp = null;
        $mdp .= strtolower($prenomUser[0]);
        $mdp .= strtolower($nomUser[0]);
        $mdp .= 1234;
        $mdp .= $sel;
        $mdp = sha1($mdp);

        AjoutUser($nomUser, $prenomUser, $entreprise, $adresse, $email, $telephone, $mdp, $sel, $roleUser);
    }
}
/**
 * @param id de l'utilisateur a supprimmer
 * @return Rien
 * @author Thibault Cart
 */
function gestionSuppressionUtilisateur($idUser)
{
    $idUserConnecter = $_SESSION["infoUtilisateur"]["userID"];
    $dansTpi = getTPI($idUser);
    $idUser = number_format($idUser);

    if ($idUser == $idUserConnecter || !empty($dansTpi)) {
    } else {
        suppressionUtilisateurs($idUser);
    }
}
/**
 * @param les nouvelles informations de l'utilisateur
 * @return message d'erreur
 * @author Thibault Cart
 */
function gestionModificationUtilisateur($idUser, $nomUser, $prenomUser, $entreprise, $Email, $telephone, $roleUser, $adresse, $mdp)
{
    $sel = creationSel();
    $messageErreur = [];
    $estOK = true;
    if ($idUser == "" || $nomUser == "" || $prenomUser == "" || $entreprise == "" || $Email == "" || $mdp == "" || $telephone == "" || empty($roleUser) || $adresse == "") {
        array_push($messageErreur, "Merci de saisir des valeurs correctes");
        $estOK = false;
    }
    if (!is_numeric($telephone)) {
        array_push($messageErreur, "Merci de saisir un numéro correct");
        $estOK = false;
    }
    if (!filter_var($Email, FILTER_VALIDATE_EMAIL)) {
        array_push($messageErreur, "Merci de saisir un Email correct");
        $estOK = false;
    }
    foreach ($roleUser as $valeur) {
        if (!filter_var($valeur, FILTER_SANITIZE_NUMBER_INT)) {
            $estOK = false;
            array_push($messageErreur, "Merci de saisir des rôles corrects");
        }
    }
    $mdp .= $sel;
    $mdp = sha1($mdp);

    if ($estOK == true) {
        modificationUtilisateur($idUser, $nomUser, $prenomUser, $entreprise, $Email, $telephone, $roleUser, $adresse, $mdp, $sel);
    } else {
        return $messageErreur;
    }
}

/**
 * Construit une série de cases à cocher basée sur le tableau associatif $liste,
 * cochant l'ensemble de éléments cochés signalé comme tel dans le tableaus $checked
 * et ajoute les éventuels attributs
 * @param string $name nom des champs
 * @param array $options liste des options
 * @param array $checked liste des options sélectionnées
 * @param array $attributes tableau associatif comportant des attributs
 *      complémentaires à ajouter à la balise SELECT
 * @return array tableau d'élements html input/checkbox prêts à l'emploi
 */

function checkboxes($name, $options, $checked, $attributes = null)
{
    $cbs = array();
    foreach ($options as $value => $item) {
        $cb =  '<label><input type="checkbox" name="' . $name . '[]" ' .
            'id="' . $name . $value . '"';

        if (is_array($attributes))
            foreach ($attributes as $attr => $val)
                $cb .= ' ' . $attr . '="' . $val . '" ';
        $cb .= 'value="' . $value . '" ';

        if (in_array($value, $checked))
            $cb .= 'checked ';

        $cb .= ' /></label>';

        $cbs[] = $cb;
    }
    return $cbs;
}

function changementDroit($idRole, $droit)
{
    $droitFiltrer = true;
    foreach ($droit as $valeur) {
        if (!filter_var($valeur, FILTER_VALIDATE_INT)) {
            $droitFiltrer = false;
        }
    }

    if ($droitFiltrer == true) {
        supprimmerDroitParID($idRole);
        foreach ($droit as $valeur) {
            attributionDroit($idRole, $valeur);
        }
    }
}
