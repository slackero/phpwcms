/*	Script: Autocompleter.js
		3rd party script for managing autocomplete functionality.
		
		Details:
		Author - Harald Kirschner <mail [at] digitarald.de>
		Refactored by - Aaron Newton <aaron [dot] newton [at] cnet [dot] com>
		License - MIT-style license
		Version - 1.0rc3
		Source - http://digitarald.de/project/autocompleter/
		
		Dependencies:
		Mootools 1.1 - <Class.Extras>, <Element.Event>, <Element.Selectors>, <Element.Form>, <Element.Dimensions>, <Fx.Style>, <Ajax>, <Json>
		Autocompleter - <Observer.js>
		
		Namespace: Autocompleter
		All functions are part of the <Autocompleter> namespace.
	*/
var Autocompleter = {};
/*	Class: Autocompleter.Base
		Base class for the Autocompleter classes.
		
		Arguments:
		el - (DOM element or id) element to observe.
		options - (object) key/value set of options.
		
		Options:
		minLength - (integer, default 1) Minimum length to start auto compliter
		useSelection - (boolean, default true) Select completed text part (works only for appended strings)
		markQuery - (boolean, default true) Mark queried string with <span class="autocompleter-queried">*</span>
		inheritWidth - (boolean, default true) Inherit width for the autocompleter overlay from the input field
		maxChoices - (integer, default 10). Maximum of suggested fields.
		injectChoice - (function, optional). Callback for injecting the list element with the arguments (itemValue, itemIndex), take a look at updateChoices for default behaviour.
		onSelect - Event Function. Fires when when an item gets selected; passed the input and the value selected.
		onShow - Event Function. Fires when autocompleter list shows.
		onHide - Event Function. Fires when autocompleter list hides.
		customTarget - (element, optional). Allows to override the autocompleter list element with your own list element.
		className - (string, default 'autocompleter-choices'). Class name for the list element.
		zIndex - (integer, default 42). z-Index for the list element.
		observerOptions - optional Options Object. For the Observer class.
		fxOptions - optional Options Object. For the Fx.Style on the list element.
		allowMulti - (boolean, defaults to false) allow more than one value, seperated by a delimeter
		delimeter - (string) default delimter between multi values (defaults to ", ")
		autotrim - (boolean) trim the delimeter on blur
		allowDupes - (boolean, defaults to false) if multi, prevent duplicate entries
		baseHref - (string) the base url where the css and image assets are located (defaults to cnet's servers you should change)

		Note:
		If you're not cnet, you should download these assets to your own local:
		http://www.cnet.com/html/rb/assets/global/autocompleter/Autocompleter.css
		http://www.cnet.com/html/rb/assets/global/autocompleter/spinner.gif
		
		Then either change this script or pass in the local when you instantiate the class.
		
		Example:
		This base class is not used directly (but rather its inheritants are such as <Autocompleter.Ajax>)
		so there is no example here.
	*/
Autocompleter.Base = new Class({

	options: {
		minLength: 1,
		useSelection: true,
		markQuery: true,
		inheritWidth: true,
		dropDownWidth: 100,
		maxChoices: 10,
		injectChoice: null,
		onSelect: Class.empty,
		onShow: Class.empty,
		onHide: Class.empty,
		customTarget: null,
		className: 'autocompleter-choices',
		zIndex: 42,
		observerOptions: {},
		fxOptions: {},
		multi: false,
		delimeter: ', ',
		autotrim: true,
		allowDupes: false,
		/*	if you're not cnet, you should download these assets to your own local:
				http://www.cnet.com/html/rb/assets/global/autocompleter/Autocompleter.css
				http://www.cnet.com/html/rb/assets/global/autocompleter/spinner.gif
			*/
		baseHref: 'http://www.cnet.com/html/rb/assets/global/autocompleter/'
	},

	initialize: function(el, options) {
		this.setOptions(options);
		if(!$('AutocompleterCss')) window.addEvent('domready', function(){
			new Asset.css(this.options.baseHref+'Autocompleter.css', {id: 'AutocompleterCss'});
		}.bind(this));
		this.element = $(el);
		this.build();
		this.observer = new Observer(this.element, this.prefetch.bind(this), $merge({
			delay: 400
		}, this.options.observerOptions));
		this.value = this.observer.value;
		this.queryValue = null;
		this.element.addEvent('blur', function(e){
			this.autoTrim.delay(50, this, e);
		}.bind(this));
		this.addEvent('onSelect', function(){
			this.element.focus();
			this.userChose = true;
			(function(){
				this.userChose = false;
			}).delay(100, this);
		}.bind(this));
	},

/*	Property: build
		Builds the html structure for choices and appends the events to the element.
		Override this function to modify the html generation.	*/

	build: function() {
		if ($(this.options.customTarget)) this.choices = this.options.customTarget;
		else {
			this.choices = new Element('ul', {
				'class': this.options.className,
				'styles': {zIndex: this.options.zIndex}
				}).injectInside(document.body);
			this.fix = new OverlayFix(this.choices);
		}
		this.fx = this.choices.effect('opacity', $merge({wait: false, duration: 200}, this.options.fxOptions))
			.addEvent('onStart', function() {
				if (this.fx.now) return;
				this.choices.setStyle('display', '');
				this.fix.show();
			}.bind(this))
			.addEvent('onComplete', function() {
				if (this.fx.now) return;
				this.choices.setStyle('display', 'none');
				this.fix.hide();
			}.bind(this)).set(0);
		this.element.setProperty('autocomplete', 'off')
			.addEvent((window.ie || window.webkit ) ? 'keydown' : 'keypress',
				this.onCommand.bindWithEvent(this))
			.addEvent('mousedown', this.onCommand.bindWithEvent(this, [true]))
			.addEvent('focus', this.toggleFocus.bind(this, [true]))
			.addEvent('blur', this.toggleFocus.bind(this, [false]))
			.addEvent('trash', this.destroy.bind(this));
	},
	
	autoTrim: function(e){
		if(this.userChose) return this.userChose = false;
		var del = this.options.delimeter;
		var val = this.element.getValue();
		if(this.options.autotrim && val.test(del+"$")){
			e = new Event(e);
			this.observer.value = this.element.value = val.substring(0, val.length-del.length);
		}
		return this.observer.value
	},
/*	Property: getQueryValue
		Returns the user's input to use to match against the full list. When options.multi == true, this value is the last entered string after the last index of the delimeter.
		
		Arguments:
		value - (string) optional, the value to clean; defaults to this.observer.value

		Examples:
(start code)
user input: blue
getQueryValue() returns "blue"

user input: blue, green, yellow
options.multi = true
options.delimter = ", "
getQueryValue() returns "yellow"

user input: blue, green, yellow, 
options.multi = true
options.delimter = ", "
getQueryValue() returns ""
(end)
	*/
	getQueryValue: function(value){
		value = $pick(value, this.observer.value);
		return (this.options.multi)?value.lastElement(this.options.delimeter).toString():value||'';
	},
	
/*	Property: destroy
		Remove the autocomplete functionality from the input.
	*/
	destroy: function() {
		this.choices.remove();
	},

	toggleFocus: function(state) {
		this.focussed = state;
		if (!state) this.hideChoices();
	},

	onCommand: function(e, mouse) {
		var val = this.getQueryValue();
		if (mouse && this.focussed) this.prefetch();
		if (e.key) switch (e.key) {
			case 'enter':
				if (this.selected && this.visible) {
					this.choiceSelect(this.selected);
					e.stop();
				} return;
			case 'up': case 'down':
				if (this.getQueryValue() != (val || this.queryValue)) this.prefetch();
				else if (this.queryValue === null) break;
				else if (!this.visible) this.showChoices();
				else {
					this.choiceOver((e.key == 'up')
						? this.selected.getPrevious() || this.choices.getLast()
						: this.selected.getNext() || this.choices.getFirst() );
					this.setSelection();
				}
				e.stop(); return;
			case 'esc': case 'tab': 
				this.hideChoices(); 
				if (this.options.multi) this.element.value = this.element.getValue().trimLastElement();
				return;
		}
		this.value = false;
	},

	setSelection: function() {
		if (!this.options.useSelection) return;
		var del = this.options.delimeter;
		var qVal = this.getQueryValue(this.queryValue);
		var elVal = this.getQueryValue(this.element.getValue());
		var startLength;
		if(this.options.multi)	{
			var index = this.queryValue.lastIndexOf(del);
			var delLength = (index<0)?0:del.length;
			startLength = qVal.length+(index<0?0:index)+delLength;
		} else startLength = qVal.length;

		if (elVal.indexOf(qVal) != 0) return;
		var insert = this.selected.inputValue.substr(startLength);
		if (window.ie) {
			var sel = document.selection.createRange();
			sel.text = insert;
			sel.move("character", - insert.length);
			sel.findText(insert);
			sel.select();
		} else {
			var offset = (this.options.multi && this.element.value.test(del))?
				this.element.getValue().length-elVal.length+qVal.length
				:this.queryValue.length;
			this.element.value = this.element.value.substring(0, offset) + insert;
			this.element.selectionStart = offset;
			this.element.selectionEnd = this.element.value.length;
		}
		this.value = this.observer.value = this.element.value;
	},
/*	Property: hideChoices
		Hides the choices from the user.
	*/
	hideChoices: function() {
		if (!this.visible) return;
		this.visible = this.value = false;
		this.observer.clear();
		this.fx.start(0);
		this.fireEvent('onHide', [this.element, this.choices]);
	},

/*	Property: showChoices
		Shows the choices to the user.
	*/
	showChoices: function() {
		if (this.visible || !this.choices.getFirst()) return;
		this.visible = true;
		var pos = this.element.getCoordinates(this.options.overflown);
		this.choices.setStyles({'left': pos.left, 'top': pos.bottom});
		this.choices.setStyle('width', (this.options.inheritWidth)?pos.width:this.options.dropDownWidth);
		this.fx.start(1);
		this.choiceOver(this.choices.getFirst());
		this.fireEvent('onShow', [this.element, this.choices]);
	},

	prefetch: function() {
		var val = this.getQueryValue(this.element.getValue());
		if (val.length < this.options.minLength) this.hideChoices();
		else if (val == this.queryValue) this.showChoices();
		else this.query();
	},

	updateChoices: function(choices) {
		this.choices.empty();
		this.selected = null;
		if (!choices || !choices.length) return;
		if (this.options.maxChoices < choices.length) choices.length = this.options.maxChoices;
		choices.each(this.options.injectChoice || function(choice, i){
			var el = new Element('li').setHTML(this.markQueryValue(choice));
			el.inputValue = choice;
			this.addChoiceEvents(el).injectInside(this.choices);
		}, this);
		this.showChoices();
	},

	choiceOver: function(el) {
		if (this.selected) this.selected.removeClass('autocompleter-selected');
		this.selected = el.addClass('autocompleter-selected');
	},

	choiceSelect: function(el) {
		if(this.options.multi) {
			var del = this.options.delimeter;
			var value = (this.element.value.trimLastElement(del) + el.inputValue).split(del);
			var fin = [];
			if (!this.options.allowDupes) {
				value.each(function(item){
					if(fin.contains(item))fin.remove(item); //move it to the end
					fin.include(item);
				})
			} else fin = value;
			this.observer.value = this.element.value = fin.join(del)+del;
		} else this.observer.value = this.element.value = el.inputValue;
		
		
		this.hideChoices();
		this.fireEvent('onSelect', [this.element, el.inputValue], 20);
	},

/*	Property: markQueryValue
		Marks the queried word in the given string with <span class="autocompleter-queried">*</span>
		Call this i.e. from your custom parseChoices, same for addChoiceEvents
		
		Arguments:
		txt - (string) the string to mark
	 */
	markQueryValue: function(txt) {
		var val = (this.options.multi)?this.lastQueryElementValue:this.queryValue;
		return (this.options.markQuery && val) ? txt.replace(new RegExp('^(' + val.escapeRegExp() + ')', 'i'), '<span class="autocompleter-queried">$1</span>') : txt;
	},

/*	Property: addChoiceEvents
		Appends the needed event handlers for a choice-entry to the given element.
		
		Arguments:
		el - (DOM element or id) the element to add
*/
	addChoiceEvents: function(el) {
		return el.addEvents({
			'mouseover': this.choiceOver.bind(this, [el]),
			'mousedown': this.choiceSelect.bind(this, [el])
		});
	},
	query: Class.empty
});

Autocompleter.Base.implement(new Events);
Autocompleter.Base.implement(new Options);

/*	Class: OverlayFix
		Private class used by <Autocompleter> - basically an <IframeShim>.
	*/
var OverlayFix = new Class({

	initialize: function(el) {
		this.element = $(el);
		if (window.ie){
			this.element.addEvent('trash', this.destroy.bind(this));
			this.fix = new Element('iframe', {
					'properties': {'frameborder': '0', 'scrolling': 'no', 'src': 'javascript:false;'},
					'styles': {'position': 'absolute', 'border': 'none', 'display': 'none', 'filter': 'progid:DXImageTransform.Microsoft.Alpha(opacity=0)'}})
				.injectAfter(this.element);
		}
	},

	show: function() {
		if (this.fix) this.fix.setStyles($extend(
			this.element.getCoordinates(), {'display': '', 'zIndex': (this.element.getStyle('zIndex') || 1) - 1}));
		return this;
	},

	hide: function() {
		if (this.fix) this.fix.setStyle('display', 'none');
		return this;
	},

	destroy: function() {
		this.fix.remove();
	}

});

String.extend({
	lastElement: function(separator){
		separator = separator || ' ';
		var txt = this; //(separator.test(' $'))?this:this.trim();
		var index = txt.lastIndexOf(separator);
		var result = (index == -1)? txt: txt.substr(index + separator.length, txt.length);
		return result;
	},
 
 
	trimLastElement: function(separator){
		separator = separator || ' ';
		var txt = this; //(separator.test(' $'))?this:this.trim();
		var index = this.lastIndexOf(separator);
		return (index == -1)? "": txt.substr(0, index + separator.length);
	}
});

/* do not edit below this line */   
/* Section: Change Log 

$Source: /cvs/main/flatfile/html/rb/js/global/cnet.global.framework/common/3rdParty/Autocomplete/Autocompleter.js,v $
$Log: Autocompleter.js,v $
Revision 1.7  2008/02/21 20:07:58  newtona
fixing a typo in Autocompleter

Revision 1.6  2007/10/29 18:28:57  newtona
fixed a bug in autocompleter, see: http://forum.mootools.net/viewtopic.php?pid=31481#p31481

Revision 1.5  2007/09/05 18:36:58  newtona
fixing all js warnings in the code base; they weren't breaking anything, but they can create performance issues and it's good practice...

Revision 1.4  2007/08/15 01:03:30  newtona
Added more event info for Autocompleter.js
Slimbox no longer adds css to the page if there aren't any images found for the instance
Iframeshim now exits quietly if you try and position it before the dom is ready
jsonp now handles having more than one request open at a time
removed a console.log statement from window.cnet.js (shame on me for leaving it there)

Revision 1.3  2007/06/12 20:26:52  newtona
*** empty log message ***

Revision 1.2  2007/06/07 18:43:35  newtona
added CSS to autocompleter.js
removed string.cnet.js dependencies from template parser and stickyWin.default.layout.js

Revision 1.1  2007/06/02 01:35:17  newtona
*** empty log message ***


*/
