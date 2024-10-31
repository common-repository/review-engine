<?php
if( !defined('ABSPATH') ) { exit; }

class ReviewEngine_Components_Editor {
	public static function init() {
		self::_add_actions();
	}

	private static function _add_actions() {
		if( is_admin() ) {
			add_action('media_buttons', array(__CLASS__, 'add_media_buttons'), 999999);
			add_action('wp_enqueue_editor', array(__CLASS__, 'enqueue_editor_scripts'));
		}
	}

	public static function add_media_buttons($editor_id) {
		printf('<a href="#" class="button reviewengine-insert-button add_media" title="%s" data-editor="%s">%s</a>', __('ReviewEngine - Insert Affiliate Link'), $editor_id, __('Insert Affiliate Link'));
		printf('<a href="#" class="button reviewengine-create-button add_media" title="%s" data-editor="%s">%s</a>', __('ReviewEngine - Quick Create Affiliate Link'), $editor_id, __('Quick Create Affiliate Link'));
	}

	public static function enqueue_editor_scripts() {
		wp_enqueue_script('thickbox');
		
		wp_enqueue_script('reviewengine-editor', plugins_url('assets/editor.js', __FILE__), array('jquery', 'thickbox'), REE_VERSION, true);
		wp_enqueue_style('reviewengine-editor', plugins_url('assets/editor.css', __FILE__), array(), REE_VERSION);

		wp_localize_script( 'reviewengine-editor', 'ReviewEngineHelpers', array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'post_id' => get_the_ID()
		));
	}
}

ReviewEngine_Components_Editor::init();