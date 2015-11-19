jQuery(function () {
	wrapper = jQuery('#easy_t_shortcode_generator');
	if (wrapper.length > 0)
	{
		var button = wrapper.find('#sc_generate');
		button.on('click', ezt_build_shortcode);
		ezt_enable_shortcode_highlighting();
		jQuery('#sc_gen_paginate').parent().bind('click', function () {
			ezt_toggle_pagination_options();
		});
		jQuery('#sc_gen_use_slider').parent().bind('click', function () {
			ezt_toggle_slider_options();
		});
		ezt_toggle_slider_options();	
		ezt_toggle_pagination_options();
	}
	
	ezt_link_upgrade_labels();
	
	jQuery('.gp_highlight_code').on('click', function () {
		jQuery(this).select();
	});
});


function ezt_link_upgrade_labels()
{
	if (jQuery('.plugin_is_not_registered').length == 0) {
		return;
	}
	jQuery('.easy-t-radio-button').each(function (index) {
		var my_radio = jQuery(this).find('input[type=radio]');
		if (my_radio)
		{
			var disabled = (my_radio.attr('disabled') && my_radio.attr('disabled').toLowerCase() == 'disabled');
			if (disabled) {
				var my_em = jQuery(this).find('label em:first');
				var my_img = jQuery(this).find('label img');
				if (my_em.length > 0 || my_img.length > 0) {
					var my_id = my_radio.attr('id');
					var buy_url = 'https://goldplugins.com/our-plugins/easy-testimonials-details/upgrade-to-easy-testimonials-pro/?utm_campaign=upgrade_themes&utm_source=theme_selection&utm_banner=' + my_id;
					var link_template = '<a href="@buy_url" target="_blank"></a>';
					var link = link_template.replace(/@buy_url/g, buy_url);
					my_em.wrap(link);
					my_img.wrap(link);
				}				
			}
		}
	});
}

function ezt_highlight_shortcode()
{
	jQuery('#sc_gen_output').select();
}

function ezt_toggle_slider_options()
{
	var $opts_trs = jQuery('tr.slider_option');
	var $val = ezt_get_value_from_input('#sc_gen_use_slider', 0, 'yes_or_no_to_0_or_1');
	if ($val == 1) {
		$opts_trs.removeClass('disabled');
	} else {
		$opts_trs.addClass('disabled');
	}
	
}

function ezt_toggle_pagination_options()
{
	var $opts_trs = jQuery('tr.pagination_option');
	var $val = ezt_get_value_from_input('#sc_gen_paginate', 0, 'yes_or_no_to_0_or_1');
	if ($val == 1) {
		$opts_trs.removeClass('disabled');
	} else {
		$opts_trs.addClass('disabled');
	}
	
}

function ezt_enable_shortcode_highlighting()
{
	jQuery('#sc_gen_output').bind('click', function ()
	{
		ezt_highlight_shortcode();
	});
}

function ezt_get_value_from_input (selector, default_value, filter)
{
	var trg = jQuery(selector);
	var val = '';

	if ( trg.is(':checkbox') ) {
		val = ( jQuery(selector).is(':checked') ? jQuery(selector).val() : '' );
	} else {
		val = jQuery(selector).val();
	}
	
	val = (val ? val : default_value);
	
	if (filter == 'int') {
		var temp_val  = parseInt(val + '' , 10 );
		if (isNaN(temp_val)) {
			return default_value;
		} else {
			return temp_val;
		}
	}
	else if (filter == 'convert_to_milliseconds') {
		var temp_val  = parseInt(val + '' , 10 );
		if (isNaN(temp_val)) {
			return default_value;
		} else {
			return temp_val * 1000;
		}
	}
	else if (filter == 'yes_or_no_to_0_or_1') {
		if (val == 'yes') {
			return 1;
		} else if (val == 'no' || val == '') {
			return 0;			
		} else {
			return default_value;
		}
	}
	else {
		return val;
	}
}

function ezt_add_attribute($key, $val, $orderby, $use_slider, $use_pagination, $use_random)
{
	if ($key == 'use_excerpt') {
		return ($val == 1) ? " use_excerpt='1'" : '';
	}
	else if ($key == 'pager') {
		if ($use_slider && $val == 1) {
			return " pager='1'";
		} else {
			return '';
		}	
	}
	else if ($key == 'prev_next') {
		return ($use_slider && $val == 1) ? " prev_next='1'" : '';
	}
	else if ($key == 'pause_on_hover') {
		if ($use_slider && $val == 1) {
			return " pause_on_hover='1'";
		} else {
			return '';
		}	
	}
	else if ($key == 'paused') {
		if ($use_slider && $val == 1) {
			return " paused='1'";
		} else {
			return '';
		}	
	}
	else if ($key == 'show_title') {
		return ($val == 1) ? " show_title='1'" : '';
	}
	else if ($key == 'auto_fit_container') {
		if($use_slider){
			return ($val == 1) ? " auto_height='container'" : " auto_height='calc'";
		} else {
			return '';
		}
	}
	else if ($key == 'show_thumbs') {
		return ($val == 1) ? " show_thumbs='1'" : '';
	}
	else if ($key == 'count') {
		return ($val > 1) ? " count='" + $val + "'" : '';
	}
	else if ($key == 'width') {
		return ($val) ? " width='" + $val + "'" : '';
	}
	else if ($key == 'testimonials_per_slide') {
		return ($use_slider && $val > 1) ? " testimonials_per_slide='" + $val + "'" : '';
	}
	else if ($key == 'category') {
		return ($val != 'all') ? " category='" + $val + "'" : '';
	}
	else if ($key == 'show_rating') {
		return ($val != 'hide') ? " show_rating='" + $val + "'" : '';
	}
	else if ($key == 'show_date') {
		return ($val == 1) ? " show_date='1'" : '';
	}
	else if ($key == 'show_other') {
		return ($val == 1) ? " show_other='1'" : '';
	}
	else if ($key == 'orderby') {
		//don't add orderby attribute to the random_testimonial shortcode or testimonial_cycle random=true
		if ($use_random){
			return '';
		} else {
			return ($val != '') ? " orderby='" + $val + "'" : '';
		}
	}
	else if ($key == 'use_slider') {
		return '';
	}
	else if ($key == 'paginate') {
		//don't add pagination attribute to random_testimonial or testimonial_cycle shortcodes
		if ($use_random || $use_slider) {
			return '';
		} else {
			return ($val == 1) ? " paginate='1'" : '';
		}
	}
	else if ($key == 'theme') {
		return " theme='" + $val + "'";
	}
	else if ($key == 'num_per_page') {
		if ($use_pagination) {
			return " testimonials_per_page='" + $val + "'";
		} else {
			return '';
		}
	}
	else if ($key == 'transition') {
		if ($use_slider) {
			return " transition='" + $val + "'";
		} else {
			return '';
		}
	}
	else if ($key == 'timer') {
		if ($use_slider) {
			return " timer='" + $val + "'";
		} else {
			return '';
		}
	}
	else if ($key == 'order') {
		if ($orderby !=='random' && $orderby !=='rand') {
			return " order='" + $val + "'";
		} else {
			return '';
		}
	}
	else {
		return " " + $key + "='" + $val + "'";
	}
}

function ezt_build_shortcode()
{
	// collect variables
	var $atts = [];
	var $str = '';
	$atts['count'] = ezt_get_value_from_input('#sc_gen_count', 10, 'int');
	$atts['width'] = ezt_get_value_from_input('#sc_gen_width', '100%');
	$atts['orderby'] = ezt_get_value_from_input('#sc_gen_order_by', 'id');
	$atts['order'] = ezt_get_value_from_input('#sc_gen_order_dir', 'ASC');
	$atts['category'] = ezt_get_value_from_input('#sc_gen_category', 'all');
	$atts['show_title'] = ezt_get_value_from_input('#sc_gen_show_title', 0, 'yes_or_no_to_0_or_1');
	$atts['theme'] = ezt_get_value_from_input('#sc_gen_theme', 'default_style');
	$atts['use_excerpt'] = ezt_get_value_from_input("input[name='sc_gen_use_excerpt']:checked", 1, 'yes_or_no_to_0_or_1');
	$atts['show_thumbs'] = ezt_get_value_from_input('#sc_gen_show_thumbs', 0, 'yes_or_no_to_0_or_1');
	$atts['show_rating'] = ezt_get_value_from_input("input[name='sc_gen_show_ratings']:checked", 'hide');
	$atts['show_date'] = ezt_get_value_from_input('#sc_gen_show_date', 0, 'yes_or_no_to_0_or_1');
	$atts['show_other'] = ezt_get_value_from_input('#sc_gen_show_other', 0, 'yes_or_no_to_0_or_1');
	$atts['use_slider'] = ezt_get_value_from_input('#sc_gen_use_slider', 0, 'yes_or_no_to_0_or_1');
	$atts['transition'] = ezt_get_value_from_input('#sc_gen_transition', 'fade');
	$atts['paginate'] = ezt_get_value_from_input('#sc_gen_paginate', 0, 'yes_or_no_to_0_or_1');
	$atts['num_per_page'] = ezt_get_value_from_input('#sc_gen_num_per_page', 1, 'int');
	$atts['timer'] = ezt_get_value_from_input('#sc_gen_slider_timer', 4000, 'convert_to_milliseconds');
	$atts['testimonials_per_slide'] = ezt_get_value_from_input('#sc_gen_slider_testimonials_per_slide', 1, 'int');
	$atts['pager'] = ezt_get_value_from_input('#sc_gen_show_pager', 0, 'yes_or_no_to_0_or_1');
	$atts['prev_next'] = ezt_get_value_from_input('#sc_gen_show_prev_next', 0, 'yes_or_no_to_0_or_1');
	$atts['paused'] = ezt_get_value_from_input('#sc_gen_paused', 0, 'yes_or_no_to_0_or_1');
	$atts['pause_on_hover'] = ezt_get_value_from_input('#sc_gen_pause_on_hover', 0, 'yes_or_no_to_0_or_1');
	$atts['auto_fit_container'] = ezt_get_value_from_input("input[name='sc_gen_auto_fit']:checked", 1, 'yes_or_no_to_0_or_1');
	
	// begin with either "[testimonials", "[random_testimonial", or "[testimonial_cycle"
	$str = '[';
	$use_slider = false;
	$use_pagination = false;
	$use_random = false;
	
	//set to true if paginate is set
	if ($atts['paginate'] == 1){
		$use_pagination = true;
	}	
	
	if ($atts['use_slider'] == 1) 
	{
		if ($atts['orderby'] == 'rand') {	
			$str += 'testimonials_cycle random="true"';	
			$use_random = true;
		} else {
			$str += 'testimonials_cycle';
		}
		$use_slider = true;
		//if this is a testimonial cycle, disable pagination		
		$use_pagination = false;	
	}
	else if ($atts['orderby'] == 'rand') {	
		$str += 'random_testimonial';
		//if this is a random testimonial, disable pagination		
		$use_pagination = false;	
		$use_random = true;
	}
	else {
		$str += 'testimonials';	
	}
	
	// next add each attribute according to the user supplied values
	var $a;
	for ($key in $atts) {
		$str += ezt_add_attribute($key, $atts[$key], $atts['orderby'], $use_slider, $use_pagination, $use_random);
	}
	
	// finally, close and display the shortcode
	$str += ']';
	jQuery('#sc_gen_output').val($str);
	jQuery('#sc_gen_output_wrapper').show();
	
	ezt_highlight_shortcode();
	
}