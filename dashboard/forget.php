<?php
namespace Dedinomy;
require "bootstrap.php";
Utils::isBanned();
$Auth = Auth::getInstance();
$Auth->isLoggued();
$Session = Session::getInstance();
$form="formEmail";
if(isset($_GET['id']) && isset($_GET['token'])){
    $form="formNewPassword";
    $authReset = $Auth->checkResetToken($_GET['id'], $_GET['token']);
    if($authReset){
        if(!empty($_POST)){
            $Validator = Validator::getInstance($_POST);
            $Validator->isConfirmed('password');
            if($Validator->isValid()){
                $Db = Database::getInstance();
                $passwordHashed = $Auth->hashPassword($_POST['password']);
                $Db->query('UPDATE dn_users SET password = ?, reset_token = NULL, reset_at = NULL WHERE id = ?', [$passwordHashed, $_GET['id']]);
                $Session->setFlash('success', "Votre mot de passe à bien été mis à jour, vous pouvez à présent vous connecter avec votre nouveau mot de passe");
                Utils::redirect('login.php');
            }
        }
    } else {
        $Session->setFlash('danger', "Le token fourni n'est pas valide ou à expiré");
        Utils::redirect('login.php');
    }
}
if(!empty($_POST) && !empty($_POST['email'])){
    if($Auth->resetPassword($_POST['email'])){
        $Session->setFlash('success', "Les instructions du rappel de mot de passe vous ont été envoyés par Mail");
        Utils::redirect('forget.php');
    } else {
        $Session->setFlash('danger', "Aucun compte ne correspond à cet Email");
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo SCRIPT_NAME ?></title>
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/font-awesome.min.css" rel="stylesheet">
    <link href="assets/css/animate.min.css" rel="stylesheet">
    <link href="assets/css/custom.css" rel="stylesheet">
    <link href="assets/css/icheck/flat/green.css" rel="stylesheet">
    <script src="assets/js/jquery.min.js"></script>
    <!--[if lt IE 9]>
    <script src="assets/js/html5shiv.min.js"></script>
    <script src="assets/js/respond.min.js"></script>
    <![endif]-->
</head>
<body style="background:#F7F7F7;">
<div class="">
    <div id="wrapper">
        <div id="login" class="animate form">
            <h1 style="text-align: center;"><?php echo SCRIPT_NAME ?></h1>
            <?php echo $Session->getFlash();?>
            <section class="login_content">
                <?php if($form=="formNewPassword"){?>
                <form method="post">
                    <h1>Nouveau mot de passe</h1>
                    <div>
                        <input type="password" class="form-control" placeholder="Mot de passe" name="password" required/>
                    </div>
                    <div>
                        <input type="password" class="form-control" placeholder="Mot de passe (Confirmation)" name="password_confirm" required/>
                    </div>
                    <div>
                        <button class="btn btn-default submit" type="submit">Enregistrer</button>
                    </div>
                    <div class="clearfix"></div>
                    <div class="separator">
                        <div class="clearfix"></div>
                        <br />
                        <div>
                            <p><?php echo COPY ?></p>
                        </div>
                    </div>
                </form>
                <?php } elseif($form == "formEmail") { ?>
                <form method="post">
                    <h1>Mot de passe perdu</h1>
                    <div>
                        <input type="text" class="form-control" placeholder="Email de votre compte" name="email" required/>
                    </div>
                    <div>
                        <button class="btn btn-default submit" type="submit">Envoyer</button>
                    </div>
                    <div class="clearfix"></div>
                    <div class="separator">
                        <div class="clearfix"></div>
                        <br />
                        <div>
                            <p><?php echo COPY ?></p>
                        </div>
                    </div>
                </form>
                <?php } ?>
            </section>
        </div>
    </div>
</div>
</body>
</html>