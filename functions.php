<?php


function cdm_thumbPdf($pdf)
{
    try
    {
        $tmp = '../../../uploads/wp-client-document-manager';
        $format = "png";
        $source = $pdf;
		
        $dest =  "".$pdf."_small.$format";
 		$dest2 = "".$pdf."_big.$format";
      
	  
            $exec = "convert -scale 80x80 ".$source."[0] $dest";
			
            exec($exec);
			 $exec2 = "convert -scale 250x250 ".$source."[0] $dest2";			
            exec($exec2);
		
 
        $im = new Imagick($dest);
   
    }
    catch(Exception $e)
    {
       // echo $e->getMessage();
    }
	
}

if (!function_exists('sp_client_upload_settings')){

function sp_client_upload_settings(){
	
global $wpdb;
	
	
	if($_GET['save_options'] == 1){
		

		
			foreach( $_POST as $key => $value){
				
			
	
				
				update_option( $key,$value ); 
	
			}
			
			
				
	if($_POST['sp_cu_user_projects'] == "1"){update_option('sp_cu_user_projects','1' ); }else{update_option('sp_cu_user_projects','0' );	}
	if($_POST['sp_cu_user_projects_thumbs'] == "1"){update_option('sp_cu_user_projects_thumbs','1' ); }else{update_option('sp_cu_user_projects_thumbs','0' );	}
	if($_POST['sp_cu_user_projects_thumbs_pdf'] == "1"){update_option('sp_cu_user_projects_thumbs_pdf','1' ); }else{update_option('sp_cu_user_projects_thumbs_pdf','0' );	}
	if($_POST['sp_cu_js_redirect'] == "1"){update_option('sp_cu_js_redirect','1' ); }else{update_option('sp_cu_js_redirect','0' );	}		
			
	}
	
	
	
	if(get_option('sp_cu_user_projects') == 1){ $sp_cu_user_projects = ' checked="checked" ';	}else{ $sp_cu_user_projects = '  '; }
	if(get_option('sp_cu_user_projects_thumbs') == 1){ $sp_cu_user_projects_thumbs = ' checked="checked" ';	}else{ $sp_cu_user_projects_thumbs = '  '; }
	if(get_option('sp_cu_user_projects_thumbs_pdf') == 1){ $sp_cu_user_projects_thumbs_pdf = ' checked="checked" ';	}else{ $sp_cu_user_projects_thumbs_pdf = '  '; }
	if(get_option('sp_cu_js_redirect') == 1){ $sp_cu_js_redirect = ' checked="checked" ';	}else{ $sp_cu_js_redirect = '  '; }
	echo '<h2>Settings</h2>'.sp_client_upload_nav_menu().'';	 
	
	
	if($_POST['upgrade'] != ""){
	
	
	$mydir = ''.ABSPATH.'wp-content/plugins/sp-client-document-manager/premium/'; 
	if (is_dir($mydir)){
	$d = dir($mydir); 
	while($entry = $d->read()) { 
	 if ($entry!= "." && $entry!= "..") { 
	 @unlink($entry); 
	 } 
	} 
	$d->close(); 
	@rmdir($mydir); 
	}
	function _return_direct() { return 'direct'; }
add_filter('filesystem_method', '_return_direct');
WP_Filesystem();
remove_filter('filesystem_method', '_return_direct');
	
	global $wp_filesystem;	
	echo  unzip_file( $_FILES['premium']['tmp_name'],''.ABSPATH.'wp-content/plugins/sp-client-document-manager/' );	
	echo '<script type="text/javascript">
<!--
window.location = "admin.php?page=sp-client-document-manager-settings&cdm-upgrade=1"
//-->
</script>';
	
	
	}
	
	$content .='
<div style="border:1px solid #CCC;padding:5px;margin:5px;background-color:#e3f1d4;">';

if(CU_PREMIUM != 1){

$content .='<h3>Upgrade to premium!</h3>
<p>If you would like to see the extra features and upgrade to premium please purchase the addon package by <a href="http://smartypantsplugins.com/sp-client-document-manager/" target="_blank">clicking here</a>. Once purchased you will receive a file, upload that file here and the plugin will do the rest!</p>';
}else{
$content .='<h3>Thanks for upgrading!</h3>
<p>You can patch the premium version with the upload form below once new versions become available!</p>';
}
	$content .='
<form action="" method="post" enctype="multipart/form-data">
<input type="file" name="premium"> <input type="submit" name="upgrade" value="Install Premium!">
</form>

</div>
<h2>Settings</h2>

	<form action="admin.php?page=sp-client-document-manager-settings&save_options=1" method="post">
	 <table class="wp-list-table widefat fixed posts" cellspacing="0">
  
   
         <tr>
    <td width="300"><strong>Filename Format</strong><br><em>Use the below codes to determine the file format, whatever you put in the box will show up before the actual file name.If you keep this blank then you leave the risk to existing files. Please see the example to the right.</em><br><br>
	%y =  Year: yyyy<br> 
	%d =  Day:  dd<br>
	%m =  Month: mm<br>
	%h =  Hour: 24 hour format<br>
	%min = Minute<br>
	%u = Username<br>
	%uid = User ID<br>
	%t = Timstamp<br>
	%r = Random #<br>
	
	</td>
    <td><input type="text" name="sp_cu_filename_format"  value="'.get_option('sp_cu_filename_format').'"  size=80"><br><div style="margin:5px;padding:5px;"> Example:<br><br>
	If the user uploads a file called example.pdf and you put<strong>  %y-%m-%d-</strong> the final file name  will be: <strong>'.date("Y").'-'.date("m").'-'.date("d").'-example.pdf</strong></div></td>
  </tr>
      <tr>
    <td width="300"><strong>Thank you message</strong><br><em>This is the thank you text the user sees after they upload.</em></td>
    <td><input type="text" name="sp_cu_thankyou"  value="'.get_option('sp_cu_thankyou').'"  size=80"> </td>
  </tr>
       <tr>
    <td width="300"><strong>Delete Message</strong><br><em>The confirmation screen asking the user if they want to delete the file.</em></td>
    <td><input type="text" name="sp_cu_delete"  value="'.get_option('sp_cu_delete').'"  size=80"> </td>
  </tr>
  <tr>
    <td width="300"><strong>WP Folder</strong><br><em>Use this option only if your wp installation is in a sub folder of your url. For instance if your site is www.example.com/blog/ then put /blog/ in the field. This helps find the uploads directory.</em></td>
    <td><input type="text" name="sp_cu_wp_folder"  value="'.get_option('sp_cu_wp_folder').'"  size=80"> </td>
  </tr>
  
     <tr>
    <td width="300"><strong>Javascript Redirect?</strong><br><em>If your on a windows system you need to use javascript redirection as FastCGI does not allow force download files.</em></td>
    <td><input type="checkbox" name="sp_cu_js_redirect"   value="1" '. $sp_cu_js_redirect.'> </td>
  </tr>
  ';
  
  if (CU_PREMIUM == 1){
	  
	

	  $content .='
     <tr>
    <td width="300"><strong>User Projects?</strong><br><em>If you want to allow the user to create projects check this box.</em></td>
    <td><input type="checkbox" name="sp_cu_user_projects"   value="1" '. $sp_cu_user_projects.'> </td>
  </tr>
     <tr>
    <td width="300"><strong>Thumbnail Mode?</strong><br><em>Would you like to display all images as thumbnails?</em></td>
    <td><input type="checkbox" name="sp_cu_user_projects_thumbs"   value="1" '. $sp_cu_user_projects_thumbs.'> </td>
  </tr>
    <tr>
    <td width="300"><strong>Create thumbnails for pdfs and psds?</strong><br><em>You must have Image Magick Installed on your server. '. $imagemagick.' </em></td>
    <td><input type="checkbox" name="sp_cu_user_projects_thumbs_pdf"   value="1" '. $sp_cu_user_projects_thumbs_pdf.'> </td>
  </tr>
  <tr>
    <td width="300"><strong>Categories Text</strong><br><em>This is the text you want to call categories, for example you may want to use it as a status.</em></td>
    <td><input type="text" name="sp_cu_cat_text"  value="'.get_option('sp_cu_cat_text').'"  size=80"> </td>
  </tr>
   <tr>
    <td width="300"><strong>File Deletion Period</strong><br><em>How many days should a file exist before its deleted from the system? Leave blank if you do not wish to use this function.</em></td>
    <td><input type="text" name="sp_cu_file_delete"  value="'.get_option('sp_cu_file_delete').'"  size=80"> </td>
  </tr>
     <tr>
    <td width="300"><strong>Dropbox App Key</strong><br><em>App key from your <a href="https://www.dropbox.com/developers/apps" target="_blank">Dropbox app page</a>.</em></td>
    <td><input type="text" name="sp_cu_db_app_key"  value="'.get_option('sp_cu_db_app_key').'"  size=80"> </td>
  </tr>
      <tr>
    <td width="300"><strong>Dropbox App Secret</strong><br><em>App secret from your <a href="https://www.dropbox.com/developers/apps" target="_blank">Dropbox app page</a>.</em></td>
    <td><input type="text" name="sp_cu_db_secret"  value="'.get_option('sp_cu_db_secret').'"  size=80"> </td>
  </tr>
  ';
  
  }
  $content .='
    <tr>
    <td>&nbsp;</td>
    <td><input type="submit" name="save_options" value="Save Options"></td>
  </tr>
</table>
</form>
	
	';
	
	
	
	echo $content;
	
}
}
if (!function_exists('sp_client_upload_help')){
function sp_client_upload_help(){
	
echo '<h2>Smarty Pants Client Document Manager</h2>'.sp_client_upload_nav_menu().'
	
<p>Please update the page where your uploader shortcode will be placed</p>
<p>On the page place the shortcode [sp-client-document-manager] to show the uploader</p>
<p>This plugin relies on 2 other plugins to make a seamless experience, you will want to download install "Theme my login" and "Cemys extra fields" You optionally download "Ajax login" for a nice login page on your sidebar</p>
<p>Please donate to keep development going on this plugin! <a href="http://smartypantsplugins.com/donate/" target="_blank">http://smartypantsplugins.com/donate/</a></p>
 
';	
	
}
}
if (!function_exists('sp_client_upload_nav_menu')){
function sp_client_upload_nav_menu(){
	global $cu_ver,$sp_client_upload,$sp_cdm_ver ;
	
	if(CU_PREMIUM == 1){
		
		$ver = $sp_cdm_ver;
		
	}else{
	$ver = $sp_client_upload;	
	}
$content .= '<strong>Version:</strong> '.$cu_ver .' '.$ver.'<div style="padding:10px;font-weight:bold">

<a href="admin.php?page=sp-client-document-manager" class="button" style="margin-right:10px">'.__("Home","sp-cdm").'</a>
<a href="admin.php?page=sp-client-document-manager-settings" class="button" style="margin-right:10px">'.__("Settings","sp-cdm").'</a>
<a href="admin.php?page=sp-client-document-manager-vendors" class="button" style="margin-right:10px">'.__("Vendors","sp-cdm").'</a>';
$content .= '<a href="admin.php?page=sp-client-document-manager-projects" class="button" style="margin-right:10px">'.__("Projects","sp-cdm").'</a>';
if (CU_PREMIUM == 1){

$content .= '<a href="admin.php?page=sp-client-document-manager-categories" class="button" style="margin-right:10px">'.__("Categories","sp-cdm").'</a>';
}
$content .= '<a href="admin.php?page=sp-client-document-manager-help" class="button" style="margin-right:10px">'.__("Instructions","sp-cdm").'</a>
<a href="users.php" class="button"  style="margin-right:10px">'.__("View users","sp-cdm").'</a>
</div>';	
	return $content;
}

}
if (!function_exists('sp_client_upload_admin')){

function sp_client_upload_admin(){
	
	global $wpdb;
	
	$user_id = $_REQUEST['user_id'];
	

if($_GET['dlg-delete-file'] != ""){
	
		$r = $wpdb->get_results("SELECT *  FROM ".$wpdb->prefix."sp_cu   where  id = ".$_GET['dlg-delete-file']."", ARRAY_A);
		
		
		unlink('../wp-content/uploads/sp-client-document-manager/'.$user_id.'/'.$r[0]['file'].'');
	
		$wpdb->query("
	DELETE FROM ".$wpdb->prefix."sp_cu WHERE id = ".$_GET['dlg-delete-file']."
	");
	

}





	
	
	if($user_id != ""){
		echo '<h2>'.__("User Uploads","sp-cdm").'</h2><a name="downloads"></a>';
	$r = $wpdb->get_results("SELECT *  FROM ".$wpdb->prefix."sp_cu   where uid = $user_id  and parent = 0 order by date desc", ARRAY_A);
	$delete_page = 'user-edit.php?user_id='.$user_id.'';
	
	$download_user = '<a href="../wp-content/plugins/sp-client-document-manager/ajax.php?function=download-archive&id='.$user_id.'" class="button">'.__("Click to download all files","sp-cdm").'</a>';
	}else{
	$r = $wpdb->get_results("SELECT *  FROM ".$wpdb->prefix."sp_cu   where  parent = 0 order by id desc LIMIT 150", ARRAY_A);	
	$html .='<form id="your-profile">';	
	$delete_page = 'admin.php?page=sp-client-document-manager';
		$download_user = '';
	}

if($r == FALSE){
	
	
$html .=  '<p style="color:red">'.__("No Uploads Exist!","sp-cdm").'</p>';
	
}else{



//show uploaded documents
  $html .= '
<script type="text/javascript">

function sp_client_upload_email_vendor(){
	

    	jQuery.ajax({
			 
		  type: "POST",
		  url:  "../wp-content/plugins/sp-client-document-manager/ajax.php?function=email-vendor" ,
		 
		 data:  jQuery("#your-profile" ).serialize(),
		  success: function(msg){
   								jQuery("#updateme").empty();
								jQuery("#updateme").append( msg);
								
							  }
 		});	
	
	return false;
}


</script>
'.	$download_user.'
  <table class="wp-list-table widefat fixed posts" cellspacing="0">
	<thead>
	<tr>
<th>'.__("File Name","sp-cdm").'</th>
<th>'.__("User","sp-cdm").'</th>
<th>'.__("Date","sp-cdm").'</th>
<th>'.__("Download","sp-cdm").'</th>
<th>'.__("Email","sp-cdm").'</th>
</tr>
	</thead>


';



				for($i=0; $i<count($r); $i++){
					
					
					if($r[$i]['name'] == ""){
						
						$name = $r[$i]['file'];
					}else{
						
						$name = $r[$i]['name'];
			
		
		
					}
					
			$r_user = $wpdb->get_results("SELECT *  FROM ".$wpdb->prefix."users where ID = ".$r[$i]['uid']."", ARRAY_A);				
				if(get_option('sp_cu_js_redirect') == 1){
				$target = 'target="_blank"';	
				}else{
				$target = ' ';	
				}	
					
					
				$html .= '
	
 <tr>
    <td ><strong>'.stripslashes($name).'</strong>
	<br><em>'.__("Notes: ","sp-cdm").' '.$r[$i]['notes'].'</em>
	
	</td>
	<td><a href="user-edit.php?user_id='.$r[$i]['uid'].'">'.$r_user[0]['display_name'].'</a></td>
	 <td >'.date('F jS Y h:i A', strtotime($r[$i]['date'])).'</td>
   
    <td><a style="margin-right:15px" href="' . get_bloginfo('wpurl') . '/wp-content/plugins/sp-client-document-manager/download.php?fid='.$r[$i]['id'].'" target="_blank">'.__("Download","sp-cdm").'</a> <a href="'.$delete_page .'&dlg-delete-file='.$r[$i]['id'].'&user_id='.$r[$i]['uid'].'#downloads">'.__("Delete","sp-cdm").'</a> </td>
<td><input type="checkbox" name="vendor_email[]" value="'.$r[$i]['id'].'"></td>	</tr>


  
  ';	
					
				}
			$html .= '</table>
			
				<div style="text-align:right">
			<div id="updateme"></div>
				'.__("Choose  the files you want to send above, type a message and choose a vendor then click submit:","sp-cdm").'  <select name="vendor">
				';
				
				
				
				if($_POST['submit-vendor'] != ""){
					
				//	print_r($_POST);
					
					
				}
				
			$vendors = $wpdb->get_results("SELECT *  FROM ".$wpdb->prefix."options   where option_name  LIKE 'sp_client_upload_vendors%'  order by option_name", ARRAY_A);

						for($i=0; $i<count(	$vendors); $i++){
				
				$vendor_info[$i] = unserialize($vendors[$i]['option_value']);
				
				$html .=  '<option value="'.$vendor_info[$i]['email'].'">'.$vendor_info[$i]['name'].'</option>';
				
						}
				
				
				$html .= '</select> '.__("Message:","sp-cdm").' <input type="text" name="vendor-message"> <select name="vendor_attach"><option value="1">'.__("Attach to email:","sp-cdm").' </option><option value="0">'.__("Send links to files","sp-cdm").' </option><option value="3">'.__("Attach and link to to files","sp-cdm").' </option></select> <input type="submit" name="submit-vendor" value="'.__("Email vendor files!","sp-cdm").'" onclick="sp_client_upload_email_vendor();return false;"> 
				</div>
				';
				
		
}
if($user_id != ""){
echo $html;
}else{
	$html .='</form>';	
return $html;	
}
	
}



}



add_action( 'edit_user_profile', 'sp_client_upload_admin' );
?>