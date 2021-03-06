<!DOCTYPE html>
<head>

    <!--[if lt IE 7 ]> <html class="ie6" <?php language_attributes(); ?>> <![endif]-->
    <!--[if IE 7 ]>    <html class="ie7" <?php language_attributes(); ?>> <![endif]-->
    <!--[if IE 8 ]>    <html class="ie8" <?php language_attributes(); ?>> <![endif]-->
    <!--[if lt IE 9]>
    <script src="<?php echo get_template_directory_uri(); ?>/js/html5shiv/html5shiv.js"></script>
    <![endif]-->
    <!--[if IE 9 ]><html class="ie9" <?php language_attributes(); ?>> <![endif]-->
    <!--[if (gt IE 9)|!(IE)]><!--> <html <?php language_attributes(); ?>> <!--<![endif]-->

    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <title><?php wp_title("",true); ?></title>

    <!-- set width to the device viewing the site -->
    <meta name="viewport" content="width=device-width" />

    <?php wp_head(); ?>

</head>


<body id="<?php print get_stylesheet(); ?>" <?php body_class('ct-body'); ?>>

<div class="overflow-container">
    <a class="skip-content" href="#main">Наверх</a>
<header id="site-header" class="site-header" role="banner">

	<div class="title-info">
		<?php get_template_part('logo')  ?>    
	</div>
	
	<?php get_template_part( 'menu', 'primary' ); // adds the primary menu ?>

</header>
<div id="main" class="main" role="main">