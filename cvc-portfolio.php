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
		add_action( 'wp_enqueue_scripts', array( $this, 'load_scripts_styles' ) );
		add_shortcode( 'cvc_portfolio', array( $this, 'list_projects' ) );
		add_filter( 'excerpt_length', array( $this, 'change_excerpt_length' ) );
		add_filter( 'excerpt_more', array( $this, 'change_excerpt_more' ) );
		add_filter( 'manage_edit-project_columns', array( $this->posttypes, 'add_admin_column_headers' ) );
		add_filter( 'the_content', array( $this, 'append_gallery' ) );
		add_action( 'manage_posts_custom_column', array( $this->posttypes, 'add_admin_column_data' ) );
		register_activation_hook( __FILE__, array( $this, 'activation' ) );
		register_activation_hook( __FILE__, array( $this, 'activation' ) );
		register_deactivation_hook( __FILE__, array( $this, 'deactivation' ) );
		add_image_size( 'medium-square', 300, 300, true );
	}


	/**
	 * Enqueue the CSS and JS.
	 */
	public function load_scripts_styles() {
		$css_filename = 'style.css';
		$css_url = plugin_dir_url( __FILE__ ) . $css_filename;
		$css_path = plugin_dir_path( __FILE__ ) . $css_filename;
		$css_modified = filemtime( $css_path );
		wp_enqueue_style( 'cvc-portfolio', $css_url, null, $css_modified );

		$js_filename = '/js/main.min.js';
		$js_url = plugin_dir_url( __FILE__ ) . $js_filename;
		$js_path = plugin_dir_path( __FILE__ ) . $js_filename;
		$js_modified = filemtime( $js_path );
		wp_enqueue_script( 'cvc-portfolio', $js_url, null, $js_modified );
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
		return 'project' === $GLOBALS['post']->post_type ? 15 : $length;
	}


	/**
	 * Output the list of projects.
	 *
	 * @return string
	 */
	public function list_projects() : string {
		ob_start();
		$photo_projects = new \WP_Query( array(
			'post_type'      => 'project',
			'posts_per_page' => - 1,
			'orderby'        => 'title',
			'order'          => 'asc',
			'meta_query'     => array (
				array(
					'key'           => 'photos',
					'value'         => '',
					'compare'       => '!=',
				),
			),
		) );

		$projects = new \WP_Query( array(
			'post_type'      => 'project',
			'posts_per_page' => - 1,
			'orderby'        => 'title',
			'order'          => 'asc',
			'meta_query'     => array (
				array(
					'key'           => 'photos',
					'value'         => '',
					'compare'       => '=',
				),
			),
		) );

		include 'includes/project-list.php';
		return ob_get_clean();
	}


	/**
	 * Return a title for the project.
	 *
	 * @return string
	 */
	protected function get_title_formatted() : string {
		global $post;
		$location = esc_html( get_field( 'city' ) );
		$state = esc_html( get_field( 'state' ) );
		$title = esc_html( get_the_title( $post->ID ) );
		$title .= get_field( 'type' ) ? ' ' . esc_html( get_field( 'type' ) ) : '';

		if ( ! empty( $location ) || ! empty( $state ) ) {
			$title .= '<span class="location">';
			$title .= $location ? ' - ' . $location : '';
			$title .= $state ? ', ' . substr( $state , 0, 2 ) : '';
			$title .= '</span>';
		}

		return $title;
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
		$msg = ' requires that the Advanced Custom Fields Pro ' .
		       'plugin be installed and activated.';
		$msg = get_plugin_data( __FILE__ )['Name'] . esc_html__( $msg, TEXT_DOMAIN );

		if ( is_admin() ) {
			?><div class="notice notice-error"><p><?php echo $msg ?></p></div><?php
		}
		trigger_error( $msg );
	}

	/**
	 * Handle activation setup.
	 */
	public function activation() {
		remove_action( 'init', array( $this->posttypes, 'project_init' ) );
		$this->posttypes->project_init();
		flush_rewrite_rules();
	}


	/**
	 * Handle deactivation clean up.
	 */
	public function deactivation() {
		flush_rewrite_rules();
	}


	/**
	 * Append the gallery to project posts that have images.
	 *
	 * @param string $content Orginal content of post.
	 *
	 * @return string Updated content of post.
	 */
	public function append_gallery( string $content ) : string {

		// Only do work if the post type is a 'project' and we're on a single post.
		if ( ! ( is_single() && 'project' === $GLOBALS['post']->post_type ) ) {
			return $content;
		}

		// Grab images associated with project.
		$images = get_field( 'photos' );
		ob_start();
		include 'includes/gallery.php';
		$gallery = ob_get_clean();
		return $content . $gallery;
	}
}

new Portfolio();
