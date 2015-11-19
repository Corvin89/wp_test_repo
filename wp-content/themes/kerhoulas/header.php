<?php
/**
 * The template for displaying the header
 *
 * Displays all of the head element and everything up until the "site-content" div.
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<link href="<?php bloginfo('template_directory');?>/stylesheet/font-awesome.css" rel="stylesheet" type="text/css" media="screen" />
    <script type="text/javascript" src="<?php bloginfo('template_directory');?>/javascript/jquery.min.js"></script
	><!--[if lt IE 9]>
	<script src="<?php echo esc_url( get_template_directory_uri() ); ?>/js/html5.js"></script>
	<![endif]-->
<script>
$(document).ready(function(){
 $(".approachdiv li").wrapInner("<span></span>");
});
</script>
<script type="text/javascript">
$(function() {
var resmnu = $('.resmnu');
menu = $('nav ul#menu-header-menu');
menuHeight	= menu.height();

$(resmnu).on('click', function(e) {
e.preventDefault();
menu.stop().slideToggle(600);
});

$(window).resize(function(){
       	var w = $(window).width();
       	if(w > 479 && menu.is(':hidden')) {
       	menu.removeAttr('style');
       	}
   	});

$(".resmnu").click(function(){
$(this).toggleClass('close');
});

});
</script>
<script>
$(document).ready(function(){
 $(".myget").wrapInner("<div class="multibo"> </div>");
});
</script>
<style>
.rightcont .myget{ background-color:<?php echo get_option('HEX8');?>;}
.logo .textwidget{color: <?php echo get_option('HEX22');?>}
a{color: <?php echo get_option('HEX1');?>}
.resmnu{  color: <?php echo get_option('HEX2');?>}
.topnav-2{ background: <?php echo get_option('HEX1');?>}
body{background: #e1d5bd; color: <?php echo get_option('HEX1');?>; }
.headercntr{ background: <?php echo get_option('HEX2');?>;}
.topnav-2 li a{ color:<?php echo get_option('HEX2');?>}
nav{ background: <?php echo get_option('HEX3');?>;}
nav .topnav li{border-right: 1px solid <?php echo get_option('HEX4');?>;}
nav .topnav li:hover ul{ background: <?php echo get_option('HEX3');?>}
.easy_testimonial{border-top: 10px solid <?php echo get_option('HEX23');?>;}
nav .topnav li ul li{border-bottom: 1px solid <?php echo get_option('HEX5');?>;}
nav .topnav li a{ color: <?php echo get_option('HEX4');?>;}
nav .topnav li a:hover{ color: <?php echo get_option('HEX6');?>}
.callsarae{color: <?php echo get_option('HEX2');?>}
.callsarae h4{color:<?php echo get_option('HEX22');?>;}
.callsarae b{background: <?php echo get_option('HEX24');?>;}
.callsarae em{ color: <?php echo get_option('HEX1');?>;}
.approachdiv h2{ color: <?php echo get_option('HEX3');?>;}
.approachdiv li{ background: <?php echo get_option('HEX8');?>;  color: <?php echo get_option('HEX1');?>;}
.approachdiv li:after{color: <?php echo get_option('HEX2');?>; background: <?php echo get_option('HEX9');?>; }
.middlehome{ background: <?php echo get_option('HEX10');?>;}
.easy_testimonial:after{border-top: 10px solid <?php echo get_option('HEX23');?>;} 
.arealeft h2{ color: <?php echo get_option('HEX2');?>;}
.arealeft h3{ color: <?php echo get_option('HEX2');?>;}
.arealeft h4{ color: <?php echo get_option('HEX2');?>;}
.arealeft h5{ color: <?php echo get_option('HEX2');?>;}
.arealeft ul li:after{ color: <?php echo get_option('HEX1');?>;}
.myget{ border: 2px solid <?php echo get_option('HEX2');?>;}
.rightcont h2{background: <?php echo get_option('HEX12');?>; color: <?php echo get_option('HEX2');?>;}
.rightcont h2:after{border-bottom: 10px solid <?php echo get_option('HEX12');?>;}
.rightcont li input[type="submit"]{background: <?php echo get_option('HEX12');?>; color: <?php echo get_option('HEX2');?>; }
#footer{ background: <?php echo get_option('HEX1');?>;}
#footer h3{border-bottom: 1px solid <?php echo get_option('HEX13');?>; color:<?php echo get_option('HEX2');?>;}
.bootomfoot{ background: <?php echo get_option('HEX21');?>; color:<?php echo get_option('HEX2');?>;}
.bootomfoot p{ color: <?php echo get_option('HEX17');?>}
#footer ul li a{ color: <?php echo get_option('HEX15');?>;}
#footer ul li a:hover{ color: <?php echo get_option('HEX2');?>;}
#footer ol li{  color:<?php echo get_option('HEX15');?>;}
#footer ol li a{ color:<?php echo get_option('HEX15');?>;}
#footer ol li a:hover{ color: <?php echo get_option('HEX2');?>;}
.innerbanner h1 span { color: <?php echo get_option('HEX2');?>;}
.loopteam{ background: <?php echo get_option('HEX9');?>; }
.loopcover h2{ border-bottom: 1px solid <?php echo get_option('HEX16');?>;}
.blogarea{background: <?php echo get_option('HEX9');?>; border: 1px solid <?php echo get_option('HEX2');?>;}
.blogtop .date {  background: <?php echo get_option('HEX2');?>;}
.blogtop h3 a{color: <?php echo get_option('HEX3');?>;}
.blogtop h3 a:hover{ color: <?php echo get_option('HEX1');?>}
.googlemap{border: 2px solid <?php echo get_option('HEX2');?>;}
.innerbanner{ background: <?php echo get_option('HEX2');?>;}

.rightcont h2{color: #<?php echo get_option('HEX2');?>;}
.arealeft h2{color:<?php echo get_option('HEX3');?>;}
.arealeft h3{color:<?php echo get_option('HEX3');?>;}
.arealeft h4{color:<?php echo get_option('HEX3');?>;}
.arealeft h5{color:<?php echo get_option('HEX3');?>;}
.contin ol li label {  background: <?php echo get_option('HEX18');?>; }
.contin ol li .fa { color: <?php echo get_option('HEX2');?>;}
#hour label{ background:<?php echo get_option('HEX18');?>;}
#hour li .fa{ color: <?php echo get_option('HEX2');?>;}
.read{ color: <?php echo get_option('HEX18');?>}
.categoryin{border-bottom: 2px solid <?php echo get_option('HEX2');?>;}
.categoryin ul li{ border-bottom: 1px dashed <?php echo get_option('HEX19');?>}
.archivein ul li{ border-bottom: 1px dashed <?php echo get_option('HEX19');?>}
.midform .search-form input[type="search"]{border: 1px solid <?php echo get_option('HEX16');?>;}
.midform .search-form input[type="submit"]{background: <?php echo get_option('HEX1');?>;color: <?php echo get_option('HEX2');?>;} 
.gpl label .fa{ color: <?php echo get_option('HEX2');?>;}
#requestan h2{ color: <?php echo get_option('HEX1');?>; border-bottom: 2px solid <?php echo get_option('HEX1');?>;}
#requestan ul li input[type="submit"]{ background: <?php echo get_option('HEX22');?>; color: <?php echo get_option('HEX2');?>;}
.innerbanner h1 span:after {  background: <?php echo get_option('HEX1');?>}
#requestan {  background: <?php echo get_option('HEX8');?>}
.gpl label{  background: <?php echo get_option('HEX22');?>}
.easy_testimonial{  background: <?php echo get_option('HEX2');?>}
.testimonial-client:after{  background: <?php echo get_option('HEX1');?>}
#requestan ul li input[type="text"]{ border: 1px solid <?php echo get_option('HEX10');?>; }
#requestan ul li input[type="email"]{ border: 1px solid <?php echo get_option('HEX10');?>; }
#requestan ul li input[type="tel"]{ border: 1px solid <?php echo get_option('HEX10');?>; }
#requestan ul li textarea{ border: 1px solid <?php echo get_option('HEX10');?>; }

@media (max-width:1000px){
.callsarae a{ background: <?php echo get_option('HEX24');?>;  border-radius: 16px;  color: <?php echo get_option('HEX2');?>;}
}

</style>
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<section class="maincntr">
	<div class="wrapper">
	
<header class="headercntr">
			<span class="logo"><a href="<?php bloginfo('url');?>"><?php dynamic_sidebar('Logo');?></a></span>
			<aseide class="callsarae">
				<h4>Call Now</h4>
				<span>
				<a href="tel:<?php if ( function_exists( 'ot_get_option' ) ) {
  echo $test_input = ot_get_option( 'phonenumber' );
}?>"><?php if ( function_exists( 'ot_get_option' ) ) {
  echo $test_input = ot_get_option( 'phonenumber' );
}?></a>
				<b><i class="fa fa-phone"></i></b>  <em><?php if ( function_exists( 'ot_get_option' ) ) {
  echo $test_input = ot_get_option( 'phonenumber' );
}?></em></span>
			</aseide>
			
			<div class="approachdiv">
<h2><strong><?php dynamic_sidebar('Approach heading');?><span style="color: #ff0000;"><?php echo do_shortcode('[urlparam param="city" default="*CITYMETA*" /]');?></span> Area?</h2></strong>			
<?php dynamic_sidebar('Approach');?>
			</div>
			
			<nav class="navbar">
			<span class="resmnu">Nav</span>
				<?php wp_nav_menu(array('theme_location' => 'primary' , 'menu_class' => 'topnav-2')); ?>
				<?php wp_nav_menu(array('theme_location' => 'primary' , 'menu_class' => 'topnav')); ?>
				
			</nav>
		</header>