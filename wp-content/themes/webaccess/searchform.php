 <form id="searchform" method="get" action="<?php echo home_url(); ?>">
  <div>
	<?php
	/* Use label with attribut "for" and corresponding "id" in the input,
	 * otherwise replace with:
	 * <input type="text" id="search" title="description of this field" />
	 * 
	 * SGQRI 008-01 Spécification 21 b) pour tout champ, comporter une 
	 * étiquette codée conformément à l’annexe 1 ou lorsque l’espace est 
	 * insuffisant pour placer une étiquette, la description de la fonction 
	 * du champ concerné codée conformément à l’annexe 1;
	 */
	 ?>
	 <label class="screen_reader" for="search_input"><?php _e('Search'); ?></label>   
	 <input type="text" name="s" id="search_input" />
	 <input id="searchsubmit" type="submit" value="<?php _e('Search'); ?>" />
  </div>
 </form>
