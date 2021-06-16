<?php
/**
 * CAWeb Navigation Menu Class
 *
 * @see https://developer.wordpress.org/reference/classes/walker_nav_menu/
 * @see https://core.trac.wordpress.org/browser/tags/5.3/src/wp-includes/class-walker-nav-menu.php
 * @package CAWeb
 */

if ( ! class_exists( 'CAWeb_Nav_Menu' ) ) {
	/**
	 * Core class used to implement an HTML list of nav menu items.
	 *
	 * @link https://developer.wordpress.org/reference/classes/walker_nav_menu/
	 */
	class CAWeb_Nav_Menu extends Walker_Nav_Menu {

		/**
		 * Attach Filters/Hooks
		 *
		 * @return void
		 */
		public function __construct() {
			/* Hooked onto the WordPress Navigation Walker Edit */
			add_filter( 'wp_edit_nav_menu_walker', array( $this, 'caweb_edit_nav_menu_walker' ), 9999 );
			add_action( 'wp_nav_menu_item_custom_fields', array( $this, 'caweb_nav_menu_item_custom_fields' ), 9, 4 );
			add_action( 'wp_update_nav_menu_item', array( $this, 'caweb_update_nav_menu_item' ), 10, 3 );

			/* Hooked onto the WordPress Navigation */
			add_filter( 'wp_nav_menu_args', array( $this, 'caweb_nav_menu_args' ) );
			/* https://core.trac.wordpress.org/browser/tags/4.8/src/wp-includes/widgets/class-wp-nav-menu-widget.php#L17 */
			add_filter( 'widget_nav_menu_args', array( $this, 'caweb_widget_nav_menu_args' ), 10, 4 );
			add_filter( 'wp_nav_menu', array( $this, 'caweb_nav_menu' ), 10, 2 );
		}

		/**
		 * Filters the arguments used to display a navigation menu.
		 *
		 * @link https://developer.wordpress.org/reference/hooks/wp_nav_menu_args/
		 * @param  array $args Array of wp_nav_menu() arguments.
		 *               $args['fallback_cb'] = array( $this, 'caweb_menu_fail' ).
		 * @return array
		 */
		public function caweb_nav_menu_args( $args ) {
			$args['fallback_cb'] = array( $this, 'caweb_menu_fail' );

			return $args;
		}

		/**
		 * Filters the arguments for the Navigation Menu widget.
		 *
		 * @link https://developer.wordpress.org/reference/hooks/widget_nav_menu_args/
		 * @param  array   $nav_menu_args An array of arguments passed to wp_nav_menu() to retrieve a navigation menu.
		 * @param  WP_Term $nav_menu Nav menu object for the current menu.
		 * @param  array   $args Display arguments for the current widget.
		 * @param  array   $instance Array of settings for the current widget.
		 *
		 * @return array
		 */
		public function caweb_widget_nav_menu_args( $nav_menu_args, $nav_menu, $args, $instance ) {
			if ( isset( $nav_menu_args['menu'] ) ) {
				$args['echo'] = false;
				print wp_kses( $this->createWidgetNavMenu( $nav_menu_args['menu'] ), caweb_allowed_html() );
			}

			return $args;
		}

		/**
		 * Filters the Walker class used when adding nav menu items.
		 *
		 * @link https://developer.wordpress.org/reference/hooks/wp_edit_nav_menu_walker/
		 * @param  string $current The walker class to use. Default 'Walker_Nav_Menu_Edit'.
		 *
		 * @return string
		 */
		public function caweb_edit_nav_menu_walker( $current = 'Walker_Nav_Menu_Edit' ) {
			if ( 'Walker_Nav_Menu_Edit' !== $current ) {
				return $current;
			}

			return 'CAWeb_Nav_Menu_Walker';
		}

		/**
		 * Filters the HTML content for navigation menus.
		 *
		 * @link https://developer.wordpress.org/reference/hooks/wp_nav_menu/
		 * @param  string   $nav_menu The HTML content for the navigation menu.
		 * @param  stdClass $args An object containing wp_nav_menu() arguments.
		 *
		 * @return string
		 */
		public function caweb_nav_menu( $nav_menu, $args ) {
			global $post;
			$post_id = is_object( $post ) ? $post->ID : ( isset( $post['ID'] ) ? $post['ID'] : -1 );

			$theme_location = $args->theme_location;
			/* Header Menu Construction */
			if ( 'header-menu' === $theme_location && ! empty( $args->menu ) ) {
				$nav_menu = $this->createNavMenu( $args );

				/* If not currently on the Front Page and Auto Home Nav Link option is true, create the Home Nav Link */
				$home_link = ( isset( $args->home_link ) && $args->home_link ? '<li class="nav-item nav-item-home"><a href="/" class="first-level-link"><span class="ca-gov-icon-home"></span> Home</a></li>' : '' );

				$search_link = 'page-templates/searchpage.php' !== get_page_template_slug( $post_id ) && '' !== get_option( 'ca_google_search_id', '' ) ?
									'<li class="nav-item" id="nav-item-search" ><button class="first-level-link h-auto"><span class="ca-gov-icon-search" aria-hidden="true"></span> Search</button></li>' : '';

				$nav_style = isset( $args->style ) ? ( 'flexmega' === $args->style ? 'megadropdown' : $args->style ) : 'megadropdown';

				$nav_menu = sprintf(
					'<nav id="navigation" class="main-navigation %1$s hidden-print nav">
                                <ul id="nav_list" class="top-level-nav">%2$s%3$s%4$s</ul></nav>',
					$nav_style,
					$home_link,
					$nav_menu,
					$search_link
				);

				/* Footer Menu Construction */
			} elseif ( 'footer-menu' === $theme_location && ! empty( $args->menu ) ) {
				$nav_menu   = $this->createFooterMenu( $args );
				$powered_by = is_plugin_active( 'caweb-admin/caweb-admin.php' ) || is_plugin_active_for_network( 'caweb-admin/caweb-admin.php' ) ? '<span class="pull-right">Powered by: CAWeb Publishing Service</span>' : '';

				$nav_menu = sprintf(
					'<footer id="footer" class="global-footer hidden-print"><div class="container footer-menu"><div class="group">%1$s</div></div><!-- Copyright Statement --><div class="copyright"><div class="container"><p class="d-inline">Copyright <span aria-hidden="true">&copy;</span> %2$s State of California</p>%3$s</div></div></footer>',
					$nav_menu,
					gmdate( 'Y' ),
					$powered_by
				);
			}

			return $nav_menu;
		}

		/**
		 * If the menu doesn't exists, this callback function will fire. Default is 'wp_page_menu'.
		 *
		 * @param  array $args Array of nav menu arguments.
		 *
		 * @return string
		 */
		public function caweb_menu_fail( $args ) {
			$nav_menu = '';
			if ( 'header-menu' === $args['theme_location'] ) {
				$nav_menu = '<nav id="navigation" class="main-navigation hidden-print nav"><ul id="nav_list" class="top-level-nav">
                        <li class="nav-item"><a href="#" class="first-level-link"><span class="ca-gov-icon-warning-triangle" aria-hidden="true"></span><strong>There Is No Navigation Menu Set</strong></a></li></ul></nav>';
			} elseif ( 'footer-menu' === $args['theme_location'] ) {
				$nav_menu     = '<ul class="footer-links"><li><a>There Is No Navigation Menu Set</a></li></ul>';
				$social_links = '';
				$nav_menu     = sprintf(
					'<footer id="footer" class="global-footer hidden-print"><div class="container"><div class="group">%1$s%2$s</div></div>
                            <!-- Copyright Statement -->
                      <div class="copyright">
                      <div class="container p-0"> &copy;
                      <script>document.write(new Date().getFullYear())</script> State of California </div></div></footer>',
					$nav_menu,
					$social_links
				);
			}

			if ( isset( $args['echo'] ) && $args['echo'] ) {
				print wp_kses( $nav_menu, caweb_allowed_html() );
			} else {
				return $nav_menu;
			}
		}

		/**
		 * Begin Creation of the Widget Navigation Menu
		 *
		 * @param  WP_Term $nav_menu Nav menu object for the current menu.
		 *
		 * @return string
		 */
		public function createWidgetNavMenu( $nav_menu ) {
			$widget_nav_menu = '';

			$menuitems = wp_get_nav_menu_items( $nav_menu->term_id, array( 'order' => 'DESC' ) );
			_wp_menu_item_classes_by_context( $menuitems );

			/* Iterate thru menuitems create Top Level (first-level-link) */
			foreach ( $menuitems as $i => $item ) {
				/*
				If a top level nav item,
				menu_item_parent= 0
				*/
				if ( ! $item->menu_item_parent ) {
					$sub_nav   = '';
					$item_meta = get_post_meta( $item->ID );

					/* Get array of Sub Nav Items (second-level-links) */
					$child_links = caweb_get_nav_menu_item_children( $item->ID, $menuitems );

					/* Count of Sub Nav Link */
					$child_count = count( $child_links );

					/* If there are child links create the sub-nav */
					if ( 0 < $child_count ) {
						$sub_nav_items = '';

						/* Iterate thru $child_links create Sub Level (second-level-links) */
						foreach ( $child_links as $i => $subitem ) {
							$sub_item_meta  = get_post_meta( $subitem->ID );
							$sub_nav_items .= sprintf(
								'<li class="%1$s%2$s"%3$s%4$s><a href="%5$s"%6$s>%7$s</a></li>',
								implode( ' ', $subitem->classes ),
								( in_array( 'current-menu-item', $subitem->classes, true ) ? ' active ' : '' ),
								( ! empty( $subitem->attr_title ) ? sprintf( ' title="%1$s" ', $subitem->attr_title ) : '' ),
								( ! empty( $subitem->xfn ) ? sprintf( ' rel="%1$s" ', $subitem->xfn ) : '' ),
								$subitem->url,
								( ! empty( $subitem->target ) ? sprintf( ' target="%1$s" ', $subitem->target ) : '' ),
								$subitem->title
							);
						}

						$sub_nav = sprintf( '<ul class="description">%1$s</ul>', $sub_nav_items );
					} /* End of sub-nav */

					$item_nav_image = '';
					if ( ! empty( $item_meta['_caweb_menu_icon'][0] ) ) {
						$item_nav_image_class = 'widget_nav_menu_icon ca-gov-icon-' . $item_meta['_caweb_menu_icon'][0];
						$item_nav_image       = "<span class=\"$item_nav_image_class\"></span>";
					} elseif ( ! empty( $item_meta['_caweb_menu_image'][0] ) ) {
						$item_nav_image = sprintf( '<img class="widget_nav_menu_img" src="%1$s"/>', $item_meta['_caweb_menu_image'][0] );
					}

					$widget_nav_menu .= sprintf(
						'<li class="nav-item %1$s%2$s"%3$s%4$s><a %5$s href="%6$s"%7$s%8$s>%9$s%10$s</a></li>',
						implode( ' ', $item->classes ),
						( in_array( 'current-menu-item', $item->classes, true ) ? ' active ' : '' ),
						( ! empty( $item->xfn ) ? sprintf( ' rel="%1$s" ', $item->xfn ) : '' ),
						( ! empty( $item->attr_title ) ? sprintf( ' title="%1$s" ', $item->attr_title ) : '' ),
						( ! empty( $item_nav_image ) ? 'class="widget_nav_menu_a"' : '' ),
						$item->url,
						( ! empty( $item->target ) ? sprintf( ' target="%1$s" ', $item->target ) : '' ),
						( 0 < $child_count ? ' class="toggle" ' : '' ),
						$item_nav_image,
						sprintf( '<p class="widget_nav_menu_title">%1$s</p>', $item->title )
					);
				}
			}

			return sprintf( '<ul class="accordion-list">%1$s</ul>', $widget_nav_menu );
		}

		/**
		 * HTML for the Navigation Menu (first-level-links)
		 *
		 * @param  stdClass $args An object containing wp_nav_menu() arguments.
		 *
		 * @return string
		 */
		public function createNavMenu( $args ) {
			$menuitems = wp_get_nav_menu_items( $args->menu->term_id, array( 'order' => 'DESC' ) );

			_wp_menu_item_classes_by_context( $menuitems );

			$nav_item = '';
			/* Iterate thru menuitems create Top Level (first-level-link) */
			foreach ( $menuitems as $i => $item ) {
				/*
				If a top level nav item,
				menu_item_parent= 0
				*/
				if ( ! $item->menu_item_parent ) {
					$item_meta = get_post_meta( $item->ID );
					/* Get array of Sub Nav Items (second-level-links) */
					$child_links = caweb_get_nav_menu_item_children( $item->ID, $menuitems );

					/* Count of Sub Nav Link */
					$child_count = count( $child_links );

					/* Get icon if present */
					$icon = isset( $item_meta['_caweb_menu_icon'] ) && ! empty( $item_meta['_caweb_menu_icon'][0] ) ? $item_meta['_caweb_menu_icon'][0] : 'logo invisible';
					$icon = '<span class="ca-gov-icon-' . $icon . '"></span>';

					/* if is current menut item add .active */
					$item->classes[] = in_array( 'current-menu-item', $item->classes, true ) ? ' active ' : '';

					/* Get column count */
					$item->classes[] = isset( $item_meta['_caweb_menu_column_count'] ) ? $item_meta['_caweb_menu_column_count'][0] : '';

					$sub_nav_indicator = $child_count ? '<span class="ca-gov-icon-triangle-down carrot align-middle" aria-hidden="true"></span>' : '';

					/* Create Link */
					$nav_item .= sprintf(
						'<li class="nav-item %1$s"%2$s title="%3$s"><a href="%4$s" class="first-level-link"%5$s>%6$s<span class="link-title">%7$s%8$s</span></a>',
						implode( ' ', $item->classes ),
						! empty( $item->xfn ) ? sprintf( ' rel="%1$s" ', $item->xfn ) : '',
						$item->attr_title,
						$item->url,
						! empty( $item->target ) ? sprintf( ' target="%1$s"', $item->target ) : '',
						$icon,
						$item->title,
						$sub_nav_indicator
					);

					/* If there are child links create the sub-nav */
					if ( 0 < $child_count && 'singlelevel' !== $args->style ) {
						if ( in_array( $args->style, array( 'flexmega', 'megadropdown' ), true ) ) {
							/* Sub nav image variables */
							$sub_img_class = 'full';
							$sub_img_div   = '';

							// if there a sub nav image set.
							if ( isset( $item_meta['_caweb_menu_image'][0] ) && ! empty( $item_meta['_caweb_menu_image'][0] ) ) {
								$nav_img      = $item_meta['_caweb_menu_image'][0];
								$nav_img_side = isset( $item_meta['_caweb_menu_image_side'][0] ) ? $item_meta['_caweb_menu_image_side'][0] : 'left';
								$nav_img_size = isset( $item_meta['_caweb_menu_image_size'][0] ) ? $item_meta['_caweb_menu_image_size'][0] : 'quarter';

								$sub_img_class = sprintf(
									'%1$s %2$s',
									( 'quarter' === $nav_img_size ? 'three-quarters' : 'half' ),
									( 'left' === $nav_img_side ? 'offset-' . $nav_img_size : '' )
								);

								$sub_img_div = sprintf(
									'<div class="%2$s with-image-%3$s" style="background: url(%1$s) no-repeat; background-size: 100%% 100%%;"></div>',
									$nav_img,
									$nav_img_size,
									$nav_img_side
								);

							}

							$nav_item .= sprintf(
								'<div class="sub-nav"><div class="%1$s">%2$s</div>%3$s</div></li>',
								$sub_img_class,
								$this->createSubNavMenu( $child_links, $args ),
								$sub_img_div
							);
						} else {
							$nav_item .= sprintf(
								'<div class="sub-nav"><div>%1$s</div></div></li>',
								$this->createSubNavMenu( $child_links, $args )
							);
						}
					} else {
						$nav_item .= '</li>';
					}
				}
			} /* End of for each */

			/* Print the list to the Navigation UL */
			return $nav_item;
		}

		/**
		 * HTML for Sub Navigation Menu from the Top Level Nav Item (second-level-links)
		 *
		 * @param  mixed    $child_links Array of Sub Nav Items (second-level-links).
		 * @param  stdClass $args An object containing wp_nav_menu() arguments.
		 *
		 * @return string
		 */
		public function createSubNavMenu( $child_links, $args ) {

			/* second-level-nav variables */
			$class   = 'second-level-nav';
			$element = 'ul';

			if ( 'flexmega' === $args->style ) {
				$class  .= ' flex';
				$element = 'div';
			}

			$sub_nav = '';

			$border_first_subnav = false;

			/* Iterate thru $child_links create Sub Level (second-level-links) */
			foreach ( $child_links as $i => $item ) {
				$item_meta = get_post_meta( $item->ID );
				$unit_size = isset( $item_meta['_caweb_menu_unit_size'][0] ) ? $item_meta['_caweb_menu_unit_size'][0] : 'unit1';
				$new_row   = isset( $item_meta['_caweb_menu_flexmega_row'][0] ) ? $item_meta['_caweb_menu_flexmega_row'][0] : '';

				/* Get icon if present */
				$icon = '';
				if ( isset( $item_meta['_caweb_menu_icon'] ) && ! empty( $item_meta['_caweb_menu_icon'][0] ) ) {
					$icon = sprintf(
						'<span class="ca-gov-icon-%1$s%2$s" aria-hidden="true"></span>',
						$item_meta['_caweb_menu_icon'][0],
						'unit2' === $unit_size && 'flexmega' === $args->style ? ' font-size-40' : ''
					);
				}

				/* Get desc if present */
				$desc = ! empty( $item->description ) ? sprintf( '<div class="link-description">%1$s</div>', $item->description ) : '&nbsp;';

				if ( 'flexmega' === $args->style ) {
					/* Get nav media if present */
					$nav_media_image     = isset( $item_meta['_caweb_menu_media_image'][0] ) ? $item_meta['_caweb_menu_media_image'][0] : '';
					$nav_media_alt_text  = isset( $item_meta['_caweb_nav_media_image_alt_text'][0] ) ? $item_meta['_caweb_nav_media_image_alt_text'][0] : '';
					$nav_media_alignment = isset( $item_meta['_caweb_menu_media_image_alignment'][0] ) ? $item_meta['_caweb_menu_media_image_alignment'][0] : 'left';

					$body = 'unit1' !== $unit_size ? sprintf( '<p class="h3 sub-nav-link">%1$s</p>', $item->title ) : $item->title;

					$body .= $desc;

					// Modify variables for unit3.
					if ( 'unit3' === $unit_size ) {
						$media_class = 'media-object width-80 height-80';

						$media_class = 'image-icon rounded-50x m-b-md';
						$media_wrap  = '';

						// if image is left aligned.
						if ( 'left' === $nav_media_alignment ) {
							if ( ! empty( $item->description ) ) {
								$desc = sprintf( '<div class="teaser link-description text-left">%1$s</div>', $item->description );
							}

							$media_class = 'media-object width-80 height-80';
							$media_wrap  = '<div class="media-left">';

							$body = sprintf(
								'<div class="media-body"><div class="title text-left">%1$s</div>%2$s</div>',
								$item->title,
								$desc
							);
						}

						$media = sprintf(
							'%1$s<img class="%2$s" src="%3$s" alt="%4$s">%5$s',
							$media_wrap,
							$media_class,
							$nav_media_image,
							$nav_media_alt_text,
							! empty( $media_wrap ) ? '</div>' : ''
						);
					} else {
						$media = $icon;
					}

					/* Create Link */
					$link = sprintf(
						'<a href="%1$s" class="second-level-link"%2$s>%3$s%4$s</a>',
						$item->url,
						( ! empty( $item->target ) ? sprintf( ' target="%1$s"', $item->target ) : '' ),
						$media,
						$body
					);

					$border = isset( $item_meta['_caweb_menu_flexmega_border'][0] ) ? $item_meta['_caweb_menu_flexmega_border'][0] : '';

					// if first row has a border it will be added at the end since a new row isnt required.
					if ( ! $i && ! empty( $border ) ) {
						$border_first_subnav = true;
					}

					if ( $new_row ) {

						$link = sprintf(
							'</div><%1$s class="%2$s%3$s">%4$s',
							$element,
							$class,
							! empty( $border ) ? ' with-border' : '',
							$link
						);
					}
					$sub_nav .= $link;
				} else {
					if ( 'unit3' !== $unit_size ) {
						/* Create Link */
						$link = sprintf(
							'<a href="%1$s" class="second-level-link"%2$s>%3$s%4$s%5$s</a>',
							$item->url,
							( ! empty( $item->target ) ? sprintf( ' target="%1$s"', $item->target ) : '' ),
							$icon,
							$item->title,
							'unit2' === $unit_size ? $desc : ''
						);

						$sub_nav .= sprintf(
							'<li %1$s title="%2$s" %3$s>%4$s</li>',
							sprintf( ' class="%1$s %2$s" ', $unit_size, implode( ' ', $item->classes ) ),
							$item->attr_title,
							( ! empty( $item->xfn ) ? sprintf( ' rel="%1$s" ', $item->xfn ) : '' ),
							$link
						);

					} else {
						/* Get nav media if present */
						$nav_media_image    = isset( $item_meta['_caweb_menu_media_image'][0] ) ? $item_meta['_caweb_menu_media_image'][0] : '';
						$nav_media_alt_text = isset( $item_meta['_caweb_nav_media_image_alt_text'][0] ) ? $item_meta['_caweb_nav_media_image_alt_text'][0] : '';

						$nav_media = 'megadropdown' === $args->style ?
							sprintf(
								'<div class="media-left"><a href="%1$s"><img style="height: 77px; max-width: 77px;" src="%2$s" alt="%3$s"/></a></div>',
								$item->url,
								$nav_media_image,
								$nav_media_alt_text
							) : '';

						$sub_nav .= sprintf(
							'<li %1$s title="%2$s" %3$s><div class="nav-media">
								<div class="media">%4$s<div class="media-body"><div class="title"><a href="%5$s"%6$s>%7$s</a></div>
								<div class="teaser">%8$s</div></div></div></div></li>',
							sprintf( ' class="%1$s %2$s" ', $unit_size, implode( ' ', $item->classes ) ),
							$item->attr_title,
							( ! empty( $item->xfn ) ? sprintf( ' rel="%1$s" ', $item->xfn ) : '' ),
							$nav_media,
							$item->url,
							( ! empty( $item->target ) ? sprintf( ' target="%1$s"', $item->target ) : '' ),
							$item->title,
							$desc
						);
					}
				}
			}

			// if flex megamenu adjust class case first row had a border which doesn't require new row.
			if ( $border_first_subnav ) {
				$class .= ' with-border';
			}

			/* Close .second-level-nav */
			$output = sprintf( '<%1$s class="%2$s">%3$s</%1$s>', $element, $class, $sub_nav );

			/* Return the Sub Nav */
			return $output;
		}

		/**
		 * HTML for the Footer Menu
		 *
		 * @param  stdClass $args An object containing wp_nav_menu() arguments.
		 *
		 * @return string
		 */
		public function createFooterMenu( $args ) {
			$nav_links = '';

			/* loop thru and create a link (parent nav item only) */
			$menuitems = wp_get_nav_menu_items( $args->menu->term_id, array( 'order' => 'DESC' ) );

			foreach ( $menuitems as $item ) {
				if ( ! $item->menu_item_parent ) {
					$nav_links .= sprintf(
						'<li%1$stitle="%2$s"%3$s><a href="%4$s"%5$s>%6$s</a></li>',
						( ! empty( $item->classes ) ? sprintf( ' class="%1$s" ', implode( ' ', $item->classes ) ) : '' ),
						$item->attr_title,
						( ! empty( $item->xfn ) ? sprintf( ' rel="%1$s" ', $item->xfn ) : '' ),
						$item->url,
						( ! empty( $item->target ) ? sprintf( ' target="%1$s"', $item->target ) : '' ),
						$item->title
					);
				}
			}

			$social_links = $this->createFooterSocialMenu( $args );

			$class = ! empty( $social_links ) ? 'three-quarters' : 'full-width';
			$style = '';

			$nav_links = sprintf( '<div class="%1$s"><ul class="footer-links" %2$s><li><a href="#skip-to-content">Back to Top</a></li>%3$s</ul></div>%4$s', $class, $style, $nav_links, $social_links );

			return $nav_links;
		}

		/**
		 * HTML for the Footer Social Menu
		 *
		 * @param  stdClass $args An object containing wp_nav_menu() arguments.
		 *
		 * @return string
		 */
		public function createFooterSocialMenu( $args ) {
			$social_share = caweb_get_site_options( 'social' );
			$social_links = '';

			foreach ( $social_share as $opt ) {
				$share_email = 'ca_social_email' === $opt ? true : false;
				$sub         = rawurlencode( sprintf( '%1$s | %2$s', get_the_title(), get_bloginfo( 'name' ) ) );
				$body        = rawurlencode( get_permalink() );
				$mailto      = $share_email ? sprintf( 'mailto:?subject=%1$s&body=%2$s', $sub, $body ) : '';

				if ( get_option( $opt . '_footer' ) && ( $share_email || '' !== get_option( $opt ) ) ) {
					$share         = substr( $opt, 10 );
					$share         = str_replace( '_', '-', $share );
					$title         = get_option( "${opt}_hover_text", 'Share via ' . ucwords( $share ) );
					$social_url    = $share_email ? $mailto : esc_url( get_option( $opt ) );
					$social_target = sprintf( ' target="%1$s"', get_option( $opt . '_new_window', true ) ? '_blank' : '_self' );
					$social_icon   = ! empty( $share ) ? "<span class=\"ca-gov-icon-$share\"></span>" : '';
					$social_links .= sprintf( '<li><a href="%1$s" title="%2$s"%3$s>%4$s<span class="sr-only">%5$s</span></a></li>', $social_url, $title, $social_target, $social_icon, $share );
				}
			}

			$social_links = ! empty( $social_links ) ? sprintf( '<ul class="socialsharer-container">%1$s</ul>', $social_links ) : '';

			return ! empty( $social_links ) ? sprintf( '<div class="quarter">%1$s</div>', $social_links ) : $social_links;
		}

		/**
		 * CAWeb wp nav menu item custom fields hook. Hooked from the CAWeb_Nav_Menu_Walker.
		 *
		 * @param  mixed $item_id Not used.
		 * @param  mixed $item Menu item data object.
		 * @param  mixed $depth Depth of menu item. Used for padding.
		 * @param  mixed $args Not used.
		 *
		 * @return void
		 */
		public function caweb_nav_menu_item_custom_fields( $item_id, $item, $depth, $args ) {
			$tmp                       = get_post_meta( $item->ID );
			$icon                      = isset( $tmp['_caweb_menu_icon'][0] ) && ! empty( $tmp['_caweb_menu_icon'][0] ) ? $tmp['_caweb_menu_icon'][0] : '';
			$unit_size                 = isset( $tmp['_caweb_menu_unit_size'][0] ) && ! empty( $tmp['_caweb_menu_unit_size'][0] ) ? $tmp['_caweb_menu_unit_size'][0] : 'unit1';
			$mega_menu_img             = isset( $tmp['_caweb_menu_image'][0] ) && ! empty( $tmp['_caweb_menu_image'][0] ) ? $tmp['_caweb_menu_image'][0] : '';
			$mega_menu_side            = isset( $tmp['_caweb_menu_image_side'][0] ) ? $tmp['_caweb_menu_image_side'][0] : 'left';
			$mega_menu_size            = isset( $tmp['_caweb_menu_image_size'][0] ) ? $tmp['_caweb_menu_image_size'][0] : 'quarter';
			$menu_column_count         = isset( $tmp['_caweb_menu_column_count'][0] ) ? $tmp['_caweb_menu_column_count'][0] : '';
			$nav_media_img             = isset( $tmp['_caweb_menu_media_image'][0] ) ? $tmp['_caweb_menu_media_image'][0] : '';
			$nav_media_image_alt_text  = isset( $tmp['_caweb_nav_media_image_alt_text'][0] ) && ! empty( $tmp['_caweb_nav_media_image_alt_text'][0] ) ? $tmp['_caweb_nav_media_image_alt_text'][0] : '';
			$nav_media_image_alignment = isset( $tmp['_caweb_menu_media_image_alignment'][0] ) ? $tmp['_caweb_menu_media_image_alignment'][0] : 'left';
			$flex_border               = isset( $tmp['_caweb_menu_flexmega_border'][0] ) ? $tmp['_caweb_menu_flexmega_border'][0] : '';
			$flex_row                  = isset( $tmp['_caweb_menu_flexmega_row'][0] ) ? $tmp['_caweb_menu_flexmega_row'][0] : '';

			$nav_menu_style = get_option( 'ca_default_navigation_menu', 'megadropdown' );
			?>
			<div class="icon-selector <?php print 'unit3' === $unit_size ? 'hidden' : ''; ?> description description-wide">
				<?php
				print wp_kses(
					caweb_icon_menu(
						array(
							'select' => $icon,
							'name'   => $item_id . '_icon',
							'header' => 'Select an Icon',
						)
					),
					caweb_allowed_html( array(), true )
				);
				?>
			</div>
			<div class="unit-selector<?php print ! $depth ? ' hidden' : ''; ?> description description-wide">
				<p><strong>Select a height for the navigation item</strong></p>
				<select name="<?php print esc_attr( $item_id ); ?>_unit_size" class="unit-size-selector" id="unit-size-selector-<?php print esc_attr( $item_id ); ?>">
					<option value="unit1"<?php print 'unit1' === $unit_size ? ' selected' : ''; ?>>Unit 1 - 50px height</option>
					<option value="unit2"<?php print 'unit2' === $unit_size ? ' selected' : ''; ?>>Unit 2 - 100px height</option>
					<?php if ( in_array( $nav_menu_style, array( 'flexmega', 'megadropdown' ), true ) ) : ?>
						<option value="unit3"<?php print 'unit3' === $unit_size ? ' selected' : ''; ?>>Unit 3 - 100px height w/ Image</option>
					<?php endif; ?>
				</select>
			</div>
			<?php if ( 'flexmega' === $nav_menu_style ) : ?>
			<div class="description description-wide flexmega-row <?php print ! $depth ? ' hidden' : ''; ?>">
				<p>
					<label for="<?php print esc_attr( $item_id ); ?>_flexmega_row"><input type="checkbox" name="<?php print esc_attr( $item_id ); ?>_flexmega_row" id="<?php print esc_attr( $item_id ); ?>_flexmega_row" <?php print $flex_row ? 'checked' : ''; ?> class="new-row"> New Row</label>
				</p>
			</div>
			<div class="description description-wide flexmega-border <?php print ! $depth || ( $depth && ! $flex_row ) ? 'hidden' : ''; ?>">
				<label for="<?php print esc_attr( $item_id ); ?>_flexmega_border"><input type="checkbox" name="<?php print esc_attr( $item_id ); ?>_flexmega_border" id="<?php print esc_attr( $item_id ); ?>_flexmega_border" <?php print $flex_border ? 'checked' : ''; ?>> Add Border</label>
			</div>
			<?php endif; ?>
			<div class="media-image<?php print 'unit3' !== $unit_size ? ' hidden' : ''; ?> description description-wide">
				<p><strong>Navigation Media Image</strong></p>
				<p>Select an Image</p>
				<input name="<?php print esc_attr( $item_id ); ?>_media_image" id="<?php print esc_attr( $item_id ); ?>_media_image" type="text" class="link-text" style="width: 97%;" value="<?php print esc_attr( $nav_media_img ); ?>" />
				<input type="button" class="library-link" value="Browse" id="library-link-<?php print esc_attr( $item_id ); ?>" name="<?php print esc_attr( $item_id ); ?>_media_image" data-choose="Choose a Default Image" data-update="Set as Navigation Media Image" />
				<p>Navigation Media Image Alt Text
				<input name="<?php print esc_attr( $item_id ); ?>_media_image_alt_text" id="<?php print esc_attr( $item_id ); ?>_media_image_alt_text" value="<?php print esc_attr( $nav_media_image_alt_text ); ?>" type="text" /></p>
				<p>Navigation Media Image Alignment</p>
				<label for="<?php print esc_attr( $item_id ); ?>_media_image_alignment_left">
				<input name="<?php print esc_attr( $item_id ); ?>_media_image_alignment" id="<?php print esc_attr( $item_id ); ?>_media_image_alignment_left" value="left" type="radio"<?php print 'left' === $nav_media_image_alignment ? ' checked' : ''; ?>/>Left</label>
				<label for="<?php print esc_attr( $item_id ); ?>_media_image_alignment_top">
				<input name="<?php print esc_attr( $item_id ); ?>_media_image_alignment" id="<?php print esc_attr( $item_id ); ?>_media_image_alignment_top" value="top" type="radio"<?php print 'top' === $nav_media_image_alignment ? ' checked' : ''; ?>/>Top</label>
			</div>
			<?php if ( 'megadropdown' === $nav_menu_style ) : ?>
			<div class="mega-menu-images<?php print $depth ? ' hidden' : ''; ?> description description-wide ">
				<p><strong>Mega Menu Image Option</strong></p>
				<p>Select an Image</p>
				<input name="<?php print esc_attr( $item_id ); ?>_image" id="<?php print esc_attr( $item_id ); ?>_image" type="text" class="link-text" style="width: 97%;" value="<?php print esc_attr( $mega_menu_img ); ?>" />
				<input type="button" value="Browse" id="library-link-<?php print esc_attr( $item_id ); ?>" class="library-link" name="<?php print esc_attr( $item_id ); ?>_image" data-choose="Choose a Default Image" data-update="Set as Sub Navigation Image" />
				<p>Select a Side / Select a Size</p>
				<select name="<?php print esc_attr( $item_id ); ?>_image_side">
					<option value="left"<?php print 'left' === $mega_menu_side ? ' selected' : ''; ?>>Left</option>
					<option value="right"<?php print 'right' === $mega_menu_side ? ' selected' : ''; ?>>Right</option>
				</select>
				/
				<select name="<?php print esc_attr( $item_id ); ?>_image_size">
					<option value="quarter"<?php print 'quarter' === $mega_menu_size ? 'selected' : ''; ?>>Quarter</option>
					<option value="half"<?php print 'half' === $mega_menu_size ? 'selected' : ''; ?>>Half</option>
				</select>
				<p>Select a column layout</p>
				<select name="<?php print esc_attr( $item_id ); ?>_column_count">
					<option value=""<?php print empty( $menu_column_count ) ? ' selected' : ''; ?>>Select layout...</option>
					<option value="two-columns"<?php print ! empty( $menu_column_count ) && 'two-columns' === $menu_column_count ? ' selected' : ''; ?>>2 Columns</option>
					<option value="three-columns"<?php print ! empty( $menu_column_count ) && 'three-columns' === $menu_column_count ? ' selected' : ''; ?>>3 Columns</option>
					<option value="four-columns"<?php print ! empty( $menu_column_count ) && 'four-columns' === $menu_column_count ? ' selected' : ''; ?>>4 Columns</option>
				</select>
			</div>
				<?php
			endif;
		}

		/**
		 * Fires after a navigation menu item has been updated.
		 * Save menu custom fields that are added on to ca_custom_nav_walker.
		 *
		 * @param  int   $menu_id ID of the updated menu.
		 * @param  int   $menu_item_db_id ID of the updated menu item.
		 * @param  array $args An array of arguments used to update a menu item.
		 *
		 * @return int
		 */
		public function caweb_update_nav_menu_item( $menu_id, $menu_item_db_id, $args ) {
			$verified = isset( $_POST['update-nav-menu-nonce'] ) &&
				wp_verify_nonce( sanitize_key( $_POST['update-nav-menu-nonce'] ), 'update-nav_menu' );

			/* Check if element is properly sent */
			if ( $verified && isset( $_POST['menu-item-db-id'] ) ) {
				$icon                        = isset( $_POST[ $menu_item_db_id . '_icon' ] ) ? sanitize_text_field( wp_unslash( $_POST[ $menu_item_db_id . '_icon' ] ) ) : '';
				$unit_size                   = isset( $_POST[ $menu_item_db_id . '_unit_size' ] ) ? sanitize_text_field( wp_unslash( $_POST[ $menu_item_db_id . '_unit_size' ] ) ) : 'unit1';
				$item_image                  = isset( $_POST[ $menu_item_db_id . '_image' ] ) ? esc_url_raw( wp_unslash( $_POST[ $menu_item_db_id . '_image' ] ) ) : '';
				$item_image_side             = isset( $_POST[ $menu_item_db_id . '_image_side' ] ) ? sanitize_text_field( wp_unslash( $_POST[ $menu_item_db_id . '_image_side' ] ) ) : 'left';
				$item_image_size             = isset( $_POST[ $menu_item_db_id . '_image_size' ] ) ? sanitize_text_field( wp_unslash( $_POST[ $menu_item_db_id . '_image_size' ] ) ) : 'quarter';
				$column_count                = isset( $_POST[ $menu_item_db_id . '_column_count' ] ) ? sanitize_text_field( wp_unslash( $_POST[ $menu_item_db_id . '_column_count' ] ) ) : '';
				$item_media_image            = isset( $_POST[ $menu_item_db_id . '_media_image' ] ) ? esc_url_raw( wp_unslash( $_POST[ $menu_item_db_id . '_media_image' ] ) ) : '';
				$item_media_image_alt_text   = isset( $_POST[ $menu_item_db_id . '_media_image_alt_text' ] ) ? sanitize_text_field( wp_unslash( $_POST[ $menu_item_db_id . '_media_image_alt_text' ] ) ) : '';
				$item_media_image_aligntment = isset( $_POST[ $menu_item_db_id . '_media_image_alignment' ] ) ? sanitize_text_field( wp_unslash( $_POST[ $menu_item_db_id . '_media_image_alignment' ] ) ) : '';
				$flexmega_border             = isset( $_POST[ $menu_item_db_id . '_flexmega_border' ] ) ? sanitize_text_field( wp_unslash( $_POST[ $menu_item_db_id . '_flexmega_border' ] ) ) : '';
				$flexmega_row                = isset( $_POST[ $menu_item_db_id . '_flexmega_row' ] ) ? sanitize_text_field( wp_unslash( $_POST[ $menu_item_db_id . '_flexmega_row' ] ) ) : '';

				update_post_meta( $menu_item_db_id, '_caweb_menu_icon', $icon );
				update_post_meta( $menu_item_db_id, '_caweb_menu_unit_size', $unit_size );
				update_post_meta( $menu_item_db_id, '_caweb_menu_image', $item_image );
				update_post_meta( $menu_item_db_id, '_caweb_menu_image_side', $item_image_side );
				update_post_meta( $menu_item_db_id, '_caweb_menu_image_size', $item_image_size );
				update_post_meta( $menu_item_db_id, '_caweb_menu_column_count', $column_count );
				update_post_meta( $menu_item_db_id, '_caweb_menu_media_image', $item_media_image );
				update_post_meta( $menu_item_db_id, '_caweb_nav_media_image_alt_text', $item_media_image_alt_text );
				update_post_meta( $menu_item_db_id, '_caweb_menu_media_image_alignment', $item_media_image_aligntment );
				update_post_meta( $menu_item_db_id, '_caweb_menu_flexmega_border', $flexmega_border );
				update_post_meta( $menu_item_db_id, '_caweb_menu_flexmega_row', $flexmega_row );

			}

			return $menu_item_db_id;
		}
	}
}

new CAWeb_Nav_Menu();
