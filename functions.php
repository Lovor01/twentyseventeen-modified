<?php
//twentyseventeen-child functions

add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );
function my_theme_enqueue_styles() {
 
		//wp_enqueue_script( 'lc-appear',	get_stylesheet_directory_uri() . '/assets/js/appear.js', '', array(), false);
		//wp_enqueue_script( 'lc-effects',	get_stylesheet_directory_uri() . '/assets/js/lc_effects.js', '', array(), false);


		$parent_style	=	'parent-style';


		wp_enqueue_style($parent_style, get_template_directory_uri() . '/style.css');
		//wp_enqueue_style(	'child-style',get_stylesheet_directory_uri() . '/style.css',array( $parent_style ),	wp_get_theme()->get('Version'));
		
		if ( is_page( 157 ) ) {
			// wp_enqueue_script( 'reCaptcha', 'https://www.google.com/recaptcha/api.js' , array(), false, false);
			add_action('wp_head', function() {
				?>
				<script src="https://www.google.com/recaptcha/api.js" async defer></script>
				<script>
				  function onSubmit(token) {
					document.getElementsByTagName("form")[0].submit();
				  }
				</script><?php
			});
		}

		
		// enqueue aos
		wp_enqueue_script( 'aos-js', get_stylesheet_directory_uri() . '/assets/js/aos.js', '', array(), false);
		wp_enqueue_style( 'aos-css', get_stylesheet_directory_uri() . '/assets/css/aos.css' );

		if (!is_front_page(  ))
			wp_enqueue_script( 'typewriter', get_stylesheet_directory_uri() . '/assets/js/mytypewriter.js', '', array(), false);
}

// povećaj broj sekcija u temi
add_filter( 'twentyseventeen_front_page_sections', 'prefix_custom_front_page_sections' );
function prefix_custom_front_page_sections( $num_sections )
{
	return 4;
}

// change proudly powered

// add id of the panel (and class??) to menu
function childtheme_theme_menu_class($atts, $item, $args, $depth) {
static $panelno = 0;

	if( is_array( $atts ) && ($depth == 0) ) {
		if ($panelno != 0) {
			//$atts['class'] = 'nav-menu-scroll-down';

			if (preg_match('~[/]([^/]*)[/]$~U', $atts['href'], $matches, PREG_OFFSET_CAPTURE, 0) == 1) {
				if ($item->title <> 'News')
					$atts['href'] = (substr ( $atts['href'] , 0 , $matches[1][1] ) . '#panel' . $panelno);
			}
				$panelno++;
		}
		else { $panelno = 1; }
	}
	 
	return $atts;
}
add_filter('nav_menu_link_attributes','childtheme_theme_menu_class', 0,4);

// promijeni veličinu header slike
function my_header_filter( $default ) {
	$mydefault = array(
		'width'              => 2000,
		'height'             => 1280,
	);
	$default = array_replace($default, $mydefault);
	return $default;
} 
add_filter('twentyseventeen_custom_header_args','my_header_filter');

// change header image
function my_setup(){
	remove_image_size( 'twentyseventeen-featured-image' );
	// add_image_size( 'twentyseventeen-featured-image', 2000, 1280, true );
}
add_action( 'after_setup_theme', 'my_setup', 11 );


/* add_filter( 'twentyseventeen_custom_colors_saturation', function($saturation) {
	return 100;
} );
 */

/* add_filter( 'twentyseventeen_custom_colors_css', function($css, $hue, $saturation) {
	$css='';
	$hue=19;
	$saturation=100;
} );
 */

/**
 *  image meta box
 **/
add_action( 'add_meta_boxes', 'listing_image_add_metabox' );
function listing_image_add_metabox () {
	add_meta_box( 'sideImageMeta', __( 'Slike na lijevoj strani', 'twenty-seventeenth-child' ), 'listing_image_metabox', 'page', 'normal', 'low');
}

// prepare HTML for image metaBOX
function metaImageHTML($meta_ID, $post) {
	global $content_width, $_wp_additional_image_sizes;
	static $num = 1;
	$image_id = get_post_meta( $post->ID, $meta_ID.$num, true );

	$old_content_width = $content_width;
	$content_width = 254;
	$content = '<div>';

	if ( $image_id && get_post( $image_id ) ) {
		// thumbnail already set
		if ( ! isset( $_wp_additional_image_sizes['post-thumbnail'] ) ) {
			$thumbnail_html = wp_get_attachment_image( $image_id, array( $content_width, $content_width ) );
		} else {
			$thumbnail_html = wp_get_attachment_image( $image_id, 'post-thumbnail' );
		}
	// input hidden u value sadržava id od slike i to se prenosi u bazu

		if ( ! empty( $thumbnail_html ) ) {
			$content .= $thumbnail_html .
				'<p class="hide-if-no-js"><a href="javascript:;" class="remove_listing_image_button" >' . esc_html__( 'Remove image', 'twenty-seventeenth-child' ) . '</a></p>' .
				'<input type="hidden" class="upload_listing_image" name="_listing_cover_image' . $num . '" value="' . esc_attr( $image_id ) . '" />';
		} else {
			delete_post_meta( $post->ID, $meta_ID.$num );
		}

		$content_width = $old_content_width;
	} else {
		// thumbnail not set
		$content .= '<img src="" style="width:' . esc_attr( $content_width ) . 'px;height:auto;border:0;display:none;" />' .
			'<p class="hide-if-no-js"><a title="' . esc_attr__( 'Set image', 'twenty-seventeenth-child' ) . '" href="javascript:;" class="upload_listing_image_button" data-uploader_title="' . esc_attr__( 'Choose an image', 'text-domain' ) . '" data-uploader_button_text="' . esc_attr__( 'Set image', 'text-domain' ) . '">' . esc_html__( 'Set image', 'text-domain' ) . ' ' . $num . '</a></p>'.
			'<input type="hidden" class="upload_listing_image" name="_listing_cover_image' . $num . '" value="" />';

	}
	$content .= '</div>';
	$num++;
	return $content;
}

function listing_image_metabox ( $post ) {

	$content = metaImageHTML('_listing_image_id', $post) .
		'<hr />' .
		metaImageHTML('_listing_image_id', $post) .
		'<hr />' .
		metaImageHTML('_listing_image_id', $post) .
		'<hr />' .
		metaImageHTML('_listing_image_id', $post);

	echo $content;
}

add_action( 'save_post', 'listing_image_save', 10, 1 );
function listing_image_save ( $post_id ) {
	for ($x=1; $x<=4; $x++)
		if( isset( $_POST['_listing_cover_image'.$x] ) ) {
			$image_id = (int) $_POST['_listing_cover_image'.$x];
			if ($image_id == 0) 
				delete_post_meta( $post_id, '_listing_image_id'.$x );
			else 
				update_post_meta( $post_id, '_listing_image_id'.$x, $image_id );
		};
}

//enqueue script
function add_admin_scripts( $hook ) {

	global $post;
	
	if ( $hook == 'post-new.php' || $hook == 'post.php' ) {
		if ( 'page' === $post->post_type ) {
		wp_enqueue_script( 'imageMetaScript', get_stylesheet_directory_uri().'/assets/js/admin_image_metabox.js' );
		}
	}
}
add_action( 'admin_enqueue_scripts', 'add_admin_scripts', 10, 1 );

//**** end of image metabox */

/****
 * impossible to set like this
 *  function change_customizer($wp_customize) {
	$wp_customize->get_control('colorscheme_hue')->mode = 'full';
}
add_action( 'customize_register', 'change_customizer', 11 ); */

function themeslug_remove_hentry( $classes ) {
	if ( is_page() ) {
		$classes = array_diff( $classes, array( 'hentry' ) );
	}
	return $classes;
}
add_filter( 'post_class','themeslug_remove_hentry' );

/***
 * custom header image tag
 */

function header_image_markup($html, $header, $attr) {
	return '<img src="https://www.thegeopair.com/wp-content/uploads/extra/naslovna1_optimized.jpg" width="2112" height="1366" alt="Geopair" srcset="https://www.thegeopair.com/wp-content/uploads/extra/naslovna1_optimized.jpg 2112w, https://www.thegeopair.com/wp-content/uploads/extra/naslovna1_optimized-300x194.jpg 300w, https://www.thegeopair.com/wp-content/uploads/extra/naslovna1_optimized-768x497.jpg 768w, https://www.thegeopair.com/wp-content/uploads/extra/naslovna1_optimized-1024x662.jpg 1024w" sizes="100vw" />';
}

add_filter('get_header_image_tag', 'header_image_markup', 20, 3);

/***
 * add meta tags in header
 */

add_action( 'wp_head', function() {?>
	<meta name="keywords" content="seismic data services, seismic tape transcription, seismic data subsetting, seismic geometry merge, seismic multiple 3D survey merging" />
	<meta name="description" content="Geopair provides seismic data services, seismic tape transcription, seismic data subsetting, seismic geometry merge and seismic multiple 3D survey merging." /> <?php
	});

// yoast disable transient caching
add_filter('wpseo_enable_xml_sitemap_transient_caching', '__return_false');

// activate translations

	add_action( 'after_setup_theme', function () {
		load_theme_textdomain( 'twentyseventeen-child', get_stylesheet_directory() . '/languages' );
		} );