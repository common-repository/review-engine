<?php
if( !defined('ABSPATH') ) { exit; }

class ReviewEngine_Amazon_API {
	var $access_key;
	var $secret_key;

	public function __construct() {
		$this->access_key = reviewengine_get_setting('access_key');
		$this->secret_key = reviewengine_get_setting('secret_key');
	}

	public function set_credentials($access_key, $secret_key) {
		$this->access_key = $access_key;
		$this->secret_key = $secret_key;
	}

	public function get_item($identifier, $locale = null, $associate_tag = null) {
		$query = array(
			'AssociateTag' => $associate_tag,
			'IdType' => 'ASIN',
			'ItemId' => urlencode($identifier),
			'Operation' => 'ItemLookup',
			'ResponseGroup' => 'Images,ItemAttributes,Offers,Reviews',
			'Sort' => 'relevancerank',
		);

		$response = $this->send_request( $query, $locale );

		if( !is_wp_error($response) ) {
			return false;
		}

		return $response;
	}

	public function search( $keywords, $page = 1, $locale = null, $associate_tag = null) {
		$query = array(
			'AssociateTag' => $associate_tag,
			'ItemPage' => $page,
			'Keywords' => urlencode($keywords),
			'Operation' => 'ItemSearch',
			'ResponseGroup' => 'BrowseNodes,Images,ItemAttributes,Offers,Reviews',
			'SearchIndex' => 'All',
		);

		$response = $this->send_request( $query, $locale );

		if( is_wp_error( $response ) || $response == false ) {
			return false;
		}

		$data = $this->extract_search_data( $response );
		$data['locale'] = $locale;
		return $data;
	}

	public function extract_search_data( $response ) {
		$keywords = '';
		$page = 1;
		$pages = 1;
		$items = array();

		if( isset($response['Items']) && 
			isset($response['Items']['Request']) && 
			isset($response['Items']['Request']['ItemSearchRequest']) && 
			isset($response['Items']['Request']['ItemSearchRequest']['ItemPage']) ) {
			$page = max( 1, $response['Items']['Request']['ItemSearchRequest']['ItemPage'] );
		}

		if( isset($response['Items']) && 
			isset($response['Items']['TotalPages']) ) {
			$pages = min( 5, intval($response['Items']['TotalPages']) ) ;
		}

		if( isset($response['Items']) && 
			isset($response['Items']['Request']) && 
			isset($response['Items']['Request']['ItemSearchRequest']) && 
			isset($response['Items']['Request']['ItemSearchRequest']['Keywords']) ) {
			$keywords = $response['Items']['Request']['ItemSearchRequest']['Keywords'];
		}

		if( isset($response['Items']) && 
			isset($response['Items']['Item']) ) {
			$items = array_map( array($this, 'extract_product_data'), $response['Items']['Item'] );
		}

		return compact( 'keywords', 'page', 'pages', 'items' );
	}

	public function extract_product_data( $item ) {
		$attributes = array();
		if( isset($item['ItemAttributes']) && is_array($item['ItemAttributes']) ) {
			$attributes = $this->extract_product_attributes( $item['ItemAttributes'] );
		}

		// $categories = array();
		// if( isset($item['BrowseNodes']) && is_array($item['BrowseNodes']) ) {
		// 	$categories = $this->extract_product_categories( $item['BrowseNodes'] );
		// }

		$asin = false;
		if( isset( $item['ASIN'] ) ) {
			$asin = $item['ASIN'];
		}

		$title = '';
		if( isset( $attributes['Title'] ) ) {
			$title = $attributes['Title'];
		}

		$url = '';
		if( isset( $item['DetailPageURL'] ) ) {
			$url = current( explode( '?', urldecode( $item['DetailPageURL'] ) ) );
		}

		$offer = array();
		if( isset($item['Offers']) && is_array($item['Offers']) && isset($item['Offers']['Offer']) && is_array($item['Offers']['Offer']) ) {
			$offer = $item['Offers']['Offer'];
		}

		$price = 0;
		if( isset($offer['OfferListing']) && isset($offer['OfferListing']['SalePrice']) && isset($offer['OfferListing']['SalePrice']['FormattedPrice']) ) {
			$price = $offer['OfferListing']['SalePrice']['FormattedPrice'];

			if( isset($offer['OfferListing']) && isset($offer['OfferListing']['Price']) && isset($offer['OfferListing']['Price']['FormattedPrice']) && !isset($attributes['ListPrice']) ) {
				$attributes['ListPrice'] = $offer['OfferListing']['Price']['FormattedPrice'];
			}
		} elseif( isset($offer['OfferListing']) && isset($offer['OfferListing']['Price']) && isset($offer['OfferListing']['Price']['FormattedPrice']) ) {
			$price = $offer['OfferListing']['Price']['FormattedPrice'];
		} else {
			$price = __('N/A');
		}

		$offer = array();
		$offer['price'] = $price;
		
		$offer['condition'] = __('Unknown');
		if( isset($offer['OfferAttributes']) && isset($offer['OfferAttributes']['Condition']) ) {
			$offer['condition'] = $offer['OfferAttributes']['Condition'];
		}

		$offer['saved'] = __('N/A');
		if( isset($offer['OfferListing']) && isset($offer['OfferListing']['AmountSaved']) && isset($offer['OfferListing']['AmountSaved']['FormattedPrice']) ) {
			$offer['saved'] = $offer['OfferListing']['AmountSaved']['FormattedPrice'];
		}

		$lowest_price_new = 0;
		if( isset($item['OfferSummary']) && isset($item['OfferSummary']['LowestNewPrice']) && isset($item['OfferSummary']['LowestNewPrice']['FormattedPrice']) ) {
			$lowest_price_new = $item['OfferSummary']['LowestNewPrice']['FormattedPrice'];
		}

		$lowest_price_refurbished = 0;
		if( isset($item['OfferSummary']) && isset($item['OfferSummary']['LowestRefurbishedPrice']) && isset($item['OfferSummary']['LowestRefurbishedPrice']['FormattedPrice']) ) {
			$lowest_price_refurbished = $item['OfferSummary']['LowestRefurbishedPrice']['FormattedPrice'];
		}

		$lowest_price_used = 0;
		if( isset($item['OfferSummary']) && isset($item['OfferSummary']['LowestUsedPrice']) && isset($item['OfferSummary']['LowestUsedPrice']['FormattedPrice']) ) {
			$lowest_price_used = $item['OfferSummary']['LowestUsedPrice']['FormattedPrice'];
		}

		$reviews = array();
		if( isset( $item['CustomerReviews']['IFrameURL'] ) ) {
			$reviews = $this->extract_product_reviews( $item['CustomerReviews']['IFrameURL'] );
		}

		$images = $this->extract_product_images( $item );
		
		return compact(
			'asin',
			'title',
			'url',
			'attributes',
			'images',
			'lowest_price_new',
			'lowest_price_refurbished',
			'lowest_price_used',
			'offer',
			'reviews'
		);
	}

	public function extract_product_attributes( $attributes ) {
		$normalized = array();

		foreach($attributes as $name => $value) {
			if(is_string($value)) {
				$normalized[$name] = $value;
			} else if(is_array($value) && preg_match('#^.*Dimensions$#', $name)) {
				$normalized[$name] = $value;
			} else if(is_array($value) && preg_match('#^.*List$#', $name)) {
				$normalized[$name] = array_values($value);
			} else if(is_array($value) && preg_match('#^.*Price$#', $name)) {
				$normalized[$name] = $value['FormattedPrice'];
			} else if(is_array($value)) {
				$normalized[$name] = array_values($value);
			}
		}

		return $normalized;
	}

	public function extract_product_categories($browse_nodes) {
		if(isset($browse_nodes['BrowseNode'])) {
			$browse_nodes = isset($browse_nodes['BrowseNode'][0]) ? $browse_nodes['BrowseNode'] : array($browse_nodes['BrowseNode']);
		} else {
			$browse_nodes = array();
		}

		$normalized = array();
		foreach($browse_nodes as $key => $browse_node) {
			$normalized[$key]['ancestors'] = array();
			if( isset( $browse_node['Ancestors'] ) ) {
				$normalized[$key]['ancestors'] = $this->extract_product_categories( $browse_node['Ancestors'] );
			}

			$normalized[$key]['children'] = array();
			if( isset( $browse_node['Children'] ) ) {
				$normalized[$key]['children'] = $this->extract_product_categories( $browse_node['Children'] );
			}

			$normalized[$key]['id'] = null;
			if( isset( $browse_node['BrowseNodeId'] ) ) {
				$normalized[$key]['id'] = $browse_node['BrowseNodeId'];
			}

			$normalized[$key]['name'] = null;
			if( isset( $browse_node['Name'] ) ) {
				$normalized[$key]['name'] = $browse_node['Name'];
			}
		}

		return $normalized;
	}

	public function extract_product_images( $item ) {
		$images = array();
		$image_urls = array();

		if( isset($item['ImageSets']) && is_array($item['ImageSets']) && isset($item['ImageSets']['ImageSet']) && is_array($item['ImageSets']['ImageSet']) ) {
			$image_sets = isset($item['ImageSets'][0]) ? $item['ImageSets']['ImageSet'] : array($item['ImageSets']['ImageSet']);

			foreach( $image_sets as $image_set ) {
				foreach( $image_set as $image_key => $image ) {
					if('@attributes' === $image_key || !isset($image['URL']) || in_array($image['URL'], $image_urls)) {
						continue;
					}

					$image_urls[] = $image['URL'];

					$images[] = array(
						'url' => $image['URL'],
						'height' => $image['Height'],
						'width' => $image['Width'],
					);
				}
			}
		} else {
			if( isset($item['SmallImage']) && !in_array($item['SmallImage']['URL'], $image_urls) ) {
				$image_urls[] = $item['SmallImage']['URL'];

				$images[] = array(
					'url' => $item['SmallImage']['URL'],
					'height' => $item['SmallImage']['Height'],
					'width' => $item['SmallImage']['Width'],
				);
			}

			if( isset($item['MediumImage']) && !in_array($item['MediumImage']['URL'], $image_urls) ) {
				$image_urls[] = $item['MediumImage']['URL'];

				$images[] = array(
					'url' => $item['MediumImage']['URL'],
					'height' => $item['MediumImage']['Height'],
					'width' => $item['MediumImage']['Width'],
				);
			}

			if( isset($item['LargeImage']) && !in_array($item['LargeImage']['URL'], $image_urls) ) {
				$image_urls[] = $item['LargeImage']['URL'];

				$images[] = array(
					'url' => $item['LargeImage']['URL'],
					'height' => $item['LargeImage']['Height'],
					'width' => $item['LargeImage']['Width'],
				);
			}
		}

		return $images;
	}

	public function extract_product_reviews( $iframe_url ) {
		$iframe_url = str_replace( 'http://', 'https://', $iframe_url );

		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_ENCODING, 'gzip,deflate');
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 120);
		curl_setopt( $ch, CURLOPT_URL, $iframe_url);
		curl_setopt( $ch, CURLOPT_HEADER, 0);
		curl_setopt( $ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36');

		$data = curl_exec($ch);
		curl_close( $ch);

		if( empty( $data ) ) {
			return false;
		}

		$doc = new DOMDocument(); 
	    @$doc->loadHTML( $data );
	    $xpath = new DOMXPath($doc);
	    $elements = $xpath->query(".//span[@class='crAvgStars']");
	    $output = array();

	    if( !empty( $elements ) && $elements->length > 0 ) {
	    	$element_1 = $elements->item(0)->getElementsByTagName("img");
	    	$element_2 = $elements->item(0)->getElementsByTagName("a");

	    	if( !empty( $element_1 ) && $element_1->length > 0 ) {
	    		$text = $element_1->item(0)->getAttribute("title");
	    		$text = str_replace( 'out of 5 stars', '', $text );
	    		$text = str_replace( 'out of 5 star', '', $text );
	    		$star = trim( $text );
	    		$output['star'] = $star;
	    	}

	    	if( !empty( $element_2 ) && $element_2->length > 0 ) {
	    		$text = $element_2->item(1)->textContent;
	    		$text = str_replace( 'customer reviews', '', $text );
	    		$text = str_replace( 'customer review', '', $text );
	    		$total = trim( $text );
	    		$output['total'] = $total;
	    	}
	    }

	    return $output;
	}

	public function send_request( $query, $locale = null ) {
		$locale = reviewengine_amazon_locale($locale);

		if(!isset($query['AssociateTag']) || empty($query['AssociateTag'])) {
			$query['AssociateTag'] = reviewengine_locale_associate_tag($locale);
		}

		if(!isset($query['AWSAccessKeyId']) || empty($query['AWSAccessKeyId'])) {
			$query['AWSAccessKeyId'] = $this->access_key;
		}

		if(!isset($query['Service']) || empty($query['Service'])) {
			$query['Service'] = 'AWSECommerceService';
		}

		if(!isset($query['Timestamp']) || empty($query['Timestamp'])) {
			$query['Timestamp'] = gmdate('Y-m-d\TH:i:s\Z');
		}

		if(!isset($query['Version']) || empty($query['Version'])) {
			$query['Version'] = '2013-08-01';
		}

		$request_url = $this->create_sign_url(add_query_arg($query, reviewengine_amazon_endpoint($locale)));
		$response = wp_remote_get($request_url, array(
			'timeout' => 10,
			'user-agent' => sprintf( __('ReviewEngine Version %s'), REE_VERSION ),
		));

		if( is_wp_error( $response ) ) {
			return $response;
		}

		return $this->get_response( wp_remote_retrieve_body($response) );
	}

	public function create_sign_url($url) {
		$url = urldecode($url);

		$urlparts = parse_url($url);

		foreach (explode('&', $urlparts['query']) as $part) {
			if (strpos($part, '=')) {
				list($name, $value) = explode('=', $part);
			} else {
				$name = $part;
				$value = '';
			}
			$params[$name] = $value;
		}

		ksort($params);

		$canonical = '';
		foreach ($params as $key=>$val) {
			$canonical .= "{$key}=".rawurlencode($val).'&';
		}

		$canonical = preg_replace("/&$/", '', $canonical);

		$canonical = str_replace(array(' ', '+', ', ', ';'), array('%20', '%20', urlencode(','), urlencode(':')), $canonical);

		$string_to_sign = "GET\n{$urlparts['host']}\n{$urlparts['path']}\n$canonical";

		$signature = base64_encode(hash_hmac('sha256', $string_to_sign, $this->secret_key, true));

		return "{$urlparts['scheme']}://{$urlparts['host']}{$urlparts['path']}?$canonical&Signature=".rawurlencode($signature);
	}

	public function get_response($response_string) {
		$xml = @simplexml_load_string($response_string);

		if( !is_object($xml) ) {
			$response = new WP_Error('parse_response_xml_error', __('Could not parse the response from Amazon as XML.'));
		} elseif( isset($xml->Error) ) {
			$response = new WP_Error((string)$xml->Error->Code, (string)$xml->Error->Message);
		} elseif( isset($xml->Items->Request->Errors->Error) ) {
			$response = new WP_Error((string)$xml->Items->Request->Errors->Error->Code, (string)$xml->Items->Request->Errors->Error->Message);
		} else {
			$response = json_decode(json_encode($xml), true);

			if(isset($response['Items']) && isset($response['Items']['Item'])) {
				if(isset($response['Items']['Item']) && isset($response['Items']['Item']['ASIN'])) {
					$response['Items']['Item'] = array($response['Items']['Item']);
				}

				foreach($response['Items']['Item'] as $item_key => $item) {
					if(!isset($item['ImageSets']) || !isset($item['ImageSets']['ImageSet']) || !is_array($item['ImageSets']['ImageSet'])) {
						$response['Items']['Item'][$item_key]['ImageSets']['ImageSet'] = array();
					}

					$sets = array();

					if(!isset($response['Items']['Item'][$item_key]['ImageSets']['ImageSet'][0])) {
						$response['Items']['Item'][$item_key]['ImageSets']['ImageSet'][] = $response['Items']['Item'][$item_key]['ImageSets']['ImageSet'];
					}

					foreach($response['Items']['Item'][$item_key]['ImageSets']['ImageSet'] as $set) {
						$attributes = isset($set['@attributes']) && is_array($set['@attributes']) ? $set['@attributes'] : false;
						unset($set['@attributes']);

						if($attributes && isset($attributes['Category']) && 'primary' === $attributes['Category']) {
							$set = array_reverse($set);

							foreach($set as $image_key => $image) {
								array_unshift($sets, $image);
							}
						} else {
							foreach($set as $image_key => $image) {
								array_push($sets, $image);
							}
						}
					}

					$response['Items']['Item'][$item_key]['ImageSets']['ImageSet'] = $sets;
				}
			}
		}

		if( !is_wp_error($response) ) {
			return $response;
		}

		return false;
	}
}