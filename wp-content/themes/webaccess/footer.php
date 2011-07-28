<div id="footer">
	<!-- The h1 element for the footer helps to maintain a proper order of the headers of the page. -->
	<h1 class="screen_reader"><?php _e('Footer'); ?></h1>
	<div id="subfooter-left">
		<h2>Contactez-nous!</h2>
		<p>
		Coopérative AccessibilitéWeb<br />
		1751, rue Richardson suite 6.111<br />
		Montréal, (Québec), H3K 1G6<br />
		Téléphone Sans-frais : 1-877-315-5550<br />
		Télécopieur Sans-frais : 1-877-315-5550<br />
		Région de Montréal : 514-312-3378<br />
		Courriel: info@accessibiliteweb.com
		</p>
		<div id="googlemap">
			<div class="avertissement">
			<a href="/accessibilite">
				<img src="<?php bloginfo('template_directory'); ?>/images/icone_avertissement.gif" alt="<?php _e('Avertissement, le contenu disponible dans la section suivante pourrait ne pas être conforme aux exigences du SGQRI&nbsp;008-01.', 'webaccess'); ?>" title="<?php _e('Avertissement, le contenu disponible dans la section suivante pourrait ne pas être conforme aux exigences du SGQRI&nbsp;008-01', 'webaccess'); ?>" />
				<span class="msg-icone"><?php _e('Consultez la page <em>Accessibilité</em> pour plus d’information sur les contenus non conformes', 'webaccess'); ?></span>
			</a>
			</div>	
			<iframe width="270" height="180" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="http://maps.google.ca/maps?f=q&amp;source=s_q&amp;hl=en&amp;geocode=&amp;q=Accessibilit%C3%A9Web,+Montreal,+QC&amp;aq=0&amp;sll=45.484101,-73.561932&amp;sspn=0.007432,0.01929&amp;g=1751+Rue+Richardson,+Montr%C3%A9al,+Communaut%C3%A9-Urbaine-de-Montr%C3%A9al,+Qu%C3%A9bec+H3K+1G5&amp;ie=UTF8&amp;hq=Accessibilit%C3%A9Web,&amp;hnear=Montreal,+Communaut%C3%A9-Urbaine-de-Montr%C3%A9al,+Quebec&amp;ll=45.483981,-73.562154&amp;spn=0.054797,0.017572&amp;output=embed"></iframe>
			<!--<a href="http://maps.google.ca/maps?f=q&amp;source=embed&amp;hl=en&amp;geocode=&amp;q=Accessibilit%C3%A9Web,+Montreal,+QC&amp;aq=0&amp;sll=45.484101,-73.561932&amp;sspn=0.007432,0.01929&amp;g=1751+Rue+Richardson,+Montr%C3%A9al,+Communaut%C3%A9-Urbaine-de-Montr%C3%A9al,+Qu%C3%A9bec+H3K+1G5&amp;ie=UTF8&amp;hq=Accessibilit%C3%A9Web,&amp;hnear=Montreal,+Communaut%C3%A9-Urbaine-de-Montr%C3%A9al,+Quebec&amp;ll=45.483981,-73.562154&amp;spn=0.054797,0.017572"></a>-->
		</div>
	 </div>
	<div id="subfooter-middle">
		<?php echo do_shortcode( '[contact-form 1 "Contact form 1"]' ); ?>
	</div>
	 <div id="subfooter-right">
		 <img src="<?php bloginfo('template_directory'); ?>/images/twitter.gif" alt=""/>
		<h2>Twitter Feed</h2>
		<p>Suivez @AccessibiliteWb sur Twitter!</p>
		<?php twitter_messages("accessibilitewb", 3, true, false, false, true, true, false); ?>
		<?php if ( class_exists( 'MailPress' ) ) MailPress ::form( array( 'txtbutton' => '_e( "Stay Connected", "webaccess" )' ) ); ?> 
	 </div>
	 <div id="subfooter-bottom">
		 <p>&copy; Coopérative AccessibilitéWeb, 2006-<?php echo date('Y'); ?>. Sous licence <a href="http://creativecommons.org/licenses/by-nd/3.0/deed.fr_CA" hreflang="fr_CA" lang="en_CA">Creative Commons BY-ND</a>. <a href="/accessibilite"><?php _e('Accessibility', 'webaccess'); ?></a> <a href="/plan-du-site"><?php _e('Site Map', 'webaccess'); ?></a></p>
	</div>
</div><!-- end footer -->

<?php wp_footer(); ?>

</div><!-- end container -->

</body>
</html>
