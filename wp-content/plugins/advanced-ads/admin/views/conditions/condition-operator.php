<select name="<?php echo $name; ?>[operator]">
	<option value="is" <?php selected( 'is', $operator ); ?>><?php esc_attr_e( 'is', 'advanced-ads' ); ?></option>
	<option value="is_not" <?php selected( 'is_not', $operator ); ?>><?php esc_attr_e( 'is not', 'advanced-ads' ); ?></option>
</select>