<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes() ?>>
<head>
	<title>
		<?php wp_title('-',true,'right'); ?>
		<?php bloginfo('name'); ?>
	</title>
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type') ?>; charset=<?php bloginfo('charset') ?>"/>
	<!-- 
	SGQRI 008-01 Spécification 21 b)
	pour la page d’accueil du site, le plan de site et toute page d’accueil 
	des trois premiers niveaux de navigation d’un site Web, être accompagnée 
	au minimum de l’élément de métadonnée résumé décrit selon la syntaxe 
	de la norme ISO 15836 Information et documentation – L'ensemble des 
	éléments de métadonnées Dublin Core (2003).
	-->
	<meta name="dc.description" content="<?php bloginfo('description'); ?>" />
	<meta name="dc.title" content="<?php the_title(); ?>" />
	<meta name="dc.creator" content="Rocío Alvarado" />
	<meta name="dc.publisher" content="AccessibilitéWeb" />
	<meta name="dc.format" content="<?php bloginfo('html_type') ?>" />
	<meta name="generator" content="WordPress <?php bloginfo('version'); ?>" />
	
	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<link rel="shortcut icon" href="/favicon.ico" />
	<link rel="pingback" href="<?php bloginfo('pingback_url') ?>" />

	<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo('stylesheet_url'); ?>" />
	<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo('template_directory'); ?>/css/less.css" />
	<link rel="stylesheet" type="text/css" media="print" href="<?php bloginfo('template_directory'); ?>/css/print.css" />
	<?php if ( is_singular() && get_option( 'thread_comments' ) ) wp_enqueue_script( 'comment-reply' ); ?>
	<?php wp_head() ?>
</head>

<body <?php body_class(); ?>>
<div id="container">
	
	<div id="skip" class="screen_reader">
		<a href="#content" title="<?php _e('Go to content', 'webaccess'); ?>"><?php _e('Skip to content', 'webaccess'); ?></a>
	</div>
	
	<div id="header">
		<div id="sitename">
			<!-- The h1 element is used for both site title and page site.  -->
			<h1>
				<a href="<?php echo get_settings('home'); ?>">
					<img src="<?php bloginfo('template_url'); ?>/images/logo.png" alt="<?php bloginfo('name');?> logo" />
				</a>
			</h1>
		</div>
		<?php if ( get_bloginfo( 'description' ) ) :  ?>
			<p id="sitedescription"><?php bloginfo( 'description' ); ?></p>
		<?php endif; ?>
		<?php /* if ( is_front_page() && current_theme_supports( 'post-thumbnails' ) && has_post_thumbnail( $post->ID ) ) :
			echo get_the_post_thumbnail( $post->ID );
		endif; */ ?>
		<div id="search">
			<?php get_search_form(); ?>
		</div>
	</div>
