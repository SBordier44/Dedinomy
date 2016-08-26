<?php
namespace Dedinomy;

$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND \strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
if(!$isAjax) {
    exit('Access denied...');
}
header('Content-Type: application/json; charset=utf-8');
require "bootstrap.php";
$Auth = Auth::getInstance();
if($Auth->user()){
    if(isset($_GET['dedivalidate'])){
        $Ajax = Ajax::getInstance();
        if($Ajax->dediValidate($_GET['dedivalidate'])){
            $array = ['status' => 'ok', 'message' => 'Success'];
            echo json_encode($array);
        } else {
            $array = ['status' => 'nok', 'message' => 'Failed'];
            echo json_encode($array);
        }
    }
    if(isset($_GET['dedidelete'])){
        $Ajax = Ajax::getInstance();
        if($Ajax->dediDelete($_GET['dedidelete'])){
            $array = ['status' => 'ok', 'message' => 'Success'];
            echo json_encode($array);
        } else {
            $array = ['status' => 'nok', 'message' => 'Failed'];
            echo json_encode($array);
        }
    }
    if(isset($_GET['DediEdit']) && !empty($_POST)){
        $Ajax = Ajax::getInstance();
        if($Ajax->dediEdit($_POST)){
            $array = ['status' => 'ok', 'message' => 'Success'];
            echo json_encode($array);
        } else {
            $array = ['status' => 'nok', 'message' => 'Failed'];
            echo json_encode($array);
        }
    }
    if(isset($_GET['banipdelete'])){
        $Ajax = Ajax::getInstance();
        if($Ajax->banipDelete($_GET['banipdelete'])){
            $array = ['status' => 'ok', 'message' => 'Success'];
            echo json_encode($array);
        } else {
            $array = ['status' => 'nok', 'message' => 'Failed'];
            echo json_encode($array);
        }
    }
    if(isset($_GET['addIP'])){
        $Ajax = Ajax::getInstance();
        if($Ajax->addIp($_GET['addIP'])){
            $array = ['status' => 'ok', 'message' => 'Success'];
            echo json_encode($array);
        } else {
            $array = ['status' => 'nok', 'message' => 'Failed'];
            echo json_encode($array);
        }
    }
    if(isset($_GET['action']) && !empty($_POST) && $_GET['action']=="adduser"){
        $Ajax = Ajax::getInstance();
        $Validator = Validator::getInstance($_POST);
        $errors = [];
        $Validator->isAlpha('username', "L'identifiant n'est pas valide (Alphanumerique uniquement)");
        if($Validator->isValid()){
            $Validator->isUniq('username', 'dn_users', "Cet identifiant est déja pris !");
        }
        $Validator->isEmail('email', "Cet email n'est pas valide");
        if($Validator->isValid()){
            $Validator->isUniq('email', 'dn_users', "Cet Email est déja utilisé pour un autre utilisateur !");
        }
        $Validator->isConfirmed('password', "Les mots de passe ne correspondent pas!");
        if($Validator->isValid()){
            $retour = $Ajax->createUser($_POST['username'], $_POST['password'], $_POST['email'], $_POST['grade']);
            if($retour){
                $array = ['status' => 'ok', 'message' => 'Success'];
                echo json_encode($array);
            } else {
                $array = ['status' => 'nok', 'message' => 'Création impossible'];
                echo json_encode($array);
            }
        } else {
            $errors = $Validator->getErrors();
            $array = ['status' => 'nok', 'message' => $errors];
            echo json_encode($array);
        }
    }
    if(isset($_GET['userdelete'])){
        $Ajax = Ajax::getInstance();
        if($Ajax->userDelete($_GET['userdelete'])){
            $array = ['status' => 'ok', 'message' => 'Success'];
            echo json_encode($array);
        } else {
            $array = ['status' => 'nok', 'message' => 'Failed'];
            echo json_encode($array);
        }
    }
    if(isset($_GET['action']) && $_GET['action']=="savesettings" && !empty($_POST)){
        $Ajax = Ajax::getInstance();
        if($Ajax->saveSettings($_POST)){
            $array = ['status' => 'ok', 'message' => 'success'];
            echo json_encode($array);
        } else {
            $array = ['status' => 'nok', 'message' => 'Failed'];
            echo json_encode($array);
        }
    }
    if(isset($_GET['action']) && $_GET['action']=="getcredentialsapi"){
        $Ajax = Ajax::getInstance();
        if($Ajax->getCredentialsApi()){
            $array = ['status' => 'ok', 'message' => 'success'];
            echo json_encode($array);
        } else {
            $array = ['status' => 'nok', 'message' => 'Failed'];
            echo json_encode($array);
        }
    }
    if(isset($_GET['maintenance'])){
        $Ajax = Ajax::getInstance();
        if($Ajax->saveSettings(array('maintenance_status' => $_GET['maintenance']))){
            $array = ['status' => 'ok', 'message' => 'Success'];
            echo json_encode($array);
        } else {
            $array = ['status' => 'nok', 'message' => 'Failed'];
            echo json_encode($array);
        }
    }
    if(isset($_GET['Updater']) && $_GET['Updater']=="execute"){
        $Ajax = Ajax::getInstance();
        if($Ajax->execUpdate()){
            $array = ['status' => 'ok', 'message' => 'Success'];
            echo json_encode($array);
        } else {
            $array = ['status' => 'nok', 'message' => 'Failed'];
            echo json_encode($array);
        }
    }
    if(isset($_GET['ProfilEdit']) && !empty($_POST)){
        if($_GET['ProfilEdit']=="Email"){
            $Ajax = Ajax::getInstance();
            if($Ajax->editProfilMail($_POST)){
                $array = ['status' => 'ok', 'message' => 'Success'];
                echo json_encode($array);
            } else {
                $array = ['status' => 'nok', 'message' => 'Failed'];
                echo json_encode($array);
            }
        }
        if($_GET['ProfilEdit']=="Password"){
            $Ajax = Ajax::getInstance();
            $Validator = Validator::getInstance($_POST);
            $errors=[];
            $Validator->isConfirmed('password', "Les mots de passe ne correspondent pas !");
            if($Validator->isValid()) {
                if ($Ajax->editProfilPassword($_POST)) {
                    $array = ['status' => 'ok', 'message' => 'Success'];
                    echo json_encode($array);
                } else {
                    $array = ['status' => 'nok', 'message' => 'Failed'];
                    echo json_encode($array);
                }
            } else {
                $errors = $Validator->getErrors();
                $array = ['status' => 'nok', 'message' => $errors];
                echo json_encode($array);
            }
        }
    }
    if(isset($_GET['pluginInstall']) && !empty($_GET['pluginInstall'])){
        $Ajax = Ajax::getInstance();
        if($Ajax->pluginInstall($_GET['pluginInstall'])){
            $array = ['status' => 'ok', 'message' => 'Success'];
            echo json_encode($array);
        } else {
            $array = ['status' => 'nok', 'message' => 'Failed'];
            echo json_encode($array);
        }
    }
    if(isset($_GET['pluginUninstall']) && !empty($_GET['pluginUninstall'])){
        $Ajax = Ajax::getInstance();
        if($Ajax->pluginUninstall($_GET['pluginUninstall'])){
            $array = ['status' => 'ok', 'message' => 'Success'];
            echo json_encode($array);
        } else {
            $array = ['status' => 'nok', 'message' => 'Failed'];
            echo json_encode($array);
        }
    }
    if(isset($_GET['pluginConf']) && $_GET['pluginConf']=='Save' && !empty($_POST)){
        $Ajax = Ajax::getInstance();
        if($Ajax->pluginConf($_POST)){
            $array = ['status' => 'ok', 'message' => 'Success'];
            echo json_encode($array);
        } else {
            $array = ['status' => 'nok', 'message' => 'Failed'];
            echo json_encode($array);
        }
    }
} else {
    $array = ['status' => 'nok', 'message' => 'Authorization Failed'];
    echo json_encode($array);
}