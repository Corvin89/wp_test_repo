<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */

get_header(); ?>

	<section class="innerbanner">

				<img src="<?php bloginfo('template_directory');?>/images/inner.jpg" alt="" height="344" width="1419" />

				<h1><span><b><?php the_title();?></b></span></h1>

		</section>
		
		<section class="middlehome">    
					<div class="wrapper">
					<div class="midform"><h1 class="page-title"><?php _e( 'Oops! That page can&rsquo;t be found.', 'twentyfifteen' ); ?></h1>

					<p><?php _e( 'It looks like nothing was found at this location. Maybe try a search?', 'twentyfifteen' ); ?></p>

					<?php get_search_form(); ?></div>
					</div>
	</section>
</div>
<?php get_footer(); ?>
