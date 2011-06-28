<div id="comments">
	<?php if ( post_password_required() ) : ?>
		<p class="nopassword"><?php _e( 'This post is password protected. Enter the password to view any comments.', 'webaccess' ); ?></p>
	</div><!-- #comments -->
	<?php
		/* Stop the rest of comments.php from being processed,
		 * but don't kill the script entirely -- we still have
		 * to fully load the template.
		 */
		return;
	endif;
	?>
<?php
	/* Accessibility changes to the comment form involve making the wording 
	 * most be clear, removing the aria-required that does not validate with 
	 * the present doctype, and replacing some HTML tags and attributes posted. 
	 */
	$args = array(
		'logged_in_as' => '<p class="logged-in-as">' . sprintf( __( 'Your comment will be published as <a href="%1$s">%2$s</a>.', 'webaccess' ), admin_url( 'profile.php' ), $user_identity ) . '</p>',
		'comment_field' => '<div class="comment-form-comment"><label for="comment">'. __( 'Comment' ). '</label><textarea name="comment" rows="8" cols="45" id="comment"></textarea></div>',
		'comment_notes_after' =>    
					'<p class="form-allowed-tags">' . sprintf( __( 'You may use these <abbr title="HyperText Markup Language">HTML</abbr> tags and attributes: %s' ), ' <code lang="en">' . 
					htmlentities('<a href="" title="" hreflang=""> <abbr title="" lang=""> <blockquote cite="" lang=""> <cite lang=""> <code lang=""> <em> <strike> <strong>') . '</code>' ) . '</p>',  
		'label_submit' => __( 'Submit your Comment' , 'webaccess'),    
	);
	comment_form($args); ?>
	<div id="comments-list" class="comments">
		<!-- The h2 element must be unique and represent the amount of comments -->
		<h2>
			<?php _e('The post', 'webaccess'); ?><em> <?php the_title(); ?> </em> <?php comments_number(__('has no comments', 'webaccess'), __('has one comment', 'webaccess'), __('has % comments', 'webaccess')); ?>
		</h2>

	<?php if ( have_comments() ) : ?>
		<ul class="commentlist">
			<?php wp_list_comments('type=comment&callback=webaccess_comment'); ?>
		</ul>
		<div id="comments-nav-below" class="comments-navigation">
			<?php paginate_comments_links(); ?>
		</div>
	<?php endif // end have_comments() ?>
	</div><!-- #comments-list .comments -->
</div><!-- #comments -->
