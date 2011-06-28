<?php get_header(); ?>
<!-- archive.php -->
<div id="middle">
	<div id="content">
		<div id="posts">
		<?php if (have_posts()) : ?>
	
		<!-- The h1 element is used for both site title and page site.  -->
		<?php if (is_category()) { ?>				
		<h1 class="archive"><?php _e('Post(s) categorised by', 'webaccess'); ?> <em><?php echo single_cat_title(); ?></em></h1>
		
 	  	<?php } elseif (is_day()) { ?><!-- for qtranslate use the_time('%d %B %Y');  -->
		<h1 class="archive"><?php _e('Archive(s) for the date', 'webaccess'); ?> <em><?php the_time('j F Y'); ?></em></h1>
		
	 	<?php } elseif (is_month()) { ?><!-- for qtranslate use the_time('%B %Y'); -->
		<h1 class="archive"><?php _e('Archive(s) for the month of', 'webaccess'); ?> <em><?php the_time('F Y'); ?></em></h1>

		<?php } elseif (is_year()) { ?><!-- for qtranslate use the_time('%Y'); -->
		<h1 class="archive"><?php _e('Archive(s) for the year', 'webaccess'); ?> <em><?php the_time('Y'); ?></em></h1>
		
		<?php } elseif (is_tag()) { ?>
		<h1 class="archive"><?php _e('Post(s) archived with the tag', 'webaccess'); ?> <em><?php single_tag_title(); ?></em></h1>

		<?php /* If this is a paged archive */ } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
		<h1 class="archive"><?php _e('Blog Archives', 'webaccess'); ?></h1>
		<?php } ?>
		
		<?php while (have_posts()) : the_post(); ?>
			<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
				<div class="meta">
					<span class="date"><?php the_date(); ?></span> <span class="author"><?php _e('by', 'webaccess'); ?> <?php the_author() ?> </span>
					<span class="date"><?php the_date(); ?></span>
					
					<?php if ( comments_open() ) : ?>
					<span class="comments">
						<?php 
							/* Makes the a href unique and represent the current comment 
							 * 
							 * Le a href est unique et représente le commentaire actuel
							 * 
							 * SGQRI 008-01 Spécification 18 d)
							 * pour un hyperlien constituant la seule façon d’accéder à 
							 * une destination à partir de cette page, libeller l’hyperlien 
							 * pour que sa destination puisse être déterminée hors de son 
							 * contexte immédiat;
							 */
							$title = '<em class="screen_reader"> '.get_the_title().'</em>';
							comments_popup_link(__('No comments for the post', 'webaccess').$title, __('1 comment for the post', 'webaccess').$title, __('% comments for the post', 'webaccess').$title);
						?>
					</span>
					<?php endif; //end comments open ?>
					
					<?php if (is_user_logged_in()) : ?>
					<span class="edit-link"><a href="<?php echo get_edit_post_link() ?>"><?php _e('Edit the post', 'webaccess'); ?> <em class="screen_reader"><?php the_title(); ?></em></a></span>
					<?php endif; //end user_logged ?>	
				</div>
				
				<div class="post_excerpt">
					 <?php the_excerpt(); ?>
				</div>
			
			</div><!-- end post-ID --> 
		
		<?php endwhile; ?>
	
	<?php else : ?>
		
		<?php get_search_form(); ?>
	
	<?php endif; ?>	
		
		</div><!-- end posts --> 
		
		<?php if (function_exists("emm_paginate")) {
			emm_paginate("title=");
		} ?>
		
	</div><!-- end content --> 
</div><!-- end middle -->

<?php get_sidebar(); ?>
	
<?php get_footer(); ?>
