<?php
if( !defined('ABSPATH') ) {
    exit;
}

class ReviewEngine_Components_Shortcodes {
    public static function init() {
        self::_add_actions();
        self::_add_shortcodes();
    }

    private static function _add_actions() {
        if( is_admin() ) {
            add_action('save_post', array(__CLASS__, 'delete_transient'));
        } else {
            add_action('wp_enqueue_scripts', array(__CLASS__, 'enqueue_resources'));
        }
    }

    private static function _add_shortcodes() {
        add_shortcode('reviewengine_link', array(__CLASS__, 'reviewengine_link'));
        add_shortcode('reviewengine_button', array(__CLASS__, 'reviewengine_button'));
    }

    public static function enqueue_resources() {
        wp_enqueue_style('reviewengine-frontend-plugins', REE_PLUGIN_URL . 'assets/css/ree-frontend-plugins.css', array(), REE_VERSION);
        wp_enqueue_style('reviewengine-button', REE_PLUGIN_URL . 'assets/css/reviewengine-button.css', array(), REE_VERSION);
        wp_enqueue_style('reviewengine-frontend', REE_PLUGIN_URL . 'assets/css/ree-frontend.css', array(), REE_VERSION);

        wp_enqueue_script('reviewengine-frontend-plugins', REE_PLUGIN_URL . '/assets/js/ree-frontend-plugins.js', array('jquery'), false, true);
        wp_enqueue_script('reviewengine-frontend', REE_PLUGIN_URL . '/assets/js/ree-frontend.js', array('jquery', 'reviewengine-frontend-plugins'), false, true);

        wp_localize_script( 'reviewengine-frontend', 'reviewengine', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'disclaimer' => reviewengine_get_setting('disclaimer'),
            'disclaimer_text' => reviewengine_get_setting('disclaimer_text'),
        ));

        // ADD CUSTOM STYLE
        $custom_css = self::button_styles();

        if( $custom_css ) {
            wp_add_inline_style('reviewengine-button', $custom_css);
        }
        // END ADD CUSTOM STYLE
    }

    public function delete_transient($post_id) {
        delete_transient('reviewengine_button_' . $post_id);
    }

    public static function button_styles() {
        if( !is_single() && !is_page() ) {
            return;
        }

        global $post;
        $post_id = $post->ID;

        $transient_name = 'reviewengine_button_' . $post_id;
        // delete_transient( $transient_name );
        if( false === ( $css = get_transient($transient_name) ) ) {

            $style = get_post_meta($post_id, REE_META_KEY_BUTTON_STYLE, true);

            if( empty($style) ) {
                return;
            }

            // CLEAN BUTTON STYLE
            preg_match_all('/' . get_shortcode_regex() . '/s', get_post_field('post_content', $post_id), $matches, PREG_SET_ORDER);
            $style = maybe_unserialize($style);
            $post_buttons = array();
            $new_style = array();

            if (!empty($matches)) {
                foreach ($matches as $match) {
                    $shortcode = trim($match[2]);

                    if (in_array($shortcode, array('reviewengine_button'))) {
                        $attributes = shortcode_parse_atts($match[3]);

                        if (isset($attributes['id'])) {
                            $post_buttons[] = trim($attributes['id']);
                        }
                    }
                }
            }

            foreach ($style as $id => $data) {
                if (in_array($id, $post_buttons)) {
                    $new_style[$id] = $data;
                }
            }

            update_post_meta($post_id, REE_META_KEY_BUTTON_STYLE, maybe_serialize($new_style));
            // END CLEAN BUTTON STYLE

            ob_start();
            include 'button/frontend.css.php';
            $css = ob_get_clean();

            // Minify the CSS.
            $css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);
            $css = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $css);

            set_transient($transient_name, $css, REE_CACHE_PERIOD);
        }

        return apply_filters('reviewengine_render_button_css', $css, $post_id);
    }

    public static function reviewengine_link($atts, $content = null) {
        $button_atts = apply_filters('reviewengine_link_atts', $atts);
        $content = trim($content);
        
        if( empty( $content ) ) {
            return '';
        }

        $id = isset( $button_atts['id'] ) ? esc_attr( $button_atts['id'] ) : '';
        $url = isset( $button_atts['url'] ) ? esc_url( $button_atts['url'] ) : '';

        return sprintf('<a href="%1$s" class="reviewengine-link" data-id="%2$s" %3$s>%4$s</a>', $url, $id, reviewengine_link_attributes(), $content);
    }

    public static function reviewengine_button($atts, $content = null) {
        $button_atts = apply_filters('reviewengine_button_atts', $atts);
        $content = trim($content);
        
        if( empty( $content ) ) {
            return '';
        }

        $id = isset( $button_atts['id'] ) ? esc_attr( $button_atts['id'] ) : '';
        $url = isset( $button_atts['url'] ) ? esc_url( $button_atts['url'] ) : '';
        $type = isset( $button_atts['type'] ) ? esc_attr( $button_atts['type'] ) : 'flat';
        $size = isset( $button_atts['size'] ) ? esc_attr( $button_atts['size'] ) : 's';
        $icon = isset( $button_atts['icon'] ) ? esc_attr( $button_atts['icon'] ) : '';
        $icon_position = isset( $button_atts['icon_position'] ) ? esc_attr( $button_atts['icon_position'] ) : '';
        $has_icon = false;

        if( !empty($icon)
            && !empty($icon_position) 
            && $icon_position != 'hide' ) {
            $has_icon = true;
        }

        $wrap_class = array('reviewengine-btn-wrap');
        $wrap_class[] = 'reviewengine-btn-wrap-' . $id;
        $button_class = array('reviewengine-btn');
        $button_class[] = 'reviewengine-btn-' . $type;
        $button_class[] = 'reviewengine-btn-' . $size;
        $button_class[] = 'reviewengine-btn-' . $id;

        if( $has_icon ) {
            $wrap_class[] = 'reviewengine-btn-has-icon';
            $wrap_class[] = 'reviewengine-btn-icon-' . $icon_position;
        }

        $output = '<div class="' . implode(' ', $wrap_class) . '">';
        $output .= '<a href="' . $url . '" class="' . implode(' ', $button_class) . '" ' .reviewengine_link_attributes(). '>';
        if( $has_icon && $icon_position == 'before' ) {
            $output .= '<i class="fa ' . $icon . '"></i>';
        }
        $output .= '<span class="reviewengine-btn-text">' . $content . '</span>';
        if( $has_icon && $icon_position == 'after' ) {
            $output .= '<i class="fa ' . $icon . '"></i>';
        }
        $output .= '</a>';
        $output .= '</div>';

        return $output;
    }
}

ReviewEngine_Components_Shortcodes::init();