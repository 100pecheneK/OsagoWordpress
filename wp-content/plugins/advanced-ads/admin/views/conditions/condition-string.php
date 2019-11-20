<input type="hidden" name="<?php echo $name; ?>[type]" value="<?php echo $options['type']; ?>"/>
		<div class="advads-condition-line-wrap">
			<?php include( ADVADS_BASE_PATH . 'admin/views/ad-conditions-string-operators.php' ); ?>
			<input type="text" name="<?php echo $name; ?>[value]" value="<?php echo $value; ?>"/>
		</div>
		<p class="description"><?php echo $type_options[ $options['type'] ]['description']; ?></p>
