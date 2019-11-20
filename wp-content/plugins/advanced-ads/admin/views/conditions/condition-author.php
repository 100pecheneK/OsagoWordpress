<div class="advads-conditions-single advads-buttonset">
	<?php foreach ( $authors as $_author ) {
		// don’t use strict comparasion because $values contains strings.
		if ( in_array( $_author->ID, $values ) ) {
			$_val = 1;
		} else {
			$_val = 0;
		}
		$author_name = $_author->display_name;
		?><label class="button ui-button"
				 for="advads-conditions-<?php echo absint( $index ); ?>-<?php echo absint( $_author->ID ); ?>">
		<?php echo esc_attr( $author_name ); ?>
		</label><input type="checkbox"
					   id="advads-conditions-<?php echo absint( $index ); ?>-<?php echo absint( $_author->ID ); ?>"
					   name="<?php echo $name; ?>[value][]" <?php checked( $_val, 1 ); ?>
					   value="<?php echo absint( $_author->ID ); ?>"><?php
	}
	?>
	<p class="advads-conditions-not-selected advads-error-message"><?php esc_html_x( 'Please select some items.', 'Error message shown when no display condition term is selected', 'advanced-ads' ); ?></p>
</div>
<?php
if ( count( $authors ) >= $max_authors ) :
	?>
	<p class="advads-error-message">
		<?php
		printf(  // translators: %d is the number of elements in the list and %s a URL.
			__( 'Only %d elements are displayed above. Use the <code>advanced-ads-admin-max-terms</code> filter to change this limit according to <a href="%s" target="_blank">this page</a>.', 'advanced-ads' ),
			absint( $max_authors ),
			esc_url( ADVADS_URL . 'codex/filter-hooks//#utm_source=advanced-ads&utm_medium=link&utm_campaign=author-term-limit' )
		);
		?>
	</p>
<?php
endif;
