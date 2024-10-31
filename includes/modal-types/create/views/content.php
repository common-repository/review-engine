<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<?php
	do_action('admin_print_styles');
	do_action('admin_print_scripts');
	do_action('admin_head');
	?>
</head>

<body>
	<div id="loader" style="display: none;"><span class="spinner"><i class="bounce1"></i><i class="bounce2"></i><i class="bounce3"></i></span></div>

	<div class="wrap" id="reviewengine-modal">
	<?php 
	$amazon_api_status = reviewengine_amazon_api_status();

	if( !$amazon_api_status ) :
		
		echo sprintf( '<div class="alert alert-danger">Amazon API credentials are not connected. Click <a href="%s" target="_top">here</a> to configure.</div>', admin_url('edit.php?post_type=ree_product&page=reviewengine-settings#ree-amazon') );

	else :
	?>		
		<div id="network">
			<div class="form-group">
				<label for="affiliate-network"><?php _e('Affiliate Network'); ?></label>
				<select name="affiliate-network" id="affiliate-network" class="form-control">
					<option value="amazon"><?php _e('Amazon Associate Program'); ?></option>
				</select>
			</div><!-- .form-group -->
		</div><!-- .reviewengine-modal-state -->

		<div id="affiliate-amazon">
			<div id="search">
				<form action="#" method="get" id="search-form" class="row">
					<div class="col-md-6">
						<?php
						$locale = reviewengine_get_setting( 'default_search_locale' );
						$locales = reviewengine_my_locales();
						echo '<select name="search-locale" id="search-locale" class="form-control">';
						echo '<option value="">Search Location</option>';
						foreach( $locales as $key => $value ) {
							echo '<option value="' .$key. '" ' .selected( $locale, $key, false ). '>' .$value. '</option>';
						}
						echo '</select>';
						?>
					</div>
					<div class="col-md-6">
						<div class="search-keyword">
							<input type="text" placeholder="<?php _e('Search Keywords or ASIN'); ?>" name="search-keyword" class="form-control">
							<button type="submit">
								<i class="fa fa-search" aria-hidden="true"></i>
							</button>
						</div>
					</div>
				</form><!-- .row -->
				<div id="search-results" style="display: none;">
					<table class="table">
						<thead>
							<tr>
								<th colspan="2" class="column-product"><?php _e('Product'); ?></th>
								<th class="column-display" style="width: 30%"><?php _e('Display'); ?></th>
							</tr>
						</thead>
						<tbody></tbody>
						<tfoot>
							<tr>
								<th colspan="2" class="column-product"><?php _e('Product'); ?></th>
								<th class="column-display" style="width: 30%"><?php _e('Display'); ?></th>
							</tr>
						</tfoot>
					</table><!-- .table -->
					<button type="button" class="btn btn-primary button-more" style="display: none;">
						<span class="text"><?php _e('Load More Products'); ?></span>
					</button>
				</div><!-- #search-results -->
			</div><!-- #search -->
			<?php do_action( 'reviewengine_display_content' ); ?>
		</div><!-- #affiliate-amazon -->
	<?php endif; ?>
	</div><!-- .wrap -->

	<script type="text/html" id="template-search-result">
		<tr data-asin="{{asin}}">
			<td class="column-photo"><img src="{{photo}}"/></td>
			<td class="column-product">
				<p><a href="{{url}}" target="_blank"><strong>{{name}}</strong></a></p>
				<div class="product-info">
					<p><strong>{{price}}</strong></p>
					<span class="rating"><i style="width:{{percent_rating}}%"></i></span>
					<span>({{total_reviews}})</span>
				</div>
			</td>
			<td class="column-display" style="width: 30%"><?php echo apply_filters( 'reviewengine_display_buttons', '' ); ?></td>
		</tr>
	</script>
</body>
</html>