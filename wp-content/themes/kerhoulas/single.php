<?php

/**

 * The template for displaying all single posts and attachments

 *

 * @package WordPress

 * @subpackage Twenty_Fifteen

 * @since Twenty Fifteen 1.0

 */



get_header(); ?>

	<section class="innerbanner">

				<?php if ( has_post_thumbnail() ) {
	the_post_thumbnail();
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

	</section>

	</div><!-- .content-area -->

</div>

<?php get_footer(); ?>

