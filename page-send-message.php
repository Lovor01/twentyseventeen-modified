<?php
/**
 * The template for processing form
 * 
 * Napravio Lovro
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen_child
 * @since 1.0
 * @version 1.0
 */

/*process form
define('WP_USE_THEMES', true);*/

/** Loads the WordPress Environment and Template 
requireonce ('/wp-blog-header.php');*/

function performloop() {
	while ( have_posts() ) : the_post();

		get_template_part( 'template-parts/page/content', 'page' );

		// If comments are open or we have at least one comment, load up the comment template.
		if ( comments_open() || get_comments_number() ) :
			comments_template();
		endif;

	endwhile; // End of the loop.
}


$formsent = False;

 if ( !empty ($_POST ) ) {
		$formsent = True;
		
}


get_header(); ?>

<div class="wrap">
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<?php
			if (!$formsent) {
				performloop();
			} else {
			
				// sanitize form values
				$ime    = sanitize_text_field( $_POST["ime"] );
				$prezime = sanitize_text_field( $_POST["prezime"] );
				$email   = sanitize_email( $_POST["email"] );
				$poruka = sanitize_text_field( $_POST["poruka"] );

				$response = $_POST["g-recaptcha-response"];

				$url = 'https://www.google.com/recaptcha/api/siteverify';
				$data = array(
					'secret' => '6Lc_VUAUAAAAAI0Ccz24y3Z7oWO6zm1rhRSemCt0',
					'response' => $_POST["g-recaptcha-response"]
				);
				$options = array(
					'http' => array (
						'header' => "Content-type: application/x-www-form-urlencoded\r\n",
						'method' => 'POST',
						'content' => http_build_query($data)
					)
				);
				$context  = stream_context_create($options);
				$verify = file_get_contents($url, false, $context);
				$captcha_success=json_decode($verify);
			
				if ($captcha_success->success==true) {
					if (!empty($ime) && !empty($prezime) && !empty($email) && !empty($poruka)) {

						// get the blog administrator's email address
						$to = get_option( 'admin_email' );
						$headers = "From: $ime $prezime <$email>" . "\r\n";
	
						// If email has been processed for sending, display a success message
						wp_mail( $to, 'nova poruka sa stranice Geopair', $poruka, $headers );
						if ($wpdb->insert('contact_form', array(
								"ime"=>$ime,
								"prezime"=>$prezime,
								"email"=>$email,
								"poruka"=>$poruka),
							'%s') === false) {
								echo '<p>An error saving form to database has ocurred!</p>';
						}
						echo '<p>Message successfully sent!</p>';
					} else {
						performloop();
						echo __('<br/><p>Please fill all fields!</p>', 'twentyseventeen-child');
					}	// empty test
	
				} else if ($captcha_success->success==true) {
					echo '<p>reCaptcha validation failed!</p><p style="color:red;">Data is not sent!</p>';
				}
			}
			?>

		</main><!-- #main -->
	</div><!-- #primary -->
</div><!-- .wrap -->

<?php get_footer();
