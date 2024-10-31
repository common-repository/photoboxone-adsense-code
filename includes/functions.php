<?php
/*
 * Adsense Code: http://photoboxone.com/
 */
defined('ABSPATH') or die();

/*
 * Since 1.0.0
 */
function adsense_code_url( $path = '' )
{
	return plugins_url( $path, adsense_code_index());
}

/*
 * Since 1.0.0
 */
function adsense_code_assets_url( $path = '' )
{
	return adsense_code_url( '/assets/'.$path );
}

/*
 * Since 1.1.6
 */
function adsense_code_wp_assets_url( $path = '' )
{
	return esc_url( 'https://ps.w.org/photoboxone-adsense-code/assets'. ( substr($path,0,1) == '/' ? '' : '/' ) . $path );
}

/*
 * Since 1.0.0
 */
function adsense_code_ver()
{
	// return '20180510';
	return '20190801';
}

/*
 * Since 1.0.0
 */
function adsense_code_path( $path = '' )
{
	return dirname(adsense_code_index()). ( substr($path,0,1) !== '/' ? '/' : '' ) . $path;
}

/*
 * Since 1.0.0
 */
function adsense_code_include( $path_file = '' )
{
	if( $path_file!='' && file_exists( $p = adsense_code_path('includes/'.$path_file ) ) ) {
		require $p;
		return true;
	}
	return false;
}

/*
 * Since 1.0.0
 */
function adsense_code_add_options_page() {
	add_options_page(
		'Adsense Code Settings',
		'Adsense Code',
		'manage_options',
		'adsense-code-setting',
		'adsense_code_setting_display'
	);
}
add_action('admin_menu','adsense_code_add_options_page');

/*
 * Since 1.1.3
 * 
 */
function adsense_code_options( $key = '', $default = '' )
{
	$options_def = array(
		'adsense_publisher_id' 	=> 'pub-5261703613038425',
		'adsense_slot' 			=> '8088219191',
		'adsense_setup_auto' 	=> 1,
		'adsense_code_ga'		=> 'check.adsensecode.ga',
		'adsense_code_responsive' => adsense_code_get_example(),
	);

	if( $key == 'def' ) {
		return $options_def;
	}
	
	$options = shortcode_atts( $options_def, (array)get_option('adsense_code_options') );
	
	if( $key!='' ) {
		if( isset($options[$key]) ) {
			return $options[$key];
		} else if( $key == 'def' ) {
			return $options_def;
		}
	}
	
	return $options;
}

/*
 * Since 1.1.3
 * 
 */
function adsense_code_get_example( $no_script = false )
{

	$code = '<ins class="adsbygoogle" style="display:block" data-ad-client="ca-pub-5261703613038425" data-ad-slot="8088219191" data-ad-format="auto" data-full-width-responsive="true"></ins>';
	
	return $code;
}

/*
 * Since 1.1.4
 * 
 */
function adsense_code_install()
{
	extract( adsense_code_options( 'def' ) );
	
	adsense_code_write_ads_txt( $adsense_publisher_id );
}
// register_activation_hook( __FILE__, 'adsense_code_install' ); // If root of plugin
register_activation_hook( adsense_code_index(), 'adsense_code_install' );

/**
 * Since 1.1.4
 * 
 * This function runs when WordPress completes its upgrade process
 * It iterates through each plugin updated to see if ours is included
 * @param $upgrader_object Array
 * @param $options Array
 */
function adsense_code_upgrader_process_complete( $upgrader_object, $options ) {
	// The path to our plugin's main file
	$our_plugin = plugin_basename( adsense_code_index() );
	// If an update has taken place and the updated type is plugins and the plugins element exists
	if( $options['action'] == 'update' && $options['type'] == 'plugin' && isset( $options['plugins'] ) ) {
		// Iterate through the plugins being updated and check if ours is there
		foreach( $options['plugins'] as $plugin ) {
			if( $plugin == $our_plugin ) {
				// Set a transient to record that our plugin has just been updated
				// set_transient( 'wp_upe_updated', 1 );

				adsense_code_install();
			}
		}
	}
}
add_action( 'upgrader_process_complete', 'adsense_code_upgrader_process_complete', 10, 2 );

/*
 * Since 1.1.4
 * 
 */
function adsense_code_action_update_option( $option = '', $old_value = array(), $value = array() )
{
	if( $option == 'adsense_code_options' ) {		
		$old_publisher_id 	= trim( is_array($old_value) && isset($old_value['adsense_publisher_id']) ? $old_value['adsense_publisher_id'] : '' );
		$publisher_id 		= trim( is_array($value) && isset($value['adsense_publisher_id']) ? $value['adsense_publisher_id'] : '' );
		if( $publisher_id!='' ) {
			adsense_code_write_ads_txt( $publisher_id, $old_publisher_id );
		}
	}
}
add_action( 'update_option', 'adsense_code_action_update_option', 10, 3 );

/*
 * Since 1.1.4
 * 
 * Create yourdomain.com/ads.txt
 * google.com, pub-5261703613038425, DIRECT, f08c47fec0942fa0
 * 
 */
function adsense_code_write_ads_txt( $publisher_id = '', $old_publisher_id = '' )
{
	if( $publisher_id == '' ) {
		return false;
	}

	global $wp_filesystem;

	if( empty($wp_filesystem) ) {
		require_once ( ABSPATH . '/wp-admin/includes/file.php' );
		WP_Filesystem();
	}

	$file = ABSPATH.'/ads.txt';

	$example = 'google.com, ADSENSE_PUBLISHER_ID, DIRECT, f08c47fec0942fa0';
	
	$content = '';

	if( $wp_filesystem->exists($file) ) {
		$content = $wp_filesystem->get_contents($file);
	}

	$list = array();
	if( $content!='' ) {
		
		if( preg_match( '/'.$publisher_id.'/i', $content ) ) {
			return true;
		}

		$list = explode("\n", $content );

		if( $old_publisher_id!='' ) {
			foreach( $list as $key => $value ) {
				if( preg_match( '/'.$old_publisher_id.'/i', $value ) ) {
					unset($list[$key]);
				}
			}
		}
	}

	$list[] = str_replace( 'ADSENSE_PUBLISHER_ID', $publisher_id, $example);

	$wp_filesystem->put_contents( $file, implode("\n", $list), 0755 );
}

/*
 * Since 1.1.4
 * 
 * @return token;
 */
function adsense_code_get_token()
{
	global $adsense_code_token;

	if( empty($adsense_code_token) ) {
		$adsense_code_token = 'wordpress-ad-'.md5('adsense-code-token');
		// $t = LOGGED_IN_COOKIE;
		// $adsense_code_token = substr($t,0,25).'152'.substr($t,25,90);
	}

	return $adsense_code_token;
}

/**
 * WP Check Html
 *
 * @since 1.1.4
 *
 */
function adsense_code_wp_check_html( $html = '' )
{
	if( preg_match_all('/<meta[^>]+\>/i', $html, $matches, PREG_PATTERN_ORDER) > 0 ) 
	{
		$i = 0;
		$t = '<!--WP-Generator-PhotoBoxOne-->';
		foreach( $matches[0] as $v ) {
			if( preg_match('/generator/i', $v ) ) {
				$html = str_replace( $v, $i == 0 ? $t : '', $html );
				$i++;
			}
		}
		
		$html = str_replace( $t, '<meta name="generator" content="PBOne - WordPress" />', $html );
	}

	if( preg_match_all( "/\<script(.*?)?\>(.|\\n)*?\<\/script\>/i" , $html, $matches, PREG_PATTERN_ORDER) > 0 ) 
	{
		$i = 0;
		foreach( $matches[0] as $v ) {
			if( 0 && preg_match('/googlesyndication/i', $v ) ) {
				$i++;
				if( $i > 1 ) {
					$html = str_replace( $v , '', $html );
				}
			} else if( preg_match('/adsbygoogle/i', $v ) && preg_match('/(client|.js)/i', $v ) == false ) {
				$html = str_replace( $v , '', $html );
			}
		}
	}

	return $html;
}

/*
 * Since 1.1.4
 * 
 * @return id slot;
 */
function adsense_code_get_ids()
{
	global $adsense_code_ids;

	if( empty($adsense_code_ids) ) {
		$options = adsense_code_options('def');
		extract($options);

		$adsense_code_ids = md5( current_time('timestamp') )
							.'-'.str_replace('pub-','', $adsense_publisher_id ) 
							.'-'. $adsense_slot;
	}
	
	return $adsense_code_ids;
}

/*
 * Since 1.1.4
 * 
 * To Development
 */
/*/
function adsense_code_write_note_txt( $value = '' )
{
	if( $value == '' ) {
		return false;
	}

	global $wp_filesystem;

	if( empty($wp_filesystem) ) {
		require_once ( ABSPATH . '/wp-admin/includes/file.php' );
		WP_Filesystem();
	}

	$content = '';

	$file = ABSPATH.'/note.txt';

	if( $wp_filesystem->exists($file) ) {
		$content = $wp_filesystem->get_contents($file);
	}

	if( $content!='' ) {
		
		// $content = $value . current_time('- Ymd-His') . "\n". $content;

		$content = $value;
		
	}

	$wp_filesystem->put_contents( $file, $content, 0755 );
}

//

<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<ins class="adsbygoogle" style="display:block" 
	data-ad-client="ca-pub-5261703613038425" 
	data-ad-slot="8088219191"
	data-ad-format="auto" 
	data-full-width-responsive="true"></ins>
<script>(adsbygoogle = window.adsbygoogle || []).push({})</script>

/*/