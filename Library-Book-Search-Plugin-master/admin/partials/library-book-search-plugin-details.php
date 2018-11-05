<?php
/**
 * Plugin detail text
 *
 * @sing 1.0.0
 * @link       multidots.com
 * @since      1.0.0
 * @package    Library_Book_Search_Plugin
 * @subpackage Library_Book_Search_Plugin/admin/partials
 */

?>
<div class="wrap">
	<h1 class="wp-heading-inline">
		<?php esc_attr_e( 'Library Book Search Plugin', 'library-book-search-plugin' ); ?>
	</h1>
	<p>
		<?php esc_attr_e( 'This is a demo plugin for book search functionality. Where end users search books in the different parameter like Book Name, Author, and price etc..', 'library-book-search-plugin' ); ?>
	</p>
	<h2><?php esc_attr_e( 'ShortCode', 'library-book-search-plugin' ); ?></h2>
	<p>
		<h3><strong><?php esc_attr_e( '[library-book-search]', 'library-book-search-plugin' ); ?></strong></h3>
	</p>
	<p>
	<strong><?php echo esc_attr_e( "Using this shortcode in php file you can write like this <?php echo do_shortcode('[library-book-search]'); ?>", 'library-book-search-plugin' ); ?></strong>
	</p>
	<p>
		<?php esc_attr_e( 'Using this shortcode you can show library book search facility on page/post.', 'library-book-search-plugin' ); ?>
	</p>
</div>
