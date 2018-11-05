<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       multidots.com
 * @since      1.0.0
 *
 * @package    Library_Book_Search_Plugin
 * @subpackage Library_Book_Search_Plugin/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Library_Book_Search_Plugin
 * @subpackage Library_Book_Search_Plugin/public
 * @author     Nirav Mehta <nirmehta4491@gmail.com>
 */
class Library_Book_Search_Plugin_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    string $plugin_name The ID of this plugin.
	 */
	private static $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    string $version The current version of this plugin.
	 */
	private static $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.0.0
	 *
	 * @param string $plugin_name The name of the plugin.
	 * @param string $version The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		Library_Book_Search_Plugin_Public::$plugin_name = $plugin_name;
		Library_Book_Search_Plugin_Public::$version     = $version;

		/**
		 * Add action for load script and style files on front side.
		 */
		add_action( 'wp_enqueue_scripts', 'Library_Book_Search_Plugin_Public::enqueue_styles' );

		add_action( 'wp_enqueue_scripts', 'Library_Book_Search_Plugin_Public::enqueue_scripts' );

		/**
		 * Add action ajax for get search data on front side.
		 */
		add_action( 'wp_ajax_search_book', 'Library_Book_Search_Plugin_Public::get_search_book_list' );

		add_action( 'wp_ajax_nopriv_search_book', 'Library_Book_Search_Plugin_Public::get_search_book_list' );

		/**
		 * Add action for book detail update time delete cache data.
		 */
		add_action( 'save_post', 'Library_Book_Search_Plugin_Public::refresh_books_cache', 10, 1 );

		add_action( 'delete_post', 'Library_Book_Search_Plugin_Public::refresh_books_cache', 10, 1 );

		/**
		 * Add Filter for show book detail on single page.
		 */
		add_filter( 'single_template', 'Library_Book_Search_Plugin_Public::single_book_template' );

		/**
		 * Add short-code for show book search facility on page/post.
		 */
		add_shortcode( 'library-book-search', 'Library_Book_Search_Plugin_Public::book_search_list_shortcode' );
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since  1.0.0
	 */
	public static function enqueue_styles() {

		/**
		 * Load Front side css
		 */
		wp_enqueue_style(
			Library_Book_Search_Plugin_Public::$plugin_name,
			plugin_dir_url( __FILE__ ) . 'css/library-book-search-plugin-public.css',
			array(),
			Library_Book_Search_Plugin_Public::$version
		);

		/**
		 * Load Front Side Jquery UI css
		 */
		wp_enqueue_style(
			Library_Book_Search_Plugin_Public::$plugin_name . '-ui',
			plugin_dir_url( __FILE__ ) . 'css/jquery-ui.css',
			array(),
			Library_Book_Search_Plugin_Public::$version
		);
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since  1.0.0
	 */
	public static function enqueue_scripts() {

		// Load front side Jquery UI js.
		wp_enqueue_script( 'jquery-ui-slider' );

		// Load Front side Plugin javascript.
		wp_enqueue_script(
			Library_Book_Search_Plugin_Public::$plugin_name,
			plugin_dir_url( __FILE__ ) . 'js/library-book-search-plugin-public.js',
			array( 'jquery', 'wp-util' ),
			Library_Book_Search_Plugin_Public::$version,
			true
		);

	}

	/**
	 * Register the short-code for the display book search form and list of the site.
	 *
	 * @since  1.0.0
	 * @return string
	 */
	public static function book_search_list_shortcode() {

		$book_lists  = Library_Book_Search_Plugin_Public::get_search_book_list( '', '', '', '', '', 1 );
		$book_prices = Library_Book_Search_Plugin_Public::get_books_price();

		set_query_var( 'book_search_data', $book_prices );

		/**
		 * Get book search HTML data.
		 */
		ob_start();

		include 'partials/book-list-table.php';

		$book_list_html = ob_get_contents();

		ob_clean();

		return $book_list_html;

	}

	/**
	 * Create the function for the return book search list data of the site.
	 *
	 * @since  1.0.0
	 *
	 * @param   string  $publisher This is publisher name.
	 * @param   string  $author This is author name.
	 * @param   string  $book_title This is book title.
	 * @param   array   $price This is price of book.
	 * @param   integer $rating This is rating of book.
	 * @param   integer $paged This is number of page.
	 *
	 * @return string
	 */
	public static function get_search_book_list( $publisher, $author, $book_title, $price, $rating, $paged ) {

		$book_data  = filter_input( INPUT_POST, 'security', FILTER_VALIDATE_INT );
		$book_title = filter_input( INPUT_POST, 'book_title', FILTER_SANITIZE_STRING );
		$author     = filter_input( INPUT_POST, 'author', FILTER_SANITIZE_STRING );
		$publisher  = filter_input( INPUT_POST, 'publisher', FILTER_SANITIZE_STRING );
		$rating     = filter_input( INPUT_POST, 'rating', FILTER_VALIDATE_INT );
		$paged      = filter_input( INPUT_POST, 'paged', FILTER_VALIDATE_INT );
		$price_min  = filter_input( INPUT_POST, 'price_min', FILTER_VALIDATE_INT );
		$price_max  = filter_input( INPUT_POST, 'price_max', FILTER_VALIDATE_INT );

		$book_no        = 0;
		$book_list_data = '';
		if ( $paged > 1 ) {
			$book_no = ( $paged - 1 ) * get_option( 'posts_per_page', 10 );
		}

		if ( isset( $book_data ) ) {
			if ( ! empty( $price_min ) && ! empty( $price_max ) ) {
				$price = array( $price_min, $price_max );
			} else {
				$price = '';
			}
		}

		$book_parameters = array(
			'post_type' => 'book',
			'paged'     => $paged,
		);

		$book_parameters['meta_query'][] = array(
			'key'     => '_book_price',
			'value'   => '0',
			'type'    => 'numeric',
			'compare' => '!=',
		);

		if ( $book_title ) {
			$book_parameters['s'] = $book_title;
		}

		if ( $publisher ) {
			$book_parameters['tax_query'] = array(
				array(
					'taxonomy' => 'publisher',
					'field'    => 'slug',
					'terms'    => $publisher,
				),
			); // WPCS: slow query ok. Used wp object cache.
		}

		if ( $author ) {
			$book_parameters['tax_query'] = array(
				array(
					'taxonomy' => 'author',
					'field'    => 'slug',
					'terms'    => $author,
				),
			); // WPCS: slow query ok. Used wp object cache.
		}

		if ( $price ) {
			$book_parameters['meta_query'][] = array(
				'key'     => '_book_price',
				'value'   => array( $price[0], $price[1] ),
				'type'    => 'numeric',
				'compare' => 'BETWEEN',
			);
		}

		if ( $rating ) {
			$book_parameters['meta_query'][] = array(
				'key'     => '_book_rating',
				'value'   => $rating,
				'type'    => 'numeric',
				'compare' => '=',
			);
		}

		$price_name = 'pricemin0pricemax0author';
		if ( $price ) {
			$price_name = "pricemin{$price[0]}pricemax{$price[1]}";
		}

		$book_cache_lists = "paged{$paged}rating{$rating}{$price_name}{$author}publisher{$publisher}title{$book_title}";

		$book_lists = wp_cache_get( $book_cache_lists, 'lbs_book_lists' );

		if ( ! $book_lists ) {

			$book_lists = new WP_Query( $book_parameters );

			if ( ! is_wp_error( $book_lists ) && $book_lists->have_posts() ) {
				// Cache the whole WP_Query object in the object cache and store it for 1 hour.
				wp_cache_set( $book_cache_lists, $book_lists, 'lbs_book_lists', 60 * MINUTE_IN_SECONDS );
			}
		}

		// Get book list HTML data.
		ob_start();

		if ( $book_lists->have_posts() ) {
			while ( $book_lists->have_posts() ) {
				$book_lists->the_post();
				$book_id = get_the_ID();
				set_query_var( 'book_array',
					array(
						'book_id' => $book_id,
						'book_no' => ++ $book_no,
					)
				);
				include 'partials/book-list-details.php';
			}

			echo wp_kses_post( $book_list_data );

			// Include book pagination html on page.
			include 'partials/book-pagination.php';

		} else {
			// Include book not found html on page.
			include 'partials/book-not-found.php';
		}

		$book_list_data = ob_get_contents();
		ob_clean();

		if ( ! empty( $book_data ) ) {

			// Return book json list data.
			wp_send_json_success( array( 'book_list' => $book_list_data ) );

		} else {

			// Return book list data.
			return $book_list_data;

		}

		wp_send_json_error();
	}

	/**
	 * Create the function for the return all price array.
	 *
	 * @since  1.0.0
	 *
	 * @return array
	 */
	public static function get_books_price() {

		$prices = wp_cache_get( 'book_price', 'lbs_book_price' );

		if ( ! $prices ) {
			$pricing_data = new WP_Query( array(
				'post_type'      => 'book',
				'posts_per_page' => - 1,
				'meta_key'       => '_book_price',
				'meta_value'     => '0',
				'meta_compare'   => '!=',
			) ); // WPCS: slow query ok. Used wp object cache.

			$prices = array();
			if ( $pricing_data->have_posts() ) {
				while ( $pricing_data->have_posts() ) {
					$pricing_data->the_post();
					$book_id            = get_the_ID();
					$book_price         = get_post_meta( $book_id, '_book_price', true );
					$prices[ $book_id ] = $book_price;
				}
			}

			wp_cache_set( 'book_price', $prices, 'lbs_book_price', 60 * MINUTE_IN_SECONDS );
		}

		if ( count( $prices ) > 0 ) {
			$metas = array(
				'max_price' => max( $prices ),
				'min_price' => min( $prices ),
			);
		} else {
			$metas = array(
				'max_price' => 0,
				'min_price' => 0,
			);
		}

		return $metas;

	}


	/**
	 * Handles saving the meta box.
	 *
	 * @link    https://10up.github.io/Engineering-Best-Practices/php/
	 *
	 * @since   1.0.0
	 *
	 * @param   int $post_id Post ID.
	 */
	public function refresh_books_cache( $post_id ) {
		if ( 'book' === get_post_type( $post_id ) ) {
			wp_cache_delete( 'book_price', 'lbs_book_price' );
		}
	}

	/**
	 * Create the function for the return all price array.
	 *
	 * @since  1.0.0
	 *
	 * @param  string $single This is template name.
	 *
	 * @return string
	 */
	public static function single_book_template( $single ) {
		global $wp_query, $post;

		/* Checks for single template by post type */
		if ( 'book' === $post->post_type ) {
			if ( file_exists( plugin_dir_path( __FILE__ ) . '/single-book.php' ) ) {
				return plugin_dir_path( __FILE__ ) . '/single-book.php';
			}
		}

		return $single;
	}
}
