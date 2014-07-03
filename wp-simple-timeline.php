<?php
/*
Plugin Name: Simple Timeline
Version: 0.3
Plugin URI: 
Author: Kuma Xu
Author URI: http://www.weiyangx.com
Description: 简单的时间轴创建和展示插件。
*/
if ( ! defined( 'ABSPATH' ) ) exit;
define('STL_url', WP_PLUGIN_URL."/".dirname( plugin_basename( __FILE__ ) ) );
define('STL_path', WP_PLUGIN_DIR."/".dirname( plugin_basename( __FILE__ ) ) );

global $STL_db_version;
$STL_db_version = "0.3";

function STL_install() {
	global $wpdb;
	global $STL_db_version;
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
   
	// Install tables
	$sql = "CREATE TABLE " . $wpdb->prefix . "stl_node (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		node_content text NOT NULL,
		node_date int(10) NOT NULL,
		node_status int(1) NOT NULL,
		UNIQUE KEY id (id)
		) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci;";
	dbDelta($sql);
 
   add_option("STL_db_version", $STL_db_version);
   
    $installed_ver = get_option( "stl_db_version" );

   if ($installed_ver != $STL_db_version) {

      $sql = "CREATE TABLE " . $wpdb->prefix . "STL_node (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		node_content text NOT NULL,
		node_date int(10) NOT NULL,
		node_status int(1) NOT NULL,
		UNIQUE KEY id (id)
		) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci;";
      dbDelta($sql);

      update_option( "STL_db_version", $STL_db_version );
  }
}

function STL_uninstall() {
	global $wpdb;
   
	// Remove tables
	$wpdb->query($wpdb->prepare("DROP TABLE IF EXISTS " . $wpdb->prefix . "STL_node",''));
 	
	// Remove option
   delete_option("STL_db_version");
}


register_activation_hook( __FILE__, 'STL_install' );
register_uninstall_hook( __FILE__, 'STL_uninstall' );

function STL_update_db_check() {
    global $STL_db_version;
    if (get_site_option('STL_db_version') != $STL_db_version) {
        STL_install();
    }
}

add_action('STL_loaded', 'STL_update_db_check');

function STL_styles()  
	{ 
	   
	    wp_register_style( "STL_styles",  STL_url . '/css/style.css' , "", "1.0.0");
	    wp_enqueue_style( 'STL_styles' );
	}
add_action('wp_enqueue_scripts', 'STL_styles');

function STL_admin() {   
	include('STL_node_admin.php');
}

function STL_admin_add() {   
	include('STL_node_add.php');
}

function STL_admin_edit() {   
	include('STL_node_edit.php');
}

function STL_admin_actions() {
	add_menu_page("时间轴管理", "时间轴管理", "level_10", "STL_admin", "STL_admin", NULL );
	add_submenu_page( "STL_admin", "添加时间轴", "添加时间轴", "level_10", "STL_admin_add", "STL_admin_add" );
	add_submenu_page( "STL_admin", "修改时间轴", "修改时间轴", "level_10", "STL_admin_edit", "STL_admin_edit" );
}

add_action('admin_menu', 'STL_admin_actions');

function STL_show_timeline() {
	global $wpdb;
	$output = '<ul class="timeline">';
	$nodes = $wpdb->get_results($wpdb->prepare("SELECT node_content, node_date FROM ".$wpdb->prefix."stl_node WHERE node_status = %d ORDER BY node_date DESC",'1'));
	$count = $wpdb->get_var($wpdb->prepare("SELECT count(id) FROM ".$wpdb->prefix."stl_node WHERE node_status = %d",'1'));
	if($nodes){
		foreach($nodes as $node){
			$date = date('Y-m-d',$node->node_date);
			$content = apply_filters('the_content', $node->node_content);
			$output .= '<li><div class="time">'.$date.'</div>';
			$output .= '<div class="version"></div>';
			$output .= '<div class="number">'.$count.'</div>';
			$output .= '<div class="content"><pre>'.$content.'<div class="clearfix"></div></pre></div></li>';
			$count--;
		}
	}
	else{
		$output .= '<span class="alert">没有时间轴内容被找到。</span>';
	}
	$output .='</ul>';
	return $output;
}
add_shortcode( 'stl-show-timeline', 'STL_show_timeline' );

//Ajax Callback Functions
function stl_ajax_del_node() {
	if(isset($_POST['data']))
	{
		$id = intval($_POST['data']);
		global $wpdb;
		if($wpdb->query($wpdb->prepare("DELETE FROM ".$wpdb->prefix."stl_node WHERE id = %d",$id)))
		{
			echo '1';
		}
		else {
			echo '0';
		}
		
	}
    die();
}

add_action('wp_ajax_stl_ajax_del_node', 'stl_ajax_del_node');
?>