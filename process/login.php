<?php

global $gotchaLogin;

class gotchaLogin extends gotchaAPI
{
	function gotchaLogin()
	{
		$on	= gotchaGetOption('show_login');
		
		if($on) :
			add_action('login_form'				,array(&$this,'fieldAction'));
			add_filter('shake_error_codes'		,array(&$this,'error'));
			add_filter('wp_authenticate_user'	,array(&$this,'validation'));
		endif;
	}
	
	function error($codes)
	{
		$codes[]	= 'gotcha_error';
		return $codes;
	}
	
	function validation($user)
	{
		if($this->ValidateCaptcha($_POST['gotcha-data'])==FALSE) :
		//if(!in_array($_POST['gotcha-data'],$this->valid)) :
			return new WP_Error('gotcha_error','<strong>Login Error : </strong>You dropped wrong icon','gotcha');
		endif;
		
		return $user;
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

add_action('init'	,'gotchaLoginInit');

function gotchaLoginInit()
{
	global $gotchaLogin;	
	
	$gotchaLogin	= new gotchaLogin;
}

?>