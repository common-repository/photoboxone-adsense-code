<?php
/*
 * Adsense Code: http://photoboxone.com/
 */
defined('ABSPATH') or die();

/*
 * Since 1.0.0
 */
$pagenow = sanitize_text_field( isset($GLOBALS['pagenow'])?$GLOBALS['pagenow']:'' );
if( $pagenow == 'plugins.php' ){
	
	function adsense_code_plugin_actions( $actions, $plugin_file, $plugin_data, $context ) {
		$url_setting = admin_url('options-general.php?page=adsense-code-setting');
		
		array_unshift($actions, '<a href="https://paypal.me/photoboxone" target="_blank">'.__("Donate", 'adsense-code')."</a>");			
		array_unshift($actions, "<a href=\"$url_setting\">".__("Settings", 'adsense-code')."</a>");
		return $actions;
	}
	
	add_filter("plugin_action_links_".plugin_basename(adsense_code_index()), "adsense_code_plugin_actions", 10, 4);
}

/*
 * Since 1.0.0
 */
function adsense_code_init_theme_opotion() {
	
	// add Setting
	add_settings_section(
		'adsense_code_options_section',
		'Adsense Code Options',		
		'adsense_code_options_section_display',
		'adsense-code-options-section'
	);
	
	register_setting( 'adsense_code_settings','adsense_code_options');
	
	// Styles
	wp_enqueue_style( 'adsense-code-admin-style', adsense_code_assets_url('admin.css'), '', adsense_code_ver() );
	
	// Scripts
	wp_enqueue_script( 'adsense-code-admin-script', adsense_code_assets_url('admin.js'), array('jquery'), adsense_code_ver(), true );
	
}
add_action('admin_init', 'adsense_code_init_theme_opotion');

/*
 * Since 1.0.0
 */
function adsense_code_setting_display(){
	
	$options = adsense_code_options();
	extract($options);
	
?>
	<div class="wrap adsense_code_settings clearfix">
		<h2><?php _e( 'Setting - Adsense Code', 'adsense-code' ); ?></h2>
		<?php adsense_code_help_links(); ?>
		<div class="adsense_code_advanced clearfix">
			<div class="col-md-12 clearfix">
				<form action="options.php" method="post">
					<?php settings_fields('adsense_code_settings' ); ?>
					<h3><?php _e( 'Google AdSense', 'adsense-code' ); ?></h3>
					<p>
						<label for="adsense_publisher_id"><?php _e( 'Publisher ID', 'adsense-code' ); ?>:</label>
						<input value="<?php echo $adsense_publisher_id;?>" type="text" name="adsense_code_options[adsense_publisher_id]" id="adsense_publisher_id" class="inputbox" placeholder="pub-5261703613038425" />
					</p>
					<p>
						<label for="adsense_setup_auto"><?php _e( 'Auto ads', 'adsense-code' ); ?>:</label>
						<select name="adsense_code_options[adsense_setup_auto]" id="adsense_setup_auto">
							<option value="0"><?php _e( 'No', 'adsense-code' ); ?></option>
							<option value="1"<?php echo ($adsense_setup_auto?" selected":"");?>><?php _e( 'Yes', 'adsense-code' ); ?></option>
						</select>
					</p>
					<p>
						<label for="adsense_code_responsive"><?php _e( 'Code Responsive', 'adsense-code' ); ?>:</label>
						<textarea name="adsense_code_options[adsense_code_responsive]" id="adsense_code_responsive" class="inputbox textareabox" ><?php echo $adsense_code_responsive;?></textarea>
					</p>
					<p><?php _e( 'Example', 'adsense-code' ); ?>:</p>
					<p>
						<a class="ad-unit-link" href="https://www.google.com/adsense/new/pub-id/myads/ad-units" target="_blank">
							<?php _e( 'Google Adsense - Ad Units', 'adsense-code' ); ?>
						</a>
					</p>
					<pre class="pre"><?php 
						echo htmlspecialchars( adsense_code_get_example() ); 
					?></pre>
					
					<?php submit_button(); ?>
				</form>
			</div>
		</div>
		<?php adsense_code_help_links(); ?>
	</div>
<?php
}

/*
 * Since 1.0.0
 */
function adsense_code_help_links(){
	?>
	<p>
		<a href="http://photoboxone.com/adsense-code/" target="_blank" rel="help">
			<?php _e( 'How to use an Adsense Code plugin?', 'adsense-code' ); ?>			
		</a>
	</p>
	<?php
}