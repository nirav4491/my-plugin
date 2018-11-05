<?php
/**
 * Book extra details field html
 *
 * @sing 1.0.0
 * @link       multidots.com
 * @since      1.0.0
 * @package    Library_Book_Search_Plugin
 * @subpackage Library_Book_Search_Plugin/admin/partials *
 */

$book_object = get_query_var( 'book_object' );

// Get book price from post meta.
$book_price = get_post_meta( $book_object->ID, '_book_price', true );

// Get book rating from post meta.
$book_rating = get_post_meta( $book_object->ID, '_book_rating', true );

$book_price  = ( $book_price ) ? $book_price : '0';
$book_rating = ( $book_rating ) ? $book_rating : '';

?>
<div class='inside'>
	<h3><?php esc_attr_e( 'Price', 'library-book-search-plugin' ); ?></h3>
	<p>
		<input type="number" class="book_price" name="book_price" value="<?php echo esc_attr( $book_price ); ?>"/>
	</p>

	<h3><?php esc_attr_e( 'Rating', 'library-book-search-plugin' ); ?></h3>
	<p>
		<select class="book_rating" name="book_rating">
			<?php
			for ( $i = 1; $i < 6; $i ++ ) {
				?>
				<option value="<?php echo esc_attr( $i ); ?>" <?php selected( $i, $book_rating ); ?> ><?php echo esc_html( $i ); ?> </option>
				<?php
			}
			?>
		</select>
	</p>
</div>
