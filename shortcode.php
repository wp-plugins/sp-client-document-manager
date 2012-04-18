<?php


function display_sp_thumbnails($r ){
	global $wpdb,$current_user,$user_ID;
	
	$html .= '<script type="text/javascript" src="/wp-content/plugins/sp-client-document-manager/js/jqueryFileTree/jqueryFileTree.js"></script>
			<link rel="stylesheet" type="text/css" media="all" href="/wp-content/plugins/sp-client-document-manager/js/jqueryFileTree/jqueryFileTree.css" />
		';
	$html .="
	<div class=\"sp_cu_filetree\">
	<script type=\"text/javascript\">
	
	jQuery(document).ready( function() {
    jQuery('#file_manager').fileTree({
        root: '/wp-content/uploads/sp-client-document-manager/".$user_ID."/',
        script: '/wp-content/plugins/sp-client-document-manager/ajax.php?function=file-tree&uid=".$user_ID."',
        expandSpeed: 1000,
        collapseSpeed: 1000,
        multiFolder: false
    }, function(file) {
       // alert(file);
	   
	   
	   	  var url = '/wp-content/plugins/sp-client-document-manager/ajax.php?function=view-file&id=' + file;
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
});
	</script>
	
	<div id=\"file_manager\">
	
	</div></div>
	";

				
	
	return 	$html ;
	
}






function display_sp_thumbnails2($r ){
	global $wpdb,$current_user,$user_ID;
	
	
		for($i=0; $i<count($r); $i++){
				$ext = substr(strrchr($r[$i]['file'], '.'), 1);	
					
					if($r[$i]['pid'] != 0){
						  $projecter = $wpdb->get_results("SELECT *
	
									 FROM ".$wpdb->prefix."sp_cu_project
									 WHERE id = '".$r[$i]['pid']."'
									 ", ARRAY_A);
									 
									 
							$project_title ='Project: '.stripslashes($projecter[0]['name']).'';		 	
					}else{
						$project_title = 'Project: None';
					}
					
					
					if($ext== 'png' or $ext == 'jpg' or $ext = 'jpeg' or $ext = 'gif' ){
					$icon = '<td><img src="/wp-content/uploads/sp-client-document-manager/'.$user_ID.'/'.$r[$i]['file'].'" width="90"></td>';	
					}else{
					$icon = '';		
					}
					
				$html .= '
				<div class="sp_cu_item">
				 
				
				  <table width="100%" cellpadding="2" cellspacing="2" style="border:none;padding:0px;margin:0px;">

<tr>
'.$icon .'
<td><h3> '.stripslashes($r[$i]['name']).'</h3></td>
<td style="text-align:right"> <em>'.date('F jS Y h:i A', strtotime($r[$i]['date'])).'</em></td>
</tr>
<tr>
<td style="text-align:left">'.$project_title .'<br>Notes: <em>'.$r[$i]['notes'].'</em></td>

    
    <td style="text-align:right;width:170px;">
	
	
	<a href="' . get_bloginfo('wpurl') . '/wp-content/plugins/sp-client-document-manager/download.php?uid='.$user_ID.'&file='.$r[$i]['file'].'" title="Download" style="margin-right:15px"  ><img src="' . get_bloginfo('wpurl') . '/wp-content/plugins/sp-client-document-manager/images/download.png"></a> 
	<a href="javascript:sp_cu_confirm(\'#sp_cu_confirm_delete\',200,\'?dlg-delete-file='.$r[$i]['id'].'#downloads\');" title="Delete" ><img src="' . get_bloginfo('wpurl') . '/wp-content/plugins/sp-client-document-manager/images/delete.png"></a> </td>
	</tr>

  </tr>
  </table></div>';	
					
			
}

				
	
	return 	$html ;
	
}





if (!function_exists('display_sp_upload_form')){

function display_sp_upload_form(){
	
global $wpdb,$current_user;



$html .='
<script type="text/javascript">


jQuery(document).ready(function() {
jQuery("#dlg-upload").bind("click", function() {

jQuery(".sp_change_indicator_button").hide();
jQuery(".sp_change_indicator").slideDown();







jQuery(\'.sp_change_indicator\').html(\'<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0"  width="204" height="16"  id="mymoviename"><param name="movie" value="'. get_bloginfo("wpurl"). '/wp-content/plugins/sp-client-document-manager/image_138464.swf" /><param name="quality" value="high" /><param name="bgcolor" value="#ffffff" /><embed src="'. get_bloginfo("wpurl"). '/wp-content/plugins/sp-client-document-manager/image_138464.swf" quality="high" bgcolor="#ffffff" width="204" height="16" name="mymoviename" align="" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer"></embed></object><br><em>Please wait, your file is currently uploading! </em>\');
document.forms["sp_upload_form"].submit();
return true;

});




});

</script>
<div style="display:none">
	<div  id="sp_cu_add_project">
		<form action="'.$_SERVER['REQUEST_URI'].'" method="post">
		Project Name: <input type="text" name="project-name"  style="width:200px !important"> <input type="submit" value="Add Project" name="add-project">
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
				  <table width="100%" cellpadding="2" cellspacing="2" style="border:none;padding:0px;margin:0px;">
  <tr>
    <td width="90">File name:</td>
    <td><input  type="text" name="dlg-upload-name"></td>
  </tr>
  
  ';
  

  
 if (CU_PREMIUM == 1){ 
 $html .= sp_cdm_display_projects(); 
 $html .= sp_cdm_display_categories(); 
 }
 
 
 
  $html .= '
  <tr>
    <td>File:</td>
    <td>	    <input id="file_upload" name="dlg-upload-file[]" type="file" multiple>
<div id="upload_list"></div>
							</td>
  </tr>
  <tr>
    <td>Notes:</td>
    <td><textarea style="width:90%;height:70px" name="dlg-upload-notes"></textarea></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>
						<div class="sp_change_indicator_button"><input id="dlg-upload" onclick="sp_change_indicator()" type="submit" name="submit" value="Upload" ></div>
						<div class="sp_change_indicator" ></div>	
							</td>
  </tr>
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
	$a['notes'] = addslashes($data['dlg-upload-notes']);
	check_folder_sp_client_upload();
	

	if($files['dlg-upload-file']['name'] != ""){
	
	
	
	$a['file'] = sp_uploadFile($files);
    $wpdb->insert(  "".$wpdb->prefix."sp_cu", $a );
	
	$to = get_option('admin_email');
	$headers .= "From: ".$current_user->user_firstname." ".$current_user->user_lastname." <".$current_user->user_email.">\r\n";
	$message  = "Client uploaded a new document<br><br> Click here view the files: " . get_bloginfo('wpurl') . "/wp-admin/user-edit.php?user_id=".$user_ID."#downloads";
	$subject = 'New file upload from client';
	wp_mail( $to, $subject, $message, $headers, $attachments );
	
		$html .= '<script type="text/javascript">


jQuery(document).ready(function() {
 sp_cu_dialog("#sp_cu_thankyou",400,200);
});
</script>';
}else{
	
	$html .= '<p style="color:red">Please upload a file!</p>';
	
}
}





$r = $wpdb->get_results("SELECT *  FROM ".$wpdb->prefix."sp_cu   where uid = $user_ID  order by date desc", ARRAY_A);




//show uploaded documents
  $html .= '
  
  <script type="text/javascript">
  jQuery(function() {
		
	});
  </script>
  
  
  
    <div style="display:none">
  <div id="cp_cdm_upload_form">
  '.display_sp_upload_form().'
  </div>
  </div>
  


	
	<div >

  <a href="javascript:sp_cu_dialog(\'#cp_cdm_upload_form\',700,600)"><img src="' . get_bloginfo('wpurl') . '/wp-content/plugins/sp-client-document-manager/images/add.png"> Add File</a>  
';



		$html .=display_sp_thumbnails($r );
				
		$html .='</div>';





 
  } else{
	  
	  return '<script type="text/javascript">
<!--
window.location = "'.bloginfo('url').'/login/?redirect_to='.urlencode($_SERVER['REQUEST_URI']).'"
//-->
</script>';
	 
  }
  
return $html;
  
}
}
	add_shortcode( 'sp-client-media-manager', 'display_sp_client_upload' );	
?>