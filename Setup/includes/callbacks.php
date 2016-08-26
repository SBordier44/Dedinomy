<?php

class Callbacks extends CallbacksCore
{

	public function installSQL()
	{
        $config_db = [
            'db_username' => $_SESSION['NBXSESSINSTALLER']['params']['db_username'],
            'db_password' => $_SESSION['NBXSESSINSTALLER']['params']['db_password'],
            'db_name'     => $_SESSION['NBXSESSINSTALLER']['params']['db_name'],
            'db_server'   => $_SESSION['NBXSESSINSTALLER']['params']['db_hostname']
        ];
		if (!\Dedinomy\Database::testConnect($config_db)){
			return false;
		}
		if (!\Dedinomy\Database::installSQL(INSTALLROOT . 'sql/data.sql', $config_db)){
			return false;
		}
        $config_file = '<?php'."\n";
        $config_file .= "if(!defined('SECURE_LINK')) die('Direct access is not authorized');\n\n";
        $config_file .= "return [\n";
        $config_file .= "   'db_username' => '".$_SESSION['NBXSESSINSTALLER']['params']['db_username']."',\n";
        $config_file .= "   'db_password' => '".$_SESSION['NBXSESSINSTALLER']['params']['db_password']."',\n";
        $config_file .= "   'db_name' => '".$_SESSION['NBXSESSINSTALLER']['params']['db_name']."',\n";
        $config_file .= "   'db_server' => '".$_SESSION['NBXSESSINSTALLER']['params']['db_hostname']."'\n";
        $config_file .= "];";
        if(!file_put_contents(ROOT . 'Config/database.php', $config_file)){
            return false;
        }
        return true;
	}

	public function endInstall()
	{
		$Session = \Dedinomy\Session::getInstance();
        $Session->delete('NBXSESSINSTALLER');
        $file = fopen(INSTALLROOT . ".lockInstallerSystem", 'w');
        fclose($file);
        return true;
	}

	public function updateSQL()
	{
        $Auth = \Dedinomy\Auth::getInstance();
        $Settings = \Dedinomy\Settings::getInstance();
        if(!$Auth->register($_SESSION['NBXSESSINSTALLER']['params']['adm_username'], $_SESSION['NBXSESSINSTALLER']['params']['adm_password'], $_SESSION['NBXSESSINSTALLER']['params']['adm_mail'], 'admin')){
            return false;
        }
        if(!$Settings->set('sitename', $_SESSION['NBXSESSINSTALLER']['params']['adm_sitename'])){
            return false;
        }
		return true;
	}
}