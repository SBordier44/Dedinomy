<?php
namespace Dedinomy;
header('Content-Type: text/html; charset=utf-8');
if (!require('dashboard/bootstrap.php')) {
    die('NON');
}
if ($_REQUEST['sms']) {
    $Db = Database::getInstance();
    $Sanitizer = Sanitizer::getInstance($_REQUEST);
    $Auth = Auth::getInstance();
    $Settings = Settings::getInstance();
    $_REQUEST['sms'] = $Sanitizer->string('sms');
    $message = explode(' ', $_REQUEST['sms']);
    $i = 0;
    $dedi = '';
    foreach ($message as $a => $b) {
        if ($i > 0) {
            $dedi .= ' ' . $b;
        }
        $i++;
    }
    $Db->query('INSERT INTO dn_messages SET nickname = ?, message = ?, ip = ?, online = ?', ['SMS+', $dedi, Utils::getClientIP(), $Settings->get('dedi_autopublish')]);
    $Mailer = new \PHPMailer();
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
        $Mailer->Subject = 'Nouvelle Dédicace SMS+ !';
        $Mailer->Body = 'Bonjour !<br /><br />Vous revevez cet Email car une nouvelle Dédicace SMS+ viens d\'être postée sur votre site !<br /><br />Rendez-vous dans l\'administration de DediNomy pour la voir ;)<br /><br /><a href="http://' . Utils::getBaseScript() . '/dashboard/login.php" target="_blank">Administration de DediNomy</a>';
        $Mailer->AltBody = "Bonjour ! \r\n Vous revevez cet Email car une nouvelle Dédicace SMS+ viens d'être postée sur votre site ! \r\n Rendez-vous dans l'administration de DediNomy pour la voir ;) \r\n http://" . Utils::getBaseScript() . "/dashboard/login.php";
    } else {
        $Mailer->Subject = 'Nouvelle Dédicace SMS+ En Attente !';
        $Mailer->Body = 'Bonjour !<br /><br />Vous revevez cet Email car une nouvelle Dédicace SMS+ viens d\'être soumise pour validation sur votre site !<br /><br />Rendez-vous dans l\'administration de DediNomy pour la voir ;)<br /><br /><a href="http://' . Utils::getBaseScript() . '/dashboard/login.php" target="_blank">Administration de DediNomy</a>';
        $Mailer->AltBody = "Bonjour ! \r\n Vous revevez cet Email car une nouvelle Dédicace SMS+ viens d'être soumise pour validation sur votre site ! \r\n Rendez-vous dans l'administration de DediNomy pour la voir ;) \r\n http://" . Utils::getBaseScript() . "/dashboard/login.php";
    }
    $Mailer->send();
    exit('OUI');
}