<?php
$post_id = $post->ID;
$post_data = get_post_meta( $post_id, REE_META_KEY_PRODUCT_DATA, true );
$post_data = maybe_unserialize( $post_data );
?>
<h2 class="re-nav-tab nav-tab-wrapper wp-clearfix">
	<a href="#re-basic" class="nav-tab nav-tab-active"><?php _e('Basic', 'reviewengine'); ?></a>
	<a href="#re-attributes" class="nav-tab"><?php _e('Attributes', 'reviewengine'); ?></a>
	<a href="#re-gallery" class="nav-tab"><?php _e('Gallery', 'reviewengine'); ?></a>
</h2>

<div id="re-basic">
	<table class="form-table">
		<tbody>
			<?php
			echo '<tr>';
			echo '<th scope="row" colspan="2"><a href="' .esc_url( $post_data['url'] ). '" target="_blank">' .$post->post_title. '</a></th>';
			echo '</tr>';

			reviewengine_wp_text_input( array(
				'id' => '_re_asin',
				'name' => 'ree_product[asin]',
				'value' => $post_data['asin'] ? $post_data['asin'] : '',
				'label' => __( 'Amazon ASIN', 'reviewengine' )
			));

			reviewengine_wp_text_input( array(
				'id' => '_re_price',
				'name' => 'ree_product[price]',
				'value' => $post_data['price'] ? $post_data['price'] : '',
				'label' => __( 'Price', 'reviewengine' )
			));

			reviewengine_wp_text_input( array(
				'id' => '_re_lowest-price-new',
				'name' => 'ree_product[lowest-price-new]',
				'value' => $post_data['lowest-price-new'] ? $post_data['lowest-price-new'] : '',
				'label' => __( 'Lowest Price New', 'reviewengine' )
			));

			reviewengine_wp_text_input( array(
				'id' => '_re_lowest-price-refurbished',
				'name' => 'ree_product[lowest-price-refurbished]',
				'value' => $post_data['lowest-price-refurbished'] ? $post_data['lowest-price-refurbished'] : '',
				'label' => __( 'Lowest Price Refurbished', 'reviewengine' )
			));

			reviewengine_wp_text_input( array(
				'id' => '_re_lowest-price-used',
				'name' => 'ree_product[lowest-price-used]',
				'value' => $post_data['lowest-price-used'] ? $post_data['lowest-price-used'] : '',
				'label' => __( 'Lowest Price Used', 'reviewengine' )
			));
			?>
		</tbody>
	</table>
</div>

<div id="re-attributes" style="display: none;">
	<table class="form-table">
		<tbody>
			<?php
			reviewengine_wp_text_input( array(
				'id' => '_re_actor',
				'name' => 'ree_product[actor]',
				'value' => $post_data['actor'] ? $post_data['actor'] : '',
				'label' => __( 'Actor', 'reviewengine' )
			));

			reviewengine_wp_text_input( array(
				'id' => '_re_artist',
				'name' => 'ree_product[artist]',
				'value' => $post_data['artist'] ? $post_data['artist'] : '',
				'label' => __( 'Artist', 'reviewengine' )
			));

			reviewengine_wp_text_input( array(
				'id' => '_re_aspect-ratio',
				'name' => 'ree_product[aspect-ratio]',
				'value' => $post_data['aspect-ratio'] ? $post_data['aspect-ratio'] : '',
				'label' => __( 'Aspect Ratio', 'reviewengine' )
			));

			reviewengine_wp_text_input( array(
				'id' => '_re_audience-rating',
				'name' => 'ree_product[audience-rating]',
				'value' => $post_data['audience-rating'] ? $post_data['audience-rating'] : '',
				'label' => __( 'Audience Rating', 'reviewengine' )
			));

			reviewengine_wp_text_input( array(
				'id' => '_re_audio-format',
				'name' => 'ree_product[audio-format]',
				'value' => $post_data['audio-format'] ? $post_data['audio-format'] : '',
				'label' => __( 'Audio Format', 'reviewengine' )
			));

			reviewengine_wp_text_input( array(
				'id' => '_re_binding',
				'name' => 'ree_product[binding]',
				'value' => $post_data['binding'] ? $post_data['binding'] : '',
				'label' => __( 'Binding', 'reviewengine' )
			));

			reviewengine_wp_text_input( array(
				'id' => '_re_brand',
				'name' => 'ree_product[brand]',
				'value' => $post_data['brand'] ? $post_data['brand'] : '',
				'label' => __( 'Brand', 'reviewengine' )
			));

			reviewengine_wp_text_input( array(
				'id' => '_re_clothing-size',
				'name' => 'ree_product[clothing-size]',
				'value' => $post_data['clothing-size'] ? $post_data['clothing-size'] : '',
				'label' => __( 'Clothing Size', 'reviewengine' )
			));

			reviewengine_wp_text_input( array(
				'id' => '_re_color',
				'name' => 'ree_product[color]',
				'value' => $post_data['color'] ? $post_data['color'] : '',
				'label' => __( 'Color', 'reviewengine' )
			));

			reviewengine_wp_text_input( array(
				'id' => '_re_format',
				'name' => 'ree_product[format]',
				'value' => $post_data['format'] ? $post_data['format'] : '',
				'label' => __( 'Format', 'reviewengine' )
			));

			reviewengine_wp_text_input( array(
				'id' => '_re_genre',
				'name' => 'ree_product[genre]',
				'value' => $post_data['genre'] ? $post_data['genre'] : '',
				'label' => __( 'Genre', 'reviewengine' )
			));

			reviewengine_wp_text_input( array(
				'id' => '_re_hardware-platform',
				'name' => 'ree_product[hardware-platform]',
				'value' => $post_data['hardware-platform'] ? $post_data['hardware-platform'] : '',
				'label' => __( 'Hardware Platform', 'reviewengine' )
			));

			reviewengine_wp_text_input( array(
				'id' => '_re_item-dimensions',
				'name' => 'ree_product[item-dimensions]',
				'value' => $post_data['item-dimensions'] ? $post_data['item-dimensions'] : '',
				'label' => __( 'Item Dimensions', 'reviewengine' )
			));

			reviewengine_wp_text_input( array(
				'id' => '_re_item-part-number',
				'name' => 'ree_product[item-part-number]',
				'value' => $post_data['item-part-number'] ? $post_data['item-part-number'] : '',
				'label' => __( 'Item Part Number', 'reviewengine' )
			));

			reviewengine_wp_text_input( array(
				'id' => '_re_manufacturer',
				'name' => 'ree_product[manufacturer]',
				'value' => $post_data['manufacturer'] ? $post_data['manufacturer'] : '',
				'label' => __( 'Manufacturer', 'reviewengine' )
			));

			reviewengine_wp_text_input( array(
				'id' => '_re_media-type',
				'name' => 'ree_product[media-type]',
				'value' => $post_data['media-type'] ? $post_data['media-type'] : '',
				'label' => __( 'Media Type', 'reviewengine' )
			));

			reviewengine_wp_text_input( array(
				'id' => '_re_model',
				'name' => 'ree_product[model]',
				'value' => $post_data['model'] ? $post_data['model'] : '',
				'label' => __( 'Model', 'reviewengine' )
			));

			reviewengine_wp_text_input( array(
				'id' => '_re_mpn',
				'name' => 'ree_product[mpn]',
				'value' => $post_data['mpn'] ? $post_data['mpn'] : '',
				'label' => __( 'MPN', 'reviewengine' )
			));

			reviewengine_wp_text_input( array(
				'id' => '_re_operating-system',
				'name' => 'ree_product[operating-system]',
				'value' => $post_data['operating-system'] ? $post_data['operating-system'] : '',
				'label' => __( 'Operating System', 'reviewengine' )
			));

			reviewengine_wp_text_input( array(
				'id' => '_re_platform',
				'name' => 'ree_product[platform]',
				'value' => $post_data['platform'] ? $post_data['platform'] : '',
				'label' => __( 'Platform', 'reviewengine' )
			));

			reviewengine_wp_text_input( array(
				'id' => '_re_size',
				'name' => 'ree_product[size]',
				'value' => $post_data['size'] ? $post_data['size'] : '',
				'label' => __( 'Size', 'reviewengine' )
			));

			reviewengine_wp_text_input( array(
				'id' => '_re_sku',
				'name' => 'ree_product[sku]',
				'value' => $post_data['sku'] ? $post_data['sku'] : '',
				'label' => __( 'SKU', 'reviewengine' )
			));

			reviewengine_wp_text_input( array(
				'id' => '_re_upc',
				'name' => 'ree_product[upc]',
				'value' => $post_data['upc'] ? $post_data['upc'] : '',
				'label' => __( 'UPC', 'reviewengine' )
			));

			reviewengine_wp_text_input( array(
				'id' => '_re_warranty',
				'name' => 'ree_product[warranty]',
				'value' => $post_data['warranty'] ? $post_data['warranty'] : '',
				'label' => __( 'Warranty', 'reviewengine' )
			));

			$feature = '';
			if( isset( $post_data['feature'] ) ) {
				$feature = $post_data['feature'];

				if( is_array( $post_data['feature'] ) ) {
					$feature = implode( '; ', $post_data['feature'] );
				}
			}
			reviewengine_wp_textarea_input( array(
				'id' => '_re_feature',
				'name' => 'ree_product[feature]',
				'value' => $feature,
				'rows' => 10,
				'cols' => 50,
				'label' => __( 'Feature', 'reviewengine' ),
				'description' => __( '', 'reviewengine' )
			));
			?>
		</tbody>
	</table>
</div><!-- #re-attributes -->

<div id="re-gallery" style="display: none;">
	<table class="form-table">
		<tbody>
			<tr>
				<th scope="row">
					<label for="asin">Gallery</label>
				</th>
				<td>
					<?php
					if( isset( $post_data['images'] ) ) {
						echo '<ul class="re-gallery">';
						foreach( $post_data['images'] as $image ) {
							echo '<li><a href="' .$image['url']. '" target="_blank"><img src="' .$image['url']. '" alt=""></a></li>';
						}
						echo '</ul>';
					}
					?>
				</td>
			</tr>
		</tbody>
	</table>
</div><!-- #re-gallery -->