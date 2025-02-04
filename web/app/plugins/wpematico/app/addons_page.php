<?php
// don't load directly 
if ( !defined('ABSPATH') ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

/**
 *  PLUGINS PAGES ADDONS
 *  Experimental.  Uses worpdress plugins.php file filtered
 */

function wpematico_get_addons_update() {
	$wpematico_updates = 0; // Cache received responses.
	$plugin_updates = get_site_transient('update_plugins');
	if ($plugin_updates === false) {
		$plugin_updates = new stdClass();
	}

	if (!isset($plugin_updates->response)) {
		$plugin_updates->response = array();
	}
	foreach ($plugin_updates->response as $r_plugin => $value) {
		if(strpos($r_plugin, 'wpematico_') !== false) {
			$wpematico_updates++;
		}
	}
	return $wpematico_updates;
}


add_action( 'admin_init', 'redirect_to_wpemaddons',0  );
function redirect_to_wpemaddons() {
	global $pagenow;
	$getpage = (isset($_REQUEST['page']) && !empty($_REQUEST['page']) ) ? $_REQUEST['page'] : '';
	if ($pagenow != 'admin-ajax.php' || $getpage == 'wpemaddons')
	if ($pagenow == 'plugins.php' && ($getpage=='')  ){
		$plugin = isset($_REQUEST['plugin']) ? $_REQUEST['plugin'] : '';
		$s = isset($_REQUEST['s']) ? urlencode($_REQUEST['s']) : '';

		$location = '';

		$actioned = array_multi_key_exists( array('error', 'deleted', 'activate', 'activate-multi', 'deactivate', 'deactivate-multi', '_error_nonce' ), $_REQUEST, false );
		if( ( isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'page=wpemaddons') ) && $actioned ) {
			$location = add_query_arg('page','wpemaddons', $location);// $_SERVER['REQUEST_URI'];
			if (!headers_sent()) {
				wp_redirect($location);
			}
			
		}
	}
}
function wpe_include_plugins() {
	global $user_ID;
	$user_ID = get_current_user_id();
	if (!defined('WPEM_ADMIN_DIR')) {
		define('WPEM_ADMIN_DIR' , ABSPATH . basename(admin_url()));
	}
	$status ='all'; 
	$page=  (!isset($page) or is_null($page))? 1 : $page;
	require WPEM_ADMIN_DIR . '/plugins.php';
			
}

add_action( 'admin_menu', 'wpe_addon_admin_menu',99 );
function wpe_addon_admin_menu() {

	if (!empty($_REQUEST['verify-delete'])) {
			wpe_include_plugins();
			return false;	
	}
	if (!empty($_REQUEST['action']) && $_REQUEST['action'] == 'delete-selected') {
		$plugins = isset( $_REQUEST['checked'] ) ? (array) wp_unslash( $_REQUEST['checked'] ) : array();
		$plugins = array_filter($plugins, 'is_plugin_inactive'); // Do not allow to delete Activated plugins.
		if (empty( $plugins ) ) {
			wpe_include_plugins();
			return false;	
		}
	}
	$update_wpematico_addons = wpematico_get_addons_update();
	$count_menu = '';
	if (!empty($update_wpematico_addons) && $update_wpematico_addons > 0) {
		$count_menu = "<span class='update-plugins count-{$update_wpematico_addons}' style='position: absolute;	margin-left: 5px;'><span class='plugin-count'>" . number_format_i18n($update_wpematico_addons) . "</span></span>";
	}
	
	$page = add_submenu_page(
		'plugins.php',
		__( 'Add-ons', 'wpematico' ),
		__( 'WPeMatico Add-ons', 'wpematico' ).' '.$count_menu,
		'manage_options',
		'wpemaddons',
		'add_admin_plugins_page'
	);
	add_action( 'admin_print_scripts-' . $page, 'WPeAddon_admin_scripts' );
	$page = add_submenu_page(
		'edit.php?post_type=wpematico',
		__( 'Add-ons', 'wpematico' ),
		__( 'Extensions', 'wpematico' ).' '.$count_menu,
		'manage_options',
		'plugins.php?page=wpemaddons'
	);

}


add_action( 'admin_head', 'WPeAddon_admin_head' );
function WPeAddon_admin_scripts() {
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'plupload-all' );
	wp_enqueue_style( 'plugin-install' );
	wp_enqueue_script( 'plugin-install' );
	add_thickbox();
	wp_enqueue_script( 'wpematico-update', WPeMatico::$uri . 'app/js/wpematico_updates.js', array( 'jquery', 'inline-edit-post' ), WPEMATICO_VERSION, true );
}


function WPeAddon_admin_head(){
	global $pagenow, $page_hook;
	if($pagenow=='plugins.php' && $page_hook=='plugins_page_wpemaddons'){
	?>
	<script type="text/javascript">
		jQuery(document).ready(function($){
			$('.wrap h1').html('WPeMatico Add-Ons Plugins');
			var $all= $('.subsubsub .all a').attr('href');
			var $act= $('.subsubsub .active a').attr('href');
			var $ina= $('.subsubsub .inactive a').attr('href');
			var $rec= $('.subsubsub .recently_activated a').attr('href');
			var $upg= $('.subsubsub .upgrade a').attr('href');
			$('.subsubsub .all a').attr('href',$all+'&page=wpemaddons');
			$('.subsubsub .active a').attr('href',$act+'&page=wpemaddons');
			$('.subsubsub .inactive a').attr('href',$ina+'&page=wpemaddons');
			$('.subsubsub .recently_activated a').attr('href',$rec+'&page=wpemaddons');
			$('.subsubsub .upgrade a').attr('href',$upg+'&page=wpemaddons');
		});
	</script>
	<style type="text/css">
		@media screen and (max-width: 782px) {
			.plugins_page_wpemaddons .column-name{
			      display: none;
			}
		}
	</style>
	<?php 
	}
}

add_action( 'admin_init', 'activate_desactivate_plugins',0  );
function activate_desactivate_plugins() {
	global $plugins, $status, $wp_list_table;
	if (!defined('WPEM_ADMIN_DIR')) {
		define('WPEM_ADMIN_DIR' , ABSPATH . basename(admin_url()));
	}
	$accepted_actions = array();
	$accepted_actions[] = 'deactivate-selected';
	$accepted_actions[] = 'activate-selected';

	if (isset($_REQUEST['checked']) && isset($_REQUEST['page']) && $_REQUEST['page'] == 'wpemaddons' && in_array($_REQUEST['action'], $accepted_actions) !== false){
		$status ='all'; 
		$page=  (!isset($page) or is_null($page))? 1 : $page;
		$plugins['all']=get_plugins();
		
		require WPEM_ADMIN_DIR . '/plugins.php' ;
		exit;
	}
	
}

function add_admin_plugins_page() {
	if (!defined('WPEM_ADMIN_DIR')) {
		define('WPEM_ADMIN_DIR' , ABSPATH . basename(admin_url()));
	}
	
	if ( ! class_exists( 'WP_List_Table' ) ) {
		require_once WPEM_ADMIN_DIR . '/includes/class-wp-list-table.php';
	}
	
	if ( ! class_exists( 'WP_Plugins_List_Table' ) ) {
		require WPEM_ADMIN_DIR .'/includes/class-wp-plugins-list-table.php';
	}
	
	global $plugins, $status, $wp_list_table;
	$status ='all'; 
	$page=  (!isset($page) or is_null($page))? 1 : $page;
	$plugins['all']=get_plugins();
	wp_update_plugins();
	wp_clean_plugins_cache(false);
	require WPEM_ADMIN_DIR . '/plugins.php' ;
	exit;

}


add_filter( "manage_plugins_page_wpemaddons_columns", 'wpematico_addons_get_columns' );
function wpematico_addons_get_columns() {
	global $status;

	return array(
		'cb'          => !in_array( $status, array( 'mustuse', 'dropins' ) ) ? '<input type="checkbox" />' : '',
		'icon'        => __( 'Icon' ),
		'name'        => __( 'Add On' ),
		'description' => __( 'Description' ),
		'buybutton' => __( 'Adquire' ),
	);
}

add_action('manage_plugins_custom_column', 'wpematico_addons_custom_columns',10,3);
function wpematico_addons_custom_columns($column_name, $plugin_file, $plugin_data ) {
	// Return if don't have the wpematico word in its name or uri
	if (strpos($plugin_data['Name'], 'WPeMatico ') === false && strpos($plugin_data['PluginURI'], 'wpematico') === false ) {
		return true;
	}
	// Get the addon from the transient saved before
	$addons = get_transient('etruel_wpematico_addons_data');
	foreach($addons as $value) { 
		$plugin_data_uri = strstr( $plugin_data['PluginURI'], '://');
		$addon_data_uri = strstr( $value['PluginURI'], '://');
		if( ($plugin_data['Name'] == $value['Name']) or ($plugin_data_uri == $addon_data_uri) ) {
			$addon = $value;
		}					
	}
	switch($column_name) {
		case 'icon':
			if(isset($addon['icon'])) {
				echo $addon['icon'];
			}
			break;

		case 'buybutton':
			$caption = ( (isset($plugin_data['installed']) && ($plugin_data['installed']) ) || !isset($plugin_data['Remote'])) ? __('Installed','wpematico') : __('Purchase', 'wpematico');
			if (isset($plugin_data['installed']) && ($plugin_data['installed']) ) {
				if(!isset($plugin_data['Remote'])) {
					$caption = __('Installed','wpematico');
					$title = __('See details and prices on etruel\'s store','wpematico');
					$url   = 'https'.strstr( $plugin_data['PluginURI'], '://');
		//		}else{
		//			$caption = __('Buy', 'wpematico');
				}
			}else{
				if(!isset($plugin_data['Remote'])) {
					$caption = __('Locally','wpematico');
					$title = __('Go to plugin URI','wpematico');
					$url   = '#'.$plugin_data['Name'];
				}else{
					$caption = __('Purchase', 'wpematico'); //**** 
					$title = __('Go to purchase on the etruel\'s store','wpematico');
					$url   = 'https'.strstr( $plugin_data['buynowURI'], '://');
				}
			}

			$target = ( $caption == __('Locally','wpematico' ) ) ? '_self' : '_blank';
			$class = ( $caption == __('Purchase','wpematico' ) ) ? 'button-primary' : '';
			//echo '<a target="_Blank" class="button '.$class.'" title="'.$title.'" href="https'.strstr( $plugin_data['PluginURI'], '://').'">' . $caption . '</a>';
			echo '<a target="'.$target.'" class="button '.$class.'" title="'.$title.'" href="'.$url.'">' . $caption . '</a>';
			break;

		default:
			break;
	}
	return true;
}
	
add_filter( 'all_plugins', 'wpematico_showhide_addons');
function wpematico_showhide_addons($plugins) {
	global $current_screen;
	if (function_exists('wp_plugin_update_rows')) {
		wp_plugin_update_rows();
	}
	$show_on_plugin_page = get_option('wpem_show_locally_addons', false); 
	if ($current_screen->id == 'plugins_page_wpemaddons'){
		$plugins = apply_filters( 'etruel_wpematico_addons_array', read_wpem_addons($plugins), 10, 1 );
		foreach ($plugins as $key => $value) {
			if(strpos($key, 'wpematico_')===FALSE) {		
				unset( $plugins[$key] );
			}else{
				if(isset($plugins[$key]['Remote'])){
					add_filter( "plugin_action_links_{$key}", 'wpematico_addons_row_actions',15,4);					
				}
			}
		}		
	}else{
		/*
		** If wpem_show_locally_addons option is checked not will be filtered Add Ons WPeMatico. 
		*/
		if (!$show_on_plugin_page) { 
			foreach ($plugins as $key => $value) {
				if(strpos($key, 'wpematico_')!==FALSE) {
					unset( $plugins[$key] );
				}
			}
		}
		
	}
//	unset( $plugins['akismet/akismet.php'] );
	
	return $plugins;
}
function wpematico_addons_row_actions($actions, $plugin_file, $plugin_data, $context ){
	$actions = array();
	$actions['buynow'] =  '<a target="_Blank" class="edit" aria-label="' . esc_attr( sprintf( __( 'Go to %s WebPage','wpematico' ), $plugin_data['Name'] ) ) . '" title="' . esc_attr( sprintf( __( 'Open %s WebPage in new window.','wpematico' ), $plugin_data['Name'] ) ) . '" href="'.$plugin_data['PluginURI'].'">' . __('Details','wpematico') . '</a>';
	return $actions;
}

/**
 * Return the array of plugins plus WPeMatico Add-on found on etruel.com website
 * @param type $plugins array of current plugins
 */
Function read_wpem_addons($plugins){
	$cached 	= get_transient( 'etruel_wpematico_addons_data' );
	if ( !is_array( $cached ) ) { // If no cache read source feed
		$urls_addons = array();
		$urls_addons[] = 'http://etruel.com/downloads/category/wpematico-add-ons/feed/';
		for($i=2; $i<= 9; $i++) {
			$urls_addons[] = 'http://etruel.com/downloads/category/wpematico-add-ons/feed/?paged='.$i;
		}
		$addonitems = WPeMatico::fetchFeed($urls_addons, true, 10);
		$addon = array();
		foreach($addonitems->get_items() as $item) {
			$itemtitle = $item->get_title();
			$versions = $item->get_item_tags('', 'version');
			$version = (is_array($versions)) ? $versions[0]['data'] : '';
			$guid = $item->get_item_tags('', 'guid');
			$guid = (is_array($guid)) ? $guid[0]['data'] : '';
			wp_parse_str($guid, $query ); 
			if(isset($query ) && !empty($query ) ) {
				if(isset($query['p'])){
					$download_id = $query['p'];
				}
			}
			
			$plugindirname = str_replace('-','_', strtolower( sanitize_file_name( $itemtitle )));
			$img = $item->get_enclosure()->link;
			$icon = "<img width=\"100\" src=\"$img\" alt=\"$itemtitle\">";
			$addon[ $plugindirname ] = Array (
				'Name'		  => $itemtitle,
				'icon'		  => $icon,
				'PluginURI'	  => $item->get_permalink(),
				'buynowURI'	  => 'https://etruel.com/checkout?edd_action=add_to_cart&download_id='.$download_id.'&edd_options[price_id]=2',
				'Version'	  => $version,	// $item->get_date('U'),
				'Description' => $item->get_description(),
				'Author'	  => 'etruel', 
				'AuthorURI'   => 'https://etruel.com',
				'TextDomain'  => '',
				'DomainPath'  => '',
				'Network'	  => '',
				'Title'		  => $itemtitle,
				'AuthorName'  => 'etruel', 
				'Remote'	  => true 
			);
		}
		$addons = apply_filters( 'etruel_wpematico_addons_array', array_filter( $addon ) );
		$length = apply_filters( 'etruel_wpematico_addons_transient_length', DAY_IN_SECONDS );
		set_transient( 'etruel_wpematico_addons_data', $addons, $length );
		$cached = $addons;
	}
	//recorre plugins a ver si existe compara por URI y lo borro de cached (addons)
	foreach($plugins as $key => $plugin) {
		foreach($cached as $Akey => $addon) {
			if( ( strstr( $plugin['PluginURI'], '://') == strstr( $addon['PluginURI'], '://') ) 
/*				|| ('WPeMatico PRO' == $plugin['Name'] && 'WPeMatico Professional' == $addon['Name']) 
				|| ('WPeMatico PRO' == $plugin['Name'] && 'WPeMatico PRO' == $addon['Name']) 
*/				) { // Saco bundled
				unset( $cached[ $Akey ] );
				$plugins[$key]['installed'] = true;
			}
		}
	}
	$plugins = array_merge_recursive( $plugins, $cached );
	
	return $plugins;	
}