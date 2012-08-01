








function sp_cu_confirm(div,h,url){
	
	jQuery(  div ).dialog({
			resizable: false,
			height:h,
			modal: true,
			buttons: {
				"Yes": function() {
					window.location = url;
				},
				Cancel: function() {
					jQuery( this ).dialog( "close" );
				}
			}
		});
	
}

function sp_cu_dialog(div,w,h){
	
	jQuery(div ).dialog({
			height:h,
			width:w
	});
}


jQuery(document).ready(function() {




//add another file input when one is selected
var max = 20;
var replaceMe = function(){
	var obj = jQuery(this);
	if(jQuery("#cdm_upload_fields input[type='file']").length > max)
	{
		alert('fail');
		obj.val("");
		return false;
	}
	jQuery(obj).css({'position':'absolute','left':'-9999px','display':'none'}).parent().prepend('<input type="file" name="'+obj.attr('name')+'"/>')
	jQuery('#upload_list').append('<div class="sp_upload_div"><span class="sp_upload_name">'+obj.val()+'</span><input type="button" value="cancel"/><div>');
	jQuery("#cdm_upload_fields input[type='file']").change(replaceMe);
	jQuery("#cdm_upload_fields input[type='button']").click(function(){
		jQuery(this).parent().remove();
		jQuery(obj).remove();
		return false;
		
		
	});
}
jQuery("#cdm_upload_fields input[type='file']").change(replaceMe);







        jQuery('a.su_ajax').click(function() {
            var url = this.href;
            // show a spinner or something via css
            var dialog = jQuery('<div style="display:none" class="loading"></div>').appendTo('body');
            // open the dialog
            dialog.dialog({
                // add a close listener to prevent adding multiple divs to the document
                close: function(event, ui) {
                    // remove div with all data and events
                    dialog.remove();
                },
                modal: true,
				title: jQuery(this).attr('title'),
				height:400,
				width:700
            });
            // load remote content
            dialog.load(
                url, 
                {}, // omit this param object to issue a GET request instead a POST request, otherwise you may provide post parameters within the object
                function (responseText, textStatus, XMLHttpRequest) {
                    // remove the loading class
                    dialog.removeClass('loading');
                }
            );
            //prevent the browser to follow the link
            return false;
        });

});






	
