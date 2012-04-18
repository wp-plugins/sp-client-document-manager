<?php


function sp_client_upload_cat_add(){
	
global $wpdb;
	

	
	

echo '
<form action="admin.php?page=sp-client-document-manager-categories" method="post">';

if($_GET['id'] != ""){
$r = $wpdb->get_results("SELECT  * FROM ".$wpdb->prefix."sp_cu_cats where id = '".$_GET['id']."'  ", ARRAY_A);	

echo '<input type="hidden" name="id" value="'.$r[0]['id'].'">';
}






echo '<h2>Categories</h2>'.sp_client_upload_nav_menu().'';
echo '
	 <table class="wp-list-table widefat fixed posts" cellspacing="0">
  <tr>
    <td>Name:</td>
    <td><input type="text" name="project-name" value="'.stripslashes($r[0]['name']).'"></td>
  </tr>

  <tr>
    <td>&nbsp;</td>
    <td><input type="submit" name="save-cat" value="Save"></td>
  </tr>
</table>
</form>


';	
	
}




function sp_client_upload_cat_view(){
	
	global $wpdb;
	
	if($_POST['save-cat'] != ""){
	
	
			$insert['name'] = $_POST['project-name'];
			

		
		if($_POST['id'] != ""){
		$where['id'] =$_POST['id'] ;
		
	    $wpdb->update(  "".$wpdb->prefix . "sp_cu_cats", $insert , $where );	
		}else{
			
			

		$wpdb->insert( "".$wpdb->prefix . "sp_cu_cats",$insert );
		}
	
	
}
	



if($_GET['function'] == 'add' or $_GET['function'] == 'edit'){
	
	sp_client_upload_cat_add();
	
}elseif($_GET['function'] == 'delete'){
	
	$wpdb->query("DELETE FROM ".$wpdb->prefix ."sp_cu_cats WHERE id = ".$_GET['id']."	");		
echo '<script type="text/javascript">
<!--
window.location = "admin.php?page=sp-client-document-manager-categories"
//-->
</script>';

	
}else{
	
	echo '<h2>Categories</h2>'.sp_client_upload_nav_menu().'';	
	$r = $wpdb->get_results("SELECT  * FROM ".$wpdb->prefix."sp_cu_cats order by name", ARRAY_A);	

	
		 echo '
								
									 
									 
									 <div style="margin:10px">
									 <a href="admin.php?page=sp-client-document-manager-categories&function=add" class="button">Add Category</a>
									 </div>
									 <table class="wp-list-table widefat fixed posts" cellspacing="0">
	<thead>
	<tr>
<th>ID</th>
<th>Name</th>

<th>Action</th>
</tr>
	</thead>';
				for($i=0; $i<count(	$r); $i++){
					
					
					echo '<tr>
						<td>'.$r[$i]['id'].'</td>
						<td>'.stripslashes($r[$i]['name']).'</td>
<td>
 <a href="admin.php?page=sp-client-document-manager-categories&function=delete&id='.$r[$i]['id'].'" style="margin-right:15px" >Delete</a> 
<a href="admin.php?page=sp-client-document-manager-categories&function=edit&id='.$r[$i]['id'].'" >Modify</a></td>
</tr>';
					
				}
				
				echo '</table>';
	
}
}
?>