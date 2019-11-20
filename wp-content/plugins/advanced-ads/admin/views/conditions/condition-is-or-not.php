<input type="hidden" name="<?php echo $name; ?>[type]" value="<?php echo $options['type']; ?>"/>
<select name="<?php echo $name; ?>[operator]">
	<option value="is" <?php selected( 'is', $operator ); ?>><?php _e( 'is', 'advanced-ads' ); ?></option>
	<option value="is_not" <?php selected( 'is_not', $operator ); ?>><?php _e( 'is not', 'advanced-ads' ); ?></option>
</select>
<p class="description"><?php echo $type_options[ $options['type'] ]['description'];
	if ( isset( $type_options[ $options['type'] ]['helplink'] ) ) : ?>
		<a href="<?php echo $type_options[ $options['type'] ]['helplink']; ?>" target="_blank"><?php
		_e( 'Manual and Troubleshooting', 'advanced-ads' );
		?></a><?php endif; ?></p>