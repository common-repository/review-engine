<?php
if( !defined('ABSPATH') ) { exit; }

if(!defined('RE_SETTINGS_SECTION_AMAZON')) {
	define('RE_SETTINGS_SECTION_AMAZON', 'reviewengine-amazon');
}

class ReviewEngine_Components_SettingsTabs_Amazon {
	public static function init() {
		self::_add_actions();
		self::_add_filters();
	}

	private static function _add_actions() {
		if( is_admin() ) {
			add_action('reviewengine_display_settings_page', array(__CLASS__, 'add_settings_section_and_fields'));
			add_action('reviewengine_settings_tab_amazon_content', array(__CLASS__, 'tab_content'));
		}
	}

	private static function _add_filters() {
		add_filter('reviewengine_pre_get_settings_defaults', array(__CLASS__, 'add_settings_defaults'), 9);
		add_filter('reviewengine_sanitize_settings', array(__CLASS__, 'sanitize_settings'), 11, 3);
	}

	public static function add_settings_section_and_fields() {
		add_settings_section('credentials', __('Amazon Product Advertising API'), array(__CLASS__, 'display_section_credentials_section'), RE_SETTINGS_SECTION_AMAZON);

		add_settings_field('access_key', __('Access Key ID'), array(__CLASS__, 'display_field_access_key'), RE_SETTINGS_SECTION_AMAZON, 'credentials', array(
			'label_for' => reviewengine_get_setting_field_id('access_key'),
		));

		add_settings_field('secret_key', __('Secret Access Key'), array(__CLASS__, 'display_field_secret_key'), RE_SETTINGS_SECTION_AMAZON, 'credentials', array(
			'label_for' => reviewengine_get_setting_field_id('secret_key'),
		));

		add_settings_field('api_status', __('Status'), array(__CLASS__, 'display_field_api_status'), RE_SETTINGS_SECTION_AMAZON, 'credentials', array(
            'label_for' => reviewengine_get_setting_field_id('api_status'),
        ));

		add_settings_field('default_search_locale', __('Default Search Locale'), array(__CLASS__, 'display_field_default_search_locale'), RE_SETTINGS_SECTION_AMAZON, 'credentials', array(
            'label_for' => reviewengine_get_setting_field_id('default_search_locale'),
        ));

		add_settings_section('associates', __('Tracking ID(s)'), array(__CLASS__, 'display_section_associates_section'), RE_SETTINGS_SECTION_AMAZON);

		add_settings_field('associates_us', __('United States'), array(__CLASS__, 'display_field_associates_us'), RE_SETTINGS_SECTION_AMAZON, 'associates', array(
			'label_for' => reviewengine_get_setting_field_id('associates_us'),
		));

		add_settings_field('associates_br', __('Brazil'), array(__CLASS__, 'display_field_associates_br'), RE_SETTINGS_SECTION_AMAZON, 'associates', array(
			'label_for' => reviewengine_get_setting_field_id('associates_br'),
		));

		add_settings_field('associates_ca', __('Canada'), array(__CLASS__, 'display_field_associates_ca'), RE_SETTINGS_SECTION_AMAZON, 'associates', array(
			'label_for' => reviewengine_get_setting_field_id('associates_ca'),
		));

		add_settings_field('associates_cn', __('China'), array(__CLASS__, 'display_field_associates_cn'), RE_SETTINGS_SECTION_AMAZON, 'associates', array(
			'label_for' => reviewengine_get_setting_field_id('associates_cn'),
		));

		add_settings_field('associates_fr', __('France'), array(__CLASS__, 'display_field_associates_fr'), RE_SETTINGS_SECTION_AMAZON, 'associates', array(
			'label_for' => reviewengine_get_setting_field_id('associates_fr'),
		));

		add_settings_field('associates_de', __('Germany'), array(__CLASS__, 'display_field_associates_de'), RE_SETTINGS_SECTION_AMAZON, 'associates', array(
			'label_for' => reviewengine_get_setting_field_id('associates_de'),
		));

		add_settings_field('associates_in', __('India'), array(__CLASS__, 'display_field_associates_in'), RE_SETTINGS_SECTION_AMAZON, 'associates', array(
			'label_for' => reviewengine_get_setting_field_id('associates_in'),
		));

		add_settings_field('associates_it', __('Italy'), array(__CLASS__, 'display_field_associates_it'), RE_SETTINGS_SECTION_AMAZON, 'associates', array(
			'label_for' => reviewengine_get_setting_field_id('associates_it'),
		));

		add_settings_field('associates_jp', __('Japan'), array(__CLASS__, 'display_field_associates_jp'), RE_SETTINGS_SECTION_AMAZON, 'associates', array(
			'label_for' => reviewengine_get_setting_field_id('associates_jp'),
		));

		add_settings_field('associates_es', __('Spain'), array(__CLASS__, 'display_field_associates_es'), RE_SETTINGS_SECTION_AMAZON, 'associates', array(
			'label_for' => reviewengine_get_setting_field_id('associates_es'),
		));

		add_settings_field('associates_uk', __('United Kingdom'), array(__CLASS__, 'display_field_associates_uk'), RE_SETTINGS_SECTION_AMAZON, 'associates', array(
			'label_for' => reviewengine_get_setting_field_id('associates_uk'),
		));
	}

	public static function tab_content() {
		include('views/content.php');
	}

	public static function display_section_credentials_section($args) {
		echo sprintf( 'In order to retrieve product information from Amazon, you need to connect Review Engine with your Product Advertising API credentials. If you have not already signed up for the Amazon Product Advertising API, you can do so by following instructions listed <a href="%s" target="_blank">here</a>.', 'https://docs.aws.amazon.com/AWSECommerceService/latest/DG/becomingDev.html' );
	}

	public static function display_section_associates_section($args) {
		echo __('Enter your tracking ID below. Without the tracking ID, your Amazon associate account won\'t be credited with sales that are generated through your site. Each location will require its own unique tracking ID.');
	}

	public static function display_field_access_key($args) {
		$api_status = reviewengine_get_setting('api_status');
		$readonly = '';

		if( $api_status == 'connected' ) {
			$readonly = 'readonly';
		}

		printf('<input type="text" class="code large-text" id="%s" name="%s" value="%s" %s/>', esc_attr(reviewengine_get_setting_field_id('access_key')), esc_attr(reviewengine_get_setting_field_name('access_key')), esc_attr(reviewengine_get_setting('access_key', '')),$readonly);
	}

	public static function display_field_secret_key($args) {
		$api_status = reviewengine_get_setting('api_status');
		$readonly = '';

		if( $api_status == 'connected' ) {
			$readonly = 'readonly';
		}

		printf('<input type="text" class="code large-text" id="%s" name="%s" value="%s" %s/>', esc_attr(reviewengine_get_setting_field_id('secret_key')), esc_attr(reviewengine_get_setting_field_name('secret_key')), esc_attr(reviewengine_get_setting('secret_key', '')),$readonly);
	}

	public static function display_field_api_status($args) {
		$api_status = reviewengine_get_setting('api_status');

		printf('<input type="hidden" name="%s" value="%s" />', esc_attr(reviewengine_get_setting_field_name('api_status')), esc_attr(reviewengine_get_setting('api_status', '')));
		printf('<input type="hidden" name="%s" value="%s" />', esc_attr(reviewengine_get_setting_field_name('api_error')), esc_attr(reviewengine_get_setting('api_error', '')));

		echo '<div class="amazon-connect" style="">';
		if( empty( $api_status ) || $api_status == 'disconnected' ) {
			echo '<p style="color: #ff0000;"><span class="dashicons dashicons-no aawp-dashicons-inline"></span> ' .__('Disconnected'). '</p>';
			echo '<button type="submit" name="' .reviewengine_get_setting_field_name('connect'). '" class="button-primary ree-amazon-connect">' .__('Connect'). '</button>';
		}
		if( $api_status == 'connected' ) {
			echo '<p style="color: #008000;"><span class="dashicons dashicons-yes aawp-dashicons-inline"></span> ' .__('Connected'). '</p>';
			echo '<button type="submit" name="' .reviewengine_get_setting_field_name('disconnect'). '" class="button ree-amazon-disconnect">' .__('Disconnect'). '</button>';
			echo '<button type="submit" name="' .reviewengine_get_setting_field_name('reconnect'). '" class="button-primary ree-amazon-connect">' .__('Reconnect'). '</button>';
		}
		echo '</div>';
	}

	public static function display_field_default_search_locale($args) {
        $options = array();
        $default = reviewengine_get_setting('default_search_locale');
        $locales = reviewengine_amazon_locales();

        foreach ($locales as $locale => $locale_name) {
            $options[] = sprintf('<option %s value="%s">%s</option>', ($default === $locale ? 'selected="selected"' : ''), esc_attr($locale), esc_html($locale_name));
        }

        printf('<select id="%s" name="%s">%s</select>', reviewengine_get_setting_field_id('default_search_locale'), reviewengine_get_setting_field_name('default_search_locale'), implode('', $options));
    }

	public static function display_field_associates_us($args) {
		printf('<input type="text" class="code regular-text" id="%s" name="%s" value="%s" placeholder="yourtrackingid-20" />', esc_attr(reviewengine_get_setting_field_id('associates_us')), esc_attr(reviewengine_get_setting_field_name('associates_us')), esc_attr(reviewengine_get_setting('associates_us')));
		printf('<p class="description"><a href="%s" target="_blank">%s</a></p>', reviewengine_amazon_signup_url('US'), __('Sign up'));
	}

	public static function display_field_associates_br($args) {
		printf('<input type="text" class="code regular-text" id="%s" name="%s" value="%s" placeholder="yourtrackingid-20" />', esc_attr(reviewengine_get_setting_field_id('associates_br')), esc_attr(reviewengine_get_setting_field_name('associates_br')), esc_attr(reviewengine_get_setting('associates_br')));
		printf('<p class="description"><a href="%s" target="_blank">%s</a></p>', reviewengine_amazon_signup_url('BR'), __('Sign up'));
	}

	public static function display_field_associates_ca($args) {
		printf('<input type="text" class="code regular-text" id="%s" name="%s" value="%s" placeholder="yourtrackingid-20" />', esc_attr(reviewengine_get_setting_field_id('associates_ca')), esc_attr(reviewengine_get_setting_field_name('associates_ca')), esc_attr(reviewengine_get_setting('associates_ca')));
		printf('<p class="description"><a href="%s" target="_blank">%s</a></p>', reviewengine_amazon_signup_url('CA'), __('Sign up'));
	}

	public static function display_field_associates_cn($args) {
		printf('<input type="text" class="code regular-text" id="%s" name="%s" value="%s" placeholder="yourtrackingid-20" />', esc_attr(reviewengine_get_setting_field_id('associates_cn')), esc_attr(reviewengine_get_setting_field_name('associates_cn')), esc_attr(reviewengine_get_setting('associates_cn')));
		printf('<p class="description"><a href="%s" target="_blank">%s</a></p>', reviewengine_amazon_signup_url('CN'), __('Sign up'));
	}

	public static function display_field_associates_fr($args) {
		printf('<input type="text" class="code regular-text" id="%s" name="%s" value="%s" placeholder="yourtrackingid-20" />', esc_attr(reviewengine_get_setting_field_id('associates_fr')), esc_attr(reviewengine_get_setting_field_name('associates_fr')), esc_attr(reviewengine_get_setting('associates_fr')));
		printf('<p class="description"><a href="%s" target="_blank">%s</a></p>', reviewengine_amazon_signup_url('FR'), __('Sign up'));
	}

	public static function display_field_associates_de($args) {
		printf('<input type="text" class="code regular-text" id="%s" name="%s" value="%s" placeholder="yourtrackingid-20" />', esc_attr(reviewengine_get_setting_field_id('associates_de')), esc_attr(reviewengine_get_setting_field_name('associates_de')), esc_attr(reviewengine_get_setting('associates_de')));
		printf('<p class="description"><a href="%s" target="_blank">%s</a></p>', reviewengine_amazon_signup_url('DE'), __('Sign up'));
	}

	public static function display_field_associates_in($args) {
		printf('<input type="text" class="code regular-text" id="%s" name="%s" value="%s" placeholder="yourtrackingid-20" />', esc_attr(reviewengine_get_setting_field_id('associates_in')), esc_attr(reviewengine_get_setting_field_name('associates_in')), esc_attr(reviewengine_get_setting('associates_in')));
		printf('<p class="description"><a href="%s" target="_blank">%s</a></p>', reviewengine_amazon_signup_url('IN'), __('Sign up'));
	}

	public static function display_field_associates_it($args) {
		printf('<input type="text" class="code regular-text" id="%s" name="%s" value="%s" placeholder="yourtrackingid-20" />', esc_attr(reviewengine_get_setting_field_id('associates_it')), esc_attr(reviewengine_get_setting_field_name('associates_it')), esc_attr(reviewengine_get_setting('associates_it')));
		printf('<p class="description"><a href="%s" target="_blank">%s</a></p>', reviewengine_amazon_signup_url('IT'), __('Sign up'));
	}

	public static function display_field_associates_jp($args) {
		printf('<input type="text" class="code regular-text" id="%s" name="%s" value="%s" placeholder="yourtrackingid-20" />', esc_attr(reviewengine_get_setting_field_id('associates_jp')), esc_attr(reviewengine_get_setting_field_name('associates_jp')), esc_attr(reviewengine_get_setting('associates_jp')));
		printf('<p class="description"><a href="%s" target="_blank">%s</a></p>', reviewengine_amazon_signup_url('JP'), __('Sign up'));
	}

	public static function display_field_associates_es($args) {
		printf('<input type="text" class="code regular-text" id="%s" name="%s" value="%s" placeholder="yourtrackingid-20" />', esc_attr(reviewengine_get_setting_field_id('associates_es')), esc_attr(reviewengine_get_setting_field_name('associates_es')), esc_attr(reviewengine_get_setting('associates_es')));
		printf('<p class="description"><a href="%s" target="_blank">%s</a></p>', reviewengine_amazon_signup_url('ES'), __('Sign up'));
	}

	public static function display_field_associates_uk($args) {
		printf('<input type="text" class="code regular-text" id="%s" name="%s" value="%s" placeholder="yourtrackingid-20" />', esc_attr(reviewengine_get_setting_field_id('associates_uk')), esc_attr(reviewengine_get_setting_field_name('associates_uk')), esc_attr(reviewengine_get_setting('associates_uk')));
		printf('<p class="description"><a href="%s" target="_blank">%s</a></p>', reviewengine_amazon_signup_url('UK'), __('Sign up'));
	}

	public static function sanitize_settings($settings, $settings_raw, $settings_defaults) {
		if( isset($settings['access_key']) ) {
			$settings['access_key'] = trim($settings['access_key']);
		}

		if( isset($settings['secret_key']) ) {
			$settings['secret_key'] = trim($settings['secret_key']);
		}

		return $settings;
	}

	public static function add_settings_defaults($defaults) {
		$defaults['access_key'] = '';
		$defaults['secret_key'] = '';
		$defaults['api_status'] = '';
		$defaults['api_error'] = '';
		$defaults['default_search_locale'] = 'US';
		$defaults['associates_us'] = '';
		$defaults['associates_br'] = '';
		$defaults['associates_ca'] = '';
		$defaults['associates_cn'] = '';
		$defaults['associates_fr'] = '';
		$defaults['associates_de'] = '';
		$defaults['associates_in'] = '';
		$defaults['associates_it'] = '';
		$defaults['associates_jp'] = '';
		$defaults['associates_es'] = '';
		$defaults['associates_uk'] = '';
		
		return $defaults;
	}
}

ReviewEngine_Components_SettingsTabs_Amazon::init();