/*	Script: Autocompleter.Remote.js
		Contains the classes for the Remote methods for <Autocompleter>.

		Namespace: Autocompleter.Ajax
		Contains the classes for the Remote methods for <Autocompleter>
		
		Details:
		Author - Harald Kirschner <mail [at] digitarald.de>
		Refactored by - Aaron Newton <aaron [dot] newton [at] cnet [dot] com>
		License - MIT-style license
		Version - 1.0rc3
		Source - http://digitarald.de/project/autocompleter/
		
		Dependencies:
		Mootools 1.1 - <Class.Extras>, <Element.Event>, <Element.Selectors>, <Element.Form>, <Element.Dimensions>, <Fx.Style>, <Ajax>, <Json>
		Autocompleter - <Autocompleter.js>, <Observer.js>
	*/
Autocompleter.Ajax = {};
/*	Class: Autocompleter.Ajax.Base
		The base functionality for all <Autocompleter.Ajax> functionality.
		
		Arguments:
		el - (DOM element or id) element to observe.
		url - (string) the url to query for values
		options - (object) key/value set of options.
		
		Options:
		postVar - String, default `value`. Post variable name for the query string
		postData - Object, optional. Additional request data
		ajaxOptions - optional Options Object. For the Ajax instance
		onRequest - Event Function.
		onComplete - Event Function.
		
		Example:
		The <Autocompleter.Ajax.Base> class is not used directly but rather its inhertants are (see 
		<Autocompleter.Ajax.Json> and <Autocompleter.Ajax.Xhtml>) so there is no example here.
	*/
Autocompleter.Ajax.Base = Autocompleter.Base.extend({

	options: {
		postVar: 'value',
		postData: {},
		ajaxOptions: {},
		onRequest: Class.empty,
		onComplete: Class.empty
	},

	initialize: function(el, url, options) {
		this.parent(el, options);
		this.ajax = new Ajax(url, $merge({
			autoCancel: true
		}, this.options.ajaxOptions));
		this.ajax.addEvent('onComplete', this.queryResponse.bind(this));
		this.ajax.addEvent('onFailure', this.queryResponse.bind(this, [false]));
	},

	query: function(){
		var multi = this.options.multi;
		var data = $extend({}, this.options.postData);
		if(multi) this.lastQueryElementValue = this.element.value.lastElement(this.options.delimeter);
		data[this.options.postVar] = (multi)?this.lastQueryElementValue:this.element.value;
		this.fireEvent('onRequest', [this.element, this.ajax]);
		this.ajax.request(data);
	},
	
/*	Property: queryResponse
		Inherated classes have to extend this function and use this.parent(resp)
		
		Arguments:
		resp - (String) the response from the ajax query.
*/
	queryResponse: function(resp) {
		this.value = this.queryValue = this.element.value;
		this.selected = false;
		this.hideChoices();
		this.fireEvent(resp ? 'onComplete' : 'onFailure', [this.element, this.ajax], 20);
	}

});

/*	Class: Autocompleter.Ajax.Json
		Extends <Autocompleter.Ajax.Base> to include Json support.
		
		Arguments:
		All those specified in <Autocompleter.Ajax.Base> and <Autocompleter.Base>.
		
		Example:
new Autocompleter.Ajax.Json($('ajaxJson'), 'server/auto.php' {
	postVar: 'query'
});
	*/
Autocompleter.Ajax.Json = Autocompleter.Ajax.Base.extend({

	queryResponse: function(resp) {
		this.parent(resp);
		var choices = Json.evaluate(resp || false);
		if (!choices || !choices.length) return;
		this.updateChoices(choices);
	}

});

/*	Class: Autocompleter.Ajax.Xhtml
		Extends <Autocompleter.Ajax.Base> to include Xhtml support.

		Arguments:
		All those specified in <Autocompleter.Ajax.Base> and <Autocompleter.Base>.

		Example:		
(start code)
new Autocompleter.Ajax.Xhtml($('ajaxXhtml'), 'server/auto.php', {
	postData: {html: 1}, //some data to go along with the request
	//handle the data returned
	parseChoices: function(el) {
		var value = el.getFirst().innerHTML;
		el.inputValue = value;
		this.addChoiceEvents(el).getFirst().setHTML(this.markQueryValue(value));
	}
});
(end)
	*/
Autocompleter.Ajax.Xhtml = Autocompleter.Ajax.Base.extend({

	options: {
		parseChoices: null
	},

	queryResponse: function(resp) {
		this.parent(resp);
		if (!resp) return;
		this.choices.setHTML(resp).getChildren().each(this.options.parseChoices || this.parseChoices, this);
		this.showChoices();
	},

	parseChoices: function(el) {
		var value = el.innerHTML;
		el.inputValue = value;
		el.setHTML(this.markQueryValue(value));
	}

});

/* do not edit below this line */   
/* Section: Change Log 

$Source: /cvs/main/flatfile/html/rb/js/global/cnet.global.framework/common/3rdParty/Autocomplete/Autocompleter.Remote.js,v $
$Log: Autocompleter.Remote.js,v $
Revision 1.1  2007/06/12 20:26:52  newtona
*** empty log message ***


*/