<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * e.g., it puts together the home page when no home.php file exists.
 *
 * Learn more: {@link https://codex.wordpress.org/Template_Hierarchy}
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */

get_header(); ?>

	<section class="banner">
				<?php echo do_shortcode('[cycloneslider id="homeslider"]');?>
		</section>
		
		<section class="middlehome">
				<aside class="arealeft">
				
				<?php $recent = new WP_Query("page_id=4"); while($recent->have_posts()) : $recent->the_post();?>
				<h2><?php the_title();?></h2>
        		<?php the_content(); ?>
				<?php endwhile; ?>
					
				</aside>
				
				<aside class="arearight">
					<div class="rightcont">
						<?php echo do_shortcode('[contact-form-7 id="51" title="Contact form 1"]');?>
					</div>
				</aside>
			</div>
		</section>	
	
<?php get_footer(); ?>
