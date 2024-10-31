<?php
if( !defined('ABSPATH') ) {
	exit;
}

class ReviewEngine_Components_Settings {
	public static function init() {
		self::_add_actions();
		self::_add_filters();
	}

	private static function _add_actions() {
		if( is_admin() ) {
			add_action('admin_init', array(__CLASS__, 'register_setting'));
			add_action('admin_menu', array(__CLASS__, 'add_admin_pages'));
		}
	}

	private static function _add_filters() {
		add_filter('reviewengine_pre_get_settings', array(__CLASS__, 'pre_get_settings'));
	}

	public static function add_admin_pages() {
		$reviewengine = add_submenu_page('edit.php?post_type=' . REE_POST_TYPE, __('Review Engine - Settings'), __('Settings'), 'manage_options', REE_SETTINGS_PAGE, array(__CLASS__, 'display_settings_page'));

		add_action("load-{$reviewengine}", array(__CLASS__, 'load_settings_page'));
	}

	public static function display_settings_page() {
		do_action('reviewengine_display_settings_page', REE_SETTINGS_PAGE);

		$settings = self::_get_settings();
		include('views/settings.php');
	}

	public static function load_settings_page() {

		wp_enqueue_script('reviewengine-settings', plugins_url('assets/settings.js', __FILE__), null, REE_VERSION, true);
		wp_enqueue_style( 'reviewengine-fontawesome' , REE_PLUGIN_URL . 'assets/css/fontawesome.min.css', array(), REE_VERSION, 'all' );
		wp_enqueue_style('reviewengine-settings', plugins_url('assets/settings.css', __FILE__), array(), REE_VERSION);

		do_action('reviewengine_load_settings_page');

		wp_localize_script( 'reviewengine-settings', 'reeSettingsJs', array(
			'ajaxurl' => admin_url('admin-ajax.php')
		));
	}

	public static function get_setting($settings_key, $default = null) {
		$settings = self::_get_settings();

		return isset($settings[$settings_key]) ? $settings[$settings_key] : $default;
	}

	public static function pre_get_settings($settings) {
		$settings = is_array($settings) ? $settings : array();

		return shortcode_atts(self::_get_settings_defaults(), $settings);
	}

	public static function register_setting() {
		register_setting( REE_SETTINGS_PAGE, REE_SETTINGS_NAME, array(__CLASS__, 'sanitize_settings') );
	}

	public static function sanitize_settings($settings) {
		$settings = is_array($settings) ? $settings : array();
		$settings_defaults = self::_get_settings_defaults();

		if( isset( $settings['connect'] ) || isset( $settings['reconnect'] ) ) {
			$access_key = $settings['access_key'];
			$secret_key = $settings['secret_key'];

			if( empty( $access_key ) || empty( $secret_key ) ) {
				$settings['api_status'] = 'disconnected';
				$settings['api_error'] = __('You must provide your Amazon credentials and ensure they are valid. Requests cannot be made at this time.');
			} else {
				$amazon = new ReviewEngine_Amazon_API;
				$amazon->set_credentials($access_key, $secret_key);
				$response = $amazon->search( 'Kindle' );

				if( is_wp_error( $response ) || $response == false ) {
					$settings['api_status'] = 'disconnected';
					$settings['api_error'] = __('You must provide your Amazon credentials and ensure they are valid. Requests cannot be made at this time.');
				} else {
					$settings['api_status'] = 'connected';
					$settings['api_error'] = '';
				}
			}
		}

		if( isset( $settings['disconnect'] ) ) {
			$settings['api_status'] = 'disconnected';
			$settings['api_error'] = '';
		}

		wp_cache_delete( REE_SETTINGS_NAME );

		$settings = apply_filters('reviewengine_sanitize_settings', $settings, $settings, $settings_defaults);

		return shortcode_atts($settings_defaults, $settings);
	}

	private static function _get_settings() {
		$settings = wp_cache_get( REE_SETTINGS_NAME );

		if( !is_array($settings) ) {
			$settings = apply_filters( 'reviewengine_pre_get_settings', get_option( REE_SETTINGS_NAME, self::_get_settings_defaults() ) );

			wp_cache_set( REE_SETTINGS_NAME, $settings, null, REE_CACHE_PERIOD );
		}

		return $settings;
	}

	private static function _get_settings_defaults() {
		$defaults = array();
		return apply_filters('reviewengine_pre_get_settings_defaults', $defaults);
	}
}

ReviewEngine_Components_Settings::init();