<?php
namespace Dedinomy;

if(!defined('SECURE_LINK')) die('Direct access is not authorized');

class Ajax
{
    private static $_instance;
    private $Db;
    private $Auth;

    public static function getInstance()
    {
        if (!self::$_instance) {
            $class = __CLASS__;
            self::$_instance = new $class;
        }
        return self::$_instance;
    }

    public function __construct()
    {
        $this->Db = Database::getInstance();
        $this->Auth = Auth::getInstance();
    }

    public function dediDelete($id){
        if($this->Db->query('DELETE FROM dn_messages WHERE id=?', [$id])){
            return true;
        } return false;
    }

    public function dediValidate($id){
        if($this->Db->query('UPDATE dn_messages SET online="1" WHERE id=?', [$id])){
            return true;
        } return false;
    }

    public function banipDelete($id){
        if(!$this->Auth->isAdmin()) { return false; }
        if($this->Db->query('DELETE FROM dn_banip WHERE id=?', [$id])){
            return true;
        } return false;
    }

    public function addIp($ip){
        if(!$this->Auth->isAdmin()) { return false; }
        if($this->Db->query('INSERT INTO dn_banip SET ip=?', [$ip])){
            return true;
        } return false;
    }

    public function userDelete($id){
        if(!$this->Auth->isAdmin()) { return false; }
        if($this->Db->query('DELETE FROM dn_users WHERE id=?', [$id])){
            return true;
        } return false;
    }

    public function createUser($username, $password, $email, $grade){
        if(!$this->Auth->isAdmin()) { return false; }
        if($this->Auth->register($username, $password, $email, $grade)){
            return true;
        } else {
            return false;
        }
    }

    public function saveSettings($data){
        if(!$this->Auth->isAdmin()) { return false; }
        $errors = 0;
        foreach($data as $key => $value){
            if(!$this->Db->query('UPDATE dn_settings SET '.$key.' = ? WHERE id = "1"', [$value])){
                $errors++;
            }
        }
        return (!$errors) ? true : false;
    }

    public function execUpdate(){
        if(!$this->Auth->isAdmin()) { return false; }
        $Updater = Updater::getInstance();
        return ($Updater->execUpdate())?true:false;
    }

    public function editProfilMail($Post){
        $Auth = Auth::getInstance();
        if($Auth->changeMail($Post['email'], $Post['notify_email'])){
            return true;
        } return false;
    }

    public function editProfilPassword($Post){
        $Auth = Auth::getInstance();
        if($Auth->changePassword($Post['password'])){
            return true;
        } return false;
    }

    public function getCredentialsApi(){
        $Settings = Settings::getInstance();
        $apikey = Str::random(30);
        $secretkey = Str::random(120);
        if($Settings->set('api_key', $apikey) && $Settings->set('secret_key', $secretkey)){
            return true;
        } return false;
    }

    public function dediEdit($data){
        if($this->Db->query('UPDATE dn_messages SET nickname = ?, message = ? WHERE id = ?', [
            $data['nickname'],
            $data['message'],
            $data['id']
        ])){
            return true;
        } return false;
    }
    public function pluginInstall($pluginName){
        $Plugins = Plugin::getInstance();
        if($Plugins->install($pluginName)){
            return true;
        } return false;
    }
    public function pluginUninstall($pluginName){
        $Plugins = Plugin::getInstance();
        if($Plugins->uninstall($pluginName)){
            return true;
        } return false;
    }
    public function pluginConf($Data){
        $Plugins = Plugin::getInstance();
        if($Plugins->configure($Data)){
            return true;
        } return false;
    }
}