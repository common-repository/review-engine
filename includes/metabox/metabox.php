<?php
if(!defined('ABSPATH')) { exit; }

class ReviewEngine_Components_MetaBox {
	public static function init() {
		self::_add_actions();
		self::_add_filters();
	}

	private static function _add_actions() {
		if( is_admin() ) {
			add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue_scripts') );
			add_action( 'add_meta_boxes', array( __CLASS__, 'add_meta_boxes') );
		}
	}

	private static function _add_filters() {
		if( is_admin() ) {
		}
	}

	public static function enqueue_scripts( $hook ) {
		$screen = get_current_screen();
		$screen_id = $screen ? $screen->id : '';

		if( $screen_id == REE_POST_TYPE ) {
			wp_enqueue_script('reviewengine-metabox', plugins_url('assets/metabox.js', __FILE__), array('jquery'), REE_VERSION, true);
			wp_enqueue_style('reviewengine-metabox', plugins_url('assets/metabox.css', __FILE__), array(), REE_VERSION);
		}
	}

	public static function add_meta_boxes() {
		add_meta_box( 'reviewengine-product-informations', __( 'Product Informations' ), array(__CLASS__, 'render_product_informations'), REE_POST_TYPE, 'normal', 'default' );
	}

	public static function render_product_informations($post) {
		include('views/content.php');
	}
}

ReviewEngine_Components_MetaBox::init();