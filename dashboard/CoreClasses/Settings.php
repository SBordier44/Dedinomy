<?php
namespace Dedinomy;

if(!defined('SECURE_LINK')) die('Direct access is not authorized');

class Settings
{
    private static $_instance;
    private $settings = [];
    private $Db;

    public static function getInstance()
    {
        if (!self::$_instance) {
            $class = __CLASS__;
            self::$_instance = new $class;
        }
        return self::$_instance;
    }

    public function __construct(){
        $this->Db = Database::getInstance();
        $this->settings = $this->Db->query('SELECT * FROM dn_settings WHERE id="1"')->fetch();
    }

    public function get($key){
        return $this->settings->$key;
    }

    public function set($key, $value){
        return $this->Db->query('UPDATE dn_settings SET '.$key.' = ? WHERE id="1"', [$value]);
    }
}