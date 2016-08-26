<?php
namespace Dedinomy;

if(!defined('SECURE_LINK')) die('Direct access is not authorized');

class Utils
{
    public static function debug($vars){
        echo '<pre>',print_r($vars, true),'</pre>';
    }

    public static function redirect($url){
        header("Location: $url");
        exit();
    }

    public static function now(){
        return date('Y-m-d H:i:s');
    }

    public static function convertDate($date, $args = 'd/m/Y H:i'){
        if(!$date) {return 'Jamais';}
        $date = new \DateTime($date);
        return $date->format($args);
    }

    public static function version($limit=0){
        $data = explode('.', VERSION);
        return $data[$limit];
    }

    public static function getClientIP() {
        if (isset($_SERVER)) {
            if (isset($_SERVER["HTTP_X_FORWARDED_FOR"]))
                return $_SERVER["HTTP_X_FORWARDED_FOR"];
            if (isset($_SERVER["HTTP_CLIENT_IP"]))
                return $_SERVER["HTTP_CLIENT_IP"];
            return $_SERVER["REMOTE_ADDR"];
        }
        if (getenv('HTTP_X_FORWARDED_FOR'))
            return getenv('HTTP_X_FORWARDED_FOR');
        if (getenv('HTTP_CLIENT_IP'))
            return getenv('HTTP_CLIENT_IP');
        return getenv('REMOTE_ADDR');
    }

    public static function getBaseUrl(){
        if(isset($_SERVER)){
            if(isset($_SERVER['HTTP_X_FORWARDED_HOST'])){
                return $_SERVER['HTTP_X_FORWARDED_HOST'];
            }
            if(isset($_SERVER['HTTP_X_FORWARDED_SERVER'])){
                return $_SERVER['HTTP_X_FORWARDED_SERVER'];
            }
            return $_SERVER["HTTP_HOST"];
        }
        if (getenv('HTTP_X_FORWARDED_HOST'))
            return getenv('HTTP_X_FORWARDED_HOST');
        if (getenv('HTTP_X_FORWARDED_SERVER'))
            return getenv('HTTP_X_FORWARDED_SERVER');
        return getenv('HTTP_HOST');
    }

    public static function getBaseScript(){
        return self::getBaseUrl().dirname($_SERVER['REQUEST_URI']);
    }

    public static function isBanned(){
        $database = Database::getInstance();
        $banned = $database->query('SELECT * FROM dn_banip WHERE ip = ?', [self::getClientIP()])->rowCount();
        if($banned){
            die('<center><span style="text-align: center !important; color: red; font-weight: bold">Accès refusé : Votre IP à été bannie par l\'administrateur du site.</span></center>');
        }
    }

    public static function deleteFiles($path){
        if(is_dir($path)){
            $files = glob($path . '*', GLOB_MARK);
            foreach($files as $file)
            {
                self::deleteFiles($file);
            }
            rmdir($path);
        } elseif(is_file($path)){
            unlink($path);
        }
    }
}