<?php
namespace Dedinomy;

require "bootstrap.php";
$Auth = Auth::getInstance();
$Auth->logout();
$Session = Session::getInstance();
$Session->setFlash('success', 'Vous êtes maintenant déconnecté!');
Utils::redirect('login.php');