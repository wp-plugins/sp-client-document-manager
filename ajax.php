<?php
require( '../../../wp-load.php' );
	
	global $wpdb;
	
	
	
	$function = $_GET['function'];
	
	
	switch($function){
		
		case"view-file":
		
		$r = $wpdb->get_results("SELECT *  FROM ".$wpdb->prefix."sp_cu   where id = '".$_GET['id']."'  order by date desc", ARRAY_A);
		//print_r($r);
		
		$ext = substr(strrchr($r[0]['file'], '.'), 1);	
					
					if($r[0]['pid'] != 0){
						  $projecter = $wpdb->get_results("SELECT *
	
									 FROM ".$wpdb->prefix."sp_cu_project
									 WHERE id = '".$r[0]['pid']."'
									 ", ARRAY_A);
									 
									 
							$project_title ='Project: '.stripslashes($projecter[0]['name']).'';		 	
					}else{
						$project_title = 'Project: None';
					}
					
					
					if($ext== 'png' or $ext == 'jpg' or $ext = 'jpeg' or $ext = 'gif' ){
					$icon = '<td width="160"><img src="/wp-content/uploads/sp-client-document-manager/'.$user_ID.'/'.$r[0]['file'].'" width="150"></td>';	
					}else{
					$icon = '';		
					}
					
				$html .= '
				<div id="sp_cu_viewfile">
				 <h2> '.stripslashes($r[0]['name']).'</h2>
				<div class="sp_cu_manage">';
				 if (CU_PREMIUM == 1){  
				$html .= sp_cdm_revision_button();
				 }
				
				$html .='<a href="' . get_bloginfo('wpurl') . '/wp-content/plugins/sp-client-document-manager/download.php?uid='.$user_ID.'&file='.$r[0]['file'].'" title="Download" style="margin-right:15px"  ><img src="' . get_bloginfo('wpurl') . '/wp-content/plugins/sp-client-document-manager/images/download.png"> Download File</a> 
	<a href="javascript:sp_cu_confirm(\'#sp_cu_confirm_delete\',200,\'?dlg-delete-file='.$r[0]['id'].'#downloads\');" title="Delete" ><img src="' . get_bloginfo('wpurl') . '/wp-content/plugins/sp-client-document-manager/images/delete.png"> Delete File</a>
	<br> <em>'.date('F jS Y h:i A', strtotime($r[0]['date'])).'</em>
				</div>
				
				<div class="sp_cu_item">
				
				
				  <table width="100%" cellpadding="2" cellspacing="2" style="border:none;padding:0px;margin:0px;">

<tr>

'.$icon .'
<td>
<div class="sp_su_project">
<strong>Project: </strong>'.$project_title .'
</div>
<div class="sp_su_notes">
<strong>Notes:</strong> <em>'.$r[0]['notes'].'</em>
</div>';

 if (CU_PREMIUM == 1){ 
 
 
//$html .='<div class="sp_su_history"><p><strong>Revision History</strong></p>'.sp_cdm_file_history($r[0]['id']).'</div>';
 }
$html .='


</td>
</tr>

  </table></div></div>';
  echo $html;		
		break;
		
		case"file-tree":

		
	

$_REQUEST['dir'] = urldecode($_REQUEST['dir']);
$root = ABSPATH;


	


	if (strpos( $_REQUEST['dir'], 'PID') === false){
	$r_projects = $wpdb->get_results("SELECT ".$wpdb->prefix."sp_cu.name,".$wpdb->prefix."sp_cu.id,".$wpdb->prefix."sp_cu.pid  ,".$wpdb->prefix."sp_cu.uid,".$wpdb->prefix."sp_cu.parent,
											".$wpdb->prefix."sp_cu_project.name AS project_name
										FROM ".$wpdb->prefix."sp_cu   
										LEFT JOIN ".$wpdb->prefix."sp_cu_project  ON ".$wpdb->prefix."sp_cu.pid = ".$wpdb->prefix."sp_cu_project.id
										WHERE ".$wpdb->prefix."sp_cu.uid = '".$_GET['uid']."'  
										AND pid != 0  
										AND parent = 0 
										ORDER by date desc", ARRAY_A);
	
										
	$r = $wpdb->get_results("SELECT *  FROM ".$wpdb->prefix."sp_cu   where uid = '".$_GET['uid']."'  AND pid = 0 order by date desc", ARRAY_A);
	
	}else{
		
		$rel_ex = explode("PID", $_REQUEST['dir']); 
	
		$r = $wpdb->get_results("SELECT *  FROM ".$wpdb->prefix."sp_cu   where pid = '".$rel_ex[1]."' order by date desc", ARRAY_A);	
	
	}
	


	
	echo "<ul class=\"jqueryFileTree\" style=\"display: none;\">";
	
	
	
	
	for($i=0; $i<count($r_projects); $i++){
		
		
		echo "<li class=\"directory collapsed\"><a href=\"#\" rel=\"PID".$r_projects[$i]['pid']."\">" . htmlentities(stripslashes($r_projects[$i]['project_name'])) . "</a></li>";
			
	}
	
	for($i=0; $i<count( $r ); $i++){
		
		$ext = preg_replace('/^.*\./', '', $r[$i]['file']);
		echo "<li class=\"file ext_$ext\"><a href=\"#\" rel=\"".$r[$i]['id']."\">" . htmlentities(stripslashes($r[$i]['name'])) . "</a></li>";
		
	}
	echo "</ul>";	
	
	
	


	
		break;
		case "download-project":
			
			$user_ID = $_GET['id'];
			
				
		$d = $wpdb->get_results("SELECT *  FROM ".$wpdb->prefix."sp_cu   where pid = $user_ID   and parent = 0 order by date desc", ARRAY_A);		
		$r_project = $wpdb->get_results("SELECT *  FROM ".$wpdb->prefix."sp_cu_project where id = $user_ID  ", ARRAY_A);			
			$return_file = "".preg_replace('/[^\w\d_ -]/si', '',stripslashes($r_project[0]['name'])).".zip";
			$zip = new Zip();
			//@unlink($dir.$return_file);
				
				for($i=0; $i<count($d); $i++){
			
			$dir = ''.ABSPATH.'wp-content/uploads/sp-client-document-manager/'.$r[$i]['uid'].'/';	
			$path = '../../../wp-content/uploads/sp-client-document-manager/'.$r[$i]['uid'].'/';	
			
			
					$r = $wpdb->get_results("SELECT *  FROM ".$wpdb->prefix."sp_cu   where id= '".$d[$i]['id']."'  order by date desc", ARRAY_A);
					$r_rev_check = $wpdb->get_results("SELECT *  FROM ".$wpdb->prefix."sp_cu   where parent= '".$r[0]['id']."'  order by date desc", ARRAY_A);
					if(count($r_rev_check) > 0 ){
					
					unset($r);
					$r = $wpdb->get_results("SELECT *  FROM ".$wpdb->prefix."sp_cu   where id= '".$r_rev_check[0]['id']."'  order by date desc", ARRAY_A);
					}
			
			
			
					  $zip->addFile(file_get_contents($dir.$r[$i]['file']), $r[$i]['file'] , filectime($dir.$r[$i]['file']));
					
				}
		
		
$zip->finalize(); // as we are not using getZipData or getZipFile, we need to call finalize ourselves.

$zip->setZipFile($dir.$return_file);
		

		
	header("Location: ".$path.$return_file."");	
		
		break;
		case "download-archive":
		
		
		$user_ID = $_GET['id'];
			$dir = ''.ABSPATH.'wp-content/uploads/sp-client-document-manager/'.$user_ID.'/';	
			$path = '../../../wp-content/uploads/sp-client-document-manager/'.$user_ID.'/';			
			$return_file = "Account.zip";
				$zip = new Zip();
				
		$d = $wpdb->get_results("SELECT *  FROM ".$wpdb->prefix."sp_cu   where uid = $user_ID  and parent = 0 order by date desc", ARRAY_A);		
		
		for($i=0; $i<count($d); $i++){
					
					
					
					$r = $wpdb->get_results("SELECT *  FROM ".$wpdb->prefix."sp_cu   where id= '".$d[$i]['id']."'  order by date desc", ARRAY_A);
					$r_rev_check = $wpdb->get_results("SELECT *  FROM ".$wpdb->prefix."sp_cu   where parent= '".$r[0]['id']."'  order by date desc", ARRAY_A);
					if(count($r_rev_check) > 0 ){
					
					unset($r);
					$r = $wpdb->get_results("SELECT *  FROM ".$wpdb->prefix."sp_cu   where id= '".$r_rev_check[0]['id']."'  order by date desc", ARRAY_A);
					}
					
					
					
					  $zip->addFile(file_get_contents($dir.$r[0]['file']), $r[0]['file'] , filectime($dir.$r[0]['file']));
					
		}
		

		
		
			
			//@unlink($dir.$return_file);
				
	
		
		
$zip->finalize(); // as we are not using getZipData or getZipFile, we need to call finalize ourselves.

$zip->setZipFile($dir.$return_file);
		

		
	header("Location: ".$path.$return_file."");	
		
		break;
	
	case"email-vendor":


	
	if(count($_POST['vendor_email']) == 0){
		
		echo '<p style="color:red;font-weight:bold">Please select at least one file!</p>';
	}else{
	$files = implode(",",$_POST['vendor_email']);
	$r = $wpdb->get_results("SELECT *  FROM ".$wpdb->prefix."sp_cu  WHERE id IN (".$files.")", ARRAY_A);
	
	$message .= '
	'.$_POST['vendor-message'].'<br><br>';
	
	for($i=0; $i<count($r); $i++){
		
					
					if($r[$i]['name'] == ""){
						
						$name = $r[$i]['file'];
					}else{
						
						$name = $r[$i]['name'];
					}
		
		
	$attachment_links .= '<a href="' . get_bloginfo('wpurl') . '/wp-content/uploads/sp-client-document-manager/'.$_POST['user_id'].'/'.$r[$i]['file'].'">'.$name.'</a><br>';
	$attachment_array[$i] = ''.WP_CONTENT_DIR .'/uploads/sp-client-document-manager/'.$_POST['user_id'].'/'.$r[$i]['file'].'';	
	}
	
	$to = $_POST['vendor'];
	
	$headers = array("From: ".get_option('admin_email')."",
	         "Content-Type: text/html"
	         );
	$h = implode("\r\n",$headers) . "\r\n";
	
	
	if($_POST['vendor_attach'] == 3){
		
	$attachments =$attachment_array;
	$message .= $attachment_links;		
	}elseif($_POST['vendor_attach'] == 1){
		
	$attachments =$attachment_array;
	}else{
		
	$message .= $attachment_links;	
	}

	$subject = 'New files from: '.get_option('blogname').'';
	wp_mail( $to, $subject, $message, $h, $attachments );
	
	
	
echo '<p style="color:green;font-weight:bold">Files Sent to '.$_POST['vendor'].'</p>';
	}
	break;	
		
		
		
	}

?>