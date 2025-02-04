<?php
// don't load directly 
if ( !defined('ABSPATH') ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

/**
* Retrieve tools sections
* @since       1.2.4
* @return      array
*/
function wpematico_get_debug_sections() {
	$sections                = array();
	$sections['debug_file']  = __( 'Debug File', 'wpematico' );
	$sections['danger_zone'] = __( 'Danger Zone', 'wpematico' );
	$sections = apply_filters( 'wpematico_get_debug_sections', $sections );

	return $sections;
}

function wpematico_debug_print_sections () {
	global $pagenow, $wp_roles, $current_user;			
	//$cfg = get_option(WPeMatico :: OPTION_KEY);
	$current_section = (isset($_GET['section']) ) ? $_GET['section'] : 'debug_file' ;
	$sections = wpematico_get_debug_sections();

	?>	
	<div class="wrap">
		<h3 class="nav-section-wrapper">
			<?php
			$f = TRUE;
			foreach( $sections as $section_id => $section_name ) {
				$section_url = add_query_arg( array(
					'section' => $section_id
				) );

//				$section_url = remove_query_arg( array(
//					'section'
//				), $section_url );
				
				if(!$f) echo " | "; else $f=FALSE;
				$active = $current_section == $section_id ? ' nav-section-active' : '';
				echo '<a href="' . esc_url( $section_url ) . '" title="' . esc_attr( $section_name ) . '" class="nav-section' . $active . '">' . ( $section_name ) . '</a>';

			}
			?>
		</h3>
		<div class="metabox-holder">
			<?php
			do_action( 'wpematico_settings_section_' . $current_section );
			?>
		</div><!-- .metabox-holder -->
	</div><!-- .wrap -->
	<script type="text/javascript" language="javascript">
		jQuery(function(){
			jQuery(".help_tip").tipTip({maxWidth: "300px", edgeOffset: 5,fadeIn:50,fadeOut:50, keepAlive:true, attribute:"data-tip"});
		});
	</script>
	<?php

}
add_action( 'wpematico_settings_tab_debug_info', 'wpematico_debug_print_sections' );

/**
 * Display the debug info tab
 *
 * @since       1.2.4
 * @return      void
 */
function wpematico_settings_section_danger_zone() {   
	$danger = get_option( 'WPeMatico_danger');
	$danger['wpemdeleoptions']	 = (isset($danger['wpemdeleoptions']) && !empty($danger['wpemdeleoptions']) ) ? $danger['wpemdeleoptions'] : false;
	$danger['wpemdelecampaigns'] = (isset($danger['wpemdelecampaigns']) && !empty($danger['wpemdelecampaigns']) ) ? $danger['wpemdelecampaigns'] : false;
?>
	<form action="options.php" method="post" dir="ltr">
		<h3><?php _e('Select actions to Uninstall','wpematico'); ?></h3>
		<label><input class="checkbox" value="1" type="checkbox" <?php checked($danger['wpemdeleoptions'],true);?> name="wpemdeleoptions" /> <?php _e('Delete all Options.', 'wpematico' ); ?></label><br/>
		<label><input class="checkbox" value="1" type="checkbox" <?php checked($danger['wpemdelecampaigns'],true);?> name="wpemdelecampaigns" /> <?php _e('Delete all Campaigns.', 'wpematico' ); ?></label><br/>
		<?php  wp_nonce_field('wpematico-danger'); ?>
		<input type="hidden" name="wpematico-action" value="set_danger_data" />
		<p class="submit">
			<?php submit_button( 'Save Actions to Uninstall.', 'primary', 'wpematico-set-danger-data', false ); ?>
		</p>
	</form>
<?php
}
add_action( 'wpematico_settings_section_danger_zone', 'wpematico_settings_section_danger_zone' );


function wpematico_FriendlyErrorType($type)
{
    switch($type)
    {
        case E_ERROR: // 1 //
            return 'E_ERROR';
        case E_WARNING: // 2 //
            return 'E_WARNING';
        case E_PARSE: // 4 //
            return 'E_PARSE';
        case E_NOTICE: // 8 //
            return 'E_NOTICE';
        case E_CORE_ERROR: // 16 //
            return 'E_CORE_ERROR';
        case E_CORE_WARNING: // 32 //
            return 'E_CORE_WARNING';
        case E_COMPILE_ERROR: // 64 //
            return 'E_COMPILE_ERROR';
        case E_COMPILE_WARNING: // 128 //
            return 'E_COMPILE_WARNING';
        case E_USER_ERROR: // 256 //
            return 'E_USER_ERROR';
        case E_USER_WARNING: // 512 //
            return 'E_USER_WARNING';
        case E_USER_NOTICE: // 1024 //
            return 'E_USER_NOTICE';
        case E_STRICT: // 2048 //
            return 'E_STRICT';
        case E_RECOVERABLE_ERROR: // 4096 //
            return 'E_RECOVERABLE_ERROR';
        case E_DEPRECATED: // 8192 //
            return 'E_DEPRECATED';
        case E_USER_DEPRECATED: // 16384 //
            return 'E_USER_DEPRECATED';
    }
    return "";
} 
/**
 * Display the debug info tab
 *
 * @since       1.2.4
 * @return      void
 */
function wpematico_settings_section_debug_file() {   
?>
<div class="wrap">
		<div id="poststuff">
			<div id="post-body" class="metabox-holder columns-<?php echo 1 == get_current_screen()->get_columns() ? '1' : '2'; ?>">
				<div id="postbox-container-1" class="postbox-container">
					<div id="side-sortables" class="meta-box-sortables ui-sortable">
						<div id="wpem-about" class="postbox">
							<button type="button" class="handlediv button-link" aria-expanded="true">
								<span class="screen-reader-text"><?php _e('Click to toggle'); ?></span>
								<span class="toggle-indicator" aria-hidden="true"></span>
							</button>
							<h2 class="hndle"><?php _e('About', 'wpematico'); ?></h2>
							<div class="inside">
								<p><b>WPeMatico</b> <?php echo WPEMATICO_VERSION; ?> Version</p>
								<p class="icon_version">
									<a href="http://www.wpematico.com" target="_Blank" title="<?php _e('Go to the new WPeMatico WebSite', 'wpematico'); ?>">
										<img class="logover" src="<?php echo WPeMatico :: $uri; ?>/images/icon-512x512.jpg" title="">	
										<span id="wpematico-website">WPeMatico Website</span><br>
									</a><span id="wpematico-websiteinfo"><?php _e('Comments & Tutorials', 'wpematico'); ?></span>
								</p>
								<p class="icon_version">
									<a href="https://etruel.com" target="_Blank" title="<?php _e('WPeMatico Addons in etruel.com store', 'wpematico'); ?>">
										<img class="logover" src="<?php echo WPeMatico :: $uri; ?>/images/etruelcom_ico.png" title="">	
										<span id="wpematico-etruel">etruel.com</span><br>
									</a><span id="wpematico-store"><?php _e('Addons store, FAQs & Support', 'wpematico'); ?></span>
								</p>
								<p><?php _e('Thanks for use & test this plugin.', 'wpematico'); ?></p>
								<p></p>
								<p><?php _e('If you like this plugin, you can write a 5 star review on Wordpress.', 'wpematico'); ?></p>
								<style type="text/css">#linkrate:before { content: "\2605\2605\2605\2605\2605";font-size: 18px;}
									#linkrate { font-size: 18px;}</style>
								<p style="text-align: center;">
									<a href="https://wordpress.org/support/view/plugin-reviews/wpematico?filter=5&rate=5#new-post" id="linkrate" class="button" target="_Blank" title="Click here to rate plugin on Wordpress">  Rate </a>
								</p>
								<p></p>
								<p style="text-align: center;">
									<input type="button" class="button-primary" name="buypro" value="<?php _e('Buy PRO version online', 'wpematico'); ?>" onclick="javascript:window.open('https://etruel.com/downloads/wpematico-pro/');return false;"/>
								</p>
								<p></p>
							</div>
						</div>

						<div id="promo-extended" class="postbox " >
							<div class="ribbon"><span>HOT SALES</span></div>
							<button type="button" class="handlediv" aria-expanded="true"><span class="screen-reader-text">Toggle panel: Starter Packages</span><span class="toggle-indicator" aria-hidden="true"></span></button>
							<h2 class='hndle'><span>Starter Bundled Extensions</span></h2>
							<div class="inside">
								<div class="sidebar-promo worker" id="sidebar-promo">

									<h3><span class="dashicons dashicons-welcome-learn-more" style="font-size-adjust: 1;width: 50px;"></span><?php _e('Extended functionalities', 'wpematico'); ?></h3>
									<p>
										<?php
										echo sprintf(__('Many AddOns makes the %s with the most wanted functionalities.') . '  ', '<a href="https://etruel.com/starter-packages/" target="_blank" rel="noopener"><strong>Starter Packages</strong></a>');
										?> 
										<span>
											<?php _e('Lot of new features with contents, images, tags, filters, custom fields, custom feed tags and much more extends in the WPeMatico free plugin, going further than RSS feed limits and takes you to a new experience.', 'wpematico'); ?>
										</span>
									</p>
									<p style="text-align: center;">
										<a class="button button-primary" title="Features and prices" href="https://etruel.com/starter-packages/" target="_blank"><?php _e('Starter Packages Page', 'wpematico'); ?></a>
									</p>
								</div>
							</div>
						</div>

						<div id="promo-content" class="postbox">
							<button type="button" class="handlediv" aria-expanded="true">
								<span class="screen-reader-text">Toggle panel: Support</span>
								<span class="toggle-indicator" aria-hidden="true"></span>
							</button>
							<h2 class='hndle'><span>Support</span></h2>
							<div class="inside">
								<div class="sidebar-promo" id="sidebar-promo">
									<h3><span class="dashicons dashicons-sos" style="font-size-adjust: 1;width: 50px;"></span><?php _e('Have some questions?', 'wpematico'); ?></h3>
									<p>
										<?php _e('You may find answers in our', 'wpematico'); ?> <a target="_blank" href="https://etruel.com/faqs/">FAQ</a><br><?php _e('You may', 'wpematico'); ?> <a target="_blank" href="https://etruel.com/my-account/support/"><?php _e('contact us', 'wpematico'); ?></a> <?php _e('with customization requests and suggestions.', 'wpematico'); ?><br> 
										<?php _e('Please visit our website to learn about our free and premium services at', 'wpematico'); ?> <a href="https://etruel.com/downloads/premium-support/" target="_blank" title="etruel.com">etruel.com</a>
									</p>
								</div>
							</div>
						</div>

						<div id="promo-translate" class="postbox " >
							<button type="button" class="handlediv" aria-expanded="true">
								<span class="screen-reader-text">Toggle panel: Translation</span>
								<span class="toggle-indicator" aria-hidden="true"></span>
							</button>
							<h2 class='hndle'><span>Translation</span></h2>
							<div class="inside">
								<div class="sidebar-promo" id="sidebar-translate">
									<h3 class="translate"><span class="dashicons dashicons-translation" style="font-size-adjust: 1;width: 50px;"></span><?php _e('Translation friendly', 'wpematico'); ?></h3>
									<p><?php _e('Want to improve the texts or translate the plugin to your native language?', 'wpematico'); ?></p>
									<label style="text-align: center;font-weight: bold;margin: 10px;" onclick="jQuery('#howtranslate').toggle();">Show / Hide steps</label>
									<ol id="howtranslate" style="display: none;">
										Download <a href="https://poedit.net/wordpress" target="_blank" title="See the docs">Poedit</a>.<br />
										Download <a href="https://downloads.wordpress.org/plugin/wpematico.zip" target="_blank" title="Get it from wp.org">WPeMatico</a>.<br />
										<li>Launch Poedit.</li>
										<li>Edit a translation using existing .po file in lang folder.
											In case if you find errors in existing translations.</li>
										<li>Create new translation to translate into new language.</li>
									</ol>
								</div>
							</div>
						</div>

						<div class="postbox">
							<h2 class="handle"><?php _e('ShortPixel', 'wpematico'); ?></h2>
							<div class="inside" id="sp_content">
								<?php _e('Join to ShortPixel and optimize your images without compromising on quality.', 'wpematico'); ?>
								<div style="text-align: center;">
									<img src="https://shortpixel.com/img/logo.png">
									<br />
									<a class="button" target="_blank" href="https://shortpixel.com/h/af/QMVAHE4246413"><?php _e('Get ShortPixel API Key', 'wpematico'); ?></a>
								</div>
							</div>
						</div>

						<div class="postbox">
							<h2 class="handle"><?php _e('The Perfect Package', 'wpematico'); ?></h2>
							<div class="inside">
								<p id="left1" onmouseover="jQuery(this).css('opacity',0.9);this.style.backgroundColor='#111'" onmouseout="jQuery(this).css('opacity',0.5);this.style.backgroundColor='#fff'" style="text-align:center;opacity: 0.5;border-radius: 14px 14px 0 0;"><a href="https://etruel.com/downloads/wpematico-perfect-package/" target="_Blank" title="Go to etruel WebSite"><img style="width: 100%;" src="https://etruel.com/wp-content/uploads/edd/2016/09/wpematico_package_1024x512-300x150.png" title=""></a><br />
									WPeMatico The Perfect Package</p>
							</div>
						</div>
						
					</div>		<!-- #side-sortables -->
				</div>		<!--  postbox-container-1 -->		

	<?php do_action('wpematico_system_status_page_before'); ?>
	<div id="postbox-container-2" class="postbox-container">
	<table class="widefat wpematico-system-status-debug" cellspacing="0">
		<tbody>
			<tr>
				<td colspan="3" data-export-label="WPeMatico Status">
					<p class="text">
						<?php _e('Use this file to get support on '); ?><a href="https://etruel.com/support/" target="_blank" rel="follow">etruel's website</a>.
					</p>
					<span class="get-system-status">
						<a href="javascript:;" onclick='jQuery( "#debug-report" ).slideDown();jQuery( this ).parent().fadeOut();' class="button-primary debug-report"><?php _e('Get System Report', 'wpematico' ); ?></a>
						<span class="system-report-msg"><?php _e('Click the button to see and download the system report.', 'wpematico' ); ?></span>
					</span>
					<div id="debug-report" style="display: none;">
						<form action="<?php echo esc_url( admin_url( 'edit.php?post_type=wpematico&page=wpematico_settings&tab=debug_info' ) ); ?>" method="post" dir="ltr">
							<label><input class="checkbox" value="1" type="checkbox" name="alsophpinfo" /> <?php _e('Include also PHPInfo() if available.', 'wpematico' ); ?></label><br/>
							<label><input class="checkbox" value="1" type="checkbox" checked="checked" name="alsocampaignslogs" /> <?php _e('Include also Last Campaigns Log.', 'wpematico' ); ?></label><br/>
							<?php do_action('wpematico_debug_page_form_options'); ?>
							<input type="hidden" name="wpematico-action" value="download_debug_info" />
							<p class="submit">
								<?php submit_button( 'Download Debug Info File', 'primary', 'wpematico-download-debug-info', false ); ?>
							</p>
							<div style="max-width: 650px;">
							<textarea readonly="readonly" id="debug-info-textarea" name="wpematico-sysinfo" 
									  title="<?php _e('To copy the system info, click below then press Ctrl + C (PC) or Cmd + C (Mac).',  'wpematico'); ?>"
									  style="width: 100%;min-height: 370px;"
							><?php 
								echo wpematico_debug_info_get(); 
							?></textarea>
								<?php  wp_nonce_field('wpematico-settings'); ?>
								<label onclick="jQuery('#debug-info-textarea').focus();jQuery('#debug-info-textarea').select()" ><?php _e('SELECT ALL', 'wpematico'); ?></label>
							</div>

						</form>
						<p></p>
					</div>
					
				</td>
			</tr>
		</tbody>
	</table>
	
	<p></p>
	<?php wpematico_show_data_info(); 	?>
	</div>		<!--  postbox-container-2 -->
		</div> <!-- #post-body -->
	</div> <!-- #poststuff -->

</div>
<?php 
}
add_action( 'wpematico_settings_section_debug_file', 'wpematico_settings_section_debug_file' );

function wpematico_get_plugin_new_version( $plugin ) {
	static $plugin_updates = array(); // Cache received responses.
	$response = '';
	if (empty( $plugin_updates)) {
		$plugin_updates = get_site_transient('update_plugins');
		if ($plugin_updates === false) {
			$plugin_updates = new stdClass();
		}
	}
	if (!isset($plugin_updates->response)) {
		$plugin_updates->response = array();
	}
	foreach ($plugin_updates->response as $r_plugin => $value) {
		if ($r_plugin == $plugin) {
			$response = $value->new_version;
			break;
		}
	}

	return $response;
}
function wpematico_disk_total_space($echo = true) {
	$bytes = disk_total_space("."); 
    $si_prefix = array( 'B', 'KB', 'MB', 'GB', 'TB', 'EB', 'ZB', 'YB' );
    $base = 1024;
    $class = min((int)log($bytes , $base) , count($si_prefix) - 1);
    if ($echo) {
    	 echo sprintf('%1.2f' , $bytes / pow($base,$class)) . ' ' . $si_prefix[$class] . '<br />';
    } else {
    	return sprintf('%1.2f' , $bytes / pow($base,$class)) . ' ' . $si_prefix[$class];
    }
   
}

function wpematico_disk_free_space($echo = true) {
	$bytes = disk_free_space("."); 
    $si_prefix = array( 'B', 'KB', 'MB', 'GB', 'TB', 'EB', 'ZB', 'YB' );
    $base = 1024;
    $class = min((int)log($bytes , $base) , count($si_prefix) - 1);
    if ($echo) {
    	 echo sprintf('%1.2f' , $bytes / pow($base,$class)) . ' ' . $si_prefix[$class] . '<br />';
    } else {
    	return sprintf('%1.2f' , $bytes / pow($base,$class)) . ' ' . $si_prefix[$class];
    }
}

function wpematico_get_option_active_plugins() {
	static $option_active_plugins = array();
	if (empty($option_active_plugins)) {
		$option_active_plugins = (array) get_option( 'active_plugins', array() );
	}
	return $option_active_plugins;
}

function wpematico_get_active_plugins() {
	static $wpematico_active_plugins = array();
	if (empty($wpematico_active_plugins)) {
		$active_plugins = wpematico_get_option_active_plugins();
		if ( is_multisite() ) {
			$active_plugins = array_merge( $active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
		}
		foreach ( $active_plugins as $plugin ) {
			$wpematico_active_plugins[] = array(
					'new_version' 	 => wpematico_get_plugin_new_version($plugin),
					'plugin_data' 	 => @get_plugin_data( WP_PLUGIN_DIR . '/' . $plugin ),
					'dirname' 	  	 => dirname( $plugin ),
					'version_string' => 'Version',
					'network_string' => '',
				  );
		}
	}
	return $wpematico_active_plugins;		
}

/**
 * 
 * @global object $wpdb
 * @staticvar array $vars
 * @return type array $vars to extract 
 */
function wpematico_debug_data() {
	static $vars = array();
	if (empty($vars)) {
		global $wpdb;
		if( !class_exists( 'Browser' ) )
			require_once dirname( __FILE__) . '/lib/browser.php';  //https://github.com/cbschuld/Browser.php

		$vars['browser']			= new Browser();

		// Get theme info
		if( get_bloginfo( 'version' ) < '3.4' ) {
			$theme_data = get_theme_data( get_stylesheet_directory() . '/style.css' );
			$vars['theme']			= $theme_data['Name'] . ' ' . $theme_data['Version'];
		} else {
			$theme_data = wp_get_theme();
			$vars['theme']			= $theme_data->Name . ' ' . $theme_data->Version;
		}

		// Try to identify the hosting provider
		$vars['host']				= wpematico_get_host();

		$vars['home_url'] 			= home_url();
		$vars['site_url'] 		  	= site_url();
		$vars['is_multisite'] 		= is_multisite();
		$vars['db_version'] 		= $wpdb->db_version();
		$vars['php_ok'] 			= (function_exists('version_compare') && version_compare(phpversion(), '5.3.0', '>='));
		$vars['remote_get_work'] 	= false;
		$vars['remote_post_work'] 	= false;
		$response 					= wp_remote_post( 'https://etruel.com/downloads/feed/', array( 'decompress' => false, 'user-agent' => 'wpematico-debug' ) );
		if ( ! is_wp_error( $response ) && $response['response']['code'] >= 200 && $response['response']['code'] < 300 ) {
			$vars['remote_post_work']= true;
		}
		$response					=	wp_remote_get( 'https://etruel.com/downloads/feed/', array( 'decompress' => false, 'user-agent' => 'wpematico-debug' ) );
		if ( ! is_wp_error( $response ) && $response['response']['code'] >= 200 && $response['response']['code'] < 300 ) {
			$vars['remote_get_work']= true;			
		}
		$vars['front_page_id']		= get_option( 'page_on_front' );
		$vars['blog_page_id']		= get_option( 'page_for_posts' );
		$vars['disk_total_space']	= wpematico_disk_total_space(false);
		$vars['disk_free_space']	= wpematico_disk_free_space(false);
		
		$vars['professional_help'] 	= '<a href="https://etruel.com/downloads/wpematico-professional/" target="_blank">WPeMatico Professional</a>';
		$vars['cache_help'] 		= '<a href="https://etruel.com/downloads/wpematico-cache/" target="_blank">WPeMatico Cache</a>';
		$vars['mmf_help']			= '<a href="https://etruel.com/downloads/wpematico-make-feed-good/" target="_blank">Make Me Feed</a>';
		$vars['polyglot_help']		= '<a href="https://etruel.com/downloads/wpematico-polyglot/" target="_blank">WPeMatico PolyGlot</a>';
		$vars['full_help']			= '<a href="https://etruel.com/downloads/wpematico-full-content/" target="_blank">WPeMatico Full Content</a>';
		$vars['better_help']		= '<a href="https://etruel.com/downloads/wpematico-better-excerpts/" target="_blank">WPeMatico Better Excerpts</a>';
		$vars['chinese_help']		= '<a href="https://etruel.com/downloads/wpematico-chinese-tags/" target="_blank">WPeMatico Chinese Tags</a>';
		$vars['facebook_help']		= '<a href="https://etruel.com/downloads/wpematico-facebook-fetcher/" target="_blank">WPeMatico Facebook Fetcher</a>';
		$vars['thumbnail_help']		= '<a href="https://etruel.com/downloads/wpematico-thumbnail-scratcher/" target="_blank">WPeMatico Thumbnail Scratcher</a>';
		$vars['smtp_help']			= '<a href="https://etruel.com/downloads/wpematico-smtp/" target="_blank">WPeMatico SMTP</a>';

		$vars['pcre_ok'] 		= extension_loaded('pcre');
		$vars['curl_ok'] 		= function_exists('curl_exec');
		//$vars['curl_ok'] 		= extension_loaded('curl');
		$vars['zlib_ok'] 		= extension_loaded('zlib');
		$vars['mbstring_ok'] 	= extension_loaded('mbstring');
		$vars['iconv_ok'] 		= extension_loaded('iconv');
		$vars['ssl_ok'] 		= extension_loaded('openssl');
		$vars['mcrypt_ok'] 		= extension_loaded('mcrypt');
		$vars['ZipArchive'] 	= class_exists( 'ZipArchive' );
		$vars['DOMDocument'] 	= class_exists( 'DOMDocument' );
		$vars['GD_ok']			= ( extension_loaded( 'gd' ) && function_exists( 'gd_info' ) );


		if( function_exists('apache_get_modules')) {
			$apache_modules = apache_get_modules();
			$vars['m_rewrite_ok'] 	= in_array('mod_rewrite', $apache_modules);
			$vars['m_mime_ok'] 		= in_array('mod_mime', $apache_modules);
			$vars['m_deflate_ok']	= in_array('mod_deflate', $apache_modules);
		}else{
			$vars['m_rewrite_ok'] 	= (isset($_SERVER['HTTP_MOD_REWRITE']) && $_SERVER['HTTP_MOD_REWRITE'] == 'On' ) ? true : FALSE;

		}
		if (extension_loaded('xmlreader')) {
			$vars['xml_ok'] = true;
		}elseif (extension_loaded('xml')) {
			$parser_check = xml_parser_create();
			xml_parse_into_struct($parser_check, '<foo>&amp;</foo>', $values);
			xml_parser_free($parser_check);
			$vars['xml_ok'] = isset($values[0]['value']);
		}else{
			$vars['xml_ok'] = false;
		}

		$vars['wp_memory'] = wpematico_let_to_num( WP_MEMORY_LIMIT );
		$vars['wp_max_upload_size'] = wp_max_upload_size() ;
		$vars['permalink_structure'] = get_option( 'permalink_structure' ) ? get_option( 'permalink_structure' ) : 'Default';
		$vars['show_on_front'] = get_option( 'show_on_front' );
		// Only show page specs if frontpage is set to 'page'
		if( $vars['show_on_front'] == 'page' ) {
			$front_page_id = get_option('page_on_front');
			$blog_page_id = get_option('page_for_posts');
			$vars['wp_front_page_id'] = ( $front_page_id != 0 ? get_the_title( $front_page_id ) . ' (#' . $front_page_id . ')' : 'Unset' );
			$vars['wp_blog_page_id'] = ( $blog_page_id != 0 ? get_the_title( $blog_page_id ) . ' (#' . $blog_page_id . ')' : 'Unset' );
		}
		$vars['db_prefix'] = strlen( $wpdb->prefix );
		$vars['post_stati'] = implode( ', ', get_post_stati() );

		$vars['active_plugins'] = wpematico_get_active_plugins();
		$vars['muplugins'] = get_mu_plugins();		
		
		if ( function_exists( 'ini_get' ) ) {
			$vars['safe_mode'] = ini_get('safe_mode');
			$vars['allow_url_fopen'] = ini_get('allow_url_fopen');
			$vars['memory'] = wpematico_let_to_num( ini_get( 'memory_limit' ) );
			$vars['time_limit'] = ini_get('max_execution_time');
			$vars['ini_set'] = ini_set('max_execution_time', $vars['time_limit']) === false ? false : true;
			$vars['disable_functions'] = ini_get('disable_functions');
			$vars['upload_max_filesize'] = ini_get('upload_max_filesize');
			$vars['post_max_size'] = ini_get('post_max_size');
			$vars['max_input_vars'] = ini_get('max_input_vars');
			$vars['required_input_vars'] = 0; // 12000 + ( 500 + 1000 );	// 1000 = theme options

			$vars['display_errors'] = ini_get('display_errors');

			$vars['session_name'] = esc_html( ini_get( 'session.name' ) );
			$vars['session_cookie_path'] = esc_html( ini_get( 'session.cookie_path' ) );
			$vars['session_save_path'] = esc_html( ini_get( 'session.save_path' ) );
			$vars['session_use_cookies'] = ini_get( 'session.use_cookies' );
			$vars['session_use_only_cookies'] = ini_get( 'session.use_only_cookies' );
			
			$vars['suhosin_max_input_vars'] = ini_get( 'suhosin.post.max_vars' );
			$vars['suhosin_required_input_vars'] = 0; //$required_input_vars + ( 500 + 1000 );
			$vars['suhosin_max_request_vars'] = ini_get( 'suhosin.request.max_vars' );
			$vars['suhosin_required_request_vars'] = 0; //$suhosin_required_request_vars + ( 500 + 1000 );
			$vars['suhosin_max_value_length'] = ini_get( "suhosin.post.max_value_length" );
			$vars['recommended_max_value_length'] = 0; //2000000;

		}

	}
	
	
	
	return $vars;
}
/**
 * Shows all data into a table
 */

function wpematico_show_data_info() {
	$debug_data = wpematico_debug_data();
	extract($debug_data);
	?>
		<h3 class="screen-reader-text"><?php _e( 'Server Environment', 'wpematico' ); ?></h3>
		<table class="widefat debug-section" cellspacing="0">
			<thead>
				<tr>
					<th colspan="3" class="debug-section-title" data-export-label="Server Environment"><?php _e( 'Server Environment', 'wpematico' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php if( $host ) : ?>
				<tr>
					<td data-export-label="Hosting Provider"><?php _e( 'Hosting Provider:', 'wpematico' ); ?></td>
					<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'Information about the hosting provider of your site.', 'wpematico'  ) . '">[?]</a>'; ?></td>
					<td><?php echo $host; ?></td>
				</tr>
				<?php endif; ?>
				<tr>
					<td data-export-label="Server Info"><?php _e( 'Server Info:', 'wpematico' ); ?></td>
					<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'Information about the web server that is currently hosting your site.', 'wpematico'  ) . '">[?]</a>'; ?></td>
					<td><?php echo esc_html( $_SERVER['SERVER_SOFTWARE'] ); ?></td>
				</tr>
				<tr>
					<td data-export-label="MySQL Version"><?php _e( 'MySQL Version:', 'wpematico' ); ?></td>
					<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'The version of MySQL installed on your hosting server.', 'wpematico'  ) . '">[?]</a>'; ?></td>
					<td>
						<?php echo $db_version; ?>
					</td>
				</tr>
				<tr>
					<td data-export-label="PHP Version"><?php _e( 'PHP Version:', 'wpematico' ); ?></td>
					<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'The version of PHP installed on your hosting server.', 'wpematico'  ) . '">[?]</a>'; ?></td>
					<td><?php 
							if (!$php_ok) {
								echo '<mark class="error">' . esc_html( phpversion() ) . __( 'WPeMatico requires PHP >= 5.3.', 'wpematico' ) . '</mark>';
							} else {
								echo '<mark class="yes">' . esc_html( phpversion() ) . '</mark>';
							}
					?></td>
				</tr>
				<tr>
					<td data-export-label="Disk Total Space"><?php _e( 'Disk Total Space:', 'wpematico' ); ?></td>
					<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'The total size of a filesystem or disk partition.', 'wpematico'  ) . '">[?]</a>'; ?></td>
					<td><?php echo $disk_total_space; ?></td>
				</tr>

				<tr>
					<td data-export-label="Disk Free Space"><?php _e( 'Disk Free Space:', 'wpematico' ); ?></td>
					<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'The available space on filesystem or disk partition.', 'wpematico'  ) . '">[?]</a>'; ?></td>
					<td><?php echo $disk_free_space; ?></td>
				</tr>
				<tr>
					<td data-export-label="Mod Rewrite"><?php _e( 'Mod Rewrite:', 'wpematico' ); ?></td>
					<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr(sprintf(__( '%s is required by %s.', 'wpematico'  ), 'Mod Rewrite', $cache_help)) . '">[?]</a>'; ?></td>
					<td><?php echo ($m_rewrite_ok) ? '<mark class="yes">&#10004;</mark>' : '<mark class="'.(defined( 'WPEMATICO_CACHE_VERSION') ? 'error' : 'error-no-install' ).'">'.sprintf(__('%s is not installed on your server, but is recommended by %s.', 'wpematico'), 'Mod Rewrite', 'some addons').'</mark>'; ?></td>
				</tr>
				<tr>
					<td data-export-label="Mod Mime"><?php _e( 'Mod Mime:', 'wpematico' ); ?></td>
					<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr(sprintf(__( '%s is required by %s.', 'wpematico'  ), 'Mod Mime', $cache_help)) . '">[?]</a>'; ?></td>
					<td><?php echo ($m_mime_ok) ? '<mark class="yes">&#10004;</mark>' : '<mark class="'.(defined( 'WPEMATICO_CACHE_VERSION') ? 'error' : 'error-no-install' ).'">'.sprintf(__('%s is not installed on your server, but is recommended by %s.', 'wpematico'), 'Mod Mime', 'some addons').'</mark>'; ?></td>
				</tr>
				<tr>
					<td data-export-label="Mod Deflate"><?php _e( 'Mod Deflate:', 'wpematico' ); ?></td>
					<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr(sprintf(__( '%s is required by %s.', 'wpematico'  ), 'Mod Deflate', $cache_help)) . '">[?]</a>'; ?></td>
					<td><?php echo ($m_deflate_ok) ? '<mark class="yes">&#10004;</mark>' : '<mark class="'.(defined( 'WPEMATICO_CACHE_VERSION') ? 'error' : 'error-no-install' ).'">'.sprintf(__('%s is not installed on your server, but is recommended by %s.', 'wpematico'), 'Mod Deflate', 'some addons').'</mark>'; ?></td>
				</tr>
				
			</tbody>
		</table>

		<h3 class="screen-reader-text"><?php _e( 'PHP Environment', 'wpematico' ); ?></h3>
		<table class="widefat debug-section" cellspacing="0">
			<thead>
				<tr>
					<th colspan="3" class="debug-section-title" data-export-label="PHP Environment"><?php _e( 'PHP Environment', 'wpematico' ); ?></th>
				</tr>
			</thead>
			<tbody>
			<?php if ( function_exists( 'ini_get' ) ) : ?>
					<tr>
						<td data-export-label="PHP Post Max Size"><?php _e( 'PHP Post Max Size:', 'wpematico' ); ?></td>
						<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'The largest file size that can be contained in one post.', 'wpematico'  ) . '">[?]</a>'; ?></td>
						<td><?php echo size_format( wpematico_let_to_num( $post_max_size ) ); ?></td>
					</tr>
					<tr>
						<td data-export-label="PHP Max Input Vars"><?php _e( 'PHP Max Input Vars:', 'wpematico' ); ?></td>
						<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'The maximum number of variables your server can use for a single function to avoid overloads.', 'wpematico'  ) . '">[?]</a>'; ?></td>
						<?php
						?>
						<td><?php
							if ( $max_input_vars < $required_input_vars ) {
								echo '<mark class="error">' . sprintf( __( '%s - Recommended Value: %s.<br />Max input vars limitation will truncate POST data such as menus. See: <a href="%s" target="_blank">Increasing max input vars limit.</a>', 'wpematico' ), $max_input_vars, '<strong>' . $required_input_vars . '</strong>', 'http://sevenspark.com/docs/ubermenu-3/faqs/menu-item-limit' ) . '</mark>';
							} else {
								echo '<mark class="yes">' . $max_input_vars . '</mark>';
							}
						?></td>
					</tr>
					<tr>
						<td data-export-label="PHP Time Limit"><?php _e( 'PHP Time Limit:', 'wpematico' ); ?></td>
						<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'The amount of time (in seconds) that your site will spend on a single operation before timing out (to avoid server lockups)', 'wpematico'  ) . '">[?]</a>'; ?></td>
						<td><?php
							if ( $time_limit < 180 && $time_limit != 0 ) {
								echo '<mark class="error">' . sprintf( __( '%s - We recommend setting max execution time to at least 180. <br /> To give a campaign 5 minutes to run without timeouts, <strong>300</strong> seconds of max execution time is required.<br />See: <a href="%s" target="_blank">Increasing max execution to PHP</a>', 'wpematico' ), $time_limit, 'http://codex.wordpress.org/Common_WordPress_Errors#Maximum_execution_time_exceeded' ) . '</mark>';
							} else {
								echo '<mark class="yes">' . $time_limit . '</mark>';
								if ( $time_limit < 300 && $time_limit != 0 ) {
									echo '<br /><mark class="error">' . __( 'Current time limit is sufficient, but if you want to give 5 minutes to run without timeouts to each campaign, the required time is <strong>300</strong>.', 'wpematico' ) . '</mark>';
								}
							}
						?></td>
					</tr>
					<tr>
						<td data-export-label="PHP Memory Limit"><?php _e( 'PHP Memory Limit:', 'wpematico' ); ?></td>
						<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'The maximum amount of memory (RAM) that your PHP allows in this server.', 'wpematico'  ) . '">[?]</a>'; ?></td>
						<td><?php
							if ( $memory < 128000000 ) {
								echo '<mark class="error">' . sprintf( __( '%s - We recommend setting memory to at least <strong>128MB</strong>. <br /> Please define memory limit in <strong>php.ini</strong> file.', 'wpematico' ), size_format( $memory ), 'http://codex.wordpress.org/Editing_wp-config.php#Increasing_memory_allocated_to_PHP' ) . '</mark>';
							} else {
								echo '<mark class="yes">' . size_format( $memory ) . '</mark>';
							}
						?></td>
					</tr>
					<tr>
						<td data-export-label="PHP Safe Mode"><?php _e( 'PHP Safe Mode:', 'wpematico' ); ?></td>
						<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'The PHP safe mode is an attempt to solve the shared-server security problem. This feature has been DEPRECATED as of PHP 5.3.0 and REMOVED as of PHP 5.4.0.', 'wpematico'  ) . '">[?]</a>'; ?></td>
						<td><?php
							if ( $safe_mode ) {
								echo '<mark class="error">On - ' . sprintf( __( 'We recommend turn safe_mode <strong>Off</strong>. <br /> See: <a href="%s" target="_blank">PHP: Safe Mode.</a>.', 'wpematico' ), 'http://php.net/manual/en/features.safe-mode.php' ) . '</mark>';
							} else {
								echo '<mark class="yes">' . 'Off' . '</mark>';
							}
						?></td>
					</tr>
					<tr>
						<td data-export-label="Allow URL fopen"><?php _e( 'Allow URL fopen:', 'wpematico' ); ?></td>
						<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'Enables the URL-aware fopen wrappers that enable accessing URL object like files.', 'wpematico'  ) . '">[?]</a>'; ?></td>
						<td><?php
							if ( $allow_url_fopen ) {
								echo '<mark class="yes">' . 'On' . '</mark>';
							} else {
								echo '<mark class="error">Off - ' . sprintf( __( 'We recommend turn Allow URL fopen <strong>On</strong>. <br /> See: <a href="%s" target="_blank">PHP: Allow URL fopen.</a>.', 'wpematico' ), 'http://php.net/manual/en/filesystem.configuration.php#ini.allow-url-fopen' ) . '</mark>';
							}
						?></td>
					</tr>
					<tr>
						<td data-export-label="ini_set"><?php _e( 'ini_set:', 'wpematico' ); ?></td>
						<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'Sets the value of a PHP configuration option.  The configuration option will keep this new value during the script\'s execution, and will be restored at the script\'s ending. ', 'wpematico'  ) . '">[?]</a>'; ?></td>
						<td><?php
							if ( $ini_set ) {
								echo '<mark class="yes">' . 'On' . '</mark>';
							} else {
								echo '<mark class="no">Off - ' . sprintf( __( 'We recommend to activate <strong>set_ini()</strong> in your server. <br /> See: <a href="%s" target="_blank">PHP: ini_set.</a>.', 'wpematico' ), 'http://php.net/manual/en/function.ini-set.php' ) . '</mark>';
							}
						?></td>
					</tr>
					<tr>
						<td data-export-label="PHP Disabled Functions"><?php _e( 'PHP Disabled Functions:', 'wpematico' ); ?></td>
						<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'PHP disabled functions to avoid potential unknown vulnerabilities.', 'wpematico'  ) . '">[?]</a>'; ?></td>
						<td><?php echo str_replace(',', ',<br/>', $disable_functions ); ?></td>
					</tr>
					<tr>
						<td data-export-label="PHP Display Errors"><?php _e( 'PHP Display Errors:', 'wpematico' ); ?></td>
						<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'Shows or hide all the PHP errors and warnings in your script.', 'wpematico'  ) . '">[?]</a>'; ?></td>
						<td><?php echo ( $display_errors ? __('On','wpematico').' (' . $display_errors . ')' : 'N/A' ); ?></td>
					</tr>

				<?php endif; ?>
				<tr>
					<td data-export-label="PHP Current error_reporting levels"><?php _e( 'PHP Current error_reporting levels:', 'wpematico' ); ?></td>
					<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'PHP error_reporting — Shows which PHP errors are currently reported. ', 'wpematico'  ) . '">[?]</a>'; ?></td>
					<td><?php 
					$errLvl = error_reporting();
					for ($i = 0; $i < 15;  $i++ ) {
						print wpematico_FriendlyErrorType($errLvl & pow(2, $i)) . "<br>\n";
					} 
					?></td>
				</tr>
				<?php

				?>
				<tr>
					<td data-export-label="cURL (php.net/curl)"><?php _e( 'cURL (php.net/curl):', 'wpematico' ); ?></td>
					<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr(sprintf(__( '%s is required by %s.', 'wpematico'  ), 'cURL (php.net/curl)', 'WPeMatico Core, '.$professional_help.', '.$cache_help.', '.$mmf_help.', '.$polyglot_help)) . '">[?]</a>'; ?></td>
					<td><?php echo ($curl_ok) ? '<mark class="yes">&#10004;</mark>' : '<mark class="error">'.sprintf(__('%s is not installed on your server, but is recommended by %s.', 'wpematico'), 'cURL (php.net/curl)', 'some addons and Simplepie').'</mark>'; ?></td>
				</tr>
				<tr>
					<td data-export-label="ZipArchive"><?php _e( 'ZipArchive:', 'wpematico' ); ?></td>
					<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'ZipArchive is recommended. They can be used to import and export zip files.', 'wpematico'  ) . '">[?]</a>'; ?></td>
					<td><?php echo $ZipArchive ? '<mark class="yes">&#10004;</mark>' : '<mark class="error">'.sprintf(__('%s is not installed on your server, but is recommended by %s.', 'wpematico'), 'ZipArchive', 'WPeMatico Core').'</mark>'; ?></td>
				</tr>
				<tr>
					<td data-export-label="DOMDocument"><?php _e( 'DOMDocument:', 'wpematico' ); ?></td>
					<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr(sprintf(__( '%s is recommended by %s.', 'wpematico'  ), 'DOMDocument', 'WPeMatico Core')) . '">[?]</a>'; ?></td>
					<td><?php echo $DOMDocument ? '<mark class="yes">&#10004;</mark>' : '<mark class="error">'.sprintf(__('%s is not installed on your server, but is recommended by %s.', 'wpematico'), 'DOMDocument', 'some addons').'</mark>'; ?></td>
				</tr>
				<tr>
					<td data-export-label="GD Library"><?php _e( 'GD Library:', 'wpematico' ); ?></td>
					<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'WPeMatico uses this library to resize images and speed up your site\'s loading time', 'wpematico'  ) . '">[?]</a>'; ?></td>
					<td><?php echo $GD_ok ? '<mark class="yes">&#10004;</mark>' : '<mark class="error">'.sprintf(__('%s is not installed on your server, but is recommended by %s.', 'wpematico'), 'GD', 'WPeMatico Core').'</mark>'; ?></td>
				</tr>
				<tr>
					<td data-export-label="XML (php.net/xml)"><?php _e( 'XML (php.net/xml):', 'wpematico' ); ?></td>
					<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'XML (php.net/xml) is required.', 'wpematico'  ) . '">[?]</a>'; ?></td>
					<td><?php echo ($xml_ok) ? '<mark class="yes">&#10004;</mark>' : '<mark class="error">'.sprintf(__('%s is not installed on your server, but is recommended by %s.', 'wpematico'), 'XML (php.net/xml)', 'WPeMatico Core').'</mark>'; ?></td>
				</tr>
				<tr>
					<td data-export-label="PCRE (php.net/pcre)"><?php _e( 'PCRE (php.net/pcre):', 'wpematico' ); ?></td>
					<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr(sprintf(__( '%s is required by %s.', 'wpematico'  ), 'PCRE (php.net/pcre)', 'WPeMatico Core, '.$professional_help.', '.$full_help.', '.$better_help.', '.$cache_help.', '.$chinese_help.', '.$facebook_help.', '.$mmf_help.', '.$thumbnail_help.', '.$thumbnail_help.'')) . '">[?]</a>'; ?></td>
					<td><?php echo ($pcre_ok) ? '<mark class="yes">&#10004;</mark>' : '<mark class="error">'.sprintf(__('%s is not installed on your server, but is recommended by %s.', 'wpematico'), 'PCRE (php.net/pcre)', 'some addons and WPeMatico Core').'</mark>'; ?></td>
				</tr>
				<tr>
					<td data-export-label="Zlib (php.net/zlib)"><?php _e( 'Zlib (php.net/zlib):', 'wpematico' ); ?></td>
					<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr(sprintf(__( '%s is required by %s.', 'wpematico'  ), 'Zlib (php.net/zlib)', 'WPeMatico Core, '.$cache_help)) . '">[?]</a>'; ?></td>
					<td><?php echo ($zlib_ok) ? '<mark class="yes">&#10004;</mark>' : '<mark class="error">'.sprintf(__('%s is not installed on your server, but is recommended by %s.', 'wpematico'), 'Zlib (php.net/zlib)', 'some addons and WPeMatico Core').'</mark>'; ?></td>
				</tr>
				<tr>
					<td data-export-label="php.net/mbstring"><?php _e( 'php.net/mbstring:', 'wpematico' ); ?></td>
					<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr(sprintf(__( '%s is required by %s.', 'wpematico'  ), 'php.net/mbstring', 'WPeMatico Core, '.$full_help.', '.$chinese_help.', '.$mmf_help)) . '">[?]</a>'; ?></td>
					<td><?php echo ($mbstring_ok) ? '<mark class="yes">&#10004;</mark>' : '<mark class="error">'.sprintf(__('%s is not installed on your server, but is recommended by %s.', 'wpematico'), 'php.net/mbstring', 'some addons and WPeMatico Core').'</mark>'; ?></td>
				</tr>
				<tr>
					<td data-export-label="iconv (php.net/iconv)"><?php _e( 'iconv (php.net/iconv):', 'wpematico' ); ?></td>
					<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr(sprintf(__( '%s is required by %s.', 'wpematico'  ), 'iconv (php.net/iconv)', 'WPeMatico Core, '.$full_help.', '.$mmf_help)) . '">[?]</a>'; ?></td>
					<td><?php echo ($iconv_ok) ? '<mark class="yes">&#10004;</mark>' : '<mark class="error">'.sprintf(__('%s is not installed on your server, but is recommended by %s.', 'wpematico'), 'iconv (php.net/iconv)', 'some addons and WPeMatico Core').'</mark>'; ?></td>
				</tr>
				<tr>
					<td data-export-label="OpenSSL (php.net/openssl)"><?php _e( 'OpenSSL (php.net/openssl):', 'wpematico' ); ?></td>
					<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr(sprintf(__( '%s is required by %s.', 'wpematico'  ), 'OpenSSL (php.net/openssl)', $smtp_help)) . '">[?]</a>'; ?></td>
					<td><?php echo ($ssl_ok) ? '<mark class="yes">&#10004;</mark>' : '<mark class="'.(defined( 'WPESMTP_VERSION') ? 'error' : 'error-no-install' ).'">'.sprintf(__('%s is not installed on your server, but is recommended by %s.', 'wpematico'), 'OpenSSL (php.net/openssl)', 'some addons').'</mark>'; ?></td>
				</tr>
				<tr>
					<td data-export-label="mcrypt (php.net/mcrypt)"><?php _e( 'mcrypt (php.net/mcrypt):', 'wpematico' ); ?></td>
					<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr(sprintf(__( '%s is required by %s.', 'wpematico'  ), 'mcrypt (php.net/mcrypt)', $smtp_help)) . '">[?]</a>'; ?></td>
					<td><?php echo ($mcrypt_ok) ? '<mark class="yes">&#10004;</mark>' : '<mark class="'.(defined( 'WPESMTP_VERSION') ? 'error' : 'error-no-install' ).'">'.sprintf(__('%s is not installed on your server, but is recommended by %s.', 'wpematico'), 'mcrypt (php.net/mcrypt)', 'some addons').'</mark>'; ?></td>
				</tr>
<?php /*	Commented SUHOSIN details not important			<tr>
					<td data-export-label="SUHOSIN Installed"><?php _e( 'SUHOSIN Installed:', 'wpematico' ); ?></td>
					<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'Suhosin is an advanced protection system for PHP installations. It was designed to protect your servers on the one hand against a number of well known problems in PHP applications and on the other hand against potential unknown vulnerabilities within these applications or the PHP core itself.
					If enabled on your server, Suhosin may need to be configured to increase its data submission limits.', 'wpematico'  ) . '">[?]</a>'; ?></td>
					<td><?php echo extension_loaded( 'suhosin' ) ? '&#10004;' : '&ndash;'; ?></td>
				</tr>
				<?php if ( extension_loaded( 'suhosin' ) ): ?>
					<tr>
						<td data-export-label="Suhosin Post Max Vars"><?php _e( 'Suhosin Post Max Vars:', 'wpematico' ); ?></td>
						<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'The maximum number of variables your server can use for a single function to avoid overloads.', 'wpematico'  ) . '">[?]</a>'; ?></td>
						<td><?php
							if ( $suhosin_max_input_vars < $suhosin_required_input_vars ) {
								echo '<mark class="error">' . sprintf( __( '%s - Recommended Value: %s.<br />Max input vars limitation will truncate POST data such as menus. See: <a href="%s" target="_blank">Increasing max input vars limit.</a>', 'wpematico' ), $suhosin_max_input_vars, '<strong>' . ( $suhosin_required_input_vars ) . '</strong>', 'http://sevenspark.com/docs/ubermenu-3/faqs/menu-item-limit' ) . '</mark>';
							} else {
								echo '<mark class="yes">' . $suhosin_max_input_vars . '</mark>';
							}
						?></td>
					</tr>
					<tr>
						<td data-export-label="Suhosin Request Max Vars"><?php _e( 'Suhosin Request Max Vars:', 'wpematico' ); ?></td>
						<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'The maximum number of variables your server can use for a single function to avoid overloads.', 'wpematico'  ) . '">[?]</a>'; ?></td>
						<td><?php
							if ( $suhosin_max_request_vars < $suhosin_required_request_vars ) {
								echo '<mark class="error">' . sprintf( __( '%s - Recommended Value: %s.<br />Max input vars limitation will truncate POST data such as menus. See: <a href="%s" target="_blank">Increasing max input vars limit.</a>', 'wpematico' ), $suhosin_max_request_vars, '<strong>' . ( $suhosin_required_request_vars + ( 500 + 1000 ) ) . '</strong>', 'http://sevenspark.com/docs/ubermenu-3/faqs/menu-item-limit' ) . '</mark>';
							} else {
								echo '<mark class="yes">' . $suhosin_max_request_vars . '</mark>';
							}
						?></td>
					</tr>
					<tr>
						<td data-export-label="Suhosin Post Max Value Length"><?php _e( 'Suhosin Post Max Value Length:', 'wpematico' ); ?></td>
						<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'Defines the maximum length of a variable that is registered through a POST request.', 'wpematico'  ) . '">[?]</a>'; ?></td>
						<td><?php
							if ( $suhosin_max_value_length < $recommended_max_value_length ) {
								echo '<mark class="error">' . sprintf( __( '%s - Recommended Value: %s.<br />Post Max Value Length limitation may prohibit the form data from being saved to your database.', 'wpematico' ), $suhosin_max_value_length, '<strong>' . $recommended_max_value_length . '</strong>' ) . '</mark>';
							} else {
								echo '<mark class="yes">' . $suhosin_max_value_length . '</mark>See: <a href="http://suhosin.org/stories/configuration.html" target="_blank">Suhosin Configuration Info</a>.';
							}
						?></td>
					</tr>
				<?php endif; // suhosin installed ?>
*/ ?>				
				<tr>
					<td data-export-label="Session enabled"><?php _e( '$_SESSION enabled:', 'wpematico' ); ?></td>
					<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'PHP Session Configuration. http://php.net/manual/es/reserved.variables.session.php', 'wpematico'  ) . '">[?]</a>'; ?></td>
					<td><?php echo isset( $_SESSION ) ? '&#10004;' : '&ndash;'; ?></td>
				</tr>
				<?php if ( isset( $_SESSION ) ) : ?>
					<tr>
						<td data-export-label="Session Name"><?php _e( 'Session Name:', 'wpematico' ); ?></td>
						<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'The Session Name.', 'wpematico'  ) . '">[?]</a>'; ?></td>
						<td><?php echo $session_name; ?></td>
					</tr>				
					<tr>
						<td data-export-label="Cookie Path"><?php _e( 'Cookie Path:', 'wpematico' ); ?></td>
						<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'The Session Cookie Path.', 'wpematico'  ) . '">[?]</a>'; ?></td>
						<td><?php echo $session_cookie_path; ?></td>
					</tr>				
					<tr>
						<td data-export-label="Save Path"><?php _e( 'Save Path:', 'wpematico' ); ?></td>
						<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'The Session Save Path.', 'wpematico'  ) . '">[?]</a>'; ?></td>
						<td><?php echo $session_save_path; ?></td>
					</tr>				
				<tr>
					<td data-export-label="Use Cookies"><?php _e( 'Use Cookies:', 'wpematico' ); ?></td>
					<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'Use Cookies.', 'wpematico'  ) . '">[?]</a>'; ?></td>
					<td><?php echo ( $session_use_cookies ) ? '&#10004;' : '&ndash;'; ?></td>
				</tr>
				<tr>
					<td data-export-label="Use Only Cookies"><?php _e( 'Use Only Cookies:', 'wpematico' ); ?></td>
					<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'Use Only Cookies.', 'wpematico'  ) . '">[?]</a>'; ?></td>
					<td><?php echo ( $session_use_only_cookies ) ? '&#10004;' : '&ndash;'; ?></td>
				</tr>
				<?php endif; ?>

			</tbody>
		</table>
		
		<h3 class="screen-reader-text"><?php _e( 'WordPress Environment', 'wpematico' ); ?></h3>
		<table class="widefat debug-section" cellspacing="0">
			<thead>
				<tr>
					<th colspan="3" class="debug-section-title" data-export-label="WordPress Environment"><?php _e( 'WordPress Environment', 'wpematico' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td data-export-label="User Browser"><?php _e( 'User Browser:', 'wpematico' ); ?></td>
					<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'The local users\' browser information.', 'wpematico'  ) . '">[?]</a>'; ?></td>
					<td><?php echo "<pre style='margin: 0;font-size: 11px;'>$browser</pre>"; ?></td>
				</tr>
				<tr>
					<td data-export-label="Home URL"><?php _e( 'Home URL:', 'wpematico' ); ?></td>
					<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'The URL of your site\'s homepage.', 'wpematico'  ) . '">[?]</a>'; ?></td>
					<td><?php echo $home_url; ?></td>
				</tr>
				<tr>
					<td data-export-label="Site URL"><?php _e( 'Site URL:', 'wpematico' ); ?></td>
					<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'The root URL of your site.', 'wpematico'  ) . '">[?]</a>'; ?></td>
					<td><?php echo $site_url; ?></td>
				</tr>
				<tr>
					<td data-export-label="WP Version"><?php _e( 'WP Version:', 'wpematico' ); ?></td>
					<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'The version of WordPress installed on your site.', 'wpematico'  ) . '">[?]</a>'; ?></td>
					<td><?php echo bloginfo('version'); ?></td>
				</tr>
				<tr>
					<td data-export-label="WP Multisite"><?php _e( 'WP Multisite:', 'wpematico' ); ?></td>
					<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'Whether or not you have WordPress Multisite enabled.', 'wpematico'  ) . '">[?]</a>'; ?></td>
					<td><?php if ($is_multisite) {
							echo '<mark class="no">' . '&#10004;' . __( 'WPeMatico was not fully tested in Multisite. Test it and give us your comments on the <a href="https://wordpress.org/support/plugin/wpematico/" target="_blank">forums</a>', 'wpematico' ) . '</mark>';
						} else {
							echo '<mark class="yes">' . __( 'No','wpematico') . '</mark>';
						} 
						?>
					</td>
				</tr>
				<tr>
					<td data-export-label="Language WPLANG"><?php _e( 'Language WPLANG:', 'wpematico' ); ?></td>
					<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'The current language set in wp-config.php, WPLANG constant. Default = en_US', 'wpematico'  ) . '">[?]</a>'; ?></td>
					<td><?php echo get_locale() ?></td>
				</tr>
				<tr>
					<td data-export-label="Language Setting"><?php _e( 'Language Setting:', 'wpematico' ); ?></td>
					<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'The current language used by WordPress. Default = English', 'wpematico'  ) . '">[?]</a>'; ?></td>
					<td><?php echo ( get_option( 'WPLANG' ) ? get_option( 'WPLANG' ) : 'Default' ) ?></td>
				</tr>

				<tr>
					<td data-export-label="Permalink Structure"><?php _e( 'Permalink Structure:', 'wpematico' ); ?></td>
					<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'The root URL of your site.', 'wpematico'  ) . '">[?]</a>'; ?></td>
					<td><?php echo $permalink_structure; ?></td>
				</tr>
				<tr>
					<td data-export-label="Active Theme"><?php _e( 'Active Theme:', 'wpematico' ); ?></td>
					<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'The version of WordPress installed on your site.', 'wpematico'  ) . '">[?]</a>'; ?></td>
					<td><?php echo $theme; ?></td>
				</tr>
				<tr>
					<td data-export-label="Show On Front"><?php _e( 'Show On Front:', 'wpematico' ); ?></td>
					<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'Wordpress option Show On Front.', 'wpematico'  ) . '">[?]</a>'; ?></td>
					<td><?php echo $show_on_front; ?>
					<?php if ($show_on_front == 'page') {
							echo '<br>  Page On Front:  ' . ($wp_front_page_id!= 'Unset' ? '<mark class="yes">' . $wp_front_page_id . '</mark>' : '<mark class="no">' . $wp_front_page_id . '</mark>') .'<br>' ;
							echo ' Page For Posts: ' . ($wp_blog_page_id!= 'Unset' ? '<mark class="yes">' . $wp_blog_page_id . '</mark>' : '<mark class="no">' . $wp_blog_page_id . '</mark>');
						} 
						?>
					</td>
				</tr>
				
				<tr>
					<td data-export-label="WP Remote Get"><?php _e( 'WP Remote Get:', 'wpematico' ); ?></td>
					<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'WPeMatico uses this method to communicate with the different RSS feeds and remote websites.', 'wpematico'  ) . '">[?]</a>'; ?></td>
					<td><?php echo ( $remote_get_work ) ? '<mark class="yes">&#10004;</mark>' : '<mark class="error">wp_remote_get() failed. Some theme features may not work. Please contact your hosting provider and make sure that https://etruel.com/downloads/feed/ is not blocked.</mark>'; ?></td>
				</tr>
				<tr>
					<td data-export-label="WP Remote Post"><?php _e( 'WP Remote Post:', 'wpematico' ); ?></td>
					<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'WPeMatico uses this method to communicate with the different RSS feeds and remote websites', 'wpematico'  ) . '">[?]</a>'; ?></td>
					<td><?php echo ($remote_post_work) ? '<mark class="yes">&#10004;</mark>' : '<mark class="error">wp_remote_post() failed. Some theme features may not work. Please contact your hosting provider and make sure that https://etruel.com/downloads/feed/ is not blocked.</mark>'; ?></td>
				</tr>
				<tr>
					<td data-export-label="Table Prefix"><?php _e( 'Table Prefix:', 'wpematico' ); ?></td>
					<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'The prefix of the DB tables names.', 'wpematico'  ) . '">[?]</a>'; ?></td>
					<td><?php echo 'Length: ' . $db_prefix . '   Status: ' . ( $db_prefix > 16 ? '<mark class="error">ERROR: Too long</mark>' : '<mark class="yes">Acceptable</mark>' ) ?></td>
				</tr>
				<tr>
					<td data-export-label="WP Memory Limit"><?php _e( 'WP Memory Limit:', 'wpematico' ); ?></td>
					<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'The maximum amount of memory (RAM) that your site can use at one time.', 'wpematico'  ) . '">[?]</a>'; ?></td>
					<td><?php
						if ( $wp_memory < 128000000 ) {
							echo '<mark class="no">' . sprintf( __( '%s - We recommend setting memory to at least <strong>128MB</strong>. <br /> Please define memory limit in <strong>wp-config.php</strong> file. To learn how, see: <a href="%s" target="_blank">Increasing memory allocated to PHP.</a>', 'wpematico' ), size_format( $wp_memory ), 'http://codex.wordpress.org/Editing_wp-config.php#Increasing_memory_allocated_to_PHP' ) . '</mark>';
						} else {
							echo '<mark class="yes">' . size_format( $wp_memory ) . '</mark>';
						}
					?></td>
				</tr>
				<tr>
					<td data-export-label="WP Max Upload Size"><?php _e( 'WP Max Upload Size:', 'wpematico' ); ?></td>
					<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'The largest file size that can be uploaded to your WordPress installation.', 'wpematico'  ) . '">[?]</a>'; ?></td>
					<td><?php echo size_format( $wp_max_upload_size ); ?></td>
				</tr>
				<tr>
					<td data-export-label="Registered Post Stati"><?php _e( 'Registered Post Stati:', 'wpematico' ); ?></td>
					<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'Registered Post Status by different custom post types or plugins.', 'wpematico'  ) . '">[?]</a>'; ?></td>
					<td><?php echo str_replace(',', ',<br/>', $post_stati ); ?></td>
				</tr>
				<tr>
					<td data-export-label="WP Debug Mode"><?php _e( 'WP Debug Mode:', 'wpematico' ); ?></td>
					<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'Displays whether or not WordPress is in Debug Mode.', 'wpematico'  ) . '">[?]</a>'; ?></td>
					<td><?php if ( defined('WP_DEBUG') && WP_DEBUG ) echo '<mark class="no">' . '&#10004;' . '</mark>'; else echo '<mark class="yes">' . '&ndash;' . '</mark>'; ?></td>
				</tr>
				<tr>
					<td data-export-label="WP Debug Log Mode"><?php _e( 'WP Debug Log Mode:', 'wpematico' ); ?></td>
					<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'Displays whether or not WordPress is writing its Debug in a file.', 'wpematico'  ) . '">[?]</a>'; ?></td>
					<td><?php if ( defined('WP_DEBUG_LOG') && WP_DEBUG_LOG ) echo '<mark class="no">' . '&#10004;' . '</mark>'; else echo '<mark class="yes">' . '&ndash;' . '</mark>'; ?></td>
				</tr>
				<tr>
					<td data-export-label="WP Debug Display"><?php _e( 'WP Debug Mode Display:', 'wpematico' ); ?></td>
					<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'Displays whether or not WordPress is showing in its site all warnings and errors reported by its Debug Mode.', 'wpematico'  ) . '">[?]</a>'; ?></td>
					<td><?php if ( defined('WP_DEBUG_DISPLAY') && WP_DEBUG_DISPLAY ) echo '<mark class="no">' . '&#10004;' . '</mark>'; else echo '<mark class="yes">' . '&ndash;' . '</mark>'; ?></td>
				</tr>
				<tr>
					<td data-export-label="WP Cron"><?php _e( 'WP Cron:', 'wpematico' ); ?></td>
					<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'The cron function of WordPress.', 'wpematico'  ) . '">[?]</a>'; ?></td>
					<td><?php if ( defined('DISABLE_WP_CRON') && DISABLE_WP_CRON ) echo '<mark class="no">' . '&ndash;' . esc_attr__( 'If you deactivates the cron function you should use WPeMatico in manual mode or with an external cron.', 'wpematico') . '</mark>'; else echo '<mark class="yes">' . '&#10004;' . '</mark>'; ?></td>
				</tr>
				<tr>
					<td data-export-label="WP Cron Lock Timeout"><?php _e( 'WP Cron Lock Timeout:', 'wpematico' ); ?></td>
					<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'Defines a period of time in which only one cronjob will be fired. Since WordPress 3.3. Value: time in seconds (Default: 60).', 'wpematico'  ) . '">[?]</a>'; ?></td>
					<td><?php if ( defined('WP_CRON_LOCK_TIMEOUT') ) echo WP_CRON_LOCK_TIMEOUT==60 ? '<mark class="yes">' . 60 . '</mark>' : '<mark class="error">' . WP_CRON_LOCK_TIMEOUT . '</mark>'; else echo '<mark class="no">' . '&ndash;' . '</mark>'; ?></td>
				</tr>
				<tr>
					<td data-export-label="Alternate WP Cron"><?php _e( 'Alternate WP Cron:', 'wpematico' ); ?></td>
					<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'Some servers disable the functionality that enables WordPress Cron to work properly. This constant provides an easy fix that should work on any server.', 'wpematico'  ) . '">[?]</a>'; ?></td>
					<td><?php if ( defined('ALTERNATE_WP_CRON') && ALTERNATE_WP_CRON ) echo '<mark class="no">' . '&#10004;' . '</mark>'; else echo '<mark class="yes">' . '&ndash;' . '</mark>'; ?> </td>
				</tr>
			</tbody>
		</table>

	<?php //if (count( (array) $muplugins ) > 0 ) : ?>
		<h3 class="screen-reader-text"><?php _e( 'Must-Use Plugins', 'wpematico' ); ?></h3>
		<table class="widefat debug-section" cellspacing="0" id="status">
			<thead>
				<tr>
					<th colspan="3" class="debug-section-title" data-export-label="Must-Use Plugins (<?php echo count( (array) $muplugins ); ?>)"><?php _e( 'Must-Use Plugins', 'wpematico' ); ?> (<?php echo count( (array) $muplugins ); ?>)</th>
				</tr>
			</thead>
			<tbody>
				<?php			
				foreach ( $muplugins as $plugin ) {
					$new_version    = $plugin['new_version'];
					$plugin_data    = $plugin['plugin_data'];
					$dirname        = $plugin['dirname'];
					$version_string = $plugin['version_string'];
					$network_string = $plugin['network_string'];

					if ( ! empty( $plugin_data['Name'] ) ) {

						// link the plugin name to the plugin url if available
						$plugin_name = esc_html( $plugin_data['Name'] );

						if ( ! empty( $plugin_data['PluginURI'] ) ) {
							$plugin_name = '<a href="' . esc_url( $plugin_data['PluginURI'] ) . '" title="' . __( 'Visit plugin homepage' , 'wpematico' ) . '">' . $plugin_name . '</a>';
						}
						?>
						<tr>
							<td><?php echo $plugin_name; ?></td>
							<td class="help">&nbsp;<?php echo $plugin_data['Version']; ?>
								<?php if (!empty($new_version)) : ?>
									<strong><?php printf(__('(needs update - %s)', 'wpematico'), $new_version); ?></strong>
								<?php endif; ?>
							</td>
							<td><?php printf( _x( 'by %s', 'by author', 'wpematico' ), $plugin_data['Author'] ) . ' &ndash; ' . esc_html( $plugin_data['Version'] ) . $version_string . $network_string; ?></td>
						</tr>
						<?php
					}
				}
				?>
			</tbody>
		</table>
	<?php //endif; // (count($muplugins ) > 0 ?>

		<h3 class="screen-reader-text"><?php _e( 'Active Plugins', 'wpematico' ); ?></h3>
		<table class="widefat debug-section" cellspacing="0" id="status">
			<thead>
				<tr>
					<th colspan="3" class="debug-section-title" data-export-label="Active Plugins (<?php echo count( (array) $active_plugins ); ?>)"><?php _e( 'Active Plugins', 'wpematico' ); ?> (<?php echo count( (array) $active_plugins ); ?>)</th>
				</tr>
			</thead>
			<tbody>
				<?php				
				foreach ( $active_plugins as $plugin ) {
					$new_version    = $plugin['new_version'];
					$plugin_data    = $plugin['plugin_data'];
					$dirname        = $plugin['dirname'];
					$version_string = $plugin['version_string'];
					$network_string = $plugin['network_string'];

					if ( ! empty( $plugin_data['Name'] ) ) {

						// link the plugin name to the plugin url if available
						$plugin_name = esc_html( $plugin_data['Name'] );

						if ( ! empty( $plugin_data['PluginURI'] ) ) {
							$plugin_name = '<a href="' . esc_url( $plugin_data['PluginURI'] ) . '" title="' . __( 'Visit plugin homepage' , 'wpematico' ) . '">' . $plugin_name . '</a>';
						}
						?>
						<tr>
							<td><?php echo $plugin_name; ?></td>
							<td class="help">&nbsp;<?php echo $plugin_data['Version']; ?>
								<?php if (!empty($new_version)) : ?>
									<strong><?php printf(__('(needs update - %s)', 'wpematico'), $new_version); ?></strong>
								<?php endif; ?>
							</td>
							<td><?php printf( _x( 'by %s', 'by author', 'wpematico' ), $plugin_data['Author'] ) . ' &ndash; ' . esc_html( $plugin_data['Version'] ) . $version_string . $network_string; ?></td>
						</tr>
						<?php
					}
				}
				?>
			</tbody>
		</table>
		
<?php
}




add_action( 'wpematico_set_danger_data', 'wpematico_save_danger_data' );
function wpematico_save_danger_data() {
	if ( 'POST' === $_SERVER[ 'REQUEST_METHOD' ] ) {
		if ( get_magic_quotes_gpc() ) {
			$_POST = array_map( 'stripslashes_deep', $_POST );
		}	
		check_admin_referer('wpematico-danger');
		$danger['wpemdeleoptions'] = (isset($_POST['wpemdeleoptions']) && !empty($_POST['wpemdeleoptions']) ) ? $_POST['wpemdeleoptions'] : false;
		$danger['wpemdelecampaigns'] = (isset($_POST['wpemdelecampaigns']) && !empty($_POST['wpemdelecampaigns']) ) ? $_POST['wpemdelecampaigns'] : false;
		if( update_option( 'WPeMatico_danger', $danger ) or add_option( 'WPeMatico_danger', $danger ) ) {
			WPeMatico::add_wp_notice( array('text' => __('Actions to Uninstall saved.',  'wpematico').'<br>'.__('The actions are executed when the plugin is uninstalled.',  'wpematico'), 'below-h2'=>false ) );
		}
		wp_redirect( admin_url( 'edit.php?post_type=wpematico&page=wpematico_settings&tab=debug_info&section=danger_zone') );
	}
}
/**
 * Get system info
 *
 * @since       1.2.4
 * @access      public
 * @global      object $wpdb Used to query the database using the WordPress Database API
 * @return      string $return A string containing the info to output
 */
function wpematico_debug_info_get() {
	//global $wpdb;
	$cfg = get_option(WPeMatico :: OPTION_KEY);
	$cfg = apply_filters('wpematico_check_options', $cfg); 
	$debug_data = wpematico_debug_data(); 
	extract($debug_data);

	$return  = '<pre>### Begin Debug Info ###' . "\n\n";

	$return .= "" . '-- Server Environment' . "\n\n";
	// Can we determine the site's host?
	if( $host ) {
		$return .= 'Hosting Provider:         ' . $host . "\n";
		$return  = apply_filters( 'wpematico_sysinfo_after_host_info', $return );
	}

	// Server configuration (really just versioning)
	$return .= 'WebServer Info:           ' . $_SERVER['SERVER_SOFTWARE'] . "\n";
	$return .= 'MySQL Version:            ' . $db_version . "\n";
	$return .= 'PHP Version:              ' . esc_html( phpversion() ) . "\n";
	$return .= 'Disk Total Space:         ' . $disk_total_space . "\n";
	$return .= 'Disk Free Space:          ' . $disk_free_space . "\n";
	$return  = apply_filters( 'wpematico_sysinfo_after_webserver_config', $return );

	$return .= "\n" . '-- Required Apache Mods' . "\n";
	$return .= 'Mod Rewrite:             ' . ( ($m_rewrite_ok) ? 'Enabled' : 'Disabled' ) . "\n";
	$return .= 'Mod Mime:                ' . ( ($m_mime_ok) ? 'Enabled' : 'Disabled' ) . "\n";
	$return .= 'Mod Deflate:             ' . ( ($m_deflate_ok) ? 'Enabled' : 'Disabled' ) . "\n";
					 
	$return  = apply_filters( 'wpematico_sysinfo_after_apache_mods', $return );

	$return .= "\n" . '-- PHP Environment' . "\n\n";
	
	$return .= 'Post Max Size:           ' . $post_max_size . "\n";
	$return .= 'Max Input Vars:          ' . $max_input_vars . "\n";
	$return .= 'PHP Time Limit:          ' . $time_limit . "\n";
	$return .= 'PHP Memory Limit:        ' . size_format( $memory ) . "\n";
//	$return .= 'Upload Max Filesize:     ' . $upload_max_filesize . "\n";
	$return .= 'Safe Mode:               ' . ( $safe_mode ? 'Enabled' : 'Disabled' ) . "\n";
	$return .= 'Allow URL fopen:         ' . ( $allow_url_fopen ? 'On' : 'Off' ) . "\n";
	$return .= 'ini_set:         ' . ( $ini_set ? 'On' : 'Off' ) . "\n";
	$return .= 'Disabled Functions:      ' . $disable_functions . "\n";
	$return .= 'Display Errors:          ' . ( $display_errors ? 'On (' . $display_errors . ')' : 'N/A' ) . "\n";
	if ( $display_errors ) {
		$return .= 'error_reporting levels:  ';
		$errLvl = error_reporting();
		for ($i = 0; $i < 15;  $i++ ) {
			$return .= wpematico_FriendlyErrorType($errLvl & pow(2, $i)) . ", ";
		}
	}

	$return  = apply_filters( 'wpematico_sysinfo_after_php_config', $return );

	// PHP extensions and such
	$return .= "\n\n" . '-- PHP Extensions' . "\n\n";
	
	// SimplePie required extensions and such	
	$return .= 'cURL (php.net/curl):     ' . ( ($curl_ok) ? 'Enabled' : 'Disabled' ) . "\n";
	$return .= 'ZipArchive:              ' . ( ($ZipArchive) ? 'Enabled' : 'Disabled' ) . "\n";
	$return .= 'DOMDocument:             ' . ( ($DOMDocument) ? 'Enabled' : 'Disabled' ) . "\n";
	$return .= 'GD Library:              ' . ( ($GD_ok) ? 'Enabled' : 'Disabled' ) . "\n";
	$return .= 'XML (php.net/xml):       ' . ( ($xml_ok) ? 'Enabled, and sane' : 'Disabled, or broken' ) . "\n";
	$return .= 'PCRE (php.net/pcre):     ' . ( ($pcre_ok) ? 'Enabled' : 'Disabled' ) . "\n";
	$return .= 'Zlib (php.net/zlib):     ' . ( ($zlib_ok) ? 'Enabled' : 'Disabled' ) . "\n";
	$return .= 'php.net/mbstring:        ' . ( ($mbstring_ok) ? 'Enabled' : 'Disabled' ) . "\n";
	$return .= 'iconv (php.net/iconv):   ' . ( ($iconv_ok) ? 'Enabled' : 'Disabled' ) . "\n";
	$return .= 'OpenSSL(php.net/openssl):' . ( ($ssl_ok) ? 'Enabled' : 'Disabled' ) . "\n";
	$return .= 'MCrypt (php.net/mcrypt): ' . ( ($mcrypt_ok) ? 'Enabled' : 'Disabled' ) . "\n";
//	$return .= 'fsockopen:               ' . ( function_exists( 'fsockopen' ) ? 'Supported' : 'Not Supported' ) . "\n";
//	$return .= 'SOAP Client:             ' . ( class_exists( 'SoapClient' ) ? 'Installed' : 'Not Installed' ) . "\n";

	$return  = apply_filters( 'wpematico_sysinfo_after_simplepie_ext', $return );
	$return  = apply_filters( 'wpematico_sysinfo_after_php_ext', $return );

	// Session stuff
	$return .= "\n" . '-- Session Configuration' . "\n";
	$return .= 'Session:                  ' . ( isset( $_SESSION ) ? 'Enabled' : 'Disabled' ) . "\n";

	// The rest of this is only relevant is session is enabled
	if( isset( $_SESSION ) ) {
		$return .= 'Session Name:             ' . $session_name . "\n";
		$return .= 'Cookie Path:              ' . $session_cookie_path . "\n";
		$return .= 'Save Path:                ' . $session_save_path . "\n";
		$return .= 'Use Cookies:              ' . ( $session_use_cookies ? 'On' : 'Off' ) . "\n";
		$return .= 'Use Only Cookies:         ' . ( $session_use_only_cookies ? 'On' : 'Off' ) . "\n";
	}

	$return  = apply_filters( 'wpematico_sysinfo_after_session_config', $return );

	// Start with the basics...
	$return .= "\n" . '-- WordPress Environment' . "\n\n";
	// The local users' browser information, handled by the Browser class
	$return .= "" . '-- User Browser' . "\n";
	$return .= $browser . "\n";
	$return  = apply_filters( 'wpematico_sysinfo_after_user_browser', $return );

	$return .= 'Home URL:                 ' . $home_url . "\n";
	$return .= 'Site URL:                 ' . $site_url . "\n";
	$return .= 'Version:                  ' . get_bloginfo( 'version' ) . "\n";
	$return .= 'Multisite:                ' . ($is_multisite ? 'Yes' : 'No' ) . "\n";
	$return  = apply_filters( 'wpematico_sysinfo_after_site_info', $return );

	// WordPress configuration
	$return .= "\n" . '-- WordPress Configuration' . "\n";
	$return .= 'Language WPLANG:          ' . get_locale() . "\n";
	$return .= 'Language Setting:         ' . ( get_option( 'WPLANG' ) ? get_option( 'WPLANG' ) : 'Default' ) . "\n";
	$return .= 'Permalink Structure:      ' . $permalink_structure . "\n";
	$return .= 'Active Theme:             ' . $theme . "\n";
	$return .= 'Show On Front:            ' . $show_on_front . "\n";
	// Only show page specs if frontpage is set to 'page'
	if( get_option( 'show_on_front' ) == 'page' ) {
		$return .= 'Page On Front:            ' . $wp_front_page_id  . "\n";
		$return .= 'Page For Posts:           ' . $wp_blog_page_id . "\n";
	}
	$return .= 'Remote Get:               ' . ($remote_get_work ? 'wp_remote_get() works' : 'wp_remote_get() does not work' ) . "\n";
	$return .= 'Remote Post:              ' . ($remote_post_work ? 'wp_remote_post() works' : 'wp_remote_post() does not work' ) . "\n";
	$return .= 'Table Prefix:             ' . 'Length: ' . $db_prefix . '   Status: ' . ( $db_prefix > 16 ? 'ERROR: Too long' : 'Acceptable' ) . "\n";

	$return .= 'Memory Limit:             ' . size_format( $wp_memory ) . "\n";
	$return .= 'WP Max Upload Size:       ' . size_format( $wp_max_upload_size ) . "\n";
	$return .= 'Registered Post Stati:    ' . $post_stati . "\n";
	
	$return .= 'WP_DEBUG:                 ' . ( defined( 'WP_DEBUG' ) ? WP_DEBUG ? 'Enabled' : 'Disabled' : 'Not set' ) . "\n";
	$return .= 'WP_DEBUG_LOG:             ' . ( defined( 'WP_DEBUG_LOG' ) ? WP_DEBUG_LOG ? 'Enabled' : 'Disabled' : 'Not set' ) . "\n";
	$return .= 'WP_DEBUG_DISPLAY:         ' . ( defined( 'WP_DEBUG_DISPLAY' ) ? WP_DEBUG_DISPLAY ? 'Enabled' : 'Disabled' : 'Not set' ) . "\n";

	$return .= 'DISABLE_WP_CRON:          ' . ( defined( 'DISABLE_WP_CRON' ) ? DISABLE_WP_CRON ? 'True' : 'False' : 'Not set' ) . "\n";
	$return .= 'WP_CRON_LOCK_TIMEOUT:     ' . ( defined( 'WP_CRON_LOCK_TIMEOUT' ) ? WP_CRON_LOCK_TIMEOUT : 'Not set' ) . "\n";
	$return .= 'ALTERNATE_WP_CRON:        ' . ( defined( 'ALTERNATE_WP_CRON' ) ? ALTERNATE_WP_CRON ? 'Enabled' : 'Disabled' : 'Not set' ) . "\n";


	$return  = apply_filters( 'wpematico_sysinfo_after_wordpress_config', $return );

	
	// WPeMatico configuration
	$return .= "\n" . '-- WPeMatico Configuration' . "\n\n";
	$return .= 'Version:                  ' . WPeMatico::$version . "\n";

	foreach($cfg as $name => $value): 
		if ( wpematico_option_blacklisted($name)) continue; 
		$value = sanitize_option($name, $value); 
		$return .= $name . ":\t\t" . ((is_array($value))? print_r($value,1): esc_html($value)) . "\n";
	endforeach;

	$return  = apply_filters( 'wpematico_sysinfo_after_wpematico_config', $return );

    // Must-use plugins
    if( count( $muplugins > 0 ) ) {
        $return .= "\n" . '-- Must-Use Plugins (' .count( (array) $muplugins ) .')' . "\n\n";

        foreach( $muplugins as $plugin => $plugin_data ) {
            $return .= $plugin_data['Name'] . ': ' . $plugin_data['Version'] . "\n";
        }

        $return = apply_filters( 'wpematico_sysinfo_after_wordpress_mu_plugins', $return );
    }

	// WordPress active plugins
	$return .= "\n" . '-- WordPress Active Plugins (' .count( (array) $active_plugins ) .')' . "\n\n";
	foreach ($active_plugins as $key => $plugin) {
		$new_version    = $plugin['new_version'];
		$plugin_data    = $plugin['plugin_data'];

		if ( ! empty( $plugin_data['Name'] ) ) {
			$plugin_name = esc_html( $plugin_data['Name'] );
			$return .= $plugin_name . ': ' . $plugin_data['Version'].(!empty($new_version)?' (needs update - '.$new_version.')': ''). "\n";
		}
	}
	$return  = apply_filters( 'wpematico_sysinfo_after_wordpress_plugins', $return );

	// WordPress inactive plugins
	$plugins = get_plugins();
	$return .= "\n" . '-- WordPress Inactive Plugins' . "\n\n";
	foreach( $plugins as $plugin_path => $plugin ) {
		if( in_array( $plugin_path, wpematico_get_option_active_plugins()) )
			continue;
		$new_version = wpematico_get_plugin_new_version($plugin_path);
		$return .= $plugin['Name'] . ': ' . $plugin['Version'].(!empty($new_version)?' (needs update - '.$new_version.')': ''). "\n";
	}

	$return = apply_filters( 'wpematico_sysinfo_after_wordpress_plugins_inactive', $return );

	
	// WordPress CONSTANTS filtering users & passwords
	$return .= "\n" . '-- WordPress user Defined Constants' . "\n\n";

	$wp_constants = get_defined_constants(1);
	unset($wp_constants['user']['DB_USER']);
	unset($wp_constants['user']['DB_PASSWORD']);
	unset($wp_constants['user']['AUTH_KEY']);
	unset($wp_constants['user']['SECURE_AUTH_KEY']);
	unset($wp_constants['user']['LOGGED_IN_KEY']);
	unset($wp_constants['user']['NONCE_KEY']);
	unset($wp_constants['user']['AUTH_SALT']);
	unset($wp_constants['user']['SECURE_AUTH_SALT']);
	unset($wp_constants['user']['LOGGED_IN_SALT']);
	unset($wp_constants['user']['NONCE_SALT']);
	unset($wp_constants['user']['COOKIEHASH']);
	unset($wp_constants['user']['USER_COOKIE']);
	unset($wp_constants['user']['PASS_COOKIE']);
	unset($wp_constants['user']['AUTH_COOKIE']);
	unset($wp_constants['user']['SECURE_AUTH_COOKIE']);
	unset($wp_constants['user']['LOGGED_IN_COOKIE']);
	unset($wp_constants['user']['TEST_COOKIE']);
	
	$return .= print_r($wp_constants['user'], 1);

	$return  = apply_filters( 'wpematico_sysinfo_after_get_defined_constants', $return );

	$return .= "\n\n" . '### End Debug Info ###</pre>';

	return $return;
}


/**
 * Generates a System Info download file
 *
 * @since       2.0
 * @return      void
 */
function wpematico_debug_info_download() {
	check_admin_referer('wpematico-settings');
	nocache_headers();

	header( 'Content-Type: text/plain' );
	header( 'Content-Disposition: attachment; filename="wpematico-debug-info.txt"' );
	
	echo wp_strip_all_tags( $_POST['wpematico-sysinfo'] );

	if( !empty($_POST['alsophpinfo']) ) {
		echo "\n\n" . '-- PHPInfo --' . "\n\n";  
		echo 'PHPInfo:                  ' . ( (!strpos(ini_get( 'disable_functions' ),'phpinfo')) ? 'Enabled' : 'Disabled' ) . "\n\n";
		if (!strpos(ini_get( 'disable_functions' ),'phpinfo')) :
			unset( $_REQUEST["wpematico-sysinfo"]);
			unset( $_POST["wpematico-sysinfo"]);
			phpinfo();
		endif;
	}

	do_action('wpematico_download_debug_file_extra_data');

	if( !empty($_POST['alsocampaignslogs']) ) {
		echo "\n\n" . '-- LAST CAMPAIGNS LOG --' . "<br />\n\n";  
		$args = array(
			'orderby'         => 'ID',
			'order'           => 'ASC',
			'post_type'       => 'wpematico', 
			'numberposts' => -1
		);
		$campaigns = get_posts( $args );
		foreach( $campaigns as $post ):
			echo "<br />\n\n" . '### CAMPAIGN ID Name:     ' . $post->ID .' '.get_the_title($post->ID) . "<br />\n\n";
			echo get_post_meta( $post->ID, 'last_campaign_log', true ); 	
		endforeach; 
	}
	echo "\n\n" . '-- ENDFILE --' . "\n";  
	die();
	
// +++ COMENTADO si lo quiero parseado sin html
//	$return = wp_strip_all_tags( $_POST['wpematico-sysinfo'] );
	
//	if( $_POST['alsophpinfo']==1 ) {
//		$return .= "\n\n" . '-- PHPInfo --' . "\n\n";  
//		$return .= 'PHPInfo:                  ' . ( (!strpos(ini_get( 'disable_functions' ),'phpinfo')) ? 'Enabled' : 'Disabled' ) . "\n\n";
//		if (!strpos(ini_get( 'disable_functions' ),'phpinfo')) :
//			ob_start();
//			phpinfo();
//			$phpinfo = ob_get_contents();
//			ob_end_clean();
//			$phpinfo = str_replace("</td","  </td",$phpinfo);
//			$return .= wp_strip_all_tags($phpinfo);
//			$return .= $phpinfo;
//		endif;
//	}
//	echo $return;
//	die();
	
}
add_action( 'wpematico_download_debug_info', 'wpematico_debug_info_download' );


function wpematico_option_blacklisted($setting) {
	// TODO: add other settings from premium modules
	$blacklisted = array(
		'mailsendmail',
		'mailsecure',
		'mailhost',
		'mailport',
		'mailuser',
		'mailpass',
	);
	return in_array($setting, $blacklisted);
}


/**
	 * wpematico_let_to_num function.
	 *
	 * This function transforms the php.ini notation for numbers (like '2M') to an integer.
	 *
	 * @since 1.6.3
	 *
	 * @param $size
	 * @return int
	 */
	function wpematico_let_to_num( $size ) {
		$l   = substr( $size, -1 );
		$ret = substr( $size, 0, -1 );
		switch ( strtoupper( $l ) ) {
			case 'P':
				$ret *= 1024;
			case 'T':
				$ret *= 1024;
			case 'G':
				$ret *= 1024;
			case 'M':
				$ret *= 1024;
			case 'K':
				$ret *= 1024;
		}
		return $ret;
	}
