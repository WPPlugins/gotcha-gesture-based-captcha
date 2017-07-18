<?php

global $gotcha_app,$id_tinymce,$image_upload;
$image_upload	= 0;

$id_tinymce	= array();

class gotchaApp 
{
	var $table;
	var $structure;
	var $setting;
	var $opt_name;
	var $id;
	var $value;
	var $name;
	var $containerID = array();
	var $default_value;

	/* initialization */
	function init()
	{
		//initalization variable
		
		//calling any functions for the first time
		
		add_action('admin_menu',array(&$this,"createMenu"));
		add_action('admin_head',array(&$this,"adminCSSJS"));
		
	}

	// check table if exists
	function checkTable($table)
	{	
		global $wpdb;
	
		$query	= "SHOW TABLES LIKE '".$wpdb->prefix.$table."'";

		$result	= $wpdb->get_results($query,ARRAY_A);
		
		if(is_array($result) && isset($result[0])) { return true; } else { return false; }
	}
	
	// create table if the table is not exists
	function createTable($table,$structure)
	{
		global $wpdb;
		
		$query	= "CREATE TABLE IF NOT EXISTS `".$wpdb->prefix.$table."` (".
					$structure.
				  ") ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;";
		echo($query);
		$wpdb->query($query);
	}
	
	// delete table
	function deleteTable($table)
	{
		global $wpdb;
		$sql	= "DROP TABLE `".$wpdb->prefix.$table."`";
		$wpdb->query($sql);
	}
	
	// create menu
	function createMenu()
	{
		global $gotcha;
		
		$icon	= GPLUGINLINK.'/admin/images/admin.png';
		add_menu_page( $gotcha['name']."'s Theme Setting", $gotcha['name'], "manage_categories", "gotcha", 'gotchaThemeSetting', $icon,3);
	}
	
	//========================================================================================================
	// post
	//========================================================================================================
	
	function post($args = array())
	{
		$default	= array(
			'post_type'		=> 'post',
			'numberposts'	=> -1,
		);
		
		$options	= array();
		$args		= wp_parse_args($args,$default);
		$args		= wp_parse_args($args,$default);
		
		query_posts($args);
		
		if(have_posts()) :
			while(have_posts()) :
			the_post();
			
				$ID				= get_the_ID();
				$post_title		= get_the_title();
				
				$options[$ID]	= $post_title;
			
			endwhile;
		endif;
		
		return $options;	
	}
	
	//========================================================================================================
	// taxonomy
	//========================================================================================================
	
	function taxonomy($args = array())
	{
		$default = array(
			'taxonomy'      => 'category',
		);
		
		$options	= array();
		$args		= wp_parse_args($args,$default);		

		$taxonomies	= get_categories($args);
		
		foreach($taxonomies as $tax) :
			
			$options[$tax->term_id]	= $tax->name;
			
		endforeach;
		
		return $options;
	}
	
	//========================================================================================================
	// user
	//========================================================================================================
	
	function user($args)
	{
		$users		= get_users($args);
		$options	= array();
		
		foreach($users as $user) :
			$options[$user->ID]	= $user->display_name;
		endforeach;	
		
		return $options;
	}
	
	//========================================================================================================
	// add CSS and JS if necessary
	//========================================================================================================
	function adminCSSJS() 
	{
		?>
		<script type="text/javascript">
		jQuery(document).ready(function() {
			jQuery(".fade").fadeIn(1000).fadeTo(1000, 1).fadeOut(1000);
			jQuery(".toplevel_page_gotcha").attr("href","admin.php?page=gotcha-setting");
		});
		</script>
		<style type="text/css">
		li#toplevel_page_gotcha li.wp-first-item { display:none; }
		</style>
		<?php
	}
	
	//==================================================//
	//== pagination								  	  ==//
	//==================================================//
	
	function pagination($total_data,$targetpage,$limit = 2,$adjacent = 3)
	{
		$total_pages	= $total_data;
		$page 			= $_GET['number'];
		
		if($page) 		$start = ($page - 1) * $limit; 	//first item to display on this page
		else			$start = 0;						//if no page var is given, set start to 0
	
		/* Setup page vars for display. */
		$page		= ( $page == 0 ) ? 1 : $page;
		$prev		= $page - 1;						//previous page is page - 1
		$next 		= $page + 1;						//next page is page + 1
		$lastpage 	= ceil($total_pages/$limit);		//lastpage is = total pages / items per page, rounded up.
		$lpm1 		= $lastpage - 1;					//last page minus 1
	
		$pagination = "";
		if($lastpage > 1) :
	
			$pagination .= "<div class=\"pagination\">";
			//previous button
			if ($page > 1) 	$pagination.= "<a href=\"$targetpage&number=$prev\">&laquo;</a>";
			else			$pagination.= "<span class=\"disabled\">&laquo;</span>";	
			
			//pages	
			if ($lastpage < 7 + ($adjacents * 2)) :	//not enough pages to bother breaking it up
			
				for ($counter = 1; $counter <= $lastpage; $counter++) :
				
					if ($counter == $page)	$pagination.= "<span class=\"current\">$counter</span>";
					else					$pagination.= "<a href=\"$targetpage&number=$counter\">$counter</a>";					
					
				endfor;
				
			elseif($lastpage > 5 + ($adjacents * 2)) :	//enough pages to hide some
	
				//close to beginning; only hide later pages
				if($page < 1 + ($adjacents * 2)) :
				
					for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++) :
						if ($counter == $page) 	$pagination.= "<span class=\"current\">$counter</span>";
						else					$pagination.= "<a href=\"$targetpage&number=$counter\">$counter</a>";					
						
					endfor;
					
					$pagination.= "...";
					$pagination.= "<a href=\"$targetpage&number=$lpm1\">$lpm1</a>";
					$pagination.= "<a href=\"$targetpage&number=$lastpage\">$lastpage</a>";		
	
				//in middle; hide some front and some back
				elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)) :
				
					$pagination.= "<a href=\"$targetpage&number=1\">1</a>";
					$pagination.= "<a href=\"$targetpage&number=2\">2</a>";
					$pagination.= "...";
					
					for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++) :
	
						if ($counter == $page)	$pagination.= "<span class=\"current\">$counter</span>";
						else					$pagination.= "<a href=\"$targetpage&number=$counter\">$counter</a>";					
							
					endfor;
					$pagination.= "...";
					$pagination.= "<a href=\"$targetpage&number=$lpm1\">$lpm1</a>";
					$pagination.= "<a href=\"$targetpage&number=$lastpage\">$lastpage</a>";		
					
				//close to end; only hide early pages
				else :
					$pagination.= "<a href=\"$targetpage&number=1\">1</a>";
					$pagination.= "<a href=\"$targetpage&number=2\">2</a>";
					$pagination.= "...";
					
					for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++) :
	
						if ($counter == $page)	$pagination.= "<span class=\"current\">$counter</span>";
						else					$pagination.= "<a href=\"$targetpage&number=$counter\">$counter</a>";					
						
					endfor;
				endif;
			endif;
			
			//button
			if ($page < $counter - 1) 	$pagination.= "<a href=\"$targetpage&number=$next\">&raquo;</a>";
			else						$pagination.= "<span class=\"disabled\">&raquo;</span>";
			$pagination.= "</div>\n";		
			
		endif;
		
		echo($pagination);	
	}
}

$gotcha_app	= new gotchaApp;
$gotcha_app->init();

// ================================================================	//
// CORE
// ================================================================	//

function gotchaDebug()
{
	global $gotcha;
	$vars	= func_get_args();

	if($gotcha['debug']) :
	
		foreach($vars as $var) :
			?><pre><?php
			print_r($var);
			?></pre><?php	
		endforeach;
	
	endif;
}

function gotchaGetOption($name,$type = 'text',$data = null)
{
	global $gotcha;
	$value	= get_option($gotcha['prefix'].$name);
	$value	= (!is_array($value)) ? stripslashes($value) : $value;
	
	if(!empty($value)) :
		switch($type) :
			case "image"	: return "<img alt='' src='".$value."' title='".$data."' />"; 
							  break;
							  
			case "text"		: return $value; break;
			
			case "post"		: return get_permalink($value); break;	
			
			case "category"	: return get_category_link($value); break;	  
			
			case "array"	: return explode(',',str_replace(' ','',$value));
							  break;
							  
			case "attach"	: return wp_get_attachment_url($value); break;
		endswitch;
	endif;
	
	return '';
}

function gotchaTaxoMeta($taxo,$term_id,$name,$type = 'text')
{
	global $gotcha;
	
	$name	= $taxo.'_'.$term_id.'_'.$name;
	
	return gotchaGetOption($name,$type);
}

function gotchaCustomField($name,$postID = NULL,$type = 'text')
{
		
	$postID	= ( is_null($postID) ) ? get_the_ID() : $postID;
		
	$value	= get_post_meta($postID,$name);
	$value	= (sizeof($value) > 0) ? end($value) : NULL;	
	
	switch($type) :
		
		case "image"	: $value	= "<img src='".$value."' alt='' />"; break;
		case "post"		: $value	= get_permalink($value); break;
		case "attach"	: $value	= wp_get_attachment_url($value); break;
		default			:
		case "text"		: break;
		
	endswitch;
		
	return $value;
}

function gotchaAdminCallbackPermissionPage()
{
	global $pagenow;
	
	$allowed_page	= array(
		'post-new.php','user.php','admin.php','post.php','users.php','edit-tags.php',
	);	
	
	return in_array($pagenow,$allowed_page);
}

add_action('wp_ajax_gotcha-media-upload-show-image','gotchaMediaUploadGetImage');

function gotchaMediaUploadGetImage() 
{
	if ( wp_attachment_is_image( $_REQUEST['id'] ) ) :
		echo wp_get_attachment_image( $_REQUEST['id'],'thumbnail');
	else :
		echo gotchaMediaUploadFileLink( $_REQUEST['id'] );
	endif;
	exit();
}

// Generate markup for file link
function gotchaMediaUploadFileLink( $id ) 
{
	$attachment_url = wp_get_attachment_url( $id );
	$filetype_check = wp_check_filetype( $attachment_url );
	$filetype_parts = explode( '/', $filetype_check['type'] );
	return '<a href="' . wp_get_attachment_url( $id ) . '" style="display: block; min-height:32px; padding: 10px 0 0 38px; background: url(' . plugins_url( "img/icon-" . $filetype_parts[1] . ".png", __FILE__ ) . ') no-repeat; font-size: 13px; font-weight: bold;">' . basename( $attachment_url ) . '</a>';
}

?>