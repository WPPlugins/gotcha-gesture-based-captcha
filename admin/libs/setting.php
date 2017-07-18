<?php

if(!class_exists('gotchaSettingApp')) :
	include(GPLUGINPATH.'/admin/layout/setting.php');
endif;

global $gotchaThemeSettingApp;

class gotchaThemeSettingApp extends gotchaSettingApp
{	
	var $data;
	var $value;
	var $status;
	var $containerID;
	var $prefix		= "gotcha_"; 
	var $messages	= array();
	
	// init
	function init()
	{
		global $gotcha;

	}
	
	// load style
	function loadStylesAndScripts($hook)
	{
		global $gotcha;
		
		if($hook != $gotcha['slug'].'_page_gotcha-setting') return;
		
		wp_register_style('jquery-slider'				, GPLUGINLINK.'/admin/css/jquery.slider.css');
		wp_register_style('jquery-slider.round'			, GPLUGINLINK.'/admin/css/jquery.slider.round.css');
		wp_register_style('jquery-slider.round.plastic'	, GPLUGINLINK.'/admin/css/jquery.slider.round.plastic.css');
		wp_register_style('jquery-ibutton'				, GPLUGINLINK.'/admin/css/jquery.ibutton.css');
		wp_register_style('jquery-colorpicker'			, GPLUGINLINK.'/admin/css/jquery.colorpicker.css');
		//wp_register_style('thickbox'					, get_bloginfo('template_url' ).'/wp-includes/js/thickbox/thickbox.css');
		
		wp_enqueue_style('jquery-slider');
		wp_enqueue_style('jquery-slider.round');
		wp_enqueue_style('jquery-slider.round.plastic');
		wp_enqueue_style('jquery-ibutton');
		wp_enqueue_style('jquery-colorpicker');
		wp_enqueue_style('thickbox');

		
		wp_register_script('google-font'		, 'https://ajax.googleapis.com/ajax/libs/webfont/1.0.27/webfont.js');
		wp_register_script('jquery-cookie'		, GPLUGINLINK.'/admin/js/jquerycookie.js'			,array('jquery'));
		wp_register_script('jquery-dependClass'	, GPLUGINLINK.'/admin/js/jquery.dependClass.js'	,array('jquery'));
		wp_register_script('jquery-ibutton'		, GPLUGINLINK.'/admin/js/jquery.ibutton.js'		,array('jquery'));
		wp_register_script('jquery-slider'		, GPLUGINLINK.'/admin/js/jquery.slider.js'		,array('jquery','jquery-dependClass'));
		wp_register_script('jquery-googlefont'	, GPLUGINLINK.'/admin/js/jquery.googlefont.js'	,array('jquery','google-font'));
    	wp_register_script('jquery-colorpicker'	, GPLUGINLINK.'/admin/js/jquery.colorpicker.js'	,array('jquery'));
		wp_register_script('gotcha-admin-script'	, GPLUGINLINK.'/admin/js/script.php'				,array('jquery','media-upload','thickbox','jquery-colorpicker'));
		
		wp_enqueue_script('jquery');
		wp_enqueue_script('jquery-ui-core');
		wp_enqueue_script('jquery-cookie');
		wp_enqueue_script('jquery-dependClass');
		wp_enqueue_script('jquery-slider');		
		wp_enqueue_script('jquery-ibutton');
		wp_enqueue_script('media-upload');
		wp_enqueue_script('google-font');
		wp_enqueue_script('jquery-googlefont');
		wp_enqueue_script('thickbox');
		wp_enqueue_script('jquery-colorpicker');
		wp_enqueue_script('gotcha-admin-script');
	}
	
	/*==================================================================================*/
	/*=========================			  CONTROLLER		   =========================*/
	/*==================================================================================*/
	
	// processing Input
	function processingInput($variable = NULL)
	{
		$variable	= (is_null($variable)) ? $_POST : $variable;
		
		if(isset($variable['data']) && isset($variable['_wpnonce']) && wp_verify_nonce($variable['_wpnonce'],'gotcha-update-theme-setting')) :			
			$this->data	= $variable['data'];
			
			$this->updateData();
		endif;
	}
	
	// updateData
	function updateData()
	{
		global $gotcha;
		
		$array_keys	= array_keys($this->data);
		
		foreach($array_keys as $key) :
			if(is_array($this->data[$key])) :
				$value	= implode(',',$this->data[$key]);
			else :
				$value	= $this->data[$key];
			endif;
			update_option($key,$value);
		endforeach;
		
		if(isset($_POST['section'])) :
			update_option($gotcha['prefix'].'first-update-'.$_POST['section'],true);
		endif;
		
		$this->status	= "update";
	}
	
	// resetValue
	function resetValue($default = NULL)
	{
		global $gotcha;

		if(!is_null($default))
		foreach($default as $section => $setting) :
			foreach($setting as $part => $value) :
				$name	= $gotcha['prefix'].$section.'_'.$part;
				update_option($name,$value);
			endforeach;
		endforeach;
	}
	
	// ====================================================================
	// LAYOUT NOTIFICATION
	// ====================================================================	
	
	function layoutMessage($section)
	{
		$temp	= array();
		foreach($section as $key => $message) :
			$updated	= gotchaGetOption('first-update-'.$key);
			if(!$updated) :
				$temp[]	= $message;
			endif;
		endforeach;
		

		$this->messages	= array_merge($this->messages,$temp);

		add_action('admin_notices',array(&$this,'message'));
	}
	
	// ====================================================================
	// MESSAGE
	// ====================================================================	
	
	function message()
	{
		if(count($this->messages) > 0) :
			foreach($this->messages as $message) :
				echo $message;
			endforeach;
		endif;	
	}
	
	/*==================================================================================*/
	/*=========================				VIEW			   =========================*/
	/*==================================================================================*/
	
	// notification
	function notification()
	{
		$this->processingInput();
		
		if($this->status == "update") :
		?><div class="message updated fade"><p>Settings have been updated</p></div><?php
		endif;
	}
	
	// view
	function showview($menu)
	{

		$this->notification();
		
		if(!is_admin()) :
			return "";
		endif;
		
		?>
        <div id="gotcha-media" class="wrap">
        	<div id="gotcha-body">
            	<h2 class="header">Plugin Options</h2>
                
                <div class="menu">
	                <ul>
                    <?php foreach($menu as $id => $title) : ?>
	                    <li><a href="#" id="<?php echo $id; ?>">		<span class="icon"></span> <span class="text"><?php echo $title; ?></span></a></li>
                    <?php endforeach; ?>
                    </ul>
                </div>
                
                <div class="container">
                	<div class="holder"></div>
                    <div class='copyright'>
                    	this outstanding wordpress plugin setting is developed by <a href="http://orangerdev.com" title="Visit the expert">orangerdev.com</a>, 
                        the expert wordpress developer. <br />OrangeR Framework ver 2.0 ( built 3 May 2013 )
					</div>
                </div>
                
                <div class="clearfix"></div>
                
                <div id="gotcha-notification">
                	<div class="gotcha-holder">
                    	<div class="loader-holder">
	                    	<div class="loader"><img src="<?php echo GPLUGINLINK; ?>/admin/images/ajax-loader.gif" alt="" /></div>
                            <div class="message"></div>
						</div>
                        <div class="message-holder">
	                        <div class="icon"></div>
    	                	<div class="message"></div>
                            <div class="clearfix"></div>
						</div>
                    </div>
                </div>
                
            </div>
            
            <?php $this->notificationJavascript(); ?>
            
            <script type="text/javascript" language="javascript1.2">
			jQuery(document).ready(function(){
				var menuRequest	= "<?php echo $_REQUEST['menu']; ?>";
				
				function menuload(data)
				{
					jQuery.ajax({
						url			: "<?php echo admin_url('admin-ajax.php'); ?>",
						dataType	: 'html',
						data		: data,
						type		: 'POST',
						beforeSend	: function(){
							preloadingNotification('loading content');
						},
						success		: function(response){
							jQuery('#gotcha-body .container .holder').html(response);
							postloadingNotification('success','loading content success')
						}
					});
				}

				var current	= jQuery.cookie('gotcha-menu');
				
				jQuery('#gotcha-body .menu li:last').addClass('last');
				
				if( current == null )
				{ 
					firstLink	= jQuery('#gotcha-body .menu li:first-child').find('a'); 
					
					firstLink.addClass('current');
					current		= firstLink.attr('id');
				}
				else if(menuRequest != '') {
					current	= menuRequest;	
					
					jQuery('#gotcha-body .menu ul li a#' + current).addClass('current');
				}
				else 
				{
				
					jQuery('#gotcha-body .menu ul li').find('a').each(function(){
						var target	= jQuery(this).attr('id');
						
						if(target == current)
						{ jQuery(this).addClass('current'); }
					});
				}
				
				var data	= {
					action	: 'gotcha-admin-menu',
					target	: current
				}
				
				menuload(data);
				
				jQuery('#gotcha-body .menu li a').click(function(){
					var target	= jQuery(this).attr('id');
					var data	= {
						action	: 'gotcha-admin-menu',
						target	: target
					}

					jQuery('#gotcha-body .menu a').removeClass('current');
					jQuery(this).addClass('current');
					jQuery.cookie('gotcha-menu',target);
					
					menuload(data);
					
					return false;
				});
			});
			</script>
        	
        </div>
        
        <?php
	}
	
	/* ================================================================================	*/
	/* == Notification Javascript													==	*/
	/* ================================================================================	*/
	
	function notificationJavascript()
	{
		?>
        <script type="text/javascript" language="javascript1.2">
				var preloadingNotification = function (message)
				{
					jQuery('#gotcha-notification').fadeIn('fast',function(){
						jQuery(this).find('.loader-holder').show().find('.message').html(message);
					});
				}
				
				var  postloadingNotification = function(status,message)
				{
					var notification	= jQuery('#gotcha-notification');
					
					notification.find('.loader-holder').hide().find('.message').html('');
					notification.find('.message-holder').show().addClass(status).find('.message').html(message);
					
					notification.delay(2000,"myQueue").queue("myQueue", function(){
						notification.fadeOut('fast');
						notification.find('.message-holder').hide().removeClass(status).find('.message').html('');						
						
					}).dequeue("myQueue");					
				}
		</script>
        <?php	
	}
	
	/* ================================================================================	*/
	/* == AJAX Defined																==	*/
	/* ================================================================================	*/
	
	function ajaxSaveInstallment()
	{
		
		?>
        <?php $this->notificationJavascript(); ?>
        <script type="text/javascript" language="javascript1.2">
		
		jQuery(document).ready(function(){
			
				var preloadingNotification = function (message)
				{
					jQuery('#gotcha-notification').find('.message').html('');
					jQuery('#gotcha-notification .loader-holder,#gotcha-notification .message-holder').hide();
					
					jQuery('#gotcha-notification').fadeIn('fast',function(){
						jQuery(this).find('.loader-holder').show().find('.message').html(message);
					});
				}
				
				var  postloadingNotification = function(status,message)
				{
					var notification	= jQuery('#gotcha-notification');
					
					notification.find('.loader-holder').find('.message').html('');
					notification.find('.loader-holder').hide('500',function(){
						notification.find('.message-holder').show().addClass(status).find('.message').html(message);
					
						notification.delay(8000,"myQueue").queue("myQueue", function(){
							notification.fadeOut('fast');
							notification.find('.message-holder').hide().removeClass(status).find('.message').html('');						
							
						}).dequeue("myQueue");					
					});
				}
				
			var multipleCheckboxes	= function(object)
			{
				var checked	= false;
				var name	= object.attr('name');
				data		= new Array;
				
				i	= 1;
				object.each(function(){
					if(jQuery(this).is(':checked')) 
					{ checked	= true;	}
					else { checked = false; }
					data[i]	= { 'name' : name,'value' : checked }
					
					i++;
				});
				
				
				
				return data;
			}
			
			jQuery('#gotcha-form').submit(function(){
				var checkbox	= new Array();
				var multiple	= new Array();
				var params = jQuery('#gotcha-form :checkbox').each(function() {
					
					if(jQuery(this).hasClass('multiple') == false)
					{
						var data	={
							name 	: jQuery(this).attr('name'),
							value	: jQuery(this).is(':checked')
						}
						
						checkbox.push(data)
					}
				});
				
				checkbox.push( multipleCheckboxes(jQuery('input:checkbox')));
				
				var dataSerial	= jQuery(this).serializeArray();
				
				var data	= {
					'serial'	: dataSerial,
					'checkbox'	: checkbox	
				}
				
				
				var	data	= {
					action	: "gotcha-save-options",
					data	: data,
					section	: jQuery('#gotcha-form input#menu-address').val()
				}
				
				jQuery.ajax({
					url			: "<?php echo admin_url('admin-ajax.php'); ?>",
					dataType	: 'html',
					data		: data,
					type		: 'POST',
					beforeSend	: function(){  preloadingNotification('system is saving setting');},
					success		: function(response){ 
						postloadingNotification('success','settings has been saved'); 
					}
				});
				return false;
			});
			
			jQuery('#gotcha-form #reset-setting').click(function(){
				var data	= {
					action	: "gotcha-reset-setting"	
				}
				jQuery.ajax({
					url			: "<?php echo admin_url('admin-ajax.php'); ?>",
					dataType	: 'html',
					data		: data,
					type		: 'POST',
					beforeSend	: function(){  preloadingNotification('system is saving setting');},
					success		: function(response){ postloadingNotification('success','settings has been reseted'); 
						function gotchaRedirectSave()
						{
							window.location = "<?php echo admin_url('admin.php?page=gotcha-setting'); ?>";
						}
						setTimeout(gotchaRedirectSave, 3000);
					}
				});
				
				return false;
			});
		});
		</script>
        <?php
	}
	
	function navigation($title,$section,$links)
	{
		?>
        <div class="float-nav">
        	<h4><?php echo $title; ?></h4>
            <div class="content">
            <ul>
            <?php foreach($links as $id => $label) : ?>
            	<li><a href="#<?php echo $section.'-anc-'.$id; ?>"><?php echo $label; ?></a></li>
            <?php endforeach; ?>
            </ul>
				<a href="#<?php echo $section.'-anc-save-options'; ?>" class="save">Go To Save Button</a>
            </div>
        </div>
        <?php	
	}

}

global $gotchaThemeSettingApp;
$gotchaThemeSettingApp	= new gotchaThemeSettingApp;
$gotchaThemeSettingApp->init();

/* ajax menu */
add_action('wp_ajax_gotcha-admin-menu'	, 'gotchaSettingMenu');
add_action('wp_ajax_gotcha-save-options'	, 'gotchaSaveOptions');
add_action('wp_ajax_gotcha-reset-setting'	, 'gotchaResetSetting');

/* ============ reset setting ============ */

function gotchaResetSetting()
{
	global $gotchaThemeSettingApp,$gotchaThemeSetting;
	
	$gotchaThemeSetting->resetValue($gotchaThemeSetting->default);
	
	exit();
}

/* ============ save options ============ */
function gotchaSaveOptions()
{
	global $gotcha,$gotchaThemeSettingApp;
	
	
	$serial		= $_POST['data']['serial'];	
	$checkboxes	= $_POST['data']['checkbox'];
	
	$checkboxes_tmp	= array();
	
	// checking for checkboxes
	if(is_array($checkboxes)) :
		foreach($checkboxes  as $checkbox) :
			
			$value	= ($checkbox['value'] == 'true') ? true : false;
			$checkboxes_tmp[$checkbox['name']]	= $value;
		
		endforeach;
	endif;
	
	$checkboxes	= $checkboxes_tmp;
	
	// checking for inputs
	$new_all_data	= array();
	$new_all_data['data']	= array();
	foreach($serial as $data) :
	
		$pos	= strpos($data['name'],$gotcha['prefix']);
		if($pos === false) :
			$new_all_data[$data['name']]	= $data['value'];
		else :
		
			// check if multiple value	
			if(!isset($checkboxes[$data['name']]) || ($checkboxes[$data['name']] && isset($checkboxes[$data['name']]) )) :
		
				// check if multiple name is already set				
				if(isset($new_all_data['data'][$data['name']])) :
				
					if(!is_array($new_all_data['data'][$data['name']])) :
						$new_all_data['data'][$data['name']]	= array($new_all_data['data'][$data['name']]);
					endif;
					
					// push additional value into exists variable
					array_push($new_all_data['data'][$data['name']],$data['value']);
				else :
					// create new value
					$new_all_data['data'][$data['name']]	= $data['value'];
				endif;
			elseif($checkboxes[$data['name']]) :
			
			endif;
		endif;
		
	endforeach;
	
	$new_all_data['data']	= array_merge($checkboxes,$new_all_data['data']);
	
	unset($new_all_data['menu-address']);
	unset($new_all_data['menu-section']);
	
	gotchaDebug($new_all_data,$_POST);
	
	$gotchaThemeSettingApp->processingInput($new_all_data);
	
	exit();
}

/* ============== get menu ============== */
function gotchaSettingMenu($target = "")
{
	global $gotchaThemeSetting;

	$target	= (!empty($target)) ? 'menu_'.$target : 'menu_'.$_POST['target'];
	
	if(method_exists('gotchaThemeSetting',$target)) :
		$gotchaThemeSetting->$target();
	else :
		echo 'menu is not exists';
	endif;
	
	
	exit();	
}

function gotchaThemeSettingApp()
{
	global $gotchaThemeSettingApp;
	
	$gotchaThemeSettingApp->view();
}

if(!class_exists('gotchaExportImport')) :
	include(GPLUGINPATH.'/admin/libs/export-import.php');
endif;
?>