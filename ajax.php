<?php
require( '../../../wp-load.php' );
	
	global $wpdb;
$upload_dir = wp_upload_dir();	
	
	
	$function = $_GET['function'];
	
	
	switch($function){
			case "remove-category":
		
		
		$wpdb->query("DELETE FROM ".$wpdb->prefix ."sp_cu_project WHERE id = ".$_REQUEST['id']."	");		
	$wpdb->query("DELETE FROM ".$wpdb->prefix ."sp_cut WHERE pid = ".$_REQUEST['id']."	");	
	
		break;	
		
		case "save-category":
		
		
		
			$insert['name'] = $_POST['name'];
			

		
		if($_POST['id'] != ""){
		$where['id'] =$_POST['id'] ;
		
	    $wpdb->update(  "".$wpdb->prefix . "sp_cu_project", $insert , $where );	
		echo ''.__("Updated Category Name","sp-cdm").': '.$insert['name'].'';
		exit;
		}else{
		$insert['uid'] = $_POST['uid'];
	
		$wpdb->insert( "".$wpdb->prefix . "sp_cu_project",$insert );
		echo $wpdb->insert_id;
		exit;
		}
	echo 'Error!';
	print_r($_POST);
		break;
		
		
		
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
			$img = '<img src="'.content_url().'/plugins/sp-client-document-manager/classes/thumb.php?src='.content_url().'/uploads/sp-client-document-manager/'.$r[0]['uid'].'/'.$r[0]['file'].'&w=250&h=250">';
		
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
				
				if(class_exists('cdmProductivityUser')){
			  $html .= '<span id="cdm_comment_button_holder">'.$cdm_comments->button().'</span>';
				 }
				
				
				
				if(get_option('sp_cu_js_redirect') == 1){
				$target = 'target="_blank"';	
				}else{
				$target = ' ';	
				}
				$html .='<a '.$target.' href="' . get_bloginfo('wpurl') . '/wp-content/plugins/sp-client-document-manager/download.php?fid='.$r[0]['id'].'" title="Download" style="margin-right:15px"  ><img src="' . get_bloginfo('wpurl') . '/wp-content/plugins/sp-client-document-manager/images/download.png"> '.__("Download File","sp-cdm").'</a> ';
				
				
		
		

			
			if(($current_user->ID == $r[0]['uid']) or (cdmFindLockedGroup($current_user->ID , $r[0]['uid']) == true)){
				
				$html .='
	<a href="javascript:sp_cu_confirm(\'#sp_cu_confirm_delete\',200,\'?dlg-delete-file='.$r[0]['id'].'#downloads\');" title="Delete" ><img src="' . get_bloginfo('wpurl') . '/wp-content/plugins/sp-client-document-manager/images/delete.png">'.__("Delete File","sp-cdm").'</a>';
			}
	$html .='
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
<strong>'.__("Project ","sp-cdm").': </strong>'.$project_title .'
</div>
<div class="sp_su_project">
<strong>'.__("File Type ","sp-cdm").': </strong>'.$ext .'
</div>';
if($r[0]['tags'] != ""){
	
	$html .='
<div class="sp_su_notes">
<strong>'.__("Tags ","sp-cdm").': </strong> '.stripslashes($r[0]['tags']).'
</div>';	 
	 
}
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
 
 if(class_exists('cdmProductivityUser')){
  $html .= '<div id="cdm_comments_container">'.$cdm_comments->view($r[0]['id']).'</div>';
 }
$html .='


</td>
</tr>

  </table></div></div></div>';
  echo $html;		
		break;
		
		case "file-list":
		
			 if (CU_PREMIUM == 1){  	
		$find_groups = cdmFindGroups($_GET['uid']);
			 }
		
	
if($_REQUEST['search'] != ""){


$search_project .= " AND ".$wpdb->prefix."sp_cu_project.name LIKE '%".$_REQUEST['search']."%' ";	
$search_file .= " AND (name LIKE '%".$_REQUEST['search']."%' or  tags LIKE '%".$_REQUEST['search']."%')  ";		
}
		
	
		
		$r_projects = $wpdb->get_results("SELECT ".$wpdb->prefix."sp_cu.name,
												 ".$wpdb->prefix."sp_cu.id,
												 ".$wpdb->prefix."sp_cu.pid ,
												 ".$wpdb->prefix."sp_cu.uid,
												 ".$wpdb->prefix."sp_cu.parent,
												 ".$wpdb->prefix."sp_cu_project.name AS project_name
												
												 
										FROM ".$wpdb->prefix."sp_cu   
										LEFT JOIN ".$wpdb->prefix."sp_cu_project  ON ".$wpdb->prefix."sp_cu.pid = ".$wpdb->prefix."sp_cu_project.id
										WHERE (".$wpdb->prefix."sp_cu.uid = '".$_GET['uid']."'  ".$find_groups .")
										AND pid != 0
										AND  ".$wpdb->prefix."sp_cu.parent = 0 
										".$sub_projects."
										".$search_project."
										GROUP BY pid
										ORDER by date desc", ARRAY_A);
										
					
								
										
		echo '<div id="dlg_cdm_file_list">
		<table border="0" cellpadding="0" cellspacing="0">
		<thead>';
		if($_GET['pid'] == ''){
			
			$jscriptpid = "''";	
		}else{
			$jscriptpid = "'".$_GET['pid']."'";	
		}
		echo '<tr>
		<th></th>
		<th class="cdm_file_info" style="text-align:left"><a href="javascript:sp_cdm_sort(\'name\','.$jscriptpid.')">Name</a></th>
		<th class="cdm_file_date"><a href="javascript:sp_cdm_sort(\'date\','.$jscriptpid.')">Date</a></th>
	
		<th class="cdm_file_type">Type</th>	
		</tr>	
		
		';
		if($_GET['pid'] != "" && get_option('sp_cu_user_projects') == 1 ){	
			$r_project_info = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."sp_cu_project where id = ".$_GET['pid']."", ARRAY_A);
	
		echo '<tr>
	
		<th colspan="4" style="text-align:right">
		<div style="padding-right:10px">
		<a href="javascript:sp_cu_dialog(\'#edit_category\',550,130)"><img src="'.content_url().'/plugins/sp-client-document-manager/images/application_edit.png"> Edit Project Name</a>   <a href="javascript:sp_cu_remove_project()" style="margin-left:20px"> <img src="'.content_url().'/plugins/sp-client-document-manager/images/delete_small.png"> Remove Project</a>
		
		<div style="display:none">	
		
		
		<script type="text/javascript">
		
		
function sp_cu_edit_project(){
	
	jQuery.ajax({
   type: "POST",
   url: "'.content_url().'/plugins/sp-client-document-manager/ajax.php?function=save-category",
   data: "name=" + jQuery("#edit_project_name").val() + "&id=" +  jQuery("#edit_project_id").val(),
   success: function(msg){
   jQuery("#cmd_file_thumbs").load("'.content_url().'/plugins/sp-client-document-manager/ajax.php?function=file-list&uid='.$_GET['uid'].'&pid='.$_GET['pid'].'");
   jQuery("#edit_category").dialog("close");
   alert(msg);	
  
   }
 });
}

function sp_cu_remove_project(){
	
	jQuery( "#delete_category" ).dialog({
			resizable: false,
			height:240,
			width:440,
			modal: true,
			buttons: {
				"Delete all items": function() {
						
							
						jQuery.ajax({
					   type: "POST",
					   url: "'.content_url().'/plugins/sp-client-document-manager/ajax.php?function=remove-category",
					   data: "id='.$_GET['pid'].'" ,
					   success: function(msg){
					   jQuery("#cmd_file_thumbs").load("'.content_url().'/plugins/sp-client-document-manager/ajax.php?function=file-list&uid='.$_GET['uid'].'");
					 
					 
					  
					   }
					 });
					 
					jQuery( this ).dialog( "close" );	
						
				},
				Cancel: function() {
					jQuery( this ).dialog( "close" );
				}
			}
		});
	
	
	

	
}

		</script>	
		<div id="delete_category" title="'.__("Delete Category?","sp-cdm").'">
	<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>'.__("Are you sure you would like to delete this category? Doing so will remove all files related to this category.","sp-cdm").'</p>
		</div>

		
		
				<div id="edit_category">			
			
			<input type="hidden"  name="edit_project_id" id="edit_project_id" value="'.$_GET['pid'].'">		
			'.__("Project Name:","sp-cdm").' <input value="'.stripslashes($r_project_info[0]['name']).'" id="edit_project_name" type="text" name="name"  style="width:200px !important"> 
			<input type="submit" value="'.__("Save Project","sp-cdm").'" onclick="sp_cu_edit_project()">
			
			</div>
			
		
		
		</div>
		
		
		</th>
		
		</tr>	
		
		';		
		}
		echo'</thead><tbody>';
		if($_GET['pid'] == ""){
		
		
		for($i=0; $i<count($r_projects); $i++){
		
		
		echo '<tr onclick="sp_cdm_load_project('.$r_projects[$i]['pid'].')">
		<td class="cdm_file_icon ext_directory"></td>
		<td class="cdm_file_info">'.stripslashes($r_projects[$i]['project_name']).'</td>
		<td class="cdm_file_date">&nbsp;</td>
		
		<td class="cdm_file_type">Folder</td>	
		</tr>	
		';
			
	}
		}else{
		
		echo '<tr onclick="sp_cdm_load_file_manager();" >
	<td class="cdm_file_icon ext_directory"></td>
		<td class="cdm_file_info">&laquo; Go Back</td>
		<td class="cdm_file_date">&nbsp;</td>
		
		<td class="cdm_file_type"></td>
	</tr>	
		';	
			
		}
	
	
	
	if($_GET['sort'] == ''){
	$sort = 'date';	
	}else{
	$sort = $_GET['sort'];		
	}
	
	if($_GET['pid'] == ""){
	$r = $wpdb->get_results("SELECT *  FROM ".$wpdb->prefix."sp_cu   where (uid = '".$_GET['uid']."' ".$find_groups.")  AND pid = 0 	AND parent = 0  ".$search_file." order by ".$sort ." ", ARRAY_A);
	}else{
	$r = $wpdb->get_results("SELECT *  FROM ".$wpdb->prefix."sp_cu   where pid = '".$_GET['pid']."' AND parent = 0   ".$search_file."  order by ".$sort ."  ", ARRAY_A);		
	}
	
	for($i=0; $i<count( $r ); $i++){
		
		
		
		$ext = preg_replace('/^.*\./', '', $r[$i]['file']);
		

		$r_cat = $wpdb->get_results("SELECT name  FROM ".$wpdb->prefix."sp_cu_cats   where id = '".$r[$i]['cid']."' ", ARRAY_A);		
		
		if($r_cat[0]['name'] == ''){
			$cat = stripslashes($r_cat[0]['name']);
		}else{
			$cat = '';
		}
		echo '<tr onclick="sp_cdm_showFile('.$r[$i]['id'].')">
				<td class="cdm_file_icon ext_'.$ext.'"></td>
		<td class="cdm_file_info">'.stripslashes($r[$i]['name']).'</td>
		<td class="cdm_file_date">'.date("F Y g:i A",strtotime($r[$i]['date'])).'</td>

		<td class="cdm_file_type">'.$ext.'</td>	
		</tr>	
		';
		
		
	
		
	}
	
		
		$content .='</tbody></table><div style="clear:both"></div></div>';
		
		break;
		
		case "thumbnails":
		
			 if (CU_PREMIUM == 1){  	
		$find_groups = cdmFindGroups($_GET['uid']);
			 }
		
	
if($_REQUEST['search'] != ""){


$search_project .= " AND ".$wpdb->prefix."sp_cu_project.name LIKE '%".$_REQUEST['search']."%' ";	
$search_file .= " AND (name LIKE '%".$_REQUEST['search']."%' or  tags LIKE '%".$_REQUEST['search']."%')  ";		
}

		$r_projects = $wpdb->get_results("SELECT ".$wpdb->prefix."sp_cu.name,
												 ".$wpdb->prefix."sp_cu.id,
												 ".$wpdb->prefix."sp_cu.pid ,
												 ".$wpdb->prefix."sp_cu.uid,
												 ".$wpdb->prefix."sp_cu.parent,
												 ".$wpdb->prefix."sp_cu_project.name AS project_name
												 
										FROM ".$wpdb->prefix."sp_cu   
										LEFT JOIN ".$wpdb->prefix."sp_cu_project  ON ".$wpdb->prefix."sp_cu.pid = ".$wpdb->prefix."sp_cu_project.id
										WHERE (".$wpdb->prefix."sp_cu.uid = '".$_GET['uid']."'  ".$find_groups .")
										AND pid != 0
										AND  ".$wpdb->prefix."sp_cu.parent = 0 
										".$sub_projects."
										".$search_project."
										GROUP BY pid
										ORDER by date desc", ARRAY_A);
										
										
										
		echo '<div id="dlg_cdm_thumbnails">';
		
		
		if($_GET['pid'] != ""){
		$r_current_project = $wpdb->get_results("SELECT *  FROM ".$wpdb->prefix."sp_cu_project  WHERE id = ".$_GET['pid']."", ARRAY_A);
		
		}
		
		
		
		
		
		if((($_GET['pid'] != "" && get_option('sp_cu_user_projects') == 1) && ($_GET['uid'] == $r_current_project[0]['uid'])) or (cdmFindLockedGroup($current_user->ID ,$r_current_project[0]['uid']) == true) ){	
		$r_project_info = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."sp_cu_project where id = ".$_GET['pid']."", ARRAY_A);
	
		echo '
		<div style="padding-right:10px">
		<a href="javascript:sp_cu_dialog(\'#edit_category\',550,130)"><img src="'.content_url().'/plugins/sp-client-document-manager/images/application_edit.png"> Edit Project Name</a>   <a href="javascript:sp_cu_remove_project()" style="margin-left:20px"> <img src="'.content_url().'/plugins/sp-client-document-manager/images/delete_small.png"> Remove Project</a>
		
		<div style="display:none">	
		
		
		<script type="text/javascript">
		
		
function sp_cu_edit_project(){
	
	jQuery.ajax({
   type: "POST",
   url: "'.content_url().'/plugins/sp-client-document-manager/ajax.php?function=save-category",
   data: "name=" + jQuery("#edit_project_name").val() + "&id=" +  jQuery("#edit_project_id").val(),
   success: function(msg){
   jQuery("#cmd_file_thumbs").load("'.content_url().'/plugins/sp-client-document-manager/ajax.php?function=file-list&uid='.$_GET['uid'].'&pid='.$_GET['pid'].'");
   jQuery("#edit_category").dialog("close");
   alert(msg);	
  
   }
 });
}

function sp_cu_remove_project(){
	
	jQuery( "#delete_category" ).dialog({
			resizable: false,
			height:240,
			width:440,
			modal: true,
			buttons: {
				"Delete all items": function() {
						
							
						jQuery.ajax({
					   type: "POST",
					   url: "'.content_url().'/plugins/sp-client-document-manager/ajax.php?function=remove-category",
					   data: "id='.$_GET['pid'].'" ,
					   success: function(msg){
					   jQuery("#cmd_file_thumbs").load("'.content_url().'/plugins/sp-client-document-manager/ajax.php?function=file-list&uid='.$_GET['uid'].'");
					 
					 
					  
					   }
					 });
					 
					jQuery( this ).dialog( "close" );	
						
				},
				Cancel: function() {
					jQuery( this ).dialog( "close" );
				}
			}
		});
	
	
	

	
}

		</script>	
		<div id="delete_category" title="'.__("Delete Category?","sp-cdm").'">
	<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>'.__("Are you sure you would like to delete this category? Doing so will remove all files related to this category.","sp-cdm").'</p>
		</div>

		
		
			<div id="edit_category">			
			
			<input type="hidden"  name="edit_project_id" id="edit_project_id" value="'.$_GET['pid'].'">		
			'.__("Project Name:","sp-cdm").' <input value="'.stripslashes($r_project_info[0]['name']).'" id="edit_project_name" type="text" name="name"  style="width:200px !important"> 
			<input type="submit" value="'.__("Save Project","sp-cdm").'" onclick="sp_cu_edit_project()">
			
			</div>
			
		
		
		</div>
		
	
		
		</div>	
		
		';		
		}
		
		
		
		
		if(count($r_projects) > 0){
		
		
		for($i=0; $i<count($r_projects); $i++){
		
		
		echo '<div class="dlg_cdm_thumbnail_folder">
				<a href="javascript:sp_cdm_load_project('.$r_projects[$i]['pid'].')"><img src="'.content_url().'/plugins/sp-client-document-manager/images/my_projects_folder.png">
				<div class="dlg_cdm_thumb_title">
				'.stripslashes($r_projects[$i]['project_name']).'
				</div>
				</a>
				</div>
		
		';
			
	}
		}else{
		if($_GET['pid'] != ""){
		echo '<div class="dlg_cdm_thumbnail_folder">
				<a href="javascript:sp_cdm_load_file_manager()"><img src="'.content_url().'/plugins/sp-client-document-manager/images/my_projects_folder.png">
				<div class="dlg_cdm_thumb_title">
			<< Go Back
				</div>
				</a>
				</div>
		
		';	
		}
		}
	
	
	if($_GET['pid'] == ""){
	$r = $wpdb->get_results("SELECT *  FROM ".$wpdb->prefix."sp_cu   where (uid = '".$_GET['uid']."' ".$find_groups.")  AND pid = 0 	AND parent = 0  ".$search_file." order by date desc", ARRAY_A);
	}else{
	$r = $wpdb->get_results("SELECT *  FROM ".$wpdb->prefix."sp_cu   where pid = '".$_GET['pid']."' AND parent = 0   ".$search_file."  order by date desc", ARRAY_A);		
	}
	
	for($i=0; $i<count( $r ); $i++){
		
		
		
		$ext = preg_replace('/^.*\./', '', $r[$i]['file']);
		
		$images_arr = array("jpg","png","jpeg", "gif", "bmp");
		
		if(in_array(strtolower($ext), $images_arr)){
			$img = '<img src="'.content_url().'/plugins/sp-client-document-manager/classes/thumb.php?src='.content_url().'/uploads/sp-client-document-manager/'.$r[$i]['uid'].'/'.$r[$i]['file'].'&w=80&h=80">';
		
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
				'. stripslashes($r[$i]['name']).'
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



if($_REQUEST['search'] != ""){


$search_project .= " AND ".$wpdb->prefix."sp_cu_project.name LIKE '%".$_REQUEST['search']."%' ";	
$search_file .= " AND ".$wpdb->prefix."sp_cu.name LIKE '%".$_REQUEST['search']."%' ";		
}

	if (strpos( $_REQUEST['dir'], 'PID') === false){
	$r_projects = $wpdb->get_results("SELECT ".$wpdb->prefix."sp_cu.name,".$wpdb->prefix."sp_cu.id,".$wpdb->prefix."sp_cu.pid  ,".$wpdb->prefix."sp_cu.uid,
											".$wpdb->prefix."sp_cu_project.name AS project_name
										FROM ".$wpdb->prefix."sp_cu   
										LEFT JOIN ".$wpdb->prefix."sp_cu_project  ON ".$wpdb->prefix."sp_cu.pid = ".$wpdb->prefix."sp_cu_project.id
										WHERE (".$wpdb->prefix."sp_cu.uid = '".$_GET['uid']."'   ".$find_groups." )
										AND pid != 0
										AND parent = 0 
										".$search_project."
										GROUP BY pid
										ORDER by date desc", ARRAY_A);

									
	$r = $wpdb->get_results("SELECT *  FROM ".$wpdb->prefix."sp_cu   where (uid = '".$_GET['uid']."' ".$find_groups.") AND pid = 0 	AND parent = 0  ".$search_file." order by date desc", ARRAY_A);
	
	}else{
		
		$rel_ex = explode("PID", $_REQUEST['dir']); 
	
		$r = $wpdb->get_results("SELECT *  FROM ".$wpdb->prefix."sp_cu   where pid = '".$rel_ex[1]."' AND parent = 0   ".$search_file."  order by date desc", ARRAY_A);	
	
	}
	


	
	echo "<ul class=\"jqueryFileTree\" style=\"display: none;\">";
	
	
	
	
	for($i=0; $i<count($r_projects); $i++){
		
		
		echo "<li class=\"directory collapsed\"><a href=\"#\" rel=\"PID".$r_projects[$i]['pid']."\">" .stripslashes($r_projects[$i]['project_name']) . "</a></li>";
			
	}
	
	for($i=0; $i<count( $r ); $i++){
		
		$ext = preg_replace('/^.*\./', '', $r[$i]['file']);
		echo "<li class=\"file ext_$ext\"><a href=\"#\" rel=\"".$r[$i]['id']."\">" .stripslashes($r[$i]['name']) . "</a></li>";
		
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