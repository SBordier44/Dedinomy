<?php
namespace Dedinomy;

require "bootstrap.php";
Utils::isBanned();
$Auth = Auth::getInstance();
$Auth->restrict();
Pager::render();