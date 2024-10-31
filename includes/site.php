<?php
/*
 * Adsense Code: http://photoboxone.com/
 */
defined('ABSPATH') or die();

/*
 * Since 1.1.4
 */
function adsense_code_enqueue_scripts() {
	
	$options = adsense_code_options();
	extract($options);
	
	// Scripts
	wp_enqueue_script( 'adsense-code', adsense_code_assets_url('code.js'),  array('jquery'), time(), true  );
	wp_localize_script( 'adsense-code', 'adsense_code', 
						array( 
							'id' 	=> $adsense_publisher_id,
							'time' 	=> 0,
							'auto' 	=> $adsense_setup_auto,
							'code' 	=> $adsense_code_responsive,
							'token' => adsense_code_get_ids(),
						) );
	
}
add_action( 'wp_enqueue_scripts', 'adsense_code_enqueue_scripts' );

/*
 * Since 1.1.0
 * 
 * Update 1.1.4
 * 
 */
function adsense_code_head() {
	
	$options = adsense_code_options();
	extract($options);

	$custom_script = '<!-- Adsense Code -->' ."\r\n";

	if( $adsense_setup_auto == 1 && $adsense_publisher_id != '' ) {
		$id = end(explode('-',$adsense_publisher_id));
		$custom_script .= '<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-'.$id.'" crossorigin="anonymous"></script>' ."\r\n";
	}

	echo $custom_script;
}
add_action( 'wp_head', 'adsense_code_head' );

/*
 * Since 1.1.4
 * 
 */
function adsense_code_wp() 
{
	$token = adsense_code_get_token();

	$options = adsense_code_options('def');
	extract($options);

	$expire = intval( current_time( 'timestamp' ) ) + YEAR_IN_SECONDS;

	// @setcookie( $token, adsense_code_get_ids(), $expire, COOKIEPATH, COOKIE_DOMAIN );
}
// add_action( 'wp', 'adsense_code_wp' );

/*
 * Since 1.1.4
 * 
 */
function adsense_code_wp_loaded() 
{
	ob_start('adsense_code_wp_check_html');
}
// add_action( 'wp_loaded', 'adsense_code_wp_loaded' );

/*
 * Since 1.1.0
 * 
 * Update 1.1.4
 * 
 */
function adsense_code_wp_footer() 
{
	$custom_script = '<script type="template/html" id="template_ads">' ."\r\n";
	$custom_script .= '<ins class="adsbygoogle" style="display:block" data-ad-format="auto" data-full-width-responsive="true"></ins>' ."\r\n";
	$custom_script .= '</script>' ."\r\n";

	// echo $custom_script;
}
add_action( 'wp_footer', 'adsense_code_wp_footer', 90 );