<?php
class CallbacksCore
{
	public $config = [];
	public $language = [];
	public $error = false;
	public $db = null;
	public $db_engines = [];
	public $db_version = false;

	function CallbacksCore($config, $language)
	{
		$this->config = $config;
		$this->language = $language;
	}
}