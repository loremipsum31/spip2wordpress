<div id="footer">
	<!-- The h1 element for the footer helps to maintain a proper order of the headers of the page. -->
	<h1 class="screen_reader"><?php _e('Footer'); ?></h1>
	<?php echo do_shortcode( '[contact-form 1 "Contact form 1"]' ); 
	/*
	 * 
	 * echo do_shortcode( '[contact-form 1 "Contact form 1"]' ); 
	 * Google Maps à la mitaine.
	 * Twitter ??
	 * the_widget( 'ALO_Easymail_Widget' );
	 */
	 ?>
	<p>&copy; Coopérative AccessibilitéWeb, 2006-<?php echo date('Y'); ?>. Sous licence <a href="http://creativecommons.org/licenses/by-nd/3.0/deed.fr_CA" hreflang="fr_CA" lang="en_CA">Creative Commons BY-ND</a>. <a href="/accessibilite"><?php _e('Accessibility', 'webaccess'); ?></a> <a href="/plan-du-site"><?php _e('Site Map', 'webaccess'); ?></a></p>
</div>

<?php wp_footer(); ?>

</div><!-- end container -->

</body>
</html>
