<?php
/*
Plugin Name: Easy Testimonials
Plugin URI: https://goldplugins.com/our-plugins/easy-testimonials-details/
Description: Easy Testimonials - Provides custom post type, shortcode, sidebar widget, and other functionality for testimonials.
Author: Gold Plugins
Version: 1.31.10
Author URI: https://goldplugins.com
Text Domain: easy-testimonials

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
along with Easy Testimonials .  If not, see <http://www.gnu.org/licenses/>.
*/

global $easy_t_footer_css_output;

require_once('include/lib/lib.php');
require_once('include/lib/BikeShed/bikeshed.php');
require_once("include/lib/testimonials_importer.php");
require_once("include/lib/testimonials_exporter.php");

//setup JS
function easy_testimonials_setup_js() {
	$disable_cycle2 = get_option('easy_t_disable_cycle2');
	$use_cycle_fix = get_option('easy_t_use_cycle_fix');

	// register the recaptcha script, but only enqueue it later, when/if we see the submit_testimonial shortcode
	$recaptcha_lang = get_option('easy_t_recaptcha_lang', '');
	$recaptcha_js_url = 'https://www.google.com/recaptcha/api.js' . ( !empty($recaptcha_lang) ? '?hl='.urlencode($recaptcha_lang) : '' );
	wp_register_script(
			'g-recaptcha',
			$recaptcha_js_url
	);

	// register the grid-height script, but only enqueue it later, when/if we see the testimonials_grid shortcode with the auto_height option on
	$recaptcha_lang = get_option('easy_t_recaptcha_lang', '');
	$recaptcha_js_url = 'https://www.google.com/recaptcha/api.js' . ( !empty($recaptcha_lang) ? '?hl='.urlencode($recaptcha_lang) : '' );
	wp_register_script(
			'easy-testimonials-grid',
			plugins_url('include/js/easy-testimonials-grid.js', __FILE__),
			array( 'jquery' )
	);
	
	if(!$disable_cycle2){
		wp_enqueue_script(
			'cycle2',
			plugins_url('include/js/jquery.cycle2.min.js', __FILE__),
			array( 'jquery' ),
			false,
			true
		);  
		
		if(isValidKey()){  
			wp_enqueue_script(
				'easy-testimonials',
				plugins_url('include/js/easy-testimonials.js', __FILE__),
				array( 'jquery' ),
				false,
				true
			);
			wp_enqueue_script(
				'rateit',
				plugins_url('include/js/jquery.rateit.min.js', __FILE__),
				array( 'jquery' ),
				false,
				true
			);
		}
		
		if($use_cycle_fix){
			wp_enqueue_script(
				'easy-testimonials-cycle-fix',
				plugins_url('include/js/easy-testimonials-cycle-fix.js', __FILE__),
				array( 'jquery' ),
				false,
				true
			);
		}
	}
}

//add Testimonial CSS to header
function easy_testimonials_setup_css() {
	wp_register_style( 'easy_testimonial_style', plugins_url('include/css/style.css', __FILE__) );
	
	$cache_key = '_easy_t_testimonial_style';
	$style = get_transient($cache_key);
	if ($style == false) {
		$style = get_option('testimonials_style', 'x');
		set_transient($cache_key, $style);
	}

	// enqueue the base style unless "no_style" has been specified
	if($style != 'no_style') {
		wp_enqueue_style( 'easy_testimonial_style' );
	}

	// enqueue Pro CSS files
	if(isValidKey()) {
		//five star ratings
		wp_register_style( 'easy_testimonial_rateit_style', plugins_url('include/css/rateit.css', __FILE__) );
		wp_enqueue_style( 'easy_testimonial_rateit_style' );
		
		//pro themes
		wp_register_style( 'easy_testimonials_pro_styles', plugins_url('include/css/easy_testimonials_pro.css', __FILE__) );
		wp_enqueue_style( 'easy_testimonials_pro_styles' );
	}	
}

function easy_t_send_notification_email($submitted_testimonial = array()){
	//get e-mail address from post meta field
	//TBD: logic to use comma-separated e-mail addresses
	$email_addresses = explode(",", get_option('easy_t_submit_notification_address', get_bloginfo('admin_email')));
 
	$subject = "New Easy Testimonial Submission on " . get_bloginfo('name');
	
	//see if option is set to include testimonial in e-mail
	if(get_option('easy_t_submit_notification_include_testimonial')){ //option is set, build message containing testimonial
		$body = "You have received a new submission with Easy Testimonials on your site, " . get_bloginfo('name') . ".  Login to approve or trash it! \r\n\r\n";		
		
		$body .= "Title: {$submitted_testimonial['post']['post_title']} \r\n";
		$body .= "Body: {$submitted_testimonial['post']['post_content']} \r\n";
		$body .= "Name: {$submitted_testimonial['the_name']} \r\n";
		$body .= "Position/Web Address/Other: {$submitted_testimonial['the_other']} \r\n";
		$body .= "Location/Product Reviewed/Other: {$submitted_testimonial['the_other_other']} \r\n";
		$body .= "Rating: {$submitted_testimonial['the_rating']} \r\n";
	} else { //option isn't set, use default message
		$body = "You have received a new submission with Easy Testimonials on your site, " . get_bloginfo('name') . ".  Login and see what they had to say!";
	}
 
	//use this to set the From address of the e-mail
	$headers = 'From: ' . get_bloginfo('name') . ' <'.get_bloginfo('admin_email').'>' . "\r\n";
	
	//loop through available e-mail addresses and fire off the e-mails!
	foreach($email_addresses as $email_address){
		if(wp_mail($email_address, $subject, $body, $headers)){
			//mail sent!
		} else {
			//failure!
		}
	}
}
	
function easy_t_check_captcha() {
	
	
	if ( !class_exists('ReallySimpleCaptcha') && !easy_testimonials_use_recaptcha() ) {
		// captcha's cannot possibly be checked, so return true
		return true;
	} else {
		$captcha_correct = false; // false until proven correct		
	}
	
	// look for + verify a reCAPTCHA first
	if ( !empty($_POST["g-recaptcha-response"]) ) 
	{
		if ( !class_exists('EZT_ReCaptcha') ) {
			require_once ('include/lib/ezt_recaptchalib.php');
		}
		$secret = get_option('easy_t_recaptcha_secret_key', '');
		$response = null;
		if ( !empty($secret)  )
		{
			$reCaptcha = new EZT_ReCaptcha($secret);
			$response = $reCaptcha->verifyResponse(
				$_SERVER["REMOTE_ADDR"],
				$_POST["g-recaptcha-response"]
			);
			$captcha_correct = ($response != null && $response->success);
		}
	}
	else if ( !empty ($_POST['captcha_prefix']) && class_exists('ReallySimpleCaptcha') )
	{
		$captcha = new ReallySimpleCaptcha();
		// This variable holds the CAPTCHA image prefix, which corresponds to the correct answer
		$captcha_prefix = $_POST['captcha_prefix'];
		// This variable holds the CAPTCHA response, entered by the user
		$captcha_code = $_POST['captcha_code'];
		// This variable will hold the result of the CAPTCHA validation. Set to 'false' until CAPTCHA validation passes
		$captcha_correct = false;
		// Validate the CAPTCHA response
		$captcha_check = $captcha->check( $captcha_prefix, $captcha_code );
		// Set to 'true' if validation passes, and 'false' if validation fails
		$captcha_correct = $captcha_check;
		// clean up the tmp directory
		$captcha->remove($captcha_prefix);
		$captcha->cleanup();			
	}
	
	return $captcha_correct;
}	
	
function easy_t_outputCaptcha()
{
	if ( easy_testimonials_use_recaptcha() ) {
		?>
			<div class="g-recaptcha" data-sitekey="<?php echo htmlentities(get_option('easy_t_recaptcha_api_key', '')); ?>"></div>
			<br />		
		<?php
	}
	else if ( class_exists('ReallySimpleCaptcha') )
	{
		// Instantiate the ReallySimpleCaptcha class, which will handle all of the heavy lifting
		$captcha = new ReallySimpleCaptcha();
		 
		// ReallySimpleCaptcha class option defaults.
		// Changing these values will hav no impact. For now, these are here merely for reference.
		// If you want to configure these options, see "Set Really Simple CAPTCHA Options", below
		$captcha_defaults = array(
			'chars' => 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789',
			'char_length' => '4',
			'img_size' => array( '72', '24' ),
			'fg' => array( '0', '0', '0' ),
			'bg' => array( '255', '255', '255' ),
			'font_size' => '16',
			'font_char_width' => '15',
			'img_type' => 'png',
			'base' => array( '6', '18'),
		);
		 
		/**************************************
		* All configurable options are below  *
		***************************************/
		 
		//Set Really Simple CAPTCHA Options
		$captcha->chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
		$captcha->char_length = '4';
		$captcha->img_size = array( '100', '50' );
		$captcha->fg = array( '0', '0', '0' );
		$captcha->bg = array( '255', '255', '255' );
		$captcha->font_size = '16';
		$captcha->font_char_width = '15';
		$captcha->img_type = 'png';
		$captcha->base = array( '6', '18' );
		 
		/********************************************************************
		* Nothing else to edit.  No configurable options below this point.  *
		*********************************************************************/
		 
		// Generate random word and image prefix
		$captcha_word = $captcha->generate_random_word();
		$captcha_prefix = mt_rand();
		// Generate CAPTCHA image
		$captcha_image_name = $captcha->generate_image($captcha_prefix, $captcha_word);
		// Define values for CAPTCHA fields
		$captcha_image_url =  get_bloginfo('wpurl') . '/wp-content/plugins/really-simple-captcha/tmp/';
		$captcha_image_src = $captcha_image_url . $captcha_image_name;
		$captcha_image_width = $captcha->img_size[0];
		$captcha_image_height = $captcha->img_size[1];
		$captcha_field_size = $captcha->char_length;
		// Output the CAPTCHA fields
		?>
		<div class="easy_t_field_wrap">
			<img src="<?php echo $captcha_image_src; ?>"
			 alt="captcha"
			 width="<?php echo $captcha_image_width; ?>"
			 height="<?php echo $captcha_image_height; ?>" /><br/>
			<label for="captcha_code"><?php echo get_option('easy_t_captcha_field_label','Captcha'); ?></label><br/>
			<input id="captcha_code" name="captcha_code"
			 size="<?php echo $captcha_field_size; ?>" type="text" />
			<p class="easy_t_description"><?php echo get_option('easy_t_captcha_field_description','Enter the value in the image above into this field.'); ?></p>
			<input id="captcha_prefix" name="captcha_prefix" type="hidden"
			 value="<?php echo $captcha_prefix; ?>" />
		</div>
		<?php
	}
}

//handle file upload for image in front end submission form
function easy_t_upload_user_file( $file = array(), $post_id ) {
    
    require_once( ABSPATH . 'wp-admin/includes/admin.php' );
    
    $file_return = wp_handle_upload( $file, array('test_form' => false ) );
    
	// Set an array containing a list of acceptable formats
	$allowed_file_types = array('image/jpg','image/jpeg','image/gif','image/png');
	
    if( isset( $file_return['error'] ) || isset( $file_return['upload_error_handler'] ) ) {
        return false;
    } else {
	
		//only uploaded file types that are allowed
		if(in_array($file_return['type'], $allowed_file_types)) {
        
			$filename = $file_return['file'];
			
			$attachment = array(
				'post_mime_type' => $file_return['type'],
				'post_title' => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
				'post_content' => '',
				'post_status' => 'inherit',
				'guid' => $file_return['url']
			);
			
			$attachment_id = wp_insert_attachment( $attachment, $file_return['url'] );
			
			require_once (ABSPATH . 'wp-admin/includes/image.php' );
			$attachment_data = wp_generate_attachment_metadata( $attachment_id, $filename );
			wp_update_attachment_metadata( $attachment_id, $attachment_data );
			
			if( 0 < intval( $attachment_id ) ) {
				//make this the testimonial's featured image
				set_post_thumbnail( $post_id, $attachment_id );
				
				return $attachment_id;
			}
		} else {
			return false;
		}
    }
    
    return false;
}

function easy_testimonials_use_recaptcha()
{
	return ( 
		get_option('easy_t_use_captcha', 0)
		&& strlen( get_option('easy_t_recaptcha_api_key', '') ) > 0
		&& strlen( get_option('easy_t_recaptcha_secret_key', '') ) > 0
	);
}
	
//submit testimonial shortcode
function submitTestimonialForm($atts){

		// enqueue reCAPTCHA JS if needed
		if( easy_testimonials_use_recaptcha() ) {
			wp_enqueue_script('g-recaptcha');			
		}
		ob_start();
		
        // process form submissions
        $inserted = false;
       
        if( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && $_POST['action'] == "post_testimonial" ) {
			if(isValidKey()){  
				$do_not_insert = false;
				
				if (isset ($_POST['the-title']) && strlen($_POST['the-title']) > 0) {
						$title =  $_POST['the-title'];
				} else {
						$title_error = '<p class="easy_t_error">Please give ' . strtolower(get_option('easy_t_body_content_field_label','your testimonial')) . ' a ' . strtolower(get_option('easy_t_title_field_label','title')) . '.</p>';
						$do_not_insert = true;
				}
			   
				if (isset ($_POST['the-body']) && strlen($_POST['the-body']) > 0) {
						$body = $_POST['the-body'];
				} else {
						$body_error = '<p class="easy_t_error">Please enter ' . strtolower(get_option('easy_t_body_content_field_label','your testimonial')) . '.</p>';
						$do_not_insert = true;
				}			
				
				if( get_option('easy_t_use_captcha',0) ){ 
					$correct = easy_t_check_captcha(); 
					if(!$correct){
						$captcha_error = '<p class="easy_t_error">Captcha did not match.</p>';
						$do_not_insert = true;
					}
				}
				
				if(isset($captcha_error) || isset($body_error) || isset($title_error)){
					echo '<p class="easy_t_error">There was an error with your submission.  Please check the fields and try again.</p>';
				}
			   
				if(!$do_not_insert){
					//snag custom fields
					$the_other = isset($_POST['the-other']) ? $_POST['the-other'] : '';
					$the_other_other = isset($_POST['the-other-other']) ? $_POST['the-other-other'] : '';
					$the_name = isset($_POST['the-name']) ? $_POST['the-name'] : '';
					$the_rating = isset($_POST['the-rating']) ? $_POST['the-rating'] : '';
					$the_email = isset($_POST['the-email']) ? $_POST['the-email'] : '';
					$the_category = isset($_POST['the-category']) ? $_POST['the-category'] : "";
					
					$tags = array();
				   
					$post = array(
						'post_title'    => $title,
						'post_content'  => $body,
						'post_category' => array(),  // custom taxonomies too, needs to be an array
						'tags_input'    => $tags,
						'post_status'   => 'pending',
						'post_type'     => 'testimonial'
					);
				
					$new_id = wp_insert_post($post);
					
					//set the testimonial category
					//TBD: handle multiple categories
					wp_set_object_terms($new_id, $the_category, 'easy-testimonial-category');
				   
					//set the custom fields
					update_post_meta( $new_id, '_ikcf_client', $the_name );
					update_post_meta( $new_id, '_ikcf_position', $the_other );
					update_post_meta( $new_id, '_ikcf_other', $the_other_other );
					update_post_meta( $new_id, '_ikcf_rating', $the_rating );
					update_post_meta( $new_id, '_ikcf_email', $the_email );
				   
				   //collect info for notification e-mail
				   $submitted_testimonial = array(
						'post' => $post,
						'the_name' => $the_name,
						'the_other' => $the_other,
						'the_other_other' => $the_other_other,
						'the_rating' => $the_rating,
						'the_email' => $the_email
				   );
				   
					$inserted = true;
					
					//if the user has submitted a photo with their testimonial, handle the upload
					if( ! empty( $_FILES ) ) {
						foreach( $_FILES as $file ) {
							if( is_array( $file ) ) {
								$attachment_id = easy_t_upload_user_file( $file, $new_id );
							}
						}
					}
				}
			} else {
				echo "You must have a valid key to perform this action.";
            }
        }       
       
        $content = '';
       
        if(isValidKey()){ 		
			if($inserted){
				$redirect_url = get_option('easy_t_submit_success_redirect_url','');
				easy_t_send_notification_email($submitted_testimonial);
				if(strlen($redirect_url) > 2){
					echo '<script type="text/javascript">window.location.replace("'.$redirect_url.'");</script>';
				} else {					
					echo '<p class="easy_t_submission_success_message">' . get_option('easy_t_submit_success_message','Thank You For Your Submission!') . '</p>';
				}
			} else { ?>
			<!-- New Post Form -->
			<div id="postbox">
					<form id="new_post" class="easy-testimonials-submission-form" name="new_post" method="post" enctype="multipart/form-data" >
							<div class="easy_t_field_wrap <?php if(isset($title_error)){ echo "easy_t_field_wrap_error"; }//if a title wasn't entered add the wrap error class ?>">
								<?php if(isset($title_error)){ echo $title_error; }//if a title wasn't entered display a message ?>
								<label for="the-title"><?php echo get_option('easy_t_title_field_label','Title'); ?></label><br />
								<input type="text" id="the-title" value="<?php echo ( !empty($_POST['the-title']) ? htmlentities($_POST['the-title']) : ''); ?>" tabindex="1" size="20" name="the-title" />
								<p class="easy_t_description"><?php echo get_option('easy_t_title_field_description','Please give your Testimonial a Title.  *Required'); ?></p>
							</div>
							<?php if(!get_option('easy_t_hide_name_field',false)): ?>
							<div class="easy_t_field_wrap">
								<label for="the-name"><?php echo get_option('easy_t_name_field_label','Name'); ?></label><br />
								<input type="text" id="the-name" value="<?php echo ( !empty($_POST['the-name']) ? htmlentities($_POST['the-name']) : ''); ?>" tabindex="2" size="20" name="the-name" />
								<p class="easy_t_description"><?php echo get_option('easy_t_name_field_description','Please enter your Full Name.'); ?></p>
							</div>
							<?php endif; ?>
							<?php if(!get_option('easy_t_hide_email_field',false)): ?>
							<div class="easy_t_field_wrap">
								<label for="the-email"><?php echo get_option('easy_t_email_field_label','Your E-Mail Address'); ?></label><br />
								<input type="text" id="the-email" value="<?php echo ( !empty($_POST['the-email']) ? htmlentities($_POST['the-email']) : ''); ?>" tabindex="2" size="20" name="the-email" />
								<p class="easy_t_description"><?php echo get_option('easy_t_email_field_description','Please enter your e-mail address.  This information will not be publicly displayed.'); ?></p>
							</div>
							<?php endif; ?>
							<?php if(!get_option('easy_t_hide_position_web_other_field',false)): ?>
							<div class="easy_t_field_wrap">
								<label for="the-other"><?php echo get_option('easy_t_position_web_other_field_label','Position / Web Address / Other'); ?></label><br />
								<input type="text" id="the-other" value="<?php echo ( !empty($_POST['the-other']) ? htmlentities($_POST['the-other']) : ''); ?>" tabindex="3" size="20" name="the-other" />
								<p class="easy_t_description"><?php echo get_option('easy_t_position_web_other_field_description','Please enter your Job Title or Website address.'); ?></p>
							</div>
							<?php endif; ?>
							<?php if(!get_option('easy_t_hide_other_other_field',false)): ?>
							<div class="easy_t_field_wrap">
								<label for="the-other-other"><?php echo get_option('easy_t_other_other_field_label','Location / Product Reviewed / Other'); ?></label><br />
								<input type="text" id="the-other-other" value="<?php echo ( !empty($_POST['the-other-other']) ? htmlentities($_POST['the-other-other']) : ''); ?>" tabindex="3" size="20" name="the-other-other" />
								<p class="easy_t_description"><?php echo get_option('easy_t_other_other_field_description','Please enter your the name of the item you are Reviewing.');?>
							</div>
							<?php endif; ?>
							<?php if(!get_option('easy_t_hide_category_field',false)): ?>
							<?php $testimonial_categories = get_terms( 'easy-testimonial-category', 'orderby=title&hide_empty=0' ); ?>
							<div class="easy_t_field_wrap">
								<label for="the-category"><?php echo get_option('easy_t_category_field_label','Category'); ?></label><br />
								<select id="the-category" name="the-category">
									<?php
									foreach($testimonial_categories as $cat) {
										$sel_attr = ( !empty($_POST['the-category']) && $_POST['the-category'] == $cat->slug) ? 'selected="selected"' : '';
										printf('<option value="%s" %s>%s</option>', $cat->slug, $sel_attr, htmlentities($cat->name));
									}
									?>
								</select>
								<p class="easy_t_description"><?php echo get_option('easy_t_category_field_description','Please select the Category that best matches your Testimonial.'); ?></p>
							</div>
							<?php endif; ?>
							<?php if(get_option('easy_t_use_rating_field',false)): ?>
							<div class="easy_t_field_wrap">
								<label for="the-rating"><?php echo get_option('easy_t_rating_field_label','Your Rating'); ?></label><br />
								<select id="the-rating" tabindex="4" size="20" name="the-rating" >
									<?php 
									foreach(range(1, 5) as $rating) {
										$sel_attr = ( !empty($_POST['the-rating']) && $_POST['the-rating'] == $rating) ? 'selected="selected"' : '';
										printf('<option value="%d" %s>%d</option>', $rating, $sel_attr, $rating);
									}
									?>
								</select>
								<div class="rateit" data-rateit-backingfld="#the-rating" data-rateit-min="0"></div>
								<p class="easy_t_description"><?php echo get_option('easy_t_rating_field_description','1 - 5 out of 5, where 5/5 is the best and 1/5 is the worst.'); ?></p>
							</div>
							<?php endif; ?>
							<div class="easy_t_field_wrap <?php if(isset($body_error)){ echo "easy_t_field_wrap_error"; }//if a testimonial wasn't entered add the wrap error class ?>">
								<?php if(isset($body_error)){ echo $body_error; }//if a testimonial wasn't entered display a message ?>
								<label for="the-body"><?php echo get_option('easy_t_body_content_field_label','Your Testimonial'); ?></label><br />
								<textarea id="the-body" name="the-body" cols="50" tabindex="5" rows="6"><?php echo ( !empty($_POST['the-body']) ? htmlentities($_POST['the-body']) : ''); ?></textarea>
								<p class="easy_t_description"><?php echo get_option('easy_t_body_content_field_description','Please enter your Testimonial.  *Required'); ?></p>
							</div>							
							<?php if(get_option('easy_t_use_image_field',false)): ?>
							<div class="easy_t_field_wrap">
								<label for="the-image"><?php echo get_option('easy_t_image_field_label','Testimonial Image'); ?></label><br />
								<input type="file" id="the-image" value="" tabindex="6" size="20" name="the-image" />
								<p class="easy_t_description"><?php echo get_option('easy_t_image_field_description','You can select and upload 1 image along with your Testimonial.  Depending on the website\'s settings, this image may be cropped or resized.  Allowed file types are .gif, .jpg, .png, and .jpeg.'); ?></p>
							</div>
							<?php endif; ?>
							
							<?php 
								if( get_option('easy_t_use_captcha',0) ) {
									?><div class="easy_t_field_wrap <?php if(isset($captcha_error)){ echo "easy_t_field_wrap_error"; }//if a captcha wasn't correctly entered add the wrap error class ?>"><?php
									//if a captcha was entered incorrectly (or not at all) display message
									if(isset($captcha_error)){ echo $captcha_error; }
									easy_t_outputCaptcha();
									?></div><?php
								}
							?>
							
							<div class="easy_t_field_wrap"><input type="submit" value="<?php echo get_option('easy_t_submit_button_label','Submit Testimonial'); ?>" tabindex="7" id="submit" name="submit" /></div>
							<input type="hidden" name="action" value="post_testimonial" />
							<?php wp_nonce_field( 'new-post' ); ?>
					</form>
			</div>
			<!--// New Post Form -->
			<?php }
		   
			$content = ob_get_contents();
			ob_end_clean(); 
        }
       
        return apply_filters('easy_t_submission_form', $content);
}

//add Custom CSS
function easy_testimonials_setup_custom_css() {
	//use this to track if css has been output
	global $easy_t_footer_css_output;
	
	if($easy_t_footer_css_output){
		return;
	} else {
		echo '<style type="text/css" media="screen">' . get_option('easy_t_custom_css') . "</style>";
		$easy_t_footer_css_output = true;
	}
}

//display Testimonial Count
//$category is the slug of the category you want a count from
//if nothing is passed, displays count of all testimonials
//$status is the status of the testimonials to be included in the count
//defaults to published testimonials only
function easy_testimonials_display_count($category = '', $status = 'publish'){
	$tax_query = array();	
	
	//if a category slug was passed
	//only count testimonials within that category
	if(strlen($category)>0){
		$tax_query = array(
			array(
				'taxonomy' => 'easy-testimonial-category',
				'field' => 'slug',
				'terms' => $category
			)
		);
	}
	
	$args = array (
		'post_type' => 'testimonial',
		'tax_query' => $tax_query,
		'post_status' => $status
	);
	
	$count_query = new WP_Query( $args );
	
	$count = $count_query->found_posts;
	
	return $count;
}

//shortcode mapping function for easy_testimonials_display_count
//accepts two attributes, category and status
function outputTestimonialsCount($atts){
	//load shortcode attributes into an array
	extract( shortcode_atts( array(
		'category' => '',
		'status' => 'publish'
	), $atts ) );
	
	return easy_testimonials_display_count($category, $status);
}

if(!function_exists('word_trim')):
	function word_trim($string, $count, $ellipsis = FALSE)
	{
		$words = explode(' ', $string);
		if (count($words) > $count)
		{
			array_splice($words, $count);
			$string = implode(' ', $words);
			// trim of punctionation
			$string = rtrim($string, ',;.');	

			// add ellipsis if needed
			if (is_string($ellipsis)) {
				$string .= $ellipsis;
			} elseif ($ellipsis) {
				$string .= '&hellip;';
			}			
		}
		return $string;
	}
endif;

//load proper language pack based on current language
function easy_t_load_textdomain() {
	$plugin_dir = basename(dirname(__FILE__));
	load_plugin_textdomain( 'easy-testimonials', false, $plugin_dir . '/languages' );
}

//setup custom post type for testimonials
function easy_testimonials_setup_testimonials(){
	//include custom post type code
	include('include/lib/ik-custom-post-type.php');
	//include options code
	include('include/easy_testimonial_options.php');	
	$easy_testimonial_options = new easyTestimonialOptions();
			
	//setup post type for testimonials
	$postType = array('name' => 'Testimonial', 'plural' =>'Testimonials', 'slug' => 'testimonial', 'exclude_from_search' => !get_option('easy_t_show_in_search', true));
	$fields = array(); 
	$fields[] = array('name' => 'client', 'title' => 'Client Name', 'description' => "Name of the Client giving the testimonial.  Appears below the Testimonial.", 'type' => 'text');
	$fields[] = array('name' => 'email', 'title' => 'E-Mail Address', 'description' => "The client's e-mail address.  This field is used to check for a Gravatar, if that option is enabled in your settings.", 'type' => 'text'); 
	$fields[] = array('name' => 'position', 'title' => 'Position / Location / Other', 'description' => "The information that appears below the client's name.", 'type' => 'text');  
	$fields[] = array('name' => 'other', 'title' => 'Location / Product Reviewed / Other', 'description' => "The information that appears below the second custom field, Postion / Location / Other.", 'type' => 'text');  
	$fields[] = array('name' => 'rating', 'title' => 'Rating', 'description' => "The client's rating, if submitted along with their testimonial.  This can be displayed below the client's position, or name if the position is hidden, or it can be displayed above the testimonial text.", 'type' => 'text');  
	//$fields[] = array('name' => 'htid', 'title' => 'HTID', 'description' => "Please leave this alone -- this field should never be publicly displayed.");  
	$myCustomType = new ikTestimonialsCustomPostType($postType, $fields);
	register_taxonomy( 'easy-testimonial-category', 'testimonial', array( 'hierarchical' => true, 'label' => __('Testimonial Category', 'easy-testimonials'), 'rewrite' => array('slug' => 'testimonial-category', 'with_front' => true) ) ); 
	
	//load list of current posts that have featured images	
	$supportedTypes = get_theme_support( 'post-thumbnails' );
	
	//none set, add them just to our type
    if( $supportedTypes === false ){
        add_theme_support( 'post-thumbnails', array( 'testimonial' ) );       
		//for the testimonial thumb images    
	}
	//specifics set, add our to the array
    elseif( is_array( $supportedTypes ) ){
        $supportedTypes[0][] = 'testimonial';
        add_theme_support( 'post-thumbnails', $supportedTypes[0] );
		//for the testimonial thumb images
    }
	//if neither of the above hit, the theme in general supports them for everything.  that includes us!
	
	add_image_size( 'easy_testimonial_thumb', 50, 50, true );
		
	add_action( 'admin_menu', 'easy_t_add_meta_boxes'); // add our custom meta boxes
}

function easy_t_add_meta_boxes(){
	add_meta_box( 'testimonial_shortcodes', 'Shortcodes', 'easy_t_display_shortcodes_meta_box', 'testimonial', 'side', 'default' );
}

//from http://codex.wordpress.org/Function_Reference/get_intermediate_image_sizes
function easy_t_output_image_options(){
	global $_wp_additional_image_sizes;
	$sizes = array();
	foreach( get_intermediate_image_sizes() as $s ){
		$sizes[ $s ] = array( 0, 0 );
		if( in_array( $s, array( 'thumbnail', 'medium', 'large' ) ) ){
			$sizes[ $s ][0] = get_option( $s . '_size_w' );
			$sizes[ $s ][1] = get_option( $s . '_size_h' );
		}else{
			if( isset( $_wp_additional_image_sizes ) && isset( $_wp_additional_image_sizes[ $s ] ) )
				$sizes[ $s ] = array( $_wp_additional_image_sizes[ $s ]['width'], $_wp_additional_image_sizes[ $s ]['height'], );
		}
	}

	$current_size = get_option('easy_t_image_size');
	
	foreach( $sizes as $size => $atts ){
		$disabled = '';
		$selected = '';
		$register = '';
		
		if(!isValidKey()){
			$disabled = 'disabled="DISABLED"';
			$current_size = 'easy_testimonial_thumb';
			$register = " - Register to Enable!";
		}
		if($current_size == $size){
			$selected = 'selected="SELECTED"';
			$disabled = '';
			$register = '';
		}
		echo "<option value='".$size."' ".$disabled . " " . $selected.">" . ucwords(str_replace("-", " ", str_replace("_", " ", $size))) . ' ' . implode( 'x', $atts ) . $register . "</option>";
	}
}
 
//this is the heading of the new column we're adding to the testimonial posts list
function easy_t_column_head($defaults) {  
	$defaults = array_slice($defaults, 0, 2, true) +
    array("single_shortcode" => "Shortcode") +
    array_slice($defaults, 2, count($defaults)-2, true);
    return $defaults;  
}  

//this content is displayed in the testimonial post list
function easy_t_columns_content($column_name, $post_ID) {  
    if ($column_name == 'single_shortcode') {  
		echo "<input type=\"text\" value=\"[single_testimonial id={$post_ID}]\" />";
    }  
} 

//this is the heading of the new column we're adding to the testimonial category list
function easy_t_cat_column_head($defaults) {  
	$defaults = array_slice($defaults, 0, 2, true) +
    array("single_shortcode" => "Shortcode") +
    array_slice($defaults, 2, count($defaults)-2, true);
    return $defaults;  
}  

//this content is displayed in the testimonial category list
function easy_t_cat_columns_content($value, $column_name, $tax_id) {  

	$category = get_term_by('id', $tax_id, 'easy-testimonial-category');
	
	return "<textarea>[testimonials category='{$category->slug}']</textarea>"; 
} 

//return an array of random numbers within a given range
//credit: http://stackoverflow.com/questions/5612656/generating-unique-random-numbers-within-a-range-php
function UniqueRandomNumbersWithinRange($min, $max, $quantity) {
    $numbers = range($min, $max);
    shuffle($numbers);
    return array_slice($numbers, 0, $quantity);
}

//load testimonials into an array and output a random one
function outputRandomTestimonial($atts){
	//load shortcode attributes into an array
	extract( shortcode_atts( array(
		'testimonials_link' => get_option('testimonials_link'),
		'count' => 1,
		'word_limit' => false,
		'body_class' => 'testimonial_body',
		'author_class' => 'testimonial_author',
		'show_title' => 0,
		'short_version' => false,
		'use_excerpt' => false,
		'category' => '',
		'show_thumbs' => NULL,
		'show_rating' => false,
		'theme' => '',
		'show_date' => false,
		'show_other' => false,
		'width' => false
	), $atts ) );
	
	$show_thumbs = ($show_thumbs === NULL) ? get_option('testimonials_image') : $show_thumbs;
	
	//load testimonials into an array
	$i = 0;
	$loop = new WP_Query(array( 'post_type' => 'testimonial','posts_per_page' => '-1', 'easy-testimonial-category' => $category));
	while($loop->have_posts()) : $loop->the_post();
		$postid = get_the_ID();	
		$testimonials[$i]['date'] = get_the_date('M. j, Y');
		//load rating
		//if set, append english text to it
		$testimonials[$i]['rating'] = get_post_meta($postid, '_ikcf_rating', true); 
		$testimonial['num_stars'] = ''; //reset num stars (Thanks Steve@IntegrityConsultants!)
		if(strlen($testimonials[$i]['rating'])>0){
			$testimonials[$i]['num_stars'] = $testimonials[$i]['rating'];
			$testimonials[$i]['rating'] = '<p class="easy_t_ratings" itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating"><meta itemprop="worstRating" content = "1"/><span itemprop="ratingValue">' . $testimonials[$i]['rating'] . '</span>/<span itemprop="bestRating">5</span> Stars.</p>';
		}	

		if($use_excerpt){
			$testimonials[$i]['content'] = get_the_excerpt();
		} else {				
			$testimonials[$i]['content'] = get_the_content();
		}
		
		//if nothing is set for the short content, use the long content
		if(strlen($testimonials[$i]['content']) < 2){
			$temp_post_content = get_post($postid); 			
			if($use_excerpt){
				$testimonials[$i]['content'] = $temp_post_content->post_excerpt;
				if($testimonials[$i]['content'] == ''){
					$testimonials[$i]['content'] = wp_trim_excerpt($temp_post_content->post_content);
				}
			} else {				
				$testimonials[$i]['content'] = $temp_post_content->post_content;
			}
		}
		
		if ($word_limit) {
			$testimonials[$i]['content'] = word_trim($testimonials[$i]['content'], 65, TRUE);
		}
			
		if(strlen($show_rating)>2){
			if($show_rating == "before"){
				$testimonials[$i]['content'] = $testimonials[$i]['rating'] . ' ' . $testimonials[$i]['content'];
			}
			if($show_rating == "after"){
				$testimonials[$i]['content'] =  $testimonials[$i]['content'] . ' ' . $testimonials[$i]['rating'];
			}
		}
		
		if ($show_thumbs) {
			$testimonials[$i]['image'] = build_testimonial_image($postid);
		}
		
		$testimonials[$i]['title'] = get_the_title($postid);	
		$testimonials[$i]['postid'] = $postid;	
		$testimonials[$i]['client'] = get_post_meta($postid, '_ikcf_client', true); 	
		$testimonials[$i]['position'] = get_post_meta($postid, '_ikcf_position', true); 
		$testimonials[$i]['other'] = get_post_meta($postid, '_ikcf_other', true); 
		
		$i++;
	endwhile;
	wp_reset_postdata();
	
	$randArray = UniqueRandomNumbersWithinRange(0,$i-1,$count);
	
	ob_start();
	
	foreach($randArray as $key => $rand){
		if(isset($testimonials[$rand])){
			$this_testimonial = $testimonials[$rand];
			if(!$short_version){
				echo build_single_testimonial($this_testimonial,$show_thumbs,$show_title,$this_testimonial['postid'],$author_class,$body_class,$testimonials_link,$theme,$show_date,$show_rating,$show_other,$width);
			} else {
				echo $this_testimonial['content'];
			}
		}
	}
	
	$content = ob_get_contents();
	ob_end_clean();
	
	return apply_filters('easy_t_random_testimonials_html', $content);
}

//output specific testimonial
function outputSingleTestimonial($atts){ 
	//load shortcode attributes into an array
	extract( shortcode_atts( array(
		'testimonials_link' => get_option('testimonials_link'),
		'show_title' => 0,
		'body_class' => 'testimonial_body',
		'author_class' => 'testimonial_author',
		'id' => '',
		'use_excerpt' => false,
		'show_thumbs' => NULL,
		'short_version' => false,
		'word_limit' => false,
		'show_rating' => false,
		'theme' => '',
		'show_date' => false,
		'show_other' => false,
		'width' => false
	), $atts ) );
	
	$show_thumbs = ($show_thumbs === NULL) ? get_option('testimonials_image') : $show_thumbs;
	
	ob_start();
	
	$i = 0;
	
	//load testimonials into an array
	$loop = new WP_Query(array( 'post_type' => 'testimonial','p' => $id));
	while($loop->have_posts()) : $loop->the_post();
		$postid = get_the_ID();
		$testimonial['date'] = get_the_date('M. j, Y');
		$testimonial['client'] = get_post_meta($postid, '_ikcf_client', true); 	
		$testimonial['position'] = get_post_meta($postid, '_ikcf_position', true); 

		//load rating
		//if set, append english text to it
		$testimonial['rating'] = get_post_meta($postid, '_ikcf_rating', true); 
		$testimonial['num_stars'] = ''; //reset num stars (Thanks Steve@IntegrityConsultants!)
		if(strlen($testimonial['rating'])>0){
			$testimonial['num_stars'] = $testimonial['rating'];
			$testimonial['rating'] = '<p class="easy_t_ratings" itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating"><meta itemprop="worstRating" content = "1"/><span itemprop="ratingValue">' . $testimonial['rating'] . '</span>/<span itemprop="bestRating">5</span> Stars.</p>';
			//$testimonial['rating'] = '<span class="easy_t_ratings">' . $testimonial['rating'] . '/5 Stars.</span>';
		}	
		
		if($use_excerpt){
			$testimonial['content'] = get_the_excerpt();
		} else {				
			$testimonial['content'] = get_the_content();
		}
		
		//if nothing is set for the short content, use the long content
		if(strlen($testimonial['content']) < 2){
			$temp_post_content = get_post($postid); 			
				$testimonial['content'] = $temp_post_content->post_excerpt;
			if($use_excerpt){
				if($testimonial['content'] == ''){
					$testimonial['content'] = wp_trim_excerpt($temp_post_content->post_content);
				}
			} else {				
				$testimonial['content'] = $temp_post_content->post_content;
			}
		}
			
		if(strlen($show_rating)>2){
			if($show_rating == "before"){
				$testimonial['content'] = $testimonial['rating'] . ' ' . $testimonial['content'];
			}
			if($show_rating == "after"){
				$testimonial['content'] =  $testimonial['content'] . ' ' . $testimonial['rating'];
			}
		}
		
		if ($show_thumbs) {		
			$testimonial['image'] = build_testimonial_image($postid);
		}
		
		$testimonial['client'] = get_post_meta($postid, '_ikcf_client', true); 	
		$testimonial['position'] = get_post_meta($postid, '_ikcf_position', true); 
		$testimonial['other'] = get_post_meta($postid, '_ikcf_other', true); 
	
		echo build_single_testimonial($testimonial,$show_thumbs,$show_title,$postid,$author_class,$body_class,$testimonials_link,$theme,$show_date,$show_rating,$show_other,$width);
			
	endwhile;	
	wp_reset_postdata();
	
	$content = ob_get_contents();
	ob_end_clean();	
	
	return apply_filters( 'easy_t_single_testimonial_html', $content);
}

//output all testimonials
function outputTestimonials($atts){ 
	
	//load shortcode attributes into an array
	extract( shortcode_atts( array(	
		'testimonials_link' => '',//get_option('testimonials_link'),
		'show_title' => 0,
		'count' => -1,
		'body_class' => 'testimonial_body',
		'author_class' => 'testimonial_author',
		'id' => '',
		'use_excerpt' => false,
		'category' => '',
		'show_thumbs' => NULL,
		'short_version' => false,
		'orderby' => 'date',//'none','ID','author','title','name','date','modified','parent','rand','menu_order'
		'order' => 'ASC',//'DESC'
		'show_rating' => false,
		'paginate' => false,
		'testimonials_per_page' => 10,
		'theme' => '',
		'show_date' => false,
		'show_other' => false,
		'width' => false
	), $atts ) );
	
	$show_thumbs = ($show_thumbs === NULL) ? get_option('testimonials_image') : $show_thumbs;
			
	if(!is_numeric($count)){
		$count = -1;
	}
	
	//if we are paging the testimonials, set the $count to the number of testimonials per page
	if($paginate){
		$count = $testimonials_per_page;
	}
	
	ob_start();
	
	$i = 0;
	
	//load testimonials into an array
	$loop = new WP_Query(array( 'post_type' => 'testimonial','posts_per_page' => $count, 'easy-testimonial-category' => $category, 'orderby' => $orderby, 'order' => $order, 'paged' => get_query_var( 'paged' )));
	while($loop->have_posts()) : $loop->the_post();
		$postid = get_the_ID();	
		$testimonial['date'] = get_the_date('M. j, Y');
		if($use_excerpt){
			$testimonial['content'] = get_the_excerpt();
		} else {				
			$testimonial['content'] = get_the_content();
		}

		//load rating
		//if set, append english text to it
		$testimonial['rating'] = get_post_meta($postid, '_ikcf_rating', true); 
		$testimonial['num_stars'] = ''; //reset num stars (Thanks Steve@IntegrityConsultants!)
		if(strlen($testimonial['rating'])>0){	
			$testimonial['num_stars'] = $testimonial['rating'];
			$testimonial['rating'] = '<p class="easy_t_ratings" itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating"><meta itemprop="worstRating" content = "1"/><span itemprop="ratingValue">' . $testimonial['rating'] . '</span>/<span itemprop="bestRating">5</span> Stars.</p>';
			//$testimonial['rating'] = '<span class="easy_t_ratings">' . $testimonial['rating'] . '/5 Stars.</span>';		
		}	
		
		//if nothing is set for the short content, use the long content
		if(strlen($testimonial['content']) < 2){
			$temp_post_content = get_post($postid); 			
				$testimonial['content'] = $temp_post_content->post_excerpt;
			if($use_excerpt){
				if($testimonial['content'] == ''){
					$testimonial['content'] = wp_trim_excerpt($temp_post_content->post_content);
				}
			} else {				
				$testimonial['content'] = $temp_post_content->post_content;
			}
		}
			
		if(strlen($show_rating)>2){
			if($show_rating == "before"){
				$testimonial['content'] = $testimonial['rating'] . ' ' . $testimonial['content'];
			}
			if($show_rating == "after"){
				$testimonial['content'] =  $testimonial['content'] . ' ' . $testimonial['rating'];
			}
		}
		
		if ($show_thumbs) {		
			$testimonial['image'] = build_testimonial_image($postid);
		}
		
		$testimonial['client'] = get_post_meta($postid, '_ikcf_client', true); 	
		$testimonial['position'] = get_post_meta($postid, '_ikcf_position', true); 
		$testimonial['other'] = get_post_meta($postid, '_ikcf_other', true); 	
	
		echo build_single_testimonial($testimonial,$show_thumbs,$show_title,$postid,$author_class,$body_class,$testimonials_link,$theme,$show_date,$show_rating,$show_other,$width);
			
	endwhile;	
	
	//output the pagination links, if instructed to do so
	//TBD: make all labels controllable via settings
	if($paginate){
		echo '<div class="easy_t_pagination">';
			echo '<div style="float:left;">' . get_previous_posts_link( __('Previous Testimonials', 'easy-testimonials') ) . '</div>';
			echo '<div style="float:right;">' . get_next_posts_link( __('Next Testimonials', 'easy-testimonials'), $loop->max_num_pages ) . '</div>';
		echo '</div>';
	}
	
	wp_reset_postdata();
	
	$content = ob_get_contents();
	ob_end_clean();	
	
	return apply_filters('easy_t_testimonials_html', $content);
}

/*
 * Displays a grid of testimonials, with the requested number of columns
 *
 * @param array $atts Shortcode options. These include the [testimonial]
					  shortcode attributes, which are passed through.
 *
 * @return string HTML representing the grid of testimonials.
 */
function easy_t_testimonials_grid_shortcode($atts)
{
	// load shortcode attributes into an array
	// note: these are mostly the same attributes as [testimonials] shortcode
	$atts = shortcode_atts( array(
		'testimonials_link' => '',//get_option('testimonials_link'),
		'show_title' => 0,
		'count' => -1,
		'body_class' => 'testimonial_body',
		'author_class' => 'testimonial_author',
		'id' => '',
		'ids' => '', // i've heard it both ways
		'use_excerpt' => false,
		'category' => '',
		'show_thumbs' => NULL,
		'short_version' => false,
		'orderby' => 'date',//'none','ID','author','title','name','date','modified','parent','rand','menu_order'
		'order' => 'ASC',//'DESC'
		'show_rating' => false,
		'paginate' => false,
		'testimonials_per_page' => 10,
		'theme' => '',
		'show_date' => false,
		'show_other' => false,
		'width' => false,
		'cols' => 3, // 1-10
		'grid_width' => false,
		'grid_spacing' => false,
		'grid_class' => '',
		'cell_width' => false,
		'responsive' => true,
		'equal_height_rows' => false
	), $atts );
	
	extract( $atts );
	
	// allow ids or id to be passed in
	if ( empty($id) && !empty($ids) ) {
		$id = $ids;
	}
	
	$testimonials_output = '';
	$col_counter = 1;
	$row_counter = 0;
	
	if ($equal_height_rows) {
		wp_enqueue_script('easy-testimonials-grid');
	}
	
	if ( empty($rows) ) {
		$rows  = -1;
	}
	
	// make sure $cols is between 1 and 10
	$cols = max( 1, min( 10, intval($cols) ) );
	
	// create CSS for cells (will be same on each cell)
	$cell_style_attr = '';
	$cell_css_rules = array();

	if ( !empty($grid_spacing) && intval($grid_spacing) > 0 ) {
		$coefficient = intval($grid_spacing) / 2;
		$unit = ( strpos($grid_spacing, '%') !== false ) ? '%' : 'px';
		$cell_margin = $coefficient . $unit;			
		$cell_css_rules[] = sprintf('margin-left: %s', $cell_margin);
		$cell_css_rules[] = sprintf('margin-right: %s', $cell_margin);			
	}

	if ( !empty($cell_width) && intval($cell_width) > 0 ) {
		$cell_css_rules[] = sprintf('width: %s', $cell_width);
	}

	$cell_style_attr = !empty($cell_css_rules) ? sprintf('style="%s"', implode(';', $cell_css_rules) ) : '';
	
	// combine the rules into a re-useable opening <div> tag to be used for each cell
	$cell_div_start = sprintf('<div class="easy_testimonials_grid_cell" %s>', $cell_style_attr);
	
	// grab all requested testimonials and build one cell (in HTML) for each
	// note: using WP_Query instead of get_posts in order to respect pagination
	//    	 more info: http://wordpress.stackexchange.com/a/191934
	$args = array(
		'post_type' => 'testimonial',
		'posts_per_page' => $count,
		'easy-testimonial-category' => $category,
		'orderby' => $orderby,
		'order' => $order,
		'paged' => get_query_var( 'paged' )
	);
	
	// restrict to specific posts if requested
	if ( !empty($id) ) {
		$args['post__in'] = array_map('intval', explode(',', $id));
	}
	
	$loop = new WP_Query($args);
	$in_row = false;
	while( $loop->have_posts() ) {
		$loop->the_post();

		if ($col_counter == 1) {
			$in_row = true;
			$row_counter++;
			$testimonials_output .= sprintf('<div class="easy_testimonials_grid_row easy_testimonials_grid_row_%d">', $row_counter);
		}
				
		$testimonials_output .= $cell_div_start;
	
		$postid = get_the_ID();
		$testimonials_output .= easy_t_get_single_testimonial_html($postid, $atts);
		
		$testimonials_output .= '</div>';

		if ($col_counter == $cols) {
			$in_row = false;
			$testimonials_output .= '</div><!--easy_testimonials_grid_row-->';
			$col_counter = 1;
		} else {
			$col_counter++;
		}
	} // endwhile;
	
	// close any half finished rows
	if ($in_row) {
		$testimonials_output .= '</div><!--easy_testimonials_grid_row-->';
	}
	
	// restore globals to their original values (i.e, $post and friends)
	wp_reset_postdata();
		
	// setup the grid's CSS, insert the grid of testimonials (the cells) 
	// into the grid, add a clearing div, and return the whole thing
	$grid_classes = array(
		'easy_testimonials_grid',
		'easy_testimonials_grid_' . $cols
	);
	
	if ($responsive) {
		$grid_classes[] = 'easy_testimonials_grid_responsive';
	}
	
	if ($equal_height_rows) {
		$grid_classes[] = 'easy_testimonials_grid_equal_height_rows';
	}	

	// add any grid classes specified by the user
	if ( !empty($grid_class) ) {
		$grid_classes = array_merge( $grid_classes, explode(' ', $grid_class) );
	}
	
	// combine all classes into an class attribute
	$grid_class_attr = sprintf( 'class="%s"', implode(' ', $grid_classes) );
	
	// add all style rules for the grid (currently, only specifies width)
	$grid_css_rules = array();
	if ( !empty($grid_width) && intval($grid_width) > 0 ) {
		$grid_css_rules[] = sprintf('width: %s', $grid_width);
	}
	
	// combine all CSS rules into an HTML style attribute
	$grid_style_attr = sprintf( 'style="%s"', implode(';', $grid_css_rules) );
		
	// add classes and CSS rules to the grid, insert cells, return result
	$grid_template = '<div %s %s>%s</div>';
	$grid_html = sprintf($grid_template, $grid_class_attr, $grid_style_attr, $testimonials_output);
	return $grid_html;
}

//output a single testimonial for each theme_array
//useful for demoing all of the themes or testing compatibility on a given website
//output all testimonials
function outputAllThemes($atts){ 
	
	//load options
	include("include/lib/config.php");	
	
	//load shortcode attributes into an array
	extract( shortcode_atts( array(	
		'testimonials_link' => '',//get_option('testimonials_link'),
		'show_title' => 0,
		'count' => 1,
		'body_class' => 'testimonial_body',
		'author_class' => 'testimonial_author',
		'id' => '',
		'use_excerpt' => false,
		'category' => '',
		'show_thumbs' => NULL,
		'short_version' => false,
		'orderby' => 'date',//'none','ID','author','title','name','date','modified','parent','rand','menu_order'
		'order' => 'ASC',//'DESC'
		'show_rating' => false,
		'paginate' => false,
		'testimonials_per_page' => 10,
		'theme' => '',
		'show_date' => false,
		'show_other' => false,
		'show_free_themes' => false,
		'width' => false
	), $atts ) );
	
	$show_thumbs = ($show_thumbs === NULL) ? get_option('testimonials_image') : $show_thumbs;
			
	if(!is_numeric($count)){
		$count = -1;
	}
	
	//if we are paging the testimonials, set the $count to the number of testimonials per page
	if($paginate){
		$count = $testimonials_per_page;
	}
	
	ob_start();
	
	$i = 0;
	
	//load testimonials into an array
	$loop = new WP_Query(array( 'post_type' => 'testimonial','posts_per_page' => $count, 'easy-testimonial-category' => $category, 'orderby' => $orderby, 'order' => $order, 'paged' => get_query_var( 'paged' )));
	while($loop->have_posts()) : $loop->the_post();
		$postid = get_the_ID();	
		$testimonial['date'] = get_the_date('M. j, Y');
		if($use_excerpt){
			$testimonial['content'] = get_the_excerpt();
		} else {				
			$testimonial['content'] = get_the_content();
		}

		//load rating
		//if set, append english text to it
		$testimonial['rating'] = get_post_meta($postid, '_ikcf_rating', true); 
		$testimonial['num_stars'] = ''; //reset num stars (Thanks Steve@IntegrityConsultants!)
		if(strlen($testimonial['rating'])>0){	
			$testimonial['num_stars'] = $testimonial['rating'];
			$testimonial['rating'] = '<p class="easy_t_ratings" itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating"><meta itemprop="worstRating" content = "1"/><span itemprop="ratingValue">' . $testimonial['rating'] . '</span>/<span itemprop="bestRating">5</span> Stars.</p>';
			//$testimonial['rating'] = '<span class="easy_t_ratings">' . $testimonial['rating'] . '/5 Stars.</span>';		
		}	
		
		//if nothing is set for the short content, use the long content
		if(strlen($testimonial['content']) < 2){
			$temp_post_content = get_post($postid); 			
				$testimonial['content'] = $temp_post_content->post_excerpt;
			if($use_excerpt){
				if($testimonial['content'] == ''){
					$testimonial['content'] = wp_trim_excerpt($temp_post_content->post_content);
				}
			} else {				
				$testimonial['content'] = $temp_post_content->post_content;
			}
		}
			
		if(strlen($show_rating)>2){
			if($show_rating == "before"){
				$testimonial['content'] = $testimonial['rating'] . ' ' . $testimonial['content'];
			}
			if($show_rating == "after"){
				$testimonial['content'] =  $testimonial['content'] . ' ' . $testimonial['rating'];
			}
		}
		
		if ($show_thumbs) {		
			$testimonial['image'] = build_testimonial_image($postid);
		}
		
		$testimonial['client'] = get_post_meta($postid, '_ikcf_client', true); 	
		$testimonial['position'] = get_post_meta($postid, '_ikcf_position', true); 
		$testimonial['other'] = get_post_meta($postid, '_ikcf_other', true); 	
			
	endwhile;	
	
	wp_reset_postdata();
	
	if($show_free_themes){
		foreach($free_theme_array as $theme_slug => $theme_name){
			echo "<h4>$theme_name</h4>";
			echo build_single_testimonial($testimonial,$show_thumbs,$show_title,$postid,$author_class,$body_class,$testimonials_link,$theme_slug,$show_date,$show_rating,$show_other,$width);
		}
	}
	
	foreach($pro_theme_array as $theme_set => $theme_set_array){
		foreach($theme_set_array as $theme_slug => $theme_name){
			echo "<h4>$theme_name</h4>";
			echo build_single_testimonial($testimonial,$show_thumbs,$show_title,$postid,$author_class,$body_class,$testimonials_link,$theme_slug,$show_date,$show_rating,$show_other,$width);
		}
	}
	
	$content = ob_get_contents();
	ob_end_clean();	
	
	return apply_filters('easy_t_testimonials_html', $content);
}

//output all testimonials for use in JS widget
function outputTestimonialsCycle($atts){ 	
	//load shortcode attributes into an array
	extract( shortcode_atts( array(
		'testimonials_link' => get_option('testimonials_link'),
		'show_title' => 0,
		'count' => -1,
		'transition' => 'scrollHorz',
		'show_thumbs' => NULL,
		'timer' => '2000',
		'container' => false,//deprecated, use auto_height instead
		'use_excerpt' => false,
		'auto_height' => false,
		'category' => '',
		'body_class' => 'testimonial_body',
		'author_class' => 'testimonial_author',
		'random' => '',
		'orderby' => 'date',//'none','ID','author','title','name','date','modified','parent','rand','menu_order'
		'order' => 'ASC',//'DESC'
		'pager' => false,
		'show_pager_icons' => false,
		'show_rating' => false,
		'testimonials_per_slide' => 1,
		'theme' => '',
		'show_date' => false,
		'show_other' => false,
		'pause_on_hover' => false,
		'prev_next' => false,
		'width' => false,
		'paused' => false
	), $atts ) );	
	
	$show_thumbs = ($show_thumbs === NULL) ? get_option('testimonials_image') : $show_thumbs;
			
	if(!is_numeric($count)){
		$count = -1;
	}
	
	ob_start();
	
	$i = 0;
	
	if(!isValidKey() && !in_array($transition, array('fadeOut','fade','scrollHorz'))){
		$transition = 'fadeout';
	}
	
	//use random WP query to be sure we aren't just randomly sorting a chronologically queried set of testimonials
	//this prevents us from just randomly ordering the same 5 testimonials constantly!
	if($random){
		$orderby = "rand";
	}

	//determine if autoheight is set to container or to calculate
	//not sure why i did this so backwards to begin with!  oh well...
	if($container){
		$container = "container";
	}
	if($auto_height == "calc"){
		$container = "calc";
	} else if($auto_height == "container"){
		$container = "container";
	}
	
	?>
	
	<div class="cycle-slideshow" 
		data-cycle-fx="<?php echo $transition; ?>" 
		data-cycle-timeout="<?php echo $timer; ?>"
		data-cycle-slides="> div.testimonial_slide"
		<?php if($container): ?> data-cycle-auto-height="<?php echo $container; ?>" <?php endif; ?>
		<?php if($random): ?> data-cycle-random="true" <?php endif; ?>
		<?php if($pause_on_hover): ?> data-cycle-pause-on-hover="true" <?php endif; ?>
		<?php if($paused): ?> data-cycle-paused="true" <?php endif; ?>
		<?php if($prev_next): ?> data-cycle-prev=".easy-t-cycle-prev"  data-cycle-next=".easy-t-cycle-next" <?php endif; ?>
	>
	<?php
	
	$counter = 0;
	
	//load testimonials into an array
	$loop = new WP_Query(array( 'post_type' => 'testimonial','posts_per_page' => $count, 'orderby' => $orderby, 'order' => $order, 'easy-testimonial-category' => $category));
	while($loop->have_posts()) : $loop->the_post();		
		if($counter == 0){
			$testimonial_display = '';
		} else {
			$testimonial_display = 'style="display:none;"';
		}
		
		if($counter%$testimonials_per_slide == 0){
			?><div <?php echo $testimonial_display; ?> class="testimonial_slide"><?php
		}
		
		$counter ++;
	
		$postid = get_the_ID();

		$testimonial['date'] = get_the_date('M. j, Y');
		
		//if nothing is set for the short content, use the long content
		if($use_excerpt){
			$testimonial['content'] = get_the_excerpt();
		} else {				
			$testimonial['content'] = get_the_content();
		}

		//load rating
		//if set, append english text to it
		$testimonial['rating'] = get_post_meta($postid, '_ikcf_rating', true); 
		$testimonial['num_stars'] = ''; //reset num stars (Thanks Steve@IntegrityConsultants!)
		if(strlen($testimonial['rating'])>0){
			$testimonial['num_stars'] = $testimonial['rating'];
			$testimonial['rating'] = '<p class="easy_t_ratings" itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating"><meta itemprop="worstRating" content = "1"/><span itemprop="ratingValue">' . $testimonial['rating'] . '</span>/<span itemprop="bestRating">5</span> Stars.</p>';
			//$testimonial['rating'] = '<span class="easy_t_ratings">' . $testimonial['rating'] . '/5 Stars.</span>';
		}	
		
		//if nothing is set for the short content, use the long content
		if(strlen($testimonial['content']) < 2){
			$temp_post_content = get_post($postid); 			
				$testimonial['content'] = $temp_post_content->post_excerpt;
			if($use_excerpt){
				if($testimonial['content'] == ''){
					$testimonial['content'] = wp_trim_excerpt($temp_post_content->post_content);
				}
			} else {				
				$testimonial['content'] = $temp_post_content->post_content;
			}
		}
			
		if(strlen($show_rating)>2){			
			if($show_rating == "before"){
				$testimonial['content'] = $testimonial['rating'] . ' ' . $testimonial['content'];
			}
			if($show_rating == "after"){
				$testimonial['content'] =  $testimonial['content'] . ' ' . $testimonial['rating'];
			}
		}
		
		if ($show_thumbs) {		
			$testimonial['image'] = build_testimonial_image($postid);
		}
		
		$testimonial['client'] = get_post_meta($postid, '_ikcf_client', true); 	
		$testimonial['position'] = get_post_meta($postid, '_ikcf_position', true); 
		$testimonial['other'] = get_post_meta($postid, '_ikcf_other', true); 
		
		echo build_single_testimonial($testimonial,$show_thumbs,$show_title,$postid,$author_class,$body_class,$testimonials_link,$theme,$show_date,$show_rating,$show_other,$width);
		
		if($counter%$testimonials_per_slide == 0){
			?></div><?php
		}
		
	endwhile;	
	wp_reset_postdata();
	
	//display pager icons
	if($pager || $show_pager_icons ){
		?><div class="cycle-pager"></div><?php
	}
	
	?>
	</div>
	<?php
	
	//display previous and next buttons
	//do it after the closing div so it is outside of the slideshow container
	if($prev_next){
		?><div class="cycle-prev easy-t-cycle-prev"><?php _e('&lt;&lt; Prev', 'easy-testimonials'); ?> </div>
		<div class="cycle-next easy-t-cycle-next"><?php _e('Next &gt;&gt;', 'easy-testimonials'); ?></div><?php
	}
	
	$content = ob_get_contents();
	ob_end_clean();	
	
	return apply_filters( 'easy_t_testimonials_cyle_html', $content);
}

//passed an array of acceptable shortcode attributes
//this function will build a string of classes representing the chosen attributes
//returns string ready for echoing as classes
function easy_t_build_classes_from_atts($atts = array()){
	$class_string = "";
	
	foreach ($atts as $key => $value){
		$class_string .= " " . $value . "_" . $key;
	}
	
	return $class_string;
}

/*
 * Generates and returns the HTML for a given testimonial, 
 * considering the shortcode attributess provided.
 *
 * @param integer $postid The post ID of the testimonial
 * @param array $atts The shortcode attributes to use for build this testimonial
 *
 * @return string The HTML output for this testimonial
 */
function easy_t_get_single_testimonial_html($postid, $atts)
{
	global $post; 
	$post = get_post( $postid, OBJECT );
	setup_postdata( $post );

	extract($atts);
	
	ob_start();
	
	$testimonial['date'] = get_the_date('M. j, Y');
	if($use_excerpt){
		$testimonial['content'] = get_the_excerpt();
	} else {				
		$testimonial['content'] = get_the_content();
	}

	//load rating
	//if set, append english text to it
	$testimonial['rating'] = get_post_meta($postid, '_ikcf_rating', true); 
	$testimonial['num_stars'] = ''; //reset num stars (Thanks Steve@IntegrityConsultants!)
	if(strlen($testimonial['rating'])>0){	
		$testimonial['num_stars'] = $testimonial['rating'];
		$testimonial['rating'] = '<p class="easy_t_ratings" itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating"><meta itemprop="worstRating" content = "1"/><span itemprop="ratingValue">' . $testimonial['rating'] . '</span>/<span itemprop="bestRating">5</span> Stars.</p>';
		//$testimonial['rating'] = '<span class="easy_t_ratings">' . $testimonial['rating'] . '/5 Stars.</span>';		
	}	
	
	//if nothing is set for the short content, use the long content
	if(strlen($testimonial['content']) < 2){
		//$temp_post_content = get_post($postid); 			
		$testimonial['content'] = $post->post_excerpt;
		if($use_excerpt){
			if($testimonial['content'] == ''){
				$testimonial['content'] = wp_trim_excerpt($post->post_content);
			}
		} else {				
			$testimonial['content'] = $post->post_content;
		}
	}
		
	if(strlen($show_rating)>2){
		if($show_rating == "before"){
			$testimonial['content'] = $testimonial['rating'] . ' ' . $testimonial['content'];
		}
		if($show_rating == "after"){
			$testimonial['content'] =  $testimonial['content'] . ' ' . $testimonial['rating'];
		}
	}
	
	if ($show_thumbs) {		
		$testimonial['image'] = build_testimonial_image($postid);
	}
	
	$testimonial['client'] = get_post_meta($postid, '_ikcf_client', true); 	
	$testimonial['position'] = get_post_meta($postid, '_ikcf_position', true); 
	$testimonial['other'] = get_post_meta($postid, '_ikcf_other', true); 	

	build_single_testimonial($testimonial,$show_thumbs,$show_title,$postid,$author_class,$body_class,$testimonials_link,$theme,$show_date,$show_rating,$show_other,$width);
	
	wp_reset_postdata();	
	$content = ob_get_contents();
	ob_end_clean();	
	return $content;
}

//given a full set of data for a testimonial
//assemble the html for that testimonial
//taking into account current options
function build_single_testimonial($testimonial,$show_thumbs=false,$show_title=false,$postid,$author_class,$body_class,$testimonials_link,$theme,$show_date=false,$show_rating=false,$show_other=true,$width=false){
/* scheme.org example
 <div itemprop="review" itemscope itemtype="http://schema.org/Review">
    <span itemprop="name">Not a happy camper</span> -
    by <span itemprop="author">Ellie</span>,
    <meta itemprop="datePublished" content="2011-04-01">April 1, 2011
    <div itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating">
      <meta itemprop="worstRating" content = "1">
      <span itemprop="ratingValue">1</span>/
      <span itemprop="bestRating">5</span>stars
    </div>
    <span itemprop="description">The lamp burned out and now I have to replace
    it. </span>
  </div>
 */
	$atts = array(
		'thumbs' => ($show_thumbs) ? 'show' : 'hide',
		'title' => ($show_title) ? 'show' : 'hide',
		'date' => ($show_date) ? 'show' : 'hide',
		'rating' => $show_rating,
		'other' => ($show_other) ? 'show' : 'hide'
	);
	$attribute_classes = easy_t_build_classes_from_atts($atts);
 
	$output_theme = easy_t_get_theme_class($theme);
	$testimonial_body_css = easy_testimonials_build_typography_css('easy_t_body_');	
	$width = $width ? 'style="width: ' . $width . '"' : get_option('easy_t_width','');
	
?>
	<div class="<?php echo $output_theme; ?> <?php echo $attribute_classes; ?> easy_t_single_testimonial" <?php echo $width; ?>>
		<blockquote itemprop="review" itemscope itemtype="http://schema.org/Review" class="easy_testimonial" style="<?php echo $testimonial_body_css; ?>">
			<?php if ($show_thumbs) {
				echo $testimonial['image'];
			} ?>		
			<?php if ($show_title) {
				echo '<p itemprop="name" class="easy_testimonial_title">' . get_the_title($postid) . '</p>';
			} ?>	
			<?php if(get_option('meta_data_position')) {
				easy_testimonials_build_metadata_html($testimonial, $author_class, $show_date, $show_rating, $show_other);	
			} ?>
			<div class="<?php echo $body_class; ?>" itemprop="description">
				<?php if(get_option('easy_t_apply_content_filter',false)): ?>
					<?php echo apply_filters('the_content',$testimonial['content']); ?>
				<?php else:?>
					<?php echo wpautop($testimonial['content']); ?>
				<?php endif;?>
				<?php if(strlen($testimonials_link)>2):?><a href="<?php echo $testimonials_link; ?>" class="easy_testimonials_read_more_link"><?php echo get_option('easy_t_view_more_link_text', 'Read More Testimonials'); ?></a><?php endif; ?>
			</div>	
			<?php if(!get_option('meta_data_position')) {	
				easy_testimonials_build_metadata_html($testimonial, $author_class, $show_date, $show_rating, $show_other);	
			} ?>
		</blockquote>
	</div>
<?php
}

/*
 * Assemble the HTML for the Testimonial Image taking into account current options
 */		
function build_testimonial_image($postid){
	//load image size settings
	$testimonial_image_size = isValidKey() ? get_option('easy_t_image_size') : "easy_testimonial_thumb";
	if(strlen($testimonial_image_size) < 2){
		$testimonial_image_size = "easy_testimonial_thumb";		
		$width = 50;
        $height = 50;
	} else {		
		//one of the default sizes, load using get_option
		if( in_array( $testimonial_image_size, array( 'thumbnail', 'medium', 'large' ) ) ){
			$width = get_option( $testimonial_image_size . '_size_w' );
			$height = get_option( $testimonial_image_size . '_size_h' );
		//size added by theme, user, or plugin
		//load using additional image sizes global
		}else{
			global $_wp_additional_image_sizes;
			
			if( isset( $_wp_additional_image_sizes ) && isset( $_wp_additional_image_sizes[ $testimonial_image_size ] ) ){
				$width = $_wp_additional_image_sizes[ $testimonial_image_size ]['width'];
				$height = $_wp_additional_image_sizes[ $testimonial_image_size ]['height'];
			}
		}
	}
	
	//use whichever of the two dimensions is larger
	$size = ($width > $height) ? $width : $height;

	//load testimonial's featured image
	$image = get_the_post_thumbnail($postid, $testimonial_image_size);
	
	//if no featured image is set
	if (strlen($image) < 2){ 
		//if use mystery man is set
		if (get_option('easy_t_mystery_man', 1)){
			//check and see if gravatars are enabled
			if(get_option('easy_t_gravatar', 1)){
				//if so, set image path to match desired gravatar with the mystery man as a fallback
				$client_email = get_post_meta($postid, '_ikcf_email', true); 
				$gravatar = md5(strtolower(trim($client_email)));
				$mystery_man = urlencode(plugins_url('include/css/mystery_man.png', __FILE__));
				
				$image = '<img class="attachment-easy_testimonial_thumb wp-post-image easy_testimonial_gravatar" src="//www.gravatar.com/avatar/' . $gravatar . '?d=' . $mystery_man . '&s=' . $size . '" />';
			} else {
				//if not, just use the mystery man
				$image = '<img class="attachment-easy_testimonial_thumb wp-post-image easy_testimonial_mystery_man" src="' . plugins_url('include/css/mystery_man.png', __FILE__) . '" />';
			}
		//else if gravatar is set
		} else if(get_option('easy_t_gravatar', 1)){
			//if set, set image path to match gravatar without using the mystery man as a fallback
			$client_email = get_post_meta($postid, '_ikcf_email', true); 
			$gravatar = md5(strtolower(trim($client_email)));
			$mystery_man = urlencode(plugins_url('include/css/mystery_man.png', __FILE__));
			
			$image = '<img class="attachment-easy_testimonial_thumb wp-post-image easy_testimonial_gravatar" src="//www.gravatar.com/avatar/' . $gravatar . '?s=' . $size . '" />';
		}
	}
	
	return $image;
}
 
/*
 *  Assemble the html for the testimonials metadata taking into account current options
 */
function easy_testimonials_build_metadata_html($testimonial, $author_class, $show_date, $show_rating, $show_other)
{
	$date_css = easy_testimonials_build_typography_css('easy_t_date_');
	$position_css = easy_testimonials_build_typography_css('easy_t_position_');
	$client_css = easy_testimonials_build_typography_css('easy_t_author_');
	$rating_css = easy_testimonials_build_typography_css('easy_t_rating_');
?>
	<p class="<?php echo $author_class; ?>">
		<?php if(strlen($testimonial['client'])>0 || strlen($testimonial['position'])>0 ): ?>
		<cite>
			<span class="testimonial-client" itemprop="author" style="<?php echo $client_css; ?>"><?php echo $testimonial['client'];?>&nbsp;</span>
			<span class="testimonial-position" style="<?php echo $position_css; ?>"><?php echo $testimonial['position'];?>&nbsp;</span>
			<?php if($show_other && strlen($testimonial['other'])>1): ?>
					<span class="testimonial-other" itemprop="itemReviewed"><?php echo $testimonial['other'];?>&nbsp;</span>
			<?php endif; ?>
			<?php if($show_date): ?>
				<span class="date" itemprop="datePublished" content="<?php echo $testimonial['date'];?>" style="<?php echo $date_css; ?>"><?php echo $testimonial['date'];?>&nbsp;</span>
			<?php endif; ?>
			<?php if($show_rating == "stars"): ?>
				<?php if(strlen($testimonial['num_stars'])>0): ?>
				<span itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating" class="stars">
				<meta itemprop="worstRating" content="1"/>
				<meta itemprop="ratingValue" content="<?php echo $testimonial['num_stars']; ?>"/>
				<meta itemprop="bestRating" content="5"/>
				<?php			
					$x = 5; //total available stars
					//output dark stars for the filled in ones
					for($i = 0; $i < $testimonial['num_stars']; $i ++){
						echo '<span class="dashicons dashicons-star-filled"></span>';
						$x--; //one less star available
					}
					//fill out the remaining empty stars
					for($i = 0; $i < $x; $i++){
						echo '<span class="dashicons dashicons-star-filled empty"></span>';
					}
				?>			
				</span>	
				<?php endif; ?>
			<?php endif; ?>
		</cite>
		<?php endif; ?>					
	</p>	
<?php
}

//passed a string
//finds a matching theme or loads the theme currently selected on the options page
//returns appropriate class name string to match theme
function easy_t_get_theme_class($theme_string){	
	$the_theme = get_option('testimonials_style');
	
	//load options
	include("include/lib/config.php");			
	
	//if the theme string is passed
	if(strlen($theme_string)>2){
		//if the theme string is valid
		if(in_array($theme_string, $theme_array)){			
			//use the theme string
			$the_theme = $theme_string;
		}
	}
	
	//remove style from the middle of our theme options and place it as a prefix
	//matching our CSS files
	$the_theme = str_replace('-style', '', $the_theme);
	$the_theme = "style-" . $the_theme;	
	
	return $the_theme;
}

//only do this once
function easy_testimonials_rewrite_flush() {
    easy_testimonials_setup_testimonials();
	
    flush_rewrite_rules();
}

//register any widgets here
function easy_testimonials_register_widgets() {
	include('include/widgets/random_testimonial_widget.php');
	include('include/widgets/single_testimonial_widget.php');
	include('include/widgets/testimonial_cycle_widget.php');
	include('include/widgets/testimonial_list_widget.php');
	include('include/widgets/testimonial_grid_widget.php');
	include('include/widgets/submit_testimonial_widget.php');

	register_widget( 'randomTestimonialWidget' );
	register_widget( 'cycledTestimonialWidget' );
	register_widget( 'listTestimonialsWidget' );
	register_widget( 'singleTestimonialWidget' );
	register_widget( 'submitTestimonialWidget' );
	register_widget( 'TestimonialsGridWidget' );
}

function easy_testimonials_admin_init($hook)
{	
	//RWG: only enqueue scripts and styles on Easy T admin pages or widgets page
	$screen = get_current_screen();
	
	if ( 	strpos($hook,'easy-testimonials')!==false || 
			$screen->id === "widgets" || 
			(function_exists('is_customize_preview') && is_customize_preview()))
	{
		wp_register_style( 'easy_testimonials_admin_stylesheet', plugins_url('include/css/admin_style.css', __FILE__) );
		wp_enqueue_style( 'easy_testimonials_admin_stylesheet' );
		wp_enqueue_script(
			'easy-testimonials-admin',
			plugins_url('include/js/easy-testimonials-admin.js', __FILE__),
			array( 'jquery' ),
			false,
			true
		); 
		wp_enqueue_script(
			'gp-admin_v2',
			plugins_url('include/js/gp-admin_v2.js', __FILE__),
			array( 'jquery' ),
			false,
			true
		);	
	}
	
	// also include some styles on *all* admin pages
	wp_register_style( 'easy_testimonials_admin_stylesheet_global', plugins_url('include/css/admin_style_global.css', __FILE__) );
	wp_enqueue_style( 'easy_testimonials_admin_stylesheet_global' );
}

//check for installed plugins with known conflicts
//if any are found, display appropriate messaging with suggested steps
//currently only checks for woothemes testimonials
function easy_testimonials_conflict_check($hook_suffix){		
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	
	$plugin = "testimonials-by-woothemes/woothemes-testimonials.php";
		
	if(is_plugin_active($plugin)){
		
		if (strpos($hook_suffix,'easy-testimonials') !== false) {
			add_action('admin_notices', 'easy_t_woothemes_testimonials_admin_notice');
		}
	}
	else {
		return false;
	}
}

//output warning message about woothemes testimonials conflicts
function easy_t_woothemes_testimonials_admin_notice(){
	echo '<div class="error"><p>';
	echo '<strong>ALERT:</strong> We have detected that Testimonials by WooThemes is installed.<br/><br/>  This plugin has known conflicts with Easy Testimonials. To prevent any issues, we recommend deactivating Testimonials by WooThemes while using Easy Testimonials.';
	echo "</p></div>";
}

//add an inline link to the settings page, before the "deactivate" link
function add_settings_link_to_plugin_action_links($links) { 
  $settings_link = '<a href="admin.php?page=easy-testimonials-settings">Settings</a>';
  array_unshift($links, $settings_link); 
  return $links; 
}

// add inline links to our plugin's description area on the Plugins page
function add_custom_links_to_plugin_description($links, $file) { 

	/** Get the plugin file name for reference */
	$plugin_file = plugin_basename( __FILE__ );
 
	/** Check if $plugin_file matches the passed $file name */
	if ( $file == $plugin_file )
	{		
		$new_links['settings_link'] = '<a href="admin.php?page=easy-testimonials-settings">Settings</a>';
		$new_links['support_link'] = '<a href="https://goldplugins.com/contact/?utm-source=plugin_menu&utm_campaign=support&utm_banner=bananaphone" target="_blank">Get Support</a>';
			
		if(!isValidKey()){
			$new_links['upgrade_to_pro'] = '<a href="https://goldplugins.com/our-plugins/easy-testimonials-details/upgrade-to-easy-testimonials-pro/?utm_source=plugin_menu&utm_campaign=upgrade" target="_blank">Upgrade to Pro</a>';
		}
		
		$links = array_merge( $links, $new_links);
	}
	return $links; 
}
	
/* Displays a meta box with the shortcodes to display the current testimonial */
function easy_t_display_shortcodes_meta_box() {
	global $post;
	echo "<strong>To display this testimonial</strong>, add this shortcode to any post or page:<br />";	
	$ex_shortcode = sprintf('[single_testimonial id="%d"]', $post->ID);	
	printf('<textarea class="gp_highlight_code">%s</textarea>', $ex_shortcode);
}

/* CSV import / export */
	
/* Looks for a special POST value, and if its found, outputs a CSV of testimonials */
function process_export()
{
	// look for an Export command first
	if (isset($_POST['_easy_t_do_export']) && $_POST['_easy_t_do_export'] == '_easy_t_do_export') {
		$exporter = new TestimonialsPlugin_Exporter();
		$exporter->process_export();
		exit();
	}
}

/* hello t integration */

//open up the json
//determine which testimonials are new, or assume we have loaded only new testimonials
//parse object and insert new testimonials
function add_hello_t_testimonials(){	
	$the_time = time();
	
	$url = get_option('easy_t_hello_t_json_url') . "?last=" . get_option('easy_t_hello_t_last_time', 0);
	
	$response = wp_remote_get( $url, array('sslverify' => false ));
			
	if(@isset($response['body'])){
		$response = json_decode($response['body']);
		
		if(isset($response->testimonials)){
			foreach($response->testimonials as $testimonial){				
				//look for a testimonial with the same HTID
				//if not found, insert this one
				$args = array(
					'post_type' => 'testimonial',
					'meta_query' => array(
						array(
							'key' => '_ikcf_htid',
							'value' => $testimonial->id,
						)
					)
				 );
				$postslist = get_posts( $args );
				
				//if this is empty, a match wasn't found and therefore we are safe to insert
				if(empty($postslist)){				
					//insert the testimonials
					
					//defaults
					$the_name = '';
					$the_rating = 5;
		
					if (isset ($testimonial->name)) {
						$the_name = $testimonial->name;
					}
					
					//assumes rating is always out of 5
					if (isset ($testimonial->rating)) {
						$the_rating = $testimonial->rating;
					}
					
					$tags = array();
				   
					$post = array(
						'post_title'    => $testimonial->name,
						'post_content'  => $testimonial->body,
						'post_category' => array(1),  // custom taxonomies too, needs to be an array
						'tags_input'    => $tags,
						'post_status'   => 'publish',
						'post_type'     => 'testimonial'
					);
				
					$new_id = wp_insert_post($post);
				   
					update_post_meta( $new_id, '_ikcf_client', $the_name );
					update_post_meta( $new_id, '_ikcf_rating', $the_rating );
					update_post_meta( $new_id, '_ikcf_htid', $testimonial->id );
				   
					$inserted = true;
					
					//update the last inserted id
					update_option( 'easy_t_hello_t_last_time', $the_time );
				}
			}
		}
	}
}

function hello_t_nag_ignore() {
	global $current_user;
	$user_id = $current_user->ID;
	/* If user clicks to ignore the notice, add that to their user meta */
	if ( isset($_GET['hello_t_nag_ignore']) && '0' == $_GET['hello_t_nag_ignore'] ) {
		 add_user_meta($user_id, 'hello_t_nag_ignore', 'true', true);
	}
}

//activate the cron job
function hello_t_cron_activate(){
	wp_schedule_event( time(), 'hourly', 'hello_t_subscription');
}

//deactivate the cron job when the plugin is deactivated
function hello_t_cron_deactivate(){
	wp_clear_scheduled_hook('hello_t_subscription');
}

add_action('hello_t_subscription', 'add_hello_t_testimonials');

//this runs a function when this plugin is deactivated
register_deactivation_hook( __FILE__, 'hello_t_cron_deactivate' );

/* end hello t integration */

/* Styling Functions */
/*
* Builds a CSS string corresponding to the values of a typography setting
*
* @param $prefix The prefix for the settings. We'll append font_name,
* font_size, etc to this prefix to get the actual keys
*
* @returns string The completed CSS string, with the values inlined
*/
function easy_testimonials_build_typography_css($prefix)
{
	$css_rule_template = ' %s: %s;';
	$output = '';
	if (!isValidKey()) {
		return $output;
	}
	/*
	* Font Family
	*/
	$option_val = get_option($prefix . 'font_family', '');
	if (!empty($option_val)) {
		// strip off 'google:' prefix if needed
		$option_val = str_replace('google:', '', $option_val);
		// wrap font family name in quotes
		$option_val = '\'' . $option_val . '\'';
		$output .= sprintf($css_rule_template, 'font-family', $option_val);
	}
	/*
	* Font Size
	*/
	$option_val = get_option($prefix . 'font_size', '');
	if (!empty($option_val)) {
		// append 'px' if needed
		if ( is_numeric($option_val) ) {
			$option_val .= 'px';
		}
		$output .= sprintf($css_rule_template, 'font-size', $option_val);
	}
	/*
	* Font Color
	*/
	$option_val = get_option($prefix . 'font_color', '');
	if (!empty($option_val)) {
		$output .= sprintf($css_rule_template, 'color', $option_val);
	}
	/*
	* Font Style - add font-style and font-weight rules
	* NOTE: in this special case, we are adding 2 rules!
	*/
	$option_val = get_option($prefix . 'font_style', '');
	// Convert the value to 2 CSS rules, font-style and font-weight
	// NOTE: we lowercase the value before comparison, for simplification
	switch(strtolower($option_val))
	{
		case 'regular':
			// not bold not italic
			$output .= sprintf($css_rule_template, 'font-style', 'normal');
			$output .= sprintf($css_rule_template, 'font-weight', 'normal');
		break;
		case 'bold':
			// bold, but not italic
			$output .= sprintf($css_rule_template, 'font-style', 'normal');
			$output .= sprintf($css_rule_template, 'font-weight', 'bold');
		break;
		case 'italic':
			// italic, but not bold
			$output .= sprintf($css_rule_template, 'font-style', 'italic');
			$output .= sprintf($css_rule_template, 'font-weight', 'normal');
		break;
		case 'bold italic':
			// bold and italic
			$output .= sprintf($css_rule_template, 'font-style', 'italic');
			$output .= sprintf($css_rule_template, 'font-weight', 'bold');
		break;
		default:
			// empty string or other invalid value, ignore and move on
		break;
	}
	// return the completed CSS string
	return trim($output);
}

function list_required_google_fonts()
{
	// check each typography setting for google fonts, and build a list
	$option_keys = array(
		'easy_t_body_font_family',
		'easy_t_author_font_family',
		'easy_t_position_font_family',
		'easy_t_date_font_family',
		'easy_t_rating_font_family'		
	);  
	$fonts = array();
	foreach ($option_keys as $option_key) {
		$option_value = get_option($option_key);
		if (strpos($option_value, 'google:') !== FALSE) {
			$option_value = str_replace('google:', '', $option_value);
			
			//only add the font to the array if it was in fact a google font
			$fonts[$option_value] = $option_value;				
		}
	}
	return $fonts;
}
	
// Enqueue any needed Google Web Fonts
function enqueue_webfonts()
{
	$cache_key = '_easy_t_webfont_str';
	$font_str = get_transient($cache_key);
	if ($font_str == false) {
		$font_list = list_required_google_fonts();
		if ( !empty($font_list) ) {
			$font_list_encoded = array_map('urlencode', $font_list);
			$font_str = implode('|', $font_list_encoded);
		} else {
			$font_str = 'x';
		}
		set_transient($cache_key, $font_str);		
	}
	
	//don't register this unless a font is set to register
	if(strlen($font_str)>2){
		$protocol = is_ssl() ? 'https:' : 'http:';
		$font_url = $protocol . '//fonts.googleapis.com/css?family=' . $font_str;
		wp_register_style( 'easy_testimonials_webfonts', $font_url);
		wp_enqueue_style( 'easy_testimonials_webfonts' );
	}
}

// Dashboard Widget Yang

/**
 * Add a widget to the dashboard.
	*
 * This function is hooked into the 'wp_dashboard_setup' action below.
 */
function easy_t_add_dashboard_widget() {
	wp_add_dashboard_widget(
		'easy_t_submissions_dashboard_widget',         // Widget slug.
		'Easy Testimonials Pro - Recent Submissions',         // Title.
		'easy_t_output_dashboard_widget' // Display function.
	);	
}

/**
 * Create the function to output the contents of our Dashboard Widget.
 */
function easy_t_output_dashboard_widget()
{
	
	$recent_submissions = '';
	
	$recent_submissions = get_posts('post_type=testimonial&posts_per_page=10&post_status=pending');
	
	if (is_array($recent_submissions)) {
		//also output a panel of stats (ie, # of pending submissions)
		
		echo '<table id="easy_t_recent_submissions" class="widefat">';
		echo '<thead>';
		echo '<tr>';
		echo '<th>Date</th>';
		echo '<th>Summary</th>';
		echo '<th>Rating</th>';
		echo '<th>Action</th>';
		echo '</tr>';
		echo '</thead>';
		echo '<tbody>';
		foreach($recent_submissions as $i => $submission)
		{
			$row_class = ($i % 2 == 0) ? 'alternate' : '';
			echo '<tr class="'.$row_class.'">';
			
			$action_url = get_admin_url() . "post.php?post=$submission->ID&action=edit";
			$action_links = '<p><a href="'.$action_url.'" class="edit_testimonial" id="'.$submission->ID.'" title="Edit Testimonial"><span class="dashicons dashicons-edit"></span>Edit</a></p>';
			$action_links .= '<p><a class="approve_testimonial" id="'.$submission->ID.'" title="Approve Testimonial"><span class="dashicons dashicons-yes"></span>Approve</a></p>';
			$action_links .= '<p><a class="trash_testimonial" id="'.$submission->ID.'" title="Trash Testimonial"><span class="dashicons dashicons-no"></span>Trash</a></p>';
			
			$rating = get_post_meta($submission->ID, '_ikcf_rating', true); 
			$rating = !empty($rating) ? $rating . "/5" : "No Rating";
			
			$friendly_time = date('Y-m-d H:i:s', strtotime($submission->post_date));
			printf ('<td>%s</td>', htmlentities($friendly_time));
			
			printf ('<td>%s</td>', wp_trim_words($submission->post_content, 25));
			printf ('<td>%s</td>', htmlentities($rating));
			printf ('<td class="action_links">%s</td>', $action_links);

			echo '</tr>';				
		}
		echo '</tbody>';
		echo '</table>';
		
		$view_all_testimonials_url= '/wp-admin/edit.php?post_type=testimonial';
		$link_text = 'View All Testimonials';
		printf ('<p class="view_all_testimonials"><a href="%s">%s &raquo;</a></p>', $view_all_testimonials_url, $link_text);
	}
}	

//admin ajax yang for dashboard widget
function easy_t_action_javascript($action) {
    ?>
    <script type="text/javascript" >
    jQuery(document).ready(function($) {
        jQuery('.action_links a').on('click', function() {
            var $this = jQuery(this);
			var	data = {action: 'easy_t_action', my_action: $this.attr('class'), my_postid: $this.attr('id')};
			
			if($this.attr('class') != "edit_testimonial"){//no ajax on edit, take visitor to edit screen instead
				jQuery.post(ajaxurl, data, function(response) {
					if($this.attr('class') == "approve_testimonial"){
						$this.parent().parent().html("<p>Approved!</p>").parent().addClass("updated");
					} else if($this.attr('class') == "trash_testimonial"){
						$this.parent().parent().html("<p>Trashed!</p>").parent().addClass("updated");
					}
				});
				
				return false;
			}
        });
     });
     </script>
     <?php
}

function easy_t_action_callback() {
    $action = $_POST['my_action'];
    $id = $_POST['my_postid'];
	$response = "";
	
    switch($action) {
            case 'approve_testimonial':
                $testimonial = array(
					'ID' => $id,
					'post_status' => 'publish'
				);
				
                $response = wp_update_post($testimonial);//returns 0 if error, otherwise ID of the updated testimonial
	 
				if($response != 0){
					echo $response;
				} else {
					//error, do something
				}
            break;

            case 'trash_testimonial':				
                $response = wp_trash_post($id);//returns false if error
				
				if(!$response){
					//error, do something
				} else {
					echo $id;
				}
            break;
     }
	 
     die();
}
//end admin ajax yang for dashboard widget
	
// End Dashboard Widget Yang

//checks for registered shortcodes and displays alert on settings screen if there are any current conflicts
function easy_testimonials_shortcode_checker(array $atts){
	//TBD
}

//search form shortcode
function easy_t_search_form_shortcode()
{
	add_filter('get_search_form', 'easy_t_restrict_search_to_custom_post_type', 10);
	$search_html = get_search_form();
	remove_filter('get_search_form', 'easy_t_restrict_search_to_custom_post_type');
	return $search_html;
}

function easy_t_restrict_search_to_custom_post_type($search_html)
{
	$post_type = 'testimonial';
	$hidden_input = sprintf('<input type="hidden" name="post_type" value="%s">', $post_type);
	$replace_with = $hidden_input . '</form>';
	return str_replace('</form>', $replace_with, $search_html);
}


//"Construct"

//load any custom shortcodes
$random_testimonial_shortcode = get_option('ezt_random_testimonial_shortcode', 'random_testimonial');
$single_testimonial_shortcode = get_option('ezt_single_testimonial_shortcode', 'single_testimonial');
$testimonials_shortcode = get_option('ezt_testimonials_shortcode', 'testimonials');
$submit_testimonial_shortcode = get_option('ezt_submit_testimonial_shortcode', 'submit_testimonial');
$testimonials_cycle_shortcode = get_option('ezt_cycle_testimonial_shortcode', 'testimonials_cycle');
$testimonials_count_shortcode = get_option('ezt_testimonials_count_shortcode', 'testimonials_count');
$testimonials_grid_shortcode = get_option('ezt_testimonials_grid_shortcode', 'testimonials_grid');

//check for shortcode conflicts
$shortcodes = array();
easy_testimonials_shortcode_checker($shortcodes);

//create shortcodes
add_shortcode($random_testimonial_shortcode, 'outputRandomTestimonial');
add_shortcode($single_testimonial_shortcode, 'outputSingleTestimonial');
add_shortcode($testimonials_shortcode, 'outputTestimonials');
add_shortcode($submit_testimonial_shortcode, 'submitTestimonialForm');
add_shortcode($testimonials_cycle_shortcode , 'outputTestimonialsCycle');
add_shortcode($testimonials_count_shortcode , 'outputTestimonialsCount');
add_shortcode('output_all_themes', 'outputAllThemes');
add_shortcode('easy_t_search_testimonials', 'easy_t_search_form_shortcode');
add_shortcode($testimonials_grid_shortcode, 'easy_t_testimonials_grid_shortcode');

//dashboard widget ajax functionality 
add_action('admin_head', 'easy_t_action_javascript');
add_action('wp_ajax_easy_t_action', 'easy_t_action_callback');

//CSV export
add_action('admin_init', 'process_export');

//add JS
add_action( 'wp_enqueue_scripts', 'easy_testimonials_setup_js', 9999 );
		
// add Google web fonts if needed
add_action( 'wp_enqueue_scripts', 'enqueue_webfonts');

//add CSS
add_action( 'wp_enqueue_scripts', 'easy_testimonials_setup_css' );

//add Custom CSS
add_action( 'wp_head', 'easy_testimonials_setup_custom_css');

//register sidebar widgets
add_action( 'widgets_init', 'easy_testimonials_register_widgets' );

//do stuff
add_action( 'init', 'easy_testimonials_setup_testimonials' );
add_action( 'admin_enqueue_scripts', 'easy_testimonials_admin_init' );
add_action( 'admin_enqueue_scripts', 'easy_testimonials_conflict_check' );
add_action('plugins_loaded', 'easy_t_load_textdomain');

add_filter('manage_testimonial_posts_columns', 'easy_t_column_head', 10);  
add_action('manage_testimonial_posts_custom_column', 'easy_t_columns_content', 10, 2); 

add_filter('manage_edit-easy-testimonial-category_columns', 'easy_t_cat_column_head', 10);  
add_action('manage_easy-testimonial-category_custom_column', 'easy_t_cat_columns_content', 10, 3); 

//add our custom links for Settings and Support to various places on the Plugins page
$plugin = plugin_basename(__FILE__);
add_filter( "plugin_action_links_{$plugin}", 'add_settings_link_to_plugin_action_links' );
add_filter( 'plugin_row_meta', 'add_custom_links_to_plugin_description', 10, 2 );	

//dashboard widget for pro users
if (isValidKey()) {
	add_action( 'wp_dashboard_setup', 'easy_t_add_dashboard_widget');		
}

//flush rewrite rules - only do this once!
register_activation_hook( __FILE__, 'easy_testimonials_rewrite_flush' );

// create an instance of BikeShed that we can use later
if (is_admin()) {
	global $EasyT_BikeShed;
	$EasyT_BikeShed = new Easy_Testimonials_GoldPlugins_BikeShed();
}
