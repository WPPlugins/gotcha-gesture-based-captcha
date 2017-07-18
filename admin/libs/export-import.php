<?php
global $gotchaExportImport;

class gotchaExportImport
{
	var $upload	= false;
	var $file	= "";
	var $xml;
	var $message;
	
	// ====================================================================
	// INITIALIZATION
	// ====================================================================	
	function gotchaExportImport()
	{
		global $gotcha;
		
		$uploaded_file	= gotchaGetOption('upload-xml');
		
		if(!$uploaded_file)
			update_option($gotcha['prefix'].'upload-xml',false);
		
		if(isset($_REQUEST['page']) && isset($_REQUEST['_wpnonce'])) :
			if(wp_verify_nonce($_REQUEST['_wpnonce'],'gotcha-export')) :
				$this->generateXML();
			endif;
		
			if(wp_verify_nonce($_REQUEST['_wpnonce'],'gotcha-import') && isset($_FILES['xml']) && $_FILES['xml']['error'] == 0 ) :
				$this->xml	= $_FILES['xml'];
				$this->uploadXML();
			endif;
		endif;	
	}
	
	// ====================================================================
	// EXPORT
	// ====================================================================	
	
	function export()
	{
		$link	= wp_nonce_url(admin_url("admin.php?page=gotcha-setting&amp;menu=expimp"),'gotcha-export');
		?><a id="export-xml" href="<?php echo $link; ?>" class="button button-primary">Generate XML</a><?php
	}
	
	// ====================================================================
	// IMPORT
	// ====================================================================	
	
	function import()
	{
		global $gotcha;
		$uploaded_file	= gotchaGetOption('upload-xml');
		
		$link	= wp_nonce_url(admin_url("admin.php?page=gotcha-setting&amp;menu=expimp"),'gotcha-import');
		?>
        <form method="post" action="<?php echo $link; ?>" enctype="multipart/form-data">
        	<input type="file" name="xml" id="import-xml" />
            <input type="submit" value="Upload" class="button button-primary" />
        </form>
        
		<?php if($uploaded_file) : ?>
        <div id="xml-notification" class="message">
			<p>Are you sure want to replace all theme and color settings from uploaded XML ? It can't be reverted</p>
            <p>
            	<a id="import-xml-now" href="#" class="button button-primary">Yes, please replace all theme and color settings</a>
            </p>
        </div>
        
        <div id="xml-process">
        </div>
        
        <script type="text/javascript">
		jQuery('#import-xml-now').click(function(){
			data	= {
				action	: 'gotcha-import',	
			}
			
			jQuery.ajax({
				url		: "<?php echo admin_url('admin-ajax.php'); ?>",
				data	: data,
				type	: "POST",
				beforeSend	: function() {
//					jQuery('#xml-notification').hide();
					jQuery('#xml-process').show();
				},
				success		: function(response) {
					result	= jQuery.parseJSON(response);
					jQuery('#xml-process').html(result.process);
				}
			});
			
			return false;
		});
		</script>
        <?php
			update_option($gotcha['prefix'].'upload-xml',false);
		endif;
	}
	
	// ====================================================================
	// generateXML
	// ====================================================================	
	
	function generateXML()
	{
		global $wpdb,$gotcha;
		
		$query		= "SELECT * ".
					  "FROM ".$wpdb->options." ".
					  "WHERE option_name LIKE '".$gotcha['prefix']."%'; ";
				  
		$results	= $wpdb->get_results($query,ARRAY_A);
		
		header('Content-type: text/xml');
		header('Content-Disposition: attachment; filename="'.$gotcha['slug'].'-'.date('y-m-d-h-i-s').'.xml"');
		
		?>
        <xml>
        	<siteinfo>
            	<name><?php bloginfo('name'); ?></name>
            	<url><?php bloginfo('url'); ?></url>
                <themeprefix><?php echo $gotcha['prefix']; ?></themeprefix>
                <date><?php echo date("Y M d H:i:s"); ?></date>
            </siteinfo>
            <?php foreach($results as $result) : ?>
            <setting>
            	<name><?php echo $result['option_name']; ?></name>
                <value><?php echo htmlspecialchars(stripslashes($result['option_value'])); ?></value>
			</setting>
            <?php endforeach; ?>
		<?php
		?></xml><?php
		
		exit();
	}
	
	// ====================================================================
	// uploadXML
	// ====================================================================	
	
	function uploadXML()
	{
		global $gotcha;
		
		if($this->xml['type'] <> 'text/xml') :
			$this->message	= array(
				'type'		=> 'error',
				'message'	=> 'You have uploaded invalid file, please use correct XML file'
			);
			add_action('admin_notices',array(&$this,'message'));
			return;
		endif;
		
		$uploads	= wp_upload_dir();
		
		$this->file	= array(
			'url'	=> $uploads['url'].'/'.$gotcha['slug'].'-xml-import.xml',
			'path'	=> $uploads['path'].'/'.$gotcha['slug'].'-xml-import.xml',
			'file'	=> $this->xml,
		);
		
		if(move_uploaded_file($this->xml['tmp_name'],$this->file['path'])) :
			chmod($this->file['path'],0777);
			
			update_option($gotcha['prefix'].'xml-import-data',$this->file);
			update_option($gotcha['prefix'].'upload-xml',true);
		endif;
	}
	
	// ====================================================================
	// importXML
	// ====================================================================	
	
	function importXML()
	{
		ob_start();
		
		$file	= gotchaGetOption('xml-import-data');

		$xml	= simplexml_load_string(file_get_contents($file['url']), 'SimpleXMLElement', LIBXML_NOCDATA);
		$xml 	= @json_decode(@json_encode((array)$xml),1);
		
		?>
        <p>The xml file was generated on <strong><?php echo $xml['siteinfo']['date']; ?></strong></p>
        <p>There are <strong><?php echo count($xml['setting']); ?></strong> theme and color settings</p>
        <p>
        	Theme system will replace static url <strong>( <?php echo $xml['siteinfo']['url']; ?> )</strong> in theme and color setting values 
            with this site url <strong>( <?php bloginfo('url'); ?> )</strong>
		</p>
        <ul>
        <?php
		foreach($xml['setting'] as $setting) :
			$name	= $setting['name'];
			
			$value	= (empty($setting['value'])) ? "" : str_replace($xml['siteinfo']['url'],get_bloginfo('url'),maybe_unserialize($setting['value']));

			if(update_option($name,$value)) :
			?><li><strong><?php echo $name; ?></strong>...... ok</li><?php
			endif;

		endforeach;
		
		?></ul><?php
		
		$message= ob_get_contents();
		
		ob_end_clean();
		
		echo(json_encode(array(
			'process'	=> $message,
		)));
		
		exit();
	}

	// ====================================================================
	// message
	// ====================================================================	

	function message()
	{
		?>
        <div class='message <?php echo $this->message['type']; ?>'>
        	<p><?php echo $this->message['message']; ?></p>
        </div>
        <?php
	}
}

add_action('init','gotchaExportImport');
add_action('wp_ajax_gotcha-import','gotchaImportXML');

function gotchaExportImport()
{
	global $gotchaExportImport;	
	
	$gotchaExportImport	= new gotchaExportImport;
}

function gotchaImportXML()
{
	global $gotchaExportImport;
	
	$gotchaExportImport->importXML();	
}

?>