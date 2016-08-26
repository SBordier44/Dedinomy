<?php
namespace Dedinomy;

if(!defined('SECURE_LINK')) die('Direct access is not authorized');

class Sanitizer
{
    private static $_instance;
    private $data;

    public static function getInstance($data)
    {
        if (!self::$_instance) {
            $class = __CLASS__;
            self::$_instance = new $class($data);
        }
        return self::$_instance;
    }

    public function __construct($data)
    {
        $this->data = $data;
    }

    private function getField($field){
        if(!isset($this->data[$field])){
            return null;
        }
        return $this->data[$field];
    }

    public function string($field){
        return filter_var($this->getField($field), \FILTER_SANITIZE_STRING);
    }

    public function email($field){
        return filter_var($this->getField($field), \FILTER_SANITIZE_EMAIL);
    }

    public function url($field){
        return filter_var($this->getField($field), \FILTER_SANITIZE_URL);
    }

    public static function ajaxMessage($string){
        return preg_replace('#"#', "'", $string);
    }
}