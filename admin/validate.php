<?php

global $gotchaValidate;

class gotchaValidate
{
	var $validate;
	var $data;
	var $message	= array();
	var $valid		= true;
	var $status		= array();
	var $errorCode	= array();
	var $result;
	
	/* ================================================ */
	/* ===					  INIT				 	 == */
	/* ================================================ */
	
	function gotchaValidate($validate,$data)
	{
		$this->validate	= $validate;
		$this->data		= $data;
		
		$this->validation();
	}
	
	/* ================================================ */
	/* ===				   VALIDATION				 == */
	/* ================================================ */	
	
	function validation()
	{
		if(is_array($this->validate) && sizeof($this->validate)) :
			
			foreach($this->validate as $field => $method) :
			
				$rules	= array_keys($method);
			
				foreach ( $rules as $rule ) :
			
					if(method_exists('gotchaValidate',$rule)) :
						$this->$rule($field);
					endif;
				
				endforeach;
			
			endforeach;
			
		endif;
	}
	
	function check($rule,$field,$message)
	{
		$this->message[] 		= $message;
		$this->valid			= false;
		$this->status[$field]	= false;
		$this->errorCode[]		= $this->validate[$field][$rule]['errorCode'];

	}
	
	/* ================================================ */
	/* ===				VALIDATION FUNCTIONS		 == */
	/* ================================================ */	
	
	/* ================================================ */
	/* ===					 NOT EMPTY		 		 == */
	/* ================================================ */	
	
	function NotEmpty($field)
	{
		$value		= $this->data[$field];
		$message	= $this->validate[$field]['NotEmpty']['message'];
		
		if(empty($value) || is_null($value))
			$this->check("NotEmpty",$field,$message);
	}
	
	/* ================================================ */
	/* ===					USER EXISTS		 		 == */
	/* ================================================ */	
	
	function UserExists($field)
	{
		$value		= $this->data[$field];
		$logic		= $this->validate[$field]['UserExists']['logic'];
		$message	= $this->validate[$field]['UserExists']['message'];
		
		if((!username_exists($value) && $logic) || (username_exists($value) && !$logic))
			$this->check("UserExists",$field,$message);
		
	}
	
	/* ================================================ */
	/* ===					EMAIL EXISTS	 		 == */
	/* ================================================ */
	
	function EmailExists($field)
	{
		$value		= $this->data[$field];
		$logic		= $this->validate[$field]['EmailExists']['logic'];
		$message	= $this->validate[$field]['EmailExists']['message'];
		
		if((!email_exists($value) && $logic) || (email_exists($value) && !$logic))
			$this->check("EmailExists",$field,$message);
	}
	
	/* ================================================ */
	/* ===					IS EMAIL		 		 == */
	/* ================================================ */
	
	function IsEmail($field)
	{
		$value		= $this->data[$field];
		$message	= $this->validate[$field]['IsEmail']['message'];
		
		if(!is_email($value))
			$this->check("IsEmail",$field,$message);
	}
	
	/* ================================================ */
	/* ===					MORE THAN		 		 == */
	/* ================================================ */
	
	function MoreThan($field)
	{
		$value		= $this->data[$field];
		$max		= $this->validate[$field]['MoreThan']['number'];
		$message	= $this->validate[$field]['MoreThan']['message'];
		
		if(strlen($value) <= $max )
			$this->check("MoreThan",$field,$message);
	}
	
	/* ================================================ */
	/* ===					  IS NOT		 		 == */
	/* ================================================ */
	
	function IsNot($field)
	{
		$value		= $this->data[$field];
		$isnot		= $this->validate[$field]['IsNot']['isnot'];
		$message	= $this->validate[$field]['IsNot']['message'];
	
		if($value == $isnot)
			$this->check("IsNot",$field,$message);
	}
	
	/* ================================================ */
	/* ===					  IS NOT		 		 == */
	/* ================================================ */
	
	function Same($field)
	{
		$value		= $this->data[$field];
		$same		= $this->validate[$field]['Same']['value'];
		$message	= $this->validate[$field]['Same']['message'];
	
		if($value <> $same)
			$this->check("Same",$field,$message);
	}
	
	/* ================================================ */
	/* ===					SAME WITH		 		 == */
	/* ================================================ */
	
	function SameWith($field)
	{
		$value		= $this->data[$field];
		$value_2	= $this->data[$this->validate[$field]['SameWith']['field']];
		$message	= $this->validate[$field]['SameWith']['message'];
		
		if($value <> $value_2)
			$this->check("SameWith",$field,$message);
	}


	/* ================================================ */
	/* ===				  VALID CAPTCHA		 		 == */
	/* ================================================ */	
	function ValidReCaptcha($field)
	{
		$value		= $this->data[$field];
		$challange	= $this->data[$field.'_chal'];
		$message	= $this->validate[$field]['ValidCaptcha'];
		
		$respond	= recaptcha_check_answer (RECAPTCHAPRIVATEKEY,$_SERVER["REMOTE_ADDR"],$challange,$value);

		if(!$respond->is_valid) :
			$this->message[]	= $message;
			$this->valid		= false;
			$this->status[$field]	= false;
			$this->errorCode[]		= $this->validate[$field]['ValidCaptcha']['errorCode'];
		endif;
	}
	
	/* ================================================ */
	/* ===				VALID SIMPLE IMAGE		     == */
	/* ================================================ */
	
	function ValidSimpleCaptcha($field)
	{
		$value		= $this->data[$field];
		$message	= $this->validate[$field]['ValidSimpleCaptcha'];
		
		if($value <> $_SESSION[$field]) :
			$this->message[]	= $message;
			$this->valid		= false;
			$this->status[$field]	= false;
		endif;
	}
	
	/* ================================================ */
	/* ===					  CHECKDATE		     	 == */
	/* ================================================ */
	
	function CheckDate($field)
	{
		$date		= $this->data[$field]['date'];
		$month		= $this->data[$field]['month'];
		$year		= $this->data[$field]['year'];
		$message	= $this->validate[$field]['CheckDate']['message'];
		
		if(!checkdate($month,$date,$year)) :
			$this->message[]	= $message;
			$this->valid		= false;
			$this->status[$field]	= false;
			$this->errorCode[]		= $this->validate[$field]['CheckDate']['errorCode'];
		endif;
	}
	
	/* ================================================ */
	/* ===				   FILE FUNCTIONS		 	== */
	/* ================================================ */	
	
	/* ================================================ */
	/* ===					 NOT ERROR		 		 == */
	/* ================================================ */	
	
	function NotError($field)
	{
		$value		= $this->data[$field];
		$message	= $this->validate[$field]['NotError']['message'];
		
		if($value['error'] <> 0) :
			$this->message[] 	= $message;
			$this->valid		= false;
			$this->status[$field]	= false;
			$this->errorCode[]		= $this->validate[$field]['NotError']['errorCode'];
		endif;
	}
	
	/* ================================================ */
	/* ===					 MAX SIZE		 		 == */
	/* ================================================ */	
	
	function MaxSize($field)
	{
		$value		= $this->data[$field]['size'] / 1024;
		$message	= $this->validate[$field]['MaxSize']['message'];
		$max_size	= $this->validate[$field]['MaxSize']['limit'];
		
		if($value > $max_size) :
			$this->message[] 	= $message;
			$this->valid		= false;
			$this->status[$field]	= false;
			$this->errorCode[]		= $this->validate[$field]['MaxSize']['errorCode'];
		endif;
	}
	
}	

?>