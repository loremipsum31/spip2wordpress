jQuery(document).ready(function() {
		jQuery(".wcag").hide();
		jQuery("#event_city").hide();
		jQuery("#event_language").hide();
		jQuery("#event_cost").hide();
			

 function displayInputs() {
	 if(jQuery('select').val() == 'wcag'){
		jQuery(".wcag").show(1000);
		jQuery("#event_city").show(1000);
		jQuery("#event_language").show(1000);
		jQuery("#event_cost").show(1000);
	} else {
		jQuery(".wcag").hide('slow');
		jQuery("#event_city").hide('slow');
		jQuery("#event_language").hide('slow');
		jQuery("#event_cost").hide('slow');
	}
}

	jQuery('select').change(displayInputs);
    displayInputs();	
 });

