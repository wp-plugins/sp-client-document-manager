<?php
/*
Plugin Name: Smarty Pants Client Document Manager
Plugin URI: http://smartypantsplugins.com/
Description: A WordPress plug-in that allows your business to manage client files securely.
Author: Smarty
Version: 1.0.4
Author URI: http://smartypantsplugins.com
*/

global $sp_client_upload;
$sp_client_upload = "1.0.4";

add_action('admin_menu', 'sp_client_upload_menu');

include 'common.php';
if(file_exists(ABSPATH.'wp-content/plugins/sp-client-document-manager/premium/index.php')){
  include(ABSPATH.'wp-content/plugins/sp-client-document-manager/premium/index.php');
  $cu_ver =  'Premium version';
}else{
	 $cu_ver = 'Free version';
}


include 'zip.class.php';

include 'admin/vendors.php';

include 'functions.php';
include 'shortcode.php';




function sp_client_upload_init() {
	if (!is_admin()) {

		wp_enqueue_script('jquery');
			wp_enqueue_script('smUpload', ''.get_bloginfo('wpurl').'/wp-content/plugins/sp-client-document-manager/upload.js');
			  wp_enqueue_script( 'jquery-ui-core' );
			   wp_enqueue_script( 'jquery-ui-dialog' );
			   wp_enqueue_script( 'jquery-ui-tabs' );
			   wp_enqueue_script( 'jquery-form' );
	}
}

function sp_client_upload_load_css(){
	if (!is_admin()) {
echo '<link type="text/css" rel="stylesheet" href="' . get_bloginfo('wpurl') . '/wp-content/plugins/sp-client-document-manager/style.css" />
<link type="text/css" rel="stylesheet" href="' . get_bloginfo('wpurl') . '/wp-content/plugins/sp-client-document-manager/css/smoothness/jquery-ui-1.8.18.custom.css" />

';

	}
}

add_action('wp_head', 'sp_client_upload_load_css');	
add_action('init', 'sp_client_upload_init');



function sp_client_upload_install() {
   global $wpdb;
   global $sp_portfolio_version;

	 $table_name = $wpdb->prefix . "sp_cu";
 	  $project_table_name = $wpdb->prefix . "sp_cu_project";  
	   $project_cat_name = $wpdb->prefix . "sp_cu_cats"; 
	
	 $sql = 'CREATE TABLE  `'.$table_name.'` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `file` varchar(255) NOT NULL,
  `notes` text NOT NULL,
  `uid` int(11) NOT NULL,
  `cid` int(11) NOT NULL,
  `pid` int(11) NOT NULL,
   `parent` int(11) NOT NULL,
  `date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ;';

$sql2 = 'CREATE TABLE  `'.$project_table_name .'` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `uid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
);';
  
$sql3 = 'CREATE TABLE IF NOT EXISTS `'.$project_cat_name.'` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ;
';
   


   require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
  dbDelta($sql);
  dbDelta($sql2); 
  dbDelta($sql3);   
  
   	$dir = ''.ABSPATH.'wp-content/uploads/sp-client-document-manager/';
	
	if(!is_dir(''.ABSPATH.'wp-content/uploads/')){
	
		@mkdir(''.ABSPATH.'wp-content/uploads/', 0777);
	}
	
	if(!is_dir($dir)){
	
		@mkdir($dir, 0777);
	}
if ( get_option( 'sp_client_upload_version') == '') {
  add_option("sp_client_upload_version", $sp_client_upload);
  add_option("sp_client_upload_page", 'Please enter the page');

}


$updatesql = $wpdb->query('ALTER TABLE `'.$wpdb->prefix.'sp_cu` ADD `pid` INT( 11 ) NOT NULL; ');



}
register_activation_hook(__FILE__,'sp_client_upload_install');







function sp_cdm_update_db_check() {
    global $sp_client_upload,$wpdb;
	
    if (get_site_option('sp_client_upload') != $sp_client_upload) {        
		
		$cur_sp_client_upload = get_site_option('sp_client_upload');
		
		//upgrade 1.0.2
		if($cur_sp_client_upload == '1.0.0' or $cur_sp_client_upload == '1.0.1' or $cur_sp_client_upload == '1.0.2'){
			
			$wpdb->query('ALTER TABLE `'.$wpdb->prefix . 'sp_cu` ADD `cid` INT( 11 ) NOT NULL;');
			
			update_option('sp_client_upload',$sp_client_upload);
		}
		
	}
}


add_action('plugins_loaded', 'sp_cdm_update_db_check');


function sp_client_upload_menu() {






  add_menu_page( 'sp_cu', 'Client Documents',  'manage_options', 'sp-client-document-manager', 'sp_client_upload_options');
	  add_submenu_page( 'sp_cu', 'Vendors', 'Vendors', 'manage_options', 'sp-client-document-manager-vendors', 'sp_client_upload_options_vendors');
	   add_submenu_page( 'sp_cu', 'Help', 'Help', 'manage_options', 'sp-client-document-manager-help', 'sp_client_upload_help');
	   
		 add_submenu_page( 'sp_cu', 'Settings', 'Settings', 'manage_options', 'sp-client-document-manager-settings', 'sp_client_upload_settings');
		 if (CU_PREMIUM == 1){
		 add_submenu_page( 'sp_cu', 'Projects', 'Projects', 'manage_options', 'sp-client-document-manager-projects', 'sp_client_upload_projects');
		 add_submenu_page( 'sp_cu', 'Categories', 'Categories', 'manage_options', 'sp-client-document-manager-categories', 'sp_client_upload_cat_view');
		 }
		 
		 
}

function sp_client_upload_options(){
	global $wpdb;
	
if($_POST['sp-client-document-manager-submit'] != ""){
	
	update_option('sp_client_upload_page', $_POST['sp_client_upload_page']);
	echo '<p style="color:green">Updated Settings!</p>';
}



echo  '


';




echo '
				
			<h2>Latest uploads</h2>'.sp_client_upload_nav_menu().'
				'. sp_client_upload_admin().'
				';







}

?>