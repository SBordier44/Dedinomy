<?php
namespace Dedinomy;

require "bootstrap.php";
Utils::isBanned();
$Auth = Auth::getInstance();
$Auth->isLoggued();
$Session = Session::getInstance();
if(!empty($_POST) && !empty($_POST['usermail']) && !empty($_POST['password'])){
    $user = $Auth->login($_POST['usermail'], $_POST['password'], $_POST['remember']);
    if($user){
        $Session->setFlash('success', "Vous êtes maintenant connecté!");
        Utils::redirect('index.php?action=home');
    } else {
        $Session->setFlash('danger', "Identifiant ou mot de passe incorrect");
        Utils::redirect('login.php');
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
                <form method="post">
                    <h1>Connexion</h1>
                    <div>
                        <input type="text" class="form-control" placeholder="Identifiant / Email" name="usermail" required/>
                    </div>
                    <div>
                        <input type="password" class="form-control" placeholder="Mot de passe" name="password" required/>
                    </div>
                    <div>
                        <button class="btn btn-default submit" type="submit">Connexion</button>
                        <a href="forget.php">Mot de passe oublié</a>
                    </div>
                    <div class="checkbox">
                        <label for="remember">
                            <input type="checkbox" name="remember" checked /> Se souvenir de moi
                        </label>
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
            </section>
        </div>
    </div>
</div>
</body>
</html>