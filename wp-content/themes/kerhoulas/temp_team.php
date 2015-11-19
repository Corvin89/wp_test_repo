<?php
/**
 * Template Name: Our Team
 *
 */

get_header(); ?>

<?php get_header();?>

<section class="middlehome">
				<aside class="arealeft">
<?php if(have_posts()) :
  while(have_posts()) : the_post();?>
  <?php the_content();?>
  
  <?php endwhile;
	endif;?>
	
	<div class="loopcover"><?php query_posts(array( 'post_type' => 'Ourteam', 'posts_per_page' => -1, 'order' => 'DESC' )); 
	 while(have_posts()) : the_post();?>
	 <div class="loopteam">
	 <div class="loopteamimg"><label><?php the_post_thumbnail();?></label></div>
	 <h2><?php the_title();?></h2>
	 <h4><?php echo $key = get_post_meta($post->ID, 'Designation', true); ?></h4>
	<div class="detob"> <?php the_excerpt();?></div>
	 </div>	 	
<?php endwhile;
	 wp_reset_postdata(); ?></div>
	</aside>
	<aside class="arearight"><?php get_sidebar();?></aside>
	</section>
	</div>

<?php get_footer();?>