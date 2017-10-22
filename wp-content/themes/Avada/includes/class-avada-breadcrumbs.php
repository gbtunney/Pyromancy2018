<?php
/**
 * Class Avada_Breadcrumbs
 * This file does the breadcrumbs handling for the fusion framework.
 *
 * @author     ThemeFusion
 * @copyright  (c) Copyright by ThemeFusion
 * @link       http://theme-fusion.com
 * @package    Avada
 * @subpackage Core
 * @since      1.0
 */

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}

/**
 * Handle breadcrumbs.
 */
class Avada_Breadcrumbs {

	/**
	 * Current post object.
	 *
	 * @var mixed
	 */
	private $post;

	/**
	 * Prefix for the breadcrumb path.
	 *
	 * @var	string
	 */
	private $home_prefix;

	/**
	 * Separator between single breadscrumbs.
	 *
	 * @var	string
	 */
	private $separator;

	/**
	 * True if terms should be shown in breadcrumb path.
	 *
	 * @var	bool
	 */
	private $show_terms;

	/**
	 * Label for the "Home" link.
	 *
	 * @var	string
	 */
	private $home_label;

	/**
	 * Prefix used for pages like date archive.
	 *
	 * @var	string
	 */
	private $tag_archive_prefix;

	/**
	 * Prefix used for search page.
	 *
	 * @var	string
	 */
	private $search_prefix;

	/**
	 * Prefix used for 404 page.
	 *
	 * @var	string
	 */
	private $error_prefix;

	/**
	 * Do ww want to show post-type archives?
	 *
	 * @var bool
	 */
	private $show_post_type_archive;

	/**
	 * The HTML markup.
	 *
	 * @var string
	 */
	private $html_markup;

	/**
	 * The one, true instance of this object.
	 *
	 * @static
	 * @access private
	 * @var null|object
	 */
	private static $instance = null;


	/**
	 * Class Constructor.
	 */
	private function __construct() {

		// Initialize object variables.
		$this->post    = get_post( get_queried_object_id() );

		// Setup default array for changeable variables.
		$defaults = array(
			 'home_prefix'            => Avada()->settings->get( 'breacrumb_prefix' ) ? Avada()->settings->get( 'breacrumb_prefix' ) : '',
			 'separator'              => Avada()->settings->get( 'breadcrumb_separator' ) ? Avada()->settings->get( 'breadcrumb_separator' ) : '',
			 'show_post_type_archive' => Avada()->settings->get( 'breadcrumb_show_post_type_archive' ) ? Avada()->settings->get( 'breadcrumb_show_post_type_archive' ) : '',
			 'show_terms'             => Avada()->settings->get( 'breadcrumb_show_categories' ) ? Avada()->settings->get( 'breadcrumb_show_categories' ) : '',
			 'home_label'             => esc_attr__( 'Home', 'Avada' ),
			 'tag_archive_prefix'     => esc_attr__( 'Tag:', 'Avada' ),
			 'search_prefix'          => esc_attr__( 'Search:', 'Avada' ),
			 'error_prefix'           => esc_attr__( '404 - Page not Found', 'Avada' ),
		);

		// Setup a filter for changeable variables and merge it with the defaults.
		$args     = apply_filters( 'avada_breadcrumbs_defaults', $defaults );
		$defaults = wp_parse_args( $args, $defaults );

		$this->home_prefix            = $defaults['home_prefix'];
		$this->separator              = $defaults['separator'];
		$this->show_post_type_archive = $defaults['show_post_type_archive'];
		$this->show_terms             = $defaults['show_terms'];
		$this->home_label             = $defaults['home_label'];
		$this->tag_archive_prefix     = $defaults['tag_archive_prefix'];
		$this->search_prefix          = $defaults['search_prefix'];
		$this->error_prefix           = $defaults['error_prefix'];
	}

	/**
	 * Get a unique instance of this object.
	 *
	 * @return object
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new Avada_Breadcrumbs();
		}
		return self::$instance;
	}

	/**
	 * Publicly accessible function to get the full breadcrumb HTML markup.
	 *
	 * @return void
	 */
	public function get_breadcrumbs() {
		// Get the Wordpres SEO options if activated; else will return FALSE.
		$options = get_option( 'wpseo_internallinks' );

		// Support for Yoast Breadcrumbs.
		if ( function_exists( 'yoast_breadcrumb' ) && $options && true === $options['breadcrumbs-enable'] ) {

			ob_start();
			yoast_breadcrumb();
			$this->html_markup = ob_get_clean();

		} else { // ThemeFusion Breadcrumbs.

			$this->prepare_breadcrumb_html();

		}

		$this->wrap_breadcrumbs();
		$this->output_breadcrumbs_html();
	}

	/**
	 * Prepare the full output of the breadcrumb path.
	 *
	 * @return void
	 */
	private function prepare_breadcrumb_html() {
		// Add the path prefix.
		$this->html_markup = $this->get_breadcrumb_prefix();

		// Add the "Home" link.
		$this->html_markup .= $this->get_breadcrumb_home();

		// Woocommerce path prefix (e.g "Shop" ).
		if ( class_exists( 'WooCommerce' ) && ( ( Avada_Helper::is_woocommerce() && is_archive() && ! is_shop() ) || is_cart() || is_checkout() || is_account_page() ) ) {
			$this->html_markup .= $this->get_woocommerce_shop_page();
		}

		// Path prefix for bbPress (e.g "Forums" ).
		if ( class_exists( 'bbPress' ) && Avada_Helper::is_bbpress() && ( Avada_Helper::bbp_is_topic_archive() || bbp_is_single_user() || Avada_Helper::bbp_is_search() ) ) {
			$this->html_markup .= $this->get_bbpress_main_archive_page();
		}

		// Single Posts and Pages (of all post types).
		if ( is_singular() ) {

			// If the post type of the current post has an archive link, display the archive breadcrumb.
			if ( isset( $this->post->post_type ) && get_post_type_archive_link( $this->post->post_type ) && $this->show_post_type_archive ) {
				$this->html_markup .= $this->get_post_type_archive();
			}

			// If the post doesn't have parents.
			if ( isset( $this->post->post_parent ) && 0 == $this->post->post_parent ) {
				$this->html_markup .= $this->get_post_terms();
			} else {
				// If there are parents; mostly for pages.
				$this->html_markup .= $this->get_post_ancestors();
			}

			$this->html_markup .= $this->get_breadcrumb_leaf_markup();
		} else {
			// Blog page is a dedicated page.
			if ( is_home() && ! is_front_page() ) {
				$posts_page         = get_option( 'page_for_posts' );
				$posts_page_title   = get_the_title( $posts_page );
				$this->html_markup .= $this->get_single_breadcrumb_markup( $posts_page_title, '', true, true, true );
			} else if ( ( is_tax() || is_tag() || is_category() || is_date() || is_author() ) && $this->show_post_type_archive && ! Avada_Helper::is_woocommerce() && ! Avada_Helper::is_bbpress() ) {
				$this->html_markup .= $this->get_post_type_archive();
			}

			// Custom post types archives.
			if ( is_post_type_archive() ) {
				// Search on custom post type (e.g. Woocommerce).
				if ( is_search() ) {
					$this->html_markup .= $this->get_post_type_archive();
					$this->html_markup .= $this->get_breadcrumb_leaf_markup( 'search' );
				} else {
					$this->html_markup .= $this->get_post_type_archive( false );
				}
			} else if ( is_tax() || is_tag() || is_category() ) {

				// Taxonomy Archives.
				if ( is_tag() ) { // If we have a tag archive, add the tag prefix.
					$this->html_markup .= $this->tag_archive_prefix;
				}
				$this->html_markup .= $this->get_taxonomies();
				$this->html_markup .= $this->get_breadcrumb_leaf_markup( 'term' );
			} else if ( is_date() ) {
				// Date Archives.
				global $wp_locale;
				$year = esc_html( get_query_var( 'year' ) );
				if ( ! $year ) {
					$year = substr( esc_html( get_query_var( 'm' ) ) , 0, 4 );
				}

				// Year Archive, only is a leaf.
				if ( is_year() ) {
					$this->html_markup .= $this->get_breadcrumb_leaf_markup( 'year' );
				} else if ( is_month() ) {
					// Month Archive, needs year link and month leaf.
					$this->html_markup .= $this->get_single_breadcrumb_markup( $year, get_year_link( $year ) );
					$this->html_markup .= $this->get_breadcrumb_leaf_markup( 'month' );
				} else if ( is_day() ) {
					// Day Archive, needs year and month link and day leaf.
					global $wp_locale;

					$month = get_query_var( 'monthnum' );
					if ( ! $month ) {
						$month = substr( esc_html( get_query_var( 'm' ) ) , 4, 2 );
					}

					$month_name = $wp_locale->get_month( $month );
					$this->html_markup .= $this->get_single_breadcrumb_markup( $year, get_year_link( $year ) );
					$this->html_markup .= $this->get_single_breadcrumb_markup( $month_name, get_month_link( $year, $month ) );
					$this->html_markup .= $this->get_breadcrumb_leaf_markup( 'day' );
				}
			} else if ( is_author() ) {
				// Author Archives.
				$this->html_markup .= $this->get_breadcrumb_leaf_markup( 'author' );
			} else if ( is_search() ) {
				// Search Page.
				$this->html_markup .= $this->get_breadcrumb_leaf_markup( 'search' );
			} else if ( is_404() ) {
				// 404 Page.
				// Special treatment for Events Calendar to avoid 404 messages on list view.
				if ( Avada_Helper::tribe_is_event() || Avada_Helper::is_events_archive() ) {
					$this->html_markup .= $this->get_breadcrumb_leaf_markup( 'events' );
				} else {
					$this->html_markup .= $this->get_breadcrumb_leaf_markup( '404' );
				}
			} else if ( class_exists( 'bbPress' ) ) {
				// bbPress.
				// Search Page.
				if ( Avada_Helper::bbp_is_search() ) {
					$this->html_markup .= $this->get_breadcrumb_leaf_markup( 'bbpress_search' );
				} elseif ( bbp_is_single_user() ) {
					// User page.
					$this->html_markup .= $this->get_breadcrumb_leaf_markup( 'bbpress_user' );
				}
			} // End if().
		} // End if().
	}

	/**
	 * Wrap the breadcrumb path in a div.
	 */
	private function wrap_breadcrumbs() {
		$this->html_markup = '<div class="fusion-breadcrumbs">' . $this->html_markup . '</div>';
	}

	/**
	 * Output the full breadcrumb HTML markup.
	 *
	 * @return void
	 */
	private function output_breadcrumbs_html() {
		echo $this->html_markup; // WPCS: XSS ok.
	}

	/**
	 * Get the markup of the breadcrumb path prefix.
	 *
	 * @return string The HTML markup of the breadcrumb path prefix.
	 */
	private function get_breadcrumb_prefix() {
		$prefix = '';

		// If the home page is a real page.
		if ( ! is_front_page() ) {
			// Add chosen path prefix.
			if ( $this->home_prefix ) {
				$prefix = '<span class="fusion-breadcrumb-prefix">' . $this->home_prefix . ':</span>';
			}
		}

		return $prefix;
	}

	/**
	 * Get the markup of the "Home" Link.
	 *
	 * @return string The HTML markup of the "Home" link.
	 */
	private function get_breadcrumb_home() {
		$home_link = '';

		// If the home page is a real page.
		if ( ! is_front_page() ) {
			$home_link = $this->get_single_breadcrumb_markup( $this->home_label, get_home_url() );
		} elseif ( is_home() && Avada()->settings->get( 'blog_title' ) ) { // If the home page is the main blog page.
			$home_link = $this->get_single_breadcrumb_markup( Avada()->settings->get( 'blog_title' ), '', true, true, true );
		}

		return $home_link;

	}

	/**
	 * Construct the full post term tree path and add its HTML markup.
	 *
	 * @return string The HTML markup of the full term breadcrumb path.
	 */
	private function get_post_terms() {
		$terms_markup = '';

		// If terms are disabled, nothing is to do.
		if ( ! $this->show_terms ) {
			return $terms_markup;
		}

		// Get the post terms.
		if ( 'post' == $this->post->post_type ) {
			$taxonomy = 'category';
		} elseif ( 'avada_portfolio' == $this->post->post_type ) {
			// ThemeFusion Portfolio.
			$taxonomy = 'portfolio_category';
		} elseif ( 'product' == $this->post->post_type && class_exists( 'WooCommerce' ) && Avada_Helper::is_woocommerce() ) {
			// Woocommerce.
			$taxonomy = 'product_cat';
		} elseif ( 'tribe_events' == $this->post->post_type ) {
			// The Events Calendar.
			$taxonomy = 'tribe_events_cat';
		} else {
			// For other post types don't return a terms tree to reduce possible errors.
			return $terms_markup;
		}

		$terms = wp_get_object_terms( $this->post->ID, $taxonomy );

		// If post does not have any terms assigned; possible e.g. portfolio posts.
		if ( empty( $terms ) ) {
			return $terms_markup;
		}

		// Check if the terms are all part of one term tree, i.e. only related terms are selected.
		$terms_by_id = array();
		foreach ( $terms as $term ) {
			$terms_by_id[ $term->term_id ] = $term;
		}

		// Unset all terms that are parents of some term.
		foreach ( $terms as $term ) {
			unset( $terms_by_id[ $term->parent ] );
		}

		// If only one term is left, we have a single term tree.
		if ( 1 === count( $terms_by_id ) ) {
			unset( $terms );
			$terms[0] = array_shift( $terms_by_id );
		}

		// The post is only in one term.
		if ( 1 === count( $terms ) ) {

			$term_parent = $terms[0]->parent;

			// If the term has a parent we need its ancestors for a full tree.
			if ( $term_parent ) {
				// Get space separated string of term tree in slugs.
				$term_tree   = get_ancestors( $terms[0]->term_id, $taxonomy );
				$term_tree   = array_reverse( $term_tree );
				$term_tree[] = get_term( $terms[0]->term_id, $taxonomy );

				// Loop through the term tree.
				foreach ( $term_tree as $term_id ) {
					// Get the term object by its slug.
					$term_object = get_term( $term_id, $taxonomy );

					// Add it to the term breadcrumb markup string.
					$terms_markup .= $this->get_single_breadcrumb_markup( $term_object->name, get_term_link( $term_object ) );
				}
			} else {
				// We have a single term, so put it out.
				$terms_markup = $this->get_single_breadcrumb_markup( $terms[0]->name, get_term_link( $terms[0] ) );
			}
		} else { // The post has multiple terms.

			// Add a parent term, if all children share the same parent.
			foreach ( $terms as $term ) {
				$term_parents[] = $term->parent;
			}

			if ( 1 === count( array_unique( $term_parents ) ) && $term_parents[0] ) {
				// Get space separated string of term tree in slugs.
				$term_tree   = get_ancestors( $terms[0]->term_id, $taxonomy );
				$term_tree   = array_reverse( $term_tree );

				// Loop through the term tree.
				foreach ( $term_tree as $term_id ) {
					// Get the term object by its slug.
					$term_object = get_term( $term_id, $taxonomy );

					// Add it to the term breadcrumb markup string.
					$terms_markup .= $this->get_single_breadcrumb_markup( $term_object->name, get_term_link( $term_object ) );
				}
			}

			// The lexicographically smallest term will be part of the breadcrump rich snippet path.
			$terms_markup .= $this->get_single_breadcrumb_markup( $terms[0]->name, get_term_link( $terms[0] ), false );
			// Drop the first index.
			array_shift( $terms );

			// Loop through the rest of the terms, and add them to string comma separated.
			$max_index = count( $terms );
			$i = 0;
			foreach ( $terms as $term ) {

				// For the last index also add the separator.
				if ( ++$i == $max_index ) {
					$terms_markup .= ', ' . $this->get_single_breadcrumb_markup( $term->name, get_term_link( $term ), true, false );
				} else {
					$terms_markup .= ', ' . $this->get_single_breadcrumb_markup( $term->name, get_term_link( $term ), false, false );
				}
			}
		} // End if().

		return $terms_markup;
	}

	/**
	 * Construct the full post ancestors tree path and add its HTML markup.
	 *
	 * @return string The HTML markup of the ancestors tree.
	 */
	private function get_post_ancestors() {
		$ancestors_markup = '';

		// Get the ancestor id, order needs to be reversed.
		$post_ancestor_ids = array_reverse( get_post_ancestors( $this->post ) );

		// Loop through the ids to get the full tree.
		foreach ( $post_ancestor_ids as $post_ancestor_id ) {
			$post_ancestor     = get_post( $post_ancestor_id );

			if ( isset( $post_ancestor->post_title ) && isset( $post_ancestor->ID ) ) {
				$ancestors_markup .= $this->get_single_breadcrumb_markup( apply_filters( 'the_title', $post_ancestor->post_title ), get_permalink( $post_ancestor->ID ) );
			}
		}

		return $ancestors_markup;
	}

	/**
	 * Construct the full term ancestors tree path and add its HTML markup.
	 *
	 * @return string The HTML markup of the term ancestors tree.
	 */
	private function get_taxonomies() {
		global $wp_query;
		$term = $wp_query->get_queried_object();
		$terms_markup = '';

		// Make sure we have hierarchical taxonomy and parents.
		if ( 0 != $term->parent && is_taxonomy_hierarchical( $term->taxonomy ) ) {
			$term_ancestors = get_ancestors( $term->term_id, $term->taxonomy );
			$term_ancestors = array_reverse( $term_ancestors );
			// Loop through ancestors to get the full tree.
			foreach ( $term_ancestors as $term_ancestor ) {
				$term_object   = get_term( $term_ancestor, $term->taxonomy );
				$terms_markup .= $this->get_single_breadcrumb_markup( $term_object->name, get_term_link( $term_object->term_id, $term->taxonomy ) );
			}
		}

		return $terms_markup;
	}

	/**
	 * Adds the markup of a post type archive.
	 *
	 * @param  string $linked Linked or not linked.
	 * @return string The HTML markup of the post type archive.
	 */
	private function get_post_type_archive( $linked = true ) {
		global $wp_query;

		$link          = '';
		$archive_title = '';

		$post_type = $wp_query->query_vars['post_type'];
		if ( ! $post_type ) {
			$post_type = get_post_type();
		}
		$post_type_object = get_post_type_object( $post_type );

		// Check if we have a post type object.
		if ( is_object( $post_type_object ) ) {

			// Woocommerce: archive name should be same as shop page name.
			if ( 'product' === $post_type ) {
				return $this->get_woocommerce_shop_page( $linked );
			}

			// Make sure that the Forums slug and link are correct for bbPress.
			if ( class_exists( 'bbPress' ) && 'topic' === $post_type ) {
				$archive_title = bbp_get_forum_archive_title();
				if ( $linked ) {
					$link = get_post_type_archive_link( bbp_get_forum_post_type() );
				}

				return $this->get_single_breadcrumb_markup( $archive_title, $link );
			}

			// Use its name as fallback.
			$archive_title = $post_type_object->name;
			// Default case. Check if the post type has a non empty label.
			if ( isset( $post_type_object->label ) && '' !== $post_type_object->label ) {
				$archive_title = $post_type_object->label;
			} elseif ( isset( $post_type_object->labels->menu_name ) && '' !== $post_type_object->labels->menu_name ) {
				// Alternatively check for a non empty menu name.
				$archive_title = $post_type_object->labels->menu_name;
			}
		}

		// Check if the breadcrumb should be linked.
		if ( $linked ) {
			$link = get_post_type_archive_link( $post_type );
		}

		return $this->get_single_breadcrumb_markup( $archive_title, $link );
	}

	/**
	 * Adds the markup of the woocommerce shop page.
	 *
	 * @param  bool $linked Linked or not linked.
	 * @return string The HTML markup of the woocommerce shop page.
	 */
	private function get_woocommerce_shop_page( $linked = true ) {
		global $wp_query;

		$post_type        = 'product';
		$post_type_object = get_post_type_object( $post_type );
		$shop_page_markup = '';
		$link             = '';

		// Make sure we are on a woocommerce page.
		if ( is_object( $post_type_object ) && class_exists( 'WooCommerce' ) && ( Avada_Helper::is_woocommerce() || is_cart() || is_checkout() || is_account_page() ) ) {
			// Get shop page id and then its name.
			$shop_page_name = wc_get_page_id( 'shop' ) ? get_the_title( wc_get_page_id( 'shop' ) ) : '';

			// Use the archive name if no shop page was set.
			if ( ! $shop_page_name ) {
				$shop_page_name = $post_type_object->labels->name;
			}

			// Check if the breadcrumb should be linked.
			if ( $linked ) {
				$link = get_post_type_archive_link( $post_type );
			}

			$separator = ! is_shop();
			$is_leaf = is_shop();

			if ( is_search() ) {
				$separator = true;
				$is_leaf = false;
			}
			$shop_page_markup = $this->get_single_breadcrumb_markup( $shop_page_name, $link, $separator, true, $is_leaf );
		}

		return $shop_page_markup;
	}

	/**
	 * Adds the markup of the bbpress main forum archive.
	 *
	 * @return string The HTML markup of the bbpress main forum archive.
	 */
	private function get_bbpress_main_archive_page() {
		global $wp_query;

		return $this->get_single_breadcrumb_markup( bbp_get_forum_archive_title(), get_post_type_archive_link( 'forum' ) );
	}

	/**
	 * Adds the markup of the breadcrumb leaf.
	 *
	 * @param  string $object_type    ID of the current query object.
	 *
	 * @return string 				    The HTML markup of the breadcrumb leaf.
	 */
	private function get_breadcrumb_leaf_markup( $object_type = '' ) {
		global $wp_query, $wp_locale;

		switch ( $object_type ) {
			case 'term':
				$term  = $wp_query->get_queried_object();
				$title = $term->name;
				break;
			case 'year':
				$year = esc_html( get_query_var( 'year', 0 ) );
				if ( ! $year ) {
					$year = substr( esc_html( get_query_var( 'm' ) ) , 0, 4 );
				}
				$title = $year;
				break;
			case 'month':
				$monthnum = get_query_var( 'monthnum', 0 );
				if ( ! $monthnum ) {
					$monthnum = substr( esc_html( get_query_var( 'm' ) ) , 4, 2 );
				}
				$title = $wp_locale->get_month( $monthnum );
				break;
			case 'day':
				$day = get_query_var( 'day' );
				if ( ! $day ) {
					$day = substr( esc_html( get_query_var( 'm' ) ) , 6, 2 );
				}
				$title = $day;
				break;
			case 'author':
				$user  = $wp_query->get_queried_object();
				if ( ! $user ) {
					$user = get_user_by( 'ID', $wp_query->query_vars['author'] );
				}
				$title = $user->display_name;
				break;
			case 'search':
				$title = $this->search_prefix . ' ' . esc_html( get_search_query() );
				break;
			case '404':
				$title = $this->error_prefix;
				break;
			case 'bbpress_search':
				$title = $this->search_prefix . ' ' . urldecode( esc_html( get_query_var( 'bbp_search' ) ) );
				break;
			case 'bbpress_user':
				$current_user_id = bbp_get_user_id( 0, true, false );
				$current_user 	 = get_userdata( $current_user_id );
				$title        	 = $current_user->display_name;
				break;
			case 'events':
				$title = tribe_get_events_title();
				break;
			default:
				$title = get_the_title( $this->post->ID );
				break;
		} // End switch().

		return '<span class="breadcrumb-leaf">' . $title . '</span>';
	}

	/**
	 * Adds the markup of a single breadcrumb.
	 *
	 * @access private
	 * @param string $title     The title that should be displayed.
	 * @param string $link      The URL of the breadcrumb.
	 * @param bool   $separator Set to TRUE to show the separator at the end of the breadcrumb.
	 * @param bool   $microdata Set to FALSE to make sure we get a link not being part of the breadcrumb microdata path.
	 * @param bool   $is_leaf   Set to TRUE to make sure leaf markup is added to the span.
	 * @return string           The HTML markup of a single breadcrumb.
	 */
	private function get_single_breadcrumb_markup( $title, $link = '', $separator = true, $microdata = true, $is_leaf = false ) {

		// Init vars.
		$microdata_itemscope = '';
		$microdata_url       = '';
		$microdata_title     = '';
		$separator_markup    = '';
		$leaf_markup         = '';

		// Setup the elements attributes for breadcrumb microdata rich snippets.
		if ( $microdata && Avada()->settings->get( 'disable_date_rich_snippet_pages' ) && Avada()->settings->get( 'disable_rich_snippet_title' ) ) {
			$microdata_itemscope = 'itemscope itemtype="http://data-vocabulary.org/Breadcrumb"';
			$microdata_url       = 'itemprop="url"';
			$microdata_title     = 'itemprop="title"';
		}

		if ( $is_leaf ) {
			$leaf_markup = ' class="breadcrumb-leaf"';
		}

		$breadcrumb_content = '<span ' . $microdata_title . $leaf_markup . '>' . $title . '</span>';

		// If a link is set add its markup.
		if ( $link ) {
			$breadcrumb_content = '<a ' . $microdata_url . ' href="' . $link . '">' . $breadcrumb_content . '</a>';
		}

		// If a separator should be added, do it.
		if ( $separator ) {
			$separator_markup = '<span class="fusion-breadcrumb-sep">' . $this->separator . '</span>';
		}

		return '<span ' . $microdata_itemscope . '>' . $breadcrumb_content . '</span>' . $separator_markup;
	}
}

/* Omit closing PHP tag to avoid "Headers already sent" issues. */
