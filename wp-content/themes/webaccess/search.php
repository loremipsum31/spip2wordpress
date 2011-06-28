<?php get_header( ); ?>
<!--search.php-->
<div id="middle">
	<div id="content">
		<div id="posts">
		<?php if (have_posts()) : ?>
		<h1 class="archive"><?php _e('Search results for', 'webaccess'); ?> <em><?php the_search_query() ?></em></h1>
		
		<?php while (have_posts()) : the_post(); ?>
			<div id="post-<?php the_ID(); ?>" class="post">
				<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
				<div class="meta">
					<span class="author"><?php _e('Published by', 'webaccess'); ?> <?php the_author() ?> </span>
					<span class="date">
						<?php 
							echo __('the', 'webaccess').' '; 
							the_date(); 
							echo ' '.__('at', 'webaccess').' '; 
							the_time(); 
						?> 
					</span>
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
							$title = ' <em>'.get_the_title().'</em>';
							comments_popup_link(__('No comments for the post', 'webaccess').$title, __('1 comment for the post', 'webaccess').$title, __('% comments for the post', 'webaccess').$title);
							?>
						</span>
					<?php endif; //end comments open ?>
					
					<?php if (is_user_logged_in()) : ?>
						<!-- 
						Makes the a href unique and represent the current post 
						Le a href est unique et représente l'article actuel 
						SGQRI 008-01 Spécification 18 d) 
						-->
						<span class="edit-link"><a href="<?php echo get_edit_post_link() ?>"><?php _e('Edit the post', 'webaccess'); ?> <em><?php the_title(); ?></em></a></span>
					<?php endif; //end user_logged ?>
				</div>
				
				<div class="post_excerpt">
					 <?php the_excerpt(); ?>
				</div>
			</div><!-- end post-ID-->

		<?php endwhile; ?>
	
	<?php else : ?>
		<h1><?php _e('No resource corresponds to your search criteria.', 'webaccess'); ?></h1>
		<p>
		<?php _e('Your search criteria ', 'webaccess'); ?> <strong><em><?php the_search_query() ?></em></strong> <?php _e(' have not been found. Please try a different keyword.', 'webaccess'); ?>
		</p>
	<?php endif; ?>
		</div><!-- end posts -->
	</div><!-- end content -->
</div><!-- end middle -->
<?php get_sidebar(); ?>

<?php get_footer() ?>
