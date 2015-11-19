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
along with The Easy Testimonials.  If not, see <http://www.gnu.org/licenses/>.
*/

class easyTestimonialOptions
{	
	function __construct(){
		//may be running in non WP mode (for example from a notification)
		if(function_exists('add_action')){
			//add a menu item
			add_action('admin_menu', array($this, 'add_admin_menu_item'));
			add_action('admin_init', 'hello_t_nag_ignore');
				
		}
	}
	
	function add_admin_menu_item(){
		$title = "Easy Testimonials Settings";
		$page_title = "Easy Testimonials Settings";
		$top_level_slug = "easy-testimonials-settings";
		
		//create new top-level menu
		add_menu_page($page_title, $title, 'administrator', $top_level_slug , array($this, 'basic_settings_page'));
		add_submenu_page($top_level_slug , 'Basic Options', 'Basic Options', 'administrator', $top_level_slug, array($this, 'basic_settings_page'));
		add_submenu_page($top_level_slug , 'Display Options', 'Display Options', 'administrator', 'easy-testimonials-display-settings', array($this, 'display_settings_page'));
		add_submenu_page($top_level_slug , 'Themes', 'Themes', 'administrator', 'easy-testimonials-style-settings', array($this, 'style_settings_page'));
		add_submenu_page($top_level_slug , 'Submission Form Options', 'Submission Form Options', 'administrator', 'easy-testimonials-submission-settings', array($this, 'submission_settings_page'));
		add_submenu_page($top_level_slug , 'Shortcode Generator', 'Shortcode Generator', 'administrator', 'easy-testimonials-shortcode-generator', array($this, 'shortcode_generator_page'));
		add_submenu_page($top_level_slug , 'Import & Export Testimonials', 'Import & Export Testimonials', 'administrator', 'easy-testimonials-import-export', array($this, 'import_export_settings_page'));
		add_submenu_page($top_level_slug , 'Help & Instructions', 'Help & Instructions', 'administrator', 'easy-testimonials-help', array($this, 'help_settings_page'));
		
		//call register settings function
		add_action( 'admin_init', array($this, 'register_settings'));	
	}


	function register_settings(){
		//register our settings
		
		/* Basic options */
		register_setting( 'easy-testimonials-settings-group', 'easy_t_custom_css' );
		register_setting( 'easy-testimonials-settings-group', 'easy_t_disable_cycle2' );
		register_setting( 'easy-testimonials-settings-group', 'easy_t_use_cycle_fix' );
		register_setting( 'easy-testimonials-settings-group', 'easy_t_apply_content_filter' );
		register_setting( 'easy-testimonials-settings-group', 'easy_t_show_in_search' );
		register_setting( 'easy-testimonials-settings-group', 'easy_t_cache_buster', array($this, 'easy_t_bust_options_cache') );
		
		/* Shortcodes */
		register_setting( 'easy-testimonials-settings-group', 'ezt_testimonials_shortcode' );
		register_setting( 'easy-testimonials-settings-group', 'ezt_single_testimonial_shortcode' );
		register_setting( 'easy-testimonials-settings-group', 'ezt_submit_testimonial_shortcode' );
		register_setting( 'easy-testimonials-settings-group', 'ezt_cycle_testimonial_shortcode' );
		register_setting( 'easy-testimonials-settings-group', 'ezt_random_testimonial_shortcode' );
		register_setting( 'easy-testimonials-settings-group', 'ezt_testimonials_count_shortcode' );
		register_setting( 'easy-testimonials-settings-group', 'ezt_testimonials_grid_shortcode' );
		
		/* Pro registration */
		register_setting( 'easy-testimonials-settings-group', 'easy_t_registered_name' );
		register_setting( 'easy-testimonials-settings-group', 'easy_t_registered_url' );
		register_setting( 'easy-testimonials-settings-group', 'easy_t_registered_key' );
		
		/* Theme selection */
		register_setting( 'easy-testimonials-style-settings-group', 'testimonials_style' );
		
		/* Submission form settings */
		register_setting( 'easy-testimonials-submission_form_options-settings-group', 'easy_t_title_field_label' );
		register_setting( 'easy-testimonials-submission_form_options-settings-group', 'easy_t_title_field_description' );
		register_setting( 'easy-testimonials-submission_form_options-settings-group', 'easy_t_name_field_label' );
		register_setting( 'easy-testimonials-submission_form_options-settings-group', 'easy_t_name_field_description' );
		register_setting( 'easy-testimonials-submission_form_options-settings-group', 'easy_t_position_web_other_field_label' );
		register_setting( 'easy-testimonials-submission_form_options-settings-group', 'easy_t_position_web_other_field_description' );
		register_setting( 'easy-testimonials-submission_form_options-settings-group', 'easy_t_other_other_field_label' );
		register_setting( 'easy-testimonials-submission_form_options-settings-group', 'easy_t_other_other_field_description' );
		register_setting( 'easy-testimonials-submission_form_options-settings-group', 'easy_t_category_field_label' );
		register_setting( 'easy-testimonials-submission_form_options-settings-group', 'easy_t_category_field_description' );
		register_setting( 'easy-testimonials-submission_form_options-settings-group', 'easy_t_body_content_field_label' );
		register_setting( 'easy-testimonials-submission_form_options-settings-group', 'easy_t_body_content_field_description' );
		register_setting( 'easy-testimonials-submission_form_options-settings-group', 'easy_t_submit_button_label' );
		register_setting( 'easy-testimonials-submission_form_options-settings-group', 'easy_t_submit_success_message' );
		register_setting( 'easy-testimonials-submission_form_options-settings-group', 'easy_t_submit_notification_address' );
		register_setting( 'easy-testimonials-submission_form_options-settings-group', 'easy_t_submit_notification_include_testimonial' );
		register_setting( 'easy-testimonials-submission_form_options-settings-group', 'easy_t_submit_success_redirect_url' );
		register_setting( 'easy-testimonials-submission_form_options-settings-group', 'easy_t_hide_position_web_other_field' );
		register_setting( 'easy-testimonials-submission_form_options-settings-group', 'easy_t_hide_other_other_field' );
		register_setting( 'easy-testimonials-submission_form_options-settings-group', 'easy_t_hide_category_field' );
		register_setting( 'easy-testimonials-submission_form_options-settings-group', 'easy_t_hide_name_field' );	
		register_setting( 'easy-testimonials-submission_form_options-settings-group', 'easy_t_hide_email_field' );	
		register_setting( 'easy-testimonials-submission_form_options-settings-group', 'easy_t_email_field_label' );
		register_setting( 'easy-testimonials-submission_form_options-settings-group', 'easy_t_email_field_description' );		
		register_setting( 'easy-testimonials-submission_form_options-settings-group', 'easy_t_use_captcha', array($this, 'enable_captcha_callback')  );	
		register_setting( 'easy-testimonials-submission_form_options-settings-group', 'easy_t_image_field_label' );
		register_setting( 'easy-testimonials-submission_form_options-settings-group', 'easy_t_image_field_description' );	
		register_setting( 'easy-testimonials-submission_form_options-settings-group', 'easy_t_use_image_field' );	
		register_setting( 'easy-testimonials-submission_form_options-settings-group', 'easy_t_captcha_field_label' );
		register_setting( 'easy-testimonials-submission_form_options-settings-group', 'easy_t_captcha_field_description' );	
		register_setting( 'easy-testimonials-submission_form_options-settings-group', 'easy_t_recaptcha_api_key' );
		register_setting( 'easy-testimonials-submission_form_options-settings-group', 'easy_t_recaptcha_secret_key' );
		register_setting( 'easy-testimonials-submission_form_options-settings-group', 'easy_t_recaptcha_lang' );
		register_setting( 'easy-testimonials-submission_form_options-settings-group', 'easy_t_rating_field_label' );
		register_setting( 'easy-testimonials-submission_form_options-settings-group', 'easy_t_rating_field_description' );	
		register_setting( 'easy-testimonials-submission_form_options-settings-group', 'easy_t_use_rating_field' );	
		register_setting( 'easy-testimonials-submission_form_options-settings-group', 'easy_t_cache_buster', array($this, 'easy_t_bust_options_cache') );
		
		/* Import / Export */
		register_setting( 'easy-testimonials-import-export-settings-group', 'easy_t_hello_t_json_url' );		
		register_setting( 'easy-testimonials-import-export-settings-group', 'easy_t_hello_t_enable_cron' );	
		register_setting( 'easy-testimonials-import-export-settings-group', 'easy_t_cache_buster', array($this, 'easy_t_bust_options_cache') );
		
		/* Hello T */
		register_setting( 'easy-testimonials-private-settings-group', 'easy_t_hello_t_last_time' );
		
		/* Typography options */
		register_setting( 'easy-testimonials-display-settings-group', 'easy_t_body_font_size' );
		register_setting( 'easy-testimonials-display-settings-group', 'easy_t_body_font_color' );
		register_setting( 'easy-testimonials-display-settings-group', 'easy_t_body_font_style' );
		register_setting( 'easy-testimonials-display-settings-group', 'easy_t_body_font_family' );

		register_setting( 'easy-testimonials-display-settings-group', 'easy_t_author_font_size' );
		register_setting( 'easy-testimonials-display-settings-group', 'easy_t_author_font_color' );
		register_setting( 'easy-testimonials-display-settings-group', 'easy_t_author_font_style' );
		register_setting( 'easy-testimonials-display-settings-group', 'easy_t_author_font_family' );

		register_setting( 'easy-testimonials-display-settings-group', 'easy_t_position_font_size' );
		register_setting( 'easy-testimonials-display-settings-group', 'easy_t_position_font_color' );
		register_setting( 'easy-testimonials-display-settings-group', 'easy_t_position_font_style' );
		register_setting( 'easy-testimonials-display-settings-group', 'easy_t_position_font_family' );

		register_setting( 'easy-testimonials-display-settings-group', 'easy_t_date_font_size' );
		register_setting( 'easy-testimonials-display-settings-group', 'easy_t_date_font_color' );
		register_setting( 'easy-testimonials-display-settings-group', 'easy_t_date_font_style' );
		register_setting( 'easy-testimonials-display-settings-group', 'easy_t_date_font_family' );

		register_setting( 'easy-testimonials-display-settings-group', 'easy_t_rating_font_size' );
		register_setting( 'easy-testimonials-display-settings-group', 'easy_t_rating_font_color' );
		register_setting( 'easy-testimonials-display-settings-group', 'easy_t_rating_font_style' );
		register_setting( 'easy-testimonials-display-settings-group', 'easy_t_rating_font_family' );
		
		/* Display settings */
		register_setting( 'easy-testimonials-display-settings-group', 'testimonials_link' );
		register_setting( 'easy-testimonials-display-settings-group', 'easy_t_view_more_link_text' );
		register_setting( 'easy-testimonials-display-settings-group', 'testimonials_image' );
		register_setting( 'easy-testimonials-display-settings-group', 'meta_data_position' );
		register_setting( 'easy-testimonials-display-settings-group', 'easy_t_mystery_man' );
		register_setting( 'easy-testimonials-display-settings-group', 'easy_t_gravatar' );
		register_setting( 'easy-testimonials-display-settings-group', 'easy_t_image_size' );
		register_setting( 'easy-testimonials-display-settings-group', 'easy_t_width' );
		register_setting( 'easy-testimonials-display-settings-group', 'easy_t_cache_buster', array($this, 'easy_t_bust_options_cache') );
		
	}
	
	// don't allow captchas to be enabled unless reCAPTCHA settings are present
	// or Really Simply Captcha is installed
	function enable_captcha_callback($val)
	{
		$can_use_recaptcha = !empty($_POST['easy_t_recaptcha_api_key'])
							 && !empty($_POST['easy_t_recaptcha_secret_key']);
						
		if ( !class_exists('ReallySimpleCaptcha') && !$can_use_recaptcha ) {
			return 0;
		} else {
			return $val;
		}
	}
	
	//function to produce tabs on admin screen
	function easy_t_admin_tabs($current = 'homepage' ) {
	
		$tabs = array( 	'easy-testimonials-settings' => __('Basic Options', 'easy-testimonials'), 
						'easy-testimonials-display-settings' => __('Display Options', 'easy-testimonials'),
						'easy-testimonials-style-settings' => __('Themes', 'easy-testimonials'),
						'easy-testimonials-submission-settings' => __('Submission Form Options', 'easy-testimonials'),
						'easy-testimonials-shortcode-generator' => __('Shortcode Generator', 'easy-testimonials'),
						'easy-testimonials-import-export' => __('Import & Export', 'easy-testimonials'),
						'easy-testimonials-help' => __('Help & Instructions', 'easy-testimonials')
					);
		echo '<div id="icon-themes" class="icon32"><br></div>';
		echo '<h2 class="nav-tab-wrapper">';
			foreach( $tabs as $tab => $name ){
				$class = ( $tab == $current ) ? ' nav-tab-active' : '';
				echo "<a class='nav-tab$class' href='?page=$tab'>$name</a>";
			}
		echo '</h2>';
	}
	
	function settings_page_top(){
		$title = "Easy Testimonials Settings";
		$message = "Easy Testimonials Settings Updated.";
		
		global $pagenow;
		global $current_user;
		get_currentuserinfo();
	?>
	<script type="text/javascript">
	jQuery(function () {
		if (typeof(gold_plugins_init_coupon_box) == 'function') {
			gold_plugins_init_coupon_box();
		}
	});
	</script>
	<?php if(isValidKey()): ?>	
	<div class="wrap easy_testimonials_admin_wrap">
	<?php else: ?>
	<div class="wrap easy_testimonials_admin_wrap not-pro">
	<?php endif; ?>
		<h2><?php echo $title; ?></h2>
		<style type="text/css">			
			fieldset {
				border: 1px solid #ccc !important;
				display: block;
				margin: 20px 0 !important;
				padding: 0 20px !important;
			}
			
			fieldset legend{
				font-size: 18px;
				font-weight: bold;
			}
		</style>
		<?php if(!isValidKey()): ?>		
				<div id="signup_wrapper">
					<?php $this->output_sidebar_coupon_form(); ?>
					<p class="u_to_p"><a href="https://goldplugins.com/our-plugins/easy-testimonials-details/upgrade-to-easy-testimonials-pro/?utm_source=themes">Upgrade to Easy Testimonials Pro now</a> to remove banners like this one.</p>					
					<?php $this->output_hello_t_banner(); ?>
					<div style="clear:right;"></div>
				</div>
				
		<?php endif; ?>
		
		<?php if (isset($_GET['settings-updated']) && $_GET['settings-updated'] == 'true') : ?>
		<div id="message" class="updated fade"><p><?php echo $message; ?></p></div>
		<?php endif;
		
		$this->get_and_output_current_tab($pagenow);
	}
	
	function output_sidebar_coupon_form()
	{
		global $current_user;
		?>
		<div class="topper">
			<h3>Save 20% on Easy Testimonials Pro!</h3>
			<p class="pitch">Sign-up for our newsletter, and we’ll send you a coupon for 20% off your upgrade to Easy Testimonials Pro!</p>
		</div>
		<div id="mc_embed_signup">
			<!--<form action="http://illuminatikarate.us2.list-manage.com/subscribe/post?u=403e206455845b3b4bd0c08dc&amp;id=a70177def0" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>-->
			<form action="https://goldplugins.com/atm/atm.php?u=403e206455845b3b4bd0c08dc&amp;id=a70177def0" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
				<div class="fields_wrapper">
					<label for="mce-NAME">Your Name:</label>
					<input type="text" value="<?php echo (!empty($current_user->display_name) ?  $current_user->display_name : ''); ?>" name="NAME" class="name" id="mce-NAME" placeholder="Your Name">
					<label for="mce-EMAIL">Your Email:</label>
					<input type="email" value="<?php echo (!empty($current_user->user_email) ?  $current_user->user_email : ''); ?>" name="EMAIL" class="email" id="mce-EMAIL" placeholder="email address" required>
					<!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
					<div style="position: absolute; left: -5000px;"><input type="text" name="b_403e206455845b3b4bd0c08dc_6ad78db648" tabindex="-1" value=""></div>
				</div>
				<div class="clear"><input type="submit" value="Send Me The Coupon Now" name="subscribe" id="mc-embedded-subscribe" class="smallBlueButton"></div>
				<p class="secure"><img src="<?php echo plugins_url( 'img/lock.png', __FILE__ ); ?>" alt="Lock" width="16px" height="16px" />We respect your privacy.</p>
				
				<input type="hidden" id="mc-upgrade-plugin-name" value="Easy Testimonials Pro" />
				<input type="hidden" id="mc-upgrade-link-per" value="https://goldplugins.com/purchase/easy-testimonials-pro/single?promo=newsub20" />
				<input type="hidden" id="mc-upgrade-link-biz" value="https://goldplugins.com/purchase/easy-testimonials-pro/business?promo=newsub20" />
				<input type="hidden" id="mc-upgrade-link-dev" value="https://goldplugins.com/purchase/easy-testimonials-pro/developer?promo=newsub20" />

				<div class="features">
					<strong>When you upgrade, you'll instantly unlock:</strong>
					<ul>
						<li>75+ Professionally Designed Themes</li>
						<li>Advanced styling and customization options</li>
						<li>A customizable Testimonial submission form</li>
						<li>7 new transitions for your testimonial widgets</li>
						<li>Custom image sizes for your avatars</li>
						<li>Import/Export your Testimonials</li>
						<li>Outstanding support from our developers</li>
						<li>Remove all banners from the admin area</li>
						<li>And more! We add new features regularly.</li>
					</ul>
				</div>
				
				<div class="customer_testimonial">
						<div class="stars">
							<span class="dashicons dashicons-star-filled"></span>
							<span class="dashicons dashicons-star-filled"></span>
							<span class="dashicons dashicons-star-filled"></span>
							<span class="dashicons dashicons-star-filled"></span>
							<span class="dashicons dashicons-star-filled"></span>
						</div>
						“Tried and is great. This is light and has all the features I need and more! Awesome!”
						<p class="author">&mdash; davidwalt  <a href="https://wordpress.org/support/topic/excellent-plugin-941" target="_blank">via WordPress.org</a></p>
				</div>
				<input type="hidden" id="gold_plugins_already_subscribed" name="gold_plugins_already_subscribed" value="0" />
			</form>
		</div>			
		<?php			
	}
	
	function output_hello_t_banner()
	{
		echo '<div class="sidebar_hello_t hello_t_banner" style="padding-top:1px; padding-left: 30px;">'; 
		echo '<h3><strong>Need more Testimonials? We can help.</strong></h3>
				<p>We are happy to introduce <strong>Hello Testimonials</strong>, a new system that helps you collect new testimonials from your customers automatically. Whenever you add a new customer to the system, they\'ll automatically receive a personalized email asking them to leave a testimonial.</p><p>For a limited time, we\'re offering a 14-Day Free Trial of the Hello Testimonials to users of Easy Testimonials, so you have nothing to lose by giving it a try.</p><p><a class="smallBlueButton" href="http://hellotestimonials.com/p/welcome-easy-testimonials-users/" title="Start Your Free Trial">Start Your Free Trial &raquo;</a></p>
				<br/>';
		echo "</div>";
		echo '<p class="u_to_p u_to_p_main_col"><a href="https://goldplugins.com/our-plugins/easy-testimonials-details/upgrade-to-easy-testimonials-pro/?utm_source=themes">Upgrade to Easy Testimonials Pro now</a> to remove banners like this one.</p>';				
	}
	
	function get_and_output_current_tab($pagenow){
		$tab = $_GET['page'];
		
		$this->easy_t_admin_tabs($tab); 
				
		return $tab;
	}
	
	function basic_settings_page(){	
		$this->settings_page_top();
		
		?><form method="post" action="options.php"><?php
		
		settings_fields( 'easy-testimonials-settings-group' ); ?>			
			
			<h3>Basic Options</h3>
			
			<p>Use the below options to control various bits of output.</p>
			
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label>Testimonials Style</a></th>
					<td><p class="description">Our Theme Options have moved!  <a href="?page=easy-testimonials-style-settings">Click here to view the new tab</a>.</p></td>
				</tr>
			</table>
			
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label for="easy_t_custom_css">Custom CSS</a></th>
					<td><textarea name="easy_t_custom_css" id="easy_t_custom_css" style="width: 250px; height: 250px;"><?php echo get_option('easy_t_custom_css', ''); ?></textarea>
					<p class="description">Input any Custom CSS you want to use here.  The plugin will work without you placing anything here - this is useful in case you need to edit any styles for it to work with your theme, though.<br/> For a list of available classes, click <a href="https://goldplugins.com/documentation/easy-testimonials-documentation/html-css-information-for-easy-testimonials/" target="_blank">here</a>.</p></td>
				</tr>
			</table>
			
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label for="easy_t_disable_cycle2">Disable Cycle2 Output</label></th>
					<td><input type="checkbox" name="easy_t_disable_cycle2" id="easy_t_disable_cycle2" value="1" <?php if(get_option('easy_t_disable_cycle2', false)){ ?> checked="CHECKED" <?php } ?>/>
					<p class="description">If checked, we won't include the Cycle2 JavaScript file.  If you suspect you are having JavaScript compatibility issues with our plugin, please try checking this box.</p>
					</td>
				</tr>
			</table>
			
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label for="easy_t_use_cycle_fix">Use Cycle Fix</label></th>
					<td><input type="checkbox" name="easy_t_use_cycle_fix" id="easy_t_use_cycle_fix" value="1" <?php if(get_option('easy_t_use_cycle_fix', false)){ ?> checked="CHECKED" <?php } ?>/>
					<p class="description">If checked, we will try and trigger Cycle2 a different way.  If you suspect you are having JavaScript compatibility issues with our plugin, please try checking this box.  NOTE: If you have Disable Cycle2 Output checked, this box will have no effect.</p>
					</td>
				</tr>
			</table>
			
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label for="easy_t_apply_content_filter">Apply The Content Filter</label></th>
					<td><input type="checkbox" name="easy_t_apply_content_filter" id="easy_t_apply_content_filter" value="1" <?php if(get_option('easy_t_apply_content_filter', true)){ ?> checked="CHECKED" <?php } ?>/>
					<p class="description">If checked, we will apply the content filter to Testimonial content.  Use this if you are experiencing problems with other plugins applying their shortcodes, etc, to your Testimonial content.</p>
					</td>
				</tr>
			</table>
			
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label for="easy_t_show_in_search">Show in Search</label></th>
					<td><input type="checkbox" name="easy_t_show_in_search" id="easy_t_show_in_search" value="1" <?php if(get_option('easy_t_show_in_search', true)){ ?> checked="CHECKED" <?php } ?>/>
					<p class="description">If checked, we will Show your Testimonials in the public site search in WordPress.</p>
					</td>
				</tr>
			</table>

			<h3>Shortcode Options</h3>
			<p class="description">Use these fields to control our registered shortcodes.  If you are experiencing issues where our shortcodes do not display at all, you can try changing them here.</p>
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label for="ezt_random_testimonial_shortcode">Random Testimonial Shortcode</label></th>
					<td><input type="text" name="ezt_random_testimonial_shortcode" id="ezt_random_testimonial_shortcode" value="<?php echo get_option('ezt_random_testimonial_shortcode', 'random_testimonial'); ?>"  style="width: 250px" />
					<p class="description">This is the shortcode for displaying random testimonials.  If you suspect you are having compatibility issues with shortcodes already registered by your theme or other plugins, try changing this value and any corresponding shortcodes you are using on your site.</p>
					</td>
				</tr>
			</table>
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label for="ezt_single_testimonial_shortcode">Single Testimonial Shortcode</label></th>
					<td><input type="text" name="ezt_single_testimonial_shortcode" id="ezt_single_testimonial_shortcode" value="<?php echo get_option('ezt_single_testimonial_shortcode', 'single_testimonial'); ?>"  style="width: 250px" />
					<p class="description">This is the shortcode for displaying a single testimonial.  If you suspect you are having compatibility issues with shortcodes already registered by your theme or other plugins, try changing this value and any corresponding shortcodes you are using on your site.</p>
					</td>
				</tr>
			</table>
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label for="ezt_testimonials_shortcode">Testimonials List Shortcode</label></th>
					<td><input type="text" name="ezt_testimonials_shortcode" id="ezt_testimonials_shortcode" value="<?php echo get_option('ezt_testimonials_shortcode', 'testimonials'); ?>"  style="width: 250px" />
					<p class="description">This is the shortcode for displaying a list of testimonials.  If you suspect you are having compatibility issues with shortcodes already registered by your theme or other plugins, try changing this value and any corresponding shortcodes you are using on your site.</p>
					</td>
				</tr>
			</table>
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label for="ezt_cycle_testimonial_shortcode">Testimonials Cycle Shortcode</label></th>
					<td><input type="text" name="ezt_cycle_testimonial_shortcode" id="ezt_cycle_testimonial_shortcode" value="<?php echo get_option('ezt_cycle_testimonial_shortcode', 'testimonials_cycle'); ?>"  style="width: 250px" />
					<p class="description">This is the shortcode for displaying cycled testimonials.  If you suspect you are having compatibility issues with shortcodes already registered by your theme or other plugins, try changing this value and any corresponding shortcodes you are using on your site.</p>
					</td>
				</tr>
			</table>
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label for="ezt_submit_testimonial_shortcode">Testimonial Submission Form Shortcode</label></th>
					<td><input type="text" name="ezt_submit_testimonial_shortcode" id="ezt_submit_testimonial_shortcode" value="<?php echo get_option('ezt_submit_testimonial_shortcode', 'submit_testimonial'); ?>"  style="width: 250px" />
					<p class="description">This is the shortcode for displaying the testimonial submission form.  If you suspect you are having compatibility issues with shortcodes already registered by your theme or other plugins, try changing this value and any corresponding shortcodes you are using on your site.</p>
					</td>
				</tr>
			</table>
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label for="ezt_testimonials_count_shortcode">Testimonials Count Shortcode</label></th>
					<td><input type="text" name="ezt_testimonials_count_shortcode" id="ezt_testimonials_count_shortcode" value="<?php echo get_option('ezt_testimonials_count_shortcode', 'testimonials_count'); ?>"  style="width: 250px" />
					<p class="description">This is the shortcode for displaying the count of Testimonials.  If you suspect you are having compatibility issues with shortcodes already registered by your theme or other plugins, try changing this value and any corresponding shortcodes you are using on your site.</p>
					</td>
				</tr>
			</table>
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label for="ezt_testimonials_grid_shortcode">Testimonials Grid Shortcode</label></th>
					<td><input type="text" name="ezt_testimonials_grid_shortcode" id="ezt_testimonials_grid_shortcode" value="<?php echo get_option('ezt_testimonials_grid_shortcode', 'testimonials_grid'); ?>"  style="width: 250px" />
					<p class="description">This is the shortcode for displaying the grid of Testimonials.  If you suspect you are having compatibility issues with shortcodes already registered by your theme or other plugins, try changing this value and any corresponding shortcodes you are using on your site.</p>
					</td>
				</tr>
			</table>
			
			<?php include('registration_options.php'); ?>
			
			<p class="submit">
				<input type="submit" class="button-primary" value="<?php _e('Save Changes', 'easy-testimonials') ?>" />
			</p>
		</form>
		</div><?php 
	}
		
	function style_settings_page(){
		$this->settings_page_top();
		?><form method="post" action="options.php"><?php
		
		if(!isValidKey()): ?>
			<p class="plugin_is_not_registered"><a href="https://goldplugins.com/our-plugins/easy-testimonials-details/upgrade-to-easy-testimonials-pro/?utm_source=themes"><?php _e('Upgrade to Easy Testimonials Pro now', 'easy-testimonials');?></a> <?php _e('to unlock all 75+ themes!', 'easy-testimonials');?> </p>
		<?php endif; ?>
				
		<?php settings_fields( 'easy-testimonials-style-settings-group' ); ?>	
		
		<h3>Style &amp; Theme Options</h3>
		
		<p class="description">Select which style you want to use.  If 'No Style' is selected, only your Theme's CSS, and any Custom CSS you've added, will be used.</p>
				
		<table class="form-table easy_t_options">
			<tr valign="top">
				<td>
					<h4>Standard Themes</h4>
					<p class="easy-t-radio-button"><input type="radio" name="testimonials_style" id="default_style" value="default_style" <?php if(get_option('testimonials_style', 'default_style') == "default_style"): echo 'checked="CHECKED"'; endif; ?>><label for="default_style">Default Style<br/><img src="<?php echo plugins_url('img/easy-t-default-style.png', __FILE__); ?>"/></label></p>
					<p class="easy-t-radio-button"><input type="radio" name="testimonials_style" id="dark_style" value="dark_style" <?php if(get_option('testimonials_style') == "dark_style"): echo 'checked="CHECKED"'; endif; ?>><label for="dark_style">Dark Style<br/><img src="<?php echo plugins_url('img/easy-t-dark-style.png', __FILE__); ?>"/></label></p>
					<p class="easy-t-radio-button"><input type="radio" name="testimonials_style" id="light_style" value="light_style" <?php if(get_option('testimonials_style') == "light_style"): echo 'checked="CHECKED"'; endif; ?>><label for="light_style">Light Style<br/><img src="<?php echo plugins_url('img/easy-t-light-style.png', __FILE__); ?>"/></label></p>
					<p class="easy-t-radio-button"><input type="radio" name="testimonials_style" id="clean_style" value="clean_style" <?php if(get_option('testimonials_style') == "clean_style"): echo 'checked="CHECKED"'; endif; ?>><label for="clean_style">Clean Style<br/><img src="<?php echo plugins_url('img/easy-t-clean-style.png', __FILE__); ?>"/></label></p>
					<p class="easy-t-radio-button"><input type="radio" name="testimonials_style" id="no_style" value="no_style" <?php if(get_option('testimonials_style') == "no_style"): echo 'checked="CHECKED"'; endif; ?>><label for="no_style">No Style<br/><img src="<?php echo plugins_url('img/easy-t-no-style.png', __FILE__); ?>"/></label></p>
				</td>
			</tr>
		</table>
		
		<p class="submit">
			<input type="submit" class="button-primary" value="<?php _e('Save Changes', 'easy-testimonials') ?>" />
		</p>
		
		<h3>Pro Themes</h3>		
			
		<?php include('theme_options.php'); ?>
				
		<p class="submit">
			<input type="submit" class="button-primary" value="<?php _e('Save Changes', 'easy-testimonials') ?>" />
		</p>
	</form>
	</div><?php 
	}

	function display_settings_page(){
		$this->settings_page_top();
		?><form method="post" action="options.php"><?php
		
		if(!isValidKey()): ?>
			<!--<p><a href="https://goldplugins.com/our-plugins/easy-testimonials-details/upgrade-to-easy-testimonials-pro/?utm_source=themes"><?php _e('Upgrade to Easy Testimonials Pro now', 'easy-testimonials');?></a> <?php _e('and get access to new features and settings.', 'easy-testimonials');?> </p>-->
		<?php endif; ?>
				
		<?php settings_fields( 'easy-testimonials-display-settings-group' ); ?>	
		
		<h3>Display Options</h3>
		
		<fieldset>
			<legend>Font Styles</legend>
			<?php if(!isValidKey()):?>
			<p class="easy_testimonials_not_registered"><strong>These features require Easy Testimonials Pro.</strong>&nbsp;&nbsp;&nbsp;<a class="button" target="blank" href="https://goldplugins.com/our-plugins/easy-testimonials-details/upgrade-to-easy-testimonials-pro/?utm_source=easy_testimonials_settings&utm_campaign=upgrade&utm_banner=display_options">Upgrade Now To Enable</a></p>
			<?php endif;?>
			<table class="form-table">
				<?php $this->typography_input('easy_t_body_*', 'Testimonial Body', 'Font style of the body.'); ?>
				<?php $this->typography_input('easy_t_author_*', 'Author\'s Name', 'Font style of the author\'s name.'); ?>
				<?php $this->typography_input('easy_t_position_*', 'Author\'s Position / Job Title', 'Font style of the author\'s Position (Job Title).'); ?>
				<?php $this->typography_input('easy_t_date_*', 'Date', 'Font style of the testimonial date.'); ?>
				<?php $this->typography_input('easy_t_rating_*', 'Rating (when text)', 'Font style of the rating (used only when ratings are displayed as text).'); ?>
			</table>
		</fieldset>
		<fieldset>
			<legend>Testimonial Images</legend>
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label for="testimonials_image">Show Testimonial Image</label></th>
					<td><input type="checkbox" name="testimonials_image" id="testimonials_image" value="1" <?php if(get_option('testimonials_image', true)){ ?> checked="CHECKED" <?php } ?>/>
					<p class="description">If checked, the Image will be shown next to the Testimonial.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="easy_t_image_size">Testimonial Image Size</a></th>
					<td>
						<select name="easy_t_image_size" id="easy_t_image_size">	
							<?php easy_t_output_image_options(); ?>
						</select>
						<p class="description">Select which size image to display with your Testimonials.  Defaults to 50px X 50px.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="easy_t_gravatar">Use Gravatar</label></th>
					<td><input type="checkbox" name="easy_t_gravatar" id="easy_t_gravatar" value="1" <?php if(get_option('easy_t_gravatar', true)){ ?> checked="CHECKED" <?php } ?>/>
					<p class="description">If checked, and you are displaying Testimonial Images, we will use a Gravatar if one is found matching the E-Mail Address on the Testimonial.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="easy_t_mystery_man">Use Mystery Man</label></th>
					<td><input type="checkbox" name="easy_t_mystery_man" id="easy_t_mystery_man" value="1" <?php if(get_option('easy_t_mystery_man', true)){ ?> checked="CHECKED" <?php } ?>/>
					<p class="description">If checked, and you are displaying Testimonial Images, the Mystery Man avatar will be used for any missing images.</p>
					</td>
				</tr>
			</table>
		</fieldset>
		<fieldset>
			<legend>View More Testimonials Link</legend>
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label for="testimonials_link">Testimonials View More Link</label></th>
					<td><input type="text" name="testimonials_link" id="testimonials_link" value="<?php echo get_option('testimonials_link', ''); ?>"  style="width: 250px" />
					<p class="description">This is the URL of the 'View More' Link.  If not set, no View More Link is output.  If set, View More Link will be output next to testimonial that will go to this page.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="easy_t_view_more_link_text">Testimonials View More Link Text</label></th>
					<td><input type="text" name="easy_t_view_more_link_text" id="easy_t_view_more_link_text" value="<?php echo get_option('easy_t_view_more_link_text', 'Read More Testimonials'); ?>"  style="width: 250px" />
					<p class="description">The Value of the View More Link text.  This defaults to Read More, but can be changed.</p>
					</td>
				</tr>
			</table>
		</fieldset>
		<fieldset>
			<legend>Custom Fields</legend>
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label for="meta_data_position">Show Testimonial Info Above Testimonial</label></th>
					<td><input type="checkbox" name="meta_data_position" id="meta_data_position" value="1" <?php if(get_option('meta_data_position', false)){ ?> checked="CHECKED" <?php } ?>/>
					<p class="description">If checked, the Testimonial Custom Fields will be displayed Above the Testimonial.  Defaults to Displaying Below the Testimonial.  Note: the Testimonial Image will be displayed to the left of this information.  NOTE: Checking this may have adverse affects on certain Styles.</p>
					</td>
				</tr>
			</table>
		</fieldset>
		<fieldset>
			<legend>Default Testimonials Width</legend>
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label for="easy_t_width">Default Testimonials Width</label></th>
					<td><input type="text" name="easy_t_width" id="easy_t_width" value="<?php echo get_option('easy_t_width', ''); ?>"  style="width: 250px" />
					<p class="description">If you want, you can set a global width for Testimonials.  This can be left blank and it can also be overrode directly, via the shortcode.</p>
					</td>
				</tr>
			</table>
		</fieldset>
		<p class="submit">
			<input type="submit" class="button-primary" value="<?php _e('Save Changes', 'easy-testimonials') ?>" />
		</p>
	</form>
	</div><?php 
	}
	
	function shortcode_generator_page() {
		$this->settings_page_top();
		$testimonial_categories = get_terms( 'easy-testimonial-category', 'orderby=title&hide_empty=0' );
		
		//load options
		include("lib/config.php");
				
		?>
		
		<h3>Shortcode Generator</h3>
		
		<p>Select the options you'd like, and then click the Generate button. You'll get a shortcode that you can copy and paste into any post or page.</p>
		<p>This generator will create a shortcode for displaying a list of testimonials or a testimonial slider.  Also available is the <code>[single_testimonial]</code> shortcode, for displaying a specific Testimonial.  <a href="https://goldplugins.com/documentation/easy-testimonials-documentation/easy-testimonials-installation-and-usage-instructions/" target="_blank">Click here</a> for more information.</p>
		
		<form id="easy_t_shortcode_generator">
			<table class="form-table">
				<tbody>						
					<tr>
						<th scope="row">
							<div class="sc_gen_control_group">
								<label for="sc_gen_count">Count</label>
							</div>
						</th>
						<td>
							<input type="text" class="valid_int" id="sc_gen_count" value="5" />
							<p class="description">How many testimonials would you like to show?</p>
						</td>
					</tr>
					
					<tr>
						<th scope="row">
							<div class="sc_gen_control_group">
								<label for="sc_gen_order_by">Order By</label>
							</div>
						</th>
						<td>
							<div class="inline-select-wrapper">
								<select id="sc_gen_order_by">
									<option value="rand">Random</option>
									<option value="id">ID</option>
									<option value="author">Author</option>
									<option value="title">Title</option>
									<option value="name">Name</option>
									<option value="date">Date</option>
									<option value="modified">Last Modified</option>
									<option value="parent">Parent ID</option>								
								</select>
							</div>
							<div class="inline-select-wrapper">
								<select id="sc_gen_order_dir">
									<option value="asc">Ascending (ASC)</option>
									<option value="desc">Descending (DESC)</option>
								</select>
							</div>
						</td>
					</tr>
					
					<tr>
						<th scope="row">
							<div class="sc_gen_control_group">
								<label for="sc_gen_category">Filter By Category</label>
							</div>
						</th>
						<td>
							<select id="sc_gen_category">
								<option value="all">All Categories</option>
								<?php foreach($testimonial_categories as $cat):?>
								<option value="<?php echo $cat->slug?>"><?php echo htmlentities($cat->name)?></option>
								<?php endforeach; ?>
							</select>
							<p class="description"><a href="<?php echo admin_url('edit-tags.php?taxonomy=easy-testimonial-category&post_type=testimonial'); ?>">Manage Testimonial Categories</a></p>
						</td>
					</tr>
					
					<tr>
						<th scope="row">
							Title
						</th>
						<td>
							<div class="sc_gen_control_group">
								<label for="sc_gen_show_title">
									<input type="checkbox" class="checkbox" id="sc_gen_show_title" value="yes" />
									Show the titles?
								</label>
							</div>
						</td>
					</tr>	
					
					<tr>
						<th scope="row">
							<div class="sc_gen_control_group">
								<label for="sc_gen_width">Testimonial Width</label>
							</div>
						</th>
						<td>
							<input type="text" id="sc_gen_width" value="<?php echo get_option('easy_t_width',''); ?>" />
							<p class="description">Set the desired width of the testimonial, here.  If none is set, Testimonials will attempt to size to the width of their container.  For example: 500px</p>
						</td>
					</tr>
					
					<tr>
						<th scope="row">
							Testimonial Length
						</th>
						<td>
							<div class="sc_gen_control_group sc_gen_control_group_radio">
								<label title="Use an excerpt for long testimonials">
									<input type="radio" value="yes" id="sc_gen_use_excerpt_yes" name="sc_gen_use_excerpt" checked="checked">
									<span>Use an excerpt for long testimonials</span>
								</label>
								<label title="Always display Testimonials at their full length">
									<input type="radio" value="no" id="sc_gen_use_excerpt_no" name="sc_gen_use_excerpt">
									<span>Always display Testimonials at their full length</span>
								</label>
							</div>
						</td>
					</tr>
					
					<tr>
						<th scope="row">
							Featured Images
						</th>
						<td>							
							<div class="sc_gen_control_group">
								<label for="sc_gen_show_thumbs">
									<input type="checkbox" class="checkbox" id="sc_gen_show_thumbs" value="yes" />
									Show Featured Images?
								</label>
							</div>
						</td>
					</tr>
					
					<tr>
						<th scope="row">
							Show Publication Date
						</th>
						<td>							
							<div class="sc_gen_control_group">
								<label for="sc_gen_show_date">
									<input type="checkbox" class="checkbox" id="sc_gen_show_date" value="yes" />
									Show Publication Date?
								</label>
							</div>
						</td>
					</tr>
					
					<tr>
						<th scope="row">
							Show "Location / Product Reviewed / Other" Field
						</th>
						<td>							
							<div class="sc_gen_control_group">
								<label for="sc_gen_show_other">
									<input type="checkbox" class="checkbox" id="sc_gen_show_other" value="yes" />
									Show "Location / Product Reviewed / Other" Field?
								</label>
							</div>
						</td>
					</tr>
					
					<tr>
						<th scope="row">
							Ratings
						</th>
						<td>
							<div class="sc_gen_control_group sc_gen_control_group_radio">
								<label title="Hide Ratings">
									<input type="radio" value="hide" id="sc_gen_show_ratings_hide" name="sc_gen_show_ratings" checked="checked">
									<span>Hide Ratings</span>
								</label>
								<label title="Show Rating Before Testimonial">
									<input type="radio" value="before" id="sc_gen_show_ratings_before" name="sc_gen_show_ratings">
									<span>Show Rating Before The Testimonial</span>
								</label>
								<label title="Show Rating After Testimonial">
									<input type="radio" value="after" id="sc_gen_show_ratings_after" name="sc_gen_show_ratings">
									<span>Show Rating After The Testimonial</span>
								</label>
								<label title="Show Rating As Stars">
									<input type="radio" value="stars" id="sc_gen_show_ratings_stars" name="sc_gen_show_ratings">
									<span>Show Rating As Stars</span>
								</label>
							</div>
						</td>
					</tr>	

					<tr>
						<th scope="row">
							<div class="sc_gen_control_group">
								<label for="sc_gen_theme">Select A Theme</label>
							</div>
						</th>
						<td>
							<select id="sc_gen_theme">
								<optgroup label="Free Options">
									<?php foreach($free_theme_array as $theme_slug => $theme_name): ?>
									<option value="<?php echo $theme_slug; ?>" <?php if(get_option('testimonials_style', 'default_style') == $theme_slug){ ?> selected="selected" <?php } ?>><?php echo $theme_name; ?></option>
									<?php endforeach; ?>
								</optgroup>
								<optgroup label="PRO Options">		
									<?php foreach($pro_theme_array as $top_level_theme_name => $theme_array): ?>
										<?php foreach($theme_array as $theme_slug => $theme_name): ?>
											<option <?php if(!isValidKey()): ?>disabled="disabled" <?php endif; ?>value="<?php echo $theme_slug; ?>" <?php if(get_option('testimonials_style', 'default_style') == $theme_slug){ ?> selected="selected" <?php } ?>><?php echo $theme_name; ?></option>
										<?php endforeach; ?>
									<?php endforeach; ?>	
								</optgroup>
							</select>
							<?php if(!isValidKey()): ?>
							<p class="description"><a href="https://goldplugins.com/our-plugins/easy-testimonials-details/upgrade-to-easy-testimonials-pro/?utm_source=themes">Upgrade To Unlock All The Themes</a></p>
							<?php endif; ?>
						</td>
					</tr>
					
					<tr>
						<th scope="row">
							Pagination
						</th>
						<td>
							<div class="sc_gen_control_group">
								<label for="sc_gen_paginate">
									<input type="checkbox" class="checkbox" id="sc_gen_paginate" value="yes" />
									Paginate the Testimonials?
								</label>
							</div>
						</td>
					</tr>		
				
					<tr class="pagination_option">
						<th scope="row">
							<div class="sc_gen_control_group">
								<label for="sc_gen_num_per_page">Testimonials Per Page</label>
							</div>
						</th>
						<td>
							<input type="text" class="valid_int" id="sc_gen_num_per_page" value="4" />
							<p class="description">The number of Testimonials to show per page.</p>
						</td>
					</tr>	
					
					<tr>
						<th scope="row">
							Testimonial Slider
						</th>
						<td>							
							<div class="sc_gen_control_group">
								<label for="sc_gen_use_slider">
									<input type="checkbox" class="checkbox" id="sc_gen_use_slider" value="yes" />
									Output your testimonials in a slider?
								</label>
							</div>
						</td>
					</tr>					

					<tr class="slider_option">
						<th scope="row">
							<div class="sc_gen_control_group">
								<label for="sc_gen_transition">Select A Transition</label>
							</div>
						</th>
						<td>
							<select id="sc_gen_transition">
								<optgroup label="Standard Options">
									<option value="fade">Fade</option>
									<option value="fadeout">Fade Out</option>
									<option value="none">None</option>
								</optgroup>
								<optgroup label="PRO Options">								
									<?php if(!isValidKey()): ?>
									<option value="scrollVert" disabled="disabled">Scroll (Vertical)</option>
									<option value="scrollHorz" disabled="disabled">Scroll (Horizontal)</option>
									<option value="fade" disabled="disabled">Fade In</option>
									<option value="fadeout" disabled="disabled">Fade Out</option>
									<option value="flipHorz" disabled="disabled">Flip (Horizontal)</option>
									<option value="flipVert" disabled="disabled">Flip (Vertical)</option>
									<option value="tileSlide" disabled="disabled">Slide</option>
									<?php else: ?>
									<option value="scrollVert">Scroll (Vertical)</option>
									<option value="scrollHorz">Scroll (Horizontal)</option>
									<option value="fade">Fade In</option>
									<option value="fadeout">Fade Out</option>
									<option value="flipHorz">Flip (Horizontal)</option>
									<option value="flipVert">Flip (Vertical)</option>
									<option value="tileSlide">Slide</option>
									<?php endif; ?>
								</optgroup>
							</select>
							<?php if(!isValidKey()): ?>
							<p class="description"><a href="https://goldplugins.com/our-plugins/easy-testimonials-details/upgrade-to-easy-testimonials-pro/?utm_source=transitions">Upgrade To Unlock All The Transitions</a></p>
							<?php endif; ?>
						</td>
					</tr>
				
					<tr class="slider_option">
						<th scope="row">
							<div class="sc_gen_control_group">
								<label for="sc_gen_slider_timer">Time Between Slides</label>
							</div>
						</th>
						<td>
							<input type="text" class="valid_int" id="sc_gen_slider_timer" value="4" />
							<p class="description">The number of seconds to pause on each Testimonial</p>
						</td>
					</tr>
					
					<tr class="slider_option">
						<th scope="row">
							<div class="sc_gen_control_group">
								<label for="sc_gen_slider_testimonials_per_slide">Testimonials Per Slide</label>
							</div>
						</th>
						<td>
							<input type="text" class="valid_int" id="sc_gen_slider_testimonials_per_slide" value="1" />
							<p class="description">The number of Testimonials to show on each slide</p>
						</td>
					</tr>				
					

					<tr class="slider_option">
						<th scope="row">
							Pause On Hover
						</th>
						<td>							
							<div class="sc_gen_control_group">
								<label for="sc_gen_pause_on_hover">
									<input type="checkbox" class="checkbox" id="sc_gen_pause_on_hover" value="yes" />
									Pause On Hover
								</label>
							</div>
						</td>
					</tr>

					<tr class="slider_option">
						<th scope="row">
							Disable Auto Transition
						</th>
						<td>							
							<div class="sc_gen_control_group">
								<label for="sc_gen_paused">
									<input type="checkbox" class="checkbox" id="sc_gen_paused" value="yes" />
									Disable Automatic Transitions (requires Pager Icons or Previous and Next Buttons to advance slides.)
								</label>
							</div>
						</td>
					</tr>						

					<tr class="slider_option">
						<th scope="row">
							Show Pager Icons
						</th>
						<td>							
							<div class="sc_gen_control_group">
								<label for="sc_gen_show_pager">
									<input type="checkbox" class="checkbox" id="sc_gen_show_pager" value="yes" />
									Show Pager Icons Below My Testimonials
								</label>
							</div>
						</td>
					</tr>					

					<tr class="slider_option">
						<th scope="row">
							Show Previous and Next Buttons
						</th>
						<td>							
							<div class="sc_gen_control_group">
								<label for="sc_gen_show_prev_next">
									<input type="checkbox" class="checkbox" id="sc_gen_show_prev_next" value="yes" />
									Show Previous and Next Buttons Below My Slideshow
								</label>
							</div>
						</td>
					</tr>
					
					<tr class="slider_option">
						<th scope="row">
							Auto Height Options
						</th>
						<td>
							<div class="sc_gen_control_group sc_gen_control_group_radio">
								<label title="Auto Adjust Height">
									<input type="radio" value="yes" id="sc_gen_auto_fit_container" name="sc_gen_auto_fit" checked="checked">
									<span>Automatically adjust the height of the slider to fit each testimonial</span>
								</label>
								<label title="Calculate Tallest Height">
									<input type="radio" value="no" id="sc_gen_auto_fit_calc" name="sc_gen_auto_fit">
									<span>Automatically set the height of the slider to fit the tallest testimonial</span>
								</label>
							</div>
						</td>
					</tr>
					
				</tbody>
			</table>
			<p class="submit">
				<button id="sc_generate" class="button button-primary" type="button">Build My Shortcode!</button>
			</p>
			
			<div id="sc_gen_output_wrapper">
				<textarea id="sc_gen_output" rows="4" cols="80"></textarea>
			</div>
			
		</form>
		
		
		</div><?php 
	}
		
	function submission_settings_page(){
		$this->settings_page_top();
		?><form method="post" action="options.php">
		
		<?php if(!isValidKey()): ?>
			<p><a href="https://goldplugins.com/our-plugins/easy-testimonials-details/upgrade-to-easy-testimonials-pro/?utm_source=themes"><?php _e('Upgrade to Easy Testimonials Pro now', 'easy-testimonials');?></a> <?php _e('and get access to new features and settings.', 'easy-testimonials');?> </p>
		<?php endif; ?>
		
		<?php settings_fields( 'easy-testimonials-submission_form_options-settings-group' ); ?>		
		
		<h3>Submission Form Options</h3>
		
		<div class="ezt_submission_form_settings">
		
		<p>Use the below options to control the look and feel of the testimonial submission form.</p>
		
		<fieldset>
			<legend>Title Field:</legend>			
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label for="easy_t_title_field_label">"Title" Field Label</label></th>
					<td><input type="text" name="easy_t_title_field_label" id="easy_t_title_field_label" <?php if(!isValidKey()): ?>disabled="disabled"<?php endif; ?> value="<?php echo get_option('easy_t_title_field_label', 'Title'); ?>" />
					<p class="description">This is the label of the first field in the form, which defaults to "Title".  Contents of this field will be passed through to the Title field inside WordPress.</p>
					</td>
				</tr>
			</table>
			
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label for="easy_t_title_field_description">"Title" Field Description</label></th>
					<td><textarea name="easy_t_title_field_description" id="easy_t_title_field_description" <?php if(!isValidKey()): ?>disabled="disabled"<?php endif; ?>><?php echo get_option('easy_t_title_field_description', 'Please give your Testimonial a Title.'); ?></textarea>
					<p class="description">This is the description below the first field in the form.</p>
					</td>
				</tr>
			</table>
		</fieldset>
		
		<fieldset>
			<legend>Name Field:</legend>		
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label for="easy_t_name_field_label">"Name" Field Label</label></th>
					<td><input type="text" name="easy_t_name_field_label" id="easy_t_name_field_label" <?php if(!isValidKey()): ?>disabled="disabled"<?php endif; ?> value="<?php echo get_option('easy_t_name_field_label', 'Name'); ?>" />
					<p class="description">This is the label of the second field in the form, which defaults to "Name."  Contents of this field will be passed through to the Name field inside WordPress.</p>
					</td>
				</tr>
			</table>
						
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label for="easy_t_name_field_description">"Name" Field Description</label></th>
					<td><textarea name="easy_t_name_field_description" id="easy_t_name_field_description" <?php if(!isValidKey()): ?>disabled="disabled"<?php endif; ?>><?php echo get_option('easy_t_name_field_description', 'Please enter your Full Name.'); ?></textarea>
					<p class="description">This is the description below the name field.</p>
					</td>
				</tr>
			</table>
			
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label for="easy_t_hide_name_field">Disable "Name" Field Display</label></th>
					<td><input type="checkbox" name="easy_t_hide_name_field" id="easy_t_hide_name_field" <?php if(!isValidKey()): ?>disabled="disabled"<?php endif; ?> value="1" <?php if(get_option('easy_t_hide_name_field', 0)){ ?> checked="CHECKED" <?php } ?>/>
					<p class="description">If checked, the name field will not be displayed in the form .</p>
					</td>
				</tr>
			</table>
		</fieldset>
		
		<fieldset>
			<legend>E-Mail Field:</legend>		
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label for="easy_t_email_field_label">"E-Mail" Field Label</label></th>
					<td><input type="text" name="easy_t_email_field_label" id="easy_t_email_field_label" <?php if(!isValidKey()): ?>disabled="disabled"<?php endif; ?> value="<?php echo get_option('easy_t_email_field_label', 'Your E-Mail Address'); ?>" />
					<p class="description">This is the label of the client email field in the form, shown after the "Name" field, which defaults to "E-Mail."  Contents of this field will be passed through to the E-Mail Address field inside WordPress.</p>
					</td>
				</tr>
			</table>
						
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label for="easy_t_email_field_description">"E-Mail" Field Description</label></th>
					<td><textarea name="easy_t_email_field_description" id="easy_t_email_field_description" <?php if(!isValidKey()): ?>disabled="disabled"<?php endif; ?>><?php echo get_option('easy_t_email_field_description', 'Please enter your e-mail address.  This information will not be publicly displayed.'); ?></textarea>
					<p class="description">This is the description below the E-Mail field.</p>
					</td>
				</tr>
			</table>
			
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label for="easy_t_hide_email_field">Disable "E-Mail" Field Display</label></th>
					<td><input type="checkbox" name="easy_t_hide_email_field" id="easy_t_hide_email_field" <?php if(!isValidKey()): ?>disabled="disabled"<?php endif; ?> value="1" <?php if(get_option('easy_t_hide_email_field', 0)){ ?> checked="CHECKED" <?php } ?>/>
					<p class="description">If checked, the E-Mail Address field will not be displayed in the form .</p>
					</td>
				</tr>
			</table>
		</fieldset>
		
		<fieldset>
			<legend>Position / Web Address / Other Field:</legend>			
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label for="easy_t_position_web_other_field_label">"Position / Web Address / Other" Field Label</label></th>
					<td><input type="text" name="easy_t_position_web_other_field_label" id="easy_t_position_web_other_field_label" <?php if(!isValidKey()): ?>disabled="disabled"<?php endif; ?> value="<?php echo get_option('easy_t_position_web_other_field_label', 'Position / Web Address / Other'); ?>" />
					<p class="description">This is the label of the third field in the form, which defaults to "Position / Web Address / Other."  Contents of this field will be passed through to the Position / Web Address / Other field inside WordPress.</p>
					</td>
				</tr>
			</table>
						
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label for="easy_t_position_web_other_field_description">"Position / Web Address / Other" Field Description</label></th>
					<td><textarea name="easy_t_position_web_other_field_description" id="easy_t_position_web_other_field_description" <?php if(!isValidKey()): ?>disabled="disabled"<?php endif; ?>><?php echo get_option('easy_t_position_web_other_field_description', 'Please enter your Job Title or Website address.'); ?></textarea>
					<p class="description">This is the description below the third field in the form.</p>
					</td>
				</tr>
			</table>
			
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label for="easy_t_hide_position_web_other_field">Disable "Position / Web Address / Other" Field Display</label></th>
					<td><input type="checkbox" name="easy_t_hide_position_web_other_field" id="easy_t_hide_position_web_other_field" <?php if(!isValidKey()): ?>disabled="disabled"<?php endif; ?> value="1" <?php if(get_option('easy_t_hide_position_web_other_field')){ ?> checked="CHECKED" <?php } ?>/>
					<p class="description">If checked, the third field in the form will not be displayed.</p>
					</td>
				</tr>
			</table>
		</fieldset>
		
		<fieldset>
			<legend>Location / Product Reviewed / Other Field:</legend>			
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label for="easy_t_other_other_field_label">"Location / Product Reviewed / Other" Field Label</label></th>
					<td><input type="text" name="easy_t_other_other_field_label" id="easy_t_other_other_field_label" <?php if(!isValidKey()): ?>disabled="disabled"<?php endif; ?> value="<?php echo get_option('easy_t_other_other_field_label', 'Location / Product Reviewed / Other'); ?>" />
					<p class="description">This is the label of the fourth field in the form, which defaults to "Location / Product Reviewed / Other."  Contents of this field will be passed through to the Location / Product Reviewed / Other field inside WordPress.</p>
					</td>
				</tr>
			</table>
						
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label for="easy_t_other_other_field_description">"Location / Product Reviewed / Other" Field Description</label></th>
					<td><textarea name="easy_t_other_other_field_description" id="easy_t_other_other_field_description" <?php if(!isValidKey()): ?>disabled="disabled"<?php endif; ?>><?php echo get_option('easy_t_other_other_field_description', 'Please enter your Location or the Product being Reviewed.'); ?></textarea>
					<p class="description">This is the description below the fourth field in the form.</p>
					</td>
				</tr>
			</table>
			
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label for="easy_t_hide_other_other_field">Disable "Location / Product Reviewed / Other" Field Display</label></th>
					<td><input type="checkbox" name="easy_t_hide_other_other_field" id="easy_t_hide_other_other_field" <?php if(!isValidKey()): ?>disabled="disabled"<?php endif; ?> value="1" <?php if(get_option('easy_t_hide_other_other_field')){ ?> checked="CHECKED" <?php } ?>/>
					<p class="description">If checked, the fourth field in the form will not be displayed.</p>
					</td>
				</tr>
			</table>
		</fieldset>
		
		<fieldset>
			<legend>Category Field:</legend>			
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label for="easy_t_category_field_label">"Category" Field Label</label></th>
					<td><input type="text" name="easy_t_category_field_label" id="easy_t_category_field_label" <?php if(!isValidKey()): ?>disabled="disabled"<?php endif; ?> value="<?php echo get_option('easy_t_category_field_label', 'Location / Product Reviewed / Other'); ?>" />
					<p class="description">This is the label of the fifth field in the form, which defaults to "Category."  This field matches the Testimonial Categories inside WordPress.</p>
					</td>
				</tr>
			</table>
						
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label for="easy_t_category_field_description">"Category" Field Description</label></th>
					<td><textarea name="easy_t_category_field_description" id="easy_t_category_field_description" <?php if(!isValidKey()): ?>disabled="disabled"<?php endif; ?>><?php echo get_option('easy_t_category_field_description', 'Please enter your Location or the Product being Reviewed.'); ?></textarea>
					<p class="description">This is the description below the fifth field in the form.</p>
					</td>
				</tr>
			</table>
			
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label for="easy_t_hide_category_field">Disable "Category" Field Display</label></th>
					<td><input type="checkbox" name="easy_t_hide_category_field" id="easy_t_hide_category_field" <?php if(!isValidKey()): ?>disabled="disabled"<?php endif; ?> value="1" <?php if(get_option('easy_t_hide_category_field')){ ?> checked="CHECKED" <?php } ?>/>
					<p class="description">If checked, the fifth field in the form will not be displayed.</p>
					</td>
				</tr>
			</table>
		</fieldset>
		
		<fieldset>
			<legend>Body Content Field:</legend>			
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label for="easy_t_body_content_field_label">"Body Content" Field Label</label></th>
					<td><input type="text" name="easy_t_body_content_field_label" id="easy_t_body_content_field_label" <?php if(!isValidKey()): ?>disabled="disabled"<?php endif; ?> value="<?php echo get_option('easy_t_body_content_field_label', 'Your Testimonial'); ?>" />
					<p class="description">This is the label of the sixth field in the form, a textarea, which defaults to "Your Testimonial."  Contents of this field will be passed through to the Body field inside WordPress.</p>
					</td>
				</tr>
			</table>
						
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label for="easy_t_body_content_field_description">Body Content Field Description</label></th>
					<td><textarea name="easy_t_body_content_field_description" id="easy_t_body_content_field_description" <?php if(!isValidKey()): ?>disabled="disabled"<?php endif; ?>><?php echo get_option('easy_t_body_content_field_description', 'Please enter your Testimonial.'); ?></textarea>
					<p class="description">This is the description below the sixth field in the form, a textarea.</p>
					</td>
				</tr>
			</table>
		</fieldset>
		
		<fieldset>
			<legend>Submission Options</legend>
					
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label for="easy_t_submit_button_label">Submit Button Label</label></th>
					<td><input type="text" name="easy_t_submit_button_label" id="easy_t_submit_button_label" <?php if(!isValidKey()): ?>disabled="disabled"<?php endif; ?> value="<?php echo get_option('easy_t_submit_button_label', 'Submit Testimonial'); ?>" />
					<p class="description">This is the label of the submit button at the bottom of the form.</p>
					</td>
				</tr>
			</table>
						
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label for="easy_t_submit_success_message">Submission Success Message</label></th>
					<td><textarea name="easy_t_submit_success_message" id="easy_t_submit_success_message" <?php if(!isValidKey()): ?>disabled="disabled"<?php endif; ?>><?php echo get_option('easy_t_submit_success_message', 'Thank You For Your Submission!'); ?></textarea>
					<p class="description">This is the text that appears after a successful submission.</p>
					</td>
				</tr>
			</table>
						
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label for="easy_t_submit_success_redirect_url">Submission Success Redirect URL</label></th>
					<td><input type="text" name="easy_t_submit_success_redirect_url" id="easy_t_submit_success_redirect_url" <?php if(!isValidKey()): ?>disabled="disabled"<?php endif; ?> value="<?php echo get_option('easy_t_submit_success_redirect_url', ''); ?>"/>
					<p class="description">If you want the user to be taken to a specific URL on your site after submitting their Testimonial, enter it into this field.  If the field is empty, they will stay on the same page and see the Success Message, instead.</p>
					</td>
				</tr>
			</table>
			
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label for="easy_t_submit_notification_address">Submission Success Notification E-Mail Address</label></th>
					<td><input type="text" name="easy_t_submit_notification_address" id="easy_t_submit_notification_address" <?php if(!isValidKey()): ?>disabled="disabled"<?php endif; ?> value="<?php echo get_option('easy_t_submit_notification_address'); ?>" />
					<p class="description">If set, we will attempt to send an e-mail notification to this address upon a succesfull submission.  If not set, submission notifications will be sent to the site's Admin E-mail address.  You can include multiple, comma-separated e-mail addresses here.</p>
					</td>
				</tr>
			</table>
			
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label for="easy_t_submit_notification_include_testimonial">Include Testimonial In Notification E-mail</label></th>
					<td><input type="checkbox" name="easy_t_submit_notification_include_testimonial" id="easy_t_submit_notification_include_testimonial" <?php if(!isValidKey()): ?>disabled="disabled"<?php endif; ?> value="1" <?php if(get_option('easy_t_submit_notification_include_testimonial')){ ?> checked="CHECKED" <?php } ?>/>
					<p class="description">If checked, the notification e-mail will include the submitted Testimonial.</p>
					</td>
				</tr>
			</table>
		</fieldset>
		
		<fieldset>
			<legend>Testimonial Image Field:</legend>
		
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label for="easy_t_image_field_label">Testimonial Image Field Label</label></th>
					<td><input type="text" name="easy_t_image_field_label" id="easy_t_image_field_label" <?php if(!isValidKey()): ?>disabled="disabled"<?php endif; ?> value="<?php echo get_option('easy_t_image_field_label', 'Your Image'); ?>" />
					<p class="description">This is the label of the Testimonial Image Field in the form, which defaults to "Your Image".</p>
					</td>
				</tr>
			</table>
						
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label for="easy_t_image_field_description">Testimonial Image Field Description</label></th>
					<td><textarea name="easy_t_image_field_description" id="easy_t_image_field_description" <?php if(!isValidKey()): ?>disabled="disabled"<?php endif; ?>><?php echo get_option('easy_t_image_field_description', 'You can select and upload 1 image along with your Testimonial.  Depending on the website\'s settings, this image may be cropped or resized.  Allowed file types are .gif, .jpg, .png, and .jpeg.'); ?></textarea>
					<p class="description">This is the description below the Testimonial Image Field in the form.</p>
					</td>
				</tr>
			</table>
						
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label for="easy_t_use_image_field">Enable Testimonial Image on Submission Form</label></th>
					<td><input type="checkbox" name="easy_t_use_image_field" id="easy_t_use_image_field" <?php if(!isValidKey()): ?>disabled="disabled"<?php endif; ?> value="1" <?php if(get_option('easy_t_use_image_field')){ ?> checked="CHECKED" <?php } ?>/>
					<p class="description">If checked, users will be allowed to upload 1 image along with their Testimonial.</p>
					</td>
				</tr>
			</table>
		</fieldset>
		
		<fieldset>
			<legend>Testimonial Ratings Field:</legend>
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label for="easy_t_rating_field_label">Testimonial Ratings Field Label</label></th>
					<td><input type="text" name="easy_t_rating_field_label" id="easy_t_rating_field_label" <?php if(!isValidKey()): ?>disabled="disabled"<?php endif; ?> value="<?php echo get_option('easy_t_rating_field_label', 'Your Rating'); ?>" />
					<p class="description">This is the label of the Testimonial Rating Field in the form, which defaults to "Your Rating".</p>
					</td>
				</tr>
			</table>
						
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label for="easy_t_rating_field_description">Testimonial Ratings Field Description</label></th>
					<td><textarea name="easy_t_rating_field_description" id="easy_t_rating_field_description" <?php if(!isValidKey()): ?>disabled="disabled"<?php endif; ?>><?php echo get_option('easy_t_rating_field_description', '1 - 5 out of 5, where 5/5 is the best and 1/5 is the worst.'); ?></textarea>
					<p class="description">This is the description below the Testimonial Rating Field in the form.</p>
					</td>
				</tr>
			</table>
						
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label for="easy_t_use_rating_field">Enable Testimonial Ratings on Submission Form</label></th>
					<td><input type="checkbox" name="easy_t_use_rating_field" id="easy_t_use_rating_field" <?php if(!isValidKey()): ?>disabled="disabled"<?php endif; ?> value="1" <?php if(get_option('easy_t_use_rating_field')){ ?> checked="CHECKED" <?php } ?>/>
					<p class="description">If checked, users will be allowed to add a 1 - 5 out of 5 rating along with their Testimonial.</p>
					</td>
				</tr>
			</table>
		</fieldset>
		
		<fieldset>
			<legend>Captcha Field:</legend>
		
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label for="easy_t_captcha_field_label">"Captcha" Field Label</label></th>
					<td><input type="text" name="easy_t_captcha_field_label" id="easy_t_captcha_field_label" <?php if(!isValidKey()): ?>disabled="disabled"<?php endif; ?> value="<?php echo get_option('easy_t_captcha_field_label', 'Captcha'); ?>" />
					<p class="description">This is the label of the Capthca field in the form, which defaults to "Captcha".  Contents of this field will be passed through to the Captcha function inside WordPress.</p>
					</td>
				</tr>
			</table>
						
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label for="easy_t_captcha_field_description">"Captcha" Field Description</label></th>
					<td><textarea name="easy_t_captcha_field_description" id="easy_t_captcha_field_description" <?php if(!isValidKey()): ?>disabled="disabled"<?php endif; ?>><?php echo get_option('easy_t_captcha_field_description', 'Please enter the text that you see above here.'); ?></textarea>
					<p class="description">This is the description below the Captcha field in the form.</p>
					</td>
				</tr>
			</table>

			<?php
				$recaptcha_portal_link = sprintf('(<a href="%s">%s</a>)', 'https://www.google.com/recaptcha/admin', 'Get Yours Here');
			?>
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label for="easy_t_recaptcha_api_key">reCAPTCHA API Key</label></th>
					<td>
						<input type="text" name="easy_t_recaptcha_api_key" id="easy_t_recaptcha_api_key" <?php if(!isValidKey()): ?>disabled="disabled"<?php endif; ?> value="<?php echo get_option('easy_t_recaptcha_api_key', ''); ?>" />
						<p class="description">To use Google's reCAPTCHA service, please enter your <strong>reCAPTCHA API Key</strong> here. <?php echo $recaptcha_portal_link; ?></p>
					</td>
				</tr>
			</table>

			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label for="easy_t_recaptcha_secret_key">reCAPTCHA Secret Key</label></th>
					<td>
						<input type="text" name="easy_t_recaptcha_secret_key" id="easy_t_recaptcha_secret_key" <?php if(!isValidKey()): ?>disabled="disabled"<?php endif; ?> value="<?php echo get_option('easy_t_recaptcha_secret_key', ''); ?>" />
						<p class="description">To use Google's reCAPTCHA service, please enter your <strong>reCAPTCHA Secret Key</strong> here. <?php echo $recaptcha_portal_link; ?></p>
					</td>
				</tr>
			</table>
	
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label for="easy_t_recaptcha_lang">reCAPTCHA Language</label></th>
					<td>
						<select name="easy_t_recaptcha_lang" id="easy_t_recaptcha_lang" <?php if(!isValidKey()): ?>disabled="disabled"<?php endif; ?>>
							<option value="">(Not Specified)</option>
							<?php
							$current_lang = get_option('easy_t_recaptcha_lang', '');
							foreach ($this->get_recaptcha_languages() as $label => $val) {
								$sel_attr = (strcmp($current_lang, $val) == 0 ? 'selected="selected"' : '');
								printf( '<option value="%s" %s>%s</option>', htmlentities($val), $sel_attr, htmlentities($label) );
							} ?>
						</select>						
					</td>
				</tr>
			</table>
						
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label for="easy_t_use_captcha">Enable Captcha on Submission Form</label></th>
					<td>
						<label>
							<input type="checkbox" name="easy_t_use_captcha" id="easy_t_use_captcha" <?php if(!isValidKey()): ?>disabled="disabled"<?php endif; ?> value="1" <?php if(get_option('easy_t_use_captcha')){ ?> checked="CHECKED" <?php } ?>/>
							Require visitors to complete a CAPTCHA before submitting their testimonials
						</label>
						<p class="description">This is useful if you are having SPAM problems. Requires a valid reCAPTCHA API Key and Secret Key to be entered above, or a compatible Captcha plugin to be installed (such as <a href="https://wordpress.org/plugins/really-simple-captcha/" target="_blank">Really Simple Captcha</a>). </p>
					</td>
				</tr>
			</table>
		</fieldset>
		<p class="submit">
			<input type="submit" class="button-primary" value="<?php _e('Save Changes', 'easy-testimonials') ?>" />
		</p>
		</div><!-- end div.ezt_submission_form_settings -->
	</form>
	</div><?php 
	}
	
	function get_recaptcha_languages()	
	{
		// from: https://developers.google.com/recaptcha/docs/language
		return array(
			'Arabic' => 'ar',
			'Bengali' => 'bn',
			'Bulgarian' => 'bg',
			'Catalan' => 'ca',
			'Chinese (Simplified)' => 'zh-CN',
			'Chinese (Traditional)' => 'zh-TW',
			'Croatian' => 'hr',
			'Czech' => 'cs',
			'Danish' => 'da',
			'Dutch' => 'nl',
			'English (UK)' => 'en-GB',
			'English (US)' => 'en',
			'Estonian' => 'et',
			'Filipino' => 'fil',
			'Finnish' => 'fi',
			'French' => 'fr',
			'French (Canadian)' => 'fr-CA',
			'German' => 'de',
			'Gujarati' => 'gu',
			'German (Austria)' => 'de-AT',
			'German (Switzerland)' => 'de-CH',
			'Greek' => 'el',
			'Hebrew' => 'iw',
			'Hindi' => 'hi',
			'Hungarain' => 'hu',
			'Indonesian' => 'id',
			'Italian' => 'it',
			'Japanese' => 'ja',
			'Kannada' => 'kn',
			'Korean' => 'ko',
			'Latvian' => 'lv',
			'Lithuanian' => 'lt',
			'Malay' => 'ms',
			'Malayalam' => 'ml',
			'Marathi' => 'mr',
			'Norwegian' => 'no',
			'Persian' => 'fa',
			'Polish' => 'pl',
			'Portuguese' => 'pt',
			'Portuguese (Brazil)' => 'pt-BR',
			'Portuguese (Portugal)' => 'pt-PT',
			'Romanian' => 'ro',
			'Russian' => 'ru',
			'Serbian' => 'sr',
			'Slovak' => 'sk',
			'Slovenian' => 'sl',
			'Spanish' => 'es',
			'Spanish (Latin America)' => 'es-419',
			'Swedish' => 'sv',
			'Tamil' => 'ta',
			'Telugu' => 'te',
			'Thai' => 'th',
			'Turkish' => 'tr',
			'Ukrainian' => 'uk',
			'Urdu' => 'ur',
			'Vietnamese' => 'vi',
		);

	}
	
	function help_settings_page(){
		$this->settings_page_top();
		include('pages/help.php');
	}	
	
	function import_export_settings_page(){				
		$this->settings_page_top();
		
		?><form method="post" action="options.php">
			<?php settings_fields( 'easy-testimonials-import-export-settings-group' ); ?>					
			
			<fieldset>
				<legend>Hello Testimonials Integration</legend>
				
				<p><strong>Want to learn more about Hello Testimonials? <a href="http://hellotestimonials.com/p/welcome-easy-testimonials-users/">Click Here!</a></strong></p>
				
				<table class="form-table">
					<tr valign="top">
						<th scope="row"><label for="easy_t_hello_t_json_url">Hello Testimonials JSON Feed URL</label></th>
						<td><textarea name="easy_t_hello_t_json_url" id="easy_t_hello_t_json_url" rows=1 style="width: 100%"><?php echo get_option('easy_t_hello_t_json_url'); ?></textarea>
						<p class="description">This is the JSON URL you copied from the Custom Integrations page inside Hello Testimonials.</p>
						</td>
					</tr>
				</table>
				
				<table class="form-table">
					<tr valign="top">
						<th scope="row"><label for="easy_t_hello_t_enable_cron">Enable Hello Testimonials Integration</label></th>
						<td><input type="checkbox" name="easy_t_hello_t_enable_cron" id="easy_t_hello_t_enable_cron" value="1" <?php if(get_option('easy_t_hello_t_enable_cron', 0)){ ?> checked="CHECKED" <?php } ?>/>
						<p class="description">If checked, new Testimonials will be loaded from your Hello Testimonials account and automatically added to your Testimonials list.</p>
						</td>
					</tr>
				</table>
				
			</fieldset>
			
			<p class="submit">
				<input type="submit" class="button-primary" value="<?php _e('Save Changes', 'easy-testimonials') ?>" />
			</p>	
			<p class="submit" style="margin-top:0;">
				<a href="?page=easy-testimonials-import-export&run-cron-now=true" class="button-primary" title="<?php _e('Import From Hello Testimonials Now', 'easy-testimonials') ?>"><?php _e('Import From Hello Testimonials Now', 'easy-testimonials') ?></a>
			</p>	
		</form>	
		
		
		<?php if(isValidKey()): ?>	
		<form method="POST" action="" enctype="multipart/form-data">					
			<fieldset>
				<legend>CSV Import / Export</legend>
				<h3>Testimonials Importer</h3>	
				<?php 
					//CSV Importer
					$importer = new TestimonialsPlugin_Importer($this);
					$importer->csv_importer(); // outputs form and handles input. TODO: break into 2 functions (one to show form, one to process input)
				?>
				<h3>Testimonials Exporter</h3>	
				<?php 
					//CSV Exporter
					TestimonialsPlugin_Exporter::output_form();
				?>
			</fieldset>
		</form>
		<?php else: ?>
		<form method="POST" action="" enctype="multipart/form-data">					
			<fieldset>
				<legend>CSV Import / Export</legend>
				<h3>Testimonials Importer</h3>	
				<p class="easy_testimonials_not_registered"><strong>This feature require Easy Testimonials Pro.</strong>&nbsp;&nbsp;&nbsp;<a class="button" target="blank" href="https://goldplugins.com/our-plugins/easy-testimonials-details/upgrade-to-easy-testimonials-pro/?utm_campaign=upgrade&utm_source=plugin&utm_banner=import_upgrade">Upgrade Now</a></p>
				<h3>Testimonials Exporter</h3>
				<p class="easy_testimonials_not_registered"><strong>This feature require Easy Testimonials Pro.</strong>&nbsp;&nbsp;&nbsp;<a class="button" target="blank" href="https://goldplugins.com/our-plugins/easy-testimonials-details/upgrade-to-easy-testimonials-pro/?utm_campaign=upgrade&utm_source=plugin&utm_banner=import_upgrade">Upgrade Now</a></p>	
			</fieldset>
		</form>
		<?php endif; ?>
		
		</div><?php 
		
		//schedule cron if enabled
		if(get_option('easy_t_hello_t_enable_cron', 0)){
			//and if the cron job hasn't already been scheduled
			if(!wp_get_schedule('hello_t_subscription')){
				//schedule the cron job
				hello_t_cron_activate();
			}
			
			//if the run cron now button has been clicked
			if (isset($_GET['run-cron-now']) && $_GET['run-cron-now'] == 'true'){
				//go ahead and add the testimonials, too
				add_hello_t_testimonials();
				
				echo '<div id="message" class="updated fade"><p>Success!  Your Testimonials have been imported!</p></div>';
			}
		} else {
			//else if the cron job option has been unchecked
			//clear the scheduled job
			hello_t_cron_deactivate();
			
			//if the run cron now button has been clicked
			if (isset($_GET['run-cron-now']) && $_GET['run-cron-now'] == 'true'){				
				echo '<div id="message" class="updated fade"><p>Hello Testimonials Integration is disabled!  Please enable to Import Testimonials.</p></div>';
			}
		}
	}
	
	function typography_input($name, $label, $description)
	{
		global $EasyT_BikeShed;
		$options = array();
		$options['name'] = $name;
		$options['label'] = $label;
		$options['description'] = $description;
		$options['google_fonts'] = true;
		$options['default_color'] = '#008800';
		$options['values'] = $this->get_typography_values($name);		
		$options['disabled'] = !isValidKey(); // typography inputs are Pro only
		$EasyT_BikeShed->typography( $options );
	}
	
	function get_typography_values($pattern, $default_value = '')
	{
		$keys = array();
		$values = array();
		$keys[] = 'font_size';
		$keys[] = 'font_family';
		$keys[] = 'font_style';
		$keys[] = 'font_color';
		foreach($keys as $key) {			
			$option_key = str_replace('*', $key, $pattern);
			$values[$key] = get_option($option_key, $default_value);
		}
		return $values;
	}
	
	function easy_t_bust_options_cache()
	{
		delete_transient('_easy_t_webfont_str');
		delete_transient('_easy_t_testimonial_style');
	}
} // end class
?>