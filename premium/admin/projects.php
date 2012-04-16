<?php

function sp_client_upload_projects_add(){
	
global $wpdb;
	

	
	

echo '
<form action="admin.php?page=sp-client-document-manager-projects" method="post">';

if($_GET['id'] != ""){
$r = $wpdb->get_results("SELECT  * FROM ".$wpdb->prefix."sp_cu_project where id = '".$_GET['id']."'  ", ARRAY_A);	

echo '<input type="hidden" name="id" value="'.$r[0]['id'].'">';
}


$users = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."users order by display_name  ", ARRAY_A);	

$userselect .='<select name="uid">';
if($_GET['id'] != ""){
	$user = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."users  where id = ".$r[0]['uid']."  ", ARRAY_A);	

$userselect .= '<option selected value="'.$r[0]['uid'].'">'.stripslashes($user[0]['display_name']).'</option>';	
}
for($i=0; $i<count($users ); $i++){
	$userselect .= '<option value="'.$users[$i]['ID'].'">'.$users[$i]['display_name'].'</option>';
}
$userselect .= '</select>';




echo '<h2>Projects</h2>'.sp_client_upload_nav_menu().'';
echo '
	 <table class="wp-list-table widefat fixed posts" cellspacing="0">
  <tr>
    <td>Name:</td>
    <td><input type="text" name="project-name" value="'.stripslashes($r[0]['name']).'"></td>
  </tr>
  <tr>
    <td>User:</td>
    <td>'.$userselect.'</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><input type="submit" name="save-project" value="Save"></td>
  </tr>
</table>
</form>


';	
	
}


function sp_client_upload_projects(){
	
	
global $wpdb;


	
if($_POST['save-project'] != ""){
	
	
			$insert['name'] = $_POST['project-name'];
			$insert['uid'] = $_POST['uid'];

		
		if($_POST['id'] != ""){
		$where['id'] =$_POST['id'] ;
		
	    $wpdb->update(  "".$wpdb->prefix . "sp_cu_project", $insert , $where );	
		}else{
			
			

		$wpdb->insert( "".$wpdb->prefix . "sp_cu_project",$insert );
		}
	
	
}
	



if($_GET['function'] == 'add' or $_GET['function'] == 'edit'){
	
	sp_client_upload_projects_add();
	
}elseif($_GET['function'] == 'delete'){
	
	$wpdb->query("DELETE FROM ".$wpdb->prefix ."sp_cu_project WHERE id = ".$_GET['id']."	");		
echo '<script type="text/javascript">
<!--
window.location = "admin.php?page=sp-client-document-manager-projects"
//-->
</script>';

	
}else{
	
	

	$r = $wpdb->get_results("SELECT ".$wpdb->prefix."sp_cu_project.name as projectName,
									".$wpdb->prefix."sp_cu_project.uid,
									".$wpdb->prefix."sp_cu_project.id AS projectID,
									".$wpdb->prefix."users.ID,
									".$wpdb->prefix."users.user_nicename
										
	
									 FROM ".$wpdb->prefix."sp_cu_project
									 LEFT JOIN ".$wpdb->prefix."users ON ".$wpdb->prefix."sp_cu_project.uid = ".$wpdb->prefix."users.ID
									 order by ".$wpdb->prefix."sp_cu_project.name", ARRAY_A);	
								



echo '<h2>Projects</h2>'.sp_client_upload_nav_menu().'';	 
									 
									 echo '
								
									 
									 
									 <div style="margin:10px">
									 <a href="admin.php?page=sp-client-document-manager-projects&function=add" class="button">Add Project</a>
									 </div>
									 <table class="wp-list-table widefat fixed posts" cellspacing="0">
	<thead>
	<tr>
<th>ID</th>
<th>Name</th>
<th>User</th>
<th>Action</th>
</tr>
	</thead>';
				for($i=0; $i<count(	$r); $i++){
				
				$vendor_info[$i] = unserialize($vendors[$i]['option_value']);	
					
				echo '	<tr>
<td>'.$r[$i]['projectID'].'</td>				
<td>'.stripslashes($r[$i]['projectName']).'</td>
<td>'.$r[$i]['user_nicename'].'</td>
<td>
<a href="../wp-content/plugins/sp-client-document-manager/ajax.php?function=download-project&id='.$r[$i]['projectID'].'" style="margin-right:15px" >Download Archive</a>  

 <a href="admin.php?page=sp-client-document-manager-projects&function=delete&id='.$r[$i]['projectID'].'" style="margin-right:15px" >Delete</a> 
<a href="admin.php?page=sp-client-document-manager-projects&function=edit&id='.$r[$i]['projectID'].'" >Modify</a></td>
</tr>';	
					
				}
				echo '</table>';
}
}



?>