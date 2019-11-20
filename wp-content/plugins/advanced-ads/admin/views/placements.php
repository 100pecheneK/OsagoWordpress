<?php
/**
 * The view for the placements page
 */
?><div class="wrap">
<?php
if ( isset( $_GET['message'] ) ) :
	if ( $_GET['message'] === 'error' ) :
		?>
	<div id="message" class="error"><p><?php _e( 'Couldn’t create the new placement. Please check your form field and whether the name is already in use.', 'advanced-ads' ); ?></p></div>
		<?php
	elseif ( $_GET['message'] === 'updated' ) :
		?>
	<div id="message" class="updated"><p><?php _e( 'Placements updated', 'advanced-ads' ); ?></p></div>
		<?php
	endif;
	?>
<?php endif; ?>
	<h1 class="wp-heading-inline"><?php echo esc_html( get_admin_page_title() ); ?></h1>
	<a href="#" class="page-title-action" title="<?php _e( 'Create a new placement', 'advanced-ads' ); ?>" class="button-secondary" onclick="advads_toggle('.advads-placements-new-form'); advads_scroll_to_element('#advads-placements-new-form');"><?php
		_e( 'New Placement', 'advanced-ads' );
		?></a>

	<hr class="wp-header-end">

	<p class="description"><?php _e( 'Placements are physically places in your theme and posts. You can use them if you plan to change ads and ad groups on the same place without the need to change your templates.', 'advanced-ads' ); ?></p>
	<p class="description"><?php printf( __( 'See also the manual for more information on <a href="%s">placements</a>.', 'advanced-ads' ), ADVADS_URL . 'manual/placements/#utm_source=advanced-ads&utm_medium=link&utm_campaign=placements' ); ?></p>
<?php

// add placement form.
include_once ADVADS_BASE_PATH . 'admin/views/placement-form.php';

if ( isset( $placements ) && is_array( $placements ) && count( $placements ) ) :
	do_action( 'advanced-ads-placements-list-before', $placements );
	?>
		<h2><?php _e( 'Placements', 'advanced-ads' ); ?></h2>
		<form method="POST" action="" id="advanced-ads-placements-form">
			<table class="widefat advads-placements-table striped">
				<thead>
					<tr>
						<th><?php _e( 'Type', 'advanced-ads' ); ?></th>
						<th><?php _e( 'Name', 'advanced-ads' ); ?></th>
						<th><?php _e( 'Options', 'advanced-ads' ); ?></th>
						<?php do_action( 'advanced-ads-placements-list-column-header' ); ?>
						<th></th>
					</tr>
				</thead>
				<tbody>
	<?php
	// order by slug
	ksort( $placements );
	foreach ( $placements as $_placement_slug => $_placement ) :
			$type_missing = false;
		if ( isset( $_placement['type'] ) && ! isset( $placement_types[ $_placement['type'] ] ) ) {
			$missed_type        = $_placement['type'];
			$_placement['type'] = 'default';
			$type_missing       = true;
		}
		?>
		<tr id="single-placement-<?php echo $_placement_slug; ?>" class="advanced-ads-placement-row">
							<td>
							<?php
							if ( $type_missing ) :  // type is not given
								?>
					<p class="advads-error-message"><?php printf( __( 'Placement type "%s" is missing and was reset to "default".<br/>Please check if the responsible add-on is activated.', 'advanced-ads' ), $missed_type ); ?></p>
								<?php
				elseif ( isset( $_placement['type'] ) ) :
					if ( isset( $placement_types[ $_placement['type'] ]['image'] ) ) :
						?>
						<img src="
						<?php
						echo $placement_types[ $_placement['type'] ]['image'];
						?>
						" title="
						<?php
						echo $placement_types[ $_placement['type'] ]['title'];
						?>
						" alt="<?php echo $placement_types[ $_placement['type'] ]['title']; ?>"/>
						<?php
					else :
						echo $placement_types[ $_placement['type'] ]['title'];
					endif;
				else :
					__( 'default', 'advanced-ads' );
				endif;
				?>
				</td>
							<td><?php echo $_placement['name']; ?><br/>
				<?php
				if ( ! isset( $_placement['type'] ) || 'default' === $_placement['type'] ) :
					$_placement['type'] = 'default';
					?>
					<a class="usage-link"><?php _e( 'show usage', 'advanced-ads' ); ?></a>
					<?php
				 endif;
				?>
				</td>
							<td class="advads-placements-table-options">
				<input type="hidden" class="advads-placement-slug" value="<?php echo $_placement_slug; ?>"/>
				<?php if ( ! isset( $_placement['type'] ) || 'default' === $_placement['type'] ) : ?>
				<div class="hidden advads-usage">
					<label><?php _e( 'shortcode', 'advanced-ads' ); ?>
					<code><input type="text" onclick="this.select();" value='[the_ad_placement id="<?php echo $_placement_slug; ?>"]'/></code>
					</label>
					<label><?php _e( 'template (PHP)', 'advanced-ads' ); ?>
					<code><input type="text" onclick="this.select();" value="if( function_exists('the_ad_placement') ) { the_ad_placement('<?php echo $_placement_slug; ?>'); }"/></code>
					</label>
				</div>
				<?php endif; ?>
				
								<?php do_action( 'advanced-ads-placement-options-before', $_placement_slug, $_placement ); ?>
				
				<?php
				ob_start();
				include ADVADS_BASE_PATH . 'admin/views/placements-item.php';
				$item_option_content = ob_get_clean();

				Advanced_Ads_Admin_Options::render_option(
					'placement-item',
					__( 'Item', 'advanced-ads' ),
					$item_option_content
				);
				?>
								<?php
								switch ( $_placement['type'] ) :
									case 'post_content':
										ob_start();
										include ADVADS_BASE_PATH . 'admin/views/placements-content-index.php';
										do_action( 'advanced-ads-placement-post-content-position', $_placement_slug, $_placement );
										$option_content = ob_get_clean();

										Advanced_Ads_Admin_Options::render_option(
											'placement-content-injection-index',
											__( 'position', 'advanced-ads' ),
											$option_content
										);

										if ( ! function_exists( 'mb_convert_encoding' ) ) :
											?>
					<p><span class="advads-error-message"><?php _e( 'Important Notice', 'advanced-ads' ); ?>: </span><?php _e( 'Your server is missing an extension. This might break the content injection.<br/>Ignore this warning if everything works fine or else ask your hosting provider to enable <em>mbstring</em>.', 'advanced-ads' ); ?></p>
											<?php
					   endif;
										if ( ! extension_loaded( 'dom' ) ) :
										?>
					<p><span class="advads-error-message"><?php _e( 'Important Notice', 'advanced-ads' ); ?>: </span><?php printf( __( 'Missing PHP extensions could cause issues. Please ask your hosting provider to enable them: %s', 'advanced-ads' ), 'dom (php_xml)' ); ?></p>
<?php
endif;
										break;
								endswitch;
								do_action( 'advanced-ads-placement-options-after', $_placement_slug, $_placement );
								ob_start();

								if ( 'header' !== $_placement['type'] ) :
									$_label    = isset( $_placement['options']['ad_label'] ) ? $_placement['options']['ad_label'] : 'default';
									$_position = ! empty( $_placement['options']['placement_position'] ) ? $_placement['options']['placement_position'] : 'default';
									$_clearfix = ! empty( $_placement['options']['placement_clearfix'] );

									ob_start();
									include ADVADS_BASE_PATH . 'admin/views/placements-ad-label.php';
									if ( ! empty( $placement_types[ $_placement['type'] ]['options']['show_position'] ) ) :
										include ADVADS_BASE_PATH . 'admin/views/placements-ad-label-position.php';
									endif;
									$option_content = ob_get_clean();

									Advanced_Ads_Admin_Options::render_option(
										'placement-ad-label',
										__( 'ad label', 'advanced-ads' ),
										$option_content
									);

					endif;

								do_action( 'advanced-ads-placement-options-after-advanced', $_placement_slug, $_placement );
								$advanced_options = ob_get_clean();
								if ( $advanced_options ) :
									?>
				<a class="advads-toggle-link advads-placement-options-link" data-placement="<?php echo $_placement_slug ?>"><?php _e( 'show all options', 'advanced-ads' ); ?></a>
									<?php
												$hidden = ( isset( $_POST['advads-last-edited-placement'] ) && $_placement_slug == $_POST['advads-last-edited-placement'] ) ? '' : ' hidden';
									?>
				<div class="advads-placements-advanced-options advads-placements-advanced-options-
									<?php
									echo $_placement_slug;
									echo $hidden;
									?>
				">
																											 <?php
																												echo $advanced_options;
																												?>
				</div>
												<?php
				endif;
								// information after options
								if ( isset( $_placement['type'] ) && 'header' === $_placement['type'] ) :
									?>
				<br/><p><?php printf( __( 'Tutorial: <a href="%s" target="_blank">How to place visible ads in the header of your website</a>.', 'advanced-ads' ), ADVADS_URL . 'place-ads-in-website-header/#utm_source=advanced-ads&utm_medium=link&utm_campaign=header-ad-tutorial' ); ?></p>
									<?php
				endif;

								?>
							</td>
							<?php do_action( 'advanced-ads-placements-list-column', $_placement_slug, $_placement ); ?>
							<td>
								<input type="checkbox" id="advads-placements-item-delete-<?php echo $_placement_slug; ?>" name="advads[placements][<?php echo $_placement_slug; ?>][delete]" value="1"/>
								<label for="advads-placements-item-delete-<?php echo $_placement_slug; ?>"><?php _ex( 'delete', 'checkbox to remove placement', 'advanced-ads' ); ?></label>
							</td>
						</tr>
	<?php endforeach; ?>
				</tbody>
			</table>
			<div class="tablenav bottom">
			<input type="submit" id="advads-save-placements-button" class="button button-primary" value="<?php _e( 'Save Placements', 'advanced-ads' ); ?>"/>
		<?php wp_nonce_field( 'advads-placement', 'advads_placement', true ); ?>
		<button type="button" title="<?php _e( 'Create a new placement', 'advanced-ads' ); ?>" class="button-secondary" onclick="advads_toggle('.advads-placements-new-form'); advads_scroll_to_element('#advads-placements-new-form');">
											   <?php
												_e( 'New Placement', 'advanced-ads' );
												?>
		</button>
		<?php do_action( 'advanced-ads-placements-list-buttons', $placements ); ?>
		</div>
		<input type="hidden" name="advads-last-edited-placement" id="advads-last-edited-placement" value="0"/>
		</form>
	<?php
	do_action( 'advanced-ads-placements-list-after', $placements );
endif;

?></div>
