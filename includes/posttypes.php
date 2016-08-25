<?php
/**
 * Setup custom post types.
 */

namespace Superiocity\CVC;

class Posttypes {

	/**
	 * Posttypes constructor sets up hooks.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'posttype_project_init' ) );
		add_filter( 'post_updated_messages', array( $this, 'posttype_project_messages' ) );
	}


	/**
	 * Initialize the 'proejct' post type.
	 */
	public function posttype_project_init() {
		register_post_type( 'project', array (
			'labels'                => array (
				'name'               => __( 'Projects', TEXT_DOMAIN ),
				'singular_name'      => __( 'Project', TEXT_DOMAIN ),
				'all_items'          => __( 'All Projects', TEXT_DOMAIN ),
				'new_item'           => __( 'New project', TEXT_DOMAIN ),
				'add_new'            => __( 'Add New', TEXT_DOMAIN ),
				'add_new_item'       => __( 'Add New project', TEXT_DOMAIN ),
				'edit_item'          => __( 'Edit project', TEXT_DOMAIN ),
				'view_item'          => __( 'View project', TEXT_DOMAIN ),
				'search_items'       => __( 'Search projects', TEXT_DOMAIN ),
				'not_found'          => __( 'No projects found', TEXT_DOMAIN ),
				'not_found_in_trash' => __( 'No projects found in trash',
					TEXT_DOMAIN ),
				'parent_item_colon'  => __( 'Parent project', TEXT_DOMAIN ),
				'menu_name'          => __( 'Projects', TEXT_DOMAIN ),
			),
			'public'                => false,
			'hierarchical'          => false,
			'show_ui'               => true,
			'show_in_nav_menus'     => true,
			'supports'              => array (
				'title',
				'editor',
				'thumbnail',
				'excerpt',
				'revisions'
			),
			'has_archive'           => true,
			'rewrite'               => true,
			'query_var'             => true,
			'menu_icon'             => 'dashicons-building',
			'show_in_rest'          => true,
			'rest_base'             => 'project',
			'rest_controller_class' => 'WP_REST_Posts_Controller',
		) );
	}


	/**
	 * Configure the messages associated with the 'project' post type.
	 *
	 * @param array $messages
	 *
	 * @return array
	 */
	public function posttype_project_messages( $messages ) {
		global $post;

		$permalink = get_permalink( $post );

		$messages['project'] = array (
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

		return $messages;
	}
}