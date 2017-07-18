<?php

global $gotchaRegister;

class gotchaRegister extends gotchaAPI
{
	function gotchaRegister()
	{
		$on	= gotchaGetOption('show_register');
		
		if($on) :
			add_action('register_form'		,array(&$this,'fieldAction'));
			add_filter('registration_errors',array(&$this,'validation'));
		endif;
	}
	
	function validation($errors)
	{
		if($this->ValidateCaptcha($_POST['gotcha-data'])==FALSE) :
		//if(!in_array($_POST['gotcha-data'],$this->valid)) :
			$errors->add('gotcha_error',__('<strong>Register Error : </strong>You dropped wrong icon','gotcha'));
		endif;
		
		return $errors;
	}
	
	function fieldAction()
	{
		$this->form();	
	}
	
	function fieldFilter($fields)
	{
		ob_start();
		
		$this->form();
		
		$content	= ob_get_contents();
		
		ob_end_clean();
		
		gotchaDebug($fields);
		
		$fields['gotcha']	= $content;
		
		gotchaDebug($fields);
		
		return $fields;
	}
}

add_action('init'	,'gotchaRegisterInit');

function gotchaRegisterInit()
{
	global $gotchaRegister;	
	
	$gotchaRegister	= new gotchaRegister;
}

?>