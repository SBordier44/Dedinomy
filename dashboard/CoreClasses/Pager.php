<?php
namespace Dedinomy;

if(!defined('SECURE_LINK')) die('Direct access is not authorized');

class Pager
{
    private static $_vars = [];
    private static $_layout = 'Layout';
    private static $_content = 'home';

    public static function setVar($name, $value){
        self::$_vars[$name] = $value;
    }

    private static function getLayout(){
        $uri = 'Pages/'.self::$_layout . '.php';
        if(file_exists($uri)){
            ob_start();
            extract(self::$_vars, \EXTR_SKIP);
            include $uri;
            return ob_get_clean();
        } else return "<strong>ERREUR N°404L:</strong> Erreur lors de l'ouverture de la page demandée !";
    }

    private static function getContent(){
        $uri = 'Pages/'.self::$_content . '.php';
        if(file_exists($uri)){
            ob_start();
            extract(self::$_vars, \EXTR_SKIP);
            include $uri;
            return ob_get_clean();
        } else return "<strong>ERREUR N°404V:</strong> La page demandée <i>".self::$_content."</i> est introuvable !";
    }

    public static function setContent($content){
        self::$_content = $content;
    }

    public static function content(){
        if(self::$_content){
            echo self::getContent();
        }
    }

    public static function setLayout($layout){
        self::$_layout = $layout;
    }

    public static function render(){
        if(isset($_GET['action'])){
            self::setContent($_GET['action']);
        }
        if(self::$_layout){
            echo self::getLayout();
        }
    }
}