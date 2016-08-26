<?php
namespace Dedinomy;

use PHPMailer;
use ReCaptcha\ReCaptcha;
use ReCaptcha\RequestMethod\CurlPost;
use SmartyBC;

require 'dashboard/bootstrap.php';
Utils::isBanned();

$Settings = Settings::getInstance();
$Csrf = SecurityCsrf::getInstance();
$Auth = Auth::getInstance();
$Db = Database::getInstance();
$token = $Csrf->set(15); // minutes
$Smarty = new SmartyBC;
$Smarty->setTemplateDir('Templates/');
$Smarty->setCompileDir('Temp/Smarty/templates_c/');
$Smarty->setConfigDir('Templates/configs/');
$Smarty->setCacheDir('Temp/Smarty/cache/');
$Smarty->allow_php_templates = true;
$Smarty->caching = false;
$Smarty->compile_check = false;
$Smarty->force_cache = false;
$Smarty->force_compile = true;
$Smarty->debugging = false;
$Smarty->escape_html = false;
function Traitement($data)
{
    global $Db, $Settings, $Auth;
    $Db->query('INSERT INTO dn_messages SET nickname = ?, message = ?, ip = ?, online = ?', [$data['nickname'], $data['message'], Utils::getClientIP(), $Settings->get('dedi_autopublish')]);
    $Mailer = new PHPMailer();
    $users = $Auth->getAllUsers();
    if (isset($_SERVER['SERVER_ADMIN'])) {
        $Mailer->setFrom($_SERVER['SERVER_ADMIN'], $Settings->get('sitename'));
    } else {
        $Mailer->setFrom('webmaster@' . Utils::getBaseUrl(), $Settings->get('sitename'));
    }
    $Mailer->isHTML(true);
    foreach ($users as $user) {
        if ($user->notify_email) {
            $Mailer->addBCC($user->email, $user->username);
        }
    }
    $Mailer->setLanguage('fr');
    $Mailer->CharSet = 'utf-8';
    if ($Settings->get('dedi_autopublish')) {
        $Mailer->Subject = 'Nouvelle Dédicace !';
        $Mailer->Body = 'Bonjour !<br /><br />Vous revevez cet Email car une nouvelle Dédicace viens d\'être postée sur votre site !<br /><br />Rendez-vous dans l\'administration de DediNomy pour la voir ;)<br /><br /><a href="http://' . Utils::getBaseScript() . '/dashboard/login.php" target="_blank">Administration de DediNomy</a>';
        $Mailer->AltBody = "Bonjour ! \r\n Vous revevez cet Email car une nouvelle Dédicace viens d'être postée sur votre site ! \r\n Rendez-vous dans l'administration de DediNomy pour la voir ;) \r\n http://" . Utils::getBaseScript() . "/dashboard/login.php";
    } else {
        $Mailer->Subject = 'Nouvelle Dédicace En Attente !';
        $Mailer->Body = 'Bonjour !<br /><br />Vous revevez cet Email car une nouvelle Dédicace viens d\'être soumise pour validation sur votre site !<br /><br />Rendez-vous dans l\'administration de DediNomy pour la voir ;)<br /><br /><a href="http://' . Utils::getBaseScript() . '/dashboard/login.php" target="_blank">Administration de DediNomy</a>';
        $Mailer->AltBody = "Bonjour ! \r\n Vous revevez cet Email car une nouvelle Dédicace viens d'être soumise pour validation sur votre site ! \r\n Rendez-vous dans l'administration de DediNomy pour la voir ;) \r\n http://" . Utils::getBaseScript() . "/dashboard/login.php";
    }
    $Mailer->send();
}

if (isset($_POST) && !empty($_POST) && isset($_POST['token'])) {
    $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    if (!$isAjax) {
        exit('Access denied...');
    }
    header('Content-Type: application/json; charset=utf-8');
    $Sanitizer = Sanitizer::getInstance($_POST);
    $_POST['message'] = $Sanitizer->string('message');
    $Validator = Validator::getInstance($_POST);
    $Validator->isAlpha('nickname', 'Le pseudo ne doit contenir que des caratères alphanumériques !');
    $Validator->isNull('message', 'Veuillez entrer une dédicace valide !');
    if ($Validator->isValid()) {
        if ($Csrf->get($_POST['token'])) {
            $Csrf->delete($_POST['token']);
            if ($Settings->get('recaptcha_status') && !empty($Settings->get('recaptcha_sitekey')) && !empty($Settings->get('recaptcha_secret'))) {
                $recaptcha = new ReCaptcha($Settings->get('recaptcha_secret'), new CurlPost());
                $resp = $recaptcha->verify($_POST['g-recaptcha-response'], Utils::getClientIP());
                if ($resp->isSuccess()) {
                    Traitement($_POST);
                    $array = ['status' => 'ok', 'message' => 'Success'];
                    echo json_encode($array);
                } else {
                    $array = ['status' => 'nokcaptcha', 'message' => $resp->getErrorCodes()];
                    if (DEBUG) {
                        throw new \Exception('Recaptcha Internal Error : ' . $resp->getErrorCodes());
                    }
                    echo json_encode($array);
                }
            } else {
                Traitement($_POST);
                $array = ['status' => 'ok', 'message' => 'Success'];
                echo json_encode($array);
            }
        } else {
            $array = ['status' => 'noktoken', 'message' => "Le formulaire à expiré, veuillez recharger la page et recommencer."];
            echo json_encode($array);
        }
    } else {
        $array = ['status' => 'nokform', 'message' => $Validator->getErrors()];
        echo json_encode($array);
    }
} else {
    $Smarty->assign('token', $token);
    $Smarty->assign('settings', $Settings);
    $Smarty->assign('moderated_msg', Sanitizer::ajaxMessage($Settings->get('moderated_msg')));
    $Smarty->assign('published_msg', Sanitizer::ajaxMessage($Settings->get('published_msg')));
    $Smarty->assign('maintenance_msg', Sanitizer::ajaxMessage($Settings->get('maintenance_msg')));
    if (isset($_GET['tpl'])) {
        $Smarty->display($_GET['tpl'] . '.tpl');
    } else $Smarty->display('poster.tpl');
}