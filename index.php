<?php
/*
Plugin Name: Ads Code
Plugin URI: http://photoboxone.com
Description: Ads code, ads widget.
Author: Photoboxone
Author URI: http://photoboxone.com
Version: 1.2.5
License: GPL-2.0+
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('ABSPATH') or die;

/*
 * Since 1.0.0 
 */
function adsense_code_index()
{
	return __FILE__;
}

require( dirname(__FILE__). '/includes/functions.php' );

adsense_code_include('widget.php');

if( is_admin() ) {
	
	adsense_code_include('setting.php');	
	
} else {
	
	adsense_code_include('site.php');
	
}