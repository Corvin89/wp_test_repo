<?php
/*
This file is part of Easy Testimonials.

Easy Testimonials is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

Easy Testimonials is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Easy Testimonials.  If not, see <http://www.gnu.org/licenses/>.

Shout out to http://www.makeuseof.com/tag/how-to-create-wordpress-widgets/ for the help
*/

class submitTestimonialWidget extends WP_Widget
{
	function __construct(){
		$widget_ops = array('classname' => 'submitTestimonialWidget', 'description' => 'Displays a Testimonial Submission Form.' );
		parent::__construct('submitTestimonialWidget', 'Easy Testimonials Submit a Testimonial', $widget_ops);		
	}
		
	function submitTestimonialWidget()
	{
		$this->__construct();
	}

	function form($instance){	
		if(isValidKey()){			
			$instance = wp_parse_args( (array) $instance, array( 'title' => '') );
			$title = $instance['title'];
					
			$testimonial_categories = get_terms( 'easy-testimonial-category', 'orderby=title&hide_empty=0' );
			?>
				<p><label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></label></p>
			<?php
		} else {
			?>
			<p><strong>Please Note:</strong><br/> This Feature Requires Easy Testimonials Pro.</p>
			<p><a href="https://goldplugins.com/our-plugins/easy-testimonials-details/upgrade-to-easy-testimonials-pro/?utm_source=submit_testimonials_widget&utm_campaign=up
				grade" target="_blank">Upgrade to Pro</a></p>
			<?php
		}
	}

	function update($new_instance, $old_instance){
		$instance = $old_instance;
		$instance['title'] = $new_instance['title'];
		return $instance;
	}

	function widget($args, $instance){
		extract($args, EXTR_SKIP);

		echo $before_widget;
		$title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);

		if (!empty($title)){
			echo $before_title . $title . $after_title;;
		}
		
		echo submitTestimonialForm(array());

		echo $after_widget;
	} 
}
?>