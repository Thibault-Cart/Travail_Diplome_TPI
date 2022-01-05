<?php

/**
 * Date création: 25.05.2020
 * Auteur : Thibault Cart
 * Nom fichier: requete.php
 */
require_once "connexion.php";
session_start();

/**
 * @param mail saisie par l'utilisateur
 * @author Thibault Cart
 * @return toutes les informations présentes dans la table users correspondant a ce mail
 */
function getUtilisateurParMail($emailLogin)
{

    $sql = "SELECT *
    FROM `users`
    WHERE `email` = :mail";

    $request = connect()->prepare($sql);
    $request->bindParam(":mail", $emailLogin, PDO::PARAM_STR);
    $request->execute();

    return $request->fetch(PDO::FETCH_ASSOC);
}

/**
 * @param Id de utilisateur
 * @return toutes les informations présentes dans la table users correspondant a cette ID
 * @author Thibault Cart
 */
function getUtilisateurParID($idUser)
{

    $sql = "SELECT *
    FROM `users`
    WHERE `userID` = :idUser";

    $request = connect()->prepare($sql);
    $request->bindParam(":idUser", $idUser, PDO::PARAM_STR);
    $request->execute();

    return $request->fetch(PDO::FETCH_ASSOC);
}

/**
 * @param L'id de l'utilisateur connecter
 * @return les roles de l'utilisateur connecté
 *
 * @author Thibault Cart
 */
function getRoleUtilisateur($idUser)
{
    $sql = "SELECT r.roleID,r.roleName FROM roles as r 
    LEFT JOIN user_roles as ur ON ur.roleID=r.roleID
     WHERE ur.userID=:id";
    $request = connect()->prepare($sql);
    $request->bindParam(":id", $idUser, PDO::PARAM_STR);
    $request->execute();

    return $request->fetchall(PDO::FETCH_ASSOC);
}


/**
 * @param l'id de l'utilisateur
 * @return les classes dans lequel l'utilisateur est connecté
 * @author Thibault Cart
 */
function getClasseUtilisateur($idUser)
{
    $sql = "SELECT c.classId,c.className,c.year 
FROM `classes` as c,`user_classes` as uc 
WHERE uc.ClassID=c.classID AND uc.userCandidateID=:id";

    $request = connect()->prepare($sql);
    $request->bindParam(":id", $idUser, PDO::PARAM_STR);
    $request->execute();

    return $request->fetch(PDO::FETCH_ASSOC);
}

/**
 * @param aucun
 * @return toutes les classes présentes dans la base, le tous trié par année et par nom de la classe
 * @author Thibault Cart avec l'aide de monsieur Comminot
 */
function getTouteLesClasse()
{
    $sql = "SELECT classID,className,year, COUNT(userCandidateID) as nbEleves FROM `classes`
 LEFT JOIN user_classes USING (classID) 
 GROUP BY classID ORDER by year,className";

    $request = connect()->prepare($sql);

    $request->execute();

    return $request->fetchall(PDO::FETCH_ASSOC);
}

/**
 * @param l'id de la classe
 * @author Thibault Cart
 * @return les informations concernant la classe présentes dans la base
 *
 */
function getClasseInfoId($idClassse)
{
    $sql = "SELECT * FROM `classes` WHERE classID=:id";

    $request = connect()->prepare($sql);
    $request->bindParam(":id", $idClassse, PDO::PARAM_STR);

    $request->execute();

    return $request->fetchall(PDO::FETCH_ASSOC);
}

/**
 * @param l'id de la classe où l'on veut connaitre le nombre d'éleve
 * @author Thibault Cart
 * @return le nombre d'éleves de cette classe
 */
function getNbEleve($idClasse)
{
    $sql = "SELECT COUNT(*) FROM user_classes WHERE classID=:id";

    $request = connect()->prepare($sql);
    $request->bindParam(":id", $idClasse, PDO::PARAM_STR);
    $request->execute();

    return $request->fetch(PDO::FETCH_NUM);
}

/**
 * @param l'id de la classe que nous voulons Supprimer
 * @author Thibault Cart
 * @return rien
 *
 */
function SupressionClasse($idClasse)
{

    $sql = "DELETE FROM `user_classes` WHERE ClassID=:id";
    $request = connect()->prepare($sql);
    $request->bindParam(":id", $idClasse, PDO::PARAM_STR);
    $request->execute();

    $sql = "DELETE FROM `classes` WHERE classID=:id";

    $request = connect()->prepare($sql);
    $request->bindParam(":id", $idClasse, PDO::PARAM_STR);
    $request->execute();


    return $request->fetch(PDO::FETCH_ASSOC);
}


/**
 * @param l'id de la classe dont on veut changer le nom
 * @param Nouveau om de la classe
 * @author Thibault Cart
 * @return Rien
 */
function changeNomClasse($idClasse, $nouveauNomClasse)
{

    $sql = "UPDATE classes SET className=:nom WHERE classID=:id ";
    $request = connect()->prepare($sql);
    $request->bindParam(":nom", $nouveauNomClasse, PDO::PARAM_STR);
    $request->bindParam(":id", $idClasse, PDO::PARAM_STR);
    $request->execute();
}

/**
 * @param nom de la nouvelle classe 
 * @param Date de la volée de la nouvelle classe 
 * @author Thibault Cart
 * @return Rien
 *
 */
function ajoutClasse($nomNouvelleClasse, $dateClasse)
{
    $sql = connect()->prepare("INSERT INTO classes(classID,className,year) 
                    VALUES (:classID,:className,:date)");

    $sql->execute([
        'classID' => null,
        'className' => $nomNouvelleClasse,
        'date' => $dateClasse,
    ]);
    return connect()->lastInsertId();
}

/**
 * @param id de l'élève dont nous voulons la ou les classes
 * @return  un tableau contenant toutes les classes où l'éleve est inscrit
 * @author Thibault Cart
 */
function getClasseParIDEleve($idEleve)
{
    $sql = connect()->prepare("SELECT classID,className,year,userCandidateID  FROM `classes` as c
        LEFT JOIN user_classes as uc USING (classID) 
            WHERE uc.userCandidateID=:id
        GROUP BY classID ORDER by year,className");
    $sql->bindParam(":id", $idEleve, PDO::PARAM_STR);

    $sql->execute();
    return $sql->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * @param id de l'utilisateur dont le mot de passe va être modifier
 * @param mots de passe crypter en Sha-1 et le sel (qui est une chaine de 10 carateres aléatoires crypter en sha-1)
 * @param Sel qui a permis de crypter le mot de passe
 * @return Rien
 * @author Thibault Cart
 */
function ModifierSelEtMDP($idUser, $mdpCrypter, $sel)
{

    $sql = "UPDATE users SET pwdHash=:mdp,pwdSalt=:salt WHERE userID=:id";
    $request = connect()->prepare($sql);
    $request->bindParam(":mdp", $mdpCrypter, PDO::PARAM_STR);
    $request->bindParam(":salt", $sel, PDO::PARAM_STR);
    $request->bindParam(":id", $idUser, PDO::PARAM_STR);
    $request->execute();
}

/**
 * @param Rien
 * @return Les parametres de l application
 * @author Thibault Cart
 */
function getParametreInformation()
{

    $sql = "SELECT name,value FROM `params`";

    $request = connect()->prepare($sql);

    $request->execute();
    return $request->fetchall(PDO::FETCH_KEY_PAIR);
}

/**
 * @param id du paramètre à modifier et sa nouvelle valeur
 * @return Rien
 * @author Thibault Cart
 */
function changeparametre($idParametre, $valeurparametre)
{
    $sql = "UPDATE params SET value=:valeurparam WHERE paramID=:idParametre";
    $request = connect()->prepare($sql);
    $request->bindParam(":valeurparam", $valeurparametre, PDO::PARAM_STR);
    $request->bindParam(":idParametre", $idParametre, PDO::PARAM_STR);
    $request->execute();
}

/**
 * @param Rien
 * @return Tous les utilisateurs enregistrer dans la base
 * @author Thibault Cart
 */
function getTousLesUtilisateurs()
{
    /*
        $sql = "SELECT lastName,email,GROUP_CONCAT(DISTINCT roleName)
        FROM `users` as u, user_roles as ur
        LEFT JOIN roles as r ON r.roleID=ur.roleID
        WHERE u.userID=ur.userID
        ";
    */
    $sql = "SELECT * FROM users";
    $request = connect()->prepare($sql);

    $request->execute();

    return $request->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * @param id de l'utilisateur auquel nous voulons connaître les rôles
 * @author Thibault Cart
 * @return Tous les roles appartenant a l'utilisateur
 */
function getRoleParID($idUser)
{
    $sql = "SELECT GROUP_CONCAT(DISTINCT roleName) as `role`  
    FROM roles as r LEFT JOIN user_roles as ur ON ur.userID=:idUser 
    WHERE ur.roleID=r.roleID
    ";

    $request = connect()->prepare($sql);
    $request->bindParam(":idUser", $idUser, PDO::PARAM_STR);

    $request->execute();

    return $request->fetch(PDO::FETCH_ASSOC);
}

/**
 * @param rien
 * @return Tous les roles présents dans la base de donnée
 * @author Thibault Cart
 */
function getTousLesRoles()
{
    $sql = "SELECT * FROM roles";
    $request = connect()->prepare($sql);

    $request->execute();

    return $request->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * @param email de l'utilisateur que nous voulons isncrire
 * @author Thibault Cart
 * @return rien si email pas présent dans la base ou alors toutes les informations de l'utilisateur possédant déjà ce mail
 */
function estDejaInscrit($email)
{
    $sql = "SELECT * FROM users WHERE email=:email ";
    $request = connect()->prepare($sql);
    $request->bindParam(":email", $email, PDO::PARAM_STR);

    $request->execute();

    return $request->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * @param Nom du nouvel utilisateur à ajouter à la base de données
 * @param Prenom du nouvel utilisateur à ajouter à la base de données
 * @param Entreprise du nouvel utilisateur à ajouter à la base de données
 * @param Adresse du nouvel utilisateur à ajouter à la base de données
 * @param Email du nouvel utilisateur à ajouter à la base de données
 * @param Telephone du nouvel utilisateur à ajouter à la base de données
 * @param Mot de passe du nouvel utilisateur à ajouter à la base de données
 * @param Sel du nouvel utilisateur à ajouter à la base de données
 * @param Role du nouvel utilisateur à ajouter à la base de données, contenu dans un tableau
 * @author Thibault Cart
 * @return Rien
 *
 */
function ajoutUser($lastName, $firstName, $companyName, $adresse, $mail, $phone, $pswHash, $pswSalt, $roles)
{
    $sql = "INSERT INTO `users`(`userID`, `lastName`, `firstName`, `companyName`, `address`, `email`, `phone`, `pwdHash`, `pwdSalt`) 
VALUES (null,:lastName,:firstName,:company,:adresse,:email,:phone,:pswhash,:pswSalt)";
    $request = connect()->prepare($sql);

    $request->bindParam(":lastName", $lastName, PDO::PARAM_STR);
    $request->bindParam(":firstName", $firstName, PDO::PARAM_STR);
    $request->bindParam(":company", $companyName, PDO::PARAM_STR);
    $request->bindParam(":adresse", $adresse, PDO::PARAM_STR);
    $request->bindParam(":email", $mail, PDO::PARAM_STR);
    $request->bindParam(":phone", $phone, PDO::PARAM_STR);
    $request->bindParam(":pswhash", $pswHash, PDO::PARAM_STR);
    $request->bindParam(":pswSalt", $pswSalt, PDO::PARAM_STR);
    $request->execute();
    $lastid = connect()->lastInsertId();

    foreach ($roles as $numeroRole) {

        $sql = "INSERT INTO `user_roles`(`userID`, `roleID`) VALUES ($lastid,$numeroRole)";
        $request = connect()->prepare($sql);
        $request->execute();
    }
}

/**
 * @param id de l'utilisateur que nous voulons supprimmer
 * @author Thibault Cart
 * @return les informations des différents TPIs correspondant à cet utilisateur
 */
function getTPI($idUser)
{

    $sql = 'SELECT * FROM `tpis` WHERE userCandidateID=:idUser1 OR userManagerID=:idUser2 OR userExpert1ID=:idUser3
     OR userExpert2ID=:idUser4';
    $request = connect()->prepare($sql);
    $request->bindParam(":idUser1", $idUser, PDO::PARAM_INT);
    $request->bindParam(":idUser2", $idUser, PDO::PARAM_INT);
    $request->bindParam(":idUser3", $idUser, PDO::PARAM_INT);
    $request->bindParam(":idUser4", $idUser, PDO::PARAM_INT);
    $request->execute();

    return $request->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * @param id de l'utilisateur que nous voulons supprimmer
 * @author Thibault Cart
 * @return rien
 */
function suppressionUtilisateurs($idUser)
{


    $sql = "DELETE FROM user_roles WHERE userID=:idUser";
    $request = connect()->prepare($sql);
    $request->bindParam(":idUser", $idUser, PDO::PARAM_STR);

    $request->execute();
    $sql = "DELETE FROM user_classes WHERE userCandidateID=:idUser";
    $request = connect()->prepare($sql);
    $request->bindParam(":idUser", $idUser, PDO::PARAM_STR);

    $request->execute();

    $sql = "DELETE FROM `users` WHERE userID=:idUser";
    $request = connect()->prepare($sql);
    $request->bindParam(":idUser", $idUser, PDO::PARAM_STR);

    $request->execute();
}

/**
 * @param id de l'utilisateur que nous voulons supprimmer
 * @param Nouveau nom de l'utilisateur à modifier
 * @param Nouveau prénom de l'utilisateur à modifier
 * @param Nom de l'entreprise qui emploie l'utilisateur à modifier 
 * @param Email de l'utilisateur à modifier
 * @param Telephone de l'utilisateur à modifier
 * @param Tableau de tous les roles de l'utilisateur à modifier
 * @param Adresse de l'utilisateur à modifier
 * @param Mot de passe de l'utilisateur à modifier
 * @param Sel permetant de copliquer le décryptage du mots de passe de l'utilsateur
 * @author Thibault Cart
 * @return rien
 */
function modificationUtilisateur($idUser, $nomUser, $prenomUser, $entreprise, $Email, $telephone, $roleUser, $adresse, $mdp, $sel)
{


    $sql = "UPDATE `users` SET `lastName`=:lastName,`firstName`=:firstName,
    `companyName`=:companyName,`address`=:address,`email`=:email,`phone`=:phone,`pwdHash`=:pwdHash,`pwdSalt`=:pwdSalt WHERE userID=:idUser;";
    $request = connect()->prepare($sql);
    $request->bindParam(":idUser", $idUser, PDO::PARAM_STR);
    $request->bindParam(":lastName", $nomUser, PDO::PARAM_STR);
    $request->bindParam(":firstName", $prenomUser, PDO::PARAM_STR);
    $request->bindParam(":companyName", $entreprise, PDO::PARAM_STR);
    $request->bindParam(":address", $adresse, PDO::PARAM_STR);
    $request->bindParam(":email", $Email, PDO::PARAM_STR);
    $request->bindParam(":phone", $telephone, PDO::PARAM_STR);
    $request->bindParam(":pwdHash", $mdp, PDO::PARAM_STR);
    $request->bindParam(":pwdSalt", $sel, PDO::PARAM_STR);
    $request->execute();

    $sql = "DELETE FROM user_roles WHERE user_roles.userID=:idUser";
    $request = connect()->prepare($sql);
    $request->bindParam(":idUser", $idUser, PDO::PARAM_STR);
    $request->execute();

    foreach ($roleUser as $value) {
        echo $value;
        $sql = "INSERT INTO `user_roles` (userID,roleID) VALUES (:idUser,:idRole) ";
        $request = connect()->prepare($sql);
        $request->bindParam(":idUser", $idUser, PDO::PARAM_STR);
        $request->bindParam(":idRole", $value, PDO::PARAM_STR);
        $request->execute();
    }
}

/**
 * @param id du role auquel nous voulons les droits
 * @return tous les droits du role
 * @author Thibault Cart
 */
function getDroit($idRole)
{
    $sql = "SELECT rightID FROM role_rights WHERE roleID=:idRole;";
    $request = connect()->prepare($sql);
    $request->bindParam(":idRole", $idRole, PDO::PARAM_STR);

    $request->execute();
    $droits = array();

    foreach ($request->fetchAll(PDO::FETCH_ASSOC) as $d) {
        $droits[] = $d["rightID"];
    }
    return $droits;
}

/**
 * @param rien
 * @return tous les droits
 * @author Thibault Cart
 */
function getTousDroit()
{
    $sql = "SELECT rightID,rightName FROM rights;";
    $request = connect()->prepare($sql);

    $request->execute();

    return $request->fetchAll(PDO::FETCH_KEY_PAIR);
}

/**
 * @param id du role qui va voir ses droits supprimmés
 * @return rien
 * @author Thibault Cart
 */
function supprimmerDroitParID($idRole)
{
    $sql = "DELETE FROM `role_rights` WHERE roleID=:idRole";
    $request = connect()->prepare($sql);
    $request->bindParam(":idRole", $idRole, PDO::PARAM_INT);

    $request->execute();
}

/**
 * @param id du role qui va se voir attribuer des droits
 * @param id du droits
 * @return rien
 * @author Thibault Cart
 */
function attributionDroit($idRole, $idDroit)
{
    $sql = "INSERT INTO `role_rights`(`roleID`, `rightID`) VALUES (:idRole,:idDroit)";
    $request = connect()->prepare($sql);
    $request->bindParam(":idRole", $idRole, PDO::PARAM_INT);
    $request->bindParam(":idDroit", $idDroit, PDO::PARAM_INT);

    $request->execute();
}
/**
 * @param id du role dont on veut les droits
 * @return rien
 * @author Thibault Cart
 */
function getDroitName($idRole)
{
    $sql = "SELECT rightName FROM `rights` as r
 LEFT JOIN role_rights as rr 
 ON rr.rightID=r.rightID 
WHERE rr.roleID=:idRole
";
    $request = connect()->prepare($sql);
    $request->bindParam(":idRole", $idRole, PDO::PARAM_INT);
    $request->execute();
    return $request->fetchAll(PDO::FETCH_ASSOC);
}
