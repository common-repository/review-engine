<div id="display-link" style="display: none;">
	<h3><?php _e('Link Options'); ?></h3>
	<form action="#" method="get" id="display-link-form">
		<div class="form-group row">
			<label for="product" class="col-sm-2 col-form-label"><?php _e('Product'); ?></label>
			<div class="col-sm-10">
				<a href="#" target="_blank" class="name"></a>
			</div>
		</div>

		<div class="form-group form-group-anchor row">
			<label for="anchor-text" class="col-sm-2 col-form-label"><?php _e('Anchor Text'); ?></label>
			<div class="col-sm-10">
				<input type="text" name="anchor-text" id="anchor-text" class="form-control">
			</div>
		</div>

		<div class="form-group form-group-tracking-id row">
			<label for="tracking-id" class="col-sm-2 col-form-label"><?php _e('Tracking ID'); ?></label>
			<div class="col-sm-10">
				<select name="tracking-id" id="tracking-id" class="form-control">
					<option value=""><?php _e('None'); ?></option>
				</select>
			</div>
		</div>
		<button type="submit" class="btn btn-primary button-insert-link"><?php _e('Insert Link'); ?></button>
		<button type="button" class="btn btn-default button-display-cancel"><?php _e('Cancel'); ?></button>
	</form>
</div><!-- #display-link -->