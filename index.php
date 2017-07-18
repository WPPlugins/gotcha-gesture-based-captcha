<?php
/*
Plugin Name: Gotcha | Gesture-based Captcha 
Plugin URI: #
Description: Innovative captcha with drag-n-drop verification
Version: 1.0.0
Author: OrangeR Dev
Author URI: http://orangerdev.
*/
/* 	============================================================================================	
	********************************************************************************************
	** 																						  **
	**  ORANGE FRAMEWORK																	  **
	**  VERSION 2.0.0  																	  	  **
	** 	LAST UPDATE 2 MAY 2013																  **
	** 																						  **
	** 	DEVELOPED BY RIDWAN ARIFANDI														  **
	** 	http://orangerdev.com																  **
	** 																						  **
	** 	UPDATE :																			  **
	**																					      **
	**	RESTRUCTURIZE FROM 1.x to 2.x													      **
	********************************************************************************************
   	============================================================================================  */

define('WP_DEMO',true);

global $gotcha;

$gotcha	= array(
	'debug'		=> true,
	'prefix'	=> 'gotcha_',
	'name'		=> 'Gotcha',
	'slug'		=> 'gotcha',
	'admin'		=> plugin_dir_path(__FILE__).'/admin/',
	'process'	=> plugin_dir_path(__FILE__).'/process/',
);



// defining link
define("GPLUGINPATH"	,plugin_dir_path(__FILE__));
define("GPLUGINLINK"	,plugin_dir_url(__FILE__));

// including file system
include($gotcha['process'].'gotcha.php');
include($gotcha['process'].'comment.php');
include($gotcha['process'].'register.php');
include($gotcha['process'].'login.php');

// localitazion
// load_theme_textdomain('gotcha',get_template_directory() . '/languages');

include($gotcha['admin'].'index.php');

/*------------------ START CALLING ACTION -------------------------------*/
add_action('init','gotchaInit',1);
add_action("admin_enqueue_scripts"	,"gotchaAdminRegisterStylesAndScript");

function gotchaInit()
{
	if(session_id() == '')
		session_start();
}

function gotchaAdminRegisterStylesAndScript()
{
	global $pagenow, $typenow;

	if(!gotchaAdminCallbackPermissionPage()) return;
	
	wp_register_style('gotcha-admin-style'			, plugins_url('/admin/css/admin-style.css',__FILE__));		
	
	wp_enqueue_style('gotcha-admin-style');
	
}

?>