<?php
function reviewengine_get_setting( $setting_name, $default = false ) {
	return apply_filters(__FUNCTION__ . '_' . $setting_name, apply_filters(__FUNCTION__, ReviewEngine_Components_Settings::get_setting($setting_name, $default), $setting_name, $default), $default);
}

function reviewengine_get_setting_field_id( $setting_name ) {
	return apply_filters(__FUNCTION__, sprintf('%s-%s', REE_SETTINGS_NAME, esc_attr($setting_name)));
}

function reviewengine_get_setting_field_name( $setting_name ) {
	return apply_filters(__FUNCTION__, sprintf('%s[%s]', REE_SETTINGS_NAME, esc_attr($setting_name)));
}

function reviewengine_amazon_locales() {
	return apply_filters(__FUNCTION__, array(
		'US' => __('United States'),
		'BR' => __('Brazil'),
		'CA' => __('Canada'),
		'CN' => __('China'),
		'FR' => __('France'),
		'DE' => __('Germany'),
		'IT' => __('Italy'),
		'IN' => __('India'),
		'JP' => __('Japan'),
		'ES' => __('Spain'),
		'UK' => __('United Kingdom'),
	));
}

function reviewengine_amazon_locale( $locale ) {
	$locale = strtoupper( $locale );
	$locales = reviewengine_amazon_locales();

	return isset($locales[$locale]) ? $locale : key($locales);
}

function reviewengine_locale_associate_tag( $locale ) {
	$name = 'associates_' . strtolower($locale);
	$tracking_id = reviewengine_get_setting( $name );

	return $tracking_id;
}

function reviewengine_my_locales() {
	$locales = reviewengine_amazon_locales();
	$my_locale = array();

	foreach( $locales as $key => $value ) {
		$option_name = 'associates_' . strtolower( $key );
		$tracking_id = reviewengine_get_setting( $option_name );
		if( !empty( $tracking_id ) ) {
			$my_locale[$key] = $value;
		}
	}

	return $my_locale;
}

function reviewengine_tracking_ids() {
	$locales = reviewengine_amazon_locales();
	$output = array();

	foreach( $locales as $key => $value ) {
		$option_name = 'associates_' . strtolower( $key );
		$tracking_id = reviewengine_get_setting( $option_name );

		if( !empty( $tracking_id ) ) {
			$output[$key] = $tracking_id;
		}
	}

	return $output;
}

function reviewengine_amazon_signup_urls() {
	return apply_filters(__FUNCTION__, array(
		'US' => 'https://affiliate-program.amazon.com/',
		'BR' => 'https://associados.amazon.com.br/',
		'CA' => 'https://associates.amazon.ca/',
		'CN' => 'https://associates.amazon.cn/',
		'DE' => 'https://partnernet.amazon.de/',
		'ES' => 'https://afiliados.amazon.es/',
		'FR' => 'https://partenaires.amazon.fr/',
		'IN' => 'https://affiliate-program.amazon.in/',
		'IT' => 'https://programma-affiliazione.amazon.it/',
		'JP' => 'https://affiliate.amazon.co.jp/',
		'UK' => 'https://affiliate-program.amazon.co.uk/',
	));
}

function reviewengine_amazon_signup_url( $locale ) {
	$locale = reviewengine_amazon_locale( $locale );
	$locale_associate_signup_urls = reviewengine_amazon_signup_urls();

	return isset($locale_associate_signup_urls[$locale]) ? $locale_associate_signup_urls[$locale] : current($locale_associate_signup_urls);
}

function reviewengine_amazon_endpoints() {
	return apply_filters(__FUNCTION__, array(
		'US' => 'https://webservices.amazon.com/onca/xml',
		'BR' => 'https://webservices.amazon.com.br/onca/xml',
		'CA' => 'https://webservices.amazon.ca/onca/xml',
		'CN' => 'https://webservices.amazon.cn/onca/xml',
		'DE' => 'https://webservices.amazon.de/onca/xml',
		'ES' => 'https://webservices.amazon.es/onca/xml',
		'FR' => 'https://webservices.amazon.fr/onca/xml',
		'IT' => 'https://webservices.amazon.it/onca/xml',
		'IN' => 'https://webservices.amazon.in/onca/xml',
		'JP' => 'https://webservices.amazon.co.jp/onca/xml',
		'UK' => 'https://webservices.amazon.co.uk/onca/xml',
	));
}

function reviewengine_amazon_endpoint( $locale ) {
	$locale = reviewengine_amazon_locale($locale);
	$locale_endpoints = reviewengine_amazon_endpoints();

	return isset($locale_endpoints[$locale]) ? $locale_endpoints[$locale] : current($locale_endpoints);
}

function reviewengine_get_url( $url, $args = array() ) {
	$params = array();
	$url = esc_url( $url );

	if( !empty( $args['tracking_id'] ) ) {
		$params['tag'] = $args['tracking_id'];
	}
	
	return add_query_arg( apply_filters( 'reviewengine_url_params', $params ), $url );
}

function reviewengine_is_asin( $string ) {
    $pattern = "/B[0-9]{2}[0-9A-Z]{7}|[0-9]{9}(X|0-9])/";

    if( preg_match( $pattern, $string, $matches ) ) {
        return true;
    }
}

function reviewengine_asin_exists( $asin ) {
	$args = array();
	$args['post_type'] = REE_POST_TYPE;
	$args['post_status'] = 'publish';
	$args['posts_per_page'] = 1;
	$args['meta_key'] = REE_META_KEY_PRODUCT_ASIN;
	$args['meta_value'] = $asin;
	$args['meta_compare'] = '=';

	$posts = get_posts( $args );

	if( empty( $posts ) ) {
		return false;
	} elseif( !empty( $posts[0]->ID ) ) {
		return $posts[0]->ID;
	}

	return false;
}

function reviewengine_link_attributes( $link_atts = array() ) {
    $link_target = reviewengine_get_setting('link_target');
    $link_rel = reviewengine_get_setting('link_rel');

    if( !empty( $link_target ) && $link_target == 1 ) {
        $link_atts['target'] = '_blank';
    }

    if( !empty( $link_rel ) && $link_rel == 1 ) {
        $link_atts['rel'] = array('nofollow');
    }

    if( empty( $link_atts ) ) {
        return '';
    }

    ksort($link_atts);
    $parts = array();

    foreach( array_filter($link_atts) as $name => $value ) {
        if( is_array($value) ) {
            $value = implode(' ', array_map('esc_attr', $value));
        } else {
            $value = esc_attr($value);
        }

        $parts[] = "{$name}=\"{$value}\"";
    }

    return implode(' ', $parts);
}

function reviewengine_amazon_api_status() {
	$api_status = reviewengine_get_setting('api_status');

	if( empty( $api_status ) || $api_status == 'disconnected' ) {
		return false;		
	}

	return true;
}