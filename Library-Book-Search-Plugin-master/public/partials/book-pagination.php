<?php
/**
 * Book List Pagination HTML
 *
 * @link       multidots.com
 * @since      1.0.0
 * @package    Library_Book_Search_Plugin
 * @subpackage Library_Book_Search_Plugin/public/partials
 */

?>
<tr>
	<td colspan="6" class="book-pagination">
		<?php
		$big_range = 999999999;
		$paginate  = paginate_links(
			array(
				'base'    => str_replace( $big_range, '%#%', '%#%' ),
				'format'  => '%#%',
				'current' => max( 1, $paged ),
				'total'   => $book_lists->max_num_pages,
			)
		);

		echo wp_kses_post( $paginate );
		?>
	</td>
</tr>
