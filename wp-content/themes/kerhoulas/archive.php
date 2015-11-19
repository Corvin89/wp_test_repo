<?php
/**
 * The template for displaying archive pages
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * If you'd like to further customize these archive views, you may create a
 * new template file for each one. For example, tag.php (Tag archives),
 * category.php (Category archives), author.php (Author archives), etc.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
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



				<div class="blogleft">

		<?php query_posts("posts_per_page=-1&paged=$paged&orderby=slug&order=DESC");?>	

		  <?php if ( have_posts() ) : ?>

          <?php /* Start the Loop */ ?>

		  <?php while ( have_posts() ) : the_post(); ?>

		  

		  <?php $date= get_the_date();

		        $dat=explode(" ", $date);

		  ?>

		

		  <div class="blogarea">

						

						<div class="blogtop">

						<div class="date"><em><?php echo $dat[0];?></em><span><?php echo $dat[1]; ?></span></div>

						

						<h3><a href="<?php the_permalink();?>"><?php the_title();?></a></h3>

						

						<ul> 

						   

						<li class="adminb"><img src="<?php bloginfo('template_directory');?>/images/admin.png" alt=""><?php the_author_posts_link(); ?></li>

						<li class="adminb2"><img src="<?php bloginfo('template_directory');?>/images/cat.png" alt=""><?php the_category(', '); ?></li>

						<li class="adminb3"><img src="<?php bloginfo('template_directory');?>/images/comments.png" alt=""><?php comments_popup_link('Comment 0', 'Comment 1', '% Comments'); ?></li>

							

						</ul>

						

						</div>

						<?php if ( has_post_thumbnail() ) { ?>

						<label class="blogimg"><?php the_post_thumbnail(array(718,500))?></label>

						<div class="rightblog">

						 <p style="text-align:justify;"><?php echo substr(get_the_content(),0,500);?>...</p>

                         <a href="<?php the_permalink();?>" class="read">Read More [+]</a>

						</div>

						<?php } else { ?>

						

						 <p style="text-align:justify;"><?php echo substr(get_the_content(),0,500);?>...</p>

                         <a href="<?php the_permalink();?>" class="read">Read More [+]</a>

						<?php } ?>

						

						<div class="blogshare"><img src="<?php bloginfo('template_directory');?>/images/like.png" alt=""></div>

						

					</div>

		  

		  <?php endwhile; endif;?>

					

					

				</div>

				

		

		</aside>

	<aside class="arearight"><?php get_sidebar('blog');?></aside>

		</section>

		</div>




<?php get_footer(); ?>
