<?php
namespace Dedinomy;

header('Content-Type: text/html; charset=utf-8');
date_default_timezone_set('Europe/Paris');
setlocale(LC_TIME, 'fr', 'fr_FR', 'fr_FR.UTF-8');
setlocale(LC_ALL, 'fr', 'fr_FR', 'fr_FR.UTF-8');
ini_set('default_charset', 'UTF-8');
require 'Paths.php';
require 'constants.php';
require VENDOR . 'autoload.php';
if(file_exists(CONFIG_USER . 'bootstrap.php')){
    require CONFIG_USER . 'bootstrap.php';
    if(defined('DEBUG') && DEBUG){
        ini_set('display_errors', 1);
        error_reporting(E_ALL);
    } else {
        ini_set('display_errors',0);
        error_reporting(0);
    }
}
if(file_exists(CONFIG_USER . 'database.php')){
    $db_conf = require CONFIG_USER . 'database.php';
    if(!Database::testConnect($db_conf) && !defined('INSTALLER')){
        die('Impossible de se connecter à la BDD');
    }
} elseif(!defined('INSTALLER')) die('Fichier de configuration MySQL introuvable !');