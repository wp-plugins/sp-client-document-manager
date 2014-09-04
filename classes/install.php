<?php

$sp_cdm_installer = new sp_cdm_installer;
register_activation_hook(''.SP_CDM_PLUGIN_DIR.'cu.php', array($sp_cdm_installer,'install') );
add_action('plugins_loaded',  array($sp_cdm_installer,'upgrade_check') );
add_action('init',  array($sp_cdm_installer,'check_caps') );
if($_GET['force_upgrade'] == 1){
add_action('plugins_loaded',  array($sp_cdm_installer,'install') );	
}

add_action( 'sp_cdm_errors', array($sp_cdm_installer,'sp_cdm_premium_upgrades') );	
add_action('admin_init',  array($sp_cdm_installer,'sp_cdm_check_admin_caps'));
class sp_cdm_installer{
	


function sp_cdm_check_admin_caps(){
	global $current_user;
	
	
	wp_enqueue_script('jquery');
   
    wp_enqueue_script('jquery-ui-core');
    wp_enqueue_script('jquery-ui-dialog');
    wp_enqueue_script('jquery-ui-tabs');
	wp_enqueue_script('jquery-ui-button');
	
	if($current_user != ''){
	
	@require_once(ABSPATH . 'wp-includes/pluggable.php');
if (  user_can($current_user->ID,'manage_options') && !current_user_can('sp_cdm') ) {
		$role = get_role( 'administrator' );
		$role->add_cap( 'sp_cdm' );	
		$role->add_cap( 'sp_cdm_vendors' );	
		$role->add_cap( 'sp_cdm_settings' );	
		$role->add_cap( 'sp_cdm_projects' );	
		$role->add_cap( 'sp_cdm_uploader' );
	
}
if (  user_can($current_user->ID,'manage_options') && !current_user_can('sp_cdm_help') ) {$role = get_role( 'administrator' ); $role->add_cap( 'sp_cdm_help' );}
if (  user_can($current_user->ID,'manage_options') && !current_user_can('sp_cdm_forms') ) {$role = get_role( 'administrator' ); $role->add_cap( 'sp_cdm_forms' );}
if (  user_can($current_user->ID,'manage_options') && !current_user_can('sp_cdm_groups') ) {$role = get_role( 'administrator' );  $role->add_cap( 'sp_cdm_groups' );}
if (  user_can($current_user->ID,'manage_options') && !current_user_can('sp_cdm_categories') ) {$role = get_role( 'administrator' ); $role->add_cap( 'sp_cdm_categories' );}
if (  user_can($current_user->ID,'manage_options') && !current_user_can('sp_cdm_top_menu') ) {$role = get_role( 'administrator' ); $role->add_cap( 'sp_cdm_top_menu' );}
if (  user_can($current_user->ID,'manage_options') && !current_user_can('sp_cdm_show_folders_as_nav') ) {$role = get_role( 'administrator' ); $role->add_cap( 'sp_cdm_show_folders_as_nav' );}
	}
}


	
	
function sp_cdm_premium_upgrades(){
	
	   global $wpdb;
	   $upgrade_count += 0;
	
  $table_name = "".$wpdb->prefix . "sp_cu_meta";
		 if($wpdb->get_var("show tables like '$table_name'") != $table_name){			
			$sql_meta = "CREATE TABLE IF NOT EXISTS `".$table_name."` (
			  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			  `fid` bigint(20) unsigned NOT NULL DEFAULT '0',
			  `pid` bigint(20) unsigned NOT NULL DEFAULT '0',
			  `uid` bigint(20) unsigned NOT NULL DEFAULT '0',
			  `meta_key` varchar(255) DEFAULT NULL,
			  `meta_value` longtext,
			  PRIMARY KEY (`id`)
			);" ;
			
			$upgrade_count +=1;
			
			  
		 }	
	
	apply_filters('sp_cdm_premium_upgrades',$upgrade_count);
	
	if($_GET['database_upgrade'] == 1){
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql_meta );
	
		echo '  <div class="updated"><p>Database updates applied!</p>
    </div>';
	
	$updated = 1;
	}
	if($updated != 1){
	if($upgrade_count > 0){
	echo '  <div class="error"><p><strong>SP Client Document Manager Update:</strong> '.$upgrade_count.' Database update(s) needed. <a href="admin.php?page=sp-client-document-manager&database_upgrade=1" class="button">click here to upgrade</a></p>
    </div>';
	}
	}
	
}
	
function install(){
	global $wpdb,$sp_client_upload;
   require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
 
  
  
  	//create upload directories
   	$dir = SP_CDM_UPLOADS_DIR;
	$cdm_upload_dir =  wp_upload_dir();
	
	if(!is_dir($cdm_upload_dir['basedir'])){
	
		@mkdir($cdm_upload_dir['basedir'], 0777);
	}
	
	if(!is_dir($dir)){
	
		@mkdir($dir, 0777);
	}
	
	//install the tables
	
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	
	$tables = $this->db_tables();
	foreach($tables as $key => $value){
		  if($value != ""){
		  dbDelta($value);			
		  }
	}
	

		do_action('sp_cdm_install');
	
		if ( get_option( 'sp_client_upload') == '') {
		  add_option("sp_client_upload", $sp_client_upload);
		  add_option("sp_client_upload_page", 'Please enter the page');
		
		}else{
		update_option("sp_client_upload", $sp_client_upload);
		}
	}
	
	function upgrade_check(){
		
	global $wpdb,$sp_client_upload;
	
	$alters = $this->db_alters();
		if(count($alters) >0){
			foreach( $alters as $key => $value){
				  if($value != ""){
				  $wpdb->query($value);
				  }
			}
		do_action('sp_cdm_upgrade_check');
		update_option("sp_client_upload", $sp_client_upload);
		}
		  
	
		
	}
function check_caps(){
global 	$current_user;
	@require_once(ABSPATH . 'wp-includes/pluggable.php');
if (  user_can(@$current_user->ID,'manage_options') && !current_user_can('sp_cdm') ) {
		$role = get_role( 'administrator' );
		$role->add_cap( 'sp_cdm' );	
		$role->add_cap( 'sp_cdm_vendors' );	
		$role->add_cap( 'sp_cdm_settings' );	
		$role->add_cap( 'sp_cdm_projects' );	
		$role->add_cap( 'sp_cdm_uploader' );
		
}
	do_action('sp_cdm_check_caps');
}
	
	function db_alters(){
		global $wpdb,$sp_client_upload;
		$cur_sp_client_upload  =	 get_option( 'sp_client_upload');
			
		if($cur_sp_client_upload < '1.2.1' ){
			$alters[] = 'ALTER TABLE `'.$wpdb->prefix . 'sp_cu` ADD `cid` INT( 11 ) NOT NULL;';
 		}	
		if($cur_sp_client_upload < '1.2.3' ){
			$alters[] =  'ALTER TABLE `'.$wpdb->prefix . 'sp_cu` ADD `tags` text NOT NULL;';
 		}
		if($cur_sp_client_upload < '1.2.7' ){
			$alters[] = "ALTER TABLE `".$wpdb->prefix ."sp_cu_project` ADD `parent` INT( 11 ) NOT NULL DEFAULT '0'";	
		}
		if($cur_sp_client_upload < '1.2.8' ){
			$alters[] = "ALTER TABLE `".$wpdb->prefix ."sp_cu_groups` ADD `locked` INT( 1 ) NOT NULL DEFAULT '0'";	
		}
		if($cur_sp_client_upload < '2.0.5' ){
			$alters[] = "ALTER TABLE `".$wpdb->prefix ."sp_cu_project` ADD `parent` INT( 11 ) NOT NULL DEFAULT '0'";	
		}
		if($cur_sp_client_upload < '2.0.6' ){
			$alters[] = "ALTER TABLE `".$wpdb->prefix ."sp_cu_project` ADD `permissions` TEXT ";	
		}
		return $alters;
	
	}
	
	function db_tables(){
global $wpdb,$sp_client_upload;
	$db_tables = array(	"".$wpdb->prefix ."sp_cu" => 
						"CREATE TABLE IF NOT EXISTS `".$wpdb->prefix ."sp_cu` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `name` varchar(255) NOT NULL,
					  `file` varchar(255) NOT NULL,
					  `notes` text NOT NULL,
					  `tags` text NOT NULL,
					  `uid` int(11) NOT NULL,
					  `cid` int(11) NOT NULL,
					  `pid` int(11) NOT NULL,
					  `parent` int(11) NOT NULL,
					  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
					  PRIMARY KEY (`id`)
						) ;",
						"".$wpdb->prefix ."sp_cu_cats" => 
						"CREATE TABLE IF NOT EXISTS `".$wpdb->prefix ."sp_cu_cats` (
					 `id` int(11) NOT NULL AUTO_INCREMENT,
					  `name` varchar(255) NOT NULL,
					  PRIMARY KEY (`id`)
						);",
						"".$wpdb->prefix ."sp_cu_forms" => 
						"CREATE TABLE IF NOT EXISTS `".$wpdb->prefix ."sp_cu_forms` (
					   `id` int(11) NOT NULL AUTO_INCREMENT,
					  `name` varchar(255) NOT NULL,
					  `template` text NOT NULL,
					  `type` varchar(255) NOT NULL,
					  `defaults` text NOT NULL,
					  `sort` int(11) NOT NULL DEFAULT '0',
					  `required` varchar(11) NOT NULL DEFAULT 'No',
					  PRIMARY KEY (`id`)
					);",
					"".$wpdb->prefix ."sp_cu_form_entries" => 
						"CREATE TABLE IF NOT EXISTS `".$wpdb->prefix ."sp_cu_form_entries` (
					   `id` int(11) NOT NULL AUTO_INCREMENT,
					  `uid` int(11) NOT NULL,
					  `fid` int(11) NOT NULL,
					  `value` varchar(255) NOT NULL,
					  `file_id` int(11) NOT NULL,
					  PRIMARY KEY (`id`)
					);",
					"".$wpdb->prefix ."sp_cu_groups" => 
						"CREATE TABLE IF NOT EXISTS `".$wpdb->prefix ."sp_cu_groups` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `name` varchar(255) NOT NULL,
					  `locked` int(1) NOT NULL DEFAULT '0',
					  PRIMARY KEY (`id`)
					);",
					"".$wpdb->prefix ."sp_cu_groups_assign" => 
						"CREATE TABLE IF NOT EXISTS `".$wpdb->prefix ."sp_cu_groups_assign` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `gid` int(11) NOT NULL,
					  `uid` int(11) NOT NULL,
					  PRIMARY KEY (`id`)
					);",
					"".$wpdb->prefix ."sp_cu_project" => 
						"CREATE TABLE IF NOT EXISTS `".$wpdb->prefix ."sp_cu_project` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `name` varchar(255) NOT NULL,
					  `uid` int(11) NOT NULL,
					  `parent` int(11) NOT NULL DEFAULT '0',
					  PRIMARY KEY (`id`)
					);"
						);
						
return $db_tables;
	}

	
	
	
	
}

?>