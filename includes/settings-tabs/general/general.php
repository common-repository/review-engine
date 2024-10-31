<?php
if( !defined('ABSPATH') ) {
    exit;
}

if( !defined('RE_SETTINGS_SECTION_GENERAL') ) {
    define('RE_SETTINGS_SECTION_GENERAL', 'reviewengine-general');
}

class ReviewEngine_Components_SettingsTabs_General {
    public static function init() {
        self::_add_actions();
        self::_add_filters();
    }

    private static function _add_actions() {
        if (is_admin()) {
            add_action('reviewengine_display_settings_page', array(__CLASS__, 'add_settings_section_and_fields'));
            add_action('reviewengine_settings_tab_general_content', array(__CLASS__, 'tab_content'));
        }
    }

    private static function _add_filters() {
        add_filter('reviewengine_pre_get_settings_defaults', array(__CLASS__, 'add_settings_defaults'), 9);
    }

    public static function add_settings_section_and_fields() {
        add_settings_section('license', __('Your License'), array(__CLASS__, 'display_section_your_license'), RE_SETTINGS_SECTION_GENERAL);
        
        add_settings_field('license_key', __('License Key'), array(__CLASS__, 'display_field_license_key'), RE_SETTINGS_SECTION_GENERAL, 'license', array(
            'label_for' => reviewengine_get_setting_field_id('license_key'),
        ));

        add_settings_field('link_target', __('Affiliate Links'), array(__CLASS__, 'display_field_link_target'), RE_SETTINGS_SECTION_GENERAL, 'license', array(
            'label_for' => reviewengine_get_setting_field_id('link_target'),
        ));

        add_settings_field('link_rel', __(''), array(__CLASS__, 'display_field_link_rel'), RE_SETTINGS_SECTION_GENERAL, 'license', array(
            'label_for' => reviewengine_get_setting_field_id('link_rel'),
        ));

        add_settings_field('disclaimer', __('Disclaimer'), array(__CLASS__, 'display_field_disclaimer'), RE_SETTINGS_SECTION_GENERAL, 'license', array(
            'label_for' => reviewengine_get_setting_field_id('disclaimer'),
        ));

        add_settings_field('disclaimer_text', __(''), array(__CLASS__, 'display_field_disclaimer_text'), RE_SETTINGS_SECTION_GENERAL, 'license', array(
            'label_for' => reviewengine_get_setting_field_id('disclaimer_text'),
        ));
    }

    public static function tab_content() {
        include('views/content.php');
    }

    public static function display_section_your_license($args) {
        echo __('Your license key provides access to updates and addons.');
    }

    public static function display_field_license_key($args) {
        echo sprintf( 'You\'re using Review Engine Lite - no license needed. Enjoy! </br>To unlock more features consider <a href="%s" target="_blank"><strong>upgrading to PRO</strong></a>', 'https://reviewengine.com/lite-upgrade/?discount=LITEUPGRADE&utm_source=WordPress&utm_medium=settings-license&utm_campaign=liteplugin' );
    }

    public static function display_field_link_target($args) {
        $access_key = reviewengine_get_setting('access_key');
        $link_target = reviewengine_get_setting('link_target');

        if( empty( $access_key ) && empty( $link_target ) ) {
            $link_target = 1;
        }
        
        echo '<label for="' .esc_attr(reviewengine_get_setting_field_id('link_target')). '">';
        printf('<input type="checkbox" class="checkbox" id="%s" name="%s" value="1" %s />', esc_attr(reviewengine_get_setting_field_id('link_target')), esc_attr(reviewengine_get_setting_field_name('link_target')), checked('1', $link_target, false));
        echo __('Open affiliate links in a new tab by default.');
        echo '</label>';
    }

    public static function display_field_link_rel($args) {
        $access_key = reviewengine_get_setting('access_key');
        $link_rel = reviewengine_get_setting('link_rel');
        
        if( empty( $access_key ) && empty( $link_rel ) ) {
            $link_rel = 1;
        }

        echo '<label for="' .esc_attr(reviewengine_get_setting_field_id('link_rel')). '">';
        printf('<input type="checkbox" class="checkbox" id="%s" name="%s" value="1" %s />', esc_attr(reviewengine_get_setting_field_id('link_rel')), esc_attr(reviewengine_get_setting_field_name('link_rel')), checked('1', $link_rel, false));
        echo __('Add ref="nofollow" atrribute to affiliate links by default.');
        echo '</label>';
    }
    
    public static function display_field_disclaimer($args) {
        echo '<label for="' .esc_attr(reviewengine_get_setting_field_id('disclaimer')). '">';
        printf('<input type="checkbox" class="checkbox" id="%s" name="%s" value="1" %s />', esc_attr(reviewengine_get_setting_field_id('disclaimer')), esc_attr(reviewengine_get_setting_field_name('disclaimer')), checked('1', reviewengine_get_setting('disclaimer'), false));
        echo __('Display affiliate disclaimer.');
        echo '</label>';
    }

    public static function display_field_disclaimer_text($args) {
        $access_key = reviewengine_get_setting('access_key');
        $disclaimer_text = reviewengine_get_setting('disclaimer_text');

        if( empty( $access_key ) && empty( $disclaimer_text ) ) {
            $disclaimer_text = 'As an Amazon Associate, I earn a commission if you click this link and make a purchase, at no additional cost to you.';
        }

        echo '<p style="margin: 0px 0px 5px;"><i>For Amazon Associate links:</i></p>';
        printf('<textarea class="code large-text" id="%s" name="%s" rows="10" cols="50">%s</textarea>', esc_attr(reviewengine_get_setting_field_id('disclaimer_text')), esc_attr(reviewengine_get_setting_field_name('disclaimer_text')), reviewengine_get_setting('disclaimer_text'));
    }

    public static function add_settings_defaults($defaults) {
        $defaults['link_target'] = '';
        $defaults['link_rel'] = '';
        $defaults['disclaimer'] = '';
        $defaults['disclaimer_text'] = _('As an Amazon Associate, I earn a commission if you click this link and make a purchase, at no additional cost to you.');

        return $defaults;
    }
}

ReviewEngine_Components_SettingsTabs_General::init();