<?php

global $gotchaComment;

class gotchaComment extends gotchaAPI
{
	function gotchaComment()
	{
		$on		= gotchaGetOption('show_comment');
		
		if($on) :
			add_action('comment_form_logged_in_after'	,array(&$this,'fieldAction'));
			add_action('comment_form_after_fields'		,array(&$this,'fieldAction'));
			add_action('pre_comment_on_post'			,array(&$this,'validation'));
		endif;
	}
	
	function validation()
	{
		
		if($this->ValidateCaptcha($_POST['gotcha-data'])==FALSE) :
		//if(!in_array($_POST['gotcha-data'],$this->valid)) :
			wp_die(__('<strong>Comment Error : </strong>You dropped wrong icon','gotcha'));
		endif;
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

add_action('init'	,'gotchaCommentInit');

function gotchaCommentInit()
{
	global $gotchaComment;	
	
	$gotchaComment	= new gotchaComment;
}

?>