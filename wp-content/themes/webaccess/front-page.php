<?php 
/**
 * Template Name: Index Page
 * MultiEdit: Left,Middle,Right
 */

get_header(); ?>

<!--page.php-->
<div id="middle" class="custom">
	<div id="content">
		<div id="posts">
			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
			<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<div class="post_body">
					<?php the_content(); ?>	
				<?php endwhile; endif; ?><!--end of post and end of loop-->
				</div> 
			</div><!-- end post-ID --> 
			<div id="left-block"><?php multieditDisplay('Left'); ?></div>
			<div id="middle-block"><?php multieditDisplay('Middle'); ?></div>
			<div id="right-block">
				<h3><?php _e('Latest News'); ?></h3>
				<div id="latest_posts">
				<?php
				$args = array( 'numberposts' => 3 );
				$lastposts = get_posts( $args );
				foreach($lastposts as $post) : setup_postdata($post); ?>
					<h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
					<div class="meta">
						<span class="date"><?php the_date(); ?></span> <span class="author"><?php _e('by', 'webaccess'); ?> <?php the_author() ?> </span>
					</div>
					<div class="gravatar"><?php echo get_avatar( get_the_author_id(), 50 ); ?></div>		
						<div class="post_excerpt">
							 <?php the_excerpt_max_charlength(200); ?>
						</div>
				<?php endforeach; ?>					
				</div><!-- end latest posts --> 
				<div><a href="/accessiblogue/">Consulter notre blogue</a></div>
				<div><?php multieditDisplay('Right'); ?></div>
				
			</div>
			<?php if (is_user_logged_in()) : ?>
				<!-- 	
				Makes the a href unique and represent the current post
				Le a href est unique et représente l'article actuel
				SGQRI 008-01 Spécification 18 d) 
				-->
				<span class="edit-link"><a href="<?php echo get_edit_post_link() ?>"><?php _e('Edit the page', 'webaccess') ?> <em><?php the_title(); ?></em></a></span>
			<?php endif; //end user_logged ?>				
		</div><!-- end posts --> 
	</div><!-- end content --> 
</div><!-- end middle --> 
	
<?php get_footer(); ?>
