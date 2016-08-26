<?php
namespace Dedinomy;

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: *");
header('Expires: Sun, 01 Jan 2014 00:00:00 GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', FALSE);
header('Pragma: no-cache');
header('Content-Type: application/json; charset=utf-8');
require 'dashboard/bootstrap.php';
$Db = Database::getInstance();
$Api = Api::getInstance();

if(isset($_SERVER['HTTP_X_DEDINOMY_APIKEY']) && isset($_SERVER['HTTP_X_DEDINOMY_SECRET'])){
    if($Api->login($_SERVER['HTTP_X_DEDINOMY_APIKEY'], $_SERVER['HTTP_X_DEDINOMY_SECRET'])){
        if(isset($_SERVER['HTTP_X_DEDINOMY_ACTION'])){
            if(method_exists($Api, $_SERVER['HTTP_X_DEDINOMY_ACTION'])){
                $call = $_SERVER['HTTP_X_DEDINOMY_ACTION'];
                $Api->setClientIp($_SERVER['HTTP_X_DEDINOMY_CLIENTIP']);
                if(isset($_POST['data'])){
                    $Api->$call($_POST['data']);
                } else {
                    $Api->$call();
                }
            } else $Api->sendJSON(['status'=>'error', 'message'=>"L'action demandée n'existe pas"]);
        } else $Api->sendJSON(['status'=>'error', 'message'=>"Aucune action n'a été fournie."]);
    } else $Api->sendJSON(['status'=>'error', 'message'=>'Identification API incorrecte.']);
}