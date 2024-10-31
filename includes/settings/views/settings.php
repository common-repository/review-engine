<div class="wrap" id="reviewengine-settings-wrap">
	<h1><span class="reviewengine-title-wrapper"><?php _e('Review Engine'); ?></span></h1>

	<?php settings_errors(); ?>
	
	<?php
	$default_tab = 'general';
	$api_error = reviewengine_get_setting('api_error');

	if( !empty( $api_error ) ) {
		$default_tab = 'amazon';

		echo '<div class="error notice is-dismissible">';
		echo '<p>' .$api_error. '</p>';
		echo '<button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>';
		echo '</div>';
	}
	?>

	<form action="options.php" method="post">
		<?php do_action('reviewengine_before_settings_tabs'); ?>
		
		<?php
		$settings_tabs = apply_filters( 'reviewengine_settings_tabs', array(
			'general' => array(
				'icon' => 'fa-cog',
				'name' => __('General')
			),
			'amazon' => array(
				'icon' => 'fa-amazon',
				'name' => __('Amazon API')
			)
		) );

		echo '<h2 class="ree-nav-tab nav-tab-wrapper wp-clearfix">';
		foreach( $settings_tabs as $tab_slug => $tab ) {
			
			$tab_class = '';
			if( $tab_slug == $default_tab ) {
				$tab_class = 'nav-tab-active';
			}
			echo '<a href="#ree-' .$tab_slug. '" class="nav-tab ' .$tab_class. '"><i class="fa ' .$tab['icon']. '" aria-hidden="true"></i> ' .$tab['name']. '</a>';

		}
		echo '</h2>';

		echo '<div class="ree-tab-content">';
		foreach( $settings_tabs as $tab_slug => $tab ) {

			$tab_class = '';
			if( $tab_slug == $default_tab ) {
				$tab_class = 'active';
			}
			echo '<div id="ree-' .$tab_slug. '" class="ree-tab-pane ' .$tab_class. '">';
			do_action('reviewengine_settings_tab_' . $tab_slug . '_content_before');
			do_action('reviewengine_settings_tab_' . $tab_slug . '_content');
			do_action('reviewengine_settings_tab_' . $tab_slug . '_content_after');
			echo '</div>';

		}
		echo '</div><!-- .ree-tab-content -->';
		?>

		<?php do_action('reviewengine_settings_before_save_button'); ?>

		<p class="submit submit-reviewengine-settings">
			<?php settings_fields(REE_SETTINGS_PAGE); ?>
			<input type="submit" class="button button-primary" value="<?php _e('Save Changes'); ?>" />
		</p>

		<?php do_action('reviewengine_settings_after_save_button'); ?>
	</form>
</div>