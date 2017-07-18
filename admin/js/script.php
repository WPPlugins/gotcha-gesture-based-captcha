<?php
	header("Content-type: text/javascript"); 
?>
Date.firstDayOfWeek = 0;
Date.format = 'yyyy-mm-dd';


    function gotchaUploadMedia()
    {
        jQuery('.upload-image-button').click(function(){
			targetID	= jQuery(this).attr('data-target');
            tb_show('', 'media-upload.php?type=image&amp;context=gotcha-custom&amp;gotcha-image-field=' + targetID + "&amp;TB_iframe=1");
			jQuery( 'html' ).addClass( 'File' );
     
            return false;
        });
        
        jQuery('.clear-image-button').click(function(){
        	confirmed	= confirm("Are you sure want to clear the image?");
            
            if(confirmed)
            {
	            var targetVal 		= "#" + jQuery(this).attr('data-target');
    	        var targetPreview	= "#preview-" + jQuery(this).attr('data-target');
            
        	    jQuery(targetVal).val('');
            	jQuery(targetPreview).html('');
            }
            return false;
        });
        
        jQuery('.upload-video-button').click(function(){
            var targetVal 		= '#' + jQuery(this).attr('alt');
            tb_show('', 'media-upload.php?type=video&amp;TB_iframe=1');
     
            window.send_to_editor = function(html) {
                videoUrl = jQuery(html).attr('href');
                jQuery(targetVal).val(videoUrl);
                tb_remove();
            }
     
            return false;
        });
        
        jQuery('.upload-file-button').click(function(){
            var targetVal 		= '#' + jQuery(this).attr('alt');
            tb_show('', 'media-upload.php?&amp;TB_iframe=1');
     
            window.send_to_editor = function(html) {
                videoUrl = jQuery(html).attr('href');
                jQuery(targetVal).val(videoUrl);
                tb_remove();
            }
     
            return false;
        });
        
        jQuery('#gotcha-media .section .help a.show-help').hover(function(){
        	jQuery(this).next().fadeIn();
        });
        
        jQuery('#gotcha-media .section .help a.show-help').click(function(){
			return false;
        });
        
		jQuery('body').click(function(){
        	jQuery('#gotcha-media .section .help-content').hide();
        });
    }
	
	function gotchaCheckMediaUpload()
	{
		// Actions for the Media Library overlay
		if ( jQuery( "body" ).attr( 'id' ) == 'media-upload' ) {
			
			// Make sure it's an overlay invoked by this plugin
			var parent_doc, parent_src, parent_src_vars, current_tab;
			var select_button = '<a href="#" class="gotcha-media-upload-insert button-secondary">Use this file</a>';
			parent_doc = parent.document;
			parent_src = parent_doc.getElementById( 'TB_iframeContent' ).src;
			parent_src_vars = gotcha_fs_get_url_vars( parent_src );
			
			if ( 'gotcha-image-field' in parent_src_vars ) {
				current_tab = jQuery( 'ul#sidemenu a.current' ).parent( 'li' ).attr( 'id' );
				jQuery( 'ul#sidemenu li#tab-type_url' ).remove();
				jQuery( 'p.ml-submit' ).remove();
				
				switch ( current_tab ) {
					case 'tab-type': {
						// File upload
						jQuery( 'table.describe tbody tr:not(.submit)' ).remove();
						//jQuery( 'table.describe tr.submit td.savesend input' ).replaceWith( select_button );
						jQuery( 'table.describe tr.submit td.savesend input' ).remove();
						jQuery( 'table.describe tr.submit td.savesend' ).prepend( select_button );
						break;
					}
					case 'tab-library': {
						// Media Library
						jQuery( '#media-items .media-item a.toggle' ).remove();
						jQuery( '#media-items .media-item' ).each( function() {
							jQuery( this ).prepend( select_button );
						});
						jQuery( 'a.gotcha-media-upload-insert' ).css({
							'display':				'block',
							'float':					'right',
							'margin':				'7px 20px 0 0'
						});
						break;
					}
				}
				// Select functionality
				jQuery( 'a.gotcha-media-upload-insert' ).click( function() {
					var item_id;
					if ( jQuery( this ).parent().attr( 'class' ) == 'savesend' ) {
						item_id = jQuery( this ).siblings( '.del-attachment' ).attr( 'id' );
						item_id = item_id.match( /del_attachment_([0-9]+)/ );
						item_id = item_id[1];
					} else {
						item_id = jQuery( this ).parent().attr( 'id' );
						item_id = item_id.match( /media\-item\-([0-9]+)/ );
						item_id = item_id[1];
					}
					
					parent.gotcha_fs_select_item( item_id, parent_src_vars['gotcha-image-field'] );
					
					return false;
				});
			}
		
		}	
	}
	
    function gotcha_fs_get_url_vars( s ) {
        var vars = {};
        var parts = s.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
            vars[key] = value;
        });
        return vars;
    }
    
    function gotcha_fs_select_item( item_id, field_id ) {
        var field, preview_div, preview_size;
        field = jQuery( '#' + field_id );
        preview_div 	= jQuery( '#preview-' + field_id);
        // Load preview image
        preview_div.html( '' ).load(ajaxurl,{
            id		: item_id,
            action	: 'gotcha-media-upload-show-image'
        });
        // Pass ID to form field
        field.val( item_id );
        // Close interface down
        tb_remove();
        jQuery( 'html' ).removeClass( 'File' );
    }
    
    function gotchaAdmin()
    {
        /* other */
        jQuery(".cb-enable").click(function(){
            var parent = jQuery(this).parents('.switch');
            jQuery('.cb-disable',parent).removeClass('selected');
            jQuery(this).addClass('selected');
            jQuery('.checkbox',parent).attr('checked', true);
        });
        jQuery(".cb-disable").click(function(){
            var parent = jQuery(this).parents('.switch');
            jQuery('.cb-enable',parent).removeClass('selected');
            jQuery(this).addClass('selected');
            jQuery('.checkbox',parent).attr('checked', false);
        });
        
        if(jQuery().iButton) {
            jQuery("input.iButton:checkbox").iButton();
        }
        
        if(jQuery().rating) {
            jQuery("input.star-rating").rating();
        }
        
        jQuery("#gotcha-nav a").click(function(){
            var targetID = jQuery(this).attr('href');
            jQuery(this).addClass('current').siblings().removeClass('current');
                            
            jQuery(".gotcha-body").hide();
            jQuery(targetID).fadeIn();
                            
            return false;
        });
        
        jQuery(".gotcha-body .custom-radio-wrapper :input").each(function(){
            var checked	= jQuery(this).is(':checked');
            
            if(checked)
            { jQuery(this).parent().addClass('custom-radio-clicked'); }
        });
        
        jQuery('.gotcha-body .custom-radio').mouseenter(function(){
            jQuery(this).addClass('custom-radio-hover');
        });
        
        jQuery('.gotcha-body .custom-radio').mouseleave(function(){
            jQuery(this).removeClass('custom-radio-hover');
        });
        
        jQuery('#gotcha-form .images-chooser .image-holder').click(function(){
            holderParent	= jQuery(this).parent();
            
            holderParent.find('.image-holder').removeClass('image-holder-checked');
            holderParent.find('input[type=radio]').attr('checked',false);
            
            jQuery(this).addClass('image-holder-checked').find('input[type=radio]').attr('checked',true);
        });
        
        jQuery("#gotcha-body .container .float-nav").ready(function(){
   			jQuery(window).scroll(function () {
            	set = jQuery(document).scrollTop()+"px";
                jQuery("#gotcha-body .container .float-nav").animate({top:set},{duration:500,queue:false});
            });
		});
	
		jQuery('#gotcha-body .container .float-nav a').click(function(){
			target	= jQuery(this).attr('href');
            console.log(target);
            topPosition = jQuery(target).offset().top - 35;
			jQuery('html, body').animate({scrollTop:topPosition}, 'slow');
			return false;
		});
        
        gotchaUploadMedia();
        gotchaCheckMediaUpload()
    }

jQuery(document).ready(function(){    
  
  	gotchaAdmin();
    
});