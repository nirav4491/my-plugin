<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       multidots.com
 * @since      1.0.0
 * @package    Library_Book_Search_Plugin
 * @subpackage Library_Book_Search_Plugin/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Library_Book_Search_Plugin
 * @subpackage Library_Book_Search_Plugin/admin
 * @author     Nirav Mehta <nirmehta4491@gmail.com>
 */
class Library_Book_Search_Plugin_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since   1.0.0
	 * @access private
	 * @var    string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.0.0
	 *
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

		// Init add action for create book custom post type.
		add_action( 'init', array( $this, 'create_book_post_type' ) );

		// Init add action for create author and publisher taxonomies.
		add_action( 'init', array( $this, 'create_book_taxonomies' ) );

		// Add action add_meta_boxes  for create meta box in book post edit page.
		add_action( 'add_meta_boxes', array( $this, 'create_book_details_meta_box' ) );

		// Init add action for create author and publisher taxonomies.
		add_action( 'save_post_book', array( $this, 'save_book_details_values' ) );

		// Admin_menu action for create menu admin side.
		add_action( 'admin_menu', array( $this, 'lbs_detail_page' ) );

	}

	/**
	 * Creating a function to create our Book post type.
	 *
	 * @since  1.0.0
	 */
	public function create_book_post_type() {

		$labels = array(
			'name'               => _x( 'Books', 'Post Type General Name', 'library-book-search-plugin' ),
			'singular_name'      => _x( 'Book', 'Post Type Singular Name', 'library-book-search-plugin' ),
			'menu_name'          => __( 'Books', 'library-book-search-plugin' ),
			'parent_item_colon'  => __( 'Parent Book', 'library-book-search-plugin' ),
			'all_items'          => __( 'All Books', 'library-book-search-plugin' ),
			'view_item'          => __( 'View Book', 'library-book-search-plugin' ),
			'add_new_item'       => __( 'Add New Book', 'library-book-search-plugin' ),
			'add_new'            => __( 'Add New', 'library-book-search-plugin' ),
			'edit_item'          => __( 'Edit Book', 'library-book-search-plugin' ),
			'update_item'        => __( 'Update Book', 'library-book-search-plugin' ),
			'search_items'       => __( 'Search Book', 'library-book-search-plugin' ),
			'not_found'          => __( 'Not Found', 'library-book-search-plugin' ),
			'not_found_in_trash' => __( 'Not found in Trash', 'library-book-search-plugin' ),
		);

		$args = array(
			'label'               => __( 'Books', 'library-book-search-plugin' ),
			'description'         => __( 'Book news and reviews', 'library-book-search-plugin' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'revisions', 'thumbnail' ),
			'taxonomies'          => array( 'publishers', 'author' ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => 5,
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'capability_type'     => 'page',
		);

		/*
		 * Register book post type.
		 */
		register_post_type( 'book', $args );

	}

	/**
	 * Creating a function to create our taxonomies for book.
	 *
	 * @since  1.0.0
	 */
	public function create_book_taxonomies() {

		$labels = array(
			'name'              => _x( 'Publishers', 'taxonomy general name', 'library-book-search-plugin' ),
			'singular_name'     => _x( 'publisher', 'taxonomy singular name', 'library-book-search-plugin' ),
			'search_items'      => __( 'Search publishers', 'library-book-search-plugin' ),
			'all_items'         => __( 'All publishers', 'library-book-search-plugin' ),
			'parent_item'       => __( 'Parent publisher', 'library-book-search-plugin' ),
			'parent_item_colon' => __( 'Parent publisher:', 'library-book-search-plugin' ),
			'edit_item'         => __( 'Edit publisher', 'library-book-search-plugin' ),
			'update_item'       => __( 'Update publisher', 'library-book-search-plugin' ),
			'add_new_item'      => __( 'Add New publisher', 'library-book-search-plugin' ),
			'new_item_name'     => __( 'New publisher Name', 'library-book-search-plugin' ),
			'menu_name'         => __( 'publisher', 'library-book-search-plugin' ),
		);

		$args = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'publisher' ),
		);

		register_taxonomy( 'publisher', array( 'book' ), $args );

		$labels = array(
			'name'              => _x( 'Authors', 'taxonomy general name', 'library-book-search-plugin' ),
			'singular_name'     => _x( 'Author', 'taxonomy singular name', 'library-book-search-plugin' ),
			'search_items'      => __( 'Search Authors', 'library-book-search-plugin' ),
			'all_items'         => __( 'All Authors', 'library-book-search-plugin' ),
			'parent_item'       => __( 'Parent Author', 'library-book-search-plugin' ),
			'parent_item_colon' => __( 'Parent Author:', 'library-book-search-plugin' ),
			'edit_item'         => __( 'Edit Author', 'library-book-search-plugin' ),
			'update_item'       => __( 'Update Author', 'library-book-search-plugin' ),
			'add_new_item'      => __( 'Add New Author', 'library-book-search-plugin' ),
			'new_item_name'     => __( 'New Author Name', 'library-book-search-plugin' ),
			'menu_name'         => __( 'Author', 'library-book-search-plugin' ),
		);

		$args = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'author' ),
		);

		register_taxonomy( 'author', array( 'book' ), $args );
	}

	/**
	 * Creating a function to create meta box in book edit page.
	 *
	 * @since  1.0.0
	 */
	public function create_book_details_meta_box() {

		add_meta_box( 'book_details', __( 'Book Details', 'library-book-search-plugin' ),
			array(
				$this,
				'create_book_details_field',
			),
		'book', 'normal', 'high');

	}

	/**
	 * Save book price and rating details values.
	 *
	 * @since  1.0.0
	 *
	 * @param object $book This object of Book.
	 */
	public function create_book_details_field( $book ) {

		wp_nonce_field( basename( __FILE__ ), 'book_details' );
		set_query_var( 'book_object', $book );
		include 'partials/book-details-extra-fields.php';
	}

	/**
	 * Save book price and rating details values.
	 *
	 * @since    1.0.0
	 *
	 * @param integer $post_id This ID of Book.
	 */
	public function save_book_details_values( $post_id ) {

		$book_price   = filter_input( INPUT_POST, 'book_price', FILTER_VALIDATE_INT );
		$book_rating  = filter_input( INPUT_POST, 'book_rating', FILTER_VALIDATE_INT );
		$book_details = filter_input( INPUT_POST, 'book_details', FILTER_SANITIZE_STRING );

		// Verify taxonomies meta box nonce.
		if ( ! isset( $book_details ) || ! wp_verify_nonce( $book_details, basename( __FILE__ ) ) ) {
			return;
		}
		// Return if auto save.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		// Check the user's permissions.
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		if ( empty( $book_price ) ) {
			$book_price = 0;
		}

		update_post_meta( $post_id, '_book_price', $book_price );
		update_post_meta( $post_id, '_book_rating', $book_rating );
	}

	/**
	 * Create the function for add Library Book Search menu admin side.
	 *
	 * @since  1.0.0
	 */
	public function lbs_detail_page() {
		add_menu_page( 'Library Book Search', 'Library Book Search', 'manage_options', 'library-book-search-plugin',
			array(
				$this,
				'lbs_detail_text',
			)
		);
	}

	/**
	 * Create the function for display plugin details on admin side.
	 * Callback function for add_menu_page
	 *
	 * @since  1.0.0
	 */
	public function lbs_detail_text() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html( 'You do not have sufficient permissions to access this page.', 'library-book-search-plugin' ) );
		}
		include 'partials/library-book-search-plugin-details.php';
	}

}
