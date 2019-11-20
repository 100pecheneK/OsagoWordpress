<form method="POST" action="" onsubmit="return advads_validate_placement_form();" class="advads-placements-new-form" id="advads-placements-new-form"
	<?php
	if ( isset( $placements ) && count( $placements ) ) {
		echo ' style="display: none;"';
	}
	?>
>
	<h3>1. <?php _e( 'Choose a placement type', 'advanced-ads' ); ?></h3>
	<p class="description"><?php printf( __( 'Placement types define where the ad is going to be displayed. Learn more about the different types from the <a href="%s">manual</a>', 'advanced-ads' ), ADVADS_URL . 'manual/placements/#utm_source=advanced-ads&utm_medium=link&utm_campaign=placements' ); ?></p>
	<div class="advads-new-placement-types advads-buttonset">
		<?php
		if ( is_array( $placement_types ) ) {
			foreach ( $placement_types as $_key => $_place ) :
				if ( isset( $_place['image'] ) ) :
					$image = '<img src="' . $_place['image'] . '" alt="' . $_place['title'] . '"/>';
				else :
					$image = '<strong>' . $_place['title'] . '</strong><br/><p class="description">' . $_place['description'] . '</p>';
				endif;
				?>
				<div class="advads-placement-type"><label
							for="advads-placement-type-<?php echo $_key; ?>"><?php echo $image; ?></label>
					<input type="radio" id="advads-placement-type-<?php echo $_key; ?>" name="advads[placement][type]"
						   value="<?php echo $_key; ?>"/>
					<p class="advads-placement-description">
						<strong><?php echo $_place['title']; ?></strong><br/><?php echo $_place['description']; ?></p>
				</div>
			<?php
			endforeach;
		};
		?>
	</div>
	<div class="clear"></div>
	<p class="advads-error-message advads-placement-type-error"><?php _e( 'Please select a placement type.', 'advanced-ads' ); ?></p>
	<br/>
	<h3>2. <?php _e( 'Choose a Name', 'advanced-ads' ); ?></h3>
	<p class="description"><?php _e( 'The name of the placement is only visible to you. Tip: choose a descriptive one, e.g. <em>Below Post Headline</em>.', 'advanced-ads' ); ?></p>
	<p><input name="advads[placement][name]" class="advads-new-placement-name" type="text" value=""
			  placeholder="<?php _e( 'Placement Name', 'advanced-ads' ); ?>"/></p>
	<p class="advads-error-message advads-placement-name-error"><?php _e( 'Please enter a name for your placement.', 'advanced-ads' ); ?></p>
	<h3>3. <?php _e( 'Choose the Ad or Group', 'advanced-ads' ); ?></h3>
	<p class="description"><?php _e( 'The ad or group that should be displayed.', 'advanced-ads' ); ?></p>
	<p><select name="advads[placement][item]">
			<option value=""><?php _e( '--not selected--', 'advanced-ads' ); ?></option>
			<?php if ( isset( $items['groups'] ) ) : ?>
				<optgroup label="<?php _e( 'Ad Groups', 'advanced-ads' ); ?>">
					<?php foreach ( $items['groups'] as $_item_id => $_item_title ) : ?>
						<option value="<?php echo $_item_id; ?>"><?php echo $_item_title; ?></option>
					<?php endforeach; ?>
				</optgroup>
			<?php endif; ?>
			<?php if ( isset( $items['ads'] ) ) : ?>
				<optgroup label="<?php _e( 'Ads', 'advanced-ads' ); ?>">
					<?php foreach ( $items['ads'] as $_item_id => $_item_title ) : ?>
						<option value="<?php echo $_item_id; ?>"><?php echo $_item_title; ?></option>
					<?php endforeach; ?>
				</optgroup>
			<?php endif; ?>
		</select></p>
	<?php wp_nonce_field( 'advads-placement', 'advads_placement', true ); ?>
	<input type="submit" class="button button-primary" value="<?php _e( 'Save New Placement', 'advanced-ads' ); ?>"/>
</form>
