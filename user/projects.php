<?php


if(!function_exists('sp_cdm_display_projects')){
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
    <td>'.__("Project:","sp-cdm").'
	

	
	
	</td>
    <td>
	<select name="pid">
	<option name="" selected="selected">'.__("No Project","sp-cdm").'</option>';

		for($i=0; $i<count($projects); $i++){
	  $html .= '<option value="'.$projects[$i]['id'].'">'.stripslashes($projects[$i]['name']).'</option>';	
		}
	
	$html .='</select>';
	
	if(get_option('sp_cu_user_projects') == 1){
		
		$html .= '<a href="javascript:sp_cu_dialog(\'#sp_cu_add_project\',550,130)" class="button" style="margin-left:15px">'.__("Add Project","sp-cdm").'</a>
		
	
		
		';
		
	}
	$html .='</td>
  </tr>';
	  
  }

	return $html;
	
}

}

?>