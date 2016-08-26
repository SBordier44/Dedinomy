<?php
if(file_exists('.lockInstallerSystem')){
    die('Réinstallation refusée !');
}
define('INSTALLER','INSTALLER');
require '../dashboard/bootstrap.php';
header("Cache-Control: no-cache, must-revalidate" );
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
header('Content-Type: text/html; charset=utf-8');
require INSTALLROOT . 'includes/core/wizard.php';
require INSTALLROOT . 'includes/wizard.php';
$wizard = new Setup();
$wizard->run();