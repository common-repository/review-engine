<?php
if(!defined('ABSPATH')) { exit; }

class ReviewEngine_Components_Modal_Create {
	public static function init() {
		self::_add_actions();
		self::_add_filters();
	}

	private static function _add_actions() {
		if( is_admin() ) {
			add_action( 'wp_ajax_ree_modal_create', array(__CLASS__, 'modal_content') );
		}
	}

	private static function _add_filters() {
		if( is_admin() ) {
		}
	}

	public static function modal_content() {
		if( !defined( 'DOING_AJAX' ) || !DOING_AJAX ) {
            wp_die( "You're not allowed to do this." );
		}

		$post_id = isset( $_REQUEST['post_id'] ) ? intval( $_REQUEST['post_id'] ) : 0;
		$selection = isset( $_REQUEST['selection'] ) ? sanitize_text_field( $_REQUEST['selection'] ) : '';
		
		do_action( 'reviewengine_modal_scripts' );

		wp_enqueue_script( 'reviewengine-create', plugins_url('assets/create.js', __FILE__), array( 'jquery' ), REE_VERSION );
		
		wp_localize_script( 'reviewengine-modal', 'reviewengine', array(
			'ajaxurl' => admin_url('admin-ajax.php'),
			'tracking_ids' => reviewengine_tracking_ids(),
			'post_id' => $post_id,
			'selection' => $selection
		));
		
		include('views/content.php');
		wp_die();
	}
}

ReviewEngine_Components_Modal_Create::init();