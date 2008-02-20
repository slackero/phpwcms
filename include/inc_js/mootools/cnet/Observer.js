/*	Script: Observer.js
		Observes formelements for changes; part of the <Autocomplete> 3rd party package.
		
		Details:
		Author - Harald Kirschner <mail [at] digitarald.de>
		License - MIT-style license
		Version - 1.0rc1
		Source - http://digitarald.de/project/autocompleter/
		
		Dependencies:
		Mootools 1.1 - <Class>, <Class.Extras>, <Element.Event>
		
		Class: Observer
		Observes form elements for changes; part of the <Autocomplete> 3rd party package.
		
		Arguments:
		el - (DOM element or id) element to observe
		onFired - (function) event to execute periodically and/or on keyup
		options - (object) a set of key/value options
		
		Options:
		periodical - (boolean) update and fire the onFired event regularly; defaults to false
		delay - (integer) how often to update using periodical if (periodical is true); defaults to 1000
	*/
var Observer = new Class({

	options: {
		periodical: false,
		delay: 1000
	},

	initialize: function(el, onFired, options){
		this.setOptions(options);
		this.addEvent('onFired', onFired);
		this.element = $(el);
		this.listener = this.fired.bind(this);
		this.value = this.element.getValue();
		if (this.options.periodical) this.timer = this.listener.periodical(this.options.periodical);
		else this.element.addEvent('keyup', this.listener);
	},

	fired: function() {
		var value = this.element.getValue();
		if (this.value == value) return;
		this.clear();
		this.value = value;
		this.timeout = this.fireEvent.delay(this.options.delay, this, ['onFired', [value]]);
	},

	clear: function() {
		$clear(this.timeout);
		return this;
	}
});

Observer.implement(new Options);
Observer.implement(new Events);

/* do not edit below this line */   
/* Section: Change Log 

$Source: /cvs/main/flatfile/html/rb/js/global/cnet.global.framework/common/3rdParty/Autocomplete/Observer.js,v $
$Log: Observer.js,v $
Revision 1.2  2007/06/12 20:26:52  newtona
*** empty log message ***

Revision 1.1  2007/06/02 01:35:17  newtona
*** empty log message ***


*/
