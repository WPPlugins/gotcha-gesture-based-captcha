<?php

if(!class_exists('gotchaThemeSettingApp')) :
	include(GPLUGINPATH.'/admin/libs/setting.php');
endif;

global $gotchaThemeSetting;

class gotchaThemeSetting extends gotchaThemeSettingApp
{	
	var $data;
	var $first	= array();
	var $value;
	var $status;
	var $containerID;
	var $prefix	= "gotcha_"; 
	var $options	= array(
		'yes'		=> array(
			true	=> "yes",
			false	=> "no",
		),
		'sidebar'	=> array(
			'left'	=> 'Left',
			'right'	=> 'Right'
		),
	);
	var $default	= array(
		'general'	=> array(
			'style'	=> 'gray',
		),
		'show'		=> array(
			'comment'	=> true,
			'login'		=> false,
			'register'	=> false,
		),
	);
	
	/* ================================================================================	*/
	/* == INIT	 																	==	*/
	/* ================================================================================	*/
	function init()
	{
		global $gotcha;
		
		// check if theme option isn't updated
		$update	= gotchaGetOption('theme_update');
		
		if(empty($update) || is_null($update) || $update == false) :
			$this->resetValue();
			update_option($gotcha['prefix'].'theme_update',true);
		endif;
		
		add_action("admin_menu",array(&$this,"createMenu"));
		
		$this->first	= array(
			'general'	=> "<div class='message error'><p>Gotcha Captcha plugin isn't set up. Set it up <a href='".admin_url('admin.php?page=gotcha-setting&amp;menu=general')."'>here</a></p></div>",
			
		);
		
		$this->layoutMessage($this->first);
	}
	
	/* ================================================================================	*/
	/* == CREATE MENU																==	*/
	/* ================================================================================	*/
	function createMenu()
	{
		global $gotcha;
		$page	= add_submenu_page( 'gotcha', $gotcha['name']." &bull; Theme Setting", 'Setting', 'manage_categories', 'gotcha-setting', array(&$this,"view"));

		// load style and script only for this page
		add_action('admin_enqueue_scripts'	, array(&$this,'loadStylesAndScripts'));
	}
	
	/* ================================================================================	*/
	/* == VIEW	 																	==	*/
	/* ================================================================================	*/
	function view()
	{
		
		$menu	= array(
                    "general"		=> "General",
		);
		
		$this->showview($menu);
	}
	
	/* ================================================================================	*/
	/* == HOMEPAGE 																	==	*/
	/* ================================================================================	*/
	
	function menu_general()
	{
		global $gotcha;
		
		?>
        <div id="gotcha-menu-general">
        <form id='gotcha-form' method="post" action="">
        
			<!-- ========================================= 			 	  HOMEPAGE			============================================== -->
            
	       	<div class="section">
				<h3><a id="general-anc-general">General</a></h3>
                <?php $this->text		("Gotcha API Key"		,"general","api_key",'text',NULL,"If you don't have the api key, you can get from <a href='#'>here</a>"); ?>
                <?php $this->text		("Gotcha User"			,"general","userkey"); ?>
                <?php $this->text		("Gotcha Website URL"	,"general","website"); ?>
				<?php $images = array(
					'gray' 	=> GPLUGINLINK.'/admin/images/setting/gray.png',
					'red'	=> GPLUGINLINK.'/admin/images/setting/red.png',
				);?>
                <?php $this->image		('Captcha Style'		,'general','style',$images); ?>
                <?php $this->button		("Show in Comment Form"	,"show","comment"); ?>
                <?php $this->button		("Show in Register Form","show","register"); ?>                
                <?php $this->button		("Show in Login Form"	,"show","login"); ?>                
			</div>
        
        	<div class="submitbox">
            	<p><strong>By clicking "Save Option", 1) you agree to let Gotcha verify every form category you choose in your website and 2) You agree to let Gotcha deploy Ads on the captcha. If you do not agree to these terms and conditions, deactivate it.</strong></p>
	            <?php wp_nonce_field('gotcha-update-theme-setting'); ?>
	            <input type="hidden" id="menu-address" name="menu-address" value="general" />
                <input type="hidden" name="menu-section" value="general" />
    	        <input type="submit" class="button-primary"  value="<?php _e('Save Options', 'gotchaTheme') ?>" />
                <input type="button" id="reset-setting" class="button" value="Reset Setting" />
                <a id="general-anc-save-options"></a>
			</div>
        </form>
        </div>
        
        <script type="text/javascript" >
		jQuery('div#gotcha-menu-general').ready(function(){
			gotchaAdmin();
			gotchaInitEditor('div#gotcha-menu-general');
		});
		</script>
        
        <?php 
		$this->ajaxSaveInstallment();
	}
}

global $gotchaThemeSetting;
$gotchaThemeSetting	= new gotchaThemeSetting;
$gotchaThemeSetting->init();

?>