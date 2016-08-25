<?php
/**
 * Plugin Name:     CVC Portfolio
 * Plugin URI:      https://bitbucket.org/superiocity/wp-plugin-cvc-portfolio
 * Description:     Store and display projects done by CVC Hospitality.
 * Author:          Larry Kagan
 * Author URI:      http://www.superiocity.com
 * Text Domain:     cvc-portfolio
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 * @package Superiocity\CVC
 */

namespace Superiocity\CVC;

const TEXT_DOMAIN = 'cvc';
require_once 'includes/posttypes.php';


/**
 * Class Portfolio
 */
class Portfolio
{
	/**
	 * Portfolio constructor.
	 */
	public function __construct() {
		new Posttypes();

		// Check for required plugin dependencies.
		add_action( 'plugins_loaded', array( $this, 'check_for_acf' ) );
	}


	/**
	 * Ensure Advanced Custom Fields plugin is activated.
	 *
	 * @return bool
	 */
	public function check_for_acf(): bool {
		if ( ! is_plugin_active( 'advanced-custom-fields-pro/acf.php' ) ) {
			add_action( 'admin_notices', array( $this, 'trigger_notify_no_acf' ) );
			return false;
		}

		return true;
	}


	/**
	 * Display an admin dashboard notification of missing ACF plugin.
	 */
	public function trigger_notify_no_acf() {
		$msg = ' requires that the Advanced Custom Fields Pro 
		       plugin be installed and activated.';
		$msg = get_plugin_data( __FILE__ )['Name'] . esc_html__( $msg, TEXT_DOMAIN );
		?><div class="notice notice-error"><p><?php echo $msg ?></p></div><?php
	}
}

new Portfolio();
