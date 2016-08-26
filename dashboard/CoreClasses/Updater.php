<?php
namespace Dedinomy;

use Desarrolla2\Cache\Adapter\File;
use Monolog\Handler\StreamHandler;
use VisualAppeal\AutoUpdate;

if(!defined('SECURE_LINK')) die('Direct access is not authorized');

class Updater{

    private static $_instance;
    public $update;

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
        $this->update = new AutoUpdate(TEMP . 'Updater', ROOT, 120);
        $this->update->setCurrentVersion(VERSION);
        $this->update->setUpdateUrl('http://dist.nubox.fr/Dedinomy/Updater');
        $this->update->setUpdateFile('update_info.ini');
        if(defined('DEBUG') && DEBUG == TRUE){
            $this->update->addLogHandler(new StreamHandler(TEMP . 'Updater/update.log'));
        }
        $this->update->setCache(new File(TEMP . 'Updater/Cache'), 3600);
    }

    public function execUpdate()
    {
        if ($this->update->checkUpdate()){
            if ($this->update->newVersionAvailable()){
                if ($this->update->update() === true) {
                    if(file_exists(TEMP . 'Updater/update.log')){
                        unlink(TEMP . 'Updater/update.log');
                    }
                    return true;
                } else {
                    return false;
                }
            } else {
                if(file_exists(TEMP . 'Updater/update.log')){
                    unlink(TEMP . 'Updater/update.log');
                }
                return false;
            }
        } else {
            return false;
        }
    }
}