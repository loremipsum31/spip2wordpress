<?php get_header(); ?>
<!--single.php-->
<div id="middle">
	<div id="content">
		<div id="intro">
			<h1>AccessiBlogue</h1>
			<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin eu risus ut mauris egestas pellentesque a vitae nunc. Etiam rhoncus accumsan dolor, sed lobortis risus pharetra eget. Nunc sed ipsum fermentum nisl eleifend tempor nec vitae nisi. Suspendisse pretium scelerisque vestibulum. Maecenas luctus leo aliquam mauris posuere viverra.</p>
		</div>		
		<div id="posts">
			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>			
			<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<h2><?php the_title(); ?></h2>
				<div class="meta">
					<span class="date">
						<?php the_date(); ?>
					</span>	
					<span class="author"> 
						<?php _e('by', 'webaccess'); ?> <?php the_author() ?> 
					</span>
					<span class="date">
						<?php
							echo ' '.__('at', 'webaccess').' '; 
							the_time(); 
						?> 
					</span>
				</div>
				<div class="gravatar"><?php echo get_avatar( get_the_author_id(), 50 ); ?></div>
				<div class="tweet"><a href="http://twitter.com/share?url=<?php echo rawurlencode(get_permalink()); ?>" title="<?php _e('Click to share this post on Twitter');?>"><?php _e('Tweet');?></a></div>
				<div class="like"><a href="http://www.facebook.com/sharer.php?u=<?php echo rawurlencode(get_permalink()); ?>&amp;t=<?php the_title(); ?>" title="<?php _e('Click to share this post on Facebook');?>"><?php _e('Share');?></a></div>				
				<div class="post_body">
					<?php the_content(); ?>
				</div>
				<div class="meta">
					<div class="category"> 		
							<?php
								/* A list must consist of 2 or more elements, therefore
								 * if there's only one Category, the item should be in
								 * a span and not in a list. 
								 */
								$cat_count = count(get_the_category()); 
								if ($cat_count == 1) { 
									echo '<h2>'.__('Category').' : </h2>';
									echo '<span>';
									the_category(' ');
									echo '</span>';
								} else { 
									echo '<h2>'.__('Categories').' : </h2>';
									the_category();
								} 
							?>
						</div>
						
						<?php if(has_tag()) : ?>
							<div class="tags"> 
							<?php
								/* A list must consist of 2 or more elements, therefore
								 * if there's only one Tag, the item should be in
								 * a span and not in a list. 
								 */
								$tag_count = count(get_the_tags()); 
								if ($tag_count == 1) { 
									echo '<h2>'.__('Tag').' : </h2>';
									the_tags('<span>', '', '</span>');
								} else { 
									echo '<h2>'.__('Tags').' : </h2>';
									the_tags('<ul><li>','</li><li>','</li></ul>'); 
								} ?>	
							</div>
						<?php endif; //end has tag ?>				
					</div>
					<?php if (is_user_logged_in()) : ?>
							<!-- 
							Makes the a href unique and represent the current post
							Le a href est unique et représente l'article actuel
							SGQRI 008-01 Spécification 18 d) 
							-->
							<span class="edit-link"><a href="<?php echo get_edit_post_link() ?>"><?php _e('Edit the post', 'webaccess') ?> <em><?php the_title(); ?></em></a></span>
					<?php endif; //end user_logged ?>
					<?php if (('open' == $post->ping_status)) : ?>
						<p id="post_meta">
							<a href="<?php trackback_url(true); ?>" rel="trackback"><?php _e('You can trackback from your own site.', 'webaccess'); ?></a> 
						</p>
					<?php endif; // end ping status ?>
				
				<?php if (comments_open()) comments_template();	?>
				
			</div><!-- end post-ID -->
			
		<?php endwhile; else: ?>
		
	<?php endif; ?>
	
		</div><!-- end posts -->
	</div><!-- end content -->
</div><!-- end middle -->

<?php get_sidebar(); ?>

<?php get_footer(); ?>
