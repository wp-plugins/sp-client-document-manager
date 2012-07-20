<?php
/*
Plugin Name: SP Client Document & Project Manager
Plugin URI: http://smartypantsplugins.com/
Description: A WordPress plug-in that allows your business to manage client files securely.
Author: Smarty
Version: 1.2.0
Author URI: http://smartypantsplugins.com
*/

global $sp_client_upload;
$sp_client_upload = "1.2.0";

load_plugin_textdomain( 'sp-cdm', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );


ini_set('upload_max_filesize', '1000M');  
ini_set('post_max_size', '1000M');  
ini_set('max_input_time', 300);  
ini_set('max_execution_time', 300); 

add_action('admin_menu', 'sp_client_upload_menu');


add_filter('wp_head','sp_cdm_tinymce_editor');

 function sp_cdm_tinymce_editor(){
  wp_admin_css('thickbox');
  wp_enqueue_script('post');
  wp_enqueue_script('media-upload');
  wp_enqueue_script('jquery');
  wp_enqueue_script('jquery-ui-core');
  wp_enqueue_script('jquery-ui-tabs');
  wp_enqueue_script('tiny_mce');
  wp_enqueue_script('editor');
  wp_enqueue_script('editor-functions');
  add_thickbox();
 }



include ''.dirname(__FILE__).'/common.php';
if(file_exists(ABSPATH.'wp-content/plugins/sp-client-document-manager/premium/index.php')){
  include(ABSPATH.'wp-content/plugins/sp-client-document-manager/premium/index.php');
  $cu_ver =  'Premium version';
}else{
	 $cu_ver = 'Free version';
}


include ''.dirname(__FILE__).'/zip.class.php';

include ''.dirname(__FILE__).'/admin/vendors.php';
include ''.dirname(__FILE__).'/admin/projects.php';
include ''.dirname(__FILE__).'/admin/uploader.php';
include ''.dirname(__FILE__).'/user/projects.php';
include ''.dirname(__FILE__).'/functions.php';
include ''.dirname(__FILE__).'/shortcode.php';




function sp_client_upload_init() {
	

		wp_enqueue_script('jquery');
			wp_enqueue_script('smUpload', plugins_url('upload.js', __FILE__) );
			  wp_enqueue_script( 'jquery-ui-core' );
			   wp_enqueue_script( 'jquery-ui-dialog' );
			   wp_enqueue_script( 'jquery-ui-tabs' );
			   wp_enqueue_script( 'jquery-form' );
			 wp_enqueue_script('smcdmvalidate', plugins_url('js/jquery.validate.js', __FILE__));
	
}

function sp_client_upload_load_css(){
	
	wp_register_style( 'cdm-style',plugins_url('style.css', __FILE__) );
	     wp_register_style( 'jqueruistyle', plugins_url('css/smoothness/jquery-ui-1.8.18.custom.css', __FILE__) );
       
	
	 wp_enqueue_style( 'cdm-style' );
	  wp_enqueue_style( 'jqueruistyle' );

	
}

add_action('wp_head', 'sp_client_upload_load_css');	
add_action('init', 'sp_client_upload_init');
add_action('admin_head', 'sp_client_upload_load_css');

function sp_client_upload_install() {
   global $wpdb;
   global  $sp_client_upload;

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
if ( get_option( 'sp_client_upload') == '') {
  add_option("sp_client_upload", $sp_client_upload);
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
		if($cur_sp_client_upload == '1.0.0' or $cur_sp_client_upload == '1.0.1' or $cur_sp_client_upload == '1.0.2' or $cur_sp_client_upload == '1.0.3' or $cur_sp_client_upload == '1.0.4' ){
			
			$wpdb->query('ALTER TABLE `'.$wpdb->prefix . 'sp_cu` ADD `cid` INT( 11 ) NOT NULL;');
			
			
		}
		
		
		if($cur_sp_client_upload < '1.1.3' ){
			$sql1 = '
CREATE TABLE  `'.$wpdb->prefix.'sp_cu_forms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `template` text NOT NULL,
  `type` varchar(255) NOT NULL,
  `defaults` text NOT NULL,
  `sort` int(11) NOT NULL DEFAULT "0",
  `required` varchar(11) NOT NULL DEFAULT "No",
  PRIMARY KEY (`id`)
) ;';

$sql2 = 'CREATE TABLE IF NOT EXISTS `'.$wpdb->prefix.'sp_cu_form_entries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `fid` int(11) NOT NULL,
  `value` varchar(255) NOT NULL,
  `file_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ';

  require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
  dbDelta($sql1);
  dbDelta($sql2); 

	}
	
	
	if($cur_sp_client_upload < '1.1.7' ){
			$sql3 = '
CREATE TABLE IF NOT EXISTS `'.$wpdb->prefix.'sp_cu_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
);';

$sql4 = 'CREATE TABLE IF NOT EXISTS `'.$wpdb->prefix.'sp_cu_groups_assign` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ;';

  require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
  dbDelta($sql3);
  dbDelta($sql4);
		}
		
		
		update_option('sp_client_upload',$sp_client_upload);
		
	}
}


add_action('plugins_loaded', 'sp_cdm_update_db_check');


function sp_client_upload_menu() {

		$projects = new cdmProjects;
		$uploader = new sp_cdm_admin_uploader;
	
		  add_menu_page( 'sp_cu', 'Client Documents',  'manage_options', 'sp-client-document-manager', 'sp_client_upload_options');
		  
		  add_submenu_page( 'sp_cu', 'Vendors', 'Vendors', 'manage_options', 'sp-client-document-manager-vendors', 'sp_client_upload_options_vendors');
		  
	      add_submenu_page( 'sp_cu', 'Help', 'Help', 'manage_options', 'sp-client-document-manager-help', 'sp_client_upload_help');
		  
	   	  add_submenu_page( 'sp_cu', 'Settings', 'Settings', 'manage_options', 'sp-client-document-manager-settings', 'sp_client_upload_settings');
		  
		   add_submenu_page( 'sp_cu', 'Projects', 'Projects', 'manage_options', 'sp-client-document-manager-projects',   array(  $projects ,'view'));
		    add_submenu_page( 'sp_cu', 'Uploader', 'Uploader', 'manage_options', 'sp-client-document-manager-uploader',   array( $uploader ,'display_sp_upload_form'));
		   
		 if (CU_PREMIUM == 1){
			 	if(class_exists('cdmForms')){
		$forms = new cdmForms;
		$groups = new cdmGroups;
		}
		  add_submenu_page( 'sp_cu', 'Forms', 'Forms', 'manage_options', 'sp-client-document-manager-forms',   array(  $forms ,'view'));
		    add_submenu_page( 'sp_cu', 'Groups', 'Groups', 'manage_options', 'sp-client-document-manager-groups',   array(  $groups ,'view'));
		 add_submenu_page( 'sp_cu', 'Categories', 'Categories', 'manage_options', 'sp-client-document-manager-categories', 'sp_client_upload_cat_view');
		 }
		 
		 
}

function sp_client_upload_options(){
	global $wpdb;
	
if($_POST['sp-client-document-manager-submit'] != ""){
	
	update_option('sp_client_upload_page', $_POST['sp_client_upload_page']);
	echo '<p style="color:green">'.__("Updated Settings!","sp-cdm").'</p>';
}
echo '<h2>'.__("Latest uploads","sp-cdm").'</h2>'.sp_client_upload_nav_menu().'
				'. sp_client_upload_admin().'';
}

?>