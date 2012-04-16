<?php


function sp_cdm_display_categories(){
	
	
	global $wpdb,$current_user;


  $cats = $wpdb->get_results("SELECT *
	
									 FROM ".$wpdb->prefix."sp_cu_cats
									
									 ", ARRAY_A);	


  if(count($cats) > 0 ){
	  $html .= ' <tr>
    <td>'.get_option('sp_cu_cat_text').':
	

	
	
	</td>
    <td>
	<select name="pid">
	<option name="" selected="selected">No Category</option>';

		for($i=0; $i<count( $cats ); $i++){
	  $html .= '<option value="'. $cats[$i]['id'].'">'.stripslashes($cats[$i]['name']).'</option>';	
		}
	
	$html .='</select>';
	

	$html .='</td>
  </tr>';
	  
  }

	return $html;
	
}

function sp_cdm_display_projects(){
	
	
	global $wpdb,$current_user;

if($_POST['add-project'] != ""){
	
			$insert['name'] = $_POST['project-name'];
			$insert['uid'] = $current_user->ID;
	$wpdb->insert( "".$wpdb->prefix . "sp_cu_project",$insert );
}
  $projects = $wpdb->get_results("SELECT *
	
									 FROM ".$wpdb->prefix."sp_cu_project
									 WHERE uid = '".$current_user->ID."'
									 ", ARRAY_A);	


  if(count($projects) > 0 or get_option('sp_cu_user_projects') == 1){
	  $html .= ' <tr>
    <td>Project:
	

	
	
	</td>
    <td>
	<select name="pid">
	<option name="" selected="selected">No Project</option>';

		for($i=0; $i<count($projects); $i++){
	  $html .= '<option value="'.$projects[$i]['id'].'">'.stripslashes($projects[$i]['name']).'</option>';	
		}
	
	$html .='</select>';
	
	if(get_option('sp_cu_user_projects') == 1){
		
		$html .= '<a href="javascript:sp_cu_dialog(\'#sp_cu_add_project\',550,130)" class="button" style="margin-left:15px">Add Project</a>
		
	
		
		';
		
	}
	$html .='</td>
  </tr>';
	  
  }

	return $html;
	
}


?>