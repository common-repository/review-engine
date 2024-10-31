<?php
if( !defined('ABSPATH') ) { exit; }

class ReviewEngine_Components_Ajax {
	public static function init() {
		self::_add_actions();
	}

	private static function _add_actions() {
		add_action( 'wp_ajax_ree_create_search', array(__CLASS__, 'create_search') );
		add_action( 'wp_ajax_ree_insert_search', array(__CLASS__, 'insert_search') );
		add_action( 'wp_ajax_ree_insert_data', array(__CLASS__, 'insert_data') );
		
		add_action( 'wp_ajax_ree_insert_link', array(__CLASS__, 'modal_insert_link') );
		add_action( 'wp_ajax_ree_insert_button', array(__CLASS__, 'modal_insert_button') );
	}

	public static function create_search() {
		$data = stripslashes_deep( $_POST );

		if( empty( $data['_keyword'] ) ) {
			wp_send_json_error();
		}

		$keyword = sanitize_text_field( $data['_keyword'] );
		$locale = reviewengine_get_setting( 'default_search_locale' );
		$page = 1;

		if( !empty( $data['_locale'] ) ) {
			$locale	= reviewengine_amazon_locale( $data['_locale'] );	
		}

		if( !empty( $data['_page'] ) 
			&& is_numeric( $data['_page'] ) ) {
			$page = absint( $data['_page'] );
		}

		$amazon = new ReviewEngine_Amazon_API;
		$response = $amazon->search( $keyword, $page, $locale );

		if( is_wp_error( $response ) || $response == false ) {
			wp_send_json_error( array(
				'message' => __('There was an issue with your search and no items were found.')
			));
		}
		
		wp_send_json_success( $response );
	}

	public static function insert_search() {
		$data = stripslashes_deep( $_POST );

		$keyword = sanitize_text_field( $data['_keyword'] );
		$page = 1;

		if( !empty( $data['_page'] ) 
			&& is_numeric( $data['_page'] ) ) {
			$page = absint( $data['_page'] );
		}

		$args = array();
		$args['post_type'] = REE_POST_TYPE;
		$args['post_status'] = 'publish';
		$args['posts_per_page'] = REE_INSERT_SEARCH_LIMIT;
		$args['paged'] = $page;

		if( empty( $keyword ) ) {
			$args['meta_key'] = REE_META_KEY_PRODUCT_DATA;
			$args['meta_compare'] = 'EXISTS';
		} else {
			if( reviewengine_is_asin( $keyword ) ) {
				$args['meta_query'] = array(
					'relation' => 'AND',
					array(
						'key' => REE_META_KEY_PRODUCT_DATA,
						'compare' => 'EXISTS',
					),
					array(
						'key' => REE_META_KEY_PRODUCT_ASIN,
						'value' => $keyword,
						'compare' => '=',
					)
				);
			} else {
				$args['meta_key'] = REE_META_KEY_PRODUCT_DATA;
				$args['meta_compare'] = 'EXISTS';
				$args['s'] = $keyword;
			}
		}

		$the_query = new WP_Query( $args );
		if( $the_query->have_posts() ) :
			$products = array();

			while( $the_query->have_posts() ) : $the_query->the_post();

				$post_id = get_the_ID();
				$product_data = get_post_meta( $post_id, REE_META_KEY_PRODUCT_DATA, true );
				if( !empty( $product_data ) ) {
					$photo = '';

					$products[$post_id]['title'] = get_the_title( $post_id );

					if( isset( $product_data['asin'] ) ) {
						$products[$post_id]['asin'] = $product_data['asin'];
					}

					if( isset( $product_data['images'] ) 
                        && is_array( $product_data['images'] ) 
                        && empty( $photo ) ) {
                        foreach( $product_data['images'] as $image ) {
                            if( isset( $image['url'] ) 
                                && isset( $image['width'] ) 
                                && $image['width'] >= 50 
                                && $image['width'] <= 150 ) {
                                $photo = esc_url( $image['url'] );
                            }
                        }
                    }

                    if( !empty( $photo ) ) {
                    	$products[$post_id]['photo'] = $photo;	
                    }
					
					if( isset( $product_data['url'] ) ) {
						$products[$post_id]['url'] = esc_url( $product_data['url'] );
					}

					if( isset( $product_data['price'] ) ) {
						$products[$post_id]['price'] = $product_data['price'];
					}

					if( isset( $product_data['reviews']['star'] ) ) {
						$products[$post_id]['reviews_star'] = $product_data['reviews']['star'];
					}

					if( isset( $product_data['reviews']['total'] ) ) {
						$products[$post_id]['reviews_total'] = $product_data['reviews']['total'];
					}
				}

			endwhile; wp_reset_postdata();

			$response = array();
			$response['products'] = $products;
			$response['max_pages'] = $the_query->max_num_pages;
			wp_send_json_success( $response );
		else:
			wp_send_json_error( array(
				'message' => __('No products were found for your search query. Please try again!')
			));
		endif;
	}

	public static function insert_data() {
		$data = stripslashes_deep( $_POST );

		if( empty( $data['_post_id'] ) 
			|| !is_numeric( $data['_post_id'] ) ) {
			wp_send_json_error();
		}

		$post_id = absint( $data['_post_id'] );

		if( get_post_type( $post_id ) != REE_POST_TYPE ) {
			wp_send_json_error();
		}

		$product_data = get_post_meta( $post_id, REE_META_KEY_PRODUCT_DATA, true );
		$post_data = array();

		if( empty( $product_data ) ) {
			wp_send_json_error();
		}

		$post_data['product_id'] = $post_id;
		$post_data['title'] = get_the_title( $post_id );

		if( isset( $product_data['asin'] ) ) {
			$post_data['asin'] = $product_data['asin'];
		}

		if( isset( $product_data['locale'] ) ) {
			$post_data['locale'] = $product_data['locale'];
		}

		if( isset( $product_data['url'] ) ) {
			$post_data['url'] = esc_url( $product_data['url'] );
		}

		if( isset( $product_data['images'] ) 
			&& is_array( $product_data['images'] ) ) {
			$post_data['images'] = $product_data['images'];
		}

		wp_send_json_success( $post_data );
	}

	public static function reviewengine_save_button_style( $post_id, $form ) {
		if( empty( $post_id ) 
			|| empty( $form ) 
			|| !is_numeric( $post_id ) ) {
			return;
		}

		$current_style = get_post_meta( $post_id, REE_META_KEY_BUTTON_STYLE, true );
		$new_style = array();

		if( !empty( $current_style ) ) {
			$new_style = maybe_unserialize( $current_style );
		}

		$button_id = uniqid();
		$new_style[$button_id] = $form;

		if( !add_post_meta( $post_id, REE_META_KEY_BUTTON_STYLE, maybe_serialize($new_style), true ) ) {
   			update_post_meta( $post_id, REE_META_KEY_BUTTON_STYLE, maybe_serialize($new_style) );
		}

        delete_transient( 'reviewengine_button_' . $post_id );
		return $button_id;
	}

	public static function save_product( $data ) {
		if( empty( $data ) ) {
			return;
		}

		$post_id = null;

		if( !empty( $data['asin'] ) ) {
			$post_id = reviewengine_asin_exists( $data['asin'] );
		}

		$args = array();
		$args['post_type'] = REE_POST_TYPE;
		$args['post_status'] = 'publish';

		if( !empty( $data['title'] ) ) {
			$args['post_title'] = $data['title'];
		}

		if( $post_id ) {
			wp_update_post( $args );
		} else {
			$post_id = wp_insert_post( $args );
		}

		if( is_wp_error( $post_id ) ) {
			return;
		}

		$post_data = array();

		if( isset( $data['asin'] ) ) {
			$post_data['asin'] = $data['asin'];
		}

		if( isset( $data['locale'] ) ) {
			$post_data['locale'] = $data['locale'];
		}
		
		if( isset( $data['url'] ) ) {
			$post_data['url'] = $data['url'];
		}
		
		if( isset( $data['attributes']['Actor'] ) ) {
			$post_data['actor'] = $data['attributes']['Actor'];	
		}
		
		if( isset( $data['attributes']['Artist'] ) ) {
			$post_data['artist'] = $data['attributes']['Artist'];
		}

		if( isset( $data['attributes']['AspectRatio'] ) ) {
			$post_data['aspect-ratio'] = $data['attributes']['AspectRatio'];
		}

		if( isset( $data['attributes']['Actor'] ) ) {
			$post_data['audience-rating'] = $data['attributes']['Actor'];
		}

		if( isset( $data['attributes']['AudioFormat'] ) ) {
			$post_data['audio-format'] = $data['attributes']['AudioFormat'];
		}

		if( isset( $data['attributes']['Binding'] ) ) {
			$post_data['binding'] = $data['attributes']['Binding'];
		}

		if( isset( $data['attributes']['Brand'] ) ) {
			$post_data['brand'] = $data['attributes']['Brand'];
		}

		if( isset( $data['attributes']['ClothingSize'] ) ) {
			$post_data['clothing-size'] = $data['attributes']['ClothingSize'];
		}

		if( isset( $data['attributes']['Color'] ) ) {
			$post_data['color'] = $data['attributes']['Color'];
		}

		if( isset( $data['attributes']['Format'] ) ) {
			$post_data['format'] = $data['attributes']['Format'];
		}

		if( isset( $data['attributes']['Genre'] ) ) {
			$post_data['genre'] = $data['attributes']['Genre'];
		}

		if( isset( $data['attributes']['HardwarePlatform'] ) ) {
			$post_data['hardware-platform'] = $data['attributes']['HardwarePlatform'];
		}

		if( isset( $data['attributes']['Actor'] ) ) {
			$post_data['item-dimensions'] = $data['attributes']['Actor'];
		}

		if( isset( $data['attributes']['ItemPartNumber'] ) ) {
			$post_data['item-part-number'] = $data['attributes']['ItemPartNumber'];
		}

		if( isset( $data['attributes']['Manufacturer'] ) ) {
			$post_data['manufacturer'] = $data['attributes']['Manufacturer'];
		}

		if( isset( $data['attributes']['MediaType'] ) ) {
			$post_data['media-type'] = $data['attributes']['MediaType'];
		}

		if( isset( $data['attributes']['Model'] ) ) {
			$post_data['model'] = $data['attributes']['Model'];
		}

		if( isset( $data['attributes']['MPN'] ) ) {
			$post_data['mpn'] = $data['attributes']['MPN'];
		}

		if( isset( $data['attributes']['OperatingSystem'] ) ) {
			$post_data['operating-system'] = $data['attributes']['OperatingSystem'];
		}

		if( isset( $data['attributes']['Platform'] ) ) {
			$post_data['platform'] = $data['attributes']['Platform'];
		}

		if( isset( $data['attributes']['Size'] ) ) {
			$post_data['size'] = $data['attributes']['Size'];
		}

		if( isset( $data['attributes']['SKU'] ) ) {
			$post_data['sku'] = $data['attributes']['SKU'];
		}

		if( isset( $data['attributes']['UPC'] ) ) {
			$post_data['upc'] = $data['attributes']['UPC'];
		}

		if( isset( $data['attributes']['Warranty'] ) ) {
			$post_data['warranty'] = $data['attributes']['Warranty'];
		}

		if( isset( $data['attributes']['Feature'] ) ) {
			$post_data['feature'] = $data['attributes']['Feature'];
		}

		if( isset( $data['lowest_price_new'] ) ) {
			$post_data['lowest-price-new'] = $data['lowest_price_new'];
		}

		if( isset( $data['lowest_price_refurbished'] ) ) {
			$post_data['lowest-price-refurbished'] = $data['lowest_price_refurbished'];
		}

		if( isset( $data['lowest_price_used'] ) ) {
			$post_data['lowest-price-used'] = $data['lowest_price_used'];
		}

		if( isset( $data['offer']['price'] ) ) {
			if( $data['offer']['price'] == 'N/A' && isset( $data['lowest_price_new'] ) ) {
				$post_data['price'] = $data['lowest_price_new'];
			} else {
				$post_data['price'] = $data['offer']['price'];
			}
		}

		if( isset( $data['reviews'] ) ) {
			if( isset( $data['reviews']['star'] ) ) {
				$post_data['reviews']['star'] = trim( $data['reviews']['star'] );
			}

			if( isset( $data['reviews']['total'] ) ) {
				$post_data['reviews']['total'] = trim( $data['reviews']['total'] );
			}
		}

		if( isset( $locale ) ) {
			$post_data['locale'] = $locale;
		}

		if( isset( $data['images'] ) ) {
			$post_data['images'] = $data['images'];
		}

		if( !empty( $post_data['asin'] ) ) {
			update_post_meta( $post_id, REE_META_KEY_PRODUCT_ASIN, $post_data['asin'] );
		}

		if( !empty( $post_data ) ) {
			update_post_meta( $post_id, REE_META_KEY_PRODUCT_DATA, $post_data );
		}

		return $post_id;
	}

	public static function modal_insert_link() {
		if( empty( $_POST['_form'] ) 
			|| empty( $_POST['_post_id'] ) 
			|| !is_numeric( $_POST['_post_id'] ) ) {
			wp_send_json_error();
		}

		wp_parse_str( $_POST['_form'], $form );

		$locale = '';
		if( !empty( $_POST['_locale'] ) ) {
			$locale = sanitize_text_field( $_POST['_locale'] );
		}

		$post_id = absint( $_POST['_post_id'] );
		$tracking_id = null;
		$product_url = null;
		$response = array();

		if( !empty( $form['tracking-id'] ) ) {
			$tracking_id = sanitize_text_field( $form['tracking-id'] );
		}
		
		// SAVE PRODUCT DATA
		if( !empty( $_POST['_product_data'] ) ) {
			$product_data = $_POST['_product_data'];

			if( !empty( $product_data['url'] ) ) {
				$product_url = esc_url( $product_data['url'] );
			}

			if( !empty( $product_data['product_id'] ) && is_numeric( $product_data['product_id'] ) ) {
				$response['product_id'] = $product_data['product_id'];
			} else {
				if( !empty( $locale ) ) {
					$product_data['locale'] = $locale;
				}

				$product_id = self::save_product( $product_data );

				if( $product_id ) {
					$response['product_id'] = $product_id;
				}
			}
		}
		// END SAVE
		
		// AFF LINK
		if( $product_url ) {
			$response['link_atts'] = reviewengine_link_attributes();
			$response['url'] = reviewengine_get_url( $product_url, array(
				'tracking_id' => $tracking_id
			));
		}
		// END AFF LINK

		wp_send_json_success( $response );
	}

	public static function modal_insert_button() {
		if( empty( $_POST['_form'] ) 
			|| empty( $_POST['_post_id'] ) 
			|| !is_numeric( $_POST['_post_id'] ) ) {
			wp_send_json_error();
		}

		wp_parse_str( $_POST['_form'], $form );

		$locale = '';
		if( !empty( $_POST['_locale'] ) ) {
			$locale = sanitize_text_field( $_POST['_locale'] );
		}

		$post_id = absint( $_POST['_post_id'] );
		$tracking_id = null;
		$product_url = null;
		$response = array();

		if( !empty( $form['tracking-id'] ) ) {
			$tracking_id = sanitize_text_field( $form['tracking-id'] );
		}
		
		// SAVE PRODUCT DATA
		if( !empty( $_POST['_product_data'] ) ) {
			$product_data = $_POST['_product_data'];
			
			if( !empty( $product_data['url'] ) ) {
				$product_url = esc_url( $product_data['url'] );
			}

			if( !empty( $product_data['product_id'] ) && is_numeric( $product_data['product_id'] ) ) {
				$response['product_id'] = $product_data['product_id'];
			} else {
				if( !empty( $locale ) ) {
					$product_data['locale'] = $locale;
				}

				$product_id = self::save_product( $product_data );

				if( $product_id ) {
					$response['product_id'] = $product_id;
				}
			}
		}
		// END SAVE

		// AFF LINK
		if( $product_url ) {
			$response['url'] = reviewengine_get_url( $product_url, array(
				'tracking_id' => $tracking_id
			));
		}
		// END AFF LINK

		// SAVE BUTTON
		$button_id = self::reviewengine_save_button_style( $post_id, $form );

		if( $button_id ) {
			$response['button_id'] = $button_id;
		}
		// END SAVE BUTTON

		wp_send_json_success( $response );
	}
}

ReviewEngine_Components_Ajax::init();