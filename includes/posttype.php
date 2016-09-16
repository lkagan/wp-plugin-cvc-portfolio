<?php
/**
 * Setup custom post types.
 *
 * @package Superiocity\CVC
 */

namespace Superiocity\CVC;

/**
 * Register and hook post types and related messages.
 */
class Posttype {

	/**
	 * Posttype constructor sets up hooks.
	 */
	public function __construct() {
		add_filter( 'post_updated_messages', array( $this, 'posttype_project_messages' ) );
	}


	/**
	 * Initialize the 'proejct' post type.
	 */
	public function project_init() {
		// @codingStandardsIgnoreStart
		register_post_type( 'project', array(
			'labels' => array(
				'name'               => __( 'Portfolio', TEXT_DOMAIN ),
				'singular_name'      => __( 'Project', TEXT_DOMAIN ),
				'all_items'          => __( 'All Projects', TEXT_DOMAIN ),
				'new_item'           => __( 'New Project', TEXT_DOMAIN ),
				'add_new'            => __( 'Add New Project', TEXT_DOMAIN ),
				'add_new_item'       => __( 'Add New Project', TEXT_DOMAIN ),
				'edit_item'          => __( 'Edit Project', TEXT_DOMAIN ),
				'view_item'          => __( 'View Project', TEXT_DOMAIN ),
				'search_items'       => __( 'Search Projects', TEXT_DOMAIN ),
				'not_found'          => __( 'No Projects Found', TEXT_DOMAIN ),
				'not_found_in_trash' => __( 'No Projects Found In Trash', TEXT_DOMAIN ),
				'parent_item_colon'  => __( 'Parent Project', TEXT_DOMAIN ),
				'menu_name'          => __( 'Portfolio', TEXT_DOMAIN ),
			),
			'public'                => true,
			'hierarchical'          => false,
			'show_ui'               => true,
			'show_in_nav_menus'     => true,
			'supports'              => array(
				'title',
				'editor',
				'excerpt',
				'revisions',
			),
			'has_archive'           => false,
			'rewrite'               => array(
				'slug'              => 'portfolio',
			),
			'query_var'             => true,
			'menu_icon'             => 'dashicons-building',
			'show_in_rest'          => true,
			'rest_base'             => 'project',
			'rest_controller_class' => 'WP_REST_Posts_Controller',
		) );
		// @codingStandardsIgnoreStart
	}


	/**
	 * Configure the messages associated with the 'project' post type.
	 *
	 * @param array $messages Default messages array.
	 *
	 * @return array
	 */
	public function posttype_project_messages( $messages ) {
		global $post;

		$permalink = get_permalink( $post );

		// @codingStandardsIgnoreStart
		$messages['project'] = array(
			0  => '', // Unused. Messages start at index 1.
			1  => sprintf( __( 'Project updated. <a target="_blank" href="%s">View project</a>',
				TEXT_DOMAIN ), esc_url( $permalink ) ),
			2  => __( 'Custom field updated.', TEXT_DOMAIN ),
			3  => __( 'Custom field deleted.', TEXT_DOMAIN ),
			4  => __( 'Project updated.', TEXT_DOMAIN ),
			/* translators: %s: date and time of the revision */
			5  => isset( $_GET['revision'] ) ? sprintf( __( 'Project restored to revision from %s',
				TEXT_DOMAIN ),
				wp_post_revision_title( (int) $_GET['revision'],
					false ) ) : false,
			6  => sprintf( __( 'Project published. <a href="%s">View project</a>',
				TEXT_DOMAIN ), esc_url( $permalink ) ),
			7  => __( 'Project saved.', TEXT_DOMAIN ),
			8  => sprintf( __( 'Project submitted. <a target="_blank" href="%s">Preview project</a>',
				TEXT_DOMAIN ),
				esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
			9  => sprintf( __( 'Project scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview project</a>',
				TEXT_DOMAIN ),
				// translators: Publish box date format, see http://php.net/date
				date_i18n( __( 'M j, Y @ G:i' ),
					strtotime( $post->post_date ) ),
				esc_url( $permalink ) ),
			10 => sprintf( __( 'Project draft updated. <a target="_blank" href="%s">Preview project</a>',
				TEXT_DOMAIN ),
				esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
		);
		// @codingStandardsIgnoreEnd

		return $messages;
	}


	/**
	 * Add a column heading to the admin page.
	 *
	 * @param array $columns Original columns.
	 *
	 * @return array
	 */
	public function add_admin_column_headers( array $columns ) : array {
		return [
			'cb'        => $columns['cb'],
			'title'     => $columns['title'],
			'city'      => 'Location',
			'state'     => 'State',
			'type'      => 'Type',
			'gallery'   => 'Has Gallery',
		];
	}


	/**
	 * Load data into the admin column.
	 *
	 * @param string $column Current column.
	 */
	public function add_admin_column_data( string $column ) {
		switch ( $column ) {
			case 'city':
			case 'state':
			case 'type':
				$data = get_field( $column, $GLOBALS['post']->ID );
				break;
			case 'gallery' :
				$data = get_field( 'photos' ) ? 'yes' : 'no';
				break;
		}

		echo esc_html( $data );
	}
}