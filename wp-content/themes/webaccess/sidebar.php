<div id="sidebar">
<!-- The h1 element for the sidebar helps to maintain a proper order of the headers of the page. -->
<h1 class="screen_reader"><?php _e('Sidebar', 'webaccess'); ?></h1>
	<?php
	/* When we call the dynamic_sidebar() function, it'll spit out
	 * the widgets for that widget area. If it instead returns false,
	 * then the sidebar simply doesn't exist, so we'll hard-code in
	 * some default sidebar stuff just in case.
	 */
	if ( ! dynamic_sidebar( 'sidebar' ) ) : ?>

		
		<?php if(count(get_tags()) !=0) : ?>
		<div id="tags">
			<?php
				/* A list must consist of 2 or more elements, therefore
				 * if there's only one Tag, the item should be in a 
				 * paragraph and not in a list.
				 * 
				 * The function wp_tag_cloud() was replaced with the function
				 * bwp_tag_cloud() that can be found in functions.php.
				 */
				$tag_count = count(get_tags()); 
				if($tag_count == 1) {
					echo '<h2 class="title">'.__('Tag').'</h2>';
					echo '<span>';
					bwp_tag_cloud('unit=em&smallest=1&largest=1&format=flat&topic_count_text_callback=default_topic_count_text'); 
					echo '</span>';
				} else { 
					echo '<h2 class="title">'.__('Tags').'</h2>';
					bwp_tag_cloud('unit=em&smallest=1&largest=1&format=list&topic_count_text_callback=default_topic_count_text'); 
				} ?> 
		</div>
		<?php endif; //end has tag ?>
		
		<div id="archives">
			<!-- To be done: If the archive consists of only one item wp_get_archives() should be replaced by a paragraph. -->
			<h2 class="title"><?php _e('Archives'); ?></h2>
			<ul>
				<?php wp_get_archives('show_post_count=1&type=yearly'); ?>
			</ul>
		</div>
		
		<div id="categories">
			<?php 
				/* A list must consist of 2 or more elements, therefore
				 * if there's only one Category, the item should be in
				 * a paragraph and not in a list.
				 */
				$cat_count = count(get_categories()); 
				if($cat_count == 1) {
					echo '<h2 class="title">'.__('Category').'</h2>';
					$categories=get_categories();
					foreach($categories as $category) { 
						if ($category->count == 1)
							$amountPosts = __('post', 'webaccess');
						else 
							$amountPosts = __('posts');
						echo '<p><a href="' . get_category_link( $category->term_id ) . '" title="' . sprintf( __( "View all posts in %s" ), $category->name ) . '" ' . '>' . $category->name.' ('. $category->count .')</a> </p>';  
					} 
				} else { ?>
			<h2 class="title"><?php _e('Categories'); ?></h2>
			<ul>
				<?php wp_list_categories('show_count=1&title_li='); ?>
			</ul>
			<?php } // end count category ?>
		</div>		
	<?php endif; // end left sidebar ?>
</div><!--end sidebar-->
