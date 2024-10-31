<?php 
defined('ABSPATH') or die();

/**
 * Custom Widget for displaying ...
 *
 * @link http://codex.wordpress.org/Widgets_API#Developing_Widgets
 *
 * @package Adsense Code
 * @subpackage Adsense Code
 * @since Adsense Code 1.0
 */

class Adsense_Code_Widget extends WP_Widget {

	/**
	 * Constructor.
	 *
	 * @since Adsense Code 1.0
	 *
	 * @return Adsense_Code_Widget
	 */
	public function __construct() {
		parent::__construct( 'widget_adsense_code', 'Adsense Code', array(
			'classname'   => 'widget_adsense_code',
			'description' => 'Use this widget to show adsense code.'
		) );
	}
	
	/**
	 * Deal with the settings when they are saved by the admin.
	 *
	 * Here is where any validation should happen.
	 *
	 * @since Adsense Code 1.0
	 *
	 * @param array $new_instance New widget instance.
	 * @param array $instance     Original widget instance.
	 * @return array Updated widget instance.
	 */
	function update( $new_instance, $instance ) {
		$instance['title']  	= strip_tags( $new_instance['title'] );
		$instance['show_title'] = empty( $new_instance['show_title'] ) ? 0 : absint($new_instance['show_title']);
		$instance['code'] 		= empty( $new_instance['code'] ) ? '' : $new_instance['code'];
		$instance['before'] 	= empty( $new_instance['before'] ) ? '' : $new_instance['before'];
		$instance['after'] 		= empty( $new_instance['after'] ) ? '' : $new_instance['after'];
		
		return $instance;
	}

	/**
	 * Display the form for this widget on the Widgets page of the Admin area.
	 *
	 * @since Adsense Code 1.0
	 *
	 * @param array $instance
	 */
	function form( $instance ) {
		
		$title  	= apply_filters( 'widget_title', empty( $instance['title'] ) ? 'Responsive - Adsense Code' : $instance['title'], $instance, $this->id_base );
		$show_title = empty( $instance['show_title'] ) ? 0 : absint( $instance['show_title'] );
		$code 		= empty( $instance['code']) ? adsense_code_get_example() : $instance['code'];
		$before 	= empty( $instance['before']) ? '' : $instance['before'];
		$after 		= empty( $instance['after']) ? '' : $instance['after'];
		$before_more= empty( $instance['before_more']) ? '' : $instance['before_more'];
		$after_more = empty( $instance['after_more']) ? '' : $instance['after_more'];
		
		?>
			<p><label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:' ); ?></label></p>
			<p><input id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php echo esc_attr( $title ); ?>" /></p>
			<p><input type="checkbox" value="1" id="<?php echo esc_attr( $this->get_field_id( 'show_title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_title' ) ); ?>" <?php echo $show_title?'checked':'';?> /><label for="<?php echo esc_attr( $this->get_field_id( 'show_title' ) ); ?>"><?php _e( 'Show Title' ); ?></label></p>
			<p><label for="<?php echo $this->get_field_id( 'code' ); ?>"><?php _e( 'Adsense Code' ); ?>:</label></p>
			<p><textarea id="<?php echo $this->get_field_id( 'code' ); ?>" name="<?php echo $this->get_field_name( 'code' ); ?>" rows="5" style="width:100%; height: 100px;" placeholder="<?php _e( 'Please insert adsense code here' ); ?>" ><?php echo $code; ?></textarea></p>
		<?php
	}
	
	/**
	 * Output the HTML for this widget.
	 *
	 * @access public
	 * @since Adsense Code 1.0
	 *
	 * @param array $args     An array of standard parameters for widgets in this theme.
	 * @param array $instance An array of settings for this widget instance.
	 */
	public function widget( $args, $instance ) {
		
		$title  		= apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
		$show_title 	= empty( $instance['show_title'] ) ? 0 : absint( $instance['show_title'] );
		$code 			= empty( $instance['code']) ? '' : $instance['code'];

		if( $code == '' ) return;
		
		echo isset($args['before_widget']) ? $args['before_widget'] : '<div class="widget_adsense_code widget_adsense_code_'.$this->id_base.'">';
		
		if ( $title != '' && $show_title ) :
			
			echo isset($args['before_title']) ? $args['before_title'] : '';
			
			echo $title;
			
			echo isset($args['before_title']) && isset($args['after_title']) ? $args['after_title'] : '';
			
		endif;
		
		// echo $this->strip_tag( $code );

		echo $code;
		
		echo isset($args['before_widget']) && isset($args['after_widget']) ? $args['after_widget'] : '</div>';
		
	}

	/**
	 * strip_tag
	 *
	 * @access public
	 * @since 1.1.4
	 *
	 * @param 	string $html .
	 * @return 	string $html .
	 */
	public function strip_tag( $html = '', $tag = 'ins' )
	{
		$regex = '/<'.$tag.'[^>]+\>/i';
		if( preg_match_all($regex, $html, $matches, PREG_PATTERN_ORDER) > 0 ) 
		{
			// $html = wp_strip_all_tags( $html ); 
			foreach( $matches[0] as $ins ) {
                return $ins;
            }
		}
		
		return $html;
	}

}

// Setup Widget now
add_action( 'widgets_init', function(){
	register_widget( 'Adsense_Code_Widget' );
});