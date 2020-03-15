<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since 1.0
 * @version 1.0
 */

$post_id = get_the_ID();
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
		<?php //twentyseventeen_edit_link( $post_id ); ?>
		<?php
			//distribute images over the post or left on desktop
			$findLast = 1;
			for ($x=1; $x<=4; $x++) {
				$img_id = get_post_meta( $post_id, '_listing_image_id' . $x, true );
				if (empty($img_id)) break;
				if (($x>1)) $addClasses = ' hide-on-mobile';
					else $addClasses = '';
					// insert the image (medium sized)
				echo wp_get_attachment_image( $img_id, 'medium', '', array( 'class' => 'img-responsive' . $addClasses ) );
				$findLast++;
			}
		?>

	</header><!-- .entry-header -->
	<div class="entry-content">
		<?php
			the_content();
			// distribute images under the post
			for ($x=2; $x<=$findLast; $x++) {
				$img_id = get_post_meta( $post_id, '_listing_image_id' . $x, true );
				echo wp_get_attachment_image( $img_id, 'medium', '', array( 'class' => 'img-responsive hide-on-desktop' ) );
			}

			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'twentyseventeen' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->
</article><!-- #post-## -->
