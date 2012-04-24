<?php




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
	
			$target_path = "uploads/";
			$dir = ''.ABSPATH.'wp-content/uploads/sp-client-document-manager/'.$user_ID.'/';
$count = sp_array_remove_empty($files['dlg-upload-file']['name']);



if($history == 1){
		$dir = ''.ABSPATH.'wp-content/uploads/sp-client-document-manager/'.$current_user->ID.'/';
	
	$filename = ''.sp_client_upload_filename($current_user->ID) .''.$files['dlg-upload-file']['name'][0].'';
	$target_path = $dir .$filename; 
	
	move_uploaded_file($files['dlg-upload-file']['tmp_name'][0], $target_path);
	
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

	$dir = ''.ABSPATH.'wp-content/uploads/sp-client-document-manager/'.$current_user->ID.'/';
	
	$filename = ''.sp_client_upload_filename($current_user->ID) .''.$files['dlg-upload-file']['name'][1].'';
	$target_path = $dir .$filename; 
	
	move_uploaded_file($files['dlg-upload-file']['tmp_name'][1], $target_path);
	
	return $filename;
	}
}
}

?>