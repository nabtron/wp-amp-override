<?php

/**
 * Plugin Name: WP AMP Override
 * Description: Plugin to override few settings & templates of official WordPress AMP plugin
 * Version:     0.0.2
 * Author:      nabtron
 * Author URI:  https://nabtron.com
 * License:     Private
 *
 * @package amp-override
 */

 /**
  * Remove google fonts from amp page
  */
add_filter( 'amp_post_template_data', function( $data ) {
	$data['font_urls'] = array();
	return $data;
} );

/**
 * Add Georgia as main body font
 */
add_action( 'amp_post_template_css', function() {
	echo 'body { font-family: Georgia, "Times New Roman", Times, Serif; }';
} );

/**
 * Change the content images links to to wp cdn
 */
function custom_wp_cdn($html) {
	$name = parse_url(get_option( 'siteurl' ));
	$local = array('127.0.0.1','localhost');
	if( in_array($name['host'], $local) ){
		return $html;
	}
	//$pattern = '/(\/\/)(127\.0\.0\.1[^\.]*?\.(?:jpe?g|png|gif|bmp))/i';
	$pattern = '/(\/\/)(('.$name['host'].')[^\.]*?\.(?:jpe?g|png|gif|bmp))/i';
	//$replacement = "$1i".rand(0,2).".wp.com/$2";
	$replacement = "$1i1.wp.com/$2";
	
	$html = preg_replace($pattern,$replacement,$html);
//	$html = str_replace('i',$name['host'],$html);
	return $html;
}
//add_filter( 'the_content', 'custom_wp_cdn' );

/**
 * Custom templates for AMP frontend
 */
add_filter( 'amp_post_template_file', 'custom_amp_set_custom_template', 10, 3 );
function custom_amp_set_custom_template( $template, $template_type, $post ) {
    if ( 'footer' === $template_type ) {
        // removed powered by wordpress
        $template = dirname( __FILE__ ) . '/templates/footer.php';
    }
	if ( 'meta-author' === $template_type ) {
        // remove gravatar to speed up loading
	    $template = dirname( __FILE__ ) . '/templates/meta-author.php';
	}
  return $template;
}

/**
 * Output custom CSS to AMP frontend
 * Change the placeholder image to base64 to prevent a callback
 */
add_action( 'amp_post_template_css', 'custom_amp_my_additional_css_styles' );
function custom_amp_my_additional_css_styles( $amp_template ) {
	// only CSS here please...
    ?>
    .amp-wp-title {border-bottom:1px solid #ccc;}
	.amp-wp-iframe-placeholder {
        background: #c2c2c2 url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGAAAABgAQMAAADYVuV7AAAABlBMVEUAAAD///+l2Z/dAAAAAXRSTlMAQObYZgAAAJtJREFUOMvd07sNAyEQBNA9ERBSwpVCaVAapVACIQFiLsAMi2UHl1jWTfaiXe1H5GlxeKWIiJ9oIhIm+jvA3MAs+AuYpmC7gsNHHFHBJAWbFVxROKuCbwqhq6LAaucAIhvdYIBEWCATbqxm4AQq4ceeBsJYjXDwIliIBolIFnnBoRBZo5yoRPVoRAvoRL95B3+M7xe/Pcb2Ms/KBe/MuxNhHM2TAAAAAElFTkSuQmCC') no-repeat center 40%;
    }
    <?php
}

// added logo - causes warning in google webmasters tools
// changed content image to i1.wp.com // already being output that way
// removed gravatar
// changed the placeholder to base64
// removed footer credits

/**
 * update routine from github
 */
require 'lib/plugin-update-checker/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
	'https://github.com/nabtron/wp-amp-override/',
	__FILE__,
	'wp-amp-override'
);

//Optional: If you're using a private repository, specify the access token like this:
$myUpdateChecker->setAuthentication('your-token-here');

//Optional: Set the branch that contains the stable release.
$myUpdateChecker->setBranch('stable-branch-name');