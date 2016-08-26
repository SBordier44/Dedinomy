<?php
namespace Dedinomy;

if(!defined('SECURE_LINK')) die('Direct access is not authorized');

class Session
{

    private static $instance;
    private $options = [

    ];

    public static function getInstance(){
        if(!self::$instance){
            $class = __CLASS__;
            self::$instance = new $class;
        }
        return self::$instance;
    }

    private function __construct(){
        //$this->options = array_merge($this->options, $this->options);
        session_start();
    }

    public function setFlash($key, $message){
        $_SESSION['Flash'][$key] = $message;
    }

    private function hasFlash(){
        return !empty($_SESSION['Flash']);
    }

    public function getFlash(){
        if($this->hasFlash()){
            $html = '';
            foreach($_SESSION['Flash'] as $type => $message) {
                $html .= "<div class='alert alert-$type'>" . $message . "</div>";
            }
            unset($_SESSION['Flash']);
            echo $html;
        }
    }

    public function getNotify(){
        if($this->hasFlash()){
            $html = '';
            foreach($_SESSION['Flash'] as $type => $message) {
                $html .= "<script>
                    $(function(){
                        new PNotify({
                            delay: 3000,
                            type: '$type',
                            text: '$message',
                            nonblock: {
                                nonblock: true
                            }
                        });
                    });
                    </script>
                ";
            }
            unset($_SESSION['Flash']);
            echo $html;
        }
    }

    public function set($key, $value){
        $_SESSION[$key] = $value;
    }

    public function get($key){
        return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
    }

    public function getAll(){
        return $_SESSION;
    }

    public function delete($key){
        unset($_SESSION[$key]);
    }

    public function destroy(){
        $_SESSION = [];
        \session_unset();
        \session_destroy();
    }
}