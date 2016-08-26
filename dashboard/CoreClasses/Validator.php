<?php
namespace Dedinomy;

if(!defined('SECURE_LINK')) die('Direct access is not authorized');

class Validator
{
    private $data;
    private $errors = [];
    private $db;

    private static $_instance;

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
        $this->db = Database::getInstance();
    }

    private function getField($field){
        if(!isset($this->data[$field])){
            return null;
        }
        return $this->data[$field];
    }

    public function isAlpha($field, $errorMsg = ''){
        if(!preg_match('#^[\w0-9_\-]+$#u', $this->getField($field))){
            $this->errors[$field] = $errorMsg;
        }
    }

    public function isUniq($field, $table, $errorMsg = ''){
        $record = $this->db->query("SELECT id FROM $table WHERE $field = ?", [$this->getField($field)])->fetch();
        if($record){
            $this->errors[$field] = $errorMsg;
        }
    }

    public function isEmail($field, $errorMsg = ''){
        if(!filter_var($this->getField($field), FILTER_VALIDATE_EMAIL)){
            $this->errors[$field] = $errorMsg;
        }
    }

    public function isConfirmed($field, $errorMsg = ''){
        $value = $this->getField($field);
        if(empty($value) || $value != $this->getField($field . '_confirm')){
            $this->errors[$field] = $errorMsg;
        }
    }

    public function isValid(){
        if(!empty($this->errors)){
            return false;
        } return true;
    }

    public function isNull($field, $errorMsg){
        if(!$this->getField($field) || empty($this->getField($field))){
            $this->errors[$field] = $errorMsg;
        }
    }

    public function isUrl($url, $errorMsg = ''){
        if(!filter_var($url, FILTER_VALIDATE_URL)){
            $this->errors[$url] = $errorMsg;
        }
    }

    public function getErrors(){
        return $this->errors;
    }
}