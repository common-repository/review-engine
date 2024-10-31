<?php
if(!defined('ABSPATH')) { exit; }

class ReviewEngine_Components_Display_Image {
	public static function init() {
		self::_add_actions();
		self::_add_filters();
	}

	private static function _add_actions() {
		if( is_admin() ) {
			add_action( 'reviewengine_modal_scripts', array(__CLASS__, 'enqueue_scripts'), 99 );
			add_action( 'reviewengine_display_content', array(__CLASS__, 'display_content') );
		}
	}

	private static function _add_filters() {
		if( is_admin() ) {
			add_filter('reviewengine_display_buttons', array(__CLASS__, 'display_button'));
		}
	}

	public static function enqueue_scripts() {
		wp_enqueue_script( 'display-image', plugins_url('assets/image.js', __FILE__), array('jquery', 'reviewengine-modal'), REE_VERSION );
	}

	public static function display_button( $buttons ) {
		$buttons .= sprintf( '<button type="button" class="btn btn-default btn-sm button-display-image">%s</button>', __('Image') );
		
		return $buttons;
	}

	public static function display_content() {
		include('views/content.php');
	}
}

ReviewEngine_Components_Display_Image::init();