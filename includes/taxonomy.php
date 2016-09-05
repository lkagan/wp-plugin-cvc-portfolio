<?php
/**
 * Setup custom taxonomies.
 *
 * @package Superiocity\CVC
 */

namespace Superiocity\CVC;

/**
 * Register and hook taxonomies and related messages.
 */
class Taxonomy {

	/**
	 * Taxonomy constructor sets up hooks.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'hotel_type_init' ) );
		add_action( 'init', array( $this, 'hotel_chain_init' ) );
	}

	/**
	 * Register the hotel_type taxonomy.
	 */
	public function hotel_type_init() {
		register_taxonomy( 'hotel-type', array( 'project' ), array(
			'public'                => false,
			'show_ui'               => true,
			'show_admin_column'     => true,
			'rewrite'               => false,
			'capabilities'          => array(
				'manage_terms' => 'edit_posts',
				'edit_terms'   => 'edit_posts',
				'delete_terms' => 'edit_posts',
				'assign_terms' => 'edit_posts',
			),
			'labels'                => array(
				'name'                       => __( 'Hotel types', TEXT_DOMAIN ),
				'singular_name'              => _x( 'Hotel type',
					'taxonomy general name', TEXT_DOMAIN ),
				'search_items'               => __( 'Search Hotel types',
					TEXT_DOMAIN ),
				'popular_items'              => __( 'Popular Hotel types',
					TEXT_DOMAIN ),
				'all_items'                  => __( 'All Hotel types', TEXT_DOMAIN ),
				'parent_item'                => __( 'Parent Hotel types',
					TEXT_DOMAIN ),
				'parent_item_colon'          => __( 'Parent Hotel type:',
					TEXT_DOMAIN ),
				'edit_item'                  => __( 'Edit Hotel type', TEXT_DOMAIN ),
				'update_item'                => __( 'Update Hotel type',
					TEXT_DOMAIN ),
				'add_new_item'               => __( 'New Hotel type', TEXT_DOMAIN ),
				'new_item_name'              => __( 'New Hotel type', TEXT_DOMAIN ),
				'separate_items_with_commas' => __( 'Hotel types separated by comma',
					TEXT_DOMAIN ),
				'add_or_remove_items'        => __( 'Add or remove Hotel types',
					TEXT_DOMAIN ),
				'choose_from_most_used'      => __( 'Choose from the most used Hotel types',
					TEXT_DOMAIN ),
				'not_found'                  => __( 'No Hotel types found.',
					TEXT_DOMAIN ),
				'menu_name'                  => __( 'Hotel types', TEXT_DOMAIN ),
			),
			'show_in_rest'          => true,
			'rest_base'             => 'hotel-type',
			'rest_controller_class' => 'WP_REST_Terms_Controller',
		) );
	}


	/**
	 * Register the hotel_chain taxonomy.
	 */
	public function hotel_chain_init() {
		register_taxonomy( 'hotel-chain', array( 'project' ), array(
			'public'                => false,
			'show_ui'               => true,
			'show_admin_column'     => true,
			'rewrite'               => false,
			'capabilities'          => array(
				'manage_terms' => 'edit_posts',
				'edit_terms'   => 'edit_posts',
				'delete_terms' => 'edit_posts',
				'assign_terms' => 'edit_posts',
			),
			'labels'                => array(
				'name'                       => __( 'Hotel chains', TEXT_DOMAIN ),
				'singular_name'              => _x( 'Hotel chain',
					'taxonomy general name', TEXT_DOMAIN ),
				'search_items'               => __( 'Search Hotel chains',
					TEXT_DOMAIN ),
				'popular_items'              => __( 'Popular Hotel chains',
					TEXT_DOMAIN ),
				'all_items'                  => __( 'All Hotel chains', TEXT_DOMAIN ),
				'parent_item'                => __( 'Parent Hotel chain',
					TEXT_DOMAIN ),
				'parent_item_colon'          => __( 'Parent Hotel chain:',
					TEXT_DOMAIN ),
				'edit_item'                  => __( 'Edit Hotel chain', TEXT_DOMAIN ),
				'update_item'                => __( 'Update Hotel chain',
					TEXT_DOMAIN ),
				'add_new_item'               => __( 'New Hotel chain', TEXT_DOMAIN ),
				'new_item_name'              => __( 'New Hotel chain', TEXT_DOMAIN ),
				'separate_items_with_commas' => __( 'Hotel chains separated by comma',
					TEXT_DOMAIN ),
				'add_or_remove_items'        => __( 'Add or remove Hotel chains',
					TEXT_DOMAIN ),
				'choose_from_most_used'      => __( 'Choose from the most used Hotel chains',
					TEXT_DOMAIN ),
				'not_found'                  => __( 'No Hotel chains found.',
					TEXT_DOMAIN ),
				'menu_name'                  => __( 'Hotel chains', TEXT_DOMAIN ),
			),
			'show_in_rest'          => true,
			'rest_base'             => 'hotel-chain',
			'rest_controller_class' => 'WP_REST_Terms_Controller',
		) );
	}
}