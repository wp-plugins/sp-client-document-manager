<?php
if(!function_exists('sp_cdm_category_value')){
function sp_cdm_category_value($id){
global $wpdb;

    $r_cat = $wpdb->get_results("SELECT *  FROM " . $wpdb->prefix . "sp_cu_cats   where id = '" . $id . "'", ARRAY_A);	 	
	return stripslashes($r_cat[0]['name']);
}
	
function sp_cdm_category_name(){
	
	if(get_option('sp_cu_cat_text') != ''){
		$cat= get_option('sp_cu_cat_text');
	}else{
		$cat =  __("Category", "sp-cdm");
	}
return $cat;	
}

}

if(!function_exists('set_html_content_type')){
function set_html_content_type() {

	return 'text/html';
}	
}

if(!function_exists('sp_cdm_folder_name')){


	
function sp_cdm_folder_name($type = 0){
	
	
		if($type == 1){
			
			if(get_option('sp_cu_folder_name_plural') == ''){
			return  __("Folders", "sp-cdm");	
			}else{
			return  stripslashes(get_option('sp_cu_folder_name_plural'));
			}
		}else{
			if(get_option('sp_cu_folder_name_single') == ''){
				return  __("Folder", "sp-cdm");
			}else{
			return  stripslashes(get_option('sp_cu_folder_name_single'));
			
			}
		}
				
	
}
}
function sp_cdm_thumbnail($url,$w,$h){
	global $wpdb;
	$params = array('width' => 400, 'height' => $h,'width' => $w, 'crop' => true);

			return bfi_thumb($url, $params);
}

function sp_cdm_get_current_user_role_name () {
    global $current_user;
    get_currentuserinfo();
    $user_roles = $current_user->roles;
    $user_role = array_shift($user_roles);
    return $user_role;
}


function sp_cdm_get_project_name($id){

	

		global $wpdb;

		

			$r = $wpdb->get_results("SELECT *

	

									 FROM ".$wpdb->prefix."sp_cu_project

									 WHERE id = '".$id."'", ARRAY_A);	

									 

				if($r[0]['name'] != ""){

					return stripslashes($r[0]['name']);

				}else{

				return false;

}

}

function sp_cdm_get_current_user_role() {

global $current_user;



	$user_roles = $current_user->roles;

	print_r($user_roles);

	$user_role = array_shift($user_roles);



	return $user_role;

}





function sp_cdm_find_users_by_role($role) {

	global $wpdb;



 $wp_user_search = new WP_User_Query(array("role"=> $role));

 $role_data = $wp_user_search->get_results();

    foreach($role_data  as $item){

 $role_data_ids[] = $item->ID;

 }



 $ids = implode(',', $role_data_ids);

 $r = $wpdb->get_results("SELECT *   from ".$wpdb->prefix . "users where id IN(".$ids .")", ARRAY_A);







 for($i=0; $i<count($r); $i++){

$emails[$i] = $r[$i]['user_email'];

 }





 return $emails;

}

function sp_do_function_header($file){

	

	

}



function sp_client_upload_filename($user_id){

	global $wpdb;

	

	

	$r = $wpdb->get_results("SELECT*

									 FROM ".$wpdb->prefix."users  where id = $user_id", ARRAY_A);	

	

	

	

	$extra = get_option('sp_cu_filename_format') ;

	$extra = str_replace('%y',date('Y'), $extra);

	$extra = str_replace('%h',date('H'), $extra );

	$extra = str_replace('%min',date('i'), $extra );

	$extra = str_replace('%m',date('m'), $extra );

	$extra = str_replace('%d',date('d'), $extra);

	$extra = str_replace('%t',time(), $extra );

	$extra = str_replace('%uid',$user_id, $extra );

	

	$extra = str_replace('%u',$r[0]['display_name'], $extra );	

	$extra = str_replace('%r',rand(100000, 100000000000), $extra );

	return $extra;

	

}





function sp_array_remove_empty($arr){

    $narr = array();

    while(list($key, $val) = each($arr)){

        if (is_array($val)){

            $val = array_remove_empty($val);

            // does the result array contain anything?

            if (count($val)!=0){

                // yes :-)

                $narr[$key] = $val;

            }

        }

        else {

            if (trim($val) != ""){

                $narr[$key] = $val;

            }

        }

    }

    unset($arr);

    return $narr;

}



function sp_uploadFile($files, $history = NULL){

	

	global $wpdb ;

	global $user_ID;

	global $current_user;

	

			

			$dir = ''.SP_CDM_UPLOADS_DIR.''.$user_ID.'/';

			$count = sp_array_remove_empty($files['dlg-upload-file']['name']);







			if($history == 1){

		$dir = ''.SP_CDM_UPLOADS_DIR.''.$current_user->ID.'/';

	

	$filename = ''.sp_client_upload_filename($current_user->ID) .''.$files['dlg-upload-file']['name'][0].'';

	$filename = strtolower($filename);

	$filename = sanitize_file_name($filename);

	$target_path = $dir .$filename; 

	

	move_uploaded_file($files['dlg-upload-file']['tmp_name'][0], $target_path);

	

	$ext = preg_replace('/^.*\./', '', $filename);

	if(get_option('sp_cu_user_projects_thumbs_pdf') == 1 && class_exists('imagick')){
	
	$info = new Imagick();
	$formats = $info->queryFormats();
		
		if(in_array(strtoupper($ext),$formats)){
		cdm_thumbPdf($target_path);
		}
	}

	

	return $filename;

}else{



	if(count($count)> 1 ){

	

	

	//echo $count;

	//	echo '<pre>';

	//print_r($files);exit;

	//echo '</pre>';



	

	

	



		

		

			$fileTime = date("D, d M Y H:i:s T");



				$zip = new Zip();

				

				

				

				for($i=0; $i<count($files['dlg-upload-file']['name']); $i++){

				

					if($files['dlg-upload-file']['error'][$i] == 0){

						

					

					

						$filename = ''.sp_client_upload_filename($user_ID) .''.$files['dlg-upload-file']['name'][$i].'';

						$filename = strtolower($filename);

						$filename = sanitize_file_name($filename);

						$target_path = $dir .$filename; 

						move_uploaded_file($files['dlg-upload-file']['tmp_name'][$i], $target_path);

				

					  $zip->addFile(file_get_contents($target_path), $filename , filectime($target_path));

					}

				}

		

		

$zip->finalize(); // as we are not using getZipData or getZipFile, we need to call finalize ourselves.

$return_file = "".rand(100000, 100000000000)."_Archive.zip";

$zip->setZipFile($dir.$return_file);

		

	return $return_file;	

		

		

	}else{



	$dir = ''.SP_CDM_UPLOADS_DIR.''.$current_user->ID.'/';

	

	$filename = ''.sp_client_upload_filename($current_user->ID) .''.$files['dlg-upload-file']['name'][1].'';

	$filename = strtolower($filename);

	$filename = sanitize_file_name($filename);

	$target_path = $dir .$filename; 

	

	move_uploaded_file($files['dlg-upload-file']['tmp_name'][1], $target_path);

	$ext = preg_replace('/^.*\./', '', $filename);

	if(get_option('sp_cu_user_projects_thumbs_pdf') == 1 && class_exists('imagick')){
	
	$info = new Imagick();
	$formats = $info->queryFormats();
		
		if(in_array(strtoupper($ext),$formats)){
		cdm_thumbPdf($target_path);
		}
	}


	return $filename;

	}

}

}

function sp_Admin_uploadFile($files,$user_ID){

	

	global $wpdb ;



	

			

			$dir = ''.SP_CDM_UPLOADS_DIR.''.$user_ID.'/';

			$count = sp_array_remove_empty($files['dlg-upload-file']['name']);







			if($history == 1){

		$dir = ''.SP_CDM_UPLOADS_DIR.''.$user_ID.'/';

	

	$filename = ''.sp_client_upload_filename($user_ID) .''.$files['dlg-upload-file']['name'][0].'';

	$filename = strtolower($filename);

	$filename = sanitize_file_name($filename);

	$target_path = $dir .$filename; 

	

	move_uploaded_file($files['dlg-upload-file']['tmp_name'][0], $target_path);

	

	$ext = preg_replace('/^.*\./', '', $filename);

	if(get_option('sp_cu_user_projects_thumbs_pdf') == 1 && class_exists('imagick')){
	
	$info = new Imagick();
	$formats = $info->queryFormats();
		
		if(in_array(strtoupper($ext),$formats)){
		cdm_thumbPdf($target_path);
		}
	}


	

	return $filename;

}else{









	if(count($count)> 1 ){

	

	

	//echo $count;

	//	echo '<pre>';

	//print_r($files);exit;

	//echo '</pre>';



	

	

	



		

		

			$fileTime = date("D, d M Y H:i:s T");



				$zip = new Zip();

				

				

				

				for($i=0; $i<count($files['dlg-upload-file']['name']); $i++){

				

					if($files['dlg-upload-file']['error'][$i] == 0){

						

					

					

						$filename = ''.sp_client_upload_filename($user_ID) .''.$files['dlg-upload-file']['name'][$i].'';

						$filename = strtolower($filename);

						$filename = sanitize_file_name($filename);

						$target_path = $dir .$filename; 

						move_uploaded_file($files['dlg-upload-file']['tmp_name'][$i], $target_path);

				

					  $zip->addFile(file_get_contents($target_path), $filename , filectime($target_path));

					}

				}

		

		

$zip->finalize(); // as we are not using getZipData or getZipFile, we need to call finalize ourselves.

$return_file = "".rand(100000, 100000000000)."_Archive.zip";

$zip->setZipFile($dir.$return_file);

		

	return $return_file;	

		

		

	}else{



	$dir = ''.SP_CDM_UPLOADS_DIR.''.$user_ID.'/';

	

	$filename = ''.sp_client_upload_filename($user_ID) .''.$files['dlg-upload-file']['name'][1].'';

	$filename = strtolower($filename);

	$filename = sanitize_file_name($filename);

	$target_path = $dir .$filename; 

	

	move_uploaded_file($files['dlg-upload-file']['tmp_name'][1], $target_path);

	$ext = preg_replace('/^.*\./', '', $filename);

	if(get_option('sp_cu_user_projects_thumbs_pdf') == 1 && class_exists('imagick')){
	
	$info = new Imagick();
	$formats = $info->queryFormats();
		
		if(in_array(strtoupper($ext),$formats)){
		cdm_thumbPdf($target_path);
		}
	}


	return $filename;

	}

}

}



?>