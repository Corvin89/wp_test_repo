<?php

/**

 * The template for displaying pages

 *

 * This is the template that displays all pages by default.

 * Please note that this is the WordPress construct of pages and that

 * other "pages" on your WordPress site will use a different template.

 *

 * @package WordPress

 * @subpackage Twenty_Fifteen

 * @since Twenty Fifteen 1.0

 */



get_header(); ?>

	<section class="innerbanner">

				<?php if ( has_post_thumbnail() ) {
	the_post_thumbnail(full);
}
else {
	echo '<img src="' . get_bloginfo( 'stylesheet_directory' ) . '/images/inner.jpg" height="344" width="1449" />';
}?>

				<h1><span><b><?php the_title();?></b></span></h1>

		</section>

		<section class="middlehome">

				<aside class="arealeft">

<?php if(have_posts()) :

  while(have_posts()) : the_post();?>

  <?php the_content();?> 

  <?php endwhile;

	endif;?>

	</aside>

	<aside class="arearight"><?php get_sidebar();?></aside>

<div id="requestan">
 <div class="mlefty">
<h2>Hours</h2>
<?php if ( dynamic_sidebar('Hours of Operations') ) :
				  endif; ?>

<div class="gpl"><label><i class="fa fa-google-plus"></i></label><a target="_blank" href="<?php if ( function_exists( 'ot_get_option' ) ) {
  echo $test_input = ot_get_option( 'googleplus' );
}?>">Google+ Page</a></div>
</div>
<div class="mrighty"><?php echo do_shortcode('[contact-form-7 id="195" title="Request an Appointment"]') ?></div>
</div>
	</section>

	</div>

	

<?php get_footer(); ?>

