<?php get_header() ?>
<!--404.php-->
<div id="middle">
	<div id="content">
	  <div id="post-0" class="post error404">
			<h1 class="entry-title"><?php _e('Page Not Found', 'webaccess'); ?></h1>
			<div class="entry-content">
				<?php $address = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>
				<p><?php printf(__('Apologies, but the page <strong><em>%s</em></strong> does not exist on this blog. Perhaps searching will help.', 'webaccess'), $address); ?></p>
				<?php get_search_form(); ?>
			</div><!-- entry-content -->
		</div><!-- #post-0 -->
	</div><!-- #content -->
</div><!-- end middle -->	
<?php get_sidebar() ?>

<?php get_footer() ?>
