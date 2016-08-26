<?php

class ValidationCore
{
	public $config = [];
	public $language = [];
	public $error = false;

	public function ValidationCore($config, $language)
	{
		$this->config = $config;
		$this->language = $language;
	}

	public function is_writable($value)
	{
		clearstatcache();
		if (!is_writable($value)){
			return false;
		}
		return true;
	}

	function required($value)
	{
		if (is_array($value)){
			return ($value) ? true : false;
		}
		else {
			return ($value != '') ? true : false;
		}
	}

	public function matches($value, $param = '')
	{
		if (is_array($param)){
			$param = current($param);
		}
		if (!isset($_POST[$param])){
			return false;
		}
		return ($value === $_POST[$param]) ? true : false;
	}

	public function min_length($value, $param = 0)
	{
		if (function_exists('mb_strlen')){
			return (mb_strlen($value) < $param) ? false : true;
		}
		return (strlen($value) < $param) ? false : true;
	}

	public function max_length($value, $param = 0)
	{
		if (function_exists('mb_strlen')){
			return (mb_strlen($value) > $param) ? false : true;
		}
		return (strlen($value) > $param) ? false : true;
	}

	public function exact_length($value, $param = 0)
	{
		if (function_exists('mb_strlen')){
			return (mb_strlen($value) != $param) ? false : true;
		}
		return (strlen($value) != $param) ? false : true;
	}

	public function min_value($value, $param = 0)
	{
		if (preg_match('#/[^0-9]#', $value)){
			return false;
		}
		return ($value < $param) ? false : true;
	}

	public function max_value($value, $param = 0)
	{
		if (preg_match('#/[^0-9]#', $value)){
			return false;
		}
		return ($value > $param) ? false : true;
	}

	public function exact_value($value, $param = 0)
	{
		return ($value != $param) ? false : true;
	}

	public function valid_email($value)
	{
		return (boolean)preg_match('#^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$#ix', $value);
	}

	public function valid_emails($value)
	{
		if (strpos($value, ',') === false){
			return $this->valid_email($value);
		}
		foreach (explode(',', $value) as $email){
			if ($this->valid_email(trim($email)) === false){
				return false;
			}
		}
		return true;
	}

	public function valid_ip($value)
	{
		$segments = explode('.', $value);
		if (count($segments) != 4){
			return false;
		}
		if ($segments[0][0] == '0'){
			return false;
		}
		foreach ($segments as $segment){
			if ($segment == '' || preg_match("/[^0-9]/", $segment) || $segment > 255 || strlen($segment) > 3){
				return false;
			}
		}
		return true;
	}

	public function alpha($value)
	{
		return (boolean)preg_match('#^([a-z])+$#i', $value);
	}

	public function alpha_numeric($value)
	{
		return (boolean)preg_match('#^([a-z0-9])+$#i', $value);
	}

	public function alpha_dash($value)
	{
		return (boolean)preg_match('#^([-a-z0-9_-])+$#i', $value);
	}

	public function numeric($value)
	{
		return (boolean)preg_match('#^[\-+]?[0-9]*\.?[0-9]+$#', $value);
	}

  	public function is_numeric($value)
	{
		return is_numeric($value) ? true : false;
	}

	public function integer($value)
	{
		return (boolean)preg_match('#^[\-+]?[0-9]+$#', $value);
	}

	public function is_natural($value)
	{
   		return (boolean)preg_match('#^[0-9]+$#', $value);
	}

	public function is_natural_no_zero($value)
	{
		if (!preg_match('#^[0-9]+$#', $value)){
			return false;
		}
		if ($value == 0){
			return false;
		}
		return true;
	}

	public function php_function($function, $value, $params = [])
	{
		if (!is_array($params)){
			$params = [$params];
		}
		return call_user_func_array($function, array_merge([$value], $params));
	}
}