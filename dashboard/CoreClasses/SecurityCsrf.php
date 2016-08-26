<?php
namespace Dedinomy;

if(!defined('SECURE_LINK')) die('Direct access is not authorized');

class SecurityCsrf{

    private static $_instance;
    private $_time = 3;

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
        $this->deleteExpiredTokens();
        if (!isset($_SESSION['Security']['Csrf'])) {
            $_SESSION['Security']['Csrf'] = [];
        }
    }

    public function debug() {
        echo json_encode($_SESSION['Security']['Csrf'], \JSON_PRETTY_PRINT);
    }

    public function set_time($time) {
        if (is_int($time) && is_numeric($time)) {
            $this->_time = $time;
            return true;
        }
        return false;
    }

    public function delete($token) {
        $this->deleteExpiredTokens();
        if ($this->get($token)) {
            unset($_SESSION['Security']['Csrf'][$token]);
            return true;
        }
        return false;
    }

    public function deleteExpiredTokens() {
        if(isset($_SESSION['Security']['Csrf'])) {
            foreach ($_SESSION['Security']['Csrf'] AS $token => $time) {
                if (time() >= $time) {
                    unset($_SESSION['Security']['Csrf'][$token]);
                }
            }
        }
    }

    public function set($time = true, $multiplier = 60) {
        if (function_exists('openssl_random_pseudo_bytes')) {
            $key = substr(bin2hex(openssl_random_pseudo_bytes(128)), 0, 128);
        } else {
            $key = sha1(mt_rand() . rand());
        }
        $value = (time() + (($time ? $this->_time : $time) * $multiplier));
        $_SESSION['Security']['Csrf'][$key] = $value;
        return $key;
    }

    public function get($token) {
        $this->deleteExpiredTokens();
        return isset($_SESSION['Security']['Csrf'][$token]);
    }

    public function last() {
        return end($_SESSION['Security']['Csrf']);
    }

}