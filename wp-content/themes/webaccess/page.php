<?php get_header(); ?>

<!--page.php-->
<div id="middle">
	<div id="content">
		<div id="posts">
			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
			<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<h1><?php the_title(); ?></h1>
				<div class="post_body">
					<?php the_content(); ?>
					<?php if (is_user_logged_in()) : ?>
						<!-- 	
						Makes the a href unique and represent the current post
						Le a href est unique et représente l'article actuel
						SGQRI 008-01 Spécification 18 d) 
						-->
						<span class="edit-link"><a href="<?php echo get_edit_post_link() ?>"><?php _e('Edit the page', 'webaccess') ?> <em><?php the_title(); ?></em></a></span>
					<?php endif; //end user_logged ?>		
				<?php endwhile; endif; ?><!--end of post and end of loop-->
				</div> 
			</div><!-- end post-ID --> 
		</div><!-- end posts --> 
		<?php if (comments_open()) comments_template();	?>
	</div><!-- end content --> 
</div><!-- end middle --> 
	
<?php get_sidebar(); ?>

<?php get_footer(); ?>
