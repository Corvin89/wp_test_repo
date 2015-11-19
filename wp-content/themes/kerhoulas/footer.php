<?php

/**

 * The template for displaying the footer

 *

 * Contains the closing of the "site-content" div and all content after.

 *

 * @package WordPress

 * @subpackage Twenty_Fifteen

 * @since Twenty Fifteen 1.0

 */

?>

	<!--/footer Start\-->

    <section id="footer">

	<div class="wrapper">

		<div class="navigation">

			<h3>Navigation</h3>

			<?php wp_nav_menu(array('theme_location' => 'foternav' , 'menu_class' => 'botnav')); ?>

		</div>

		<div class="servicesbot">

			<h3>Services</h3>

			<?php wp_nav_menu(array('theme_location' => 'services' , 'menu_class' => 'botnav')); ?>

		</div>

		<div class="weaccept">

			<h3>We Accept</h3>

			<?php if ( dynamic_sidebar('cash') ) :

				  endif; ?>

		</div>

			<div class="contactfoot">

				<h3>Contact Us</h3>

				<ol>

					<li><?php if ( dynamic_sidebar('address') ) :

				  endif; ?> </li>

					<li><?php if ( dynamic_sidebar('phomenumber') ) :

				  endif; ?></li>

					<li><?php if ( dynamic_sidebar('emails') ) :

				  endif; ?></li>

				</ol>

			</div>

		</div>

		<div class="bootomfoot">

			 <div class="pdo"> <?php if ( dynamic_sidebar('Copyright') ) :

				  endif; ?></div>

			</div>

</section>

</section>

<!--\footer End/-->

<?php wp_footer(); ?>



</body>

</html>

