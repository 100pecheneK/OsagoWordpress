<input type="hidden" name="<?php echo $name; ?>[type]" value="<?php echo $options['type']; ?>"/>
		<select name="<?php echo $name; ?>[operator]">
			<option value="is_equal" <?php selected( 'is_equal', $operator ); ?>><?php _e( 'equal', 'advanced-ads' ); ?></option>
			<option value="is_higher" <?php selected( 'is_higher', $operator ); ?>><?php _e( 'equal or higher', 'advanced-ads' ); ?></option>
			<option value="is_lower" <?php selected( 'is_lower', $operator ); ?>><?php _e( 'equal or lower', 'advanced-ads' ); ?></option>
		</select><input type="number" name="<?php echo $name; ?>[value]" value="<?php echo absint( $value ); ?>"/>
		<p class="description"><?php echo $type_options[ $options['type'] ]['description']; ?></p>