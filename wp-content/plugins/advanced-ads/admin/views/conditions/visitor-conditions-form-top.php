<?php $empty_options = ( ! is_array( $set_conditions ) || ! count( $set_conditions ) );
if ( $empty_options ) :
	?>
	<p><?php _e( 'Visitor conditions limit the number of users who can see your ad. There is no need to set visitor conditions if you want all users to see the ad.', 'advanced-ads' ); ?></p>
<?php
elseif ( Advanced_Ads_Checks::cache() && ! defined( 'AAP_VERSION' ) ) :
	?>
	<p>
		<span class="advads-error-message"><?php _e( 'It seems that a caching plugin is activated.', 'advanced-ads' ); ?></span>&nbsp;
		<?php
		printf( __( 'Check out cache-busting in <a href="%s" target="_blank">Advanced Ads Pro</a> if dynamic features get cached.', 'advanced-ads' ), ADVADS_URL . 'add-ons/advanced-ads-pro/#utm_source=advanced-ads&utm_medium=link&utm_campaign=edit-visitor' );
		?>
	</p>
<?php
endif;