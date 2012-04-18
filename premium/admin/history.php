<?php


	function sp_cdm_revision_add(){
			global $wpdb ;
	global $user_ID;
	global $current_user;
		
		$r_data = $wpdb->get_results("SELECT *  FROM ".$wpdb->prefix."sp_cu   where id = '".$_POST['parent']."' ", ARRAY_A);
		
	$data = $_POST;
	$files = $_FILES;
	$a['uid'] = $r_data[0]['uid'];
	$a['name'] = addslashes($r_data[0]['name']);
	$a['pid'] = $r_data[0]['pid'];
	$a['cid'] = $r_data[0]['cid'];
	$a['notes'] = addslashes($data['dlg-upload-notes']);
	$a['parent'] = $_POST['parent'];
	

	if($files['dlg-upload-file']['name'] != ""){
	
	
	
	$a['file'] = sp_uploadFile($files,1);
    $wpdb->insert(  "".$wpdb->prefix."sp_cu", $a );
	
	
	   
	
	$to = get_option('admin_email');
	$headers .= "From: ".$current_user->user_firstname." ".$current_user->user_lastname." <".$current_user->user_email.">\r\n";
	$message  = "Client uploaded a new document<br><br> Click here view the files: " . get_bloginfo('wpurl') . "/wp-admin/user-edit.php?user_id=".$user_ID."#downloads";
	$subject = 'New file upload from client';
	
		echo  '<p style="color:red">File uploaded!!</p>';
}else{
	
	echo  '<p style="color:red">Please upload a file!</p>';
	
}
	}
//functions
function sp_cdm_revision_button(){
	
			global $wpdb ;
	global $user_ID;
	global $current_user;

if($_POST['add-revision'] != ""){
	
echo sp_cdm_revision_add();
	
}


		return '	<a href="javascript:sp_cu_dialog(\'#revision\',550,300)"  title="Add Revision" ><img src="' . get_bloginfo('wpurl') . '/wp-content/plugins/sp-client-document-manager/images/add.png"> Add Revision</a>
		
		<div style="display:none">
	
		<script type="text/javascript">
		 
		 function showRequest(){
			 
		 }
		 function showResponse(){
		//	jQuery("#output1").html();
			 jQuery("#view_file_refresh").load("/wp-content/plugins/sp-client-document-manager/ajax.php?function=view-file&id='.$_GET['id'].'");
			jQuery("#revision").dialog("close");
		 }
		  var options = { 
        target:        \'#output1\',   // target element(s) to be updated with server response 
        beforeSubmit:  showRequest,  // pre-submit callback 
        success:       showResponse  // post-submit callback 
        
    }; 
 
    jQuery("#history_form").ajaxForm(options); 
		
		</script>
		
		
		<div id="revision">
		<div id="output1">
		</div>
		<form id="history_form" action="'.$_SERVER['REQUEST_URI'].'" method="post" enctype="multipart/form-data">
	<input type="hidden" name="parent" value="'.$_GET['id'].'">		
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="150">File</td>
    <td><input type="file" name="dlg-upload-file[]"></td>
  </tr>
  <tr>
    <td>Notes:</td>
    <td><textarea name="dlg-upload-notes" style:width:300px;height:50px"></textarea></td>
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
	
}


function sp_cdm_file_history($id){
global $wpdb,$user_ID;	
	
		$r = $wpdb->get_results("SELECT *  FROM ".$wpdb->prefix."sp_cu   where parent = '".$id."'  order by date asc", ARRAY_A);
	$r_cur = $wpdb->get_results("SELECT *  FROM ".$wpdb->prefix."sp_cu   where id = '".$id."'  ", ARRAY_A);
	
		if(count( $r ) > 0){
	$content .='<ul>';
		
		$content .='<li>
			<a href="' . get_bloginfo('wpurl') . '/wp-content/plugins/sp-client-document-manager/download.php?fid='.$r_cur[0]['id'].'&original=1">'.$r_cur[0]['name'].'</a>  <em>(Original added on '.date('F jS Y h:i A', strtotime($r_cur[0]['date'])).')</li>';
		$t= 2;
		for($i=0; $i<count( $r ); $i++){
			$t++;
			
			$rev = $i + 1;
			$content .='<li>
			<a href="' . get_bloginfo('wpurl') . '/wp-content/plugins/sp-client-document-manager/download.php?fid='.$r[$i]['id'].'">'.$r[$i]['name'].'</a> - '.$r[$i]['notes'].' <em>(rev:'. $rev.' on '.date('F jS Y h:i A', strtotime($r[$i]['date'])).')</li>';
			
		}
	$content .='</ul>';	
		}else{
		$content = 'No other files in history';	
		}
		return $content;
}


?>