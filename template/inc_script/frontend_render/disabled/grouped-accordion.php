<?php
// Load Accordion for grouped sections
if(strpos($content['all'], $template_default['classes']['cpgroup-container'])) {
	
	// This is just a possible example of how to handle over class options
	// which then could be used for other custom scripts
	/*
	renderHeadJS('
	var accOptions = {
		container: "'.$template_default['classes']['cpgroup-container'].'",
		group: "'.$template_default['classes']['cpgroup'].'",
		title: "'.$template_default['classes']['cpgroup-title'].'",
		content: "'.$template_default['classes']['cpgroup-content'].'"
	};
	');
	*/
	
	// Hide all Accordion Content elements
	// Better define this in your default CSS
	renderHeadCSS('.'.$template_default['classes']['cpgroup-content'].' {
		display: none;
	}');
	
	// Load the slightly enhanced Accordion class
	renderHeadJS('zebra_accordion.min');
	renderHeadJS('
	$(function() {
		
		var accGroups = $(".' . $template_default['classes']['cpgroup'] . '");
		if(accGroups.length > 0) {
			var accAccordion = new $.Zebra_Accordion(accGroups, {
				// The Accordion switch selector
				switch: ".'.$template_default['classes']['cpgroup-title'].'",
				// The Accordian content selector
				content: ".'.$template_default['classes']['cpgroup-content'].'",
				// Every block can be opened (or closed)
				collapsible: true,
				// Do not open the first accordion on enter the page
				show: false,
				
				// The expanded class
				expanded_class: "expanded"
				
				// put in additional Options here
				// visit http://stefangabos.ro/jquery/zebra-accordion/ to get to know more
			});
		}
		
	});'
	
	);

}


?>