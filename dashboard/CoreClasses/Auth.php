<?php
namespace Dedinomy;

if(!defined('SECURE_LINK')) die('Direct access is not authorized');

class Auth
{
    private $options = [
        "restriction_msg" => "Vous n'avez l'authorisation d'accéder à la page demandée!"
    ];
    private $session;
    private $db;

    private static $_instance;

    public static function getInstance()
    {
        if (!self::$_instance) {
            $class = __CLASS__;
            self::$_instance = new $class();
        }
        return self::$_instance;
    }

    public function __construct(){
        $this->session = Session::getInstance();
        $this->db = Database::getInstance();
    }

    public function register($username, $password, $email, $grade="modo"){
        $password = $this->hashPassword($password);
        if($this->db->query("INSERT INTO dn_users SET username = ?, password = ?, email = ?, grade=?, notify_email=?", [
            $username,
            $password,
            $email,
            $grade,
            1
        ])){
            return true;
        } return false;
    }

    public function restrict(){
        if(!$this->user()){
            Utils::redirect('login.php');
        }
    }

    public function user(){
        if(!$this->session->get('Auth')){
            return false;
        }
        return $this->session->get('Auth');
    }

    public function isLoggued(){
        if($this->user()){
            Utils::redirect('index.php?action=home');
        }
    }

    public function connectFromCookie(){
        if(isset($_COOKIE['remember']) && !$this->user()){
            $remember_token = $_COOKIE['remember'];
            $parts = explode('==', $remember_token);
            $user_id = $parts[0];
            $user = $this->db->query('SELECT * FROM dn_users WHERE id = ?', [$user_id])->fetch();
            if($user) {
                $expected = $user_id . '==' . $user->remember_token . sha1($user_id . HASH);
                if ($expected == $remember_token) {
                    $this->connect($user);
                    $this->session->delete('Flash');
                    setcookie('remember', $remember_token, time() + 60 * 60 * 24 * 7);
                }
            } else {
                setcookie('remember', NULL, -1);
            }
        }
    }

    public function connect($user){
        $this->session->set('Auth', $user);
    }

    public function login($usermail, $password, $remember = false){
        $user = $this->db->query('SELECT * FROM dn_users WHERE (username = :usermail OR email = :usermail)', ['usermail' => $usermail])->fetch();
        if(password_verify($password, $user->password)){
            $this->connect($user);
            $this->db->query('UPDATE dn_users SET last_access = NOW() WHERE id = "'.$user->id.'"');
            if($remember){
                $this->remember($user->id);
            }
            return $user;
        }
        return false;
    }

    public function remember($user_id){
        $remember_token = Str::random(250);
        $this->db->query('UPDATE dn_users SET remember_token = ? WHERE id = ?', [$remember_token, $user_id]);
        setcookie('remember', $user_id . '==' . $remember_token . sha1($user_id . HASH), time() + 60 * 60 * 24 * 7);
    }

    public function logout(){
        setcookie('remember', NULL, -1);
        $this->db->query('UPDATE dn_users SET remember_token = ? WHERE id = ?', [null, $this->user()->id]);
        $this->session->delete('Auth');
    }

    public function resetPassword($email){
        $user = $this->db->query('SELECT * FROM dn_users WHERE email = ?', [$email])->fetch();
        if($user){
            $Settings = Settings::getInstance();
            $reset_token = Str::random(60);
            $this->db->query('UPDATE dn_users SET reset_token = ?, reset_at = NOW() WHERE id = ?', [$reset_token, $user->id]);
            $Mailer = new PHPMailer();
            if(isset($_SERVER['SERVER_ADMIN'])){
                $Mailer->setFrom($_SERVER['SERVER_ADMIN'], $Settings->get('sitename'));
            } else {
                $Mailer->setFrom('webmaster@'.Utils::getBaseUrl(), $Settings->get('sitename'));
            }
            $Mailer->isHTML(true);
            $Mailer->setLanguage('fr');
            $Mailer->addAddress($user->email, $user->username);
            $Mailer->CharSet = 'utf-8';
            $Mailer->Subject = 'Réinitialisation de votre mot de passe';
            $Mailer->Body    = "Vous avez demandé à réinitialiser votre mot de passe.<br /><br />
                Afin de regénérer votre mot de passe, veuillez cliquer sur le lien suivant :
                <a href='http://".Utils::getBaseScript()."/dashboard/forget.php?id=".$user->id."&token=".$reset_token."'>Regénérer mon mot de passe</a><br /><br />
                ATTENTION : Cette réinitialisation n'est possible que pendant 30 minutes.<br /><br />
                NOTE : Si toutefois vous n'êtes pas à l'origine de cette demande, veuillez ne pas tenir compte de cet Email.";
            $Mailer->AltBody = "Vous avez demandé à réinitialiser votre mot de passe.\r\n\r\n
                Afin de regénérer votre mot de passe, veuillez copier et coller le lien suivant dans votre navigateur :
                http://".Utils::getBaseScript()."/dashboard/forget.php?id=".$user->id."&token=".$reset_token."\r\n\r\n
                ATTENTION : Cette réinitialisation n'est possible que pendant 30 minutes.\r\n\r\n
                NOTE : Si toutefois vous n'êtes pas à l'origine de cette demande, veuillez ne pas tenir compte de cet Email.";
            $Mailer->send();
            return $user;
        }
        return false;
    }

    public function checkResetToken($user_id, $token){
        return $this->db->query('SELECT * FROM dn_users WHERE id = ? AND reset_token IS NOT NULL AND reset_token = ? AND reset_at > DATE_SUB(NOW(), INTERVAL 30 MINUTE)', [$user_id, $token])->fetch();
    }

    public function hashPassword($password){
        return password_hash($password, \PASSWORD_BCRYPT);
    }

    public function restrictAdmin(){
        $data = $this->session->get('Auth');
        if($data->grade != 'admin'){
            $this->session->setFlash('notice', $this->options['restriction_msg']);
            Utils::redirect('index.php?action=home');
        }
    }

    public function changePassword($password){
        $passwordHash = $this->hashPassword($password);
        if($this->db->query('UPDATE dn_users SET password = ? WHERE id = ?', [$passwordHash, $this->user()->id])){
            $this->refresh();
            return true;
        } return false;
    }

    public function changeMail($mail, $notif){
        if($this->db->query("UPDATE dn_users SET email = ?, notify_email = ? WHERE id = ?", [$mail, $notif, $this->user()->id])){
            $this->refresh();
            return true;
        } return false;
    }

    public function isAdmin(){
        $data = $this->session->get('Auth');
        if($data->grade == 'admin'){
            return true;
        } return false;
    }

    public function refresh(){

        $user = $this->db->query('SELECT * FROM dn_users WHERE id = ?', [$this->user()->id])->fetch();
        $this->connect($user);
    }

    public function getAllUsers(){
        return $this->db->query('SELECT * FROM dn_users');
    }
}