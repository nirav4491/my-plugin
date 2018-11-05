<?php
/**
 * Book Details HTML
 *
 * @sing 1.0.0
 * @link       multidots.com
 * @since      1.0.0
 * @package    Library_Book_Search_Plugin
 * @subpackage Library_Book_Search_Plugin/public/partials
 */

$book_array  = get_query_var( 'book_array',
	array(
		'book_id' => 0,
		'book_no' => 0,
	)
);
$book_price  = get_post_meta( $book_array['book_id'], '_book_price', true );
$book_rating = get_post_meta( $book_array['book_id'], '_book_rating', true );
$author      = wp_get_post_terms( $book_array['book_id'], 'author', array( 'fields' => 'names' ) );
$publisher   = wp_get_post_terms( $book_array['book_id'], 'publisher', array( 'fields' => 'names' ) );
$book_rating = ( ( $book_rating * 100 ) / 5 );
if ( empty( $book_price ) ) {
	$book_price = 0;
}
?>
<tr>
	<td>
		<?php echo esc_html( $book_array['book_no'] ); ?>
	</td>
	<td>
		<a href="<?php echo esc_attr( get_permalink( $book_array['book_id'] ) ); ?>">
			<?php echo get_the_title( $book_array['book_id'] ); ?>
		</a>
	</td>
	<td>
		<?php echo esc_html( '$' . $book_price ); ?>
	</td>
	<td>
		<?php echo esc_html( implode( ', ', $author ) ); ?>
	</td>
	<td>
		<?php echo esc_html( implode( ', ', $publisher ) ); ?>
	</td>
	<td>
		<div class="inner-rating">
			<div class="star-rating">
				<span style="width:<?php echo esc_attr( $book_rating ); ?>%"></span>
			</div>
		</div>
	</td>
</tr>
