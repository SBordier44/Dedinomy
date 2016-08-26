<?php
namespace Dedinomy;

if(!defined('SECURE_LINK')) die('Direct access is not authorized');

class Api
{
    private static $_instance;
    private $db;
    private $ClientIp;
    private $auth;
    private $settings;

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
        $this->db = Database::getInstance();
        $this->auth = Auth::getInstance();
        $this->settings = Settings::getInstance();
    }

    public function login($apikey, $secretkey){
        $req = $this->db->query('SELECT * FROM dn_settings WHERE api_key = ? AND secret_key = ? AND id = "1"', [$apikey, $secretkey])->rowCount();
        return $req;
    }

    public function get(){
        $data = $this->db->query('SELECT * FROM dn_messages')->fetchAll();
        $this->sendJSON($data);
    }

    public function add($data=[]){
        $errors = [];
        $required = ['nickname', 'message', 'publish'];
        foreach($required as $req){
            if(!array_key_exists($req, $data)){
                $errors['errors'][] = $req.' Est manquant';
            } else {
                if(empty($data[$req])){
                    $errors['errors'][] = $req.' Est Vide';
                }
            }
        }
        if(!empty($errors)){
            $this->sendJSON($errors);
        } else {
            $this->db->query('INSERT INTO dn_messages SET nickname = ?, message = ?, ip = ?, online = ?', [$data['nickname'], $data['message'], $this->ClientIp, $data['publish']]);
            $Mailer = new \PHPMailer();
            $users = $this->auth->getAllUsers();
            if(isset($_SERVER['SERVER_ADMIN'])){
                $Mailer->setFrom($_SERVER['SERVER_ADMIN'], $this->settings->get('sitename'));
            } else {
                $Mailer->setFrom('webmaster@'.Utils::getBaseUrl(), $this->settings->get('sitename'));
            }
            $Mailer->isHTML(true);
            foreach($users as $user){
                if($user->notify_email){
                    $Mailer->addBCC($user->email, $user->username);
                }
            }
            $Mailer->setLanguage('fr');
            $Mailer->CharSet = 'utf-8';
            if($this->settings->get('dedi_autopublish')){
                $Mailer->Subject = 'Nouvelle Dédicace !';
                $Mailer->Body    = 'Bonjour !<br /><br />Vous revevez cet Email car une nouvelle Dédicace viens d\'être postée sur votre site !<br /><br />Rendez-vous dans l\'administration de DediNomy pour la voir ;)<br /><br /><a href="http://'.Utils::getBaseScript().'/dashboard/login.php" target="_blank">Administration de DediNomy</a>';
                $Mailer->AltBody = "Bonjour ! \r\n Vous revevez cet Email car une nouvelle Dédicace viens d'être postée sur votre site ! \r\n Rendez-vous dans l'administration de DediNomy pour la voir ;) \r\n http://".Utils::getBaseScript()."/dashboard/login.php";
            } else {
                $Mailer->Subject = 'Nouvelle Dédicace En Attente !';
                $Mailer->Body    = 'Bonjour !<br /><br />Vous revevez cet Email car une nouvelle Dédicace viens d\'être soumise pour validation sur votre site !<br /><br />Rendez-vous dans l\'administration de DediNomy pour la voir ;)<br /><br /><a href="http://'.Utils::getBaseScript().'/dashboard/login.php" target="_blank">Administration de DediNomy</a>';
                $Mailer->AltBody = "Bonjour ! \r\n Vous revevez cet Email car une nouvelle Dédicace viens d'être soumise pour validation sur votre site ! \r\n Rendez-vous dans l'administration de DediNomy pour la voir ;) \r\n http://".Utils::getBaseScript()."/dashboard/login.php";
            }
            $Mailer->send();
            $this->sendJSON('success');
        }
    }

    public function delete($id=""){
        $errors = [];
        if(!empty($id) && !is_array($id)){
            $req = $this->db->query('SELECT * FROM dn_messages WHERE id = ?', [$id])->rowCount();
            if(!$req){
                $errors['errors'][] = 'ID Invalide';
            }
        } else {
            $errors['errors'][] = 'ID Manquant';
        }
        if(!empty($errors)){
            $this->sendJSON($errors);
        } else {
            $this->db->query('DELETE FROM dn_messages WHERE id=?',[$id]);
            $this->sendJSON('success');
        }
    }

    public function sendJSON($data){
        echo json_encode($data);
    }

    public function setClientIp($ip){
        $this->ClientIp = $ip;
    }
}