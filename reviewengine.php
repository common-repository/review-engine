<?php
/**
 * Plugin Name: Affiliate Link Builder Plugin for Amazon Associates - Review Engine
 * Plugin URI: https://reviewengine.io
 * Description: Insert beautiful, high-converting Amazon associates affiliate links into your blog posts in just a few clicks with Review Engine.
 * Version: 1.0.41
 * Author: Review Engine
 * Author URI: https://reviewengine.io
 * Requires at least: 3.8
 * Tested up to: 4.9
 * Text Domain: reviewengine
 */

if( !defined('ABSPATH') ) {
    exit;
}

if( !defined('REE_PLUGIN_FILE') ) {
    define('REE_PLUGIN_FILE', __FILE__);
}

final class ReviewEngine {

    public $version = '1.0.41';
    protected static $_instance = null;

    public static function instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public function __construct() {
        $this->define_constants();
        $this->includes();
        $this->init_hooks();

        do_action('reviewengine_loaded');
    }

    private function init_hooks() {
        register_activation_hook(REE_PLUGIN_FILE, array($this, 'activation'));
        register_deactivation_hook(REE_PLUGIN_FILE, array($this, 'deactivation'));
        add_action('init', array($this, 'init'), 0);
    }

    private function define_constants() {
        $this->define('REE_PLUGIN_DIRECTORY', dirname(REE_PLUGIN_FILE) . '/');
        $this->define('REE_PLUGIN_BASENAME', plugin_basename(REE_PLUGIN_FILE));
        $this->define('REE_PLUGIN_URL', plugin_dir_url(REE_PLUGIN_FILE));
        $this->define('REE_VERSION', $this->version);
        $this->define('REE_CACHE_PERIOD', 6 * HOUR_IN_SECONDS);
        $this->define('REE_SETTINGS_PAGE', 'reviewengine-settings');
        $this->define('REE_SETTINGS_NAME', 'reviewengine-settings');
        $this->define('REE_POST_TYPE', 'ree_product');
        $this->define('REE_META_KEY_BUTTON_STYLE', '_ree_button_style');
        $this->define('REE_META_KEY_PRODUCT_ASIN', '_ree_product_asin');
        $this->define('REE_META_KEY_PRODUCT_DATA', '_ree_product_data');
        $this->define('REE_INSERT_SEARCH_LIMIT', 10);
    }

    private function define($name, $value) {
        if( !defined( $name ) ) {
            define($name, $value);
        }
    }

    public function activation() {
        
    }

    public function deactivation() {
        
    }

    public function init() {
        
    }

    public function includes() {
        // AMAZON API
        require_once( REE_PLUGIN_DIRECTORY . 'includes/vendor/amazon.php' );

        // CUSTOM POST TYPE
        require_once( REE_PLUGIN_DIRECTORY . 'includes/cpt/cpt.php' );

        // SETTINGS
        require_once( REE_PLUGIN_DIRECTORY . 'includes/settings/settings.php' );
        require_once( REE_PLUGIN_DIRECTORY . 'includes/settings-tabs/amazon/amazon.php' );
        require_once( REE_PLUGIN_DIRECTORY . 'includes/settings-tabs/general/general.php' );

        // METABOX
        require_once( REE_PLUGIN_DIRECTORY . 'includes/metabox-functions.php' );
        require_once( REE_PLUGIN_DIRECTORY . 'includes/metabox/metabox.php' );

        // EDITOR
        require_once( REE_PLUGIN_DIRECTORY . 'includes/editor/editor.php' );

        // MODAL
        require_once( REE_PLUGIN_DIRECTORY . 'includes/modal/modal.php' );
        require_once( REE_PLUGIN_DIRECTORY . 'includes/modal-types/insert/insert.php' );
        require_once( REE_PLUGIN_DIRECTORY . 'includes/modal-types/create/create.php' );

        // DISPLAY
        require_once( REE_PLUGIN_DIRECTORY . 'includes/display/link/link.php' );
        require_once( REE_PLUGIN_DIRECTORY . 'includes/display/image/image.php' );
        require_once( REE_PLUGIN_DIRECTORY . 'includes/display/button/button.php' );

        // AJAX HANDLE
        require_once( REE_PLUGIN_DIRECTORY . 'includes/ajax/ajax.php' );

        // HELPER FUNCTIONS
        require_once( REE_PLUGIN_DIRECTORY . 'includes/helper-functions.php' );

        // SHORTCODE
        require_once( REE_PLUGIN_DIRECTORY . 'includes/shortcodes/shortcodes.php' );
    }

}

function reviewengine_fs() {
    global $reviewengine_fs;

    if ( ! isset( $reviewengine_fs ) ) {
        // Include Freemius SDK.
        require_once dirname(REE_PLUGIN_FILE) . '/includes/vendor/freemius-sdk/start.php';

        $reviewengine_fs = fs_dynamic_init( array(
            'id'                  => '2359',
            'slug'                => 'reviewengine',
            'type'                => 'plugin',
            'public_key'          => 'pk_afbc4073a06cc51b0eb6c25e7e51c',
            'is_premium'          => false,
            'has_addons'          => false,
            'has_paid_plans'      => false,
            'menu'                => array(
                'slug'           => 'edit.php?post_type=ree_product',
                'first-path'     => 'edit.php?post_type=ree_product&page=reviewengine-settings',
                'support'        => false,
            ),
        ) );
    }

    return $reviewengine_fs;
}

add_action('plugins_loaded', 'reviewengine_integration_fs', 9);
function reviewengine_integration_fs() {
    reviewengine_fs();
    reviewengine_fs()->add_filter('connect_message_on_update', 'reviewengine_fs_custom_connect_message_on_update', 10, 6);

    do_action( 'reviewengine_fs_loaded' );
}

add_action('reviewengine_fs_loaded', 'reviewengine_initialization', 9);
function reviewengine_initialization() {
    ReviewEngine::instance();
}

function reviewengine_fs_custom_connect_message_on_update($message, $user_first_name, $product_title, $user_login, $site_link, $freemius_link) {
    return sprintf(
        __( 'Hey %1$s' ) . ',<br>' .
        __( 'Never miss an important update - opt in to our security & feature updates notifications, and non-sensitive diagnostic tracking with %2$s. Your information is 100% secure and won\'t be shared with anyone else. We only collect data to make a better product for you.' ),
        $user_first_name,
        $freemius_link
    );
}