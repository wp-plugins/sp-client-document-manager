<?php
if (!class_exists('sp_cdm_admin_uploader')){
class sp_cdm_admin_uploader{


function sp_cdm_get_form_fields($fid){
		global $wpdb;
		
		$r = $wpdb->get_results("SELECT  * FROM ".$wpdb->prefix."sp_cu_form_entries 
		
												where file_id = $fid", ARRAY_A);		

		$content .='<p><em>';
		for($i=0; $i<count(	$r); $i++){	  
			$r_field = $wpdb->get_results("SELECT  * FROM ".$wpdb->prefix."sp_cu_forms
		
												where id = ".$r[$i]['fid']." order by sort", ARRAY_A);		
												
		$content .='<strong>'.stripslashes($r_field[0]['name']).':</strong> '.stripslashes($r[$i]['value']).'<br>';
		
		}
		$content  .='</em></p>';
	
	return $content;
	
}
function process_sp_cdm_form_vars($vars,$id){
		global $wpdb,$current_user,$user_ID;
	
	
	if(count($vars) > 0){
	foreach($vars as $key => $value){
		unset($insert);
		$insert['file_id'] = $id;
		$insert['value'] =  $value;
		$insert['fid'] = $key;
		$insert['uid'] = $user_ID;
		$wpdb->insert("".$wpdb->prefix."sp_cu_form_entries", $insert);
		
	}
	}
	
}



function display_sp_upload_form(){
	
global $wpdb,$current_user;

echo '<h2>'.__("Uploader","sp-cdm").'</h2>'.sp_client_upload_nav_menu().''.__("Upload a file below to assign an upload to a user","sp-cdm").'<p><div style="padding:10px;margin:10px;border:1px solid #CCC; border-radius:5px;">';


	
	
if($_POST['submit-admin-upload'] != ""){



	
	$data = $_POST;
	$files = $_FILES;
	$a['uid'] = $_POST['author'];
	$a['name'] = addslashes($data['dlg-upload-name']);
	$a['pid'] = $data['pid'];
	$a['cid'] = $data['cid'];
	$a['notes'] = addslashes($data['dlg-upload-notes']);
	check_folder_sp_client_upload();
	

	if($files['dlg-upload-file']['name'] != ""){
	
	
	
	$a['file'] = sp_uploadFile($files);
    $wpdb->insert(  "".$wpdb->prefix."sp_cu", $a );
	
	 if (CU_PREMIUM == 1){ 
	  
	process_sp_cdm_form_vars($data['custom_forms'],$wpdb->insert_id);
	 
	 }
	
	
		echo  '<script type="text/javascript">


jQuery(document).ready(function() {
 sp_cu_dialog("#sp_cu_thankyou",400,200);
});
</script>';
}else{
	
	 '<p style="color:red">'.__("Please upload a file!","sp-cdm").'</p>';
	
}
}









echo '
<script type="text/javascript">


function sp_cdm_change_indicator(){
	

jQuery(".sp_change_indicator_button").hide();
jQuery(".sp_change_indicator").slideDown();



jQuery(\'.sp_change_indicator\').html(\'<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0"  width="204" height="16"  id="mymoviename"><param name="movie" value="'. get_bloginfo("url"). '/wp-content/plugins/sp-client-document-manager/image_138464.swf" /><param name="quality" value="high" /><param name="bgcolor" value="#ffffff" /><embed src="'. get_bloginfo("url"). '/wp-content/plugins/sp-client-document-manager/image_138464.swf" quality="high" bgcolor="#ffffff" width="204" height="16" name="mymoviename" align="" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer"></embed></object><br><em>Please wait, your file is currently uploading! </em>\');
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
<div style="display:none" >
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




			echo '<div>
			
				  <table width="100%" cellpadding="2" cellspacing="2" style="border:none;padding:0px;margin:0px;">

  <tr>
    <td>'.__("File Under User","sp-cdm").':</td>
    <td>';
	
	wp_dropdown_users(array('name' => 'author'));
	echo ' </td>
  </tr>

  <tr>
    <td>'.__("File Name:","sp-cdm").'</td>
    <td><input  type="text" name="dlg-upload-name" class="required"></td>
  </tr>
  
  ';
  
 echo  sp_cdm_display_projects(); 
  
 if (CU_PREMIUM == 1){ 

 echo  sp_cdm_display_categories(); 
 }
 

 
  echo  '
  <tr>
    <td>'.__("File:","sp-cdm").'</td>
    <td>	    <input id="file_upload" name="dlg-upload-file[]" type="file" class="required" multiple>
<div id="upload_list"></div>
							</td>
  </tr>';
  
    if (CU_PREMIUM == 1){ 
  
  echo display_sp_cdm_form();
  
  }else{
	  
  echo '<tr>
    <td>'.__("Notes:","sp-cdm").'</td>
    <td><textarea style="width:90%;height:70px" name="dlg-upload-notes"></textarea></td>
  </tr>
  ';
  }
  echo '
  <tr>
    <td>&nbsp;</td>
    <td>
						<div class="sp_change_indicator_button"><input id="dlg-upload" onclick="sp_change_indicator()" type="submit" name="submit-admin-upload" value="Upload" ></div>
						<div class="sp_change_indicator" ></div>	
							</td>
  </tr>';

  
  echo '
</table></div>';




echo '

	</form>
	
	</div>
	';
	

}






}



}
?>