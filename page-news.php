<?php
/**
 * The template for displaying last 4 news
 *
 */

get_header(); ?>

<div class="wrap">
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
		<h1>News</h1>
		<article>
		<?php
			$news_posts = wp_get_recent_posts( array( 'numberposts' => apply_filters( 'news-num-posts', '4' ) ), 'OBJECT' );
			foreach( $news_posts as $newspost ){
				?><section><?php
				printf( '<h2>%s</h2>', $newspost->post_title );
				echo apply_filters( 'the_content', $newspost->post_content );
				?></section><?php
			}
		?>
		</article>
		</main><!-- #main -->
	</div><!-- #primary -->
</div><!-- .wrap -->

<?php
get_footer();