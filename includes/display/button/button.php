<?php
if( !defined('ABSPATH') ) {
    exit;
}

class ReviewEngine_Components_Display_Button {
    public static function init() {
        self::_add_actions();
        self::_add_filters();
    }

    private static function _add_actions() {
        if( is_admin() ) {
            add_action( 'reviewengine_modal_scripts', array(__CLASS__, 'enqueue_scripts'), 99 );
            add_action('reviewengine_display_content', array(__CLASS__, 'display_content'));
        }
    }

    private static function _add_filters() {
        if( is_admin() ) {
            add_filter('reviewengine_display_buttons', array(__CLASS__, 'display_button'));
        }
    }

    public static function enqueue_scripts() {
        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_style( 'fontawesome-iconpicker', REE_PLUGIN_URL . 'assets/css/fontawesome-iconpicker.min.css', array(), REE_VERSION );
        wp_enqueue_script( 'fontawesome-iconpicker', REE_PLUGIN_URL . 'assets/js/fontawesome-iconpicker.min.js', array('jquery', 'reviewengine-modal'), REE_VERSION );
        wp_enqueue_script( 'display-button', plugins_url('assets/button.js', __FILE__), array('jquery', 'wp-color-picker', 'reviewengine-modal'), REE_VERSION );
    }

    public static function display_button($buttons) {
        $buttons .= sprintf('<button type="button" class="btn btn-default btn-sm button-display-button">%s</button>', __('Button'));

        return $buttons;
    }

    public static function display_content() {
        include('views/content.php');
    }
}

ReviewEngine_Components_Display_Button::init();