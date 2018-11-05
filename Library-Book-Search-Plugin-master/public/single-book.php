<?php
/**
 * Single Book Details HTML
 *
 * @link       multidots.com
 * @since      1.0.0
 * @package    Library_Book_Search_Plugin
 * @subpackage Library_Book_Search_Plugin/public
 */

get_header(); ?>
<div class="wrap">
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
		<?php
		while ( have_posts() ) {
			the_post();
			$book_id     = get_the_ID();
			$book_price  = get_post_meta( $book_id, '_book_price', true );
			$book_rating = get_post_meta( $book_id, '_book_rating', true );
			$author      = wp_get_post_terms( $book_id, 'author', array( 'fields' => 'names' ) );
			$publisher   = wp_get_post_terms( $book_id, 'publisher', array( 'fields' => 'names' ) );
			$book_rating = ( ( $book_rating * 100 ) / 5 );
			if ( empty( $book_price ) ) {
				$book_price = 0;
			}
			?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<div class="book-image">
					<?php the_post_thumbnail( 'medium' ); ?>
				</div>
				<div class="book-content">
					<header class="book-header">
						<h1 class="book-title"><?php echo get_the_title(); ?></h1>
						<ul class="book-meta-details">
							<li class="book-price">
								<label> <?php echo esc_html( 'Price: ' ); ?> </label>
								<span> <?php echo esc_html( '$' . $book_price ); ?> </span>
							</li>
							<li class="book-rating">
								<div class="inner-rating">
									<div class="star-rating">
										<span style="width:<?php echo esc_attr( $book_rating ); ?>%"></span>
									</div>
								</div>
							</li>
						</ul>
					</header>
					<div class="clear-both"></div>
					<div class="book-details">
						<?php the_content(); ?>
					</div>
					<footer class="book-footer">
						<ul class="book-meta-details">
							<?php if ( $author ) { ?>
							<li class="book-author">
								<label><?php echo esc_html( 'Author: ' ); ?></label>
								<span><?php echo esc_html( implode( ', ', $author ) ); ?></span>
							</li>
							<?php } ?>
							<?php if ( $publisher ) { ?>
							<li class="class-publisher"><label><?php echo esc_html( 'publisher: ' ); ?></label>
								<span><?php echo esc_html( implode( ', ', $publisher ) ); ?></span>
							</li>
							<?php } ?>
						</ul>
					</footer>
					<div class="clear-both"></div>
				</div>
				<div class="clear-both"></div>
			</article>
		<?php
		}
		?>
		</main>
	</div>
<?php get_sidebar(); ?>
</div>
<?php get_footer(); ?>
