<?php
namespace Dedinomy;

require 'dashboard/bootstrap.php';
$Db = Database::getInstance();
$Settings = Settings::getInstance();
$smarty = new \SmartyBC;
$smarty->setTemplateDir('Templates/');
$smarty->setCompileDir('Temp/Smarty/templates_c/');
$smarty->setConfigDir('Templates/configs/');
$smarty->setCacheDir('Temp/Smarty/cache/');
$smarty->allow_php_templates = true;
$smarty->caching = false;
$smarty->compile_check = false;
$smarty->force_cache = false;
$smarty->force_compile = true;
$smarty->debugging = false;
$smarty->escape_html = false;
if($Settings->get('dedi_displaylimit')){
    $messages = $Db->query('SELECT * FROM dn_messages WHERE online = "1" ORDER BY id DESC LIMIT 0,'.$Settings->get('dedi_displaylimit'))->fetchAll();
} else $messages = $Db->query('SELECT * FROM dn_messages WHERE online = "1" ORDER BY id DESC')->fetchAll();
$smarty->assign('messages', $messages);
$smarty->assign('display_datetime', $Settings->get('dedi_displaydate'));
if(isset($_GET['tpl'])){
    $smarty->display($_GET['tpl'].'.tpl');
} else $smarty->display('dedi.tpl');