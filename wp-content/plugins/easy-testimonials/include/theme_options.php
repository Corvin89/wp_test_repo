		<?php //load options ? ?>
		<?php include("lib/config.php"); ?>
		<?php if(!isValidKey()): ?><p class="plugin_is_not_registered">✘ These themes require Easy Testimonials Pro. <a href="https://goldplugins.com/our-plugins/easy-testimonials-details/upgrade-to-easy-testimonials-pro/?utm_campaign=upgrade_themes&utm_source=plugin&utm_banner=upgrade2" target="_blank" class="button">Upgrade Now</a></p><?php endif; ?>		
		<table class="form-table easy_t_options">
			<tr valign="top">
				<td>
					<h4>Card Theme</h4>
					<p class="ezt_theme_description">This responsive theme is designed to look best with Testimonial Image Size set to 150x150, Use Mystery Man enabled, Publication Date being shown, and Ratings displayed as Stars.  For example, <code>[random_testimonial show_thumbs='1' show_rating='stars' show_date='1']</code>.</p>
					<!-- card style with avatar on the left -->
					<?php foreach($pro_theme_array['card_style'] as $slug => $name): ?>
					<p class="easy-t-radio-button"><input type="radio" name="testimonials_style" id="<?php echo $slug; ?>" <?php if(!isValidKey()): ?>disabled=DISABLED <?php endif; ?>	value="<?php echo $slug; ?>" <?php if(get_option('testimonials_style') == $slug): echo 'checked="CHECKED"'; endif; ?>><label for="<?php echo $slug; ?>"><?php echo $name; ?><?php if(!isValidKey()): ?><br/><em>Requires PRO - Upgrade to Enable!</em><?php endif; ?><br/><img src="<?php echo plugins_url('img/easy-t-'.str_replace('_','-',str_replace('_style','',$slug)).'.png', __FILE__); ?>"/></label></p>
					<?php endforeach; ?>
					<h4>Elegant Theme</h4>
					<p class="ezt_theme_description">This responsive theme is designed to look best with Testimonial Image Size set to 150x150, Use Mystery Man enabled, Publication Date being shown, and Ratings displayed as Stars.  For example, <code>[random_testimonial show_thumbs='1' show_rating='stars' show_date='1']</code>.</p>
					<?php foreach($pro_theme_array['elegant_style'] as $slug => $name): ?>
					<!-- elegant style with avatar in the center -->
					<p class="easy-t-radio-button"><input type="radio" name="testimonials_style" id="<?php echo $slug; ?>" <?php if(!isValidKey()): ?>disabled=DISABLED <?php endif; ?>	value="<?php echo $slug; ?>" <?php if(get_option('testimonials_style') == $slug): echo 'checked="CHECKED"'; endif; ?>><label for="<?php echo $slug; ?>"><?php echo $name; ?><?php if(!isValidKey()): ?><br/><em>Requires PRO - Upgrade to Enable!</em><?php endif; ?><br/><img src="<?php echo plugins_url('img/easy-t-'.str_replace('_','-',str_replace('_style','',$slug)).'.png', __FILE__); ?>"/></label></p>
					<?php endforeach; ?>
					<h4>Notepad Theme</h4>
					<p class="ezt_theme_description">This responsive theme is designed to look best with Testimonial Image Size set to 150x150, Use Mystery Man enabled, Publication Date being shown, and Ratings displayed after the Testimonial.  For example, <code>[random_testimonial show_thumbs='1' show_rating='after' show_date='1']</code>.</p>
					<?php foreach($pro_theme_array['notepad_style'] as $slug => $name): ?>
					<!-- notepad style with avatar on the left, partially rotated -->
					<p class="easy-t-radio-button"><input type="radio" name="testimonials_style" id="<?php echo $slug; ?>" <?php if(!isValidKey()): ?>disabled=DISABLED <?php endif; ?>	value="<?php echo $slug; ?>" <?php if(get_option('testimonials_style') == $slug): echo 'checked="CHECKED"'; endif; ?>><label for="<?php echo $slug; ?>"><?php echo $name; ?><?php if(!isValidKey()): ?><br/><em>Requires PRO - Upgrade to Enable!</em><?php endif; ?><br/><img src="<?php echo plugins_url('img/easy-t-'.str_replace('_','-',str_replace('_style','',$slug)).'.png', __FILE__); ?>"/></label></p>
					<?php endforeach; ?>
					<h4>Business Theme</h4>
					<p class="ezt_theme_description">This responsive theme is designed to look best with Testimonial Image Size set to 150x150, Use Mystery Man enabled, Publication Date being shown, and Ratings displayed as Stars.  For example, <code>[random_testimonial show_thumbs='1' show_rating='stars' show_date='1']</code>.</p>
					<?php foreach($pro_theme_array['business_style'] as $slug => $name): ?>
					<!-- business style with avatar on the left -->
					<p class="easy-t-radio-button"><input type="radio" name="testimonials_style" id="<?php echo $slug; ?>" <?php if(!isValidKey()): ?>disabled=DISABLED <?php endif; ?>	value="<?php echo $slug; ?>" <?php if(get_option('testimonials_style') == $slug): echo 'checked="CHECKED"'; endif; ?>><label for="<?php echo $slug; ?>"><?php echo $name; ?><?php if(!isValidKey()): ?><br/><em>Requires PRO - Upgrade to Enable!</em><?php endif; ?><br/><img src="<?php echo plugins_url('img/easy-t-'.str_replace('_','-',str_replace('_style','',$slug)).'.png', __FILE__); ?>"/></label></p>
					<?php endforeach; ?>
					<h4>Modern Theme</h4>
					<p class="ezt_theme_description">This theme is designed to look best with Testimonial Image Size set to 150x150, Use Mystery Man enabled, Publication Date being shown, and Ratings displayed as Stars.  For example, <code>[random_testimonial show_thumbs='1' show_rating='stars' show_date='1']</code>.</p>
					<?php foreach($pro_theme_array['modern_style'] as $slug => $name): ?>
					<!-- modern style with avatar at the bottom, centered -->
					<p class="easy-t-radio-button"><input type="radio" name="testimonials_style" id="<?php echo $slug; ?>" <?php if(!isValidKey()): ?>disabled=DISABLED <?php endif; ?>	value="<?php echo $slug; ?>" <?php if(get_option('testimonials_style') == $slug): echo 'checked="CHECKED"'; endif; ?>><label for="<?php echo $slug; ?>"><?php echo $name; ?><?php if(!isValidKey()): ?><br/><em>Requires PRO - Upgrade to Enable!</em><?php endif; ?><br/><img src="<?php echo plugins_url('img/easy-t-'.str_replace('_','-',str_replace('_style','',$slug)).'.png', __FILE__); ?>"/></label></p>
					<?php endforeach; ?>
					<h4>Bubble Theme</h4>
					<?php foreach($pro_theme_array['bubble_style'] as $slug => $name): ?>
					<!-- bubble style -->
					<p class="easy-t-radio-button"><input type="radio" name="testimonials_style" id="<?php echo $slug; ?>" <?php if(!isValidKey()): ?>disabled=DISABLED <?php endif; ?>	value="<?php echo $slug; ?>" <?php if(get_option('testimonials_style') == $slug): echo 'checked="CHECKED"'; endif; ?>><label for="<?php echo $slug; ?>"><?php echo $name; ?><?php if(!isValidKey()): ?><br/><em>Requires PRO - Upgrade to Enable!</em><?php endif; ?><br/><img src="<?php echo plugins_url('img/easy-t-'.str_replace('_','-',str_replace('_style','',$slug)).'.png', __FILE__); ?>"/></label></p>
					<?php endforeach; ?>
					<h4>Left Avatar - 150x150</h4>
					<?php foreach($pro_theme_array['avatar-left-style'] as $slug => $name): ?>
					<!-- left avatar, 150x150 -->
					<p class="easy-t-radio-button"><input type="radio" name="testimonials_style" id="<?php echo $slug; ?>" <?php if(!isValidKey()): ?>disabled=DISABLED <?php endif; ?>	value="<?php echo $slug; ?>" <?php if(get_option('testimonials_style') == $slug): echo 'checked="CHECKED"'; endif; ?>><label for="<?php echo $slug; ?>"><?php echo $name; ?><?php if(!isValidKey()): ?><br/><em>Requires PRO - Upgrade to Enable!</em><?php endif; ?><br/><img src="<?php echo plugins_url('img/easy-t-'.str_replace('_','-',str_replace('_style','',$slug)).'.png', __FILE__); ?>"/></label></p>
					<?php endforeach; ?>
					<h4>Left Avatar - 50x50</h4>
					<?php foreach($pro_theme_array['avatar-left-style-50x50'] as $slug => $name): ?>
					<!-- left avatar, 50x50 -->                      
					<p class="easy-t-radio-button"><input type="radio" name="testimonials_style" id="<?php echo $slug; ?>" <?php if(!isValidKey()): ?>disabled=DISABLED <?php endif; ?>	value="<?php echo $slug; ?>" <?php if(get_option('testimonials_style') == $slug): echo 'checked="CHECKED"'; endif; ?>><label for="<?php echo $slug; ?>"><?php echo $name; ?><?php if(!isValidKey()): ?><br/><em>Requires PRO - Upgrade to Enable!</em><?php endif; ?><br/><img src="<?php echo plugins_url('img/easy-t-'.str_replace('_','-',str_replace('_style','',$slug)).'.png', __FILE__); ?>"/></label></p>
					<?php endforeach; ?>
					<h4>Right Avatar - 150x150</h4>
					<?php foreach($pro_theme_array['avatar-right-style'] as $slug => $name): ?>
					<!-- right avatar, 150x150 -->                   
					<p class="easy-t-radio-button"><input type="radio" name="testimonials_style" id="<?php echo $slug; ?>" <?php if(!isValidKey()): ?>disabled=DISABLED <?php endif; ?>	value="<?php echo $slug; ?>" <?php if(get_option('testimonials_style') == $slug): echo 'checked="CHECKED"'; endif; ?>><label for="<?php echo $slug; ?>"><?php echo $name; ?><?php if(!isValidKey()): ?><br/><em>Requires PRO - Upgrade to Enable!</em><?php endif; ?><br/><img src="<?php echo plugins_url('img/easy-t-'.str_replace('_','-',str_replace('_style','',$slug)).'.png', __FILE__); ?>"/></label></p>
					<?php endforeach; ?>
					<h4>Right Avatar - 50x50</h4>
					<?php foreach($pro_theme_array['avatar-right-style-50x50'] as $slug => $name): ?>
					<!-- right avatar, 50x50 -->                     
					<p class="easy-t-radio-button"><input type="radio" name="testimonials_style" id="<?php echo $slug; ?>" <?php if(!isValidKey()): ?>disabled=DISABLED <?php endif; ?>	value="<?php echo $slug; ?>" <?php if(get_option('testimonials_style') == $slug): echo 'checked="CHECKED"'; endif; ?>><label for="<?php echo $slug; ?>"><?php echo $name; ?><?php if(!isValidKey()): ?><br/><em>Requires PRO - Upgrade to Enable!</em><?php endif; ?><br/><img src="<?php echo plugins_url('img/easy-t-'.str_replace('_','-',str_replace('_style','',$slug)).'.png', __FILE__); ?>"/></label></p>
					<?php endforeach; ?>
					<div style="clear:both;"></div>
				</td>
			</tr>
		</table>