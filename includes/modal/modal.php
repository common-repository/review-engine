<?php
if(!defined('ABSPATH')) { exit; }

class ReviewEngine_Components_Modal {
	public static function init() {
		self::_add_actions();
		self::_add_filters();
	}

	private static function _add_actions() {
		if( is_admin() ) {
			add_action('reviewengine_modal_scripts', array(__CLASS__, 'modal_scripts'));
		}
	}

	private static function _add_filters() {
		if( is_admin() ) {
		}
	}

	public static function modal_scripts() {
		wp_enqueue_style( 'bootstrap' , REE_PLUGIN_URL . 'assets/css/bootstrap.min.css', array(), REE_VERSION, 'all' );
		wp_enqueue_style( 'fontawesome' , REE_PLUGIN_URL . 'assets/css/fontawesome.min.css', array(), REE_VERSION, 'all' );
		wp_enqueue_style( 'reviewengine-button' , REE_PLUGIN_URL . 'assets/css/reviewengine-button.css', array(), REE_VERSION, 'all' );
		wp_enqueue_style( 'reviewengine-modal' , plugins_url('assets/modal.css', __FILE__), array(), REE_VERSION, 'all' );

		wp_dequeue_script( 'jquery-ui-core' );
		wp_dequeue_script( 'jquery-ui-sortable' );
		wp_dequeue_script( 'admin-scripts' );
		
		wp_enqueue_script( 'bootstrap', REE_PLUGIN_URL . 'assets/js/bootstrap.min.js', array( 'jquery' ), REE_VERSION );
		wp_enqueue_script( 'mustache', REE_PLUGIN_URL . 'assets/js/mustache.min.js', array( 'jquery' ), REE_VERSION );
		wp_enqueue_script( 'reviewengine-modal', plugins_url('assets/modal.js', __FILE__), array( 'jquery' ), REE_VERSION );
	}
}

ReviewEngine_Components_Modal::init();