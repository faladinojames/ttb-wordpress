<?php
// don't load directly 
if ( !defined('ABSPATH') ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}
/**
 * Campaign Logs Class
 * @package     WPeMatico
 * @subpackage  Admin/CampaignLog
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.8.0
 */
class campaign_logs {

	/**
	* Static function hooks
	* This function exec all the hooks to work with campaign logs.
	* @access public
	* @return void
	* @since 1.8.0
	*/
	public static function hooks() {
		add_action( 'admin_post_wpematico_campaign_log', array(__CLASS__, 'print_log'));
	}
	/**
	* Static function print_log
	* This function prints the HTML page with the contents of the logs.
	* @access public
	* @return void
	* @since version
	*/
	public static function print_log() {
		$nonce = $_REQUEST['_wpnonce'];
		if(!wp_verify_nonce($nonce, 'clog-nonce') ) {
			wp_die('Are you sure?'); 
		} 

		if ( isset( $_GET['p'] ) ) {
		 	$post_id = $post_ID = (int) $_GET['p'];
		} elseif ( isset( $_POST['post_ID'] ) ) {
		 	$post_id = $post_ID = (int) $_POST['post_ID'];
		} else {
		 	$post_id = $post_ID = 0;
		}

		$log = get_post_meta( $post_id , 'last_campaign_log', true);

		?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US">
		<head profile="http://gmpg.org/xfn/11">
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		</head>
		<body>
		<h1><?php printf(__('Last Log of Campaign %s: %s', 'wpematico'), $post_id, get_the_title($post_id)); ?> </h1>
		<?php
		echo $log;

		?></body>
		</html> 
	<?php
	}

}
campaign_logs::hooks();