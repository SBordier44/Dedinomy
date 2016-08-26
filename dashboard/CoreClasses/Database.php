<?php
namespace Dedinomy;

if(!defined('SECURE_LINK')) die('Direct access is not authorized');

class Database{

    private $pdo;
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
        $config = require CONFIG_USER . 'database.php';
        $this->pdo = new \PDO("mysql:dbname=" . $config['db_name'] . ";host=" . $config['db_server'], $config['db_username'], $config['db_password']);
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_OBJ);
    }

    public function query($query, $params = false){
        if($params){
            $req = $this->pdo->prepare($query);
            $req->execute($params);
        } else {
            $req = $this->pdo->query($query);
        }
        return $req;
    }

    public function lastInsertId(){
        return $this->pdo->lastInsertId();
    }

    public static function testConnect($config){
        try{
            $test = new \PDO("mysql:dbname=" . $config['db_name'] . ";host=" . $config['db_server'], $config['db_username'], $config['db_password']);
            return true;
        } catch(\PDOException $e){
            return false;
        }
    }

    public static function installSQL($file, $config = false){
        if(!$config){
            $config = require CONFIG_USER . 'database.php';
        }
        try{
            $conn = new \PDO("mysql:dbname=" . $config['db_name'] . ";host=" . $config['db_server'], $config['db_username'], $config['db_password']);
            $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $conn->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_OBJ);
            $conn->exec(file_get_contents($file));
            return true;
        } catch (\PDOException $e){
            return false;
        }
    }
}