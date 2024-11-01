<?php
/*
Plugin Name: Your Current Location On Map
Plugin URI:  https://github.com/pmbaldha/wp-current-location-on-map/
Description: Displays your current location in map with accuracy. Your Current Location On Map plugin is very easy to use,mobile friendly,responsive.  
Version:     1.1
Author:      Prashant Baldha
Author URI:  https://github.com/pmbaldha/
License:     GPL2
Your Current Location On Map is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
 
Your Current Location On Map is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with Your Current Location On Map. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/
if ( ! function_exists( 'clom_your_current_location_on_map' ) )
{
	function clom_your_current_location_on_map()
	{ 	
		wp_enqueue_style(
			'clom_leaflet_css',
			plugins_url( '/leaflet/leaflet.css', __FILE__ )
		);	
		wp_enqueue_style(
			'clom_map_css',
			plugins_url( '/css/map.css', __FILE__ )
		);	
		wp_enqueue_script(
			'clom_leaflet_js',
			plugins_url( '/leaflet/leaflet-src.js', __FILE__ )
		);
		wp_enqueue_script(
			'clom_map_js',
			plugins_url( '/js/map-bind.js', __FILE__ ),
			array('clom_leaflet_js')
		);
		echo '<div id="clom_map">';
		if ( wp_is_mobile() ) 
		{
			echo 'Please enable gps and refresh page and allow share location';
		}
		else
		{
			echo 'Please allow share location';
		}
		echo '</div>';
	}
}
if (  function_exists( 'clom_your_current_location_on_map' ) )
	add_shortcode( 'your-current-location-on-map','clom_your_current_location_on_map');
	
/**
 * Adds Foo_Widget widget.
 */
class Clom_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'clom_widget', // Base ID
			__( 'Your Current Location on Map', 'your-current-location-on-map' ), // Name
			array( 'description' => __( 'Displays your current location in map with accuracy.', 'your-current-location-on-map' ), ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		echo $args['before_widget'];
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
		}
		clom_your_current_location_on_map();		
		echo $args['after_widget'];
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Your Live Location', 'your-current-location-on-map' );
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<?php 
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

		return $instance;
	}

} // class Clom_Widget
function register_clom_widget() {
    register_widget( 'Clom_Widget' );
}
add_action( 'widgets_init', 'register_clom_widget' );