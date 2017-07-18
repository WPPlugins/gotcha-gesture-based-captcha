<?php
global $gotchaSettingApp;

$gotchaSettingApp	= new gotchaSettingApp;

class gotchaSettingApp extends gotchaApp
{
	var $opt_name;
	var $id;
	var $value;
	var $name;
	var $default;
	
	//======================================================
	//generatedata
	//======================================================
	function data($label,$part,$section)
	{
		global $gotcha;
		
		$this->opt_name	= $gotcha['prefix'].$part."_".$section;
		$this->id		= str_replace("_","-",$this->opt_name);
		$this->value	= get_option($this->opt_name);
		$this->name		= $this->opt_name;		
		$this->value	= (is_bool($this->value) && $this->value == false) ? $this->default[$part][$section] :  stripslashes($this->value);
	}
	
	//======================================================
	// beforeInput
	//======================================================
	function beforeInput($label,$class = NULL)
	{
		if($label) :
			?><div class="input-field <?php echo $class; ?>"><label class="label-input"><?php echo $label; ?></label><?php	
		else :
			?><div class="input-field"><?php
		endif;
	}
	
	//======================================================
	// afterInput
	//======================================================
	function afterInput($info = NULL)
	{
		if(!is_null($info) && !empty($info)) :
			?><em><?php echo $info; ?></em><?php
		endif;
		
		?>
	        <div style="clear:both;"></div>
        </div>
		<?php
	}
	
	//======================================================
	// help
	//======================================================
	
	function help($title,$content)
	{
		?>
        <div class="help">
        	<span><?php echo $title; ?></span>
        	<a href='#' class='show-help'>&nbsp;</a>
            <div class="help-content">
			<?php echo $content; ?>
            </div>
        </div>
        <?php	
	}
	
	//======================================================
	// text field
	//======================================================
	function text($label,$part,$section,$type = 'text',$class = array(),$info = "")
	{
		$defclass	= array(
			'div'	=> '',
			'input'	=> '',
		);
		
		$class	= wp_parse_args($class,$defclass);
		
 		$this->data($label,$part,$section);
		$this->beforeInput($label,$class['div']);
		
		?><input type="<?php echo $type; ?>" name="<?php echo $this->name; ?>" id="<?php echo $this->id; ?>" value="<?php echo $this->value; ?>" 
        	     class="<?php echo $class['input']; ?>" /><?php
		
		$this->afterInput($info);
	}
	
	//======================================================
	// options dropdown
	//======================================================
	function options($label,$part,$section,$options,$multiple = false,$class = array(),$info = NULL)
	{
		$defclass	= array(
			'div'	=> '',
			'input'	=> '',
		);
		
		$class	= wp_parse_args($class,$defclass);
		
		$this->data($label,$part,$section);
		
		$this->beforeInput($label,$class['div']);
		
		
		$this->value	= ($multiple) 		? explode(',',$this->value)	: $this->value;
		$multiple_el	= ($multiple == 1) 	? 'multiple="multiple"' 		: "";
		$options		= ($multiple == 2) 	? $options 		: array('' => 'None') + $options;
		
		?><select name="<?php echo $this->name; ?>" id="<?php echo $this->id; ?>" <?php echo $multiple_el; ?> class="<?php echo $class['input']; ?>"><?php
			
		foreach($options as $key => $label) :
		
			$selected	= (($multiple && is_array($this->value) && in_array($key,$this->value)) || (!$multple && $key == $this->value)) ? "selected='selected'" : "";
			
			?><option value="<?php echo $key; ?>" <?php echo $selected; ?>><?php echo $label; ?></option><?php
		endforeach;
		
		?></select><?php
		
		$this->afterInput($info);
	}
	
	//======================================================
	// multiple checkboxes
	//======================================================
	function checkboxes($label,$part,$section,$options,$nothing = NULL,$nothing = NULL,$info = NULL)
	{
		$this->data($label,$part,$section);
		$this->beforeInput($label,'');
		$this->value	= explode(',',$this->value);
  	
		ob_start();
		
		?><div class="clear"></div><?php
	
		$i = 1;
		foreach ($options as $key => $label) :
			
			$class		= ( $i == 1 ) 				? "first-child" : "";
			$class		= ( $i == sizeof($options)) ? "last-child"	: $class;
			$checked	= (is_array($this->value) && in_array($key,$this->value)) ? "checked='checked'" : "";
			
			?>
            <div class="category-checkboxes <?php echo $class; ?>">
            	<input class="multiple" type="checkbox" name="<?php echo $this->name; ?>" value="<?php echo $key; ?>" <?php echo $checked; ?> />
                <label class="checkbox"><?php echo $label; ?></label>
            </div>
            <?php
			
			$i++;
		endforeach;
		
		$content	= ob_get_contents();
		
		ob_end_clean();
		
		echo $content;
		
		$this->afterInput($info);
	}
	
	//======================================================
	// textarea
	//======================================================
	function textarea($label,$part,$section,$info = NULL)
	{
 		$this->data($label,$part,$section);
		$this->beforeInput($label);
		
		?><textarea name="<?php echo $this->name; ?>" id="<?php echo $this->id; ?>" <?php echo $class; ?>><?php echo $this->value; ?></textarea><?php
		
		$this->afterInput($info);
		
	}
	
	//======================================================
	// generate taxonomy options
	//======================================================
	function taxonomy($label,$part,$section,$type = 1,$args =  array(),$multiple = false,$class = array(),$info = NULL)
	{
		$type		= ($type == 1) ? "options" : "checkboxes";
		$options	= parent::taxonomy($args);
		
		if(method_exists('gotchaSettingApp',$type)) :
			$this->$type($label,$part,$section,$options,$multiple,$class,$info);
		endif;
	
	}
	
	//======================================================
	// generate post options
	//======================================================
	
	function post($label,$part,$section,$type = 1,$args =  array(),$multiple = false,$class = array(),$info = NULL)
	{
		$type		= ($type == 1) ? "options" : "checkboxes";
		$options	= parent::post($args);
		
		if(method_exists('gotchaSettingApp',$type)) :
			$this->$type($label,$part,$section,$options,$multiple,$class,$info);
		endif;
	}
	
	//======================================================
	// generate user options
	//======================================================
	function user($label,$part,$section,$type = 1,$args =  array(),$multiple = false,$class = array(),$info = NULL)
	{
		$type		= ($type == 1) ? "options" : "checkboxes";
		$options	= parent::user($args);
		
		if(method_exists('gotchaSettingApp',$type)) :
			$this->$type($label,$part,$section,$options,$multiple,$class,$info);
		endif;
	
	}
	
	// =============================================================
	// upload
	// =============================================================
	function upload($label,$part,$section,$preview = false,$class = array())
	{
		
 		$this->data($label,$part,$section);
		$this->beforeInput($label,$class['div']);
		
		
		?>
        <input id="<?php echo $this->id; ?>" type="hidden" name="<?php echo $this->name; ?>" value="<?php echo $this->value; ?>" class='image-upload' size="70" />
		<input id="upload_image_button_<?php echo $this->id; ?>" type="button" value="Upload / Get Image" class="upload-image-button button button-primary" data-target="<?php echo $this->id; ?>" />
        <input id="clear_image_button_<?php echo $this->id; ?>" type="button" value="Delete" class="clear-image-button button" data-target="<?php echo $this->id; ?>" />
        <div class="clearfix"></div>
		<?php
		
		if($preview) :
			
		?><div id="preview-<?php echo $this->id; ?>" class="preview-image"><?php
			if(!empty($this->value) && !is_null($this->value)) : 
				echo wp_get_attachment_image( $this->value,'thumbnail');
			endif;
		?></div><?php
		endif;
		
		$this->afterInput($info);
		
	}
	
	// =============================================================
	// CheckBox
	// =============================================================
	function checkbox($label,$part,$section,$default = NULL)
	{
		global $gotcha_th_pre;
		
 		$this->data($label,$part,$section,$default);
		$checked	= (!empty($this->value)) ? "checked='checked'" : "";
		?>
        <div class="input-field">
	        <input class="checkbox" id="<?php echo $this->id; ?>" type="checkbox" name="<?php echo $this->name; ?>" value="on" <?php echo $checked; ?> />
    	    <label class="checkbox" for="<?php echo $this->name; ?>"><?php echo $label; ?></label>
		</div>
		<?php
		
	}
	
	// =============================================================
	// Radio
	// =============================================================
	function radio($label,$part,$section,$options,$class = array(),$info = NULL)
	{
 		$this->data($label,$part,$section);
		$this->beforeInput($label,$class['div']);
		$i = 1;
		foreach($options as $key => $label) : 
			$checked	= ($this->value == $key) ? "checked='checked'" : "";
			$id			= $this->id.'-'.$i;
		?>
		        <input class="radio" type="radio" name="<?php echo $this->name; ?>" value="<?php echo $key; ?>" <?php echo $checked; ?> id="<?php echo $id; ?>" />
    		    <label class="radio" for="<?php echo $id; ?>"><?php echo $label; ?></label>
		<?php 
			$i++;
		endforeach; 
		?>
            <div class="clearfix"></div>
		<?php
		
		$this->afterInput($info);		
	}
	
	// =============================================================
	// button
	// =============================================================
	
	function button($label,$part,$section,$class = array(),$info = "")
	{
		$defclass	= array(
			'div'	=> '',
			'input'	=> '',
		);
		
		$class	= wp_parse_args($class,$defclass);
		
 		$this->data($label,$part,$section);
		$this->beforeInput($label,$class['div']);
		$checked	= ($this->value == 1) ? "checked='checked'" : "";
		
		?><input type="checkbox" name="<?php echo $this->name; ?>" id="<?php echo $this->id; ?>" value="1" class="iButton <?php echo $class['input']; ?>" <?php echo $checked; ?> /><?php
		
		$this->afterInput($info);
	}
	
	
	// =============================================================
	// slider
	// =============================================================
	function slider($label,$part,$section,$start,$end,$step,$round,$dimension,$class = array(),$info = NULL)
	{
		global $gotcha_th_pre;
		
 		$this->data($label,$part,$section);
		
		$this->beforeInput($label,$class['div']);
		
		
		$this->value	= ( empty($this->value)) ? 20 : $this->value;
		?>
        <div class='slider-holder'>
        <input type="<?php echo $type; ?>" name="<?php echo $this->name; ?>" id="<?php echo $this->id; ?>" value="<?php echo $this->value; ?>" />
        </div>
		<?php
		
		$this->afterInput($info);
		?>
		<script type="text/javascript" charset="utf-8">
          jQuery("#<?php echo $this->id; ?>").slider({ 
		  	from	: <?php echo $start; ?>, 
			to		: <?php echo $end; ?>, 
			step	: <?php echo $step; ?>, 
			round	: <?php echo $round; ?>, 
			dimension	: '<?php echo $dimension; ?>', 
			skin		: "round" });
        </script>
        <?php
	}
	
	
	//======================================================
	// editor
	//======================================================
	
	function editor($label,$part,$section,$class = array(),$info = NULL)
	{
		global $th_pre,$id_tinymce;
 		$this->data($label,$part,$section);
	
		$this->beforeInput($label,$class['div']);
		
		?>
        <div class="gotcha-editor-holder">
			<div class="editor_toggle_buttons hide-if-no-js">
        	    <a id="<?php echo $this->id; ?>-html" class="gotcha-editor-html" target="<?php echo $this->id; ?>">HTML</a>
				<a id="<?php echo $this->id; ?>-visual" class="gotcha-editor-visual active" target="<?php echo $this->id; ?>">Visual</a>
	        </div>
			<div class="editor_media_buttons hide-if-no-js">
				<?php do_action( 'media_buttons' ); ?>
			</div>
			<div class="editor_container">
        		<textarea name="<?php echo $this->name; ?>" class="widefat" id="<?php echo $this->id; ?>" <?php echo $class; ?>><?php echo $this->value; ?></textarea>
	        </div>
		</div>
		<?php 
		
		$this->afterInput($info);
	}	

	// =============================================================
	// image
	// =============================================================
	
	function image($label,$part,$section,$images,$info = NULL)
	{
		$this->data($label,$part,$section);
		$this->beforeInput($label);
		
		?>
        <div id="image-chooser-<?php echo $this->name; ?>" class="images-chooser">
        	<?php 
				foreach($images as $key => $image): 
				$checked	= ( $this->value == $key ) ? "checked='checked'" : "";
				$class		= ( $this->value == $key ) ? "image-holder-checked" : "";
			?>
            <div class="image-holder <?php echo $class; ?>">
            	<div class="image"><img src="<?php echo $image; ?>" alt="" /></div>
                <input type="radio" name="<?php echo $this->name; ?>" <?php echo $checked; ?> value="<?php echo $key; ?>" />
            </div>
            <?php endforeach; ?>
        	<div class='clear'></div>
        </div>
        <?php
		
		$this->afterInput($info);
	}
	
	// =============================================================
	// Button
	// =============================================================
	
	function iButton($label,$part,$section,$class = array(),$info = "")
	{
		$defclass	= array(
			'div'	=> '',
			'input'	=> '',
		);
		
		
		$class	= wp_parse_args($class,$defclass);
		
 		$this->data($label,$part,$section);
		$this->beforeInput($label,$class['div']);
		$checked	= ($this->value == 1 && !empty($this->value)) ? "checked='checked'" : "";
		
		?><input type="checkbox" name="<?php echo $this->name; ?>" id="<?php echo $this->id; ?>" value="1" class="iButton <?php echo $class['input']; ?>" <?php echo $checked; ?> /><?php
		
		$this->afterInput($info);
	}
}
add_action('admin_init','gotchaSettingApp');
function gotchaSettingApp()
{
	global $pagenow;
		
	if(isset($_REQUEST['page'])) :
	$tpl	= strpos($_REQUEST['page'],'gotcha-template');
		
	if ($pagenow == "admin.php" && isset($_REQUEST['page']) && ($_REQUEST['page'] == 'gotcha-setting' || $_REQUEST['page'] == 'gotcha-contact-form' || $tpl !== false)) :	
		gotcha_tinymce_admin_init();
	endif;
	endif;
}
// ============================================================
// LOADING EDITOR
// ============================================================
function gotcha_tinymce_admin_init() 
{
	add_action( 'admin_head'				, 'gotcha_tinymce_load_tiny_mce');
	add_filter( 'tiny_mce_before_init'		, 'gotcha_tinymce_init_editor', 20);
	add_action( 'admin_enqueue_scripts'		, 'gotcha_tinymce_styles_and_scripts');
	add_action( 'admin_print_footer_scripts', 'gotcha_tinymce_footer_scripts');
}
/* Instantiate tinyMCE editor */
function gotcha_tinymce_load_tiny_mce() 
{
	// Remove filters added from "After the deadline" plugin, to avoid conflicts
	remove_filter( 'mce_external_plugins', 'add_AtD_tinymce_plugin' );
	remove_filter( 'mce_buttons', 'register_AtD_button' );
	remove_filter( 'tiny_mce_before_init', 'AtD_change_mce_settings' );
	//remove_all_filters('mce_external_plugins');
	
	// Add support for thickbox media dialog
	add_thickbox();
	// New media modal dialog (WP 3.5+)
	if (function_exists('wp_enqueue_media')) {
		wp_enqueue_media(); 
	}
}
/* TinyMCE setup customization */
function gotcha_tinymce_init_editor($initArray) 
{
	// Remove WP fullscreen mode and set the native tinyMCE fullscreen mode
	if (get_bloginfo('version') < "3.3") {
		$plugins = explode(',', $initArray['plugins']);
		if (isset($plugins['wpfullscreen'])) {
			unset($plugins['wpfullscreen']);
		}
		if (!isset($plugins['fullscreen'])) {
			$plugins[] = 'fullscreen';
		}
		$initArray['plugins'] = implode(',', $plugins);
	}
	// Remove the "More" toolbar button
	$initArray['theme_advanced_buttons1'] = str_replace(',wp_more', '', $initArray['theme_advanced_buttons1']);
	// Do not remove linebreaks
	$initArray['remove_linebreaks'] = false;
	// Convert newline characters to BR tags
	$initArray['convert_newlines_to_brs'] = false; 
	// Force P newlines
	$initArray['force_p_newlines'] = true; 
	// Force P newlines
	$initArray['force_br_newlines'] = false; 
	// Do not remove redundant BR tags
	$initArray['remove_redundant_brs'] = false;
	// Force p block
	$initArray['forced_root_block'] = 'p';
	// Apply source formatting
	$initArray['apply_source_formatting '] = true;
	// Return modified settings
	return $initArray;
}
function gotcha_tinymce_styles_and_scripts() 
{
	global $black_studio_tinymce_widget_version;
	
	/* script */
	add_thickbox();
	if (get_bloginfo('version') >= "3.3") :
		wp_enqueue_script('wplink');
		wp_enqueue_script('wpdialogs-popup');
	endif;
	wp_enqueue_script('media-upload');
    wp_enqueue_script('black-studio-tinymce', GPLUGINLINK.'/admin/js/black-tinymce.js', array('jquery'));
	
	/* style */
	
	if (get_bloginfo('version') < "3.3") :
		wp_enqueue_style('thickbox');
	else :
		wp_enqueue_style('wp-jquery-ui-dialog');
	endif;
	
	wp_print_styles('editor-buttons');
    wp_enqueue_style('black-studio-tinymce', GPLUGINLINK.'/admin/css/black-tinymce.css');
}
function gotcha_tinymce_footer_scripts() 
{
	// Setup for WP 3.1 and previous versions
	if (get_bloginfo('version') < "3.2") {
		if (function_exists('wp_tiny_mce')) {
			wp_tiny_mce(false, array());
		}
		if(function_exists('wp_tiny_mce_preload_dialogs')) {
			wp_tiny_mce_preload_dialogs();
		}
	}
	// Setup for WP 3.2.x
	else if (get_bloginfo('version') < "3.3") {
		if (function_exists('wp_tiny_mce')) {
			wp_tiny_mce(false, array());
		}
		if(function_exists('wp_preload_dialogs')) {
			wp_preload_dialogs( array( 'plugins' => 'wpdialogs,wplink,wpfullscreen' ) );
		}
	}
	// Setup for WP 3.3 - New Editor API
	else {
		wp_editor('', 'gotcha-tinymce');
	}
}
?>