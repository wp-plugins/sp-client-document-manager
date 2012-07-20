<?php
require( '../../../wp-load.php' );
	
	global $wpdb;
$upload_dir = wp_upload_dir();	
	
	
	$function = $_GET['function'];
	
	
	switch($function){
		
		case"view-file":
		if(get_option('sp_cu_wp_folder') == ''){
	$wp_con_folder = '/';	
	}else{
		$wp_con_folder = get_option('sp_cu_wp_folder') ;
	}
		
		

		
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
						$project_title = ''.__("Project: None","sp-cdm").'';
					}
					
					
					if($ext== 'png' or $ext == 'jpg' or $ext = 'jpeg' or $ext = 'gif' ){
					$icon = '<td width="160"><img src="'.content_url().'/uploads/sp-client-document-manager/'.$r[0]['uid'].'/'.$r[0]['file'].'" width="150"></td>';	
					}else{
					$icon = '';		
					}
					
					
		$ext = preg_replace('/^.*\./', '', $r[0]['file']);
		
		$images_arr = array("jpg","png","jpeg", "gif", "bmp");
		
		if(in_array(strtolower($ext), $images_arr)){
			$img = '<img src="'.content_url().'/plugins/sp-client-document-manager/classes/thumb.php?src=/wp-content/uploads/sp-client-document-manager/'.$r[0]['uid'].'/'.$r[0]['file'].'&w=250&h=250">';
		
		}elseif($ext == 'xls' or $ext == 'xlsx'){
			$img = '<img src="'.content_url().'/plugins/sp-client-document-manager/images/microsoft_office_excel.png">';
		}elseif($ext == 'doc' or $ext == 'docx'){
			$img = '<img src="'.content_url().'/plugins/sp-client-document-manager/images/microsoft_office_word.png">';	
		}elseif($ext == 'pub' or $ext == 'pubx'){
			$img = '<img src="'.content_url().'/plugins/sp-client-document-manager/images/microsoft_office_publisher.png">';		
		}elseif($ext == 'ppt' or $ext == 'pptx'){
			$img = '<img src="'.content_url().'/plugins/sp-client-document-manager/images/microsoft_office_powerpoint.png">';
		}elseif($ext == 'adb' or $ext == 'accdb'){
			$img = '<img src="'.content_url().'/plugins/sp-client-document-manager/images/microsoft_office_access.png">';	
		}elseif(($ext == 'pdf' or $ext == 'psd' or $ext == 'html' or $ext == 'eps') && get_option('sp_cu_user_projects_thumbs_pdf') == 1){
			if(file_exists(''.ABSPATH.'wp-content/uploads/sp-client-document-manager/'.$r[0]['uid'].'/'.$r[0]['file'].'_big.png')){
			$img = '<img src="'.content_url().'/uploads/sp-client-document-manager/'.$r[0]['uid'].'/'.$r[0]['file'].'_big.png" width="250">';		
			}else{
				$img = '<img src="'.content_url().'/plugins/sp-client-document-manager/images/adobe.png">';	
			}
		}elseif($ext == 'pdf' ){
			$img = '<img src="'.content_url().'/plugins/sp-client-document-manager/images/adobe.png">';	
			
		}else{
			$img = '<img src="'.content_url().'/plugins/sp-client-document-manager/images/package_labled.png">';
		}
					
					
				$html .= '
				<div id="view_file_refresh">
				<div id="sp_cu_viewfile">
				 <h2> '.stripslashes($r[0]['name']).'</h2>
				<div class="sp_cu_manage">';
				 if (CU_PREMIUM == 1){  
				$html .= sp_cdm_revision_button();
				 }
				
				
				if(get_option('sp_cu_js_redirect') == 1){
				$target = 'target="_blank"';	
				}else{
				$target = ' ';	
				}
				$html .='<a '.$target.' href="' . get_bloginfo('wpurl') . '/wp-content/plugins/sp-client-document-manager/download.php?fid='.$r[0]['id'].'" title="Download" style="margin-right:15px"  ><img src="' . get_bloginfo('wpurl') . '/wp-content/plugins/sp-client-document-manager/images/download.png"> '.__("Download File","sp-cdm").'</a> 
	<a href="javascript:sp_cu_confirm(\'#sp_cu_confirm_delete\',200,\'?dlg-delete-file='.$r[0]['id'].'#downloads\');" title="Delete" ><img src="' . get_bloginfo('wpurl') . '/wp-content/plugins/sp-client-document-manager/images/delete.png">'.__("Delete File","sp-cdm").'</a>
	<br> <em>'.date('F jS Y h:i A', strtotime($r[0]['date'])).'</em>
				</div>
				
				<div class="sp_cu_item">
				
				
				  <table width="100%" cellpadding="2" cellspacing="2" style="border:none;padding:0px;margin:0px;">

<tr>
<td width="200" >
<a '.$target.' href="' . get_bloginfo('wpurl') . '/wp-content/plugins/sp-client-document-manager/download.php?fid='.$r[0]['id'].'" title="Download" style="margin-right:15px"  >
'.$img  .'
</a>
</td>
<td>
<div class="sp_su_project">
<strong>'.__("Project: ","sp-cdm").' </strong>'.$project_title .'
</div>
<div class="sp_su_project">
<strong>'.__("File Type: ","sp-cdm").' </strong>'.$ext .'
</div>';

 if (CU_PREMIUM == 1){ 

$html .='
<div class="sp_su_notes">
'.sp_cdm_get_form_fields($r[0]['id']).'
</div>';	 
	 
 }else{
	 if($r[0]['notes'] != ""){
$html .='
<div class="sp_su_notes">
<strong>'.__("Notes: ","sp-cdm").':</strong> <em>'.$r[0]['notes'].'</em>
</div>';
}
 }
 if (CU_PREMIUM == 1){ 
 
 if(sp_cdm_file_history_exists($r[0]['id']) == true){
$html .='<div class="sp_su_history"><p><strong>'.__("Revision History","sp-cdm").'</strong></p>'.sp_cdm_file_history($r[0]['id']).'</div>';
 }
 }
$html .='


</td>
</tr>

  </table></div></div></div>';
  echo $html;		
		break;
		
		
		
		case "thumbnails":
		
			 if (CU_PREMIUM == 1){  	
		$find_groups = cdmFindGroups($_GET['uid']);
			 }
		
	

		$r_projects = $wpdb->get_results("SELECT ".$wpdb->prefix."sp_cu.name,".$wpdb->prefix."sp_cu.id,".$wpdb->prefix."sp_cu.pid  ,".$wpdb->prefix."sp_cu.uid,
											".$wpdb->prefix."sp_cu_project.name AS project_name
										FROM ".$wpdb->prefix."sp_cu   
										LEFT JOIN ".$wpdb->prefix."sp_cu_project  ON ".$wpdb->prefix."sp_cu.pid = ".$wpdb->prefix."sp_cu_project.id
										WHERE (".$wpdb->prefix."sp_cu.uid = '".$_GET['uid']."'  ".$find_groups .")
										AND pid != 0
										AND parent = 0 
										GROUP BY pid
										ORDER by date desc", ARRAY_A);
										
										
		echo '<div id="dlg_cdm_thumbnails">';
		
		
		if($_GET['pid'] == ""){
		
		
		for($i=0; $i<count($r_projects); $i++){
		
		
		echo '<div class="dlg_cdm_thumbnail_folder">
				<a href="javascript:sp_cdm_load_project('.$r_projects[$i]['pid'].')"><img src="'.content_url().'/plugins/sp-client-document-manager/images/my_projects_folder.png">
				<div class="dlg_cdm_thumb_title">
				'.htmlentities(stripslashes($r_projects[$i]['project_name'])).'
				</div>
				</a>
				</div>
		
		';
			
	}
		}else{
		
		echo '<div class="dlg_cdm_thumbnail_folder">
				<a href="javascript:sp_cdm_load_file_manager()"><img src="'.content_url().'/plugins/sp-client-document-manager/images/my_projects_folder.png">
				<div class="dlg_cdm_thumb_title">
			<< Go Back
				</div>
				</a>
				</div>
		
		';	
			
		}
	
	
	if($_GET['pid'] == ""){
	$r = $wpdb->get_results("SELECT *  FROM ".$wpdb->prefix."sp_cu   where (uid = '".$_GET['uid']."' ".$find_groups.")  AND pid = 0 	AND parent = 0  order by date desc", ARRAY_A);
	}else{
	$r = $wpdb->get_results("SELECT *  FROM ".$wpdb->prefix."sp_cu   where pid = '".$_GET['pid']."' AND parent = 0  order by date desc", ARRAY_A);		
	}
	
	for($i=0; $i<count( $r ); $i++){
		
		
		
		$ext = preg_replace('/^.*\./', '', $r[$i]['file']);
		
		$images_arr = array("jpg","png","jpeg", "gif", "bmp");
		
		if(in_array(strtolower($ext), $images_arr)){
			$img = '<img src="'.content_url().'/plugins/sp-client-document-manager/classes/thumb.php?src=/wp-content/uploads/sp-client-document-manager/'.$r[$i]['uid'].'/'.$r[$i]['file'].'&w=80&h=80">';
		
		}elseif($ext == 'xls' or $ext == 'xlsx'){
			$img = '<img src="'.content_url().'/plugins/sp-client-document-manager/images/microsoft_office_excel.png">';
		}elseif($ext == 'doc' or $ext == 'docx'){
			$img = '<img src="'.content_url().'/plugins/sp-client-document-manager/images/microsoft_office_word.png">';	
		}elseif($ext == 'pub' or $ext == 'pubx'){
			$img = '<img src="'.content_url().'/plugins/sp-client-document-manager/images/microsoft_office_publisher.png">';		
		}elseif($ext == 'ppt' or $ext == 'pptx'){
			$img = '<img src="'.content_url().'/plugins/sp-client-document-manager/images/microsoft_office_powerpoint.png">';
		}elseif($ext == 'adb' or $ext == 'accdb'){
			$img = '<img src="'.content_url().'/plugins/sp-client-document-manager/images/microsoft_office_access.png">';	
			}elseif(($ext == 'pdf' or $ext == 'psd' or $ext == 'html' or $ext == 'eps') && get_option('sp_cu_user_projects_thumbs_pdf') == 1){
			if(file_exists(''.ABSPATH.'wp-content/uploads/sp-client-document-manager/'.$r[$i]['uid'].'/'.$r[$i]['file'].'_small.png')){			
			$img = '<img src="'.content_url().'/uploads/sp-client-document-manager/'.$r[$i]['uid'].'/'.$r[$i]['file'].'_small.png">';	
			}else{
			$img = '<img src="'.content_url().'/plugins/sp-client-document-manager/images/adobe.png">';		
			}
		}elseif($ext == 'pdf' ){
			$img = '<img src="'.content_url().'/plugins/sp-client-document-manager/images/adobe.png">';	
		
		}else{
			$img = '<img src="'.content_url().'/plugins/sp-client-document-manager/images/package_labled.png">';
		}
		
		echo '<div class="dlg_cdm_thumbnail_folder">
			<div class="dlg_cdm_thumbnail_image">
				<a href="javascript:sp_cdm_showFile('.$r[$i]['id'].')">'.$img .'
				<div class="dlg_cdm_thumb_title">
				'. htmlentities(stripslashes($r[$i]['name'])).'
				</div>
				</a>
				</div>
				</div>
		
		';
		
		
	
		
	}
	
		
		$content .='<div style="clear:both"></div></div>';
		
		break;
		case"file-tree":

		
	

$_REQUEST['dir'] = urldecode($_REQUEST['dir']);
$root = ABSPATH;


	 if (CU_PREMIUM == 1){  	
		$find_groups = cdmFindGroups($_GET['uid']);
	
			 }


	if (strpos( $_REQUEST['dir'], 'PID') === false){
	$r_projects = $wpdb->get_results("SELECT ".$wpdb->prefix."sp_cu.name,".$wpdb->prefix."sp_cu.id,".$wpdb->prefix."sp_cu.pid  ,".$wpdb->prefix."sp_cu.uid,
											".$wpdb->prefix."sp_cu_project.name AS project_name
										FROM ".$wpdb->prefix."sp_cu   
										LEFT JOIN ".$wpdb->prefix."sp_cu_project  ON ".$wpdb->prefix."sp_cu.pid = ".$wpdb->prefix."sp_cu_project.id
										WHERE (".$wpdb->prefix."sp_cu.uid = '".$_GET['uid']."'   ".$find_groups." )
										AND pid != 0
										AND parent = 0 
										GROUP BY pid
										ORDER by date desc", ARRAY_A);

									
	$r = $wpdb->get_results("SELECT *  FROM ".$wpdb->prefix."sp_cu   where (uid = '".$_GET['uid']."' ".$find_groups.") AND pid = 0 	AND parent = 0  order by date desc", ARRAY_A);
	
	}else{
		
		$rel_ex = explode("PID", $_REQUEST['dir']); 
	
		$r = $wpdb->get_results("SELECT *  FROM ".$wpdb->prefix."sp_cu   where pid = '".$rel_ex[1]."' AND parent = 0  order by date desc", ARRAY_A);	
	
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
			
				
		$r = $wpdb->get_results("SELECT *  FROM ".$wpdb->prefix."sp_cu   where pid = $user_ID  order by date desc", ARRAY_A);		
		$r_project = $wpdb->get_results("SELECT *  FROM ".$wpdb->prefix."sp_cu_project where id = $user_ID  ", ARRAY_A);			
			$return_file = "".preg_replace('/[^\w\d_ -]/si', '',stripslashes($r_project[0]['name'])).".zip";
			$zip = new Zip();
			
			
				$dir = ''.ABSPATH.'wp-content/uploads/sp-client-document-manager/'.$r_project[0]['uid'].'/';	
			$path = '../../../wp-content/uploads/sp-client-document-manager/'.$r_project[0]['uid'].'/';	
			//@unlink($dir.$return_file);
				
				for($i=0; $i<count($r); $i++){
			
	
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
				
		$r = $wpdb->get_results("SELECT *  FROM ".$wpdb->prefix."sp_cu   where uid = $user_ID  order by date desc", ARRAY_A);		
			
			//@unlink($dir.$return_file);
				
				for($i=0; $i<count($r); $i++){
			
		
					  $zip->addFile(file_get_contents($dir.$r[$i]['file']), $r[$i]['file'] , filectime($dir.$r[$i]['file']));
					
				}
		
		
$zip->finalize(); // as we are not using getZipData or getZipFile, we need to call finalize ourselves.

$zip->setZipFile($dir.$return_file);
		

		
	header("Location: ".$path.$return_file."");	
		
		break;
	
	case"email-vendor":


	
	if(count($_POST['vendor_email']) == 0){
		
		echo '<p style="color:red;font-weight:bold">'.__("Please select at least one file!","sp-cdm").'</p>';
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
		
		
	$attachment_links .= '<a href="' . get_bloginfo('wpurl') . '/wp-content/uploads/sp-client-document-manager/'.$r[$i]['uid'].'/'.$r[$i]['file'].'">'.$name.'</a><br>';
	$attachment_array[$i] = ''.WP_CONTENT_DIR .'/uploads/sp-client-document-manager/'.$r[$i]['uid'].'/'.$r[$i]['file'].'';	
	}
	
	$to = $_POST['vendor'];
	
	$headers = array('From: "'.get_option('sp_cu_company_name').'" <'.get_option('admin_email').'>',
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

	$subject = ''.__("New files from:","sp-cdm").' '.get_option('sp_cu_company_name').'';
	wp_mail( $to, $subject, $message, $h, $attachments );
	
	
	
echo '<p style="color:green;font-weight:bold">'.__("Files Sent to","sp-cdm").' '.$_POST['vendor'].'</p>';
	}
	break;	
		
		
		
	}

?>