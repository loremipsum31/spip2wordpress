<?php get_header(); ?>
<!--index.php-->
<div id="middle">
	<div id="content">
		<div id="intro">
			<h1>AccessiBlogue</h1>
			<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin eu risus ut mauris egestas pellentesque a vitae nunc. Etiam rhoncus accumsan dolor, sed lobortis risus pharetra eget. Nunc sed ipsum fermentum nisl eleifend tempor nec vitae nisi. Suspendisse pretium scelerisque vestibulum. Maecenas luctus leo aliquam mauris posuere viverra.</p>
		</div>
		<div id="posts">
		<?php if (have_posts()) : ?>
			<?php while (have_posts()) : the_post(); ?>
			<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
				<div class="meta">
					<span class="date"><?php the_date(); ?></span> <span class="author"><?php _e('by', 'webaccess'); ?> <?php the_author() ?> </span>
					<span class="date"><?php the_date(); ?></span>
					<?php if ( comments_open() ) : ?>
						<span class="comments">
							<?php 
							/* Makes the a href unique and represent the current post
							 * 
							 * Le a href est unique et représente l'article actuel
							 * 
							 * SGQRI 008-01 Spécification 18 d) pour un hyperlien constituant la 
							 * seule façon d’accéder à une destination à partir de cette page, 
							 * libeller l’hyperlien pour que sa destination puisse être déterminée 
							 * hors de son contexte immédiat;
							 */
							$title = '<em class="screen_reader"> '.get_the_title().'</em>';
							comments_popup_link(__('No comments for the post', 'webaccess').$title , __('1 comment for the post', 'webaccess').$title, __('% comments for the post', 'webaccess').$title);
							?>
						</span>
					<?php endif; // comments open ?>
					<?php if (is_user_logged_in()) : ?>
					    <!-- 
					    Makes the a href unique and represent the current post 
					    Le a href est unique et représente l'article actuel
					    SGQRI 008-01 Spécification 18 d)
					    -->
						<span class="edit-link"><a href="<?php echo get_edit_post_link() ?>"><?php _e('Edit the post', 'webaccess'); ?> <em class="screen_reader"><?php the_title(); ?></em></a></span>
					<?php endif; //end user_logged ?>	
				</div>
				<div class="post_excerpt">
					 <?php the_excerpt(); ?>
				</div>
			</div><!-- end post-ID --> 
			<?php endwhile; ?>	
		<?php endif; ?>
		</div><!-- end posts --> 
		<?php if (function_exists("emm_paginate")) {
			emm_paginate();
		} ?>
	</div><!-- end content --> 
</div><!-- end middle -->
	
<?php get_sidebar(); ?>

<?php get_footer(); ?>
