<?php

/**
 * Date création: 25.05.2020
 * Auteur : Thibault Cart
 * Nom fichier: login.php
 */
require_once "../Controleur/function.php";
$reponse = "";
if (isset($_POST["submitLogin"])) {
    $emailLogin = filter_input(INPUT_POST, 'inputMail', FILTER_SANITIZE_EMAIL);
    $passwordLogin = filter_input(INPUT_POST, 'inputMdp', FILTER_SANITIZE_STRING);
    $reponse = procedureConnexion($emailLogin, $passwordLogin);
    if ($reponse == "ConnexionOk") {
        header("location: home.php");
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
    <link href="../css/styles.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/js/all.min.js" crossorigin="anonymous"></script>
</head>

<body class="bg-light">
    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            <main>
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-5">
                            <div class="card shadow-lg border-0 rounded-lg mt-5">
                                <div class="card-header">
                                    <h3 class="text-center font-weight-light my-4"><img src="../img/LogoExpertsDev.svg" width="60" height="60" class="d-inline-block align-middle mr-5" alt="" loading="lazy">CdEIG -
                                        Identification</h3>
                                </div>
                                <div class="card-body">
                                    <form action="" method="post">
                                        <div class="form-group"><label class="small mb-1" for="inputMail">Email</label>
                                            <input required class="form-control py-4" name="inputMail" type="email" placeholder="Saisissez votre email" />
                                        </div>
                                        <div class="form-group"><label class="small mb-1" for="inputMdp">Mot de passe</label>
                                            <input required class="form-control py-4" name="inputMdp" type="password" placeholder="Saisissez votre mot de passe" /></div>
                                        <div class="form-group d-flex align-items-center justify-content-between mt-4 mb-0">
                                            <input type="submit" value="Se Connecter" name="submitLogin" class="btn btn-primary">
                                        </div>
                                    </form>
                                    <?php
                                    //Si une erreur est présente, l'affiche
                                    if ($reponse != "") {
                                        echo "<br><h6 style='color: red'>" . $reponse . "<h6>";
                                    }

                                    ?>
                                </div>
                                <div class="card-footer text-center">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
        <div id="layoutAuthentication_footer">
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
</body>

</html>