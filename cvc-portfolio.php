<?php
/**
 * Plugin Name:     CVC Portfolio
 * Plugin URI:      https://bitbucket.org/superiocity/wp-plugin-cvc-portfolio
 * Description:     Store and display projects done by CVC Hospitality.
 * Author:          Larry Kagan
 * Author URI:      http://www.superiocity.com
 * Text Domain:     cvc
 * Version:         0.1.0
 *
 * @package Superiocity\CVC
 */

namespace Superiocity\CVC;

const TEXT_DOMAIN = 'cvc';
require_once 'includes/posttype.php';
require_once __DIR__ . '/includes/taxonomy.php';


/**
 * Class Portfolio
 */
class Portfolio
{
	/**
	 * Handles custom post types.
	 *
	 * @var Posttype $posttype
	 */
	protected $posttypes;


	/**
	 * Portfolio constructor.
	 */
	public function __construct() {
		$this->posttypes = new Posttype();

		add_action( 'plugins_loaded', array( $this, 'check_for_acf' ) );
		add_action( 'init', array( $this->posttypes, 'project_init' ) );
		add_shortcode( 'cvc_portfolio', array( $this, 'list_projects' ) );
		add_filter( 'excerpt_length', array( $this, 'change_excerpt_length' ) );
		add_filter( 'excerpt_more', array( $this, 'change_excerpt_more' ) );
		register_activation_hook( __FILE__, array( $this, 'activation' ) );
		register_deactivation_hook( __FILE__, array( $this, 'deactivation' ) );
		add_image_size( 'medium-square', 300, 300, true );
	}


	/**
	 * Set the excerpt length for projects only.
	 *
	 * @param string $orig_more Original 'more' text.
	 *
	 * @return string
	 */
	public function change_excerpt_more( $orig_more ) : string {
		return 'project' === $GLOBALS['post']->post_type ?
			' <span class="more"> (cont\'d)...</span>' : $orig_more;
	}


	/**
	 * Set the excerpt length for projects only.
	 *
	 * @param int $length Default length of excerpt.
	 *
	 * @return int
	 */
	public function change_excerpt_length( int $length ) : int {
		return 'project' === $GLOBALS['post']->post_type ? 25 : $length;
	}


	/**
	 * Output the list of projects.
	 *
	 * @return string
	 */
	public function list_projects() : string {
		ob_start();
		$project_query = new \WP_Query( array( 'post_type' => 'project' ) );
		include 'includes/project-list.php';
		return ob_get_clean();
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

	/**
	 * Handle activation setup.
	 */
	public function activation() {
		$this->posttypes->project_init();
		flush_rewrite_rules();
	}


	/**
	 * Handle deactivation clean up.
	 */
	public function deactivation() {
		flush_rewrite_rules();
	}
}

new Portfolio();
