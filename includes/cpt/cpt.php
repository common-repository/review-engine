<?php
if(!defined('ABSPATH')) { exit; }

class ReviewEngine_CPT_Settings {
	public static function init() {
		self::_add_actions();
	}

	private static function _add_actions() {
		if( is_admin() ) {
			add_action('init', array(__CLASS__, 'register_post_type'));
		}
	}

	public static function register_post_type() {
		if( !is_blog_installed() || post_type_exists( REE_POST_TYPE ) ) {
			return;
		}

		do_action( 'reviewengine_register_post_type' );

		$supports = array( 'title', 'custom-fields' );

		register_post_type( REE_POST_TYPE,
			apply_filters( 'reviewengine_register_post_type_product',
				array(
					'labels'              => array(
							'name'                  => __( 'Products', 'reviewengine' ),
							'singular_name'         => __( 'Product', 'reviewengine' ),
							'all_items'             => __( 'All Products', 'reviewengine' ),
							'menu_name'             => _x( 'Review Engine', 'Admin menu name', 'reviewengine' ),
							'add_new'               => __( 'Add New', 'reviewengine' ),
							'add_new_item'          => __( 'Add new product', 'reviewengine' ),
							'edit'                  => __( 'Edit', 'reviewengine' ),
							'edit_item'             => __( 'Edit product', 'reviewengine' ),
							'new_item'              => __( 'New product', 'reviewengine' ),
							'view'                  => __( 'View product', 'reviewengine' ),
							'view_item'             => __( 'View product', 'reviewengine' ),
							'search_items'          => __( 'Search products', 'reviewengine' ),
							'not_found'             => __( 'No products found', 'reviewengine' ),
							'not_found_in_trash'    => __( 'No products found in trash', 'reviewengine' ),
							'parent'                => __( 'Parent product', 'reviewengine' ),
							'featured_image'        => __( 'Product image', 'reviewengine' ),
							'set_featured_image'    => __( 'Set product image', 'reviewengine' ),
							'remove_featured_image' => __( 'Remove product image', 'reviewengine' ),
							'use_featured_image'    => __( 'Use as product image', 'reviewengine' ),
							'insert_into_item'      => __( 'Insert into product', 'reviewengine' ),
							'uploaded_to_this_item' => __( 'Uploaded to this product', 'reviewengine' ),
							'filter_items_list'     => __( 'Filter products', 'reviewengine' ),
							'items_list_navigation' => __( 'Products navigation', 'reviewengine' ),
							'items_list'            => __( 'Products list', 'reviewengine' ),
						),
					'description'         => __( 'This is where you can add new products to Review Engine.', 'reviewengine' ),
					'public'              => false,
					'show_ui'             => true,
					'capability_type'     => 'post',
					'map_meta_cap'        => true,
					'publicly_queryable'  => true,
					'exclude_from_search' => true,
					'hierarchical'        => false,
					'rewrite'			  => false,
					'query_var'           => true,
					'supports'            => $supports,
					'show_in_nav_menus'   => false,
					'show_in_rest'        => true,
					'menu_icon'           => null,
					'capabilities'		  => array(
						'create_posts' => false
					)
				)
			)
		);
	}
}

ReviewEngine_CPT_Settings::init();