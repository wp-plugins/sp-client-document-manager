<?php
/*
Plugin Name: SP Project & Document Manager
Plugin URI: http://smartypantsplugins.com/
Description: A WordPress plug-in that allows your business manage documents and projects with permissions in an easy to use interface.
Author: smartypants
Version: 2.5.7.7
Author URI: http://smartypantsplugins.com
*/
global $sp_client_upload;
$sp_client_upload = "2.5.7.7";
function sp_cdm_language_init()
{
    load_plugin_textdomain('sp-cdm', false, dirname(plugin_basename(__FILE__)) . '/languages/');
}
add_action('init', 'sp_cdm_language_init');
if (get_option('sp_cu_limit_file_size') == "") {
    ini_set('upload_max_filesize', '2000M');
    ini_set('post_max_size', '2000M');
    ini_set('max_input_time', 300);
    ini_set('max_execution_time', 300);
} else {
    ini_set('upload_max_filesize', '' . get_option('sp_cu_limit_file_size') . 'M');
    ini_set('post_max_size', '' . get_option('sp_cu_limit_file_size') . 'M');
    ini_set('max_input_time', 300);
    ini_set('max_execution_time', 300);
}
$cdm_upload_dir = wp_upload_dir();
define('SP_CDM_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('SP_CDM_PLUGIN_URL', plugins_url() . '/sp-client-document-manager/');
if (get_option('sp_cu_overide_upload_path') != "") {
    define('SP_CDM_UPLOADS_DIR', stripslashes(get_option('sp_cu_overide_upload_path')));
    define('SP_CDM_UPLOADS_DIR_URL', stripslashes(get_option('sp_cu_overide_upload_url')));
} else {
    define('SP_CDM_UPLOADS_DIR', $cdm_upload_dir['basedir'] . '/sp-client-document-manager/');
    define('SP_CDM_UPLOADS_DIR_URL', $cdm_upload_dir['baseurl'] . '/sp-client-document-manager/');
}
add_action('admin_menu', 'sp_client_upload_menu');
add_filter('wp_head', 'sp_cdm_tinymce_editor');
function sp_cdm_tinymce_editor()
{
    wp_admin_css('thickbox');
    wp_enqueue_script('post');
    wp_enqueue_script('media-upload');
    wp_enqueue_script('jquery');
    wp_enqueue_script('jquery-ui-core');
    wp_enqueue_script('jquery-ui-tabs');
	
	
	wp_enqueue_script('jquery-effects-core');
	wp_enqueue_script('jquery-effects-pulsate');
	wp_enqueue_script('jquery-effects-highlight');
		 
    wp_enqueue_script('tiny_mce');
    wp_enqueue_script('editor');
    wp_enqueue_script('editor-functions');
    add_thickbox();
}

require_once '' . dirname(__FILE__) . '/admin/logs.php';
require_once '' . dirname(__FILE__) . '/classes/uploader.php';
require_once '' . dirname(__FILE__) . '/classes/install.php';
if(!function_exists('bfi_thumb')){
require_once '' . dirname(__FILE__) . '/classes/mat.thumb.php';
}
require_once '' . dirname(__FILE__) . '/classes/ajax.php';
require_once '' . dirname(__FILE__) . '/common.php';
require_once '' . dirname(__FILE__) . '/zip.class.php';
require_once '' . dirname(__FILE__) . '/admin/vendors.php';
require_once '' . dirname(__FILE__) . '/admin/projects.php';
require_once '' . dirname(__FILE__) . '/user/projects.php';
require_once '' . dirname(__FILE__) . '/functions.php';
require_once '' . dirname(__FILE__) . '/shortcode.php';
require_once '' . dirname(__FILE__) . '/admin/fileview.php';
require_once '' . dirname(__FILE__) . '/admin/settings.php';

function sp_js_vars(){
		
		$vars = array('ajax_url' =>	SP_CDM_PLUGIN_URL.'ajax.php');
		$vars = apply_filters('sp_cdm/javascript_vars',$vars);
		return $vars;	
}
function sp_client_upload_init()
{
    wp_enqueue_script('jquery');
 
	wp_register_script( 'smUpload', plugins_url('upload.js', __FILE__),array('jquery','jquery-ui-core','jquery-ui-dialog','jquery-ui-button','jquery-cookie'));
	wp_localize_script( 'smUpload', 'sp_vars',sp_js_vars() );
	wp_enqueue_script( 'smUpload' );

    wp_enqueue_script('jquery-ui-core');
    wp_enqueue_script('jquery-ui-dialog');
    wp_enqueue_script('jquery-ui-tabs');
	wp_enqueue_script('jquery-ui-button');
	
    wp_enqueue_script('jquery-form');
    wp_enqueue_script('smcdmvalidate', plugins_url('js/jquery.validate.js', __FILE__));
    wp_enqueue_script('swfupload');
    wp_enqueue_script('swfupload-degrade');
    wp_enqueue_script('swfupload-queue');
    wp_enqueue_script('swfupload-handlers');
    wp_enqueue_script('jquery-cookie', plugins_url('js/jquery.cookie.js', __FILE__), array(
        'jquery'
    ));
	wp_enqueue_script('jquery-dialog-responsive', plugins_url('js/jquery.modal.responsive.js', __FILE__), array(
        'jquery',
		'jquery-ui-dialog',
		'jquery-cookie'
    ));

}
function sp_client_upload_load_css()
{
	global $sp_client_upload;
  
    if (get_option('sp_cu_jqueryui_theme') != 'none') {
       
        wp_register_style('jquery-ui-css', '//ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.min.css');
		wp_enqueue_style('jquery-ui-css');
    }
	
wp_enqueue_style('cdm-style', plugins_url('style.css', __FILE__), array(), $sp_client_upload);

    
    //echo '<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" >';
}



function sp_client_upload_load_admin_css()
{
    wp_register_style('cdm-potatoe-menu', plugins_url('css/menu.css', __FILE__));
    wp_enqueue_style('cdm-potatoe-menu');
    //echo '<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" >';
}
function sp_client_upload_admin_init()
{
    wp_enqueue_script('cdm-potatoe-menu-js', plugins_url('js/menu.js', __FILE__));
	
	
	wp_enqueue_script('jquery');
    	wp_register_script( 'smUpload', plugins_url('upload.js', __FILE__),array('jquery','jquery-ui-core','jquery-ui-dialog','jquery-ui-button','jquery-cookie'));
	wp_localize_script( 'smUpload', 'sp_vars',sp_js_vars() );
	wp_enqueue_script( 'smUpload' );

    wp_enqueue_script('jquery-ui-core');
    wp_enqueue_script('jquery-ui-dialog');
    wp_enqueue_script('jquery-ui-tabs');
	wp_enqueue_script('jquery-ui-button');
	
	  wp_enqueue_script('jquery-cookie', plugins_url('js/jquery.cookie.js', __FILE__), array(
        'jquery'
    ));
}
add_action('wp_head', 'sp_client_upload_load_css');
add_action('init', 'sp_client_upload_init');
add_action('admin_menu', 'sp_client_upload_load_css');
add_action('admin_menu', 'sp_client_upload_load_admin_css');
add_action('admin_init', 'sp_client_upload_admin_init');
function sp_client_upload_menu()
{
    $projects        = new cdmProjects;
    $sp_cdm_fileview = new sp_cdm_fileview;
    add_menu_page('Documents', 'Documents', 'sp_cdm', 'sp-client-document-manager', 'sp_client_upload_options','dashicons-media-default');
    add_submenu_page('sp-client-document-manager', sp_cdm_folder_name(1), sp_cdm_folder_name(1), 'sp_cdm_projects', 'sp-client-document-manager-projects', array(
        $projects,
        'view'
    ));
	add_submenu_page('sp-client-document-manager', 'Vendors', 'Vendors', 'sp_cdm_vendors', 'sp-client-document-manager-vendors', 'sp_client_upload_options_vendors');
    add_submenu_page('sp-client-document-manager', 'Help', 'Help', 'sp_cdm_help', 'sp-client-document-manager-help', 'sp_client_upload_help');
    add_submenu_page('sp-client-document-manager', 'Settings', 'Settings', 'sp_cdm_settings', 'sp-client-document-manager-settings', 'sp_client_upload_settings');
    
    add_submenu_page('sp-client-document-manager', 'User Files', 'User Files', 'sp_cdm_uploader', 'sp-client-document-manager-fileview', array(
        $sp_cdm_fileview,
        'view'
    ));
	do_action('sp_cu_admin_menu');
	
	 if (current_user_can('sp_cdm_show_folders_as_nav')) {
	add_menu_page('Folders', 'Folders', 'sp_cdm_show_folders_as_nav', 'sp-client-document-manager-projects',array(
        $projects,
        'view'
    ),'dashicons-portfolio');	 
	 }
	
}
function sp_client_upload_options()
{
    global $wpdb;
    if (@$_POST['sp-client-document-manager-submit'] != "") {
        update_option('sp_client_upload_page', $_POST['sp_client_upload_page']);
        echo '<p style="color:green">' . __("Updated Settings!", "sp-cdm") . '</p>';
    }
    echo '<h2>' . __("Latest uploads", "sp-cdm") . '</h2>' . sp_client_upload_nav_menu() . '
				' . sp_client_upload_admin() . '';
}
?>