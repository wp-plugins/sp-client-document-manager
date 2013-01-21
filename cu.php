<?php
/*
Plugin Name: SP Client Document & Project Manager
Plugin URI: http://smartypantsplugins.com/
Description: A WordPress plug-in that allows your business to manage client files securely.
Author: Smarty
Version: 1.4.5
Author URI: http://smartypantsplugins.com
*/

global $sp_client_upload;
$sp_client_upload = "1.4.5";

load_plugin_textdomain( 'sp-cdm', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );


ini_set('upload_max_filesize', '1000M');  
ini_set('post_max_size', '1000M');  
ini_set('max_input_time', 300);  
ini_set('max_execution_time', 300); 


if(get_option('sp_cu_time_zone') != ""){
date_default_timezone_set(get_option('sp_cu_time_zone'));
}
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

include ''.dirname(__FILE__).'/admin/fileview.php';


function sp_client_upload_init() {
	

		wp_enqueue_script('jquery');
			wp_enqueue_script('smUpload', plugins_url('upload.js', __FILE__) );
			  wp_enqueue_script( 'jquery-ui-core' );
			   wp_enqueue_script( 'jquery-ui-dialog' );
			   wp_enqueue_script( 'jquery-ui-tabs' );
			   wp_enqueue_script( 'jquery-form' );
			   
			 wp_enqueue_script('smcdmvalidate', plugins_url('js/jquery.validate.js', __FILE__));

			 wp_enqueue_script( 'swfupload' );
			  wp_enqueue_script( 'swfupload-degrade' );
			   wp_enqueue_script( 'swfupload-queue' );
			    wp_enqueue_script( 'swfupload-handlers' );
			 
	
}

function sp_client_upload_load_css(){
	
	wp_register_style( 'cdm-style',plugins_url('style.css', __FILE__) );
	     wp_register_style( 'jqueruistyle', plugins_url('css/smoothness/jquery-ui-1.8.18.custom.css', __FILE__) );
       
	 wp_enqueue_style( 'cdm-style' );
	  wp_enqueue_style( 'jqueruistyle' );
	echo '<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" >';
	
}

add_action('wp_head', 'sp_client_upload_load_css');	
add_action('init', 'sp_client_upload_init');
add_action('admin_head', 'sp_client_upload_load_css');
//add_action('plugins_loaded', 'sp_client_upload_install');









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
  `tags` text NOT NULL,
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
		
		if($cur_sp_client_upload < '1.2.1' ){
			
	$wpdb->query('ALTER TABLE `'.$wpdb->prefix . 'sp_cu` ADD `cid` INT( 11 ) NOT NULL;');
 
		}	
		
			if($cur_sp_client_upload < '1.2.3' ){
			
	$wpdb->query('ALTER TABLE `'.$wpdb->prefix . 'sp_cu` ADD `tags` text NOT NULL;');
 
		}
			if($cur_sp_client_upload < '1.2.6' ){
			
	update_option('sp_cu_admin_email_subject', 'New File From Client Document Manager!');		
	update_option('sp_cu_admin_email','Dear Admin,

User [user], has uploaded a new file, here are the details.

File: [file]
Notes: [notes]
Project: [project]
category: [category]
User: [user]

Link to users profile: [user_profile]
Link to client documents [client_documents]

This email was generated by the client document manager.');
 
 
update_option('sp_cu_user_email_subject', 'Thank you for your upload');		
	update_option('sp_cu_user_email','Thanks for your upload [user]!

Here is a direct link to your file: [file]');
  
 
		}
		
		
		
	if($cur_sp_client_upload < '1.2.7' ){
		$wpdb->query("ALTER TABLE `".$wpdb->prefix ."sp_cu_project` ADD `parent` INT( 11 ) NOT NULL DEFAULT '0'");		
	}
	if($cur_sp_client_upload < '1.2.8' ){
		$wpdb->query("ALTER TABLE `".$wpdb->prefix ."sp_cu_groups` ADD `locked` INT( 1 ) NOT NULL DEFAULT '0'");		
	}
			
		
		
		
		
		update_option('sp_client_upload',$sp_client_upload);
		
	}
}


add_action('plugins_loaded', 'sp_cdm_update_db_check');


function sp_client_upload_menu() {

		$projects = new cdmProjects;
		$uploader = new sp_cdm_admin_uploader;
		$sp_cdm_fileview = new sp_cdm_fileview;
	
		  add_menu_page( 'sp_cu', 'Client Documents',  'manage_options', 'sp-client-document-manager', 'sp_client_upload_options');
		  
		  add_submenu_page( 'sp_cu', 'Vendors', 'Vendors', 'manage_options', 'sp-client-document-manager-vendors', 'sp_client_upload_options_vendors');
		  
	      add_submenu_page( 'sp_cu', 'Help', 'Help', 'manage_options', 'sp-client-document-manager-help', 'sp_client_upload_help');
		  
	   	  add_submenu_page( 'sp_cu', 'Settings', 'Settings', 'manage_options', 'sp-client-document-manager-settings', 'sp_client_upload_settings');
		  
		   add_submenu_page( 'sp_cu', 'Projects', 'Projects', 'manage_options', 'sp-client-document-manager-projects',   array(  $projects ,'view'));
		  add_submenu_page( 'sp_cu', 'Uploader', 'Uploader', 'manage_options', 'sp-client-document-manager-uploader',   array( $uploader ,'display_sp_upload_form'));
		   
	  add_submenu_page( 'sp_cu', 'User Files', 'User Files', 'manage_options', 'sp-client-document-manager-fileview',   array( $sp_cdm_fileview ,'view'));
		 
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
function sp_client_check_installed(){
	global $wpdb;
	
	
	$wpdb->query("SHOW TABLES LIKE '".$wpdb->prefix . "sp_cu'");
if($wpdb->num_rows != 1) {
 sp_client_upload_install();
 sp_cdm_update_db_check();
}
	
}

sp_client_check_installed();
?>