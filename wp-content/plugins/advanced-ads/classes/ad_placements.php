<?php

/**
 * Advanced Ads
 *
 * @package   Advanced_Ads_Placements
 * @author    Thomas Maier <thomas.maier@webgilde.com>
 * @license   GPL-2.0+
 * @link      http://webgilde.com
 * @copyright 2014 Thomas Maier, webgilde GmbH
 */

/**
 * grouping placements functions
 *
 * @since 1.1.0
 * @package Advanced_Ads_Placements
 * @author  Thomas Maier <thomas.maier@webgilde.com>
 */
class Advanced_Ads_Placements {
	private static $ads_for_placeholders = array();
	// temporarily change content during processing
	private static $replacements = array(
		'gcse:search' => 'gcse__search', // Google custom search namespaced tags.
	);

	/**
	 * get placement types
	 *
	 * @since 1.2.1
	 * @return arr $types array with placement types
	 */
	public static function get_placement_types() {
		$types = array(
			'default' => array(
				'title' => __( 'Manual Placement', 'advanced-ads' ),
				'description' => __( 'Manual placement to use as function or shortcode.', 'advanced-ads' ),
				'image' => ADVADS_BASE_URL . 'admin/assets/img/placements/manual.png',
				'options' => array( 'show_position' => true, 'show_lazy_load' => true, 'amp' => true )
				),
			'header' => array(
				'title' => __( 'Header Code', 'advanced-ads' ),
				'description' => __( 'Injected in Header (before closing &lt;/head&gt; Tag, often not visible).', 'advanced-ads' ),
				'image' => ADVADS_BASE_URL . 'admin/assets/img/placements/header.png'
				),
			'footer' => array(
				'title' => __( 'Footer Code', 'advanced-ads' ),
				'description' => __( 'Injected in Footer (before closing &lt;/body&gt; Tag).', 'advanced-ads' ),
				'image' => ADVADS_BASE_URL . 'admin/assets/img/placements/footer.png',
				'options' => array( 'amp' => true )
				),
			'post_top' => array(
				'title' => __( 'Before Content', 'advanced-ads' ),
				'description' => __( 'Injected before the post content.', 'advanced-ads' ),
				'image' => ADVADS_BASE_URL . 'admin/assets/img/placements/content-before.png',
				'options' => array( 'show_position' => true, 'show_lazy_load' => true, 'uses_the_content' => true, 'amp' => true )
				),
			'post_bottom' => array(
				'title' => __( 'After Content', 'advanced-ads' ),
				'description' => __( 'Injected after the post content.', 'advanced-ads' ),
				'image' => ADVADS_BASE_URL . 'admin/assets/img/placements/content-after.png',
				'options' => array( 'show_position' => true, 'show_lazy_load' => true, 'uses_the_content' => true, 'amp' => true )
				),
			'post_content' => array(
				'title' => __( 'Content', 'advanced-ads' ),
				'description' => __( 'Injected into the content. You can choose the paragraph after which the ad content is displayed.', 'advanced-ads' ),
				'image' => ADVADS_BASE_URL . 'admin/assets/img/placements/content-within.png',
				'options' => array( 'show_position' => true, 'show_lazy_load' => true, 'uses_the_content' => true, 'amp' => true )
				),
			'sidebar_widget' => array(
				'title' => __( 'Sidebar Widget', 'advanced-ads' ),
				'description' => __( 'Create a sidebar widget with an ad. Can be placed and used like any other widget.', 'advanced-ads' ),
				'image' => ADVADS_BASE_URL . 'admin/assets/img/placements/widget.png',
				'options' => array( 'show_lazy_load' => true, 'amp' => true )
				),
		);
		return apply_filters( 'advanced-ads-placement-types', $types );
	}

	/**
	* update placements if sent
	 *
	 * @since 1.5.2
	*/
	static function update_placements(){

		// check user permissions
		if( ! current_user_can( Advanced_Ads_Plugin::user_cap( 'advanced_ads_manage_placements') ) ) {
			return;
		}
		
		$success = null;
		
		// add hook of last opened placement settings to URL
		$hook = !empty( $_POST['advads-last-edited-placement'] ) ? '#single-placement-' . $_POST['advads-last-edited-placement'] : '';

		if ( isset($_POST['advads']['placement']) && check_admin_referer( 'advads-placement', 'advads_placement' ) ){
			$success = self::save_new_placement( $_POST['advads']['placement'] );
		}
		// save placement data
		if ( isset($_POST['advads']['placements']) && check_admin_referer( 'advads-placement', 'advads_placement' )){
			$success = self::save_placements( $_POST['advads']['placements'] );
		}

		$success = apply_filters( 'advanced-ads-update-placements', $success );

		if(isset($success)){
			$message = $success ? 'updated' : 'error';
			wp_redirect( esc_url_raw( add_query_arg(array('message' => $message) ) ) . $hook );
		}
	}

	/**
	 * save a new placement
	 *
	 * @since 1.1.0
	 * @param array $new_placement
	 * @return mixed slug if saved; false if not
	 */
	public static function save_new_placement($new_placement) {
		// load placements // -TODO use model
		$placements = Advanced_Ads::get_ad_placements_array();

		// create slug
		$new_placement['slug'] = sanitize_title( $new_placement['name'] );

		if ( isset( $placements[ $new_placement['slug'] ] ) ) {
			$i = 1;
			// try to save placement until we found an empty slug
			do {
				$i ++;
				if ( $i === 100 ) { // prevent endless loop, just in case
					Advanced_Ads::log( 'endless loop when injecting placement' );
					break;
				}
			} while ( isset( $placements[ $new_placement['slug'] . '_' . $i ] ) );

			$new_placement['slug'] .= '_' . $i;
			$new_placement['name'] .= ' ' . $i;
		}

		// check if slug already exists or is empty
		if ( $new_placement['slug'] === '' || isset( $placements[$new_placement['slug']]) || !isset( $new_placement['type'] ) ) {
			return false;
		}

		// make sure only allowed types are being saved
		$placement_types = Advanced_Ads_Placements::get_placement_types();
		$new_placement['type'] = (isset($placement_types[$new_placement['type']])) ? $new_placement['type'] : 'default';
		// escape name
		$new_placement['name'] = esc_attr( $new_placement['name'] );

		// add new place to all placements
		$placements[$new_placement['slug']] = array(
			'type' => $new_placement['type'],
			'name' => $new_placement['name'],
			'item' => $new_placement['item']
		);
		
		// add index options
		if ( isset($new_placement['options']) ){
			$placements[$new_placement['slug']]['options'] = $new_placement['options'];
			if ( isset($placements[$new_placement['slug']]['options']['index']) ) {
				$placements[$new_placement['slug']]['options']['index'] = absint( $placements[$new_placement['slug']]['options']['index'] ); }
		} 

		// save array
		Advanced_Ads::get_instance()->get_model()->update_ad_placements_array( $placements );

		return $new_placement['slug'];
	}

	/**
	 * save placements
	 *
	 * @since 1.1.0
	 * @param array $placement_items
	 * @return mixed true if saved; error message if not
	 */
	public static function save_placements($placement_items) {

		// load placements // -TODO use model
		$placements = Advanced_Ads::get_ad_placements_array();

		foreach ( $placement_items as $_placement_slug => $_placement ) {
			// remove the placement
			if ( isset($_placement['delete']) ) {
				unset($placements[$_placement_slug]);
				continue;
			}
			// save item
			if ( isset($_placement['item']) ) {
				$placements[$_placement_slug]['item'] = $_placement['item']; }
			// save item options
			if ( isset($_placement['options']) ){
				$placements[$_placement_slug]['options'] = $_placement['options'];
				if ( isset($placements[$_placement_slug]['options']['index']) ) {
					$placements[$_placement_slug]['options']['index'] = absint( $placements[$_placement_slug]['options']['index'] ); }
			} else {
				$placements[$_placement_slug]['options'] = array();
			}
		}

		// save array
		Advanced_Ads::get_instance()->get_model()->update_ad_placements_array( $placements );

		return true;
	}

	/**
	 * get items for item select field
	 *
	 * @since 1.1
	 * @return arr $select items for select field
	 */
	public static function items_for_select() {
		$select = array();
		$model = Advanced_Ads::get_instance()->get_model();

		// load all ad groups
		$groups = $model->get_ad_groups();
		foreach ( $groups as $_group ) {
			$select['groups']['group_' . $_group->term_id] = $_group->name;
		}

		// load all ads
		$ads = $model->get_ads( array('orderby' => 'title', 'order' => 'ASC') );
		foreach ( $ads as $_ad ) {
			$select['ads']['ad_' . $_ad->ID] = $_ad->post_title;
		}

		return $select;
	}

	/**
	 * get html tags for content injection
	 *
	 * @since 1.3.5
	 * @return arr $tags array with tags that can be used for content injection
	 */
	public static function tags_for_content_injection(){
		$tags = apply_filters( 'advanced-ads-tags-for-injection', array(
			'p' => sprintf( __( 'paragraph (%s)', 'advanced-ads' ), '&lt;p&gt;' ),
			'pwithoutimg' => sprintf( __( 'paragraph without image (%s)', 'advanced-ads' ), '&lt;p&gt;' ),
			'h2' => sprintf( __( 'headline 2 (%s)', 'advanced-ads' ), '&lt;h2&gt;' ),
			'h3' => sprintf( __( 'headline 3 (%s)', 'advanced-ads' ), '&lt;h3&gt;' ),
			'h4' => sprintf( __( 'headline 4 (%s)', 'advanced-ads' ), '&lt;h4&gt;' ),
		));

		return $tags;
	}

	/**
	 * return content of a placement
	 *
	 * @since 1.1.0
	 * @param string $id   slug of the display
	 * @param array  $args optional arguments (passed to child)
	 */
	public static function output( $id = '', $args = array() ) {
		// get placement data for the slug
		if ( $id == '' ) {
			return;
		}

		$placements = Advanced_Ads::get_ad_placements_array();
		$placement = ( isset( $placements[ $id ] ) && is_array( $placements[ $id ] ) ) ? $placements[ $id ] : array();

		if ( isset( $args['change-placement'] ) ) {
			// some options was provided by the user
			$placement = Advanced_Ads_Utils::merge_deep_array( array( $placement, $args['change-placement'] ) ) ;
		}

		if ( isset( $placement['item'] ) && $placement['item'] !== '' ) {
			$_item = explode( '_', $placement['item'] );

			if ( ! isset( $_item[1] ) || empty( $_item[1] ) ) {
				return ;
			}

			// inject options
			if ( isset( $placement['options'] ) && is_array( $placement['options'] ) ) {
				foreach ( $placement['options'] as $_k => $_v ) {
					if ( ! isset( $args[ $_k ] ) ) {
						$args[ $_k ] = $_v;
					}
				}
			}

			// inject placement type
			if ( isset( $placement['type'] ) ) {
				$args[ 'placement_type' ] = $placement['type'];
			}

			// options
			$prefix = Advanced_Ads_Plugin::get_instance()->get_frontend_prefix();

			// return either ad or group content
			switch ( $_item[0] ) {
				case 'ad':
				case Advanced_Ads_Select::AD :
					// create class from placement id (not if header injection)
					if ( ! isset( $placement['type'] ) || $placement['type'] !== 'header' ) {
						if ( ! isset( $args['output'] ) ) {
							$args['output'] = array();
						}
						if ( ! isset( $args['output']['class'] ) ) {
							$args['output']['class'] = array();
						}
						$class = $prefix . $id;
						if ( ! in_array( $class, $args['output']['class'] ) ) {
							$args['output']['class'][] = $class;
						}
					}

					// fix method id
					$_item[0] = Advanced_Ads_Select::AD;
					break;

				// avoid loops (programmatical error)
				case Advanced_Ads_Select::PLACEMENT :
					return;

				case Advanced_Ads_Select::GROUP :
				    $class = $prefix . $id;
				    if ( ( isset( $placement['type'] ) && $placement['type'] !== 'header' )
					    && ( !isset( $args['output']['class'] ) 
					    || !is_array( $args['output']['class'] ) 
					    || !in_array( $class, $args['output']['class'] ) ) ) {
					$args['output']['class'][] = $class;
				    }
				default:
			}

			// create placement id for various features
			$args['output']['placement_id'] = $id;

			// add the placement to the global output array
			$advads = Advanced_Ads::get_instance();
			$name = isset( $placement['name'] ) ? $placement['name'] : $id;

			if ( ! isset( $args['global_output'] ) || $args['global_output'] ) {
				$advads->current_ads[] = array( 'type' => 'placement', 'id' => $id, 'title' => $name );
			}

			$result = Advanced_Ads_Select::get_instance()->get_ad_by_method( (int) $_item[1], $_item[0], $args );

			if ( $result && ! empty( $args['placement_clearfix'] ) ) {
				$result .= '<br style="clear: both; display: block; float: none; color:blue;"/>';
			}

			return $result;
		}
	}

	/**
	 * inject ads directly into the content
	 *
	 * @since 1.2.1
	 * @param string $placement_id id of the placement
	 * @param arr $placement_opts placement options
	 * @param string $content
	 * @return type
	 * @link inspired by http://www.wpbeginner.com/wp-tutorials/how-to-insert-ads-within-your-post-content-in-wordpress/
	 */
	public static function &inject_in_content($placement_id, $placement_opts, &$content) {
		if ( ! extension_loaded( 'dom' ) ) {
			return $content;
		}

		// get plugin options
		$plugin_options = Advanced_Ads::get_instance()->options();
		
		$wpCharset = get_bloginfo('charset');
		// parse document as DOM (fragment - having only a part of an actual post given)

		$content_to_load = self::get_content_to_load( $content, $wpCharset );
		if ( ! $content_to_load ) {
			return $content;
		}

		$dom = new DOMDocument('1.0', $wpCharset);
		// may loose some fragments or add autop-like code
		libxml_use_internal_errors(true); // avoid notices and warnings - html is most likely malformed

		$success = $dom->loadHtml('<!DOCTYPE html><html><meta http-equiv="Content-Type" content="text/html; charset=' . $wpCharset . '" /><body>' . $content_to_load);
		libxml_use_internal_errors(false);
		if ($success !== true) {
			// -TODO handle cases were dom-parsing failed (at least inform user)
			return $content;
		}

		// parse arguments
		$tag = isset($placement_opts['tag']) ? $placement_opts['tag'] : 'p';
		$tag = preg_replace('/[^a-z0-9]/i', '', $tag); // simplify tag

		// allow more complex xPath expression
		$tag = apply_filters( 'advanced-ads-placement-content-injection-xpath', $tag, $placement_opts );

		if ( $tag === 'pwithoutimg' ) {
			$tag = 'p[not(descendant::img)]';
		}

		// select positions
		$xpath = new DOMXPath($dom);
		$items = $xpath->query('/html/body/' . $tag);

		$options = array(
		    'allowEmpty' => false,   // whether the tag can be empty to be counted
			'paragraph_select_from_bottom' => isset($placement_opts['start_from_bottom']) && $placement_opts['start_from_bottom'],
			// only has before and after
			'before' => isset($placement_opts['position']) && $placement_opts['position'] === 'before'
		);

		$options['paragraph_id'] = isset($placement_opts['index']) ? $placement_opts['index'] : 1;
		$options['paragraph_id'] = max( 1, (int) $options['paragraph_id'] );

		// if there are too few items at this level test nesting
		$options['itemLimit'] = $tag === 'p' ? 2 : 1;

		// trigger such a high item limit that all elements will be considered
		if( ! empty($plugin_options['content-injection-level-disabled'] ) ){
			$options['itemLimit'] = 1000;
		}
		
		// allow hooks to change some options
		$options = apply_filters(
			'advanced-ads-placement-content-injection-options',
			$options,
			$tag );

		if ($items->length < $options['itemLimit'] ) {
			$items = $xpath->query('/html/body/*/' . $tag);
		}
		// try third level
		if ( $items->length < $options['itemLimit'] ) {
			$items = $xpath->query('/html/body/*/*/' . $tag);
		}
		// try all levels as last resort
		if ( $items->length < $options['itemLimit'] ) {
			$items = $xpath->query( '//' . $tag );
		}
		
		// allow to select other elements
		$items = apply_filters( 'advanced-ads-placement-content-injection-items', $items, $xpath, $tag );

		// filter empty tags from items
		$whitespaces = json_decode('"\t\n\r \u00A0"');
		$paragraphs = array();
		foreach ($items as $item) {
			if ( $options['allowEmpty'] || ( isset($item->textContent) && trim($item->textContent, $whitespaces) !== '' ) ) {
				$paragraphs[] = $item;
			}
		}

		$options['paragraph_count'] = count($paragraphs);

		if ($options['paragraph_count'] >= $options['paragraph_id']) {
			$offset = $options['paragraph_select_from_bottom'] ? $options['paragraph_count'] - $options['paragraph_id'] : $options['paragraph_id'] - 1;
			$offsets = apply_filters( 'advanced-ads-placement-content-offsets', array( $offset ),  $options, $placement_opts );
			$did_inject = false;

			foreach ( $offsets as $offset ) {
				// inject
				$node = apply_filters( 'advanced-ads-placement-content-injection-node', $paragraphs[$offset], $tag, $options['before'] );

				$adContent = Advanced_Ads_Select::get_instance()->get_ad_by_method( $placement_id, 'placement', $placement_opts );

				if ( trim( $adContent, $whitespaces ) === '' ) {
					continue;
				}

				$adContent = self::filter_ad_content( $adContent, $node->tagName, $options );

				// convert HTML to XML!
				$adDom = new DOMDocument('1.0', $wpCharset);
				libxml_use_internal_errors(true);
				$adDom->loadHtml('<!DOCTYPE html><html><meta http-equiv="Content-Type" content="text/html; charset=' . $wpCharset . '" /><body>' . $adContent);
				// log errors
				if ( defined ( 'WP_DEBUG' ) && WP_DEBUG && current_user_can( 'advanced_ads_manage_options' ) ) {
					foreach( libxml_get_errors() as $_error ) {
						// continue, if there is '&' symbol, but not HTML entity
						if ( false === stripos( $_error->message, 'htmlParseEntityRef:' ) ) {
							Advanced_Ads::log( 'possible content injection error for placement "' . $placement_id . '": ' . print_r( $_error, true ) );
						}
					}
				}


				if ( $options['before'] ) {
					$refNode = $node;

					foreach ( $adDom->getElementsByTagName( 'body' )->item( 0 )->childNodes as $importedNode ) {
						$importedNode = $dom->importNode( $importedNode, true );
						$refNode->parentNode->insertBefore( $importedNode, $refNode );
					}
				} else {
					// append before next node or as last child to body
					$refNode = $node->nextSibling;
					if (isset($refNode)) {

						foreach ( $adDom->getElementsByTagName( 'body' )->item( 0 )->childNodes as $importedNode ) {
							$importedNode = $dom->importNode( $importedNode, true );
							$refNode->parentNode->insertBefore( $importedNode, $refNode );
						}

					} else {
						// append to body; -TODO using here that we only select direct children of the body tag
						foreach ( $adDom->getElementsByTagName( 'body' )->item( 0 )->childNodes as $importedNode ) {
							$importedNode = $dom->importNode( $importedNode, true );
							$node->parentNode->appendChild( $importedNode );
						}
					}
				}

				libxml_use_internal_errors(false);
				$did_inject = true;
			}

			if ( ! $did_inject ) {
				return $content;
			}

			$content_orig = $content;
			// convert to text-representation
			$content = $dom->saveHTML();
			$content = self::prepare_output( $content, $content_orig );

		/**
		 * show a warning to ad admins in the Ad Health bar in the frontend, when
		 * 
		 * * the level limitation was not disabled
		 * * could not inject one ad (as by use of `elseif` here)
		 * * but there are enough elements on the site, but just in sub-containers
		 * 
		 */
		} elseif( current_user_can( Advanced_Ads_Plugin::user_cap( 'advanced_ads_manage_options') )  
			&& empty($plugin_options['content-injection-level-disabled'] )  ) {
			
			// check if there are more elements without limitation 
			$all_items = $xpath->query( '//' . $tag );
			if( $options['paragraph_id'] <= $all_items->length ){
				// add a warning to ad health
				add_filter( 'advanced-ads-ad-health-nodes', array( 'Advanced_Ads_Placements', 'add_ad_health_node' ) );
			}
		}

		return $content;
	}

	/**
	 * Get content to load.
	 *
	 * @param string $content Original content.
	 * @param $wpCharset Blog charset.
	 * @return string $content Content to load.
	 */
	private static function get_content_to_load( $content, $wpCharset ) {
		$plugin_options = Advanced_Ads::get_instance()->options();

		if ( ! self::is_new_content_injection() ) {
			// -TODO may want to verify the wpcharset is supported by server (mb_list_encodings)
			// prevent messages from dom parser
			// check if mbstring exists
			if ( ! function_exists( 'mb_convert_encoding' ) ) {
				if ( $wpCharset === "UTF-8" ) {
					$content_to_load = htmlspecialchars_decode( htmlentities( $content, ENT_COMPAT, $wpCharset, false ) );
				} else {
					return;
				}
			} else {
				$content_to_load = mb_convert_encoding( $content, 'HTML-ENTITIES', $wpCharset );
			}

			$content_to_load = str_replace( array_keys( self::$replacements ), array_values( self::$replacements ), $content_to_load );
		} else {
			// Prevent removing closing tags in scripts.
			$content_to_load= preg_replace( '/<script.*?<\/script>/', '<!--\0-->', $content);
		}


		// check which priority the wpautop filter has; might have been disabled on purpose
		$wpautop_priority = has_filter( 'the_content', 'wpautop');
		if ( $wpautop_priority && Advanced_Ads_Plugin::get_instance()->get_content_injection_priority() < $wpautop_priority ) {
			$content_to_load = wpautop( $content_to_load );
		}

		return $content_to_load;
	}

	/**
	 * Filter ad content.
	 *
	 * @param string $adContent Ad content.
	 * @param string $tag_name tar before/after the content.
	 * @param array $options Injection options.
	 * @return string $adContent Ad content.
	 */
	private static function filter_ad_content( $ad_content, $tag_name, $options ) {
		$plugin_options = Advanced_Ads::get_instance()->options();

		if ( ! self::is_new_content_injection() ) {
			// replace `</` with `<\/` in ad content when placed within `document.write()` to prevent code from breaking
			// source for this regex: http://stackoverflow.com/questions/17852537/preg-replace-only-specific-part-of-string
			$ad_content = preg_replace('#(document.write.+)</(.*)#', '$1<\/$2', $ad_content); // escapes all closing html tags
			// $ad_content = preg_replace('#(document.write.+)</sc(.*)#', '$1<\/sc$2', $ad_content); // only escapes closing </script> tags
			// $ad_content = preg_replace('#(document.write[^<^)]+)</sc(.*)#', '$1<\/sc$2', $ad_content); // too restrict, doesn’t work when beginning <script> tag is in the same line
		} else {
			//Inject placeholder.
			$id = count( self::$ads_for_placeholders );
			self::$ads_for_placeholders[] = array(
				'id' => $id,
				'tag' => $tag_name,
				'type' => $options['before'] ? 'before' : 'after',
				'ad' => $ad_content
			);
			$ad_content = '%advads_placeholder_' . $id . '%';
		}

		return $ad_content;
	}

	/**
	 * Prepare output.
	 *
	 * @param string $content Modified content.
	 * @param string $content_orig Original content.
	 * @return string $content Content to output.
	 */
	private static function prepare_output( $content, $content_orig ) {
		$plugin_options = Advanced_Ads::get_instance()->options();
		if ( ! self::is_new_content_injection() ) {
			// remove head and tail (required for dom parser but unwanted for content)
			$content = substr($content, stripos($content, '<body>') + 6);
			$content = str_replace(array('</body>', '</html>'), '', $content);
			$content = str_replace( array_values( self::$replacements ), array_keys( self::$replacements ), $content );


			// no fall-back desired: if there are too few paragraphs do nothing

			// fix shortcode quotes (malformed by backend editor)
			$matches = array();
			if (0 < preg_match_all('/\[[^]]+\]/Siu', $content, $matches, PREG_OFFSET_CAPTURE) && isset($matches[0])) {
				foreach ($matches[0] as $match) {
					$offset = $match[1];
					$content = substr($content, 0, $offset) . str_replace(array('“', '″', '&#8220;', '&quote;', '&#8243;'), '"', $match[0]) . substr($content, $offset + strlen($match[0]));
				}
			}
		} else {
			$content = self::inject_ads( $content, $content_orig, self::$ads_for_placeholders );
			self::$ads_for_placeholders = array();
		}
		return $content;
	}

	/**
	 * Search for ad placeholders in the `$content` to determine positions at which to inject ads.
	 * Given the positions, inject ads into `$content_orig.
	 *
	 * @param string $content      Post content with injected ad placeholders.
	 * @param string $content_orig Unmodified post content.
	 * @param arrray $options      Injection options.
	 * @param arrray $ads_for_placeholders  Array of ads.
	 * Each ad contains placeholder id, before or after which tag to inject the ad, the ad content.
	 * @return string $content
	 */
	private static function inject_ads( $content, $content_orig, $ads_for_placeholders ) {
		$self_closing_tags = array( 'area', 'base', 'basefont', 'bgsound', 'br', 'col', 'embed', 'frame', 'hr', 'img', 'input', 'keygen', 'link', 'meta', 'param', 'source', 'track', 'wbr' );

		// It is not possible to append/prepend in self closing tags.
		foreach ( $ads_for_placeholders as &$ad_content ) {
			if ( ( 'prepend' === $ad_content['type'] || 'append' === $ad_content['type'] )
				&& in_array( $ad_content['tag'], $self_closing_tags, true ) ) {
				$ad_content['type'] = 'after';
			}
		}
		unset( $ad_content );
		usort( $ads_for_placeholders, array( 'Advanced_Ads_Placements', 'sort_ads_for_placehoders' ) );


		// Add tags before/after which ad placehoders were injected.
		foreach ( $ads_for_placeholders as $ad_content ) {
			$tag = $ad_content['tag'];

			switch( $ad_content['type'] ) {
			case 'before':
			case 'prepend':
				$alts[] = "<${tag}[^>]*>";
				break;
			case 'after':
				if ( in_array( $tag, $self_closing_tags, true ) ) {
					$alts[] = "<${tag}[^>]*>";
				} else {
					$alts[] = "</${tag}>";
				}
				break;
			case 'append':
				$alts[] = "</${tag}>";
				break;
			}

		}
		$alts = array_unique( $alts );
		$tag_regexp = implode( '|', $alts );
		// Add ad placeholder.
		$alts[] = '%advads_placeholder_(?:\d+)%';
		$tag_and_placeholder_regexp = implode( '|', $alts );

		preg_match_all( "#{$tag_and_placeholder_regexp}#i", $content, $tag_matches );
		$count = 0;

		// For each tag located before/after an ad placeholder, find its offset among the same tags.
		foreach ( $tag_matches[0] as $r ) {
			if ( preg_match( '/%advads_placeholder_(\d+)%/', $r, $result ) ) {
				$id = $result[1];
				$found_ad = false;
				foreach ( $ads_for_placeholders as $n => $ad ) {
					if ( (int) $ad['id'] === (int) $id ) {
						$found_ad = $ad;
						break;
					}
				}
				if ( ! $found_ad ) {
					continue;
				}

				switch( $found_ad['type'] ) {
					case 'before':
					case 'append':
						$ads_for_placeholders[ $n ]['offset'] = $count;
						break;
					case 'after':
					case 'prepend':
						$ads_for_placeholders[ $n ]['offset'] = $count -1;
						break;
				}

			} else {
				$count++;
			}
		}

		// Find tags before/after which we need to inject ads.
		preg_match_all( "#{$tag_regexp}#i", $content_orig, $orig_tag_matches, PREG_OFFSET_CAPTURE );
		$new_content = '';
		$pos = 0;

		foreach ( $orig_tag_matches[0] as $n => $r ) {
			$to_inject = array();
			// Check if we need to inject an ad at this offset.
			foreach ( $ads_for_placeholders as $ad ) {
				if ( isset( $ad['offset'] ) && $ad['offset'] === $n ) {
					$to_inject[] = $ad;
				}
			}

			foreach ( $to_inject as $item ) {
				switch( $item['type'] ) {
				case 'before':
				case 'append':
					$found_pos = $r[1];
					break;
				case 'after':
				case 'prepend':
					$found_pos = $r[1] + strlen( $r[0] );
					break;
				}

				$new_content .= substr( $content_orig, $pos, $found_pos - $pos );
				$pos = $found_pos;
				$new_content .= $item['ad'];
			}
		}
		$new_content .= substr( $content_orig, $pos );
		return $new_content;
	}


	/**
	 * Callback function for usort() to sort ads for placeholders.
	 *
	 * @param array $first  The first array to compare.
	 * @param array $second The second array to compare.
	 * @return int 0 if both objects equal. -1 if second array should come first, 1 otherwise.
	 */
	public static function sort_ads_for_placehoders( $first, $second ) {
		if ( $first['type'] === $second['type'] ) {
			return 0;
		}

		$num = array( 'before' => 1, 'prepend' => 2, 'append' => 3, 'after' => 4 );

		return $num[ $first['type'] ] > $num[ $second['type'] ] ? 1 : -1;
	}

	/**
	 * Add a warning to 'Ad health'.
	 *
	 * @param array $nodes.
	 * @return array $nodes.
	 */
	public static function add_ad_health_node( $nodes ) {
		$nodes[] = array( 'type' => 1, 'data' => array(
			'parent' => 'advanced_ads_ad_health',
			'id'    => 'advanced_ads_ad_health_the_content_not_enough_elements',
			'title' => sprintf(
				/* translators: %s stands for the name of the "Disable level limitation" option and automatically translated as well */
				__( 'Set <em>%s</em> to show more ads', 'advanced-ads' ), 
				__('Disable level limitation', 'advanced-ads' ) ),
			'href'  => admin_url( '/admin.php?page=advanced-ads-settings#top#general' ),
			'meta'   => array(
				'class' => 'advanced_ads_ad_health_warning',
				'target' => '_blank'
			)
		) );
		return $nodes;
	}

	/**
	 * check if the placement can be displayed
	 *
	 * @since 1.6.9
	 * @param int $id placement id
	 * @return bool true if placement can be displayed
	 */
	static function can_display( $id = 0 ){
		if ( ! isset($id) || $id === 0 ) {
			return true;
		}

		return apply_filters( 'advanced-ads-can-display-placement', true, $id );
	}

	/**
	 * Get the placements that includes the ad or group.
	 *
	 * @param string $type 'ad' or 'group'.
	 * @param int $id Id.
	 * @return array
	 */
	public static function get_placements_by( $type, $id ) {
		$result = array();

		$placements = Advanced_Ads::get_ad_placements_array();
		foreach ( $placements as $_id => $_placement ) {
			if ( isset( $_placement['item'] ) && $_placement['item'] === $type . '_' . $id ) {
				$result[ $_id ] = $_placement;
			}
		}

		return $result;
	}

	/**
	 * Check if new content injection logic should be used.
	 *
	 * @return bool
	 */
	public static function is_new_content_injection() {
		// Enabled for new users or those who enabled it.
		$plugin_options = Advanced_Ads::get_instance()->options();
		return ! $plugin_options || ! empty( $plugin_options['content-injection-new'] );
	}
}

