<?php
// Load Accordion for grouped sections
if(!empty($template_default['classes']['cpgroup-container']) && strpos($content['all'], $template_default['classes']['cpgroup-container'])) {

	$cpgroup_container = $template_default['classes']['cpgroup-container'];
	$cpgroup           = $template_default['classes']['cpgroup'];
	$cpgroup_title     = $template_default['classes']['cpgroup-title'];
	$cpgroup_content   = $template_default['classes']['cpgroup-content'];

	// Hide all Accordion Content elements and show when expanded
	// Better define this in your default CSS
	renderHeadCSS('
	    .' . $cpgroup_title . '{cursor: pointer;}
	    .' . $cpgroup_content . '{display: none;}
	    .' . $cpgroup . '.expanded > .' . $cpgroup_content . '{display: block;}
	');

	// Vanilla JS Accordion for grouped sections
	renderHeadJS('
        document.addEventListener("click", function(e) {
            var title = e.target.closest(".' . $cpgroup_title . '");
            if(!title) {
                return;
            }
            var group = title.closest(".' . $cpgroup . '");
            if(!group) {
                return;
            }
            var container = group.closest(".' . $cpgroup_container . '");
            var isExpanded = group.classList.contains("expanded");

            if(container) {
                container.querySelectorAll(".' . $cpgroup . '.expanded").forEach(function(el) {
                    if(el !== group) {
                        el.classList.remove("expanded");
                        var t = el.querySelector(".' . $cpgroup_title . '");
                        if(t) {
                            t.classList.remove("expanded");
                        }
                    }
                });
            }

            group.classList.toggle("expanded", !isExpanded);
            title.classList.toggle("expanded", !isExpanded);
            e.preventDefault();
        });
	');
}
