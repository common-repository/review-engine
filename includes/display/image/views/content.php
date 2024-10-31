<div id="display-image" style="display: none;">
	<h3><?php _e('Image Options'); ?></h3>
	<form action="#" method="get" id="display-image-form">
		<div class="form-group row">
			<label for="product" class="col-sm-2 col-form-label"><?php _e('Product'); ?></label>
			<div class="col-sm-10">
				<a href="#" target="_blank" class="name"></a>
			</div>
		</div><!-- .form-group -->

		<div class="form-group form-group-tracking-id row">
			<label for="tracking-id" class="col-sm-2 col-form-label"><?php _e('Tracking ID'); ?></label>
			<div class="col-sm-10">
				<select name="tracking-id" id="tracking-id" class="form-control">
					<option value=""><?php _e('None'); ?></option>
				</select>
			</div>
		</div><!-- .form-group -->

		<div class="form-group form-group-image">
			<label><?php _e('Select an image'); ?></label>
			<div class="checkbox-images">

			</div>
		</div><!-- .form-group -->

		<div class="form-group form-group-alt row">
			<label for="alt" class="col-sm-2 col-form-label"><?php _e('Alt Text'); ?></label>
			<div class="col-sm-10">
				<input type="text" name="alt" id="alt" class="form-control">
			</div>
		</div><!-- .form-group -->

		<div class="form-group form-group-align row">
			<label for="align" class="col-sm-2 col-form-label"><?php _e('Align'); ?></label>
			<div class="col-sm-10">
				<select name="align" id="align" class="form-control">
					<option value="alignleft"><?php _e('Left'); ?></option>
					<option value="aligncenter"><?php _e('Center'); ?></option>
					<option value="alignright"><?php _e('Right'); ?></option>
					<option value="alignnone" selected=""><?php _e('None'); ?></option>
				</select>
			</div>
		</div><!-- .form-group -->

		<div class="form-group form-group-alt row">
			<label for="image-width" class="col-sm-2 col-form-label"><?php _e('Image Width'); ?></label>
			<div class="col-sm-10">
				<div class="input-group">
					<input type="number" name="image-width" id="image-width" min="10" max="1000" class="form-control">
					<div class="input-group-prepend">
						<span class="input-group-text"><?php _e('px'); ?></span>
					</div>
				</div>
			</div>
		</div><!-- .form-group -->

		<button type="submit" class="btn btn-primary button-insert-image"><?php _e('Insert Image'); ?></button>
		<button type="button" class="btn btn-default button-display-cancel"><?php _e('Cancel'); ?></button>
	</form>
	<script type="text/html" id="template-display-image">
		<div class="checkbox">
			<label>
				<input type="radio" name="image" value="{{image}}"><i class="fa fa-check"></i>
				<img src="{{image}}">
				<span>{{width}} x {{height}}</span>
			</label>
		</div>
	</script>
</div><!-- #display-image -->