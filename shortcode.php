<?php



function display_sp_thumbnails($r ){
	global $wpdb,$current_user,$user_ID;
	
	
	
	if(get_option('sp_cu_wp_folder') == ''){
	$wp_con_folder = '/';	
	}else{
		$wp_con_folder = get_option('sp_cu_wp_folder') ;
	}
	$html .= '<script type="text/javascript" src="'. plugins_url('js/jqueryFileTree/jqueryFileTree.js', __FILE__).'"></script>
			<link rel="stylesheet" type="text/css" media="all" href="'. plugins_url('js/jqueryFileTree/jqueryFileTree.css', __FILE__).'" />
		';
	$html .="
	<div class=\"sp_cu_filetree\">
	<script type=\"text/javascript\">
	
	
	function cdm_load_simple_file_manager(){
		
		var cdm_search = jQuery('#search_files').val();
		
		
	jQuery('#file_manager').fileTree({
        root: '". content_url()."/uploads/sp-client-document-manager/".$user_ID."/',
        script: '".content_url()."/plugins/sp-client-document-manager/ajax.php?function=file-tree&uid=".$user_ID."&search=' + cdm_search,
        expandSpeed: 100,
        collapseSpeed: 100,
        multiFolder: false
    }, function(file) {
       // alert(file);
	   
	   
	   	  var url = '".content_url()."/plugins/sp-client-document-manager/ajax.php?function=view-file&id=' + file;
		 //alert( url);
            // show a spinner or something via css
            var dialog = jQuery('<div style=\"display:none\" class=\"loading\"></div>').appendTo('body');
            // open the dialog
            dialog.dialog({
                // add a close listener to prevent adding multiple divs to the document
                close: function(event, ui) {
                    // remove div with all data and events
                    dialog.remove();
                },
                modal: true,
				height:450,
				width:850
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
	   
	   
	   
	   
	   //end functions
	   
    });	
		
	}
	
	
	jQuery(document).ready( function() {
		cdm_load_simple_file_manager();
    
});
	</script>
	
	<div id=\"file_manager\">
	
	</div></div>
	";

				
	
	return 	$html ;
	
}








if (!function_exists('display_sp_upload_form')){

function display_sp_upload_form(){
	
global $wpdb,$current_user;



$html .='
<script type="text/javascript">


function sp_cdm_change_indicator(){
	

jQuery(".sp_change_indicator_button").hide();
jQuery(".sp_change_indicator").slideDown();



jQuery(\'.sp_change_indicator\').html(\'<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0"  width="204" height="16"  id="mymoviename"><param name="movie" value="'. content_url(). '/plugins/sp-client-document-manager/image_138464.swf" /><param name="quality" value="high" /><param name="bgcolor" value="#ffffff" /><embed src="'.content_url(). '/plugins/sp-client-document-manager/image_138464.swf" quality="high" bgcolor="#ffffff" width="204" height="16" name="mymoviename" align="" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer"></embed></object><br><em>Please wait, your file is currently uploading! </em>\');
document.forms["sp_upload_form"].submit();
return true;

}


jQuery(document).ready(function() {
	jQuery("#upload_form").simpleValidate({
	  errorElement: "em",
	  ajaxRequest: false,
	  errorText: "'.__("Required","sp-cdm").'",
	   completeCallback: function() {
      
	  sp_cdm_change_indicator();
	  }
	});
});

</script>
<div style="display:none">
	<div  id="sp_cu_add_project">
		<form action="'.$_SERVER['REQUEST_URI'].'" method="post">
		'.__("Project Name:","sp-cdm").' <input type="text" name="project-name"  style="width:200px !important"> <input type="submit" value="'.__("Add Project","sp-cdm").'" name="add-project">
	</form>
	</div>
<div id="sp_cu_confirm_delete">
<p>'.get_option('sp_cu_delete').'</p>
</div>

<div id="sp_cu_thankyou">
<p>'.get_option('sp_cu_thankyou').'</p>
</div>


		

</div>

<form  action="'.$_SERVER['REQUEST_URI'].'" method="post" enctype="multipart/form-data" id="upload_form" name="sp_upload_form" >';




			$html .='<div>
			<p>'.stripslashes(get_option('sp_cu_form_instructions')).'</p>
				  <table width="100%" cellpadding="2" cellspacing="2" style="border:none;padding:0px;margin:0px;">
  <tr>
    <td>'.__("File Name:","sp-cdm").'</td>
    <td><input  type="text" name="dlg-upload-name" class="required"></td>
  </tr>
  
  ';
  
 $html .= sp_cdm_display_projects(); 
  
 if (CU_PREMIUM == 1){ 

 $html .= sp_cdm_display_categories(); 
 }
 

 
  $html .= '
  <tr>
    <td>'.__("File:","sp-cdm").'</td>
    <td>	    <input id="file_upload" name="dlg-upload-file[]" type="file" class="required" multiple>
<div id="upload_list"></div>
							</td>
  </tr>';
  
    if (CU_PREMIUM == 1){ 
	
	if( get_option('sp_cu_enable_tags') ==1){
   $html .= '
  <tr>
    <td>'.__("Tags:","sp-cdm").'</td>
    <td><textarea id="tags" name="tags"  style="width:90%;height:30px"></textarea></td>
  </tr>';
  
	}
  
  $html .=display_sp_cdm_form();
  
  }else{
	  
  $html .='<tr>
    <td>'.__("Notes:","sp-cdm").'</td>
    <td><textarea style="width:90%;height:50px" name="dlg-upload-notes"></textarea></td>
  </tr>
  ';
  }
  $html .='
  <tr>
    <td>&nbsp;</td>
    <td>
						<div class="sp_change_indicator_button"><input id="dlg-upload" onclick="sp_change_indicator()" type="submit" name="submit" value="Upload" ></div>
						<div class="sp_change_indicator" ></div>	
							</td>
  </tr>';

  
  $html .='
</table></div>';




$html .='

	</form>
	
	
	';
	
	return $html;
}

function check_folder_sp_client_upload(){
	global $user_ID;
	
	$dir = ''.ABSPATH.'wp-content/uploads/sp-client-document-manager/'.$user_ID.'/';
	if(!is_dir($dir)){
	
		mkdir($dir, 0777);
	}
	
}

function display_sp_client_upload($atts){

	global $wpdb ;
	global $user_ID;
	global $current_user;
     get_currentuserinfo();
 if ( is_user_logged_in() ) { 



if($_GET['dlg-delete-file'] != ""){
	
	
	
		$wpdb->query("
	DELETE FROM ".$wpdb->prefix."sp_cu WHERE id = ".$_GET['dlg-delete-file']."
	");
	
		 
}

if($_POST['submit'] != ""){



	
	$data = $_POST;
	$files = $_FILES;
	$a['uid'] = $user_ID;
	$a['name'] = addslashes($data['dlg-upload-name']);
	$a['pid'] = $data['pid'];
	$a['cid'] = $data['cid'];
	$a['tags'] = $data['tags'];
	$a['notes'] = addslashes($data['dlg-upload-notes']);
	check_folder_sp_client_upload();
	

	if($files['dlg-upload-file']['name'] != ""){
	
	
	
	$a['file'] = sp_uploadFile($files);
    $wpdb->insert(  "".$wpdb->prefix."sp_cu", $a );
	
	 if (CU_PREMIUM == 1){ 
	  
	 process_sp_cdm_form_vars($data['custom_forms'],$wpdb->insert_id);
	 
	 }
	$to = get_option('admin_email');
	$headers .= "".__("From:","sp-cdm")." ".$current_user->user_firstname." ".$current_user->user_lastname." <".$current_user->user_email.">\r\n";
	$message  = "".__("Client uploaded a new document","sp-cdm")."<br><br> ".__("Click here view the files:","sp-cdm")." " . get_bloginfo('wpurl') . "/wp-admin/user-edit.php?user_id=".$user_ID."#downloads";
	$subject = __("New file upload from client","sp-cdm");
	wp_mail( $to, $subject, $message, $headers, $attachments );
	
		$html .= '<script type="text/javascript">


jQuery(document).ready(function() {
 sp_cu_dialog("#sp_cu_thankyou",400,200);
});
</script>';
}else{
	
	$html .= '<p style="color:red">'.__("Please upload a file!","sp-cdm").'</p>';
	
}
}





$r = $wpdb->get_results("SELECT *  FROM ".$wpdb->prefix."sp_cu   where uid = $user_ID  order by date desc", ARRAY_A);




//show uploaded documents
  $html .= '
  

  
  
    <div style="display:none">
  <div id="cp_cdm_upload_form">
  '.display_sp_upload_form().'
  </div>
  </div>
  


	
	<div >

 
';

if(get_option('sp_cu_user_projects_thumbs') == 1){

	
		$html .='
	<script type="text/javascript">
	
	function cdm_ajax_search(){
		
	var cdm_search = jQuery("#search_files").val();
	jQuery("#cmd_file_thumbs").load("'.content_url().'/plugins/sp-client-document-manager/ajax.php?function=thumbnails&uid='.$user_ID.'&search=" + cdm_search);		
		
	}
	</script>
	
	';
	
}else{
	$html .='
	<script type="text/javascript">
	
	function cdm_ajax_search(){
		
	 cdm_load_simple_file_manager();	
	}
	</script>
	
	';
}
	
	
	$html .='Search: <input  onkeyup="cdm_ajax_search()" type="text" name="search" id="search_files">  <a href="javascript:sp_cu_dialog(\'#cp_cdm_upload_form\',700,600)"><img src="' . content_url() . '/plugins/sp-client-document-manager/images/add.png"> '.__("Add File","sp-cdm").'</a>  ';

if(get_option('sp_cu_user_projects_thumbs') == 1){
		$html .=display_sp_cdm_thumbnails($r );
}else{
		$html .=display_sp_thumbnails($r );
}
		$html .='</div>';





 
  } else{
	  
	  return '<script type="text/javascript">
<!--
window.location = "'. get_bloginfo('url').'/login/?redirect_to='.urlencode($_SERVER['REQUEST_URI']).'"
//-->
</script>';
	 
  }
  
return $html;
  
}
}
	add_shortcode( 'sp-client-media-manager', 'display_sp_client_upload' );	
	add_shortcode( 'sp-client-document-manager', 'display_sp_client_upload' );	
?>