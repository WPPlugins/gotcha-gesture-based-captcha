<?php

global $gotchaAPI;

class gotchaAPI
{
	public $active;
	public $valid	= array(
		"sbidfuhy387923opej2899012iuujnakdu97827349283wehube7q71923uh87ge7g23789i",
		"827349283wehube7q71923uh87ge7g23789ihcb0lxxu234jisiqiwjiehuqweq182u381bh",
		"uxh938e1983192u381j8uehqyw8eu18u9198bdnefugialdrqjritr6f50o7009yutuy9r90",
		"hcb0lxxu234jisiqiwjiehuqweq182u381bh81273y1uwheuqwybajsdoiw9e8qupo40ru28",
		"37jha8iuqy973y81723y817236812u3hj1i2ajnsdopo0i9tl8273hruewr0i9jeiwe883io",
		"81273y1uwheuqwybajsdoiw9e8qupo40ru28827349283wehube7q71923uh87ge7g23789ihcb",
		"ajnsdopo0i9tl8273hruewr0i9jeiwe883io7923opej2899012iuujn9tl8273hruewr0i9je",
		"823y49u23j098hds8dbkanshlw9eq20736t2drqjritr6f50o7009yutuy9r90ajnsdopo",
		"y8f98wehudf9we8u3489r092i30r8jwe9ufs49283wehube7q71923uh87ge7g23789ih",
		"bdnefugialdrqjritr6f50o7009yutuy9r90ajnsdopo0i9tl8273hruewr0i9jeiwe883io",
	);

	function ValidateCaptcha($responseValue){
		$curl = curl_init();
		// Set some options - we are passing in a useragent too here
		curl_setopt_array($curl, array(
		    CURLOPT_RETURNTRANSFER => 1,
		    CURLOPT_URL =>  'http://ec2-54-254-171-93.ap-southeast-1.compute.amazonaws.com/API-031013/embed/gogetgotcha/validate.php',
		    //CURLOPT_USERAGENT => 'Codular Sample cURL Request',
		    CURLOPT_POST => 1,
		    CURLOPT_POSTFIELDS => array(
		        'responseValue' => $responseValue,
		    )
		));
		// Send the request & save response to $resp
		$resp = curl_exec($curl);
		// Close request to clear up some resources
		curl_close($curl);
		if($resp == 1){
			return true;
		}
		else{
			return false;
		}
	}
	
	function gotchaAPI()
	{
		$this->active	= array(
			'comment'	=> gotchaGetOption('show_comment'),
			'register'	=> gotchaGetOption('show_register'),
			'login'		=> gotchaGetOption('show_login'),
		);
		
		add_action('init'				,array(&$this,'init'),1);
		add_action('wp_head'			,array(&$this,'head'));
		add_action('wp_enqueue_scripts'		,array(&$this,'script'));
		
		add_action('login_head'				,array(&$this,'head'));
		add_action('login_enqueue_scripts'	,array(&$this,'script'));

	}
	
	function init()
	{
		if(!session_id()) session_start();	
	}
	
	function script()
	{
		wp_register_style	("gotcha-style"		,"http://ec2-54-254-171-93.ap-southeast-1.compute.amazonaws.com/API-031013/embed/css/front.css");		
		wp_register_script	('jquery-pp'		,"http://ec2-54-254-171-93.ap-southeast-1.compute.amazonaws.com/API-031013/embed/js/jquerypp.custom.js",array('jquery'),NULL,true);
		wp_register_script	('gotcha-kinetic'	,"http://ec2-54-254-171-93.ap-southeast-1.compute.amazonaws.com/API-031013/embed/js/kinetic-v4.5.4.min.js",array('jquery'),NULL,true);
		wp_register_script	('gotcha-front'		,"http://ec2-54-254-171-93.ap-southeast-1.compute.amazonaws.com/API-031013/embed/js/front.js?ccd=8897",array('jquery','jquery-pp'),NULL,true);
		
		//wp_deregister_script('jquery');
		
		
		if($this->active['comment'] && (is_single() || is_page() || is_singular()) ) :
		
			wp_enqueue_script	('jquery');	
			wp_enqueue_script	('jquery-pp');
			wp_enqueue_script	('gotcha-kinetic');
			wp_enqueue_script	('gotcha-front');
			
			wp_enqueue_style	("gotcha-style");
		
		elseif(basename($_SERVER['PHP_SELF']) == 'wp-login.php') :
		
			if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'register' && $this->active['register']) :
		
				wp_enqueue_script	('jquery');	
				wp_enqueue_script	('jquery-pp');
				wp_enqueue_script	('gotcha-kinetic');
				wp_enqueue_script	('gotcha-front');
				
				wp_enqueue_style	("gotcha-style");
				
			elseif($this->active['login']) :
		
				wp_enqueue_script	('jquery');	
				wp_enqueue_script	('jquery-pp');
				wp_enqueue_script	('gotcha-kinetic');
				wp_enqueue_script	('gotcha-front');
				
				wp_enqueue_style	("gotcha-style");
				
			endif;
		
		endif;
	}
	
	function head()
	{
		$setting	= array(
			"got_private_key"	=> md5(gotchaGetOption('general_api_key')),
			"got_user_id" 		=> gotchaGetOption('general_userkey'),
			//"got_website" 		=> md5(gotchaGetOption('general_website')),
		);
		
/*			$setting = array(
				"got_private_key" => "3a55f3dca04ab1095513f2150cd14a83",
				"got_user_id" => "gotcha",
				"got_website" => "f24f86b1ed9aa861f2334cb060001797"
			);*/
		?>
        <script type="text/javascript">
			var bakpau = <?=json_encode($setting)?>;
		</script>
        <?php	
	}
	
	function form()
	{
		?>
		<div id='captcha'></div>
		<input id="gotcha_val" name="gotcha-data" type="hidden" value="" />
        <?php
	}
}

add_action('init','gotchaAPIInit');

function gotchaAPIInit()
{
	global $gotchaAPI;
	
	$gotchaAPI	= new gotchaAPI;	
}

?>