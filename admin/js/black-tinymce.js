var tinyMCEPreInit;
var edCanvas;
var wpActiveEditor;

function gotcha_deferred_activate_editor(id)
{			
	if (jQuery.active == 0 && typeof (tinyMCE.get(id)) != "object") 
	{
       	jQuery('a[id$=visual]', jQuery(this)).click()
    } else if (typeof (tinyMCE.get(id)) != "object") 
		{
           	setTimeout(function () {
           	gotcha_deferred_activate_editor(id);
           	id = null
       	}, 100)
   	}
}

function gotcha_activate_editor(id)
{
    if (typeof (tinyMCE) == "object" && typeof (tinyMCE.execCommand) == "function") 
    {
        if (typeof (tinyMCEPreInit.mceInit['gotcha-tinymce']) == "object") 
        {
            tinyMCEPreInit.mceInit[id] = tinyMCEPreInit.mceInit['gotcha-tinymce'];
            tinyMCEPreInit.mceInit[id]["elements"] = id;
            try {
                    tinymce.init(tinymce.extend({}, tinyMCEPreInit.mceInit['gotcha-tinymce'], tinyMCEPreInit.mceInit[id]))
            } catch (e) 
            { alert(e) }
        } else 
        { tinyMCE.execCommand("mceAddControl", false, id) }
    }	
}

function gotcha_deactivate_editor(id) 
{
    if (typeof (tinyMCE) == "object" && typeof (tinyMCE.execCommand) == "function" && tinyMCE.get(id) != undefined) {
        if (typeof (tinyMCE.get(id)) == "object") {
            content = tinyMCE.get(id).getContent();
            tinyMCE.execCommand("mceRemoveControl", false, id);
            jQuery('textarea#' + id).val(content)
        }
    }
}

var gotchaInitEditor	= function(target){
	
	//deactivate all textarea
	jQuery(target).find('.gotcha-editor-holder').each(function(){

		jQuery(this).find('textarea').each(function(){

			id	= jQuery(this).attr('id');
		 	gotcha_deferred_activate_editor(id);
			gotcha_activate_editor(id)
		});
	});
	
	jQuery(target + ' a.gotcha-editor-visual').live('click', function (event) {
		id	= jQuery(this).attr('target');
    	event.preventDefault();     		
				
    	jQuery(this).addClass('active');
        		
		jQuery('a[id^=' + id + '][id$=html]').removeClass('active');

		gotcha_activate_editor(id)
    });
	
	jQuery(target + ' a.gotcha-editor-html').live('click', function (event) {
		id	= jQuery(this).attr('target');
		event.preventDefault();
				
		jQuery(this).addClass('active');
				
		jQuery('a[id^=' + id + '][id$=visual]').removeClass('active');
		gotcha_deactivate_editor(id)
	});
	
    jQuery(target + ' .editor_media_buttons a').live('click', function () {
        edCanvas 		= jQuery('textarea', jQuery(this).closest('div.gotcha-editor-holder')).get();
        wpActiveEditor 	= jQuery('textarea', jQuery(this).closest('div.gotcha-editor-holder')).attr('id')
    })
};