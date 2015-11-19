<?php

/**

 * Template Name: Contact

 *

 */



get_header(); ?>


<?php get_header();?>

	<section class="innerbanner" id="contactm">
<div class="continner">
				<h1><span><b><?php the_title();?></b></span></h1>
</div>
		</section>

<section class="middlehome">

				<aside class="arealeft">

<?php if(have_posts()) :

  while(have_posts()) : the_post();?>

  <?php the_content();?>



<div class="contin" style="display: none;">
 <div class="leftconty">
<h2>Contact Information</h2>

<ol>

<li><label><i class="fa fa-home"></i></label><?php if ( dynamic_sidebar('address') ) :

				  endif; ?></li>

<li><label><i class="fa fa-phone-square"></i></label><?php if ( dynamic_sidebar('phomenumber') ) :
				  endif; ?></li>

<li><label><i class="fa fa-google-plus"></i></label><a href="#">Google+ Page</a></li>

</ol>
</div>

 <div class="rightconty">
<div class="alliin">

<h2>Hours of Operations</h2>

<?php if ( dynamic_sidebar('Hours of Operations') ) :
				  endif; ?>

</div>
</div>
</div>

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