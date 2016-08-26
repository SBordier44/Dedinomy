<?php

class SetupCore
{

	public $config = [];
	public $language = [];
	public $languages = [];
	public $vars = [];
    public $step = [];

    public function SetupCore()
	{
		session_start();
		if (!session_id()){
			die('PHP Session could not be started.');
		}
		$this->load_config();
		$this->load_language();
		$this->load_steps();
	}

    public function load_config()
	{
		if (!is_file(INSTALLROOT . 'includes/config.php')){
			die('"includes/config.php" file was not found.');
		}
		include INSTALLROOT . 'includes/config.php';
		if (!isset($config) || !is_array($config) || !$config){
			die('"includes/config.php" file is not formatted correctly.');
		}
		if (!isset($_SESSION['NBXSESSINSTALLER']['language']) || !$_SESSION['NBXSESSINSTALLER']['language']){
			$_SESSION['NBXSESSINSTALLER']['language'] = $config['language'];
		}
		$this->config = $config;
	}

    public function load_language()
	{
		if ( is_file(INSTALLROOT . 'languages/core/' . $_SESSION['NBXSESSINSTALLER']['language'] . '.php')){
			$filename = $_SESSION['NBXSESSINSTALLER']['language'];
		}
		elseif (is_file(INSTALLROOT . 'languages/core/' . $this->config['language'] . '.php')){
			$filename = $this->config['language'];
		}
		else {
			die('"includes/core/' . $_SESSION['NBXSESSINSTALLER']['language'] . '.php" file was not found.');
		}
		include INSTALLROOT . 'languages/core/' . $filename . '.php';
		if (!isset($language) || !is_array($language) || !$language ){
			die('"languages/' . $filename . '.php" file is not formatted correctly.');
		}
		$this->language = $language;
		unset($language);
		$filename = '';
		if (is_file(INSTALLROOT . 'languages/' . $_SESSION['NBXSESSINSTALLER']['language'] . '.php')){
			$filename = $_SESSION['NBXSESSINSTALLER']['language'];
		}
		elseif (is_file(INSTALLROOT . 'languages/' . $this->config['language'] . '.php')){
			$filename = $this->config['language'];
		}
		if ($filename){
			include INSTALLROOT . 'languages/' . $filename . '.php';
			if (isset($language) && is_array($language)){
				$this->language = array_merge($this->language, $language);
			}
		}
	}

    public function load_steps()
	{
		if (!is_file(INSTALLROOT . 'includes/steps.php')){
			die('"includes/steps.php" file was not found.');
		}
		include INSTALLROOT . 'includes/steps.php';
		if (!isset($steps) || !is_array($steps)){
			die('"includes/steps.php" file is not formatted correctly.');
		}
		$this->config['steps'] = $steps;
	}

    public function get_languages()
	{
		if ($handle = opendir(INSTALLROOT . 'languages/core/')){
		    while (($filename = readdir($handle)) !== false){
		        if(is_file(INSTALLROOT . 'languages/core/' . $filename)  &&  $filename != '.'  &&  $filename != '..' && strtolower(substr($filename, -4)) == '.php')
		        {
		            include INSTALLROOT . 'languages/core/' . $filename;
		            if (!isset($language) || !is_array($language) || !isset($language['language_name'])){
						die('languages/core/' . $filename . ' file is not formatted properly.');
					}
					$this->languages[substr($filename, 0, -4)] = $language['language_name'];
					unset($language);
		        }
		    }
			closedir($handle);
		}
		if($handle = opendir(INSTALLROOT . 'languages/')){
		    while(($filename = readdir($handle)) !== false){
		        if (is_file(INSTALLROOT . 'languages/' . $filename)  &&  $filename != '.'  &&  $filename != '..' && strtolower(substr($filename, -4)) == '.php')
		        {
		            include INSTALLROOT . 'languages/' . $filename;
		            if (!isset($language) || !is_array($language)){
						die('languages/' . $filename . ' file is not formatted properly.');
					}
					if (isset($language['language_name']) && !isset($this->languages[substr($filename, 0, -4)])){
						$this->languages[substr($filename, 0, -4)] = $language['language_name'];
					}
					unset($language);
		        }
		    }
			closedir($handle);
		}
	}

    public function run()
	{
		$this->set_step_number();
		$this->step = $this->config['steps'][$this->vars['step_num']-1];
		$status = $this->parse_step();
		if ($status && isset($_POST['button_next']) && $_POST['button_next'] && ($this->vars['step_num']+1) <= $this->vars['total_steps']){
			$_SESSION['NBXSESSINSTALLER']['last_step']++;
			$this->redirect($this->config['wizard_file'].'?s=' . ($this->vars['step_num']+1));
		}
		elseif (isset($_POST['button_back']) && $_POST['button_back']){
			$this->redirect($this->config['wizard_file'].'?s=' . ($this->vars['step_num']-1));
		}
		$this->output();
	}

    public function set_step_number()
	{
		$step_num = isset($_GET['s']) && $_GET['s'] && is_numeric($_GET['s']) && $_GET['s'] > 0 ? $_GET['s'] : 1;
		if (!isset($this->config['steps'][$step_num-1]) || !is_array($this->config['steps'][$step_num-1])){
			die('Step #' . $step_num . ' does not exist.');
		}
		if ( !isset($_SESSION['NBXSESSINSTALLER']['last_step'])){
			$_SESSION['NBXSESSINSTALLER']['last_step'] = 1;
		}
		if ( $step_num > 1 && $step_num > $_SESSION['NBXSESSINSTALLER']['last_step']){
			$this->redirect($this->config['wizard_file'] . ( $_SESSION['NBXSESSINSTALLER']['last_step'] > 1 ? '?s=' . $_SESSION['NBXSESSINSTALLER']['last_step'] : '' ));
		}
		$this->vars['total_steps'] = count($this->config['steps']);
		$this->vars['step_num'] = $step_num;
		$this->vars['step_pct'] = $this->vars['total_steps'] > 1 ? ceil(100/($this->vars['total_steps']-1)*($step_num-1)) : 0;
	}

    public function parse_step()
	{
		$status = true;
		include INSTALLROOT . 'includes/core/validation.php';
		include INSTALLROOT . 'includes/validation.php';
		$validate = new Validation($this->config, $this->language);
		if ( isset($this->step['fields'])){
			foreach($this->step['fields'] as $index => $field){
				$field['index'] = $index;
				if ($field['type'] == 'language'){
					$this->get_languages();
					if (isset($_POST['language'])){
						$value = $_POST['language'];
					}
					else {
						$value = $_SESSION['NBXSESSINSTALLER']['language'];
					}
					$this->step['fields'][$field['index']] = [
						'label' => 'Language',
						'name' => 'language',
						'type' => 'select',
						'items' => $this->languages,
						'value' => $_SESSION['NBXSESSINSTALLER']['language'] = $_SESSION['NBXSESSINSTALLER']['language'] = $value,
                    ];
				}
				elseif ($field['type'] == 'php-config'){
					$values = [];
					foreach ($field['items'] as $key => $value){
						if (is_array($value)){
							$name = $value[1];
							$value = $value[0];
						}
						else {
							$name = '';
						}
						$values[$key] = $this->validate_php_config($key, $value, $name);
						if (isset($values[$key]['error']) && $values[$key]['error']){
							$status = false;
						}
					}
					$this->step['fields'][$field['index']]['value'] = $field['value'] = $values;
					if (!$status){
						$this->vars['error'] = $this->language['config_php_error'];
					}
				}
				elseif($field['type'] == 'php-modules'){
					$modules = get_loaded_extensions();
					$values = [];
					foreach($field['items'] as $key => $value){
						$name = (is_array($value)) ? $value[1] : $value;
						$value = (is_array($value) && $value[0]) ? true : false;
						if ($value){
							$values[$key] = [
								'value' => (in_array($key, $modules)) ? $this->language['config_available'] : $this->language['config_unavailable'],
								'error' => (in_array($key, $modules)) ? 0 : 1,
								'message' => (in_array($key, $modules)) ? $this->language['config_pass'] : $this->language['config_fail'],
                            ];
						}
						else {
							$values[$key] = [
								'value' => (in_array($key, $modules)) ? $this->language['config_available'] : $this->language['config_unavailable'],
								'error' => 0,
								'message' => $this->language['config_pass'],
                            ];
						}
						if (isset($values[$key]['error']) && $values[$key]['error']){
							$status = false;
						}
					}
					$this->step['fields'][$field['index']]['value'] = $field['value'] = $values;
					if (!$status){
						$this->vars['error'] = $this->language['config_php_error'];
					}
				}
				elseif($field['type'] == 'file-permissions'){
					$values = [];
					foreach($field['items'] as $key => $value){
						$is_exists = file_exists($key);
						if (!$is_exists){
							$values[$key] = [
								'value' => $this->language['config_readable'],
								'error' => 1,
								'message' => (substr($key, -1) == '/') ? $this->language['config_folder_none'] : $this->language['config_file_none'],
                            ];
						}
						else {
							if($value == 'write'){
								$is_write = is_writable($key);
								$values[$key] = [
									'value' => $this->language['config_writable'],
									'error' => ($is_write) ? 0 : 1,
									'message' => ($is_write) ? $this->language['config_pass'] : ((substr($key, -1) == '/') ? $this->language['config_write_folder'] : $this->language['config_write_file']),
                                ];
							}
							else {
								$is_read = is_readable($key);
								$values[$key] = [
									'value' => $this->language['config_readable'],
									'error' => ($is_read) ? 0 : 1,
									'message' => ($is_read) ? $this->language['config_pass'] : ((substr($key, -1) == '/') ? $this->language['config_read_folder'] : $this->language['config_read_file']),
                                ];
							}
						}
						$values[$key]['path'] = $key;
						if(strpos($key, './../') === 0){
							$values[$key]['path'] = substr($key, 5);
						}
						elseif (strpos($key, '../') === 0){
							$values[$key]['path'] = substr($key, 3);
						}
						if (isset($values[$key]['error']) && $values[$key]['error']){
							$status = false;
						}
					}
					$this->step['fields'][$field['index']]['value'] = $field['value'] = $values;
					if(!$status){
						$this->vars['error'] = $this->language['config_file_error'];
					}
				}
				elseif($field['type'] == 'checkbox'){
					if(isset($_POST[$field['name']]) || isset($_POST['button_next']) && $_POST['button_next']){
						$values = (isset($_POST[$field['name']])) ? $_POST[$field['name']] : [];
					}
					elseif(isset($_SESSION['NBXSESSINSTALLER']['params'][$field['name']])){
						$values = $_SESSION['NBXSESSINSTALLER']['params'][$field['name']];
					}
					else {
						if(isset($field['default'])){
							$values = (is_array($field['default'])) ? $field['default'] : [$field['default']];
						}
						else {
							$values = [];
						}
					}
					$this->step['fields'][$field['index']]['value'] = $_SESSION['NBXSESSINSTALLER']['params'][$field['name']] = $field['value'] = $values;
				}
				elseif($field['type'] != 'header' && $field['type'] != 'info'){
					if (isset($_POST[$field['name']])){
						$value = $_POST[$field['name']];
					}
					elseif(isset($_SESSION['NBXSESSINSTALLER']['params'][$field['name']])){
						$value = $_SESSION['NBXSESSINSTALLER']['params'][$field['name']];
					}
					else {
						$value = (isset($field['default'])) ? $field['default'] : '';
					}
					$this->step['fields'][$field['index']]['value'] = $_SESSION['NBXSESSINSTALLER']['params'][$field['name']] = $field['value'] = $value;
				}
				if(isset($_POST['button_next']) && $_POST['button_next'] && isset($field['validate']) && $field['validate']){
					foreach($field['validate'] as $rule){
						if ($status && !$this->validate_rule($validate, $field, $rule)){
							$status = false;
						}
					}
				}
			}
		}
		if ($status && isset($this->step['callbacks'])){
			include INSTALLROOT . 'includes/core/callbacks.php';
			include INSTALLROOT . 'includes/callbacks.php';
			$callbacks = new Callbacks($this->config, $this->language);
			foreach($this->step['callbacks'] as $callback){
				if((!isset($callback['execute']) || $callback['execute'] == 'after') && isset($_POST['button_next']) && $_POST['button_next'] || isset($callback['execute']) && $callback['execute'] == 'before'){
					if ($status && !$this->run_callback($callbacks, $callback)){
						$status = false;
					}
				}
			}
		}
		return $status;
	}

    public function validate_rule($validate, $field, $rule)
	{
		if (isset($rule['params'])){
			$params = (is_array($rule['params'])) ? [$rule['params']] : [$rule['params']];
		}
		else {
			$params = [];
		}
		$status = false;
		if(method_exists($validate, $rule['rule'])){
			$status = call_user_func_array([$validate, $rule['rule']], array_merge([$field['value']], $params));
		}
		elseif(function_exists($rule['rule'])){
			$status = call_user_func_array([$validate, 'php_function'], array_merge([$rule['rule'], $field['value']], $params));
			$status = false;
		}
		else {
			$validate->error = 'Validation rule ' . $rule['rule'] . ' does not seem to be valid.';
		}
		if ( !$status ) {
			$this->set_validate_error($validate, $field, $rule, $params);
			return false;
		}
		return true;
	}

    public function validate_php_config($key, $value, $name = '')
	{
		$values = [];
		$config = ($key == 'php_version') ? phpversion() : ini_get($key);
		if($config == 'On'){
            $config = true;
        }
		elseif($config == 'Off' || $config == ''){
            $config = false;
        }
		if(is_null($value)){
			$values = [
				'value' => (!$config) ? $this->language['config_no'] : (($config || $config === 1) ? $this->language['config_yes'] : $config),
				'error' => 0,
				'message' => $this->language['config_pass'],
            ];
		}
		elseif(is_bool($value)){
			if ($value){
				$values = [
					'value' => ($config) ? $this->language['config_yes'] : $this->language['config_no'],
					'error' => ($config) ? 0 : 1,
					'message' => ($config) ? $this->language['config_pass'] : $this->language['config_fail'],
                ];
			}
			else {
				$values = [
					'value' => (!$config) ? $this->language['config_no'] : $this->language['config_yes'],
					'error' => (!$config) ? 0 : 1,
					'message' => (!$config) ? $this->language['config_pass'] : $this->language['config_fail'],
                ];
			}
		}
		else {
			$comparison = '=';
			if (substr($value, 0, 2) == '>=' || substr($value, 0, 2) == '<='){
				$comparison = substr($value, 0, 2);
				$value = substr($value, 2);
			}
			elseif (substr($value, 0, 1) == '>' || substr($value, 0, 1) == '<' || substr($value, 0, 1) == '='){
				$comparison = substr($value, 0, 1);
				$value = substr($value, 1);
			}
			$newcfg = $this->return_bytes($config);
			$newval = $this->return_bytes($value);
			switch($comparison){
				case '=>':
				case '>=':
					$values = [
						'error' => ($newcfg >= $newval) ? 0 : 1,
						'message' => ($newcfg >= $newval) ? $this->language['config_pass'] : sprintf($this->language['config_greater_eq'], ($name) ? $name : $key, $value),
                    ];
					break;
				case '=<':
				case '<=':
					$values = [
						'error' => ($newcfg <= $newval) ? 0 : 1,
						'message' => ($newcfg <= $newval) ? $this->language['config_pass'] : sprintf($this->language['config_less_eq'], ($name) ? $name : $key, $value),
                    ];
					break;
				case '>':
					$values = [
						'error' => ($newcfg > $newval) ? 0 : 1,
						'message' => ($newcfg > $newval) ? $this->language['config_pass'] : sprintf($this->language['config_greater'], ($name) ? $name : $key, $value),
                    ];
					break;
				case '<':
					$values = [
						'error' => ($newcfg < $newval) ? 0 : 1,
						'message' => ($newcfg < $newval) ? $this->language['config_pass'] : sprintf($this->language['config_less'], ($name) ? $name : $key, $value),
                    ];
					break;
				default:
					$values = [
						'error' => ($newcfg == $newval) ? 0 : 1,
						'message' => ($newcfg == $newval) ? $this->language['config_pass'] : sprintf($this->language['config_eq'], ($name) ? $name : $key, $value),
                    ];
					break;
			}
			$values['value'] = $config;
		}
		return $values;
	}

    public function set_validate_error($validate, $field, $rule, $params)
	{
		if (isset($rule['error'])){
			$validate->error = $rule['error'];
		}
		elseif(isset($this->language[$rule['rule']])){
			$params = $this->prep_params($params);
			$validate->error = call_user_func_array('sprintf', array_merge([$this->language[$rule['rule']]], [$field['label']], $params));
		}
		elseif (!$validate->error){
			$validate->error = 'Error message does not exist for ' . $rule['rule'] . ' rule.';
		}
		$this->vars['error'] = $this->step['fields'][$field['index']]['error'] = $validate->error;
	}

    public function prep_params($params)
	{
		if(isset($params[0]) && is_array($params[0])){
			$params = current($params);
		}
		foreach($params as $index => $param){
			foreach($this->step['fields'] as $field){
				if(isset($field['name']) && $field['name'] == $param){
					$params[$index] = $field['label'];
				}
			}
		}
		return $params;
	}

    public function parse_attributes($attrs, $default = [])
	{
		$attr = '';
		foreach ($attrs as $name => $value){
			if (isset($default[$name])){
				$attr .= ' ' . $name . '="' . $default[$name] . ' ' . $value . '"';
				unset($default[$name]);
			}
			else {
				$attr = ' ' . $name . '="' . $value . '"';
			}
		}
		foreach ($default as $name => $value){
			$attr = ' ' . $name . '="' . $value . '"';
		}
		return $attr;
	}

    public function run_callback($callbacks, $callback)
	{
		$status = false;
		if (isset($callback['params'])){
			$params = (is_array($callback['params'])) ? [$callback['params']] : [$callback['params']];
		}
		else {
			$params = [];
		}
		if (method_exists($callbacks, $callback['name'])){
			$status = call_user_func_array([$callbacks, $callback['name']], $params);
		}
		else {
			$callbacks->error = $callback['name'] . ' callback does not exist.';
		}
		if (!$status){
			if (isset($this->language[$callback['name']])){
				$params = $this->prep_params($params);
				$this->vars['error'] = $callbacks->error = call_user_func_array('sprintf', array_merge([$this->language[$callback['name']]], $params));
			}
			elseif ($callbacks->error){
				$this->vars['error'] = $callbacks->error;
			}
			else {
				$this->vars['error'] = $callbacks->error = $callback['name'] . ' callback did not return a successful result.';
			}
			return false;
		}
		return $status;
	}

    public function output()
	{
		if (!is_file(INSTALLROOT . 'views/' . $this->config['view'] . '/view.php')){
			die('views/' . $this->config['view'] . '/view.php file was not found.');
		}
		ob_start();
		include INSTALLROOT . 'views/' . $this->config['view'] . '/view.php';
		$content = ob_get_contents();
		ob_end_clean();
		echo $content;
	}

    public function return_bytes($val)
	{
	    $val = strtolower(trim($val));
	    if(substr($val, -1) == 'b' ){
	    	$val = substr($val, 0, -1);
		}
	    $last = substr($val, -1);
	    switch($last){
	        case 'g':
	        case 'gb':
	            $val *= 1024;
	        case 'm':
	        case 'mb':
	            $val *= 1024;
	        case 'k':
	        case 'kb':
	            $val *= 1024;
	    }
	    return $val;
	}

    public function redirect($url)
	{
		header('location: ' . $url);
		exit();
	}
}