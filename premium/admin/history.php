<?php

//processing

if($_POST['add-revision'] != ""){
	
	
		$r_data = $wpdb->get_results("SELECT *  FROM ".$wpdb->prefix."sp_cu   where id = '".$_POST['parent']."' ", ARRAY_A);
		
	$data = $_POST;
	$files = $_FILES;
	$a['uid'] = $r_data[0]['uid'];
	$a['name'] = addslashes($r_data[0]['name']);
	$a['pid'] = $r_data[0]['pid'];
	$a['cid'] = $r_data[0]['cid'];
	$a['notes'] = addslashes($data['dlg-upload-notes']);

	

	if($files['dlg-upload-file']['name'] != ""){
	
	
	
	$a['file'] = sp_uploadFile($files);
    $wpdb->insert(  "".$wpdb->prefix."sp_cu", $a );
	
	
	    $update['parent'] = $wpdb->insert_id;
		$where['id'] = $_POST['parent'];
	  $wpdb->update(  "".$wpdb->prefix."sp_cu", $update,$where );
	
	   $update_r['parent'] = $wpdb->insert_id;
		$where_r['parent'] = $_POST['parent'];
	  $wpdb->update(  "".$wpdb->prefix."sp_cu", $update_r,$where_r );
	
	$to = get_option('admin_email');
	$headers .= "From: ".$current_user->user_firstname." ".$current_user->user_lastname." <".$current_user->user_email.">\r\n";
	$message  = "Client uploaded a new document<br><br> Click here view the files: " . get_bloginfo('wpurl') . "/wp-admin/user-edit.php?user_id=".$user_ID."#downloads";
	$subject = 'New file upload from client';
	exit;
}else{
	
	$html .= '<p style="color:red">Please upload a file!</p>';
	
}
	
	
}
//functions
function sp_cdm_revision_button(){
	
		
	/*	
		return '	<a href="javascript:sp_cu_dialog(\'#revision\',550,300)"  title="Add Revision" ><img src="' . get_bloginfo('wpurl') . '/wp-content/plugins/sp-client-document-manager/images/add.png"> Add Revision</a>
		
		<div style="display:none">
		
		<div id="revision">
		<form action="'.$_SERVER['REQUEST_URI'].'" method="post" enctype="multipart/form-data">
	<input type="hidden" name="parent" value="'.$_GET['id'].'">		
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="150">File</td>
    <td><input type="file" name="file"></td>
  </tr>
  <tr>
    <td>Notes:</td>
    <td><textarea name="dlg-upload-file[]" style:width:300px;height:50px"></textarea></td>
  </tr>
    <tr>
    <td></td>
    <td><input type="submit" name="add-revision" value="Add Revision"></td>
  </tr>
</table>
</form>
		</div>
		</div>
		';	
		*/
}


function sp_cdm_file_history($id){
global $wpdb,$user_ID;	
	
		$r = $wpdb->get_results("SELECT *  FROM ".$wpdb->prefix."sp_cu   where parent = '".$id."'  order by date desc", ARRAY_A);
	
	
		if(count( $r ) > 0){
	$content .='<ul>';
		
	
		for($i=0; $i<count( $r ); $i++){
			
			$content .='<li><a href="' . get_bloginfo('wpurl') . '/wp-content/plugins/sp-client-document-manager/download.php?uid='.$user_ID.'&file='.$r[$i]['file'].'">'.$r[$i]['name'].'</a> - '.$r[$i]['notes'].' <em>(rev:'. $i.' on '.date('F jS Y h:i A', strtotime($r[0]['date'])).')<li>';
			
		}
	$content .='</ul>';	
		}else{
		$content = 'No other files in history';	
		}
		//return $content;
}


?>